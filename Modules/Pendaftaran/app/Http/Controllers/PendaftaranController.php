<?php

namespace Modules\Pendaftaran\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Modules\Pendaftaran\Http\Requests\BookRequest;
use Modules\Pendaftaran\Http\Requests\StoreLoanRequest;
use Modules\Pendaftaran\Http\Requests\UpdateLoanRequest;
use Modules\Pendaftaran\Models\Book;
use Modules\Pendaftaran\Models\Loan;
use Symfony\Component\HttpFoundation\Response;

class PendaftaranController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $books = Book::query()->when($search, fn ($query) => $query->where(function ($query) use ($search) {
            $query->where('title', 'like', "%{$search}%")->orWhere('author', 'like', "%{$search}%")
                ->orWhere('publisher', 'like', "%{$search}%");
        }))->orderBy('title')->paginate(10)->withQueryString();
        $loans = Loan::with('book')->when(! auth()->user()->isAdmin(), fn ($query) => $query->where('user_id', auth()->id()))
            ->latest()->paginate(10, ['*'], 'loans_page');
        $allBooks = Book::orderBy('title')->get();

        return view('pendaftaran::index', compact('books', 'loans', 'allBooks', 'search'));
    }

    public function storeBook(BookRequest $request): JsonResponse
    {
        $book = Book::create($request->validated());

        return response()->json(['message' => 'Buku berhasil ditambahkan.', 'book' => $book], 201);
    }

    public function updateBook(BookRequest $request, Book $book): JsonResponse
    {
        $book->update($request->validated());

        return response()->json(['message' => 'Perubahan buku tersimpan.', 'book' => $book]);
    }

    public function destroyBook(Request $request, Book $book): JsonResponse
    {
        abort_unless($request->user()->isAdmin(), 403);
        if ($book->loans()->exists()) {
            return response()->json(['message' => 'Buku memiliki riwayat peminjaman dan tidak dapat dihapus.'], 422);
        }
        $book->delete();

        return response()->json(['message' => 'Buku berhasil dihapus.']);
    }

    public function storeLoan(StoreLoanRequest $request): JsonResponse
    {
        $data = $request->validated();
        $book = Book::findOrFail($data['book_id']);
        if ($book->stock < 1) {
            return response()->json(['message' => 'Stok buku sedang habis.'], 422);
        }

        $data['user_id'] = $request->user()->id;
        $data['status'] = 'Dipinjam';
        if ($request->hasFile('document')) {
            $data['document_path'] = $request->file('document')->store('loan-documents', 'public');
        }
        unset($data['document']);
        $loan = Loan::create($data);
        $book->decrement('stock');

        return response()->json(['message' => 'Peminjaman berhasil diajukan.', 'loan' => $loan->load('book')], 201);
    }

    public function updateLoan(UpdateLoanRequest $request, Loan $loan): JsonResponse
    {
        $data = $request->validated();

        $oldBookId = $loan->book_id;
        $oldStatus = $loan->status;
        $newBookId = (int) $data['book_id'];
        $needsStock = $data['status'] === 'Dipinjam' && ($oldStatus !== 'Dipinjam' || $oldBookId !== $newBookId);
        if ($needsStock && Book::findOrFail($newBookId)->stock < 1) {
            return response()->json(['message' => 'Stok buku pengganti sedang habis.'], 422);
        }
        if ($request->hasFile('document')) {
            if ($loan->document_path) {
                Storage::disk('public')->delete($loan->document_path);
            }
            $data['document_path'] = $request->file('document')->store('loan-documents', 'public');
        } elseif ($request->boolean('remove_document') && $loan->document_path) {
            Storage::disk('public')->delete($loan->document_path);
            $data['document_path'] = null;
        }
        unset($data['document'], $data['remove_document']);
        $loan->update($data);
        if ($oldBookId !== $newBookId) {
            if ($oldStatus === 'Dipinjam') {
                Book::whereKey($oldBookId)->increment('stock');
            }
            if ($loan->status === 'Dipinjam') {
                Book::whereKey($newBookId)->decrement('stock');
            }
        } elseif ($oldStatus !== $loan->status) {
            Book::whereKey($loan->book_id)->increment('stock', $loan->status === 'Dikembalikan' ? 1 : -1);
        }

        return response()->json(['message' => 'Data peminjaman berhasil diperbarui.', 'loan' => $loan->load('book')]);
    }

    public function destroyLoan(Request $request, Loan $loan): JsonResponse
    {
        abort_unless($request->user()->isAdmin(), 403);
        if ($loan->status === 'Dipinjam') {
            Book::whereKey($loan->book_id)->increment('stock');
        }
        if ($loan->document_path) {
            Storage::disk('public')->delete($loan->document_path);
        }
        $loan->delete();

        return response()->json(['message' => 'Peminjaman berhasil dihapus.']);
    }

    public function document(Request $request, Loan $loan): Response
    {
        abort_unless($request->user()->isAdmin() || $loan->user_id === $request->user()->id, 403);
        abort_unless($loan->document_path && Storage::disk('public')->exists($loan->document_path), 404);

        return Storage::disk('public')->response($loan->document_path);
    }
}

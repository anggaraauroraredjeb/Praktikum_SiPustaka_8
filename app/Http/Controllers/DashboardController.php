<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Modules\Pendaftaran\Models\Book;
use Modules\Pendaftaran\Models\Loan;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();

        return view('dashboard', [
            'bookCount' => Book::count(),
            'loanCount' => $user->isAdmin() ? Loan::count() : Loan::where('user_id', $user->id)->count(),
            'recentLoans' => Loan::with('book')->when(! $user->isAdmin(), fn ($query) => $query->where('user_id', $user->id))
                ->latest()->take(5)->get(),
        ]);
    }
}

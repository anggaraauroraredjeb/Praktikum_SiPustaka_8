<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Modules\Pendaftaran\Models\Book;
use Modules\Pendaftaran\Models\Loan;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        $user = auth()->user();

        return Inertia::render('Dashboard', [
            'bookCount' => Book::count(),
            'loanCount' => $user->isAdmin() ? Loan::count() : Loan::where('user_id', $user->id)->count(),
            'recentLoans' => Loan::with('book')->when(! $user->isAdmin(), fn ($query) => $query->where('user_id', $user->id))
                ->latest()->take(5)->get(),
        ]);
    }
}

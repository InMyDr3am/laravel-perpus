<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'books' => Book::count(),
            'members' => Member::count(),
            'active_loans' => Loan::active()->count(),
            'overdue_loans' => Loan::overdue()->count(),
        ];

        $recentLoans = Loan::with(['book', 'member'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recentLoans'));
    }
}

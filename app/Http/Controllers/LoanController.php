<?php

namespace App\Http\Controllers;

use App\Http\Requests\BorrowRequest;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;
use App\Services\LoanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class LoanController extends Controller
{
    public function __construct(private readonly LoanService $loans)
    {
    }

    public function index(Request $request): View
    {
        $loans = Loan::with(['book', 'member'])
            ->when($request->query('filter') === 'active', fn ($q) => $q->active())
            ->when($request->query('filter') === 'overdue', fn ($q) => $q->overdue())
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $books = Book::where('available', '>', 0)->orderBy('title')->get();
        $members = Member::orderBy('name')->get();

        return view('loans.index', compact('loans', 'books', 'members'));
    }

    public function store(BorrowRequest $request): RedirectResponse
    {
        $book = Book::findOrFail($request->integer('book_id'));
        $member = Member::findOrFail($request->integer('member_id'));

        try {
            $this->loans->borrow($book, $member);
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('loans.index')->with('success', 'Peminjaman berhasil dicatat.');
    }

    public function returnLoan(Loan $loan): RedirectResponse
    {
        try {
            $loan = $this->loans->returnLoan($loan);
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        $message = $loan->fine > 0
            ? 'Buku dikembalikan. Denda keterlambatan: Rp'.number_format($loan->fine, 0, ',', '.').'.'
            : 'Buku berhasil dikembalikan tepat waktu.';

        return redirect()->route('loans.index')->with('success', $message);
    }
}

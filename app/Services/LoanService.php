<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class LoanService
{
    /**
     * Pinjamkan sebuah buku kepada anggota.
     *
     * @throws RuntimeException jika stok buku habis.
     */
    public function borrow(Book $book, Member $member): Loan
    {
        return DB::transaction(function () use ($book, $member) {
            // Kunci baris buku agar pengecekan stok aman dari race condition.
            $book = Book::whereKey($book->getKey())->lockForUpdate()->firstOrFail();

            if (! $book->isAvailable()) {
                throw new RuntimeException('Stok buku sedang tidak tersedia.');
            }

            $book->decrement('available');

            $borrowedAt = Carbon::today();

            return Loan::create([
                'book_id' => $book->id,
                'member_id' => $member->id,
                'borrowed_at' => $borrowedAt,
                'due_at' => $borrowedAt->copy()->addDays(config('library.loan_days')),
            ]);
        });
    }

    /**
     * Kembalikan pinjaman: kembalikan stok dan hitung denda keterlambatan.
     *
     * @throws RuntimeException jika pinjaman sudah dikembalikan.
     */
    public function returnLoan(Loan $loan): Loan
    {
        return DB::transaction(function () use ($loan) {
            if ($loan->isReturned()) {
                throw new RuntimeException('Pinjaman ini sudah dikembalikan.');
            }

            $returnedAt = Carbon::today();

            $loan->update([
                'returned_at' => $returnedAt,
                'fine' => $this->calculateFine($loan->due_at, $returnedAt),
            ]);

            $loan->book()->increment('available');

            return $loan;
        });
    }

    /** Denda = jumlah hari terlambat × tarif harian. */
    public function calculateFine(Carbon $dueAt, Carbon $returnedAt): int
    {
        $daysLate = max(0, $dueAt->diffInDays($returnedAt, absolute: false));

        return $daysLate * config('library.fine_per_day');
    }
}

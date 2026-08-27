<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\Member;
use App\Services\LoanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use RuntimeException;
use Tests\TestCase;

class LoanServiceTest extends TestCase
{
    use RefreshDatabase;

    private function makeBook(int $stock = 1): Book
    {
        $category = Category::create(['name' => 'Umum']);

        return Book::create([
            'title' => 'Buku Uji',
            'author' => 'Penulis',
            'category_id' => $category->id,
            'stock' => $stock,
            'available' => $stock,
        ]);
    }

    private function makeMember(): Member
    {
        return Member::create(['code' => 'M0001', 'name' => 'Budi']);
    }

    public function test_borrowing_decrements_availability_and_sets_due_date(): void
    {
        $service = app(LoanService::class);
        $book = $this->makeBook(stock: 2);

        $loan = $service->borrow($book, $this->makeMember());

        $this->assertSame(1, $book->fresh()->available);
        $this->assertEquals(
            Carbon::today()->addDays(config('library.loan_days'))->toDateString(),
            $loan->due_at->toDateString(),
        );
    }

    public function test_borrowing_out_of_stock_book_throws(): void
    {
        $service = app(LoanService::class);
        $book = $this->makeBook(stock: 1);
        $service->borrow($book, $this->makeMember());

        $this->expectException(RuntimeException::class);
        $service->borrow($book, $this->makeMember());
    }

    public function test_returning_restores_stock_and_charges_no_fine_when_on_time(): void
    {
        $service = app(LoanService::class);
        $book = $this->makeBook();
        $loan = $service->borrow($book, $this->makeMember());

        $service->returnLoan($loan);

        $this->assertSame(1, $book->fresh()->available);
        $this->assertSame(0, $loan->fresh()->fine);
        $this->assertNotNull($loan->fresh()->returned_at);
    }

    public function test_returning_late_charges_fine_per_day(): void
    {
        $service = app(LoanService::class);
        $book = $this->makeBook();
        $loan = $service->borrow($book, $this->makeMember());

        // Majukan tanggal jatuh tempo 3 hari ke belakang → terlambat 3 hari.
        $loan->update(['due_at' => Carbon::today()->subDays(3)]);

        $service->returnLoan($loan);

        $this->assertSame(3 * config('library.fine_per_day'), $loan->fresh()->fine);
    }
}

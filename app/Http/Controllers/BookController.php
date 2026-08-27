<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(Request $request): View
    {
        $books = Book::with('category')
            ->when($request->string('search')->trim()->value(), function ($query, $search) {
                $query->where(fn ($q) => $q
                    ->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('books.index', compact('books', 'categories'));
    }

    public function store(BookRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['available'] = $data['stock'];

        Book::create($data);

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function update(BookRequest $request, Book $book): RedirectResponse
    {
        $data = $request->validated();

        // Jaga konsistensi stok tersedia saat total stok diubah.
        $borrowed = $book->stock - $book->available;
        if ($data['stock'] < $borrowed) {
            return back()
                ->withInput()
                ->with('error', "Stok tidak boleh kurang dari jumlah yang sedang dipinjam ({$borrowed}).");
        }
        $data['available'] = $data['stock'] - $borrowed;

        $book->update($data);

        return redirect()->route('books.index')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        if ($book->loans()->exists()) {
            return back()->with('error', 'Buku tidak dapat dihapus karena memiliki riwayat peminjaman.');
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus.');
    }
}

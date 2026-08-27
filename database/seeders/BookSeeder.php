<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            ['Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', '9789793062792', 'Fiksi', 2005, 5],
            ['Bumi Manusia', 'Pramoedya Ananta Toer', 'Lentera Dipantara', '9789799731234', 'Fiksi', 1980, 4],
            ['Sapiens', 'Yuval Noah Harari', 'Harper', '9780062316097', 'Sejarah', 2011, 3],
            ['Atomic Habits', 'James Clear', 'Gramedia', '9786020633176', 'Non-Fiksi', 2018, 6],
            ['Clean Code', 'Robert C. Martin', 'Prentice Hall', '9780132350884', 'Teknologi', 2008, 3],
            ['Pemrograman Laravel', 'Rahmat Hidayat', 'Informatika', '9786025201134', 'Teknologi', 2020, 4],
            ['Filosofi Teras', 'Henry Manampiring', 'Kompas', '9786024812584', 'Non-Fiksi', 2018, 5],
            ['Negeri 5 Menara', 'Ahmad Fuadi', 'Gramedia', '9789792248616', 'Fiksi', 2009, 4],
        ];

        $categories = Category::pluck('id', 'name');

        foreach ($books as [$title, $author, $publisher, $isbn, $category, $year, $stock]) {
            Book::firstOrCreate(
                ['isbn' => $isbn],
                [
                    'title' => $title,
                    'author' => $author,
                    'publisher' => $publisher,
                    'category_id' => $categories[$category] ?? $categories->first(),
                    'year' => $year,
                    'stock' => $stock,
                    'available' => $stock,
                ],
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        $books = [
            ['name' => 'book1', 'author' => 'author1', 'total' => 20],
            ['name' => 'book2', 'author' => 'author2', 'total' => 25],
            ['name' => 'book3', 'author' => 'author3', 'total' => 30],
            ['name' => 'book5', 'author' => 'author4', 'total' => 35],
            ['name' => 'book6', 'author' => 'author5', 'total' => 23],
        ];

        foreach ($books as $book) {
            Book::firstOrCreate([
                'name' => $book['name'],
                'author' => $book['author'],
            ], [
                'total' => $book['total'],
            ]);
        }
    }
}

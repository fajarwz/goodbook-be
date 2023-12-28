<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookGenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bookIds = \App\Models\Book::pluck('id');
        $genreIds = \App\Models\Genre::pluck('id');

        foreach ($bookIds as $bookId) {
            $genreNum = 3;
            for ($i=0; $i < $genreNum; $i++) { 
                \App\Models\BookGenre::create([
                    'book_id' => $bookId,
                    'genre_id' => fake()->randomElement($genreIds),
                ]);
            }
        }
    }
}

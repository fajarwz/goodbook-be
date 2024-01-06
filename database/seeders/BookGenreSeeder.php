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
        $books = \App\Models\Book::all();
        $genreIds = \App\Models\Genre::pluck('id');

        foreach ($books as $book) {
            $genreNum = 3;
            for ($i=0; $i < $genreNum; $i++) { 
                $book->genres()->attach(fake()->randomElement($genreIds));
            }
        }
    }
}

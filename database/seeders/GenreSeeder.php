<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        \App\Models\Genre::insert([
            [
                'name' => 'Art',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Biography',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Comics',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Fantasy',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Fiction',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'History',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Horror',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Sports',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Survival',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Science',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Thriller',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'War',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}

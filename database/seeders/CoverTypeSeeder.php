<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoverTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        \App\Models\CoverType::insert([
            [
                'name' => 'Hardcover',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Paperback',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Leather-bound',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}

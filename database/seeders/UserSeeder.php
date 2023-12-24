<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = 1;
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'role_id' => $admin,
        ]);
        User::factory()->create([
            'name' => 'Fajar WZ',
            'email' => 'fajarwz@test.com',
        ]);
        User::factory()->create([
            'name' => 'Dadang',
            'email' => 'dadang@test.com',
        ]);
        User::factory(10)->create();
    }
}

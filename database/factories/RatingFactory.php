<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rating>
 */
class RatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $memberRole = 2;
        $userIds = \DB::table('users')->where('role_id', $memberRole)->pluck('id');
        $bookIds = \DB::table('books')->pluck('id');
        return [
            'user_id' => fake()->randomElement($userIds),
            'book_id' => fake()->randomElement($bookIds),
            'rating' => rand(1, 5),
        ];
    }
}

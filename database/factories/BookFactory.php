<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BookFactory extends Factory
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

        return [
            'user_id' => fake()->randomElement($userIds),
            'name' => fake()->sentence(),
            'cover' => 'https://dummyimage.com/300x400/000/fff',
            'avg_rating' => 0.00,
            'rater_count' => 0,
        ];
    }
}

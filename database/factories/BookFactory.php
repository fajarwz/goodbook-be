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
        $coverTypes = \App\Models\CoverType::pluck('id');

        return [
            'user_id' => fake()->randomElement($userIds),
            'title' => $title = fake()->sentence(),
            'slug' => \Str::slugger($title),
            'short_description' => fake()->text('250').'...',
            'description' => fake()->text('700'),
            'number_of_pages' => rand(50, 700),
            'cover_type_id' => fake()->randomElement($coverTypes),
            'published_at' => fake()->dateTime(),
            'avg_rating' => 0.00,
            'rater_count' => 0,
            'cover' => 'https://dummyimage.com/375x575/000/fff',
        ];
    }
}

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

        do {
            $uniqueUserId = fake()->randomElement($userIds);
            $uniqueBookId = fake()->randomElement($bookIds);
        } while (\DB::table('ratings')->where(['user_id' => $uniqueUserId, 'book_id' => $uniqueBookId])->exists());

        $newRating = rand(1, 5);

        $book = \DB::table('books')->where('id', $uniqueBookId)->first();

        \DB::table('books')->where('id', $uniqueBookId)->update([
            'avg_rating' => ($book->avg_rating + $newRating) / ($book->rater_count + 1),
            'rater_count' => ($book->rater_count + 1),
        ]);

        return [
            'user_id' => $uniqueUserId,
            'book_id' => $uniqueBookId,
            'rating' => $newRating,
        ];
    }
}

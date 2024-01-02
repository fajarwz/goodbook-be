<?php

namespace App\Http\Controllers\Api\Member;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Member\Review\IndexReviewRequest;
use App\Http\Requests\Api\Member\Review\FormReviewRequest;
use App\Http\Resources\Member\ReviewResource;
use App\Models\Book;
use App\Models\Review;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class ReviewController extends Controller
{
    public function index(IndexReviewRequest $request)
    {
        $reviews = QueryBuilder::for(Review::class)
            ->where('user_id', auth()->id())
            ->allowedFilters(['updated_at', 'rating'])
            ->with(['book'])
            ->paginate($request->paginate ?? 10)
            ->withQueryString();

        return Response::success([
            'reviews' => ReviewResource::collection($reviews)->response()->getData(true),
        ]);
    }

    public function store(FormReviewRequest $request)
    {
        if ($this->isUserAlreadyRateThisBook($request->book_id, auth()->id())) {
            return Response::fail([
                'message' => 'User already rate this book.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);    
        }

        try {
            $review = DB::transaction(function () use ($request) {
                $book = Book::find($request->book_id);
                $book->update([
                    'avg_rating' => ($book->avg_rating + $request->rating) / ($book->rater_count + 1),
                    'rater_count' => ($book->rater_count + 1),
                ]);
    
                return Review::create(array_merge($request->validated(), ['user_id' => auth()->id()]));
            });
        } catch (\Throwable $th) {
            return Response::fail([
                'message' => 'Fail to store data.',
            ]);
        }

        return Response::success([
            'review' => new ReviewResource($review),
        ], Response::HTTP_CREATED);
    }

    public function update(FormReviewRequest $request, Review $review)
    {
        try {
            $review = DB::transaction(function () use ($request, $review) {
                $book = Book::find($review->book_id);

                $previousTotalRating = $book->avg_rating * $book->rater_count;
                $previousTotalRating -= $review->rating;
                $newTotalRating = $previousTotalRating + $request->rating;
                $newAvgRating = $newTotalRating / $book->rater_count;

                $book->update([
                    'avg_rating' => $newAvgRating,
                ]);
    
                return tap($review)->update(array_merge($request->validated(), ['user_id' => auth()->id()]));
            });
        } catch (\Throwable $th) {
            return Response::fail([
                'message' => 'Fail to update data.',
            ]);
        }

        return Response::success([
            'review' => new ReviewResource($review),
        ]);
    }

    public function destroy(Review $review)
    {
        try {
            DB::transaction(function () use ($review) {
                $book = Book::find($review->book_id);

                $previousTotalRating = $book->avg_rating * $book->rater_count;
                $previousTotalRating -= $review->rating;
                $newAvgRating = $previousTotalRating / max(($newRaterCount = $book->rater_count - 1), 1);

                $book->update([
                    'avg_rating' => $newAvgRating,
                    'rater_count' => $newRaterCount,
                ]);
    
                $review->delete();
            });
        } catch (\Throwable $th) {
            return Response::fail([
                'message' => 'Fail to delete data.',
            ]);
        }

        return Response::success([
            'message' => 'Data deleted successfully.',
        ]);
    }

    private function isUserAlreadyRateThisBook($bookId, $userId)
    {
        return Review::where('book_id', $bookId)->where('user_id', $userId)->exists();
    }
}

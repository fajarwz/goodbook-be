<?php

namespace App\Http\Controllers\Api\Member;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Member\Rating\IndexRatingRequest;
use App\Http\Requests\Api\Member\Rating\FormRatingRequest;
use App\Http\Resources\Member\RatingResource;
use App\Models\Book;
use App\Models\Rating;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class RatingController extends Controller
{
    public function index(IndexRatingRequest $request)
    {
        $ratings = QueryBuilder::for(Rating::class)
            ->where('user_id', auth()->id())
            ->allowedFilters(['created_at'])
            ->with(['book'])
            ->paginate($request->paginate ?? 10)
            ->withQueryString();

        return Response::success([
            'ratings' => RatingResource::collection($ratings)->response()->getData(true),
        ]);
    }

    public function store(FormRatingRequest $request)
    {
        if ($this->isUserAlreadyRateThisBook($request->book_id, auth()->id())) {
            return Response::fail([
                'message' => 'User already rate this book.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);    
        }

        try {
            $rating = DB::transaction(function () use ($request) {
                $book = Book::find($request->book_id);
                $book->update([
                    'avg_rating' => ($book->avg_rating + $request->rating) / ($book->rater_count + 1),
                    'rater_count' => ($book->rater_count + 1),
                ]);
    
                return Rating::create(array_merge($request->validated(), ['user_id' => auth()->id()]));
            });
        } catch (\Throwable $th) {
            return Response::fail([
                'message' => 'Fail to store data.',
            ]);
        }

        return Response::success([
            'rating' => new RatingResource($rating),
        ], Response::HTTP_CREATED);
    }

    public function update(FormRatingRequest $request, Rating $rating)
    {
        try {
            $rating = DB::transaction(function () use ($request, $rating) {
                $book = Book::find($rating->book_id);

                $previousTotalRating = $book->avg_rating * $book->rater_count;
                $previousTotalRating -= $rating->rating;
                $newTotalRating = $previousTotalRating + $request->rating;
                $newAvgRating = $newTotalRating / $book->rater_count;

                $book->update([
                    'avg_rating' => $newAvgRating,
                ]);
    
                return tap($rating)->update(array_merge($request->validated(), ['user_id' => auth()->id()]));
            });
        } catch (\Throwable $th) {
            return Response::fail([
                'message' => 'Fail to update data.',
            ]);
        }

        return Response::success([
            'rating' => new RatingResource($rating),
        ]);
    }

    public function destroy(Rating $rating)
    {
        try {
            DB::transaction(function () use ($rating) {
                $book = Book::find($rating->book_id);

                $previousTotalRating = $book->avg_rating * $book->rater_count;
                $previousTotalRating -= $rating->rating;
                $newAvgRating = $previousTotalRating / max(($newRaterCount = $book->rater_count - 1), 1);

                $book->update([
                    'avg_rating' => $newAvgRating,
                    'rater_count' => $newRaterCount,
                ]);
    
                $rating->delete();
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
        return Rating::where('book_id', $bookId)->where('user_id', $userId)->exists();
    }
}

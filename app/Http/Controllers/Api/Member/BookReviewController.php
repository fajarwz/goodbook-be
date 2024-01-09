<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Member\BookReviewRequest;
use App\Http\Resources\Member\BookReviewResource;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookReviewController extends Controller
{
    public function index($bookId, BookReviewRequest $request)
    {
        $reviews = Review::with('user')->where('book_id', $bookId)->orderByDesc('updated_at');

        return Response::success([
            'book_reviews' => BookReviewResource::collection(
                $reviews->paginate($request->paginate ?? 10)->withQueryString()
            )->response()->getData(true),
        ]);
    }

    public function isReviewedByUser($bookId)
    {
        return Response::success([
            'book_reviews_check' => [
                'is_reviewed_by_user' => Review::where('user_id', auth()->id())
                    ->where('book_id', $bookId)
                    ->exists(),
            ],
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\ReviewRequest;
use App\Http\Resources\Admin\ReviewResource;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class ReviewController extends Controller
{
    public function index(ReviewRequest $request)
    {
        $reviews = QueryBuilder::for(Review::class)
            ->orderByDesc('updated_at')
            ->with(['user', 'book']);

        if (isset($request->search)) {
            $reviews->where('review', 'like', "%$request->search%")
            ->orWhereHas('user', function($query) use($request) {
                $query->where('name', 'like', "%$request->search%");
            })
            ->orWhereHas('book', function($query) use($request) {
                $query->where('title', 'like', "%$request->search%");
            });

            if (is_numeric($request->search)) {
                $reviews->orWhere('rating', $request->search);
            }
        }
            
        return Response::success([
            'reviews' => ReviewResource::collection(
                $reviews->paginate($request->paginate ?? 10)->withQueryString()
            )->response()->getData(true),
        ]);
    }
}

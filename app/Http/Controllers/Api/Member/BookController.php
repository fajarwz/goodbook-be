<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Member\Book\IndexBookRequest;
use App\Http\Resources\Member\Book\HomeBookResource;
use App\Http\Resources\Member\Book\IndexBookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BookController extends Controller
{
    public function index(IndexBookRequest $request)
    {
        $books = QueryBuilder::for(Book::class)
            ->with('user')
            ->allowedFilters([AllowedFilter::scope('published_between'), AllowedFilter::scope('ratings')])
            ->orderByDesc('published_at');

        if (isset($request->search)) {
            $books->where('title', 'like', "%$request->search%")
            ->orWhere('short_description', 'like', "%$request->search%")
            ->orWhereHas('user', function($query) use($request) {
                $query->where('name', 'like', "%$request->search%");
            });

            if (is_numeric($request->search)) {
                $books->orWhere('avg_rating', $request->search);
            }
        }

        return Response::success([
            'books' => IndexBookResource::collection(
                $books->paginate($request->paginate ?? 10)->withQueryString()
            )->response()->getData(true),
        ]);
    }

    public function getBest()
    {
        $books = QueryBuilder::for(Book::class)
            ->with(['user'])
            ->orderByDesc('avg_rating')
            ->take(4)
            ->get();

        return Response::success([
            'books' => HomeBookResource::collection($books),
        ]);
    }

    public function getNewest()
    {
        $books = QueryBuilder::for(Book::class)
            ->with(['user'])
            ->orderByDesc('published_at')
            ->take(4)
            ->get();

        return Response::success([
            'books' => HomeBookResource::collection($books),
        ]);
    }
}

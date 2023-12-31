<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\BookRequest;
use App\Http\Resources\Admin\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class BookController extends Controller
{
    public function index(BookRequest $request)
    {
        $books = QueryBuilder::for(Book::class)->with(['user', 'coverType'])->orderByDesc('updated_at');

        if (isset($request->search)) {
            $books->where('title', 'like', "%$request->search%")
            ->orWhere('short_description', 'like', "%$request->search%")
            ->orWhereHas('user', function($query) use($request) {
                $query->where('name', 'like', "%$request->search%");
            })
            ->orWhereHas('coverType', function($query) use($request) {
                $query->where('name', 'like', "%$request->search%");
            });

            if (is_numeric($request->search)) {
                $books->orWhere('number_of_pages', $request->search)
                    ->orWhere('avg_rating', $request->search)
                    ->orWhere('rater_count', $request->search)
                ;
            }
        }

        return Response::success([
            'books' => BookResource::collection(
                $books->paginate($request->paginate ?? 10)->withQueryString()
            )->response()->getData(true),
        ]);
    }
}

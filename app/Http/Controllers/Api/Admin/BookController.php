<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Member\Book\IndexBookRequest;
use App\Http\Resources\Member\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class BookController extends Controller
{
    public function index(IndexBookRequest $request)
    {
        $books = QueryBuilder::for(Book::class);

        if (isset($request->search)) {
            $books->where('name', 'like', "%$request->search%")
                ->orWhere('author', 'like', "%$request->search%");
        }

        return Response::success([
            'books' => 
                BookResource::collection(
                    $books->paginate($request->paginate ?? 10)->withQueryString()
                )->response()->getData(true),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use App\Http\Resources\Member\GenreResource;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class GenreController extends Controller
{
    public function index()
    {
        $genres = QueryBuilder::for(Genre::class)
            ->orderBy('name')
            ->get();

        return Response::success([
            'genres' => GenreResource::collection($genres),
        ]);
    }
}

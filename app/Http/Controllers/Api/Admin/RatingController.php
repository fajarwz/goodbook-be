<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\RatingRequest;
// use App\Http\Resources\Admin\RatingCollection;
use App\Http\Resources\Admin\RatingResource;
use App\Models\Rating;
use App\Support\ResourcePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class RatingController extends Controller
{
    public function index(RatingRequest $request)
    {
        $ratings = QueryBuilder::for(Rating::class)
            ->allowedFilters(['created_at'])
            ->with(['user', 'book'])
            ->paginate($request->paginate ?? 10)
            ->withQueryString();

        return Response::success([
            'ratings' => new ResourcePaginator(RatingResource::collection($ratings)),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\MemberRequest;
use App\Http\Resources\Admin\MemberResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class MemberController extends Controller
{
    public function index(MemberRequest $request)
    {
        $members = QueryBuilder::for(User::class)
            ->where('role_id', Role::MEMBER)
            ->allowedFilters(['created_at'])
            ->paginate($request->paginate ?? 10)
            ->withQueryString();

        return Response::success([
            'users' => MemberResource::collection($members)->response()->getData(true),
        ]);
    }
}
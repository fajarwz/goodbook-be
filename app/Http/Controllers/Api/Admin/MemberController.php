<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\MemberRequest;
use App\Http\Resources\UserResource;
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
            ->orderByDesc('updated_at');

        if (isset($request->search)) {
            $members->where('name', 'like', "%$request->search%")
                ->orWhere('email', 'like', "%$request->search%");
        }

        return Response::success([
            'users' => UserResource::collection(
                $members->paginate($request->paginate ?? 10)->withQueryString()
            )->response()->getData(true),
        ]);
    }
}

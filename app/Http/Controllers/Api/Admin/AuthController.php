<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Traits\Auth;
use App\Http\Requests\Api\Admin\AuthRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\AccessTokenResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    use Auth;

    public function login(AuthRequest $request)
    {
        $attempt = array_merge($request->validated(), [
            'role_id' => function ($query) {
                $query->where('role_id', Role::ADMIN->value);
            }
        ]);

        if (!auth()->attempt($attempt)) {
            return Response::fail([
                'credential' => 'Wrong email or password.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->user::where('email', $request->email)->where('role_id', Role::ADMIN->value)->firstOrFail();
        [$accessToken, $refreshToken, $expiresAt] = $this->generateTokenFromUser($user);

        return Response::success([
            'user' => new UserResource($user),
            'access_token' => new AccessTokenResource((object) [
                'access_token' => $accessToken->plainTextToken,
                'refresh_token' => $refreshToken->plainTextToken,
                'expires_at' => $expiresAt,
            ]),
        ]);
    }
}

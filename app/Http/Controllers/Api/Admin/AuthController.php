<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Api\Traits\Auth;
use App\Http\Requests\Api\Admin\AuthRequest;

class AuthController extends Controller
{
    use Auth;

    public function login(AuthRequest $request)
    {
        $attempt = array_merge($request->validated(), [
            'role' => function ($query) {
                $query->where('role', Role::ADMIN->value);
            }
        ]);

        if (!auth()->attempt($attempt)) {
            return Response::fail([
                'credential' => 'Wrong email or password.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->user::where('email', $request->email)->where('role', Role::ADMIN->value)->firstOrFail();
        [$accessToken, $refreshToken, $expiresAt] = $this->generateTokenFromUser($user);

        return Response::success([
            'user' => $user,
            'access_token' => [
                'access_token' => $accessToken->plainTextToken,
                'refresh_token' => $refreshToken->plainTextToken,
                'expires_at' => $expiresAt,
            ],
        ]);
    }
}

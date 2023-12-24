<?php

namespace App\Http\Controllers\Api\Traits;

use App\Enums\TokenAbility;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\AccessTokenResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;

trait Auth
{
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|string|email:rfc,dns|max:255|unique:users',
            'password' => 'required|string|min:6|max:255|confirmed',
            'password_confirmation' => 'required|string',
        ]);

        $user = $this->user::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => $request['password'],
        ]);

        [$accessToken, $refreshToken, $expiresAt] = $this->generateTokenFromUser($user);

        return Response::success([
            'user' => new UserResource($user),
            'access_token' => new AccessTokenResource((object) [
                'access_token' => $accessToken->plainTextToken,
                'refresh_token' => $refreshToken->plainTextToken,
                'expires_at' => $expiresAt,
            ]),
        ], Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if (!auth()->attempt(array_merge($attr, [
            'role_id' => function ($query) {
                $query->where('role_id', Role::MEMBER->value);
            }
        ]))) {
            return Response::fail([
                'credential' => 'Wrong email or password.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->user::where('email', $request->email)->where('role_id', Role::MEMBER->value)->firstOrFail();
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

    public function logout()
    {
        $invalidate = auth()->user()->tokens()->delete();

        if($invalidate) {
            return Response::success();
        }
    }

    public function refreshToken()
    {
        $user = auth()->user();
        $user->tokens()->delete();
        [$accessToken, $refreshToken, $expiresAt] = $this->generateTokenFromUser($user);

        return Response::success([
            'access_token' => new AccessTokenResource((object) [
                'access_token' => $accessToken->plainTextToken,
                'refresh_token' => $refreshToken->plainTextToken,
                'expires_at' => $expiresAt,
            ]),
        ], Response::HTTP_CREATED);
    }

    private function generateTokenFromUser($user)
    {
        return [
            $user->createToken(
                'access_token', 
                [TokenAbility::ACCESS_API->value], 
                $expiresAt = Carbon::now()->addMinutes(config('sanctum.custom_access_token_expiration'))
            ),
            $user->createToken(
                'refresh_token', 
                [TokenAbility::ISSUE_ACCESS_TOKEN->value], 
                Carbon::now()->addMinutes(config('sanctum.custom_refresh_token_expiration'))
            ),
            $expiresAt,
        ];
    }
}

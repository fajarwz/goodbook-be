<?php

use App\Enums\Role;
use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Member\AuthController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::prefix('member')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware(['auth:sanctum', 'ability:'.TokenAbility::ACCESS_API->value, 'role:'.Role::MEMBER->value])->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', function () {
            return Response::success([
                'user' => auth()->user(),
            ]);
        });
    });
    
    Route::post('refresh-token', [AuthController::class, 'refreshToken'])->middleware([
        'auth:sanctum',
        'ability:'.TokenAbility::ISSUE_ACCESS_TOKEN->value,
        'role:'.Role::MEMBER->value,
    ]);
});
<?php

use App\Enums\Role;
use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\RatingController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware(['auth:sanctum', 'ability:'.TokenAbility::ACCESS_API->value, 'role:'.Role::ADMIN->value])->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', function () {
            return Response::success([
                'user' => auth()->user(),
            ]);
        });
    
        Route::resource('ratings', RatingController::class)->only(['index']);
    });

    Route::post('refresh-token', [AuthController::class, 'refreshToken'])->middleware([
        'auth:sanctum',
        'ability:'.TokenAbility::ISSUE_ACCESS_TOKEN->value,
        'role:'.Role::ADMIN->value,
    ]);
});

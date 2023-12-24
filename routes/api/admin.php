<?php

use App\Enums\Role;
use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\RatingController;
use App\Http\Controllers\Api\Admin\MemberController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware(['auth:sanctum', 'ability:'.TokenAbility::ACCESS_API->value, 'role:'.Role::ADMIN->value])->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);

        Route::apiResource('books', BookController::class)->only(['index']);
        Route::apiResource('members', MemberController::class)->only(['index']);
        Route::apiResource('ratings', RatingController::class)->only(['index']);
    });

    Route::post('refresh-token', [AuthController::class, 'refreshToken'])->middleware([
        'auth:sanctum',
        'ability:'.TokenAbility::ISSUE_ACCESS_TOKEN->value,
        'role:'.Role::ADMIN->value,
    ]);
});

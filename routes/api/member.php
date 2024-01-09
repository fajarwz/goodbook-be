<?php

use App\Enums\Role;
use App\Enums\TokenAbility;
use App\Http\Controllers\Api\Member\AuthController;
use App\Http\Controllers\Api\Member\BookController;
use App\Http\Controllers\Api\Member\BookReviewController;
use App\Http\Controllers\Api\Member\GenreController;
use App\Http\Controllers\Api\Member\ReviewController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::prefix('member')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware(['auth:sanctum', 'ability:'.TokenAbility::ACCESS_API->value, 'role:'.Role::MEMBER->value])->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);

        Route::apiResource('reviews', ReviewController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::get('books/{bookId}/reviews/check', [BookReviewController::class, 'isReviewedByUser']);
    });

    Route::get('books/best', [BookController::class, 'getBest']);
    Route::get('books/newest', [BookController::class, 'getNewest']);
    Route::apiResource('books', BookController::class)->only(['index', 'show'])
        ->parameters(['books' => 'slug']);
    Route::apiResource('genres', GenreController::class)->only(['index']);
    Route::apiResource('books/{bookId}/reviews', BookReviewController::class)->only(['index']);
    
    Route::post('refresh-token', [AuthController::class, 'refreshToken'])->middleware([
        'auth:sanctum',
        'ability:'.TokenAbility::ISSUE_ACCESS_TOKEN->value,
        'role:'.Role::MEMBER->value,
    ]);
});
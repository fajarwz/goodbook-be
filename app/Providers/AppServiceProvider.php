<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Response::macro($status = 'success', function ($data = null, $statusCode = Response::HTTP_OK) use ($status) {
            return response()->json([
                'status' => $status,
                'data' => $data,
            ], $statusCode);
        });
        // CLIENT ERROR
        Response::macro($status = 'fail', function ($data = [], $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY) use ($status) {
            return response()->json([
                'status' => $status,
                'data' => $data,
            ], $statusCode);
        });
        // SERVER ERROR
        Response::macro($status = 'error', function (string $message = '', $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR) use ($status) {
            return response()->json([
                'status' => $status,
                'message' => $message,
            ], $statusCode);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

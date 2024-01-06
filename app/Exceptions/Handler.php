<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function unauthenticated($request, \Illuminate\Auth\AuthenticationException $exception)
    {
        if ($request->wantsJson()) {
            return Response::fail(['message' => 'Unauthenticated'], Response::HTTP_UNAUTHORIZED);
        }

        return parent::render($request, $exception);
    }

    public function render($request, Throwable $exception)
    {
        if ($request->wantsJson()) {
            if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return Response::fail(['message' => 'No data found.'], Response::HTTP_NOT_FOUND);
            }
            elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                return Response::fail(['message' => 'No data found.'], Response::HTTP_NOT_FOUND);
            }
            elseif ($exception instanceof \Illuminate\Validation\ValidationException) {
                return Response::fail($exception->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException) {
                return Response::fail(['message' => 'Access denied.'], Response::HTTP_FORBIDDEN);
            }
            elseif ($exception instanceof \Laravel\Sanctum\Exceptions\MissingAbilityException) {
                return Response::fail(['message' => 'Invalid credential.'], Response::HTTP_FORBIDDEN);
            }
            elseif ($exception instanceof \Illuminate\Database\QueryException) {
                return Response::error('Server error.', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return parent::render($request, $exception);
    }
}

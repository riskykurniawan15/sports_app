<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
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

        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return $this->handleApiException($e, $request);
            }
        });
    }

    /**
     * Handle API exceptions
     */
    private function handleApiException(Throwable $e, Request $request)
    {
        if ($e instanceof ValidationException) {
            return response()->json([
                'code' => [
                    'status' => 422,
                    'message' => 'Validation failed'
                ],
                'data' => null,
                'errors' => $e->errors()
            ], 422);
        }

        if ($e instanceof AuthenticationException) {
            return response()->json([
                'code' => [
                    'status' => 401,
                    'message' => 'Unauthenticated'
                ],
                'data' => null
            ], 401);
        }

        if ($e instanceof TokenExpiredException) {
            return response()->json([
                'code' => [
                    'status' => 401,
                    'message' => 'Token expired'
                ],
                'data' => null
            ], 401);
        }

        if ($e instanceof TokenInvalidException) {
            return response()->json([
                'code' => [
                    'status' => 401,
                    'message' => 'Token invalid'
                ],
                'data' => null
            ], 401);
        }

        if ($e instanceof JWTException) {
            return response()->json([
                'code' => [
                    'status' => 401,
                    'message' => 'Token not provided'
                ],
                'data' => null
            ], 401);
        }

        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'code' => [
                    'status' => 404,
                    'message' => 'Resource not found'
                ],
                'data' => null
            ], 404);
        }

        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'code' => [
                    'status' => 404,
                    'message' => 'Route not found'
                ],
                'data' => null
            ], 404);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'code' => [
                    'status' => 405,
                    'message' => 'Method not allowed'
                ],
                'data' => null
            ], 405);
        }

        // Default error response
        return response()->json([
            'code' => [
                'status' => 500,
                'message' => 'Internal server error'
            ],
            'data' => null
        ], 500);
    }
} 
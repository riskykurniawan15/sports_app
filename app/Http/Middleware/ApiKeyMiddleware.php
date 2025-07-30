<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key');
        $expectedApiKey = config('app.api_key');

        if (!$apiKey || $apiKey !== $expectedApiKey) {
            return response()->json([
                'code' => [
                    'status' => 401,
                    'message' => 'Unauthorized'
                ],
                'data' => null,
                'message' => 'Invalid or missing X-API-Key'
            ], 401);
        }

        return $next($request);
    }
} 
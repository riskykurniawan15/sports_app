<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->headers->set('Accept', 'application/json');
        
        $response = $next($request);
        
        // If response is not JSON and it's an API route, convert to JSON
        // But skip file responses (images, documents, etc.)
        $contentType = $response->headers->get('Content-Type');
        if ($request->is('api/*') && $contentType && !str_contains($contentType, 'application/json')) {
            // Skip file responses (images, documents, etc.)
            if (str_contains($contentType, 'image/') || 
                str_contains($contentType, 'application/pdf') ||
                str_contains($contentType, 'application/octet-stream') ||
                str_contains($contentType, 'text/plain')) {
                return $response;
            }
            
            return response()->json([
                'code' => [
                    'status' => $response->getStatusCode(),
                    'message' => 'Error occurred'
                ],
                'data' => null
            ], $response->getStatusCode());
        }

        // Convert Laravel's default JSON responses to our standardized format
        if ($request->is('api/*') && $contentType && str_contains($contentType, 'application/json')) {
            $responseData = json_decode($response->getContent(), true);
            
            // If it's already in our format, return as is
            if (isset($responseData['code']) && isset($responseData['data'])) {
                return $response;
            }
            
            // Convert Laravel's default format to our standardized format
            if (isset($responseData['message'])) {
                return response()->json([
                    'code' => [
                        'status' => $response->getStatusCode(),
                        'message' => $responseData['message']
                    ],
                    'data' => null
                ], $response->getStatusCode());
            }
        }
        
        return $response;
    }
} 
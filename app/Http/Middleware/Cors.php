<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Cors
{
    /**
     * Very small CORS layer for local dev + static hosting deployments.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $origin = env('CORS_ALLOW_ORIGIN', '*');

        $headers = [
            'Access-Control-Allow-Origin' => $origin,
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, X-Requested-With, X-Cart-Token, Authorization',
            'Access-Control-Max-Age' => '86400',
        ];

        if ($request->getMethod() === 'OPTIONS') {
            $response = response()->noContent();

            foreach ($headers as $key => $value) {
                $response->headers->set($key, $value);
            }

            return $response;
        }

        $response = $next($request);

        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }
}


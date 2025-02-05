<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateWithToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $validToken = env('API_STATIC_TOKEN');
        $token = $request->header('Authorization');

        if (empty($token) || empty($validToken) || $token !== $validToken) {
            return response()->json(['error' => 'Unauthorized. Invalid token or missing token.'], 401);
        }

        return $next($request);
    }
}

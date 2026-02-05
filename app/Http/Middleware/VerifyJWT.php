<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token)
            return response()->json(['error' => 'Token not provided'], 401);


        try {
            $key = env('JWT_SECRET') ?? config('jwt.secret');

            if (!is_string($key) || $key === '')
                abort(500, 'JWT key not configured.');

            $decoded = JWT::decode($token, new Key($key, 'HS256'));

            $request->merge(['user_id' => $decoded->sub]);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }

        return $next($request);
    }
}

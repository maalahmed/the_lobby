<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $allowedType
     */
    public function handle(Request $request, Closure $next, string $allowedType): Response
    {
        $user = $request->user();

        // If no user is authenticated, let auth middleware handle it
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        // Check if user type matches the allowed type
        if ($user->user_type !== $allowedType) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. You do not have permission to access this portal.',
                'error' => 'WRONG_USER_TYPE',
                'user_type' => $user->user_type,
                'required_type' => $allowedType,
            ], 403);
        }

        return $next($request);
    }
}

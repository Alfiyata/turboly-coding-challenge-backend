<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->guard('api')->check()) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
        }

        $request->merge(['user_id' => auth()->guard('api')->user()->id]);
        return $next($request);
    }
}

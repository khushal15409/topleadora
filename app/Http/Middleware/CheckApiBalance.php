<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiBalance
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Super Admin bypass
        if ($user->hasRole(\App\Support\Roles::SUPER_ADMIN)) {
            return $next($request);
        }

        $organization = $user->organization;
        if (!$organization) {
            return $next($request);
        }

        if (!$organization->api_access_enabled) {
            return response()->json(['error' => 'API access is disabled for your organization'], 403);
        }

        if ($organization->wallet_balance <= 0) {
            return response()->json(['error' => 'Insufficient wallet balance. Please top up.'], 402);
        }

        return $next($request);
    }
}

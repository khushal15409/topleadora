<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Support\Roles;

class EnsureApiClient
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Super Admin has full system access — can inspect any area.
        if ($user && $user->hasRole(Roles::SUPER_ADMIN)) {
            return $next($request);
        }

        if (!$user || !$user->hasRole(Roles::API_CLIENT)) {
            abort(403, 'Unauthorized. This area is reserved for API Clients.');
        }

        return $next($request);
    }
}

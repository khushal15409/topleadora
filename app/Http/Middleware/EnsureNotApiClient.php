<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Support\Roles;

class EnsureNotApiClient
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Super Admin always passes through — they are never blocked as an API_CLIENT.
        if ($user && $user->hasRole(Roles::SUPER_ADMIN)) {
            return $next($request);
        }

        if ($user && $user->hasRole(Roles::API_CLIENT)) {
            abort(403, 'API Clients are not allowed to access standard CRM modules.');
        }

        return $next($request);
    }
}

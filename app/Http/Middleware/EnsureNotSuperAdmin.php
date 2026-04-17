<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Support\Roles;

class EnsureNotSuperAdmin
{
    /**
     * Block Super Admin from accessing certain role-scoped areas.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->hasRole(Roles::SUPER_ADMIN)) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}


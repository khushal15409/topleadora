<?php

namespace App\Http\Middleware;

use App\Support\Roles;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganizationOnboardingComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null || $user->hasRole(Roles::SUPER_ADMIN)) {
            return $next($request);
        }

        if (! $user->hasRole(Roles::ORGANIZATION)) {
            return $next($request);
        }

        $user->loadMissing('organization');

        if ($user->organization === null) {
            return $next($request);
        }

        if (! $user->organization->requiresOnboarding()) {
            return $next($request);
        }

        if ($request->routeIs('admin.onboarding', 'admin.onboarding.store')) {
            return $next($request);
        }

        return redirect()->route('admin.onboarding');
    }
}

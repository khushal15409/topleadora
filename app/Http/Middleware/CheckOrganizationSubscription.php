<?php

namespace App\Http\Middleware;

use App\Support\Roles;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOrganizationSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        // Global FREE mode: no subscription/trial restrictions.
        if (! paymentEnabled()) {
            return $next($request);
        }

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

        if ($user->organization->hasFullCrmAccess()) {
            return $next($request);
        }

        if ($request->routeIs(
            'admin.subscription.*',
            'admin.organization.plan',
            'admin.checkout',
        )) {
            return $next($request);
        }

        return redirect()
            ->route('admin.subscription.pricing', ['expired' => 1])
            ->with('trial_expired', true);
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Support\Roles;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SyncOrganizationSubscriptionExpiry
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! paymentEnabled()) {
            return $next($request);
        }

        $user = $request->user();

        if ($user instanceof User && $user->hasRole(Roles::ORGANIZATION)) {
            $user->loadMissing('organization');
            $user->organization?->syncExpiredSubscriptions();
        }

        return $next($request);
    }
}

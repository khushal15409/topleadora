<?php

namespace App\Http\Middleware;

use App\Support\Roles;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganizationAccountActive
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

        if ($user->organization_id === null || $user->organization === null) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors(['email' => __('Your account is not linked to an organization. Contact support.')]);
        }

        if (! $user->organization->isActiveAccount()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors(['email' => __('Your account is inactive. Contact support.')]);
        }

        return $next($request);
    }
}

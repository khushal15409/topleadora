<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Roles;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();
        abort_unless($user && $user->hasRole(Roles::ORGANIZATION), 403);

        $user->loadMissing('organization');
        $organization = $user->organization;
        abort_if($organization === null, 403);

        return view('admin.onboarding.show', [
            'organization' => $organization,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user && $user->hasRole(Roles::ORGANIZATION), 403);

        $user->loadMissing('organization');
        $organization = $user->organization;
        abort_if($organization === null, 403);

        $validated = $request->validate([
            'mobile_number' => ['required', 'string', 'max:32', 'regex:/^[0-9+\s().-]{8,22}$/'],
            'organization_name' => ['required', 'string', 'max:255'],
        ]);

        $organization->update([
            'mobile_number' => $validated['mobile_number'],
            'name' => $validated['organization_name'],
            'onboarding_completed' => true,
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', __('Welcome! Your workspace is ready.'));
    }
}

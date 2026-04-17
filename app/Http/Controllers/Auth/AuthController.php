<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use App\Support\Roles;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => __('The provided credentials do not match our records.'),
            ])->onlyInput('email');
        }

        $user = Auth::user();

        if ($user->hasRole(Roles::ORGANIZATION)) {
            $user->loadMissing('organization');

            if ($user->organization_id === null || $user->organization === null) {
                Auth::logout();

                return back()
                    ->withErrors(['email' => __('Your account is not linked to an organization. Contact support.')])
                    ->onlyInput('email');
            }

            if (!$user->organization->isActiveAccount()) {
                Auth::logout();

                return back()
                    ->withErrors(['email' => __('Your account is inactive. Contact support.')])
                    ->onlyInput('email');
            }
        }

        $request->session()->regenerate();

        if ($user->hasRole(Roles::API_CLIENT)) {
            return redirect()->intended(route('dashboard.api.overview'));
        }

        return redirect()->intended(route('admin.dashboard'));
    }

    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'organization_name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
            'account_type' => ['required', 'in:crm,api'],
        ]);

        $user = DB::transaction(function () use ($validated) {
            $isApiType = $validated['account_type'] === 'api';

            $organization = Organization::query()->create([
                'name' => $validated['organization_name'],
                'slug' => Organization::uniqueSlugFromName($validated['organization_name']),
                'status' => Organization::STATUS_ACTIVE,
                'plan_id' => null,
                'trial_ends_at' => now()->addDays(7),
                'is_trial' => true,
                'mobile_number' => null,
                'onboarding_completed' => false,
                'api_access_enabled' => $isApiType,
                'wallet_balance' => $isApiType ? 0.00 : 0.00,
            ]);

            $user = User::query()->create([
                'organization_id' => $organization->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);

            if ($isApiType) {
                // Production safety: ensure required role exists before assignment.
                Role::findOrCreate(Roles::API_CLIENT, 'web');
                $user->syncRoles([Roles::API_CLIENT]);
            } else {
                Role::findOrCreate(Roles::ORGANIZATION, 'web');
                Role::findOrCreate(Roles::ORG_ADMIN, 'web');
                $user->syncRoles([Roles::ORGANIZATION, Roles::ORG_ADMIN]);
            }

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        if ($user->hasRole(Roles::API_CLIENT)) {
            return redirect()->route('dashboard.api.overview');
        }

        return redirect()
            ->route('admin.dashboard')
            ->with('track_google_conversion', true);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Support\Roles;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function pricing(Request $request): View
    {
        abort_if(! paymentEnabled(), 404);

        $user = $request->user();
        abort_unless($user && $user->hasRole(Roles::ORGANIZATION), 403);

        $plans = Plan::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $showExpiredOverlay = $request->boolean('expired');

        if ($request->session()->pull('trial_expired', false)) {
            $showExpiredOverlay = true;
        }

        return view('admin.subscription.pricing', [
            'plans' => $plans,
            'showExpiredOverlay' => $showExpiredOverlay,
        ]);
    }

    public function organizationPlan(Request $request): View
    {
        abort_if(! paymentEnabled(), 404);

        $user = $request->user();
        abort_unless($user && $user->hasRole(Roles::ORGANIZATION), 403);

        $user->loadMissing('organization.plan');
        $organization = $user->organization;
        abort_if($organization === null, 403);

        $organization->syncExpiredSubscriptions();

        $plans = Plan::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $activeSub = $organization->activeSubscription();
        $trialOn = $organization->trialIsActive();

        if ($activeSub !== null) {
            $uiStatus = 'active';
        } elseif ($trialOn) {
            $uiStatus = 'trial';
        } else {
            $uiStatus = 'expired';
        }

        $trialDaysLeft = null;
        if ($trialOn && $organization->trial_ends_at?->isFuture()) {
            $trialDaysLeft = max(
                0,
                (int) Carbon::today()->diffInDays($organization->trial_ends_at->copy()->startOfDay())
            );
        }

        $currentPlanModel = match ($uiStatus) {
            'active' => $activeSub?->plan,
            'expired' => $organization->plan,
            default => null,
        };

        $suggestedUpgrade = $plans->firstWhere('slug', 'professional')
            ?? $plans->get(1)
            ?? $plans->first();

        $renewTarget = $activeSub?->plan;
        $activateTarget = $organization->plan ?? $plans->first();

        return view('admin.subscription.organization-plan', [
            'organization' => $organization,
            'plans' => $plans,
            'activeSubscription' => $activeSub,
            'uiStatus' => $uiStatus,
            'trialDaysLeft' => $trialDaysLeft,
            'currentPlanModel' => $currentPlanModel,
            'suggestedUpgrade' => $suggestedUpgrade,
            'renewTarget' => $renewTarget,
            'activateTarget' => $activateTarget,
        ]);
    }

    public function checkout(Request $request, Plan $plan): View
    {
        abort_if(! paymentEnabled(), 404);

        $user = $request->user();
        abort_unless($user && $user->hasRole(Roles::ORGANIZATION), 403);
        abort_unless($plan->is_active, 404);

        return view('admin.subscription.checkout', [
            'plan' => $plan,
        ]);
    }

    /**
     * Checkout by numeric plan id (e.g. /admin/checkout/2).
     */
    public function checkoutById(Request $request, string $plan): View
    {
        abort_if(! paymentEnabled(), 404);

        $model = Plan::query()
            ->whereKey((int) $plan)
            ->where('is_active', true)
            ->firstOrFail();

        return $this->checkout($request, $model);
    }

    public function activate(Request $request, Plan $plan): RedirectResponse
    {
        abort_if(! paymentEnabled(), 404);

        $user = $request->user();
        abort_unless($user && $user->hasRole(Roles::ORGANIZATION), 403);
        abort_unless($plan->is_active, 404);

        $user->loadMissing('organization');
        $organization = $user->organization;
        abort_if($organization === null, 403);

        $organization->activateSubscription($plan);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', __('Your subscription is active. Enjoy full CRM access.'))
            ->with('track_google_conversion', true);
    }

    /**
     * @return array<string, mixed>
     */
    public static function planDisplayMeta(Plan $plan): array
    {
        $display = config('pricing_plans', []);

        return array_merge(
            $display['_default'] ?? [],
            $display[$plan->slug] ?? []
        );
    }
}

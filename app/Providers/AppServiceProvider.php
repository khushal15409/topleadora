<?php

namespace App\Providers;

use App\Models\Broadcast;
use App\Models\Lead;
use App\Models\User;
use App\Policies\BroadcastPolicy;
use App\Policies\LeadPolicy;
use App\Support\Roles;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('Support/helpers.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Gate::policy(Lead::class, LeadPolicy::class);
        Gate::policy(Broadcast::class, BroadcastPolicy::class);

        Gate::before(function (?User $user, string $ability, array $arguments = []) {
            if ($user === null || $ability === '') {
                return null;
            }

            $subject = $arguments[0] ?? null;
            if ($subject === Lead::class || $subject instanceof Lead) {
                return null;
            }
            if ($subject === Broadcast::class || $subject instanceof Broadcast) {
                return null;
            }

            return $user->hasRole(Roles::SUPER_ADMIN) ? true : null;
        });

        View::composer('layouts.admin', function (\Illuminate\View\View $view): void {
            $user = auth()->user();
            $orgCrmLocked = false;
            $showPlanExpiredBanner = false;
            $freeAccessMode = ! paymentEnabled();

            if ($user instanceof User && $user->hasRole(Roles::ORGANIZATION)) {
                $user->loadMissing('organization');
                if ($user->organization !== null) {
                    if (! $freeAccessMode) {
                        $orgCrmLocked = $user->organization->trialExpiredWithoutPlan();
                        $showPlanExpiredBanner = $user->organization->shouldShowPlanExpiredBanner();
                    }
                }
            }

            $view->with('orgCrmLocked', $orgCrmLocked);
            $view->with('showPlanExpiredBanner', $showPlanExpiredBanner);
            $view->with('freeAccessMode', $freeAccessMode);
        });
    }
}

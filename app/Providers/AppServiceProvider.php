<?php

namespace App\Providers;

use App\Models\Broadcast;
use App\Models\LandingPage;
use App\Models\Lead;
use App\Models\LeadLandingPage;
use App\Models\LeadNiche;
use App\Models\User;
use App\Policies\BroadcastPolicy;
use App\Policies\LeadPolicy;
use App\Support\Roles;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
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

        View::composer('layouts.leads', function (\Illuminate\View\View $view): void {
            if (! Schema::hasTable('lead_niches')) {
                $view->with('leadNavItems', collect());

                return;
            }

            $items = collect();
            if (Schema::hasTable('landing_pages') && LandingPage::query()->where('is_active', true)->exists()) {
                $seenServiceIds = [];
                $items = LandingPage::query()
                    ->activeOrdered()
                    ->with(['service', 'country'])
                    ->get()
                    ->filter(function (LandingPage $l) use (&$seenServiceIds) {
                        if ($l->service === null || $l->country === null) {
                            return false;
                        }
                        if (isset($seenServiceIds[$l->service_id])) {
                            return false;
                        }
                        $seenServiceIds[$l->service_id] = true;

                        return true;
                    })
                    ->map(fn (LandingPage $l) => [
                        'url' => route('leads.landing', $l->slug),
                        'label' => $l->service->name,
                    ])
                    ->values();
            } elseif (Schema::hasTable('lead_landing_pages') && LeadLandingPage::query()->where('is_active', true)->exists()) {
                $seenNicheIds = [];
                $items = LeadLandingPage::query()
                    ->activeOrdered()
                    ->with('niche')
                    ->get()
                    ->filter(function (LeadLandingPage $l) use (&$seenNicheIds) {
                        $niche = $l->niche;
                        if ($niche === null) {
                            return false;
                        }
                        if (isset($seenNicheIds[$niche->id])) {
                            return false;
                        }
                        $seenNicheIds[$niche->id] = true;

                        return true;
                    })
                    ->map(fn (LeadLandingPage $l) => [
                        'url' => route('leads.landing', $l->slug),
                        'label' => $l->niche->label,
                    ])
                    ->values();
            }

            if ($items->isEmpty()) {
                $items = LeadNiche::query()
                    ->activeOrdered()
                    ->get(['slug', 'label'])
                    ->map(fn (LeadNiche $n) => [
                        'url' => route('leads.landing', $n->slug),
                        'label' => $n->label,
                    ]);
            }

            $view->with('leadNavItems', $items);
        });
    }
}

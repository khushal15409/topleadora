<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class PagesController extends Controller
{
    /**
     * @return \Illuminate\Support\Collection<int, \App\Models\Plan>
     */
    private function activePricingPlans()
    {
        return Cache::remember('active_plans', 300, static function () {
            return Plan::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();
        });
    }

    public function features(): View
    {
        return view('pages.features');
    }

    public function pricing(): View
    {
        return view('pages.pricing');
    }

    public function otpApiService(): View
    {
        $pricingPlans = $this->activePricingPlans();

        return view('pages.api-service', compact('pricingPlans'));
    }

    public function whatsappCrm(): View
    {
        $pricingPlans = $this->activePricingPlans();

        return view('pages.whatsapp-crm', compact('pricingPlans'));
    }

    public function whatsappApi(): View
    {
        $pricingPlans = $this->activePricingPlans();

        return view('pages.whatsapp-api', compact('pricingPlans'));
    }

    public function leadGeneration(): View
    {
        $pricingPlans = $this->activePricingPlans();

        return view('pages.lead-generation', compact('pricingPlans'));
    }
}

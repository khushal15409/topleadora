<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PagesController extends Controller
{
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
        $pricingPlans = \Illuminate\Support\Facades\Cache::remember('active_plans', 300, static function () {
            return \App\Models\Plan::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();
        });

        return view('pages.api-service', compact('pricingPlans'));
    }
}

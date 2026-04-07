<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Public Landify-style landing page.
     */
    public function index(): View
    {
        // 300s TTL: run `php artisan cache:forget active_plans` after changing plans in admin if you need instant refresh.
        $pricingPlans = Cache::remember('active_plans', 300, static function () {
            return Plan::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();
        });

        return view('welcome', compact('pricingPlans'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Public Landify-style landing page.
     */
    public function index(): View
    {
        $pricingPlans = Plan::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('welcome', compact('pricingPlans'));
    }
}

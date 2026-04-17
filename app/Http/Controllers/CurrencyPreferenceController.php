<?php

namespace App\Http\Controllers;

use App\Services\CurrencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CurrencyPreferenceController extends Controller
{
    public function update(Request $request, CurrencyService $currencyService): RedirectResponse
    {
        $data = $request->validate([
            'currency_code' => ['nullable', 'string', 'max:3'],
            'country_code' => ['nullable', 'string', 'max:2'],
        ]);

        $currency = isset($data['currency_code']) ? strtoupper(trim((string) $data['currency_code'])) : null;
        $country = isset($data['country_code']) ? strtoupper(trim((string) $data['country_code'])) : null;

        if ($currency !== null && $currency !== '' && !$currencyService->isValidCurrencyCode($currency)) {
            return back()->with('error', __('Invalid currency.'));
        }
        if ($country !== null && $country !== '' && !preg_match('/^[A-Z]{2}$/', $country)) {
            return back()->with('error', __('Invalid country.'));
        }

        // Store in session for immediate effect
        if ($currency !== null && $currency !== '') {
            $request->session()->put('currency_code', $currency);
        } else {
            $request->session()->forget('currency_code');
        }
        if ($country !== null && $country !== '') {
            $request->session()->put('country_code', $country);
        }

        // Persist preference for logged-in users (optional override)
        if ($request->user()) {
            $request->user()->forceFill([
                'currency_code' => $currency,
                'country_code' => $country,
            ])->save();
        }

        return back()->with('success', __('Currency preference updated.'));
    }
}


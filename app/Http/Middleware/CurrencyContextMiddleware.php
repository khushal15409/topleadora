<?php

namespace App\Http\Middleware;

use App\Services\CurrencyService;
use App\Services\GeoCurrencyService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class CurrencyContextMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $currencyService = app(CurrencyService::class);

        $sessionCurrency = strtoupper((string) $request->session()->get('currency_code', ''));
        $sessionCountry = strtoupper((string) $request->session()->get('country_code', ''));

        $currency = $user?->currency_code ? strtoupper((string) $user->currency_code) : $sessionCurrency;
        $country = $user?->country_code ? strtoupper((string) $user->country_code) : $sessionCountry;

        if (!$currencyService->isValidCurrencyCode($currency)) {
            $currency = '';
        }

        if ($currency === '') {
            $detected = app(GeoCurrencyService::class)->detect(
                $request->ip(),
                $request->header('Accept-Language')
            );

            $currency = strtoupper((string) ($detected['currency_code'] ?? $currencyService->defaultDisplayCurrency()));
            $country = strtoupper((string) ($detected['country_code'] ?? $country));
            if (!$currencyService->isValidCurrencyCode($currency)) {
                $currency = $currencyService->defaultDisplayCurrency();
            }

            $request->session()->put('currency_code', $currency);
            if ($country !== '') {
                $request->session()->put('country_code', $country);
            }
        }

        $ctx = [
            'base_currency' => $currencyService->baseCurrency(),
            'currency_code' => $currency,
            'country_code' => $country !== '' ? $country : null,
        ];

        View::share('currencyContext', $ctx);

        return $next($request);
    }
}


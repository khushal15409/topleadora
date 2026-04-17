<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CurrencyService
{
    public function baseCurrency(): string
    {
        return strtoupper((string) config('currency.base', 'INR'));
    }

    public function defaultDisplayCurrency(): string
    {
        return strtoupper((string) config('currency.default_display', $this->baseCurrency()));
    }

    /**
     * @return array{base:string, rates: array<string, float>, fetched_at:int|null}
     */
    public function rates(): array
    {
        $ttl = (int) config('currency.rates.cache_ttl_seconds', 60 * 60 * 12);
        $cacheKey = 'currency:rates:' . $this->baseCurrency();

        /** @var array{base:string, rates: array<string, float>, fetched_at:int|null}|null $cached */
        $cached = Cache::get($cacheKey);
        if (is_array($cached) && isset($cached['base'], $cached['rates'])) {
            return $cached;
        }

        $payload = $this->fetchRatesFromProvider();
        Cache::put($cacheKey, $payload, $ttl);

        return $payload;
    }

    /**
     * Convert an INR amount to display currency using cached rates.
     */
    public function convertFromBase(float $amountBase, string $toCurrency): float
    {
        $to = strtoupper(trim($toCurrency));
        $base = $this->baseCurrency();

        if ($to === '' || $to === $base) {
            return $amountBase;
        }

        $rates = $this->rates()['rates'] ?? [];
        $rate = (float) ($rates[$to] ?? 0.0);
        if ($rate <= 0) {
            return $amountBase;
        }

        return $amountBase * $rate;
    }

    /**
     * Convert a display amount to INR (base). Used only for showing "you see X, we charge INR Y".
     */
    public function convertToBase(float $amount, string $fromCurrency): float
    {
        $from = strtoupper(trim($fromCurrency));
        $base = $this->baseCurrency();

        if ($from === '' || $from === $base) {
            return $amount;
        }

        $rates = $this->rates()['rates'] ?? [];
        $rate = (float) ($rates[$from] ?? 0.0);
        if ($rate <= 0) {
            return $amount;
        }

        return $amount / $rate;
    }

    public function isValidCurrencyCode(?string $code): bool
    {
        if ($code === null) {
            return false;
        }
        $c = strtoupper(trim($code));
        if (!preg_match('/^[A-Z]{3}$/', $c)) {
            return false;
        }

        if ($c === $this->baseCurrency()) {
            return true;
        }

        $rates = $this->rates()['rates'] ?? [];
        return array_key_exists($c, $rates);
    }

    /**
     * Format a base (INR) amount in the target currency for display.
     */
    public function formatFromBase(
        float $amountBase,
        string $toCurrency,
        ?string $locale = null,
        int $maxFractionDigits = 2
    ): string {
        $to = strtoupper(trim($toCurrency));
        $base = $this->baseCurrency();

        $displayAmount = $this->convertFromBase($amountBase, $to);
        $code = ($to === '') ? $base : $to;

        $locale = $locale ?: app()->getLocale();

        if (class_exists(\NumberFormatter::class)) {
            $fmt = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
            $fmt->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $maxFractionDigits);
            $out = $fmt->formatCurrency($displayAmount, $code);
            if (is_string($out) && $out !== '') {
                return $out;
            }
        }

        // Fallback: "USD 12.34"
        return $code . ' ' . number_format($displayAmount, min(2, $maxFractionDigits));
    }

    /**
     * @return array{base:string, rates: array<string, float>, fetched_at:int|null}
     */
    private function fetchRatesFromProvider(): array
    {
        $provider = (string) config('currency.rates.provider', 'open_er_api');
        $base = $this->baseCurrency();

        try {
            if ($provider === 'open_er_api') {
                $url = (string) config('currency.rates.open_er_api_url');
                $timeout = (int) config('currency.rates.timeout_seconds', 6);
                $resp = Http::timeout($timeout)->acceptJson()->get($url);
                $json = $resp->json();

                if (is_array($json) && ($json['result'] ?? '') === 'success' && is_array($json['rates'] ?? null)) {
                    /** @var array<string, float|int|string> $raw */
                    $raw = $json['rates'];
                    $rates = [];
                    foreach ($raw as $code => $rate) {
                        $rates[strtoupper((string) $code)] = (float) $rate;
                    }

                    // Ensure base is always present
                    $rates[$base] = 1.0;

                    return [
                        'base' => $base,
                        'rates' => $rates,
                        'fetched_at' => time(),
                    ];
                }
            }
        } catch (\Throwable) {
            // fall through to static fallback
        }

        // Static fallback: base only (no conversion).
        return [
            'base' => $base,
            'rates' => [
                $base => 1.0,
            ],
            'fetched_at' => null,
        ];
    }
}


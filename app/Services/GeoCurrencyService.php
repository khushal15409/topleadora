<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GeoCurrencyService
{
    /**
     * @return array{country_code: string|null, currency_code: string|null, source: string}
     */
    public function detect(?string $ip, ?string $acceptLanguage): array
    {
        $base = app(CurrencyService::class)->baseCurrency();

        $geo = $this->detectByIp($ip);
        if ($geo['country_code'] !== null || $geo['currency_code'] !== null) {
            $geo['currency_code'] = $geo['currency_code'] ?: $this->currencyFromCountry($geo['country_code']) ?: $base;
            return $geo;
        }

        $fallbackCountry = $this->countryFromAcceptLanguage($acceptLanguage);
        return [
            'country_code' => $fallbackCountry,
            'currency_code' => $this->currencyFromCountry($fallbackCountry) ?: $base,
            'source' => 'accept_language',
        ];
    }

    /**
     * @return array{country_code: string|null, currency_code: string|null, source: string}
     */
    private function detectByIp(?string $ip): array
    {
        $ip = $ip ? trim($ip) : '';
        if ($ip === '') {
            return ['country_code' => null, 'currency_code' => null, 'source' => 'ip:none'];
        }

        $skipPrivate = (bool) config('currency.geo.skip_private_ips', true);
        if ($skipPrivate && $this->isPrivateIp($ip)) {
            return ['country_code' => null, 'currency_code' => null, 'source' => 'ip:private'];
        }

        $ttl = (int) config('currency.geo.cache_ttl_seconds', 60 * 60 * 12);
        $cacheKey = 'currency:geo:' . $ip;

        /** @var array{country_code?:string|null,currency_code?:string|null}|null $cached */
        $cached = Cache::get($cacheKey);
        if (is_array($cached)) {
            return [
                'country_code' => isset($cached['country_code']) ? strtoupper((string) $cached['country_code']) : null,
                'currency_code' => isset($cached['currency_code']) ? strtoupper((string) $cached['currency_code']) : null,
                'source' => 'ip:cache',
            ];
        }

        $provider = (string) config('currency.geo.provider', 'ipapi_co');
        $timeout = (int) config('currency.geo.timeout_seconds', 3);

        try {
            if ($provider === 'ipapi_co') {
                $baseUrl = rtrim((string) config('currency.geo.ipapi_co_base_url', 'https://ipapi.co'), '/');
                $url = $baseUrl . '/' . $ip . '/json/';

                $resp = Http::timeout($timeout)->acceptJson()->get($url);
                $json = $resp->json();

                if (is_array($json)) {
                    $country = isset($json['country_code']) ? strtoupper((string) $json['country_code']) : null;
                    $currency = isset($json['currency']) ? strtoupper((string) $json['currency']) : null;

                    Cache::put($cacheKey, ['country_code' => $country, 'currency_code' => $currency], $ttl);

                    return [
                        'country_code' => $country,
                        'currency_code' => $currency,
                        'source' => 'ip:ipapi',
                    ];
                }
            }
        } catch (\Throwable) {
            // ignore
        }

        return ['country_code' => null, 'currency_code' => null, 'source' => 'ip:failed'];
    }

    private function currencyFromCountry(?string $countryCode): ?string
    {
        if ($countryCode === null || trim($countryCode) === '') {
            return null;
        }

        $cc = strtoupper(trim($countryCode));
        $map = (array) config('currency.country_currency', []);
        $cur = $map[$cc] ?? null;

        return $cur ? strtoupper((string) $cur) : null;
    }

    private function countryFromAcceptLanguage(?string $acceptLanguage): ?string
    {
        if ($acceptLanguage === null || trim($acceptLanguage) === '') {
            return null;
        }

        // Example: "en-US,en;q=0.9"
        $first = explode(',', $acceptLanguage)[0] ?? '';
        $first = trim($first);
        if ($first === '') {
            return null;
        }

        $parts = preg_split('/[-_]/', $first) ?: [];
        $region = $parts[1] ?? null;
        if ($region && preg_match('/^[A-Za-z]{2}$/', $region)) {
            return strtoupper($region);
        }

        return null;
    }

    private function isPrivateIp(string $ip): bool
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return preg_match('/^(10\\.|127\\.|172\\.(1[6-9]|2[0-9]|3[0-1])\\.|192\\.168\\.)/', $ip) === 1;
        }
        // For IPv6, treat loopback/link-local as private for our purposes.
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return str_starts_with($ip, '::1') || str_starts_with(strtolower($ip), 'fe80:') || str_starts_with(strtolower($ip), 'fc') || str_starts_with(strtolower($ip), 'fd');
        }

        return true;
    }
}


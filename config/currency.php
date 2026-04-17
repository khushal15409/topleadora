<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Base currency (storage currency)
    |--------------------------------------------------------------------------
    |
    | All amounts stored/calculated in backend MUST be in this currency.
    | Display currency may differ per user/session.
    |
    */
    'base' => env('BASE_CURRENCY', 'INR'),

    /*
    |--------------------------------------------------------------------------
    | Default display currency
    |--------------------------------------------------------------------------
    */
    'default_display' => env('DEFAULT_DISPLAY_CURRENCY', 'INR'),

    /*
    |--------------------------------------------------------------------------
    | Exchange rate provider
    |--------------------------------------------------------------------------
    |
    | Provider returns rates with base=INR.
    | You can swap this without touching application logic.
    |
    | Example endpoints:
    | - https://open.er-api.com/v6/latest/INR
    |
    */
    'rates' => [
        'provider' => env('CURRENCY_RATES_PROVIDER', 'open_er_api'),
        'open_er_api_url' => env('CURRENCY_OPEN_ER_API_URL', 'https://open.er-api.com/v6/latest/INR'),
        'cache_ttl_seconds' => (int) env('CURRENCY_RATES_CACHE_TTL', 60 * 60 * 12), // 12 hours
        'timeout_seconds' => (int) env('CURRENCY_RATES_TIMEOUT', 6),
    ],

    /*
    |--------------------------------------------------------------------------
    | Geo detection (IP)
    |--------------------------------------------------------------------------
    */
    'geo' => [
        'provider' => env('CURRENCY_GEO_PROVIDER', 'ipapi_co'),
        'ipapi_co_base_url' => env('CURRENCY_IPAPI_CO_URL', 'https://ipapi.co'),
        'cache_ttl_seconds' => (int) env('CURRENCY_GEO_CACHE_TTL', 60 * 60 * 12),
        'timeout_seconds' => (int) env('CURRENCY_GEO_TIMEOUT', 3),
        // If true, private/local IPs will skip external geo lookup.
        'skip_private_ips' => env('CURRENCY_GEO_SKIP_PRIVATE_IPS', '1') === '1',
    ],

    /*
    |--------------------------------------------------------------------------
    | Country -> currency mapping (fallback)
    |--------------------------------------------------------------------------
    |
    | This is a lightweight mapping used when geo provider gives a country code
    | but not a currency, or when falling back from locale.
    |
    */
    'country_currency' => [
        'IN' => 'INR',
        'US' => 'USD',
        'AE' => 'AED',
        'GB' => 'GBP',
        'EU' => 'EUR',
        'SA' => 'SAR',
        'CA' => 'CAD',
        'AU' => 'AUD',
        'SG' => 'SGD',
    ],
];


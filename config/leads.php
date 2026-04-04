<?php

return [

    'default_organization_id' => env('LANDING_DEFAULT_ORGANIZATION_ID'),

    'default_meta_robots' => env('LEADS_META_ROBOTS', 'index,follow'),

    /*
    |--------------------------------------------------------------------------
    | Google Search Console site verification (meta tag content value)
    |--------------------------------------------------------------------------
    */
    'google_site_verification' => env('GOOGLE_SITE_VERIFICATION'),

    /*
    |--------------------------------------------------------------------------
    | Google Analytics 4 — Measurement ID (G-XXXXXXXX)
    |--------------------------------------------------------------------------
    */
    'ga4_measurement_id' => env('GA4_MEASUREMENT_ID'),

    /*
    |--------------------------------------------------------------------------
    | JSON-LD / LocalBusiness (optional — used when all address parts set)
    |--------------------------------------------------------------------------
    */
    'schema' => [
        'business_name' => env('LEADS_SCHEMA_BUSINESS_NAME'),
        'url' => env('LEADS_SCHEMA_URL'),
        'phone' => env('LEADS_SCHEMA_PHONE'),
        'street' => env('LEADS_SCHEMA_STREET'),
        'locality' => env('LEADS_SCHEMA_LOCALITY'),
        'region' => env('LEADS_SCHEMA_REGION'),
        'postal_code' => env('LEADS_SCHEMA_POSTAL'),
        'country' => env('LEADS_SCHEMA_COUNTRY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Global fallback when hero / section images fail to load
    |--------------------------------------------------------------------------
    */
    'image_fallback_url' => env(
        'LEADS_IMAGE_FALLBACK_URL',
        'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=1200&q=80'
    ),

    /*
    |--------------------------------------------------------------------------
    | Default hero photograph (finance / business) when a page has no hero_image
    |--------------------------------------------------------------------------
    */
    'hero_default_image_url' => env(
        'LEADS_HERO_DEFAULT_IMAGE_URL',
        'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?auto=format&fit=crop&w=1920&q=80'
    ),

];

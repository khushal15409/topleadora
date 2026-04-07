<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Force https:// URLs (canonical, sitemap, OG). Enable in production.
    |--------------------------------------------------------------------------
    */
    'force_https' => env('FORCE_HTTPS', false),

    /*
    |--------------------------------------------------------------------------
    | Default social / Open Graph image (absolute URL path via asset())
    |--------------------------------------------------------------------------
    */
    'default_og_image' => env('SEO_DEFAULT_OG_IMAGE', 'front/images/landify/illustration/illustration-15.webp'),

    /*
    |--------------------------------------------------------------------------
    | Title suffix for pages that pass a short title only (optional)
    |--------------------------------------------------------------------------
    */
    'title_suffix' => env('SEO_TITLE_SUFFIX', ' | '.(env('APP_NAME', 'WhatsAppLeadCRM'))),

    /*
    |--------------------------------------------------------------------------
    | Max meta description length (chars) for auto-truncation
    |--------------------------------------------------------------------------
    */
    'description_max_length' => (int) env('SEO_DESCRIPTION_MAX', 160),

];

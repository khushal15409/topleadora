<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Favicon (global)
    |--------------------------------------------------------------------------
    |
    | Path relative to the public/ directory. Used on all layouts (landing,
    | auth, admin, onboarding). Override with BRANDING_FAVICON in .env.
    |
    */

    'favicon' => env('BRANDING_FAVICON', 'front/images/landify/favicon.png'),

    /*
    |--------------------------------------------------------------------------
    | Public support email (marketing footer, contact snippets)
    |--------------------------------------------------------------------------
    |
    | Replace with real business data in production. Set SUPPORT_EMAIL in .env.
    |
    */
    'support_email' => env('SUPPORT_EMAIL'),

];

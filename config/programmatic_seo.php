<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Virtual city landing pages (/leads/{service-slug}-{city-slug})
    |--------------------------------------------------------------------------
    | Parsed by ProgrammaticLeadResolver when no DB landing row matches the
    | slug. Keeps one URL shape: hyphenated pair is NOT a second route — it is
    | the same /leads/{slug} with structured resolution.
    */
    'enabled' => (bool) env('PROGRAMMATIC_LEADS_ENABLED', true),

    'title_pattern' => env(
        'PROGRAMMATIC_LEADS_TITLE_PATTERN',
        'Apply :service in :city | Free consultation'
    ),

    'description_pattern' => env(
        'PROGRAMMATIC_LEADS_DESC_PATTERN',
        'Apply for :service in :city, India. Secure form, quick callback, no spam. Licensed partners may reach out as per regulations.'
    ),

    'hero_cta' => env('PROGRAMMATIC_LEADS_HERO_CTA', 'Apply now in 2 minutes'),

    /*
    |--------------------------------------------------------------------------
    | Major Indian cities (slug used in URLs, label for copy)
    |--------------------------------------------------------------------------
    */
    'india_cities' => [
        ['slug' => 'mumbai', 'label' => 'Mumbai'],
        ['slug' => 'delhi', 'label' => 'Delhi'],
        ['slug' => 'bangalore', 'label' => 'Bangalore'],
        ['slug' => 'hyderabad', 'label' => 'Hyderabad'],
        ['slug' => 'ahmedabad', 'label' => 'Ahmedabad'],
        ['slug' => 'chennai', 'label' => 'Chennai'],
        ['slug' => 'kolkata', 'label' => 'Kolkata'],
        ['slug' => 'pune', 'label' => 'Pune'],
        ['slug' => 'jaipur', 'label' => 'Jaipur'],
        ['slug' => 'surat', 'label' => 'Surat'],
        ['slug' => 'lucknow', 'label' => 'Lucknow'],
        ['slug' => 'kanpur', 'label' => 'Kanpur'],
        ['slug' => 'nagpur', 'label' => 'Nagpur'],
        ['slug' => 'indore', 'label' => 'Indore'],
        ['slug' => 'thane', 'label' => 'Thane'],
        ['slug' => 'bhopal', 'label' => 'Bhopal'],
        ['slug' => 'visakhapatnam', 'label' => 'Visakhapatnam'],
        ['slug' => 'patna', 'label' => 'Patna'],
        ['slug' => 'vadodara', 'label' => 'Vadodara'],
        ['slug' => 'ghaziabad', 'label' => 'Ghaziabad'],
        ['slug' => 'ludhiana', 'label' => 'Ludhiana'],
        ['slug' => 'coimbatore', 'label' => 'Coimbatore'],
        ['slug' => 'kochi', 'label' => 'Kochi'],
        ['slug' => 'chandigarh', 'label' => 'Chandigarh'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Sitemap hints (Google treats these as hints, not strict rules)
    |--------------------------------------------------------------------------
    */
    'sitemap' => [
        'home_priority' => '1.0',
        'main_priority' => '0.85',
        'blog_priority' => '0.70',
        'leads_priority' => '0.90',
        'post_priority' => '0.65',
        'default_changefreq' => 'weekly',
        'home_changefreq' => 'daily',
        'blog_changefreq' => 'weekly',
        'leads_changefreq' => 'weekly',
    ],

];

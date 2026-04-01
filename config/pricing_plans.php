<?php

/**
 * Display copy for plan cards (matches public landing). Keyed by Plan.slug.
 */
return [
    '_default' => [
        'badge_title' => null,
        'plan_tagline' => '',
        'title' => 'Full CRM access',
        'subtitle' => 'All core CRM features after activation.',
        'features' => [
            'Leads & pipeline',
            'WhatsApp inbox',
            'Team collaboration',
        ],
        'featured' => false,
    ],

    'starter' => [
        'badge_title' => null,
        'plan_tagline' => 'Solopreneurs & first-line sellers',
        'title' => 'Get started fast',
        'subtitle' => 'Core inbox, pipeline, and reminders to organize WhatsApp leads.',
        'features' => [
            '1,000 leads',
            '1 WhatsApp number',
            'Email support',
        ],
        'featured' => false,
    ],

    'professional' => [
        'badge_title' => 'Pro',
        'plan_tagline' => 'Growing teams that need scale',
        'title' => 'Best for active sales teams',
        'subtitle' => 'Higher limits, broadcasts, analytics, and collaboration.',
        'features' => [
            '10,000 leads',
            '3 WhatsApp numbers',
            'Broadcasts & scheduling',
            'Priority support',
        ],
        'featured' => true,
    ],

    'enterprise' => [
        'badge_title' => 'Business',
        'plan_tagline' => 'High volume & multi-branch',
        'title' => 'Scale without chaos',
        'subtitle' => 'Custom limits, advanced workflows, and dedicated guidance.',
        'features' => [
            'Unlimited leads (fair use)',
            'Multi-workspace',
            'API-ready',
            'Success manager',
        ],
        'featured' => false,
    ],
];

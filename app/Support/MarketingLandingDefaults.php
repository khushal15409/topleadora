<?php

namespace App\Support;

class MarketingLandingDefaults
{
    /**
     * @return array<string, mixed>
     */
    public static function shell(): array
    {
        return [
            'hero_headline' => '',
            'hero_subheadline' => '',
            'hero_cta' => __('Get free quote'),
            'hero_image' => '',
            'hero_image_fallback' => config('leads.image_fallback_url'),
            'hero_image_alt' => '',
            'og_image' => '',
            'trust_badge' => '',
            'rating_label' => '',
            'benefits' => [],
            'how_steps' => [],
            'testimonials' => [],
            'faqs' => [],
            'final_cta_title' => '',
            'final_cta_text' => '',
            'final_cta_button' => '',
            'section_trust_image' => config('leads.image_fallback_url'),
            'trust_image_fallback' => config('leads.image_fallback_url'),
            'how_image' => config('leads.image_fallback_url'),
            'how_image_fallback' => config('leads.image_fallback_url'),
            'footer_disclaimer' => null,
            'aggregate_rating_value' => null,
            'aggregate_rating_count' => null,
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeadNiche extends Model
{
    protected $fillable = [
        'slug',
        'label',
        'is_active',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'hero_headline',
        'hero_subheadline',
        'hero_cta',
        'hero_image_url',
        'hero_image_fallback_url',
        'hero_image_alt',
        'og_image_url',
        'trust_section_image_url',
        'how_section_image_url',
        'trust_badge',
        'rating_label',
        'benefits',
        'how_steps',
        'testimonials',
        'faqs',
        'final_cta_title',
        'final_cta_text',
        'final_cta_button',
        'footer_disclaimer',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'benefits' => 'array',
            'how_steps' => 'array',
            'testimonials' => 'array',
            'faqs' => 'array',
        ];
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeActiveOrdered(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('label');
    }

    /**
     * @return HasMany<LeadLandingPage, $this>
     */
    public function landingPages(): HasMany
    {
        return $this->hasMany(LeadLandingPage::class, 'lead_niche_id');
    }

    /**
     * @return array<string, mixed>
     */
    public function toPageArray(): array
    {
        return [
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description ?? '',
            'meta_keywords' => $this->meta_keywords ?? '',
            'hero_headline' => $this->hero_headline,
            'hero_subheadline' => $this->hero_subheadline ?? '',
            'hero_image' => $this->hero_image_url ?? '',
            'hero_image_fallback' => $this->hero_image_fallback_url ?? config('leads.image_fallback_url'),
            'hero_image_alt' => $this->hero_image_alt ?? $this->label,
            'og_image' => $this->og_image_url ?? $this->hero_image_url ?? config('leads.image_fallback_url'),
            'hero_cta' => $this->hero_cta,
            'trust_badge' => $this->trust_badge ?? '',
            'rating_label' => $this->rating_label ?? '',
            'benefits' => is_array($this->benefits) ? $this->benefits : [],
            'how_steps' => is_array($this->how_steps) ? $this->how_steps : [],
            'testimonials' => is_array($this->testimonials) ? $this->testimonials : [],
            'faqs' => is_array($this->faqs) ? $this->faqs : [],
            'final_cta_title' => $this->final_cta_title ?? '',
            'final_cta_text' => $this->final_cta_text ?? '',
            'final_cta_button' => $this->final_cta_button ?? $this->hero_cta,
            'section_trust_image' => $this->trust_section_image_url ?? config('leads.image_fallback_url'),
            'trust_image_fallback' => $this->hero_image_fallback_url ?? config('leads.image_fallback_url'),
            'how_image' => $this->how_section_image_url ?? config('leads.image_fallback_url'),
            'how_image_fallback' => $this->hero_image_fallback_url ?? config('leads.image_fallback_url'),
            'footer_disclaimer' => $this->footer_disclaimer,
        ];
    }
}

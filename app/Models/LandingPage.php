<?php

namespace App\Models;

use App\Support\MarketingLandingDefaults;
use App\Support\SeoMeta;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LandingPage extends Model
{
    protected $fillable = [
        'service_id',
        'country_id',
        'slug',
        'city_slug',
        'city_label',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'robots_meta',
        'seo_body',
        'content_json',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'content_json' => 'array',
        ];
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeActiveOrdered(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('slug');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Merge stored JSON blocks with meta for public Blade components.
     *
     * @return array<string, mixed>
     */
    public function toPublicPageArray(): array
    {
        $service = $this->service;
        $defaults = is_array($service?->default_content_json) ? $service->default_content_json : [];
        $content = is_array($this->content_json) ? $this->content_json : [];
        $base = array_replace_recursive(MarketingLandingDefaults::shell(), $defaults, $content);

        $base['meta_title'] = $this->meta_title;
        $base['meta_description'] = $this->meta_description ?? '';
        $base['meta_keywords'] = $this->meta_keywords ?? '';
        $base['robots_meta'] = $this->robots_meta ?: 'index,follow';
        $base['seo_body'] = $this->seo_body ?? '';
        $base['landing_slug'] = $this->slug;
        $base['niche_slug'] = $service?->slug ?? '';
        $base['niche_label'] = $service?->name ?? '';
        $location = array_filter([
            $this->country?->name,
            filled($this->city_label) ? $this->city_label : null,
        ]);
        $base['location_label'] = $location !== [] ? implode(', ', $location) : ($this->country?->name ?? '');
        $base['location_slug'] = $this->country?->url_slug ?? '';

        // Auto meta description when empty (programmatic SEO / city landings).
        if (! filled(trim((string) $base['meta_description']))) {
            $base['meta_description'] = SeoMeta::fallbackForMarketingLanding($this)['description'];
        }

        // Stronger default CTA for conversion when JSON omits hero_cta.
        if (empty(trim((string) ($base['hero_cta'] ?? '')))) {
            $base['hero_cta'] = __('Apply now — 2 minutes, secure form');
        }

        foreach ([
            'hero_image',
            'hero_image_fallback',
            'og_image',
            'section_trust_image',
            'trust_image_fallback',
            'how_image',
            'how_image_fallback',
        ] as $imgKey) {
            if (! empty($base[$imgKey]) && is_string($base[$imgKey])) {
                $base[$imgKey] = leadPublicImageUrl($base[$imgKey]);
            }
        }

        return $base;
    }
}

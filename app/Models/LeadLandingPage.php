<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadLandingPage extends Model
{
    protected $fillable = [
        'lead_niche_id',
        'slug',
        'location_slug',
        'location_label',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'robots_meta',
        'hero_headline_override',
        'hero_subheadline_override',
        'seo_body',
        'aggregate_rating_value',
        'aggregate_rating_count',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'aggregate_rating_value' => 'float',
            'aggregate_rating_count' => 'integer',
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

    public function niche(): BelongsTo
    {
        return $this->belongsTo(LeadNiche::class, 'lead_niche_id');
    }

    /**
     * Merge niche defaults with location-specific SEO and media.
     *
     * @return array<string, mixed>
     */
    public function toPageArray(): array
    {
        $niche = $this->niche;
        if ($niche === null) {
            return [];
        }

        $base = $niche->toPageArray();

        $base['meta_title'] = $this->meta_title ?: $base['meta_title'];
        $base['meta_description'] = $this->meta_description ?? $base['meta_description'];
        $base['meta_keywords'] = $this->meta_keywords ?? $base['meta_keywords'];
        $base['robots_meta'] = $this->robots_meta ?: 'index,follow';

        if ($this->hero_headline_override) {
            $base['hero_headline'] = $this->hero_headline_override;
        }
        if ($this->hero_subheadline_override) {
            $base['hero_subheadline'] = $this->hero_subheadline_override;
        }

        $base['seo_body'] = $this->seo_body ?? '';
        $base['location_label'] = $this->location_label;
        $base['location_slug'] = $this->location_slug;
        $base['landing_slug'] = $this->slug;
        $base['niche_slug'] = $niche->slug;
        $base['niche_label'] = $niche->label;
        $base['aggregate_rating_value'] = $this->aggregate_rating_value;
        $base['aggregate_rating_count'] = $this->aggregate_rating_count;

        return $base;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketingLead extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'service_id',
        'country_id',
        'country_code',
        'country_name',
        'city',
        'landing_page_id',
        'source_page',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'extra',
    ];

    protected function casts(): array
    {
        return [
            'extra' => 'array',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function landingPage(): BelongsTo
    {
        return $this->belongsTo(LandingPage::class);
    }
}

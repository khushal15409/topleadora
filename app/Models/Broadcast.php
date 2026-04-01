<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Broadcast extends Model
{
    protected $fillable = [
        'organization_id',
        'user_id',
        'message',
        'send_to_all',
        'lead_ids',
        'total_recipients',
        'sent_count',
        'failed_count',
        'last_error',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'send_to_all' => 'boolean',
            'lead_ids' => 'array',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeForOrganization(Builder $query, int $organizationId): Builder
    {
        return $query->where('organization_id', $organizationId);
    }

    public function messagePreview(int $length = 80): string
    {
        return str($this->message)->limit($length)->toString();
    }
}

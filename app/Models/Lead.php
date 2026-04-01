<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    /** @use HasFactory<\Database\Factories\LeadFactory> */
    use HasFactory;

    public const STATUS_NEW = 'new';

    public const STATUS_CONTACTED = 'contacted';

    public const STATUS_INTERESTED = 'interested';

    public const STATUS_FOLLOW_UP = 'follow_up';

    public const STATUS_CLOSED = 'closed';

    public const SOURCE_WHATSAPP = 'whatsapp';

    public const SOURCE_INSTAGRAM = 'instagram';

    public const SOURCE_FACEBOOK = 'facebook';

    public const SOURCE_WEBSITE = 'website';

    public const SOURCE_REFERRAL = 'referral';

    public const SOURCE_OTHER = 'other';

    protected $fillable = [
        'organization_id',
        'assigned_to',
        'name',
        'email',
        'phone',
        'status',
        'source',
        'notes',
        'next_followup_at',
        'followup_completed_at',
    ];

    protected function casts(): array
    {
        return [
            'next_followup_at' => 'datetime',
            'followup_completed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Lead $lead): void {
            $lead->status = self::normalizeStatus($lead->status);
        });
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * @return array<string, string>
     */
    public static function statusOptions(): array
    {
        return [
            self::STATUS_NEW => __('New'),
            self::STATUS_CONTACTED => __('Contacted'),
            self::STATUS_INTERESTED => __('Interested'),
            self::STATUS_FOLLOW_UP => __('Follow-up'),
            self::STATUS_CLOSED => __('Closed'),
        ];
    }

    /**
     * Pipeline / Kanban column order
     *
     * @return list<string>
     */
    public static function pipelineStages(): array
    {
        return [
            self::STATUS_NEW,
            self::STATUS_CONTACTED,
            self::STATUS_INTERESTED,
            self::STATUS_FOLLOW_UP,
            self::STATUS_CLOSED,
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function sourceOptions(): array
    {
        return [
            self::SOURCE_WHATSAPP => __('WhatsApp'),
            self::SOURCE_INSTAGRAM => __('Instagram'),
            self::SOURCE_FACEBOOK => __('Facebook'),
            self::SOURCE_WEBSITE => __('Website'),
            self::SOURCE_REFERRAL => __('Referral'),
            self::SOURCE_OTHER => __('Other'),
        ];
    }

    public function statusLabel(): string
    {
        return self::statusOptions()[$this->status] ?? $this->status;
    }

    public function sourceLabel(): string
    {
        return $this->source
            ? (self::sourceOptions()[$this->source] ?? $this->source)
            : '—';
    }

    /**
     * Normalize legacy DB values
     */
    public static function normalizeStatus(?string $status): string
    {
        $status = $status ?: self::STATUS_NEW;
        if ($status === 'qualified') {
            return self::STATUS_INTERESTED;
        }

        return in_array($status, self::pipelineStages(), true) ? $status : self::STATUS_NEW;
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeForOrganization(Builder $query, int $organizationId): Builder
    {
        return $query->where('organization_id', $organizationId);
    }

    /**
     * Leads visible to this user (tenant + assignment rules).
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        $query->forOrganization((int) $user->organization_id);
        if (! $user->canViewAllOrganizationLeads()) {
            $query->where('assigned_to', $user->id);
        }

        return $query;
    }
}

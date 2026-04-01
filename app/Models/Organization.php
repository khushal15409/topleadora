<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Organization extends Model
{
    /** @use HasFactory<\Database\Factories\OrganizationFactory> */
    use HasFactory;

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'name',
        'slug',
        'status',
        'plan_id',
        'trial_ends_at',
        'is_trial',
        'mobile_number',
        'onboarding_completed',
    ];

    protected function casts(): array
    {
        return [
            'trial_ends_at' => 'datetime',
            'is_trial' => 'boolean',
            'onboarding_completed' => 'boolean',
        ];
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function broadcasts(): HasMany
    {
        return $this->hasMany(Broadcast::class);
    }

    public static function uniqueSlugFromName(string $name): string
    {
        $base = Str::slug($name) ?: 'organization';
        $slug = $base;
        $i = 1;
        while (static::query()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }

    public function requiresOnboarding(): bool
    {
        return $this->mobile_number === null
            || $this->mobile_number === ''
            || ! $this->onboarding_completed;
    }

    public function activeSubscription(): ?Subscription
    {
        return $this->subscriptions()
            ->where('status', Subscription::STATUS_ACTIVE)
            ->whereDate('end_date', '>=', Carbon::today())
            ->orderByDesc('end_date')
            ->first();
    }

    public function hasPaidPlan(): bool
    {
        return $this->activeSubscription() !== null;
    }

    public function trialIsActive(): bool
    {
        if ($this->activeSubscription() !== null) {
            return false;
        }

        if ($this->trial_ends_at === null) {
            return false;
        }

        return Carbon::now()->lt($this->trial_ends_at);
    }

    public function trialExpiredWithoutPlan(): bool
    {
        return ! $this->hasFullCrmAccess();
    }

    public function hasFullCrmAccess(): bool
    {
        if (! paymentEnabled()) {
            return true;
        }

        return $this->trialIsActive() || $this->hasPaidPlan();
    }

    public function syncExpiredSubscriptions(): void
    {
        $this->subscriptions()
            ->where('status', Subscription::STATUS_ACTIVE)
            ->whereDate('end_date', '<', Carbon::today())
            ->update(['status' => Subscription::STATUS_EXPIRED]);
    }

    public function shouldShowPlanExpiredBanner(): bool
    {
        if ($this->trialIsActive() || $this->hasFullCrmAccess()) {
            return false;
        }

        return $this->subscriptions()->exists();
    }

    public function isActiveAccount(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function activateSubscription(Plan $plan): void
    {
        $this->subscriptions()
            ->where('status', Subscription::STATUS_ACTIVE)
            ->update(['status' => Subscription::STATUS_EXPIRED]);

        $this->forceFill([
            'plan_id' => $plan->id,
            'is_trial' => false,
            'trial_ends_at' => null,
        ])->save();

        $start = Carbon::today();
        $end = Carbon::today()->addDays(30);

        $subscription = $this->subscriptions()->create([
            'plan_id' => $plan->id,
            'start_date' => $start,
            'end_date' => $end,
            'status' => Subscription::STATUS_ACTIVE,
        ]);

        Payment::query()->create([
            'organization_id' => $this->id,
            'plan_id' => $plan->id,
            'subscription_id' => $subscription->id,
            'amount' => $plan->price_monthly,
            'currency' => $plan->currency ?? 'INR',
            'status' => Payment::STATUS_SUCCESS,
            'paid_at' => now(),
        ]);
    }

    /**
     * Activate subscription using an existing Payment row (e.g. Razorpay verified).
     */
    public function activateSubscriptionFromPayment(Plan $plan, Payment $payment): Subscription
    {
        $this->subscriptions()
            ->where('status', Subscription::STATUS_ACTIVE)
            ->update(['status' => Subscription::STATUS_EXPIRED]);

        $this->forceFill([
            'plan_id' => $plan->id,
            'is_trial' => false,
            'trial_ends_at' => null,
        ])->save();

        $start = Carbon::today();
        $end = Carbon::today()->addDays(30);

        $subscription = $this->subscriptions()->create([
            'plan_id' => $plan->id,
            'start_date' => $start,
            'end_date' => $end,
            'status' => Subscription::STATUS_ACTIVE,
        ]);

        $payment->forceFill([
            'subscription_id' => $subscription->id,
            'plan_id' => $plan->id,
            'organization_id' => $this->id,
        ])->save();

        return $subscription;
    }
}

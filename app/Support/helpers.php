<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

if (! function_exists('setting')) {
    /**
     * Read a setting from DB (cached as a map).
     */
    function setting(string $key, mixed $default = null): mixed
    {
        // During early install / fresh test DBs, migrations may not have run yet.
        if (! Schema::hasTable('settings')) {
            return $default;
        }

        /** @var array<string, mixed> $all */
        $all = Cache::remember('settings:all', now()->addSeconds(60), function (): array {
            // Use Eloquent so encrypted casts are properly decrypted.
            return Setting::query()
                ->get(['key', 'value'])
                ->mapWithKeys(fn (Setting $s) => [(string) $s->key => $s->value])
                ->all();
        });

        return array_key_exists($key, $all) ? ($all[$key] ?? $default) : $default;
    }
}

if (! function_exists('isPaymentEnabled')) {
    /**
     * Global monetization toggle.
     *
     * - When OFF: platform runs in FREE mode (no trial/subscription restrictions).
     * - When ON: normal paid SaaS mode.
     */
    function isPaymentEnabled(): bool
    {
        // Default to ON to preserve existing behavior if setting is missing.
        $val = setting('payment_enabled', '1');

        return (string) $val === '1' || $val === 1 || $val === true;
    }
}

if (! function_exists('paymentEnabled')) {
    /**
     * Alias used across blades/controllers for billing toggle.
     */
    function paymentEnabled(): bool
    {
        return isPaymentEnabled();
    }
}


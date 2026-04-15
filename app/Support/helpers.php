<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

if (!function_exists('setting')) {
    /**
     * Read a setting from DB (cached as a map).
     */
    function setting(string $key, mixed $default = null): mixed
    {
        // During early install / fresh test DBs, migrations may not have run yet.
        if (!Schema::hasTable('settings')) {
            return $default;
        }

        /** @var array<string, mixed> $all */
        $all = Cache::remember('settings:all', now()->addSeconds(60), function (): array {
            // Use Eloquent so encrypted casts are properly decrypted.
            return Setting::query()
                ->get(['key', 'value'])
                ->mapWithKeys(fn(Setting $s) => [(string) $s->key => $s->value])
                ->all();
        });

        return array_key_exists($key, $all) ? ($all[$key] ?? $default) : $default;
    }
}

if (!function_exists('isPaymentEnabled')) {
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

if (!function_exists('leadImageFallbackUrl')) {
    /**
     * Global fallback URL when hero / section images fail.
     */
    function leadImageFallbackUrl(): string
    {
        return (string) config('leads.image_fallback_url');
    }
}

if (!function_exists('leadLocalPlaceholderImageUrl')) {
    /**
     * Local placeholder used as the final onerror target when remote images fail.
     */
    function leadLocalPlaceholderImageUrl(): string
    {
        return asset('front/images/leads-placeholder.svg');
    }
}

if (!function_exists('leadPublicImageUrl')) {
    /**
     * Normalize image URLs for marketing pages (full URL, storage path, or relative asset).
     */
    function leadPublicImageUrl(?string $url): string
    {
        if ($url === null || trim((string) $url) === '') {
            return '';
        }

        $u = trim((string) $url);
        if (str_starts_with($u, 'http://') || str_starts_with($u, 'https://')) {
            return $u;
        }
        if (str_starts_with($u, '//')) {
            return 'https:' . $u;
        }

        $u = ltrim($u, '/');
        if (str_starts_with($u, 'public/')) {
            $u = substr($u, strlen('public/'));
        }

        return asset($u);
    }
}

if (!function_exists('leadResponsiveSrcset')) {
    /**
     * Build a srcset for Unsplash URLs (width variants). Returns empty string if not Unsplash.
     */
    function leadResponsiveSrcset(?string $url): string
    {
        if ($url === null || $url === '' || !str_contains($url, 'images.unsplash.com')) {
            return '';
        }

        $parsed = parse_url($url);
        if (!is_array($parsed) || !isset($parsed['host'], $parsed['path'])) {
            return '';
        }

        $scheme = $parsed['scheme'] ?? 'https';
        $basePath = $scheme . '://' . $parsed['host'] . $parsed['path'];
        $widths = [640, 960, 1280, 1920];
        $parts = [];
        foreach ($widths as $w) {
            $parts[] = $basePath . '?auto=format&fit=crop&w=' . $w . '&q=80 ' . $w . 'w';
        }

        return implode(', ', $parts);
    }
}

if (!function_exists('paymentEnabled')) {
    /**
     * Alias used across blades/controllers for billing toggle.
     */
    function paymentEnabled(): bool
    {
        return isPaymentEnabled();
    }
}

if (!function_exists('isCrmPaymentEnabled')) {
    /**
     * Whether CRM subscription billing (plans, Razorpay checkout) is active.
     * Reads the 'payment_enabled' global DB setting.
     */
    function isCrmPaymentEnabled(): bool
    {
        return isPaymentEnabled();
    }
}

if (!function_exists('isApiPaymentEnabled')) {
    /**
     * Whether API wallet top-ups are permitted.
     * Completely independent of the CRM payment_enabled toggle.
     * Returns true as long as Razorpay keys exist in settings.
     */
    function isApiPaymentEnabled(): bool
    {
        return true;
    }
}

if (!function_exists('isApiClient')) {
    /**
     * Check if the authenticated user is an API Client.
     */
    function isApiClient(): bool
    {
        return auth()->check() && auth()->user()->hasRole(\App\Support\Roles::API_CLIENT);
    }
}

if (!function_exists('isSuperAdmin')) {
    /**
     * Check if the authenticated user is a Super Admin.
     * Super Admins bypass all tenant-level restrictions.
     */
    function isSuperAdmin(): bool
    {
        return auth()->check() && auth()->user()->hasRole(\App\Support\Roles::SUPER_ADMIN);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    protected function casts(): array
    {
        return [
            // Laravel encrypted cast (AES-256-GCM via APP_KEY)
            'value' => 'encrypted',
        ];
    }

    public static function getString(string $key, ?string $default = null): ?string
    {
        $row = static::query()->where('key', $key)->first();
        if (! $row) {
            return $default;
        }

        $v = $row->value;

        return $v === null || $v === '' ? $default : (string) $v;
    }

    public static function getBool(string $key, bool $default = false): bool
    {
        $v = static::getString($key);
        if ($v === null) {
            return $default;
        }

        return in_array($v, ['1', 'true', 'yes', 'on'], true);
    }

    public static function putString(string $key, ?string $value): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        // Clear setting caches so toggles reflect instantly.
        Cache::forget('settings:all');
        Cache::forget('setting:'.$key);
    }
}

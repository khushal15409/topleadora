<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteVisit extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'path',
        'query_string',
        'route_name',
        'ip_address',
        'user_agent',
        'referer',
        'session_id',
        'is_bot',
    ];

    protected function casts(): array
    {
        return [
            'is_bot' => 'boolean',
            'created_at' => 'datetime',
        ];
    }
}

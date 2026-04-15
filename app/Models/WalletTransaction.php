<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    protected $fillable = [
        'organization_id',
        'amount',
        'type',
        'source',
        'reference_id',
        'description',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'float',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}

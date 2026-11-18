<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderServiceArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'city_id',
        'center',
        'radius_km',
    ];

    protected function casts(): array
    {
        return [
            'radius_km' => 'decimal:2',
        ];
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'user_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}


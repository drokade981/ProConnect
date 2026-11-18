<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingAssignment extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'booking_id',
        'provider_id',
        'status',
        'distance_km',
        'offered_at',
        'responded_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'distance_km' => 'decimal:2',
            'offered_at' => 'datetime',
            'responded_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'user_id');
    }
}


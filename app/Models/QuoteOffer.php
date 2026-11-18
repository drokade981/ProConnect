<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class QuoteOffer extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'quote_id',
        'provider_id',
        'amount',
        'eta_hours',
        'note',
        'is_accepted',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'eta_hours' => 'integer',
            'is_accepted' => 'boolean',
        ];
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'user_id');
    }

    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class);
    }
}


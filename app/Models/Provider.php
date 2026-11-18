<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provider extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'kind',
        'agency_id',
        'display_name',
        'bio',
        'experience_months',
        'verification',
        'is_online',
        'last_location',
        'last_seen_at',
        'avg_rating',
        'rating_count',
        'jobs_completed',
        'acceptance_rate',
        'onboarded_at',
    ];

    protected function casts(): array
    {
        return [
            'experience_months' => 'integer',
            'is_online' => 'boolean',
            'last_seen_at' => 'datetime',
            'avg_rating' => 'decimal:2',
            'rating_count' => 'integer',
            'jobs_completed' => 'integer',
            'acceptance_rate' => 'decimal:2',
            'onboarded_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'agency_id', 'user_id');
    }

    public function workers(): HasMany
    {
        return $this->hasMany(Provider::class, 'agency_id', 'user_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ProviderDocument::class, 'provider_id', 'user_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(ProviderService::class, 'provider_id', 'user_id');
    }

    public function serviceAreas(): HasMany
    {
        return $this->hasMany(ProviderServiceArea::class, 'provider_id', 'user_id');
    }

    public function availability(): HasMany
    {
        return $this->hasMany(ProviderAvailability::class, 'provider_id', 'user_id');
    }

    public function timeOff(): HasMany
    {
        return $this->hasMany(ProviderTimeOff::class, 'provider_id', 'user_id');
    }

    public function quoteOffers(): HasMany
    {
        return $this->hasMany(QuoteOffer::class, 'provider_id', 'user_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'provider_id', 'user_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(BookingAssignment::class, 'provider_id', 'user_id');
    }

    public function ledger(): HasMany
    {
        return $this->hasMany(ProviderLedger::class, 'provider_id', 'user_id');
    }

    public function payouts(): HasMany
    {
        return $this->hasMany(Payout::class, 'provider_id', 'user_id');
    }

    public function bankAccounts(): HasMany
    {
        return $this->hasMany(ProviderBankAccount::class, 'provider_id', 'user_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'provider_id', 'user_id');
    }
}


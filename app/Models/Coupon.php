<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_pct',
        'discount_flat',
        'max_discount',
        'min_order',
        'valid_from',
        'valid_to',
        'usage_limit',
        'per_user_limit',
        'category_id',
    ];

    protected function casts(): array
    {
        return [
            'discount_pct' => 'decimal:2',
            'discount_flat' => 'decimal:2',
            'max_discount' => 'decimal:2',
            'min_order' => 'decimal:2',
            'valid_from' => 'datetime',
            'valid_to' => 'datetime',
            'usage_limit' => 'integer',
            'per_user_limit' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function redemptions(): HasMany
    {
        return $this->hasMany(CouponRedemption::class);
    }
}


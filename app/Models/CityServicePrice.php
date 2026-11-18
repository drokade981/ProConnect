<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CityServicePrice extends Model
{
    use HasFactory;

    protected $table = 'city_service_prices';
    public $incrementing = false;
    protected $primaryKey = null; // composite primary key ['service_id', 'city_id']

    protected $fillable = [
        'service_id',
        'city_id',
        'base_price',
        'unit_label',
        'platform_commission_pct',
    ];

    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
            'platform_commission_pct' => 'decimal:2',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderService extends Model
{
    use HasFactory;

    protected $table = 'provider_services';
    public $incrementing = false;
    protected $primaryKey = null; // composite primary key ['provider_id', 'service_id']

    protected $fillable = [
        'provider_id',
        'service_id',
        'custom_price',
        'is_enabled',
    ];

    protected function casts(): array
    {
        return [
            'custom_price' => 'decimal:2',
            'is_enabled' => 'boolean',
        ];
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'user_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}


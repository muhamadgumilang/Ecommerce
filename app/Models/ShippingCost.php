<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCost extends Model
{
    use HasFactory;

    protected $primaryKey = 'shipping_cost_id';

    protected $fillable = [
        'origin',
        'destination',
        'courier',
        'service',
        'description',
        'cost',
        'etd',
    ];

    protected $casts = [
        'cost' => 'integer',
        'etd' => 'integer',
    ];

    public function getCourierLabelAttribute(): string
    {
        return match ($this->courier) {
            'jne' => 'JNE',
            'tiki' => 'TIKI',
            'pos' => 'POS Indonesia',
            default => $this->courier,
        };
    }
}
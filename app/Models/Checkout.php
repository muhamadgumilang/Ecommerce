<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    use HasFactory;

    protected $primaryKey = 'checkout_id';

    protected $fillable = [
        'order_id',
        'shipping_address',
        'courier',
        'shipping_fee',
        'notes',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
}
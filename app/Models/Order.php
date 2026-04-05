<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'cart_id',
        'order_number',
        'customer_name',
        'customer_email',
        'customer_address',
        'status',
        'total',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}


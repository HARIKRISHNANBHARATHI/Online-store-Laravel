<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'billing_address_id',
        'shipping_address_id',
        'order_number',
        'subtotal',
        'discount',
        'tax',
        'shipping',
        'total',
        'status',
        'payment_status',
        'payment_method',
        'coupon_code',
        'billing_address_data',
        'shipping_address_data',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping' => 'decimal:2',
        'total' => 'decimal:2',
        'billing_address_data' => 'array',
        'shipping_address_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public static function generateOrderNumber(): string
    {
        return 'ORD-' . strtoupper(uniqid()) . '-' . rand(100, 999);
    }
}

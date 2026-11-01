<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'coupon_code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_code', 'code');
    }

    public function getSubtotalAttribute(): float
    {
        return (float) $this->items->sum(function ($item) {
            return $item->subtotal;
        });
    }

    public function getDiscountAttribute(): float
    {
        if (!$this->coupon_code) {
            return 0.00;
        }

        $coupon = Coupon::where('code', $this->coupon_code)->where('status', true)->first();
        if (!$coupon) {
            return 0.00;
        }

        return $coupon->calculateDiscount($this->subtotal);
    }

    public function getTaxAttribute(): float
    {
        $taxableAmount = max(0, $this->subtotal - $this->discount);
        return round($taxableAmount * 0.08, 2); // 8% sales tax
    }

    public function getShippingAttribute(): float
    {
        if ($this->subtotal == 0) {
            return 0.00;
        }
        return $this->subtotal >= 100 ? 0.00 : 10.00; // Free shipping over $100
    }

    public function getTotalAttribute(): float
    {
        return round($this->subtotal - $this->discount + $this->tax + $this->shipping, 2);
    }
}

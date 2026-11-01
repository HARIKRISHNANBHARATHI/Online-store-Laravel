<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'minimum_order_amount',
        'maximum_discount',
        'usage_limit',
        'used_count',
        'starts_at',
        'expires_at',
        'status',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'status' => 'boolean',
    ];

    public function isValidForAmount(float $subtotal): array
    {
        if (!$this->status) {
            return ['valid' => false, 'message' => 'Coupon is inactive.'];
        }

        $now = Carbon::now();

        if ($this->starts_at && $now->lt($this->starts_at)) {
            return ['valid' => false, 'message' => 'Coupon is not active yet.'];
        }

        if ($this->expires_at && $now->gt($this->expires_at)) {
            return ['valid' => false, 'message' => 'Coupon has expired.'];
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return ['valid' => false, 'message' => 'Coupon usage limit reached.'];
        }

        if ($this->minimum_order_amount !== null && $subtotal < $this->minimum_order_amount) {
            return ['valid' => false, 'message' => "Minimum order amount of {$this->minimum_order_amount} required."];
        }

        return ['valid' => true, 'message' => 'Coupon applied successfully.'];
    }

    public function calculateDiscount(float $subtotal): float
    {
        $validation = $this->isValidForAmount($subtotal);
        if (!$validation['valid']) {
            return 0.00;
        }

        if ($this->type === 'percentage') {
            $discount = ($subtotal * $this->value) / 100;
        } else {
            $discount = $this->value;
        }

        if ($this->maximum_discount !== null && $discount > $this->maximum_discount) {
            $discount = (float) $this->maximum_discount;
        }

        return min((float) $discount, $subtotal);
    }
}

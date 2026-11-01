<?php

namespace App\Services;

use App\Models\Coupon;

class CouponService
{
    public function validateCoupon(string $code, float $subtotal): array
    {
        $coupon = Coupon::where('code', strtoupper($code))->first();

        if (!$coupon) {
            return [
                'valid' => false,
                'message' => 'Invalid coupon code.',
                'coupon' => null,
                'discount' => 0.00,
            ];
        }

        $result = $coupon->isValidForAmount($subtotal);
        $discount = 0.00;

        if ($result['valid']) {
            $discount = $coupon->calculateDiscount($subtotal);
        }

        return [
            'valid' => $result['valid'],
            'message' => $result['message'],
            'coupon' => $result['valid'] ? $coupon : null,
            'discount' => $discount,
        ];
    }

    public function recordCouponUse(string $code): void
    {
        $coupon = Coupon::where('code', strtoupper($code))->first();
        if ($coupon) {
            $coupon->increment('used_count');
        }
    }
}

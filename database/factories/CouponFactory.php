<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition(): array
    {
        $type = fake()->randomElement(['percentage', 'fixed']);
        $value = $type === 'percentage' ? fake()->numberBetween(5, 30) : fake()->numberBetween(10, 50);

        return [
            'code' => strtoupper(Str::random(8)),
            'type' => $type,
            'value' => $value,
            'minimum_order_amount' => 50.00,
            'maximum_discount' => $type === 'percentage' ? 100.00 : null,
            'usage_limit' => 100,
            'used_count' => 0,
            'starts_at' => now(),
            'expires_at' => now()->addDays(30),
            'status' => true,
        ];
    }
}

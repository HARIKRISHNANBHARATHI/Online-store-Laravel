<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 50, 300);
        $discount = fake()->randomFloat(2, 0, 20);
        $tax = round(($subtotal - $discount) * 0.08, 2);
        $shipping = 10.00;
        $total = round($subtotal - $discount + $tax + $shipping, 2);

        return [
            'user_id' => User::factory(),
            'order_number' => Order::generateOrderNumber(),
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'shipping' => $shipping,
            'total' => $total,
            'status' => fake()->randomElement(['pending', 'processing', 'shipped', 'delivered']),
            'payment_status' => fake()->randomElement(['pending', 'paid']),
        ];
    }
}

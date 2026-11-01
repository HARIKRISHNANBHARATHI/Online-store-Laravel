<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;

class PaymentService
{
    /**
     * Record a Cash on Delivery (COD) payment record for an order.
     * The order stays in "pending" payment status until delivered and
     * the admin manually marks it as paid.
     */
    public function recordCodPayment(Order $order): Order
    {
        $order->update([
            'payment_status' => 'pending',   // COD: payment collected on delivery
            'payment_method' => 'cod',
            'status' => 'processing',
        ]);

        Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'transaction_id' => 'COD-' . strtoupper($order->order_number),
                'provider' => 'cod',
                'amount' => $order->total,
                'currency' => 'USD',
                'status' => 'pending',
                'paid_at' => null,
                'payload' => ['method' => 'cash_on_delivery'],
            ]
        );

        return $order;
    }

    /**
     * Admin manually marks a COD order as paid after delivering.
     */
    public function markCodOrderAsPaid(Order $order): Order
    {
        $order->update([
            'payment_status' => 'paid',
            'status' => 'delivered',
        ]);

        Payment::where('order_id', $order->id)->update([
            'status' => 'succeeded',
            'paid_at' => now(),
        ]);

        return $order;
    }
}

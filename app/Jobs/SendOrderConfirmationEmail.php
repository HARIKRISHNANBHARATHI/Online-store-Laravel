<?php

namespace App\Jobs;

use App\Models\Order;
use App\Notifications\OrderConfirmationNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOrderConfirmationEmail implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle(): void
    {
        if ($this->order->user) {
            $this->order->user->notify(new OrderConfirmationNotification($this->order));
        }
    }
}

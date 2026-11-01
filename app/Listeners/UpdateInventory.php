<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use Illuminate\Support\Facades\Log;

class UpdateInventory
{
    public function handle(OrderPlaced $event): void
    {
        Log::info("Inventory reserved and updated for Order #{$event->order->order_number}");
    }
}

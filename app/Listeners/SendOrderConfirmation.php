<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Jobs\SendOrderConfirmationEmail;

class SendOrderConfirmation
{
    public function handle(OrderPlaced $event): void
    {
        SendOrderConfirmationEmail::dispatch($event->order);
    }
}

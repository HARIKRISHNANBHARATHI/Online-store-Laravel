<?php

namespace App\Providers;

use App\Events\OrderPlaced;
use App\Listeners\SendOrderConfirmation;
use App\Listeners\UpdateInventory;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        Event::listen(
            OrderPlaced::class,
            [SendOrderConfirmation::class, 'handle']
        );

        Event::listen(
            OrderPlaced::class,
            [UpdateInventory::class, 'handle']
        );
    }
}

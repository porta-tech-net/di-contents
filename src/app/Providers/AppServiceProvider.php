<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\MessageServiceInterface;
use App\Services\MessageService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            MessageServiceInterface::class,
            MessageService::class
        );

        // Order comparison: interface-to-concrete bindings (transient).
        $this->app->bind(\App\OrderComparison\ContainerInterface\StockServiceInterface::class, \App\OrderComparison\ContainerInterface\StockService::class);
        $this->app->bind(\App\OrderComparison\ContainerInterface\PriceServiceInterface::class, \App\OrderComparison\ContainerInterface\PriceService::class);
        $this->app->bind(\App\OrderComparison\ContainerInterface\OrderRepositoryInterface::class, \App\OrderComparison\ContainerInterface\OrderRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

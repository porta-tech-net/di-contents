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
        $this->app->bind(\App\OrderComparison\ContainerInterface\PriceServiceInterface::class, \App\OrderComparison\ContainerInterface\DiscountPriceService::class);
        $this->app->bind(\App\OrderComparison\ContainerInterface\OrderRepositoryInterface::class, \App\OrderComparison\ContainerInterface\OrderRepository::class);
        $this->app->bind(
            \App\OrderComparison\ContainerConcrete\PriceService::class,
            \App\OrderComparison\ContainerConcrete\DiscountPriceService::class
        );
        // $this->app->bind(
        //     \App\OrderComparison\ContainerConcrete\StockService::class,
        //     \App\OrderComparison\ContainerConcrete\FakeStockService::class
        // );
        // $this->app->bind(\App\OrderComparison\ContainerInterface\StockServiceInterface::class, \App\OrderComparison\ContainerInterface\FakeStockService::class);
        $this->app->bind(
            \App\OrderComparison\ContainerInterface\OrderServiceInterface::class,
            function ($app) {
                $service = $app->make(
                    \App\OrderComparison\ContainerInterface\OrderService::class
                );

                return new \App\OrderComparison\ContainerInterface\LoggingOrderService(
                    $service
                );
            }
        );

        $this->app->scoped(
            \App\OrderComparison\AmbientContext\ScopedWarehouseContext::class,
            function () {
                return new \App\OrderComparison\AmbientContext\ScopedWarehouseContext();
            }
        );

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

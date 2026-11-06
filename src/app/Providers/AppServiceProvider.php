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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

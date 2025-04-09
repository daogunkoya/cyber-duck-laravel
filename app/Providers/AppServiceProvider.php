<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\PriceCalculationService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(PriceCalculationService::class, function () {
            return new PriceCalculationService(
                config('coffee.profit_margin'),
                config('coffee.shipping_cost')
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

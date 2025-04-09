<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Pricing\ProductPricingStrategy;
use App\Services\CoffeePriceCalculator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            CoffeePriceCalculator::class,
            fn() => new CoffeePriceCalculator(
                new ProductPricingStrategy(0.25, 10.00), // Gold
                new ProductPricingStrategy(0.15, 10.00)  // Arabic
            )
        );
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

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\PriceCalculationService;
use App\Repositories\CoffeeSaleRepository;
use App\Contracts\CoffeeSaleRepositoryInterface;


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
                config('coffee.shipping_cost')
            );
        });

        $this->app->bind(
            CoffeeSaleRepositoryInterface::class, 
            CoffeeSaleRepository::class
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

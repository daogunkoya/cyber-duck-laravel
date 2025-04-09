<?php

use App\DTO\CoffeeProductDto;
use App\Services\PriceCalculationService;
use App\Exceptions\InvalidProfitMarginException;

uses()
    ->beforeEach(function () {
        $this->goldCoffee = new CoffeeProductDto(
            coffeeProductId: 1,
            name: 'Gold Coffee',
            profitMargin: 0.2,
            description: "Gold Coffee is a premium coffee with a rich and complex flavor profile.",
            isActive: true
        );

        $this->service = new PriceCalculationService(shippingCost: 10.00);
    });

it('calculates selling price with 0.2 profit margin and £2 shipping', function () {
    $service = new PriceCalculationService(shippingCost: 2.00);
    $price = $service->calculateSellingPrice($this->goldCoffee, 10, 1.00); // cost: £10

    expect($price)->toBe(14.5); // (10 / 0.8) + 2 = 14.5
});

it('calculates selling price correctly for Gold Coffee', function () {
    $price = $this->service->calculateSellingPrice($this->goldCoffee, 5, 10.00); // cost: £50

    expect($price)->toBe(72.5); // (50 / 0.8) + 10 = 72.5
});

it('calculates selling price correctly for Arabic Coffee with 15% margin', function () {
    $arabicCoffee = new CoffeeProductDto(
        coffeeProductId: 2,
        name: 'Arabic Coffee',
        profitMargin: 0.15,
        description: "Arabic Coffee has a distinct, smooth flavor profile.",
        isActive: true
    );

    $price = $this->service->calculateSellingPrice($arabicCoffee, 5, 10.00); // cost: £50

    expect($price)->toBe(68.82); // (50 / 0.85) + 10 = ~68.82
});

it('throws exception for invalid profit margin >= 1', function () {
    $invalidProduct = new CoffeeProductDto(
        coffeeProductId: 3,
        name: 'Broken Coffee',
        profitMargin: 1.15,
        description: "Invalid profit margin test.",
        isActive: true
    );

    $this->service->calculateSellingPrice($invalidProduct, 1, 10.00);
})->throws(\InvalidArgumentException::class);

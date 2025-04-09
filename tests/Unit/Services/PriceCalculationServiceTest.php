<?php
// tests/Unit/Services/PriceCalculationServiceTest.php

use App\Services\PriceCalculationService;

test('calculates selling price correctly with injected values', function () {
    $service = new PriceCalculationService(0.25, 10.00);
    $price = $service->calculateSellingPrice(5, 10.00);

    // (5*10)/(1-0.25) + 10 = 76.666... rounded to 76.67
    expect($price)->toBe(76.67);
});

test('calculates selling price correctly with different values', function () {
    $service = new PriceCalculationService(0.15, 5.00);
    $price = $service->calculateSellingPrice(3, 12.50);

    // (3*12.50)/(1-0.15) + 5 = 49.117... rounded to 49.12
    expect($price)->toBe(49.12);
});

test('handles zero quantity gracefully', function () {
    $service = new PriceCalculationService(0.25, 10.00);
    $price = $service->calculateSellingPrice(0, 10.00);

    // Just shipping cost
    expect($price)->toBe(10.00);
});

test('handles minimum unit cost', function () {
    $service = new PriceCalculationService(0.25, 10.00);
    $price = $service->calculateSellingPrice(1, 0.01);

    // (1*0.01)/(1-0.25) + 10 = 10.013... rounded to 10.01
    expect($price)->toBe(10.01);
});

test('handles maximum values without errors', function () {
    $service = new PriceCalculationService(0.25, 10.00);
    $price = $service->calculateSellingPrice(PHP_INT_MAX, PHP_FLOAT_MAX);
    
    expect($price)->toBeFloat();
});
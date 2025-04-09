<?php

namespace App\Services\Pricing\Contracts;

interface PricingStrategy
{
    public function calculate(float $cost): float;
    
    public function supports(string $productType): bool;
}

// app/Services/Pricing/ProductPricingStrategy.php
namespace App\Services\Pricing;

use App\Services\Pricing\Contracts\PricingStrategy;

readonly class ProductPricingStrategy implements PricingStrategy
{
    public function __construct(
        private float $profitMargin,
        private float $shippingCost
    ) {}
    
    public function calculate(float $cost): float
    {
        return round(($cost / (1 - $this->profitMargin)) + $this->shippingCost, 2);
    }
    
    public function supports(string $productType): bool
    {
        return match($productType) {
            'gold' => true,
            'arabic' => true,
            default => false
        };
    }
}
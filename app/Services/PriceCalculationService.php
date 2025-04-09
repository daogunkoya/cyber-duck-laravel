<?php

namespace App\Services;


class PriceCalculationService
{

    public function __construct(
        private float $profitMargin,
        private float $shippingCost
    ) {}

    
    public function calculateSellingPrice(
        int $quantity,
        float $unitCost
    ): float {
        $cost = $quantity * $unitCost;
        return round(($cost / (1 - $this->profitMargin)) + $this->shippingCost, 2);
    }
}

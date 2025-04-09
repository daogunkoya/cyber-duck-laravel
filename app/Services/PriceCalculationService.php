<?php

namespace App\Services;

use App\DTO\CoffeeProductDto;
use App\Exceptions\InvalidProfitMarginException;


class PriceCalculationService
{

    public function __construct(
        private float $shippingCost
    ) {}

    
    public function calculateSellingPrice(
        CoffeeProductDto $product,
        int $quantity,
        float $unitCost
    ): float {

        $this->validateCalculationInputs($product, $quantity, $unitCost);

        $cost = $quantity * $unitCost;
        
        if ($product->profitMargin >= 1) {
            throw new \InvalidArgumentException("Profit margin must be a decimal less than 1.");
        }

        return round(($cost / (1 - $product->profitMargin)) + $this->shippingCost, 2);
    }

    private function validateCalculationInputs(
        CoffeeProductDto $product,
        int $quantity,
        float $unitCost
    ): void {
        if ($product->profitMargin <= 0 || $product->profitMargin >= 1) {
            throw new InvalidProfitMarginException();
        }
        
        if ($quantity <= 0) {
            throw new \InvalidArgumentException("Quantity must be positive");
        }
        
        if ($unitCost <= 0) {
            throw new \InvalidArgumentException("Unit cost must be positive");
        }
    }
}

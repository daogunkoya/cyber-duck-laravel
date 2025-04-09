<?php

namespace App\Services;

use App\Services\Pricing\Contracts\PricingStrategy;

final readonly class CoffeePriceCalculator
{
    /** @var PricingStrategy[] */
    private array $strategies;
    
    public function __construct(PricingStrategy ...$strategies)
    {
        $this->strategies = $strategies;
    }
    
    public function calculateCost(int $quantity, float $unitCost): float
    {
        return $quantity * $unitCost;
    }
    
    public function calculateSellingPrice(float $cost, string $productType): float
    {
        $strategy = $this->getStrategyFor($productType);
        
        return $strategy->calculate($cost);
    }
    
    private function getStrategyFor(string $productType): PricingStrategy
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($productType)) {
                return $strategy;
            }
        }
        
        throw new \InvalidArgumentException("No pricing strategy for product type: $productType");
    }
}
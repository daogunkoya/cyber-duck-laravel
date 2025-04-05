<?php
// app/Services/CoffeePriceCalculator.php
namespace App\Services;

use App\Data\CoffeeSaleData;
use App\Models\CoffeeProduct;
use App\Exceptions\CoffeeCalculationException;

class CoffeePriceCalculator
{
    private const SHIPPING_COST = 10.00;

    public function calculate(CoffeeSaleData $saleData): array
    {
        $product = CoffeeProduct::findOrFail($saleData->coffee_product_id);
        
        $cost = $this->calculateCost($saleData->quantity, $saleData->unit_cost);
        $sellingPrice = $this->calculateSellingPrice($cost, $product->profit_margin);

        return [
            'cost' => $cost,
            'selling_price' => $sellingPrice,
        ];
    }

    private function calculateCost(int $quantity, float $unitCost): float
    {
        return $quantity * $unitCost;
    }

    private function calculateSellingPrice(float $cost, float $profitMargin): float
    {
        if ($profitMargin <= 0 || $profitMargin >= 1) {
            throw CoffeeCalculationException::invalidProfitMargin();
        }
    
        $price = ($cost / (1 - $profitMargin)) + self::SHIPPING_COST;
        
        return round($price, 2);
    }
}
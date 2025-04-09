<?php
namespace App\Actions;

use App\Data\CoffeeSaleData;
use App\Models\CoffeeProduct;
use App\Services\CoffeePriceCalculator;

final readonly class CalculateCoffeePrice
{
    public function __construct(private CoffeePriceCalculator $calculator) {}
    
    public function __invoke(CoffeeSaleData $data): array
    {
        $product = CoffeeProduct::findOrFail($data->coffee_product_id);
        $cost = $this->calculator->calculateCost($data->quantity, $data->unit_cost);
        
        return [
            'cost' => $cost,
            'selling_price' => $this->calculator->calculateSellingPrice($cost, $product->type),
        ];
    }
}


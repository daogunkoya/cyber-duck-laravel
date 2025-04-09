<?php

namespace App\Services;


class PriceCalculationService
{

    public function calculateSellingPrice(
        int $quantity,
        float $unitCost
    ): float {
        $cost = $quantity * $unitCost;
        return round(($cost / (1 - config('coffee.profit_margin'))) + config('coffee.shipping_cost'), 2);
    }
}

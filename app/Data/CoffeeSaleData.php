<?php
// app/Data/CoffeeSaleData.php
namespace App\Data;

use Spatie\LaravelData\Data;

class CoffeeSaleData extends Data
{
    public function __construct(
        public int $coffee_product_id,
        public int $quantity,
        public float $unit_cost,
    ) {}
}
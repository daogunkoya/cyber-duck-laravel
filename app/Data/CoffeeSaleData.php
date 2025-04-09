<?php
// app/Data/CoffeeSaleData.php
namespace App\Data;

use Spatie\LaravelData\Data;
use App\Attributes\Validation\PositiveNumber;
use App\Attributes\Validation\ExistsInDatabase;

class CoffeeSaleData extends Data
{
    public function __construct(
        #[ExistsInDatabase('coffee_products', 'id')]
        public readonly int $coffee_product_id,

        #[PositiveNumber]
        public readonly int $quantity,

        #[PositiveNumber]
        public readonly float $unit_cost,
    ) {}
}
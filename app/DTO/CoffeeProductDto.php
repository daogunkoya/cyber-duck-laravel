<?php

namespace App\DTO;

use App\Models\CoffeeProduct;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;



readonly class CoffeeProductDto
{
    public function __construct(
        public int $coffeeProductId,
        public string $name,
        public float $profitMargin,
        public string $description,
        public bool $isActive
        

    ) {}

    public static function fromEloquentModel(CoffeeProduct $coffeeProduct): self
    {
        return new self(
            $coffeeProduct->id,
            $coffeeProduct->name,
            $coffeeProduct->profit_margin,
            $coffeeProduct->description,
            $coffeeProduct->is_active  
        );
    }

    public static function fromEloquentCollection(Collection $coffeeProducts): SupportCollection
    {
        return $coffeeProducts->map(fn (CoffeeProduct $coffeeProduct) => self::fromEloquentModel($coffeeProduct));
    }
}

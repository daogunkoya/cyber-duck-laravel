<?php
namespace App\DTO;

use App\Models\CoffeeSale;
use App\Models\CoffeeProduct;
use App\DTO\CoffeeProductDto;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

readonly class CoffeeSaleDto
{
    public function __construct(
        public int $saleId,
        public ?CoffeeProductDto $coffeeProduct,  // Make it nullable
        public int $quantity,
        public float $unitCost,
        public float $sellingPrice,
        public int $userId,
        public string $createdAt,
    ) {}

    public static function fromEloquentModel(CoffeeSale $coffeeSale): self
    {
        return new self(
            $coffeeSale->id,
            $coffeeSale->coffeeProduct ? CoffeeProductDto::fromEloquentModel($coffeeSale->coffeeProduct) : null, // Correct the method call for coffeeProduct
            $coffeeSale->quantity,
            $coffeeSale->unit_cost,
            $coffeeSale->selling_price,
            $coffeeSale->user_id,
            $coffeeSale->created_at->format('Y-m-d H:i')
        );
    }

    public static function fromEloquentCollection(Collection $coffeeSales): SupportCollection
    {
        return $coffeeSales->map(fn (CoffeeSale $coffeeSale) => self::fromEloquentModel($coffeeSale));
    }
}

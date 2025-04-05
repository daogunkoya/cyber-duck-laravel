<?php

// database/factories/CoffeeSaleFactory.php
namespace Database\Factories;

use App\Models\CoffeeProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

class CoffeeSaleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'coffee_product_id' => CoffeeProduct::factory(),
            'quantity' => fake()->numberBetween(1, 10),
            'unit_cost' => fake()->randomFloat(2, 5, 50),
            'selling_price' => fn (array $attributes) => (
                ($attributes['quantity'] * $attributes['unit_cost']) / 
                (1 - CoffeeProduct::find($attributes['coffee_product_id'])->profit_margin)
                + config('coffee.shipping_cost')
            ),
        ];
    }

    public function goldCoffee(): static
    {
        return $this->state(fn (array $attributes) => [
            'coffee_product_id' => CoffeeProduct::factory()->goldCoffee(),
        ]);
    }

    public function arabicCoffee(): static
    {
        return $this->state(fn (array $attributes) => [
            'coffee_product_id' => CoffeeProduct::factory()->arabicCoffee(),
        ]);
    }
}
<?php

// database/factories/CoffeeProductFactory.php
namespace Database\Factories;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;

class CoffeeProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'description' => fake()->sentence(),
        ];
    }

    public function goldCoffee(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Gold Coffee',
            'description' => "Gold Coffee is a premium coffee with a rich and complex flavor profile.",
        ]);
    }

    public function arabicCoffee(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Arabic Coffee',
            'description' => "Arabic Coffee is a bold and aromatic coffee with a rich and complex flavor profile.",
        ]);
    }
}
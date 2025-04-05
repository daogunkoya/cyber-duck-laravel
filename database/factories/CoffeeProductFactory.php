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
            'user_id' => User::factory(), // Add this
            'name' => fake()->unique()->words(2, true),
            'profit_margin' => fake()->randomFloat(2, 0.1, 0.4),
            'description' => fake()->sentence(),
        ];
    }

    public function goldCoffee(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Gold Coffee',
            'profit_margin' => 0.25,
        ]);
    }

    public function arabicCoffee(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Arabic Coffee',
            'profit_margin' => 0.15,
        ]);
    }
}
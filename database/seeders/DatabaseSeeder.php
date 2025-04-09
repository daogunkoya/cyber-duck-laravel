<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\CoffeeProduct;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'Sales Agent',
            'email' => 'sales@coffee.shop',
        ]);

        CoffeeProduct::factory()->goldCoffee()->create();
        CoffeeProduct::factory()->arabicCoffee()->create();
    }
}

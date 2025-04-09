<?php
// tests/Feature/CoffeeSaleTest.php

use App\Models\CoffeeProduct;
use App\Models\User;
use App\Services\PriceCalculationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::create([
        'name' => 'Sales Agent',
        'email' => 'sales@coffee.shop', 
        'password' => 'password'
    ]);
    $this->actingAs($this->user);
    
    $this->product = CoffeeProduct::factory()->create([
        'name' => 'Gold Coffee'
    ]);
    
    
    // Mock the service in container
    // $this->mock(PriceCalculationService::class, function ($mock) {
    //     $mock->shouldReceive('calculateSellingPrice')
    //         ->andReturnUsing(function ($qty, $cost) {
    //             return round(($qty * $cost) / (1 - 0.25) + 10.00, 2);
    //         });
    // });
});

test('store creates a new sale with correct calculated price', function () {
    $response = $this->post(route('sales.store'), [
        'coffee_product_id' => $this->product->id,
        'quantity' => 5,
        'unit_cost' => 10.00
    ]);

    $response->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('coffee_sales', [
        'coffee_product_id' => $this->product->id,
        'quantity' => 5,
        'unit_cost' => 10.00,
        'selling_price' => 76.67,
        'user_id' => $this->user->id
    ]);
});

test('store creates sale via API with correct calculated price', function () {
    $response = $this->postJson(route('sales.store'), [
        'coffee_product_id' => $this->product->id,
        'quantity' => 2,
        'unit_cost' => 15.00
    ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'sale' => [
                'quantity' => 2,
                'unit_cost' => 15.00,
                'selling_price' => 50.00 // (2*15)/(1-0.25) + 10
            ]
        ]);
});
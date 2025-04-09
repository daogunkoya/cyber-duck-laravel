<?php

namespace App\Repositories;

use App\Models\CoffeeSale;
use Illuminate\Database\Eloquent\Collection;

class CoffeeSaleRepository
{
    public function getAllSales(): Collection
    {
        return CoffeeSale::with('coffeeProduct')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function createSale(array $data): CoffeeSale
    {
        return CoffeeSale::create($data);
    }
}
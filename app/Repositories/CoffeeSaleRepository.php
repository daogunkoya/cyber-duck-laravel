<?php

namespace App\Repositories;

use App\Models\CoffeeSale;
use Illuminate\Database\Eloquent\Collection;
use App\Contracts\CoffeeSaleRepositoryInterface;


class CoffeeSaleRepository implements CoffeeSaleRepositoryInterface
/*************  ✨ Windsurf Command ⭐  *************/
    /**
     * Retrieve all coffee sales with their associated coffee products.
     *
     * @return Collection A collection of CoffeeSale models with related coffeeProduct, ordered by creation date descending.
/*******  83729216-02cc-4d99-b53e-1655b558e37a  *******/{

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
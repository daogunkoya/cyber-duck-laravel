<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;
use App\Models\CoffeeSale;

interface CoffeeSaleRepositoryInterface
{
    public function getAllSales(): Collection;
    public function createSale(array $data): CoffeeSale;
}


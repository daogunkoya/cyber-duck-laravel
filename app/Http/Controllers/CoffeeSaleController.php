<?php

// app/Http/Controllers/CoffeeSaleController.php
namespace App\Http\Controllers;

use App\Data\CoffeeSaleData;
use App\Http\Requests\StoreCoffeeSaleRequest;
use App\Models\CoffeeSale;
use App\Services\CoffeePriceCalculator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CoffeeSaleController extends Controller
{
    public function __construct(
        private CoffeePriceCalculator $calculator
    ) {}

    public function calculate(StoreCoffeeSaleRequest $request): JsonResponse
    {
        $saleData = CoffeeSaleData::from($request->validated());
        $calculation = $this->calculator->calculate($saleData);

        return response()->json([
            'data' => array_merge($saleData->toArray(), $calculation)
        ]);
    }

    public function store(StoreCoffeeSaleRequest $request): JsonResponse
    {
        $saleData = CoffeeSaleData::from($request->validated());
        $calculation = $this->calculator->calculate($saleData);

        $sale = Auth::user()->coffeeSales()->create(array_merge(
            $saleData->toArray(),
            $calculation
        ));

        return response()->json([
            'data' => $sale,
        ], 201);
    }
}
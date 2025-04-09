<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCoffeeSaleRequest;
use App\Models\CoffeeProduct;
use App\Models\CoffeeSale;
use App\Repositories\CoffeeSaleRepository;
use App\Services\PriceCalculationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Collection;

class CoffeeSaleController extends Controller
{
    public function __construct(
        private CoffeeSaleRepository $saleRepository,
        private PriceCalculationService $priceCalculator
    ) {}

    public function index(): View
    {
        return view('coffee_sales', [
            'coffeeProducts' => CoffeeProduct::all(),
            'sales' => $this->formatSalesData(
                $this->saleRepository->getAllSales()
            ),
            'header' => ''
        ]);
    }

    public function store(StoreCoffeeSaleRequest $request): JsonResponse|RedirectResponse
    {
        $product = CoffeeProduct::findOrFail($request->coffee_product_id);
        
        $sellingPrice = $this->priceCalculator->calculateSellingPrice(
            $request->quantity,
            $request->unit_cost
        );

        $sale = $this->saleRepository->createSale([
            'coffee_product_id' => $product->id,
            'quantity' => $request->quantity,
            'unit_cost' => $request->unit_cost,
            'selling_price' => $sellingPrice,
            'user_id' => auth()->id()
        ]);

        return $this->createResponse($request, $sale, $sellingPrice);
    }

    private function formatSalesData(Collection $sales): array
    {
        return $sales->map(fn ($sale) => [
            'id' => $sale->id,
            'product_name' => $sale->coffeeProduct->name,
            'quantity' => $sale->quantity,
            'unit_cost' => $sale->unit_cost,
            'selling_price' => $sale->selling_price,
            'created_at' => $sale->created_at->format('Y-m-d H:i'),
        ])->all();
    }

    private function createResponse(
        StoreCoffeeSaleRequest $request,
        CoffeeSale $sale,
        float $sellingPrice
    ): JsonResponse|RedirectResponse {
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'sale' => $sale,
                'product_name' => $sale->coffeeProduct->name,
                'created_at' => $sale->created_at->toISOString()
            ]);
        }

        return redirect()
            ->route('sales.index')
            ->with('success', 'Sale recorded successfully! Selling price: £' . number_format($sellingPrice, 2));
    }
}
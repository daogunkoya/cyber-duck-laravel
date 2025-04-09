<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCoffeeSaleRequest;
use App\Models\CoffeeProduct;
use App\Models\CoffeeSale;
use App\Contracts\CoffeeSaleRepositoryInterface;
use App\Services\PriceCalculationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Collection as SupportCollection;
use App\DTO\CoffeeProductDto;
use App\DTO\CoffeeSaleDto;
use App\Exceptions\InvalidProfitMarginException;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class CoffeeSaleController extends Controller
{
    public function __construct(
        private CoffeeSaleRepositoryInterface $saleRepository,
        private PriceCalculationService $priceCalculator
    ) {}

    public function index(): View
    {
        return view('coffee_sales', [
            'coffeeProducts' => CoffeeProductDto::fromEloquentCollection(CoffeeProduct::all()),
            'sales' => $this->formatSalesData(
               CoffeeSaleDto::fromEloquentCollection( $this->saleRepository->getAllSales())
            ),
            'header' => ''
        ]);                                                         
    }

    public function store(StoreCoffeeSaleRequest $request): JsonResponse|RedirectResponse
    {
        $product = CoffeeProductDto::fromEloquentModel(CoffeeProduct::findOrFail($request->coffee_product_id));
        
        try{

        $sellingPrice = $this->priceCalculator->calculateSellingPrice(
            $product,
            $request->quantity,
            $request->unit_cost
        );

        $sale = $this->saleRepository->createSale([
            'coffee_product_id' => $product->coffeeProductId,
            'quantity' => $request->quantity,
            'unit_cost' => $request->unit_cost,
            'selling_price' => $sellingPrice,
            'user_id' => auth()->id()
        ]);

        return $this->createResponse($request, $sale, $sellingPrice);

        } catch (InvalidProfitMarginException $e) {
            return $this->handleError($request, $e->getMessage(), 422);
        } catch (ModelNotFoundException $e) {
            return $this->handleError($request, 'Product not found', 404);
        } catch (\Exception $e) {
            return $this->handleError($request, 'Server error', 500);
        }
    }

    private function handleError($request, string $message, int $code)
{
    if ($request->wantsJson()) {
        return response()->json([
            'success' => false,
            'error' => $message
        ], $code);
    }

    return redirect()
        ->back()
        ->with('error', $message);
}

    private function formatSalesData(SupportCollection $sales): array
    {
        return $sales->map(fn ($sale) => [
            'id' => $sale->saleId,
            'product_name' => $sale->coffeeProduct?->name ?? 'N/A', // Handle possible null
            'quantity' => $sale->quantity,
            'unit_cost' => $sale->unitCost,
            'selling_price' => $sale->sellingPrice,
            'created_at' => $sale->createdAt,
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
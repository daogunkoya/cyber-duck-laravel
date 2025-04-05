<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoffeeSaleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    Route::post('/coffee-sales/calculate', [CoffeeSaleController::class, 'calculate']);
    Route::post('/coffee-sales', [CoffeeSaleController::class, 'store']);
    Route::get('/coffee-products', fn() => \App\Models\CoffeeProduct::all());

    return $request->user();
});

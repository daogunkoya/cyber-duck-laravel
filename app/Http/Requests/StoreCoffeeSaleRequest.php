<?php
// app/Http/Requests/StoreCoffeeSaleRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCoffeeSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'coffee_product_id' => 'required|exists:coffee_products,id',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0.01',
        ];
    }

    public function messages(): array
    {
        return [
            'unit_cost.min' => 'Unit cost must be at least £0.01',
            'quantity.min' => 'Quantity must be at least 1',
        ];
    }
}
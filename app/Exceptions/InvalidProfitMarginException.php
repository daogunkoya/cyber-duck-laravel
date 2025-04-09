<?php
// app/Exceptions/InvalidProfitMarginException.php
namespace App\Exceptions;

class InvalidProfitMarginException extends \InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct("Profit margin must be between 0 and 1 (exclusive)");
    }
}

// Update PriceCalculationService
if ($product->profitMargin <= 0 || $product->profitMargin >= 1) {
    throw new InvalidProfitMarginException();
}
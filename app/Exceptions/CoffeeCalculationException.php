<?php
// app/Exceptions/CoffeeCalculationException.php
namespace App\Exceptions;

use Exception;

class CoffeeCalculationException extends Exception
{
    public static function invalidProfitMargin(): self
    {
        return new self('Profit margin must be between 0 and 1');
    }
}
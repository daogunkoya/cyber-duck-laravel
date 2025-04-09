<?php
// app/Enums/CoffeeType.php
namespace App\Enums;

enum CoffeeType: string
{
    case GOLD = 'gold';
    case ARABIC = 'arabic';
    
    public function profitMargin(): float
    {
        return match($this) {
            self::GOLD => 0.25,
            self::ARABIC => 0.15,
        };
    }
    
    public function shippingCost(): float
    {
        return 10.00; // Could also vary by product
    }
}
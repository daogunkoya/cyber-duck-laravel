<?php

// app/Models/CoffeeProduct.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CoffeeProduct extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'profit_margin',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'profit_margin' => 'decimal:2',
    ];

    /**
     * Relationship with coffee sales
     */
    public function sales(): HasMany
    {
        return $this->hasMany(CoffeeSale::class);
    }

    /**
     * Get profit margin as percentage
     */
    public function getProfitMarginPercentageAttribute(): float
    {
        return $this->profit_margin * 100;
    }
}
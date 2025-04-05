<?php

// app/Models/CoffeeSale.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoffeeSale extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'coffee_product_id',
        'quantity',
        'unit_cost',
        'selling_price',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'quantity' => 'integer',
        'unit_cost' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with coffee product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(CoffeeProduct::class, 'coffee_product_id');
    }

    /**
     * Calculate cost amount (quantity * unit_cost)
     */
    public function getCostAttribute(): float
    {
        return $this->quantity * $this->unit_cost;
    }

    /**
     * Calculate profit amount (selling_price - cost - shipping)
     */
    public function getProfitAttribute(): float
    {
        return $this->selling_price - $this->cost - config('coffee.shipping_cost');
    }

    /**
     * Scope for sales of a specific product type
     */
    public function scopeForProduct($query, string $productName)
    {
        return $query->whereHas('product', function($q) use ($productName) {
            $q->where('name', $productName);
        });
    }

    public function scopeForCurrentUser($query)
{
    return $query->where('user_id', auth()->id());
}
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'name',
        'variant_name',
        'sku',
        'variant_sku',
        'barcode',
        'cost_price',
        'selling_price',
        'price_modifier',
        'stock_quantity',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'price_modifier' => 'decimal:2',
        'stock_quantity' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function serials(): HasMany
    {
        return $this->hasMany(ProductSerial::class);
    }
}

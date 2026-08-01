<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'business_id',
        'category_id',
        'category_name',
        'manufacturer',
        'color',
        'condition',
        'storage',
        'name',
        'barcode',
        'sku',
        'type',
        'inventory_tracking',
        'has_serial',
        'is_taxable',
        'manage_stock',
        'stock_quantity',
        'need_qty',
        'on_po_qty',
        'min_stock_level',
        'cost_price',
        'selling_price',
        'min_sales_price',
        'description',
        'alert_message',
        'is_active',
    ];

    protected $casts = [
        'business_id' => 'integer',
        'category_id' => 'integer',
        'manage_stock' => 'boolean',
        'is_active' => 'boolean',
        'stock_quantity' => 'integer',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
    ];

    /**
     * Boot multi-tenancy global scope.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('business', function (Builder $builder) {
            // Default business_id context for single tenant or multi-tenant session
            $businessId = session('active_business_id', 1);
            $builder->where('products.business_id', $businessId);
        });

        static::creating(function (Product $product) {
            if (!$product->business_id) {
                $product->business_id = session('active_business_id', 1);
            }
        });
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function serials(): HasMany
    {
        return $this->hasMany(ProductSerial::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}

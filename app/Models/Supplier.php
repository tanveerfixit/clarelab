<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'email',
        'phone',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('business', function (Builder $builder) {
            $businessId = session('active_business_id', 1);
            $builder->where('suppliers.business_id', $businessId);
        });

        static::creating(function (Supplier $supplier) {
            if (!$supplier->business_id) {
                $supplier->business_id = session('active_business_id', 1);
            }
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}

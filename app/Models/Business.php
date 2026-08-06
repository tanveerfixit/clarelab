<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
        'currency',
        'timezone',
        'date_format',
        'time_format',
        'language',
        'street_address',
        'city',
        'state',
        'zip_code',
        'country',
        'label_size',
        'barcode_length',
        'margin_top',
        'margin_left',
        'margin_bottom',
        'margin_right',
        'orientation',
        'font_size',
        'font_family',
    ];

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'slug',
        'subdomain',
        'logo_path',
        'color_primary',
        'color_secondary',
        'invoice_prefix',
        'invoice_next_number',
        'receipt_header',
        'receipt_footer',
        'address',
        'phone',
        'email',
        'opening_hours',
        'status',
    ];

    protected $casts = [
        'business_id' => 'integer',
        'invoice_next_number' => 'integer',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function serials(): HasMany
    {
        return $this->hasMany(ProductSerial::class);
    }
}

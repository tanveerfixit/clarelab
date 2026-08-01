<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpeedGridOption extends Model
{
    protected $fillable = [
        'tile_id',
        'product_id',
        'label',
        'price',
        'bg_color',
        'text_color',
        'requires_variant',
        'sort_order',
    ];

    public function tile(): BelongsTo
    {
        return $this->belongsTo(SpeedGridTile::class, 'tile_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SpeedGridTile extends Model
{
    protected $fillable = [
        'name',
        'bg_color',
        'text_color',
        'sort_order',
        'is_active',
    ];

    public function options(): HasMany
    {
        return $this->hasMany(SpeedGridOption::class, 'tile_id')->orderBy('sort_order', 'asc');
    }
}

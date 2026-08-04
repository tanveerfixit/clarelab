<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepairTicketItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'repair_ticket_id',
        'product_id',
        'name',
        'type',
        'unit_price',
        'quantity',
        'total_price',
    ];

    protected $casts = [
        'unit_price' => 'float',
        'total_price' => 'float',
        'quantity' => 'integer',
        'repair_ticket_id' => 'integer',
        'product_id' => 'integer',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(RepairTicket::class, 'repair_ticket_id');
    }
}

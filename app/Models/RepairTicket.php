<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RepairTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'branch_id',
        'ticket_number',
        'customer_name',
        'phone_number',
        'email_address',
        'device_model',
        'problem_description',
        'part_needed',
        'total_quote',
        'deposit_paid',
        'status',
    ];

    protected $casts = [
        'total_quote' => 'float',
        'deposit_paid' => 'float',
        'business_id' => 'integer',
        'branch_id' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'ticket_number';
    }

    public function getRemainingBalanceAttribute(): float
    {
        return max(0.00, round(($this->total_quote ?: 0.00) - ($this->deposit_paid ?: 0.00), 2));
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(RepairTicketItem::class, 'repair_ticket_id');
    }
}

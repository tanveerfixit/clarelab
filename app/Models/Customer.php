<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'slug',
        'company',
        'phone',
        'secondary_phone',
        'email',
        'landline',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getStatusAttribute(): string
    {
        $repairs = $this->repairs;
        if ($repairs->isEmpty()) {
            return 'Completed';
        }
        
        $statuses = $repairs->pluck('status')->toArray();
        if (in_array('In Progress', $statuses, true)) {
            return 'Processing';
        }
        if (in_array('Received', $statuses, true)) {
            return 'Booked';
        }
        
        return 'Completed';
    }

    /**
     * Get the repair tickets for the customer.
     */
    public function repairs(): HasMany
    {
        return $this->hasMany(RepairTicket::class, 'customer_id');
    }

    /**
     * Get the invoices for the customer.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'customer_id');
    }
}

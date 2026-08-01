<?php

namespace App\Livewire\Pos;

use Livewire\Component;
use Livewire\Attributes\Computed;

class CartItemModal extends Component
{
    public bool $isOpen = false;
    public ?int $itemIndex = null;

    // State Variables
    public ?float $unit_price = 0.00;
    public ?float $quantity = 1.00;
    public ?float $discount_amount = 0.00;
    public string $discount_type = 'percentage'; // 'percentage' or 'fixed'
    public ?string $description = '';

    protected $listeners = [
        'openCartItemModal' => 'open',
    ];

    public function open(int $index, array $item)
    {
        $this->itemIndex = $index;
        $this->unit_price = floatval($item['price'] ?? 0.00);
        $this->quantity = floatval($item['quantity'] ?? 1.00);
        $this->discount_amount = floatval($item['discount_amount'] ?? 0.00);
        $this->discount_type = $item['discount_type'] ?? 'percentage';
        $this->description = $item['description'] ?? '';
        
        $this->isOpen = true;
    }

    public function close()
    {
        $this->isOpen = false;
        $this->reset(['itemIndex', 'unit_price', 'quantity', 'discount_amount', 'discount_type', 'description']);
    }

    #[Computed]
    public function subtotal(): float
    {
        return round(($this->unit_price ?: 0.00) * ($this->quantity ?: 0.00), 2);
    }

    #[Computed]
    public function calculated_discount_value(): float
    {
        $sub = $this->subtotal;
        $amount = $this->discount_amount ?: 0.00;

        if ($this->discount_type === 'percentage') {
            return round($sub * ($amount / 100), 2);
        }

        return round(min($amount, $sub), 2);
    }

    #[Computed]
    public function total(): float
    {
        return round($this->subtotal - $this->calculated_discount_value, 2);
    }

    public function save()
    {
        $this->validate([
            'quantity' => 'required|numeric|min:0.01',
            'unit_price' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_type' => 'required|in:percentage,fixed',
            'description' => 'nullable|string|max:500',
        ]);

        $this->dispatch('cartItemUpdated', [
            'index' => $this->itemIndex,
            'price' => round($this->unit_price, 2),
            'quantity' => $this->quantity,
            'discount_amount' => round($this->discount_amount ?: 0.00, 2),
            'discount_type' => $this->discount_type,
            'description' => $this->description ?: '',
            'total' => $this->total
        ]);

        $this->close();
    }

    public function render()
    {
        return view('components.pos.⚡cart-item-modal');
    }
}

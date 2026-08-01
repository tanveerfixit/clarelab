<?php

namespace App\Livewire\Pos;

use Livewire\Component;
use App\Models\SpeedGridTile;
use App\Models\SpeedGridOption;

class SpeedGrid extends Component
{
    /**
     * Decoupled speed grid tiles collection with eager-loaded options.
     */
    public array $tiles = [];

    public function mount()
    {
        $this->loadGridData();
    }

    public function loadGridData()
    {
        $this->tiles = SpeedGridTile::with(['options' => function($q) {
            $q->orderBy('sort_order', 'asc');
        }])
        ->where('is_active', true)
        ->orderBy('sort_order', 'asc')
        ->get()
        ->toArray();
    }

    /**
     * Handle sub-option click. Emits quick add payload to parent POS component or handles quick addition.
     */
    public function handleSpeedGridSelection(int $optionId)
    {
        $option = SpeedGridOption::find($optionId);
        if (!$option) return;

        // Dispatch event to main CashRegister component to add to active cart
        $this->dispatch('speedGridItemSelected', [
            'option_id' => $option->id,
            'product_id' => $option->product_id,
            'name' => $option->label,
            'price' => (float)$option->price,
            'requires_variant' => (bool)$option->requires_variant,
        ]);
    }

    public function render()
    {
        return view('components.pos.⚡speed-grid');
    }
}

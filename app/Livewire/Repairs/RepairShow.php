<?php

namespace App\Livewire\Repairs;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\RepairTicket;
use App\Models\RepairTicketItem;
use App\Services\BranchContext;

#[Layout('components.layouts.app', ['header' => 'Repair Ticket Details'])]
class RepairShow extends Component
{
    public RepairTicket $ticket;

    // Line Item Form Fields
    public string $newItemName = '';
    public string $newItemType = 'service'; // service or part
    public ?float $newItemPrice = 0.00;
    public int $newItemQty = 1;

    // Search query for autocomplete
    public string $partsSearchQuery = '';

    // Selected Product to pre-fill Name & Price
    public ?int $selectedProductId = null;

    public function updatedPartsSearchQuery($value)
    {
        $this->newItemName = $value;
    }

    public function updatedNewItemType($value)
    {
        $this->selectedProductId = null;
        $this->newItemName = '';
        $this->newItemPrice = 0.00;
        $this->partsSearchQuery = '';
    }

    public function selectSearchedProduct(int $productId)
    {
        $product = \App\Models\Product::find($productId);
        if ($product) {
            $this->selectedProductId = $product->id;
            $this->newItemName = $product->name;
            $this->partsSearchQuery = $product->name;
            $this->newItemPrice = floatval($product->selling_price);
        }
    }

    public function mount($ticket)
    {
        if ($ticket instanceof RepairTicket) {
            $record = $ticket;
        } else {
            $record = RepairTicket::with('items')
                ->where(function ($q) use ($ticket) {
                    $q->where('ticket_number', (string)$ticket)
                      ->orWhere('id', (string)$ticket);
                })
                ->first();

            if (!$record) {
                $branchId = BranchContext::current()?->id ?: 1;
                $businessId = BranchContext::current()?->business_id ?: 1;

                $record = RepairTicket::create([
                    'business_id' => $businessId,
                    'branch_id' => $branchId,
                    'ticket_number' => (string)$ticket,
                    'customer_name' => 'John Doe',
                    'phone_number' => '087 123 4567',
                    'email_address' => 'john@example.com',
                    'device_model' => 'iPhone 13 Pro',
                    'problem_description' => 'Screen glass cracked & OLED touch non-responsive',
                    'part_needed' => 'iPhone 13 Pro OLED Screen Assembly',
                    'total_quote' => 120.00,
                    'deposit_paid' => 20.00,
                    'status' => 'Received',
                ]);
            }
        }

        $this->ticket = $record;
        $this->selectedStatus = $record->status;

        $this->ensureDefaultItemsIfEmpty();
    }

    private function ensureDefaultItemsIfEmpty()
    {
        if ($this->ticket->items->count() === 0 && $this->ticket->total_quote > 0) {
            // Create default service labor item from ticket quote
            $laborPrice = max(30.00, round($this->ticket->total_quote * 0.4, 2));
            $partPrice = max(0.00, round($this->ticket->total_quote - $laborPrice, 2));

            RepairTicketItem::create([
                'repair_ticket_id' => $this->ticket->id,
                'name' => "Service & Diagnostic Labor",
                'type' => 'service',
                'unit_price' => $laborPrice,
                'quantity' => 1,
                'total_price' => $laborPrice,
            ]);

            if ($partPrice > 0) {
                RepairTicketItem::create([
                    'repair_ticket_id' => $this->ticket->id,
                    'name' => $this->ticket->part_needed ?: "Replacement Part / Screen",
                    'type' => 'part',
                    'unit_price' => $partPrice,
                    'quantity' => 1,
                    'total_price' => $partPrice,
                ]);
            }

            $this->ticket->refresh();
        }
    }

    public function addItem()
    {
        $this->validate([
            'newItemName' => 'required|string|max:255',
            'newItemType' => 'required|in:service,part',
            'newItemPrice' => 'required|numeric|min:0',
            'newItemQty' => 'required|integer|min:1',
        ]);

        $unitPrice = floatval($this->newItemPrice);
        $qty = intval($this->newItemQty);
        $totalPrice = round($unitPrice * $qty, 2);

        RepairTicketItem::create([
            'repair_ticket_id' => $this->ticket->id,
            'name' => $this->newItemName,
            'type' => $this->newItemType,
            'unit_price' => $unitPrice,
            'quantity' => $qty,
            'total_price' => $totalPrice,
            'product_id' => $this->selectedProductId,
        ]);

        // Recalculate ticket total_quote
        $newTotal = RepairTicketItem::where('repair_ticket_id', $this->ticket->id)->sum('total_price');
        $this->ticket->update(['total_quote' => $newTotal]);

        $this->reset(['newItemName', 'newItemPrice', 'selectedProductId', 'partsSearchQuery']);
        $this->newItemQty = 1;
        $this->newItemType = 'service';
        $this->ticket->refresh();

        $this->dispatch('show-toast', message: 'Repair item added successfully!');
    }

    public function removeItem(int $itemId)
    {
        RepairTicketItem::where('repair_ticket_id', $this->ticket->id)->where('id', $itemId)->delete();

        // Recalculate ticket total_quote
        $newTotal = RepairTicketItem::where('repair_ticket_id', $this->ticket->id)->sum('total_price');
        $this->ticket->update(['total_quote' => $newTotal]);

        $this->ticket->refresh();
        $this->dispatch('show-toast', message: 'Item removed from repair ticket.');
    }

    public function updateStatus()
    {
        $this->ticket->update(['status' => $this->selectedStatus]);
        $this->dispatch('show-toast', message: "Ticket #{$this->ticket->ticket_number} status updated to {$this->selectedStatus}!");
    }

    public function checkoutAtRegister()
    {
        $cart = [];

        foreach ($this->ticket->items as $item) {
            $cart[] = [
                'id' => $item->product_id ? $item->product_id : ('REP-ITEM-' . $item->id),
                'name' => "{$this->ticket->device_model}: {$item->name}",
                'price' => $item->unit_price,
                'quantity' => $item->quantity,
                'has_serial' => false,
            ];
        }

        if (empty($cart)) {
            $cart[] = [
                'id' => 'REP-TICKET-' . $this->ticket->id,
                'name' => "Repair Service: {$this->ticket->device_model}",
                'price' => $this->ticket->total_quote ?: 0.00,
                'quantity' => 1,
                'has_serial' => false,
            ];
        }

        session([
            'pos_cart' => $cart,
            'pos_customer_name' => $this->ticket->customer_name,
            'pos_customer_phone' => $this->ticket->phone_number,
            'pos_repair_deposit' => $this->ticket->deposit_paid ?: 0.00,
            'repair_checkout_ticket_id' => $this->ticket->id,
        ]);

        $this->dispatch('show-toast', message: 'Transferring repair items to Cash Register...');

        return redirect()->to('/register');
    }

    public function render()
    {
        $searchResults = [];
        $queryStr = trim($this->partsSearchQuery);

        if (strlen($queryStr) >= 1) {
            $productsQuery = \App\Models\Product::query();
            if ($this->newItemType === 'service') {
                $productsQuery->where(function ($q) {
                    $q->where('type', 'service')
                      ->orWhere('inventory_tracking', 'labor')
                      ->orWhere('inventory_tracking', 'service');
                });
            } else {
                // Tracked physical items/spare parts
                $productsQuery->where(function ($q) {
                    $q->where('type', '!=', 'service')
                      ->whereNotIn('inventory_tracking', ['labor', 'service']);
                });
            }

            $searchResults = $productsQuery->where(function ($q) use ($queryStr) {
                $q->where('name', 'like', "%{$queryStr}%")
                  ->orWhere('sku', 'like', "%{$queryStr}%")
                  ->orWhere('barcode', 'like', "%{$queryStr}%");
            })
            ->limit(8)
            ->get();
        }

        return view('components.repairs.⚡repair-show', [
            'searchResults' => $searchResults,
        ]);
    }
}

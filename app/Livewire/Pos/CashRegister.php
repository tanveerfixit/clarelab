<?php

namespace App\Livewire\Pos;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use App\Modules\Pos\Services\CheckoutService;
use App\Modules\Inventory\Exceptions\InsufficientStockException;

class CashRegister extends Component
{
    public string $searchQuery = '';
    public string $customerSearch = '';
    public ?int $selectedCustomerId = null;
    public string $taxClass = 'Vat0 (0.000%)';
    public float $taxRate = 0.00;

    // Active cart items
    public array $cart = [];

    // Right Sidebar Interactive Payment State
    public string $sidebarPaymentMethod = 'Debit Card'; // 'Debit Card', 'Cash', 'Other'
    public string $sidebarPaymentInput = '';

    // Applied payment lines array for split payments in right sidebar
    public array $sidebarAppliedPayments = [];

    // Section Expand/Collapse State (Activity Log and Speed Grid collapsed by default)
    public bool $cartExpanded = true;
    public bool $activityLogExpanded = false;
    public bool $showSpeedGrid = false;

    public function toggleSpeedGrid()
    {
        $this->showSpeedGrid = !$this->showSpeedGrid;
    }

    // Comprehensive Activity Logs array
    public array $activityLogs = [];

    public ?string $errorMessage = null;

    // Receipt Modal State
    public bool $showReceiptModal = false;
    public ?array $receiptData = null;

    protected $listeners = [
        'saleFinalized' => 'handleSaleFinalized',
        'cartItemUpdated' => 'handleCartItemUpdated',
        'speedGridItemSelected' => 'handleSpeedGridItemSelected',
    ];

    public function handleSpeedGridItemSelected($payload)
    {
        // Handle array wrapped payloads from Livewire events
        if (isset($payload[0])) {
            $payload = $payload[0];
        }

        $name = $payload['name'] ?? 'Quick Item';
        $price = (float)($payload['price'] ?? 0.00);

        // Check if item already exists in cart
        $found = false;
        foreach ($this->cart as $index => $item) {
            if ($item['name'] === $name) {
                $this->cart[$index]['quantity'] += 1;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $this->cart[] = [
                'id' => rand(9000, 9999),
                'product_id' => $payload['product_id'] ?? null,
                'name' => $name,
                'price' => $price,
                'quantity' => 1,
                'discount_amount' => 0.00,
                'description' => '',
            ];
        }

        $this->logActivity('Speed Grid Added', "{$name} (€" . number_format($price, 2) . ")");
    }

    public function openCartItemModal(int $index)
    {
        if (isset($this->cart[$index])) {
            $this->dispatch('openCartItemModal', index: $index, item: $this->cart[$index]);
        }
    }

    public function handleCartItemUpdated(array $payload)
    {
        $idx = $payload['index'];
        if (isset($this->cart[$idx])) {
            $this->cart[$idx]['price'] = $payload['price'];
            $this->cart[$idx]['quantity'] = $payload['quantity'];
            $this->cart[$idx]['discount_amount'] = $payload['discount_amount'];
            $this->cart[$idx]['discount_type'] = $payload['discount_type'];
            $this->cart[$idx]['description'] = $payload['description'];

            $changes = [];
            $changes[] = "Price: €" . number_format($payload['price'], 2);
            $changes[] = "Qty: " . $payload['quantity'];
            if (!empty($payload['discount_amount']) && $payload['discount_amount'] > 0) {
                $discText = $payload['discount_type'] === 'percentage' ? "{$payload['discount_amount']}%" : "€{$payload['discount_amount']}";
                $changes[] = "Discount: {$discText}";
            }
            if (!empty($payload['description'])) {
                $changes[] = "Note: '{$payload['description']}'";
            }

            $detailsStr = "{$this->cart[$idx]['name']} (" . implode(', ', $changes) . ")";
            $this->logActivity('Cart Item Updated', $detailsStr);
            $this->syncSidebarPaymentInput();
        }
    }

    public function mount()
    {
        if (session()->has('pos_cart')) {
            $this->cart = session('pos_cart');
            $custName = session('pos_customer_name', '');
            $custPhone = session('pos_customer_phone', '');
            $deposit = session('pos_repair_deposit', 0.00);

            if ($custName) {
                $this->customerSearch = "{$custName}" . ($custPhone ? " ({$custPhone})" : "");
            }

            if ($deposit > 0) {
                // Apply deposit credit item to cart
                $this->cart[] = [
                    'id' => 'deposit-credit-' . rand(1000, 9999),
                    'name' => "Deposit Credit Already Paid",
                    'price' => -abs($deposit),
                    'quantity' => 1,
                    'discount_amount' => 0.00,
                    'description' => 'Deposit credit deducted from repair total',
                ];
            }

            $this->logActivity('Repair Ticket Loaded', "Loaded repair items for " . ($custName ?: 'Customer'));
            $this->syncSidebarPaymentInput();
            session()->forget(['pos_cart', 'pos_customer_name', 'pos_customer_phone', 'pos_repair_deposit']);
        }
    }

    public function toggleCart()
    {
        $this->cartExpanded = !$this->cartExpanded;
    }

    public function toggleActivityLog()
    {
        $this->activityLogExpanded = !$this->activityLogExpanded;
    }

    protected function logActivity(string $action, string $details): void
    {
        array_unshift($this->activityLogs, [
            'date' => now()->format('d-m-y'),
            'time' => now()->format('g:i a'),
            'user' => 'Phone Lab',
            'activity' => $action,
            'details' => $details,
        ]);
    }

    #[Computed]
    public function searchResults(): array
    {
        $query = trim($this->searchQuery);

        if (strlen($query) < 1) {
            return [];
        }

        return DB::table('products')
            ->leftJoin('branch_stocks', 'products.id', '=', 'branch_stocks.product_id')
            ->select('products.id', 'products.name', 'products.sku', 'products.barcode', 'products.selling_price', 'branch_stocks.quantity as stock_qty')
            ->where(function($q) use ($query) {
                $q->where('products.name', 'like', "%{$query}%")
                  ->orWhere('products.sku', 'like', "%{$query}%")
                  ->orWhere('products.barcode', 'like', "%{$query}%");
            })
            ->limit(8)
            ->get()
            ->map(fn($item) => (array)$item)
            ->toArray();
    }

    public function getSearchResultsProperty(): array
    {
        return $this->searchResults;
    }

    public function selectProduct(int $productId)
    {
        $product = DB::table('products')->where('id', $productId)->first();
        if (!$product) return;

        foreach ($this->cart as $index => $item) {
            if ($item['id'] === $productId) {
                $oldQty = $this->cart[$index]['quantity'];
                $this->cart[$index]['quantity']++;
                $newQty = $this->cart[$index]['quantity'];
                
                $this->logActivity(
                    'Quantity Increased',
                    "{$product->name} (Qty: {$oldQty} → {$newQty})"
                );
                $this->searchQuery = '';
                $this->syncSidebarPaymentInput();
                return;
            }
        }

        $this->cart[] = [
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku ?? 'SKU-' . $product->id,
            'price' => (float)$product->selling_price,
            'quantity' => 1,
        ];

        $this->logActivity(
            'Item Added',
            "Added {$product->name} @ €" . number_format($product->selling_price, 2)
        );

        $this->searchQuery = '';
        $this->syncSidebarPaymentInput();
    }

    public function updateQuantity(int $index, int $delta)
    {
        if (!isset($this->cart[$index])) return;

        $item = $this->cart[$index];
        $oldQty = $item['quantity'];
        $newQty = $oldQty + $delta;

        if ($newQty <= 0) {
            $this->removeFromCart($index);
        } else {
            $this->cart[$index]['quantity'] = $newQty;
            $action = $delta > 0 ? 'Quantity Increased' : 'Quantity Decreased';
            $this->logActivity(
                $action,
                "{$item['name']} (Qty: {$oldQty} → {$newQty})"
            );
        }
        $this->syncSidebarPaymentInput();
    }

    public function removeFromCart(int $index)
    {
        if (isset($this->cart[$index])) {
            $item = $this->cart[$index];
            unset($this->cart[$index]);
            $this->cart = array_values($this->cart);

            $this->logActivity(
                'Item Removed',
                "Removed {$item['name']} (Qty: {$item['quantity']}) from cart"
            );
        }
        $this->syncSidebarPaymentInput();
    }

    public function clearCart()
    {
        if (!empty($this->cart)) {
            $itemCount = count($this->cart);
            $this->cart = [];
            $this->sidebarAppliedPayments = [];
            $this->logActivity('Sale Discarded', "Transaction discarded ({$itemCount} item(s) removed)");
        }
        $this->reset(['errorMessage']);
        $this->syncSidebarPaymentInput();
    }

    public function setSidebarPaymentMethod(string $method)
    {
        $this->sidebarPaymentMethod = $method;
        $this->syncSidebarPaymentInput();
    }

    /**
     * Non-cached helper: directly sums applied payments to avoid Livewire computed cache issues.
     */
    private function getSidebarTotalPaidDirect(): float
    {
        return round(array_sum(array_column($this->sidebarAppliedPayments, 'amount')), 2);
    }

    public function syncSidebarPaymentInput()
    {
        $remaining = round($this->grandTotal - $this->getSidebarTotalPaidDirect(), 2);
        $this->sidebarPaymentInput = $remaining > 0
            ? number_format($remaining, 2, '.', '')
            : '';
    }

    public function applySidebarPayment()
    {
        $this->reset(['errorMessage']);
        $remaining = round($this->grandTotal - $this->getSidebarTotalPaidDirect(), 2);

        if ($remaining <= 0) {
            return; // silently ignore — fully paid
        }

        $val = round(floatval($this->sidebarPaymentInput), 2);
        if ($val <= 0) {
            $val = $remaining; // default: pay full remaining
        }

        // If Cash, allow taking more than remaining to calculate change. Otherwise, cap it.
        if ($this->sidebarPaymentMethod === 'Cash') {
            $applied = $val; // Keep full cash tendered amount
        } else {
            $applied = min($val, $remaining); // Card/Other capped to remaining
        }

        $this->sidebarAppliedPayments[] = [
            'method' => $this->sidebarPaymentMethod,
            'amount' => $applied,
            'time'   => now()->format('g:i a'),
        ];

        // Directly compute new remaining (capping at 0)
        $newRemaining = max(0.00, round($this->grandTotal - $this->getSidebarTotalPaidDirect(), 2));
        $this->sidebarPaymentInput = $newRemaining > 0
            ? number_format($newRemaining, 2, '.', '')
            : '';
    }

    public function removeSidebarPayment(int $index)
    {
        if (isset($this->sidebarAppliedPayments[$index])) {
            unset($this->sidebarAppliedPayments[$index]);
            $this->sidebarAppliedPayments = array_values($this->sidebarAppliedPayments);
            $this->syncSidebarPaymentInput();
        }
    }

    #[Computed]
    public function sidebarTotalPaid(): float
    {
        return $this->getSidebarTotalPaidDirect();
    }

    #[Computed]
    public function sidebarRemainingBalance(): float
    {
        return max(0.00, round($this->grandTotal - $this->getSidebarTotalPaidDirect(), 2));
    }

    #[Computed]
    public function changeDue(): float
    {
        $paid = $this->getSidebarTotalPaidDirect();
        return $paid > $this->grandTotal ? round($paid - $this->grandTotal, 2) : 0.00;
    }

    #[Computed]
    public function isSidebarFullyPaid(): bool
    {
        return $this->grandTotal > 0 && ($this->getSidebarTotalPaidDirect() >= $this->grandTotal);
    }

    // Dynamic Tax Option Selection state
    public string $taxSelection = '0.000_exclusive'; // e.g. "13.000_inclusive", "0.000_exclusive", "23.000_inclusive"

    public function updatedTaxSelection()
    {
        // Parse class values
        $parts = explode('_', $this->taxSelection);
        $rate = floatval($parts[0]);
        $this->taxRate = $rate;

        if ($this->taxSelection === '13.000_inclusive') {
            $this->taxClass = 'Product Tax (13.000% Inclusive)';
        } elseif ($this->taxSelection === '23.000_inclusive') {
            $this->taxClass = 'Vat2 (23.000% Inclusive)';
        } else {
            $this->taxClass = 'Vat0 (0.000%)';
        }

        // Recalculate payments input due to new totals
        $this->syncSidebarPaymentInput();
    }

    #[Computed]
    public function totalQuantity(): int
    {
        return array_reduce($this->cart, fn($carry, $item) => $carry + $item['quantity'], 0);
    }

    #[Computed]
    public function subtotal(): float
    {
        // subtotal sum using the dynamic item price before line discounts
        return array_reduce($this->cart, fn($carry, $item) => $carry + ($item['price'] * $item['quantity']), 0.00);
    }

    #[Computed]
    public function discountAmount(): float
    {
        return array_reduce($this->cart, function($carry, $item) {
            $sub = $item['price'] * $item['quantity'];
            $disc = 0.00;
            if (isset($item['discount_amount']) && $item['discount_amount'] > 0) {
                if ($item['discount_type'] === 'percentage') {
                    $disc = $sub * ($item['discount_amount'] / 100);
                } else {
                    $disc = min($item['discount_amount'], $sub);
                }
            }
            return $carry + $disc;
        }, 0.00);
    }

    #[Computed]
    public function taxableTotal(): float
    {
        $base = max(0.00, $this->subtotal - $this->discountAmount);
        
        // If tax is inclusive, base taxable amount is subtotal - tax portion
        if (str_contains($this->taxSelection, 'inclusive')) {
            return round($base / (1 + ($this->taxRate / 100)), 2);
        }
        
        return $base;
    }

    #[Computed]
    public function taxAmount(): float
    {
        $base = max(0.00, $this->subtotal - $this->discountAmount);

        if (str_contains($this->taxSelection, 'inclusive')) {
            // Inclusive tax is extracted out from the total
            return round($base - $this->taxableTotal, 2);
        }

        // Exclusive tax is added on top
        return round($this->taxableTotal * ($this->taxRate / 100), 2);
    }

    #[Computed]
    public function grandTotal(): float
    {
        if (str_contains($this->taxSelection, 'inclusive')) {
            // Grand total is exactly the base taxable sum since tax is inclusive
            return max(0.00, round($this->subtotal - $this->discountAmount, 2));
        }

        return max(0.00, round($this->taxableTotal + $this->taxAmount, 2));
    }



    public function openCheckoutModal()
    {
        $this->reset(['errorMessage']);

        if (empty($this->cart)) {
            $this->errorMessage = "Cannot checkout with an empty cart.";
            return;
        }

        // Strictly enforce: Modal WILL NOT pop up until full payment is taken (remaining balance is 0)
        if (!$this->isSidebarFullyPaid) {
            $this->errorMessage = "Cannot review sale: Full payment has not been taken yet. Remaining balance: €" . number_format($this->sidebarRemainingBalance, 2);
            return;
        }

        // Primary method description for applied payments
        $methods = array_unique(array_column($this->sidebarAppliedPayments, 'method'));
        $methodLabel = count($methods) === 1 ? $methods[0] : implode(' + ', $methods);

        $this->dispatch(
            'openCheckoutModal',
            cart: $this->cart,
            grandTotal: $this->grandTotal,
            subtotal: $this->subtotal,
            taxAmount: $this->taxAmount,
            taxClass: $this->taxClass,
            paymentMethod: $methodLabel,
            appliedPayments: $this->sidebarAppliedPayments,
            changeDue: $this->changeDue
        );
    }

    public function handleSaleFinalized(array $receiptData, string $printType)
    {
        $itemSummary = array_map(fn($i) => "{$i['quantity']}x {$i['name']}", $this->cart);
        $detailsText = implode(', ', $itemSummary) . " | Total: €" . number_format($receiptData['grand_total'], 2);

        $this->logActivity(
            'Sale Completed',
            "Invoice #{$receiptData['invoice_number']} - {$detailsText} ({$printType})"
        );

        if (session()->has('repair_checkout_ticket_id')) {
            $ticketId = session('repair_checkout_ticket_id');
            \App\Models\RepairTicket::where('id', $ticketId)->update(['status' => 'Completed']);
            $this->logActivity('Repair Ticket Completed', "Ticket #{$ticketId} status auto-updated to Completed");
            session()->forget('repair_checkout_ticket_id');
        }

        $this->cart = [];
        $this->sidebarAppliedPayments = [];
        $this->searchQuery = '';
        $this->customerSearch = '';
        $this->selectedCustomerId = null;
        $this->syncSidebarPaymentInput();

        $msg = "Sale finalized successfully! Invoice #{$receiptData['invoice_number']}";
        $this->dispatch('show-toast', message: $msg);

        if ($printType === 'Thermal Receipt' || $printType === 'A4 PDF Invoice') {
            $this->receiptData = $receiptData;
            $this->showReceiptModal = true;
        }
    }

    public function closeReceiptModal()
    {
        $this->showReceiptModal = false;
        $this->receiptData = null;
    }

    public function render()
    {
        return view('components.pos.⚡cash-register')
            ->layout('components.layouts.app', ['header' => 'Cash Register']);
    }
}

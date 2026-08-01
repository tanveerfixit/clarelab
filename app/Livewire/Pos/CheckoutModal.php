<?php

namespace App\Livewire\Pos;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Modules\Pos\Services\CheckoutService;
use App\Modules\Inventory\Exceptions\InsufficientStockException;

class CheckoutModal extends Component
{
    public bool $isOpen = false;

    public array $cart = [];
    public float $grandTotalAmount = 0.00;
    public float $subtotalAmount = 0.00;
    public float $taxAmountValue = 0.00;
    public string $taxClass = 'Vat0 (0.000%)';

    public string $selectedMethod = 'Debit Card';
    public array $appliedPayments = [];
    public string $receiptStyle = 'THERMAL'; // 'THERMAL' or 'A4'

    public float $discountAmount = 0.00;

    public float $changeDue = 0.00;

    public ?string $modalError = null;

    protected $listeners = [
        'openCheckoutModal' => 'open',
    ];

    public function open(array $cart, float $grandTotal, float $subtotal, float $taxAmount, string $taxClass, string $paymentMethod = 'Debit Card', array $appliedPayments = [], float $changeDue = 0.00)
    {
        $this->cart = $cart;
        $this->grandTotalAmount = round($grandTotal, 2);
        $this->subtotalAmount = round($subtotal, 2);
        $this->taxAmountValue = round($taxAmount, 2);
        $this->taxClass = $taxClass;
        $this->changeDue = round($changeDue, 2);

        // Sum up line discounts to pass down
        $this->discountAmount = array_reduce($this->cart, function($carry, $item) {
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

        $this->selectedMethod = $paymentMethod;
        $this->appliedPayments = !empty($appliedPayments) ? $appliedPayments : [
            ['method' => $paymentMethod, 'amount' => round($grandTotal, 2), 'time' => now()->format('g:i a')]
        ];
        $this->receiptStyle = 'THERMAL';
        $this->modalError = null;

        $this->isOpen = true;
    }

    public function close()
    {
        $this->isOpen = false;
    }

    public function selectReceiptStyle(string $style)
    {
        $this->receiptStyle = $style;
    }

    public function finalizeSale(CheckoutService $checkoutService)
    {
        $this->modalError = null;

        try {
            $receipt = $checkoutService->processCheckout(
                businessId: 1,
                branchId: 1,
                cart: $this->cart,
                discount: $this->discountAmount,
                paymentMethod: $this->selectedMethod
            );

            // Inject payment detail metadata for the receipt
            $receipt['change_due'] = $this->changeDue;
            $receipt['total_paid'] = $this->grandTotalAmount + $this->changeDue;

            $printType = $this->receiptStyle === 'THERMAL' ? 'Thermal Receipt' : 'A4 PDF Invoice';
            $this->dispatch('saleFinalized', receiptData: $receipt, printType: $printType);
            $this->close();

        } catch (InsufficientStockException $e) {
            $this->modalError = $e->getMessage();
        } catch (\Throwable $e) {
            $this->modalError = "Transaction error: " . $e->getMessage();
        }
    }

    public function render()
    {
        return view('components.pos.⚡checkout-modal');
    }
}

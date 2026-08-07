<?php

namespace App\Modules\Pos\Services;

use App\Modules\Inventory\Services\InventoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutService
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {}

    /**
     * Executes transaction checkout, creating invoice records and decrementing stock atomically.
     */
    public function processCheckout(int $businessId, int $branchId, array $cart, float $discount, string $paymentMethod, ?int $customerId = null, ?int $userId = null): array
    {
        return DB::transaction(function () use ($businessId, $branchId, $cart, $discount, $paymentMethod, $customerId, $userId) {
            $subtotal = 0.00;

            foreach ($cart as $item) {
                $subtotal += ($item['price'] * $item['quantity']);
                
                // Only decrement inventory if product_id is numeric (real catalog item)
                if (is_numeric($item['id'])) {
                    $this->inventoryService->decrementStock(
                        branchId: $branchId,
                        productId: (int)$item['id'],
                        quantity: $item['quantity']
                    );
                }
            }

            $taxAmount = 0.00; // Tax calculation hook
            $grandTotal = max(0.00, $subtotal - $discount + $taxAmount);
            $invoiceNumber = \App\Services\InvoiceNumberGenerator::generate();

            $invoiceId = DB::table('invoices')->insertGetId([
                'business_id' => $businessId,
                'branch_id' => $branchId,
                'customer_id' => $customerId,
                'user_id' => $userId,
                'invoice_number' => $invoiceNumber,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax_amount' => $taxAmount,
                'grand_total' => $grandTotal,
                'payment_method' => $paymentMethod,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $itemsData = [];
            foreach ($cart as $item) {
                $itemTotal = $item['price'] * $item['quantity'];
                DB::table('invoice_items')->insert([
                    'invoice_id' => $invoiceId,
                    'product_id' => is_numeric($item['id']) ? (int)$item['id'] : null,
                    'product_name' => $item['name'],
                    'unit_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total' => $itemTotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $itemsData[] = [
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total' => $itemTotal,
                    'description' => $item['description'] ?? '',
                ];
            }

            return [
                'invoice_id' => $invoiceId,
                'invoice_number' => $invoiceNumber,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax_amount' => $taxAmount,
                'grand_total' => $grandTotal,
                'payment_method' => $paymentMethod,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'items' => $itemsData,
            ];
        });
    }
}

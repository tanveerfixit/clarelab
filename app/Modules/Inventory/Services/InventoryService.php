<?php

namespace App\Modules\Inventory\Services;

use App\Modules\Core\Traits\HandlesConcurrency;
use App\Modules\Inventory\Exceptions\InsufficientStockException;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    use HandlesConcurrency;

    /**
     * Safely decrements stock for a given branch and product inside a DB transaction.
     *
     * @throws InsufficientStockException
     */
    public function decrementStock(int $branchId, int $productId, int $quantity): int
    {
        return DB::transaction(function () use ($branchId, $productId, $quantity) {
            $stockQuery = DB::table('branch_stocks')
                ->where('branch_id', $branchId)
                ->where('product_id', $productId);

            $stock = $stockQuery->first();

            $currentQty = $stock ? (int)$stock->quantity : 0;

            if ($currentQty < $quantity) {
                throw new InsufficientStockException(
                    productId: $productId,
                    requested: $quantity,
                    available: $currentQty
                );
            }

            DB::table('branch_stocks')
                ->where('branch_id', $branchId)
                ->where('product_id', $productId)
                ->decrement('quantity', $quantity);

            return $currentQty - $quantity;
        });
    }

    /**
     * Increments stock for a given branch and product, and optionally registers serial numbers.
     */
    public function incrementStock(int $branchId, int $productId, int $quantity, float $costPrice, ?int $supplierId = null, array $serialNumbers = []): void
    {
        DB::transaction(function () use ($branchId, $productId, $quantity, $costPrice, $supplierId, $serialNumbers) {
            $businessId = session('active_business_id', 1);

            // 1. Update/Insert branch stock
            $stock = DB::table('branch_stocks')
                ->where('branch_id', $branchId)
                ->where('product_id', $productId)
                ->first();

            if ($stock) {
                DB::table('branch_stocks')
                    ->where('branch_id', $branchId)
                    ->where('product_id', $productId)
                    ->increment('quantity', $quantity);
            } else {
                DB::table('branch_stocks')->insert([
                    'business_id' => $businessId,
                    'branch_id' => $branchId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'reorder_level' => 5,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 2. Update product total stock quantity & cost price (and optional supplier)
            $product = \App\Models\Product::findOrFail($productId);
            
            $productData = [
                'stock_quantity' => $product->stock_quantity + $quantity,
                'cost_price' => $costPrice,
            ];
            
            if ($supplierId) {
                $productData['supplier_id'] = $supplierId;
            }

            $product->update($productData);

            // 3. If serialized, insert the serial numbers
            if (!empty($serialNumbers)) {
                foreach ($serialNumbers as $sn) {
                    // Check if serial already exists (even if soft-deleted, or we can check active ones)
                    $exists = \App\Models\ProductSerial::where('serial_number', $sn)->exists();
                    if ($exists) {
                        throw new \Exception("Serial number '{$sn}' already exists in the system.");
                    }

                    \App\Models\ProductSerial::create([
                        'product_id' => $productId,
                        'serial_number' => $sn,
                        'status' => 'available',
                        'branch_id' => $branchId,
                        'supplier_id' => $supplierId,
                    ]);
                }
            }
        });
    }
}

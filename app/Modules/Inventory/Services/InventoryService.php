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
}

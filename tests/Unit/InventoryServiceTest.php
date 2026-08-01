<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Modules\Inventory\Services\InventoryService;
use App\Modules\Inventory\Exceptions\InsufficientStockException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class InventoryServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_decrements_stock_successfully_within_transaction(): void
    {
        $businessId = DB::table('businesses')->insertGetId(['name' => 'Test Business']);
        $branchId = DB::table('branches')->insertGetId(['business_id' => $businessId, 'name' => 'Test Branch']);
        $productId = DB::table('products')->insertGetId(['business_id' => $businessId, 'name' => 'Test Item', 'selling_price' => 10.00]);

        DB::table('branch_stocks')->insert([
            'business_id' => $businessId,
            'branch_id' => $branchId,
            'product_id' => $productId,
            'quantity' => 10,
        ]);

        $service = new InventoryService();
        $remainingStock = $service->decrementStock($branchId, $productId, 3);

        $this->assertEquals(7, $remainingStock);
        $this->assertDatabaseHas('branch_stocks', [
            'branch_id' => $branchId,
            'product_id' => $productId,
            'quantity' => 7,
        ]);
    }

    public function test_throws_insufficient_stock_exception_when_quantity_exceeds_available(): void
    {
        $businessId = DB::table('businesses')->insertGetId(['name' => 'Test Business']);
        $branchId = DB::table('branches')->insertGetId(['business_id' => $businessId, 'name' => 'Test Branch']);
        $productId = DB::table('products')->insertGetId(['business_id' => $businessId, 'name' => 'Test Item', 'selling_price' => 10.00]);

        DB::table('branch_stocks')->insert([
            'business_id' => $businessId,
            'branch_id' => $branchId,
            'product_id' => $productId,
            'quantity' => 2,
        ]);

        $this->expectException(InsufficientStockException::class);

        $service = new InventoryService();
        $service->decrementStock($branchId, $productId, 5);
    }
}

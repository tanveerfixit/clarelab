<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Wipe tables for clean re-seed
        DB::table('branch_stocks')->delete();
        DB::table('products')->delete();
        DB::table('categories')->delete();
        DB::table('customers')->delete();

        // 1. Business & Branch
        $business = DB::table('businesses')->first();
        if (!$business) {
            $businessId = DB::table('businesses')->insertGetId([
                'name' => 'Phone Lab',
                'email' => 'admin@phonelab.com',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $businessId = $business->id;
        }

        $branch = DB::table('branches')->first();
        if (!$branch) {
            $branchId = DB::table('branches')->insertGetId([
                'business_id' => $businessId,
                'name' => 'Main Store',
                'address' => 'London High St',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $branchId = $branch->id;
        }

        // 2. Categories
        $catCablesId = DB::table('categories')->insertGetId([
            'business_id' => $businessId,
            'name' => 'Cables & Adapters',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $catCasesId = DB::table('categories')->insertGetId([
            'business_id' => $businessId,
            'name' => 'Phone Cases',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $catScreenId = DB::table('categories')->insertGetId([
            'business_id' => $businessId,
            'name' => 'Screen Protectors',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $catPartsId = DB::table('categories')->insertGetId([
            'business_id' => $businessId,
            'name' => 'Repair Parts',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $catAccessoriesId = DB::table('categories')->insertGetId([
            'business_id' => $businessId,
            'name' => 'Accessories',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Products (Cables, Cases, Screen Protectors, Repair Parts, Accessories)
        $products = [
            // --- Cables & Adapters ---
            [
                'business_id' => $businessId,
                'category_id' => $catCablesId,
                'name' => 'AUX Audio Cable 3.5mm 1m',
                'sku' => '162064',
                'barcode' => '162064',
                'cost_price' => 2.50,
                'selling_price' => 10.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'business_id' => $businessId,
                'category_id' => $catCablesId,
                'name' => 'USB Type-C Fast Charging Cable 1m',
                'sku' => 'USBC-1M',
                'barcode' => '890123456001',
                'cost_price' => 3.00,
                'selling_price' => 12.99,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'business_id' => $businessId,
                'category_id' => $catCablesId,
                'name' => 'USB Type-C Fast Charging Cable 2m Braided',
                'sku' => 'USBC-2M',
                'barcode' => '890123456002',
                'cost_price' => 4.50,
                'selling_price' => 15.99,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'business_id' => $businessId,
                'category_id' => $catCablesId,
                'name' => 'Lightning to USB Cable (Apple MFi)',
                'sku' => 'LIGHT-1M',
                'barcode' => '890123456003',
                'cost_price' => 5.00,
                'selling_price' => 18.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'business_id' => $businessId,
                'category_id' => $catCablesId,
                'name' => 'Micro USB Data & Charging Cable',
                'sku' => 'MICRO-1M',
                'barcode' => '890123456004',
                'cost_price' => 1.50,
                'selling_price' => 8.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'business_id' => $businessId,
                'category_id' => $catCablesId,
                'name' => 'USB-C to HDMI 4K Adapter Cable',
                'sku' => 'USBC-HDMI',
                'barcode' => '890123456005',
                'cost_price' => 8.00,
                'selling_price' => 24.99,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'business_id' => $businessId,
                'category_id' => $catAccessoriesId,
                'name' => '20W USB-C Fast Power Adapter Plug',
                'sku' => 'PLUG-20W',
                'barcode' => '890123456006',
                'cost_price' => 6.00,
                'selling_price' => 19.99,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // --- Phone Cases ---
            [
                'business_id' => $businessId,
                'category_id' => $catCasesId,
                'name' => 'iPhone 15 Pro Max Clear Case',
                'sku' => 'CASE-IP15PM',
                'barcode' => '890200001001',
                'cost_price' => 2.00,
                'selling_price' => 12.99,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'business_id' => $businessId,
                'category_id' => $catCasesId,
                'name' => 'Samsung Galaxy S24 Ultra Rugged Case',
                'sku' => 'CASE-S24U',
                'barcode' => '890200001002',
                'cost_price' => 3.50,
                'selling_price' => 15.99,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'business_id' => $businessId,
                'category_id' => $catCasesId,
                'name' => 'iPhone 14 Silicone MagSafe Case Black',
                'sku' => 'CASE-IP14-BK',
                'barcode' => '890200001003',
                'cost_price' => 4.00,
                'selling_price' => 18.99,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // --- Screen Protectors ---
            [
                'business_id' => $businessId,
                'category_id' => $catScreenId,
                'name' => 'iPhone 15 Tempered Glass Screen Protector',
                'sku' => 'SP-IP15',
                'barcode' => '890300001001',
                'cost_price' => 1.00,
                'selling_price' => 9.99,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'business_id' => $businessId,
                'category_id' => $catScreenId,
                'name' => 'Samsung S24 Privacy Screen Protector',
                'sku' => 'SP-S24-PRV',
                'barcode' => '890300001002',
                'cost_price' => 2.00,
                'selling_price' => 14.99,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // --- Repair Parts ---
            [
                'business_id' => $businessId,
                'category_id' => $catPartsId,
                'name' => 'iPhone 13 OLED Screen Assembly',
                'sku' => 'PART-IP13-LCD',
                'barcode' => '890400001001',
                'cost_price' => 35.00,
                'selling_price' => 89.99,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'business_id' => $businessId,
                'category_id' => $catPartsId,
                'name' => 'iPhone 12 Battery Replacement 2815mAh',
                'sku' => 'PART-IP12-BAT',
                'barcode' => '890400001002',
                'cost_price' => 8.00,
                'selling_price' => 29.99,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'business_id' => $businessId,
                'category_id' => $catPartsId,
                'name' => 'Samsung S23 Charging Port Flex Cable',
                'sku' => 'PART-S23-PORT',
                'barcode' => '890400001003',
                'cost_price' => 5.00,
                'selling_price' => 19.99,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // --- Accessories ---
            [
                'business_id' => $businessId,
                'category_id' => $catAccessoriesId,
                'name' => 'Wireless Bluetooth Earbuds TWS',
                'sku' => 'ACC-EARBUDS',
                'barcode' => '890500001001',
                'cost_price' => 7.00,
                'selling_price' => 24.99,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'business_id' => $businessId,
                'category_id' => $catAccessoriesId,
                'name' => 'MagSafe Wireless Charger Pad 15W',
                'sku' => 'ACC-MAGSAFE',
                'barcode' => '890500001002',
                'cost_price' => 8.00,
                'selling_price' => 29.99,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'business_id' => $businessId,
                'category_id' => $catAccessoriesId,
                'name' => 'Car Phone Mount Magnetic Dashboard',
                'sku' => 'ACC-CARMOUNT',
                'barcode' => '890500001003',
                'cost_price' => 3.00,
                'selling_price' => 14.99,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($products as $pData) {
            $pId = DB::table('products')->insertGetId($pData);

            // Stock record
            DB::table('branch_stocks')->insert([
                'business_id' => $businessId,
                'branch_id' => $branchId,
                'product_id' => $pId,
                'quantity' => rand(15, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4. Default Walk-in Customer
        DB::table('customers')->insert([
            'business_id' => $businessId,
            'name' => 'Walk-in Customer',
            'phone' => '0000000000',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

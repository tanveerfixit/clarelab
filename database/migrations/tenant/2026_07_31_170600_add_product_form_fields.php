<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'inventory_tracking')) {
                $table->string('inventory_tracking')->default('track')->after('type'); // 'track', 'labor', 'bundles'
            }
            if (!Schema::hasColumn('products', 'has_serial')) {
                $table->boolean('has_serial')->default(false)->after('inventory_tracking');
            }
            if (!Schema::hasColumn('products', 'is_taxable')) {
                $table->boolean('is_taxable')->default(true)->after('has_serial');
            }
            if (!Schema::hasColumn('products', 'min_stock_level')) {
                $table->integer('min_stock_level')->default(5)->after('stock_quantity');
            }
            if (!Schema::hasColumn('products', 'min_sales_price')) {
                $table->decimal('min_sales_price', 10, 2)->nullable()->after('selling_price');
            }
            if (!Schema::hasColumn('products', 'color')) {
                $table->string('color')->nullable()->after('category_name');
            }
            if (!Schema::hasColumn('products', 'condition')) {
                $table->string('condition')->nullable()->after('color');
            }
            if (!Schema::hasColumn('products', 'storage')) {
                $table->string('storage')->nullable()->after('condition');
            }
            if (!Schema::hasColumn('products', 'alert_message')) {
                $table->text('alert_message')->nullable()->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'inventory_tracking',
                'has_serial',
                'is_taxable',
                'min_stock_level',
                'min_sales_price',
                'color',
                'condition',
                'storage',
                'alert_message',
            ]);
        });
    }
};

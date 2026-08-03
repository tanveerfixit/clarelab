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
        // 1. Extend products table
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'type')) {
                $table->enum('type', ['standard', 'serialized', 'variable', 'service'])->default('standard')->after('sku');
            }
            if (!Schema::hasColumn('products', 'manage_stock')) {
                $table->boolean('manage_stock')->default(true)->after('type');
            }
            if (!Schema::hasColumn('products', 'stock_quantity')) {
                $table->integer('stock_quantity')->default(0)->after('manage_stock');
            }
            if (!Schema::hasColumn('products', 'description')) {
                $table->text('description')->nullable()->after('stock_quantity');
            }
            if (!Schema::hasColumn('products', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('description');
            }
            if (!Schema::hasColumn('products', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // 2. Extend product_variants table
        Schema::table('product_variants', function (Blueprint $table) {
            if (!Schema::hasColumn('product_variants', 'variant_name')) {
                $table->string('variant_name')->after('product_id')->nullable();
            }
            if (!Schema::hasColumn('product_variants', 'variant_sku')) {
                $table->string('variant_sku')->unique()->nullable()->after('variant_name');
            }
            if (!Schema::hasColumn('product_variants', 'cost_price')) {
                $table->decimal('cost_price', 10, 2)->nullable()->after('variant_sku');
            }
            if (!Schema::hasColumn('product_variants', 'selling_price')) {
                $table->decimal('selling_price', 10, 2)->nullable()->after('cost_price');
            }
            if (!Schema::hasColumn('product_variants', 'stock_quantity')) {
                $table->integer('stock_quantity')->default(0)->after('selling_price');
            }
            if (!Schema::hasColumn('product_variants', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // 3. Create product_serials table
        if (!Schema::hasTable('product_serials')) {
            Schema::create('product_serials', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
                $table->string('serial_number')->unique();
                $table->enum('status', ['available', 'sold', 'in_repair', 'returned', 'transferred'])->default('available');
                $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
                $table->foreignId('transaction_id')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_serials');

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['variant_name', 'variant_sku', 'cost_price', 'selling_price', 'stock_quantity', 'deleted_at']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['type', 'manage_stock', 'stock_quantity', 'description', 'is_active', 'deleted_at']);
        });
    }
};

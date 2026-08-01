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
            if (!Schema::hasColumn('products', 'manufacturer')) {
                $table->string('manufacturer')->nullable()->after('name');
            }
            if (!Schema::hasColumn('products', 'category_name')) {
                $table->string('category_name')->nullable()->after('category_id');
            }
            if (!Schema::hasColumn('products', 'need_qty')) {
                $table->integer('need_qty')->default(0)->after('stock_quantity');
            }
            if (!Schema::hasColumn('products', 'on_po_qty')) {
                $table->integer('on_po_qty')->default(0)->after('need_qty');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['manufacturer', 'category_name', 'need_qty', 'on_po_qty']);
        });
    }
};

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
        if (!Schema::hasTable('repair_ticket_items')) {
            Schema::create('repair_ticket_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('repair_ticket_id')->constrained('repair_tickets')->cascadeOnDelete();
                $table->unsignedBigInteger('product_id')->nullable()->index();
                $table->string('name');
                $table->string('type')->default('service'); // service, part
                $table->decimal('unit_price', 10, 2)->default(0.00);
                $table->integer('quantity')->default(1);
                $table->decimal('total_price', 10, 2)->default(0.00);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_ticket_items');
    }
};

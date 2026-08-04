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
        if (!Schema::hasTable('repair_tickets')) {
            Schema::create('repair_tickets', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('business_id')->default(1)->index();
                $table->unsignedBigInteger('branch_id')->default(1)->index();
                $table->string('ticket_number')->unique();
                $table->string('customer_name');
                $table->string('phone_number');
                $table->string('email_address')->nullable();
                $table->string('device_model');
                $table->text('problem_description');
                $table->string('part_needed')->nullable();
                $table->decimal('total_quote', 10, 2)->default(0.00);
                $table->decimal('deposit_paid', 10, 2)->default(0.00);
                $table->string('status')->default('Received')->index();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_tickets');
    }
};

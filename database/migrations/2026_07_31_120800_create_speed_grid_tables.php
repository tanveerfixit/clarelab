<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('speed_grid_tiles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. 'iPhone Screen', 'Samsung Screen', 'Screen Protector', 'Charging Port'
            $table->string('bg_color')->default('bg-[#2c3e50]');
            $table->string('text_color')->default('text-white');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('speed_grid_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tile_id')->constrained('speed_grid_tiles')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('label'); // e.g. 'iPhone 13 Screen', 'Samsung S22 Screen'
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('bg_color')->default('bg-white');
            $table->string('text_color')->default('text-slate-800');
            $table->boolean('requires_variant')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('speed_grid_options');
        Schema::dropIfExists('speed_grid_tiles');
    }
};

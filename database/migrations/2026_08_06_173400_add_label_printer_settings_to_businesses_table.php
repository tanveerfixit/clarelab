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
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('label_size')->default('dymo_30334');
            $table->integer('barcode_length')->default(20);
            $table->integer('margin_top')->default(8);
            $table->integer('margin_left')->default(3);
            $table->integer('margin_bottom')->default(3);
            $table->integer('margin_right')->default(3);
            $table->string('orientation')->default('Landscape');
            $table->string('font_size')->default('Large');
            $table->string('font_family')->default('Arial Black');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn([
                'label_size',
                'barcode_length',
                'margin_top',
                'margin_left',
                'margin_bottom',
                'margin_right',
                'orientation',
                'font_size',
                'font_family',
            ]);
        });
    }
};

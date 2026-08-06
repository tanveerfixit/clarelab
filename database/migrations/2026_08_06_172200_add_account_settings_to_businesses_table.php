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
            $table->string('currency')->default('EUR');
            $table->string('timezone')->default('Europe/London');
            $table->string('date_format')->default('DD-MM-YY');
            $table->string('time_format')->default('12 hour');
            $table->string('language')->default('English');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn(['currency', 'timezone', 'date_format', 'time_format', 'language']);
        });
    }
};

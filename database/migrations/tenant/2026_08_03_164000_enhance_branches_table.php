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
        Schema::table('branches', function (Blueprint $table) {
            if (!Schema::hasColumn('branches', 'slug')) {
                $table->string('slug')->nullable()->after('name')->index();
            }
            if (!Schema::hasColumn('branches', 'subdomain')) {
                $table->string('subdomain')->nullable()->after('slug')->index();
            }
            if (!Schema::hasColumn('branches', 'logo_path')) {
                $table->string('logo_path')->nullable()->after('subdomain');
            }
            if (!Schema::hasColumn('branches', 'color_primary')) {
                $table->string('color_primary')->default('#1e40af')->after('logo_path');
            }
            if (!Schema::hasColumn('branches', 'color_secondary')) {
                $table->string('color_secondary')->default('#FFFFFF')->after('color_primary');
            }
            if (!Schema::hasColumn('branches', 'invoice_prefix')) {
                $table->string('invoice_prefix')->nullable()->after('color_secondary');
            }
            if (!Schema::hasColumn('branches', 'invoice_next_number')) {
                $table->integer('invoice_next_number')->default(1)->after('invoice_prefix');
            }
            if (!Schema::hasColumn('branches', 'receipt_header')) {
                $table->text('receipt_header')->nullable()->after('invoice_next_number');
            }
            if (!Schema::hasColumn('branches', 'receipt_footer')) {
                $table->text('receipt_footer')->nullable()->after('receipt_header');
            }
            if (!Schema::hasColumn('branches', 'email')) {
                $table->string('email')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('branches', 'opening_hours')) {
                $table->text('opening_hours')->nullable()->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn([
                'slug',
                'subdomain',
                'logo_path',
                'color_primary',
                'color_secondary',
                'invoice_prefix',
                'invoice_next_number',
                'receipt_header',
                'receipt_footer',
                'email',
                'opening_hours',
            ]);
        });
    }
};

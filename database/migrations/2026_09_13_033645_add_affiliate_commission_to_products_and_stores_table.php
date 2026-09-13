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
            $table->decimal('affiliate_commission_rate', 5, 2)->default(10.00)->after('price');
            $table->boolean('is_affiliate_enabled')->default(true)->after('affiliate_commission_rate');
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->decimal('default_affiliate_commission', 5, 2)->default(10.00)->after('balance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['affiliate_commission_rate', 'is_affiliate_enabled']);
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['default_affiliate_commission']);
        });
    }
};

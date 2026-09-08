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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('referrer_store_id')->nullable()->after('product_id')->constrained('stores')->nullOnDelete();
            $table->decimal('affiliate_commission', 15, 2)->default(0)->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['referrer_store_id']);
            $table->dropColumn(['referrer_store_id', 'affiliate_commission']);
        });
    }
};

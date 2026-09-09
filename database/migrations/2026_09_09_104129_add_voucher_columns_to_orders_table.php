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
            $table->string('voucher_code')->nullable()->after('amount');
            $table->decimal('discount_amount', 15, 2)->default(0)->after('voucher_code');
            $table->foreignId('campaign_id')->nullable()->after('discount_amount')->constrained('campaigns')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['campaign_id']);
            $table->dropColumn(['voucher_code', 'discount_amount', 'campaign_id']);
        });
    }
};

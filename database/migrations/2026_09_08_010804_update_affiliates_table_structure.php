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
        Schema::table('affiliates', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->after('id')->constrained('stores')->nullOnDelete();
            $table->string('whatsapp')->nullable()->after('handle');
            $table->string('referral_code')->nullable()->unique()->after('whatsapp');
            $table->decimal('commission_rate', 5, 2)->default(10.00)->comment('Persentase komisi %')->after('referral_code');
            $table->boolean('is_active')->default(true)->after('is_golden_tick');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affiliates', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->dropColumn(['store_id', 'whatsapp', 'referral_code', 'commission_rate', 'is_active']);
        });
    }
};

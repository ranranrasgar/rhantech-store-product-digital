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
        // 1. Add ad_balance to stores if not exists
        if (!Schema::hasColumn('stores', 'ad_balance')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->decimal('ad_balance', 15, 2)->default(0)->after('balance');
            });
        }

        // 2. Create seller_ads table
        if (!Schema::hasTable('seller_ads')) {
            Schema::create('seller_ads', function (Blueprint $table) {
                $table->id();
                $table->foreignId('store_id')->constrained()->cascadeOnDelete();
                $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
                $table->string('name');
                $table->string('type')->default('product'); // 'product', 'store'
                $table->string('budget_type')->default('unlimited'); // 'unlimited', 'daily'
                $table->decimal('daily_budget', 15, 2)->nullable();
                $table->string('period_type')->default('unlimited'); // 'unlimited', 'custom'
                $table->timestamp('start_date')->nullable();
                $table->timestamp('end_date')->nullable();
                $table->string('bidding_mode')->default('manual'); // 'manual', 'auto'
                $table->decimal('bid_price', 15, 2)->default(500); // biaya per klik / bid
                $table->json('target_keywords')->nullable(); // array of keywords
                $table->string('display_mode')->default('auto'); // 'auto', 'manual'
                $table->string('status')->default('active'); // 'active', 'paused', 'ended'
                $table->unsignedBigInteger('views_count')->default(0);
                $table->unsignedBigInteger('clicks_count')->default(0);
                $table->decimal('spent_amount', 15, 2)->default(0);
                $table->timestamps();
            });
        }

        // 3. Create ad_transactions table for recording top ups and charges
        if (!Schema::hasTable('ad_transactions')) {
            Schema::create('ad_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('store_id')->constrained()->cascadeOnDelete();
                $table->string('reference_no')->unique();
                $table->string('type'); // 'topup', 'deduction', 'bonus', 'refund'
                $table->decimal('amount', 15, 2);
                $table->decimal('tax_amount', 15, 2)->default(0);
                $table->decimal('total_amount', 15, 2);
                $table->string('payment_method')->nullable(); // 'store_balance', 'midtrans', 'tripay', 'manual'
                $table->string('status')->default('completed'); // 'pending', 'completed', 'failed'
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_transactions');
        Schema::dropIfExists('seller_ads');
        
        if (Schema::hasColumn('stores', 'ad_balance')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->dropColumn('ad_balance');
            });
        }
    }
};

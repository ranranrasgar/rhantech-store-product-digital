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
        Schema::table('stores', function (Blueprint $table) {
            if (!Schema::hasColumn('stores', 'is_pro')) {
                $table->boolean('is_pro')->default(false)->after('balance');
            }
            if (!Schema::hasColumn('stores', 'pro_expires_at')) {
                $table->timestamp('pro_expires_at')->nullable()->after('is_pro');
            }
            if (!Schema::hasColumn('stores', 'pro_plan')) {
                $table->string('pro_plan')->nullable()->after('pro_expires_at');
            }
            if (!Schema::hasColumn('stores', 'custom_payout_fee_percentage')) {
                $table->decimal('custom_payout_fee_percentage', 5, 2)->nullable()->after('pro_plan');
            }
        });

        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'store_id')) {
                $table->foreignId('store_id')->nullable()->after('id')->constrained('stores')->nullOnDelete();
            }
        });

        Schema::table('payout_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('payout_requests', 'fee_percentage')) {
                $table->decimal('fee_percentage', 5, 2)->default(2.50)->after('amount');
            }
            if (!Schema::hasColumn('payout_requests', 'fee_amount')) {
                $table->decimal('fee_amount', 15, 2)->default(0)->after('fee_percentage');
            }
            if (!Schema::hasColumn('payout_requests', 'net_amount')) {
                $table->decimal('net_amount', 15, 2)->default(0)->after('fee_amount');
            }
        });

        if (!Schema::hasTable('pro_subscriptions')) {
            Schema::create('pro_subscriptions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('reference_no')->unique();
                $table->string('plan')->default('monthly'); // monthly, yearly, lifetime
                $table->decimal('amount', 15, 2)->default(0);
                $table->string('payment_method')->nullable();
                $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
                $table->timestamp('paid_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pro_subscriptions');

        Schema::table('payout_requests', function (Blueprint $table) {
            $table->dropColumn(['fee_percentage', 'fee_amount', 'net_amount']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->dropColumn('store_id');
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['is_pro', 'pro_expires_at', 'pro_plan', 'custom_payout_fee_percentage']);
        });
    }
};

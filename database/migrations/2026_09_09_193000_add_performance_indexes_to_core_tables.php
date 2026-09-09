<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to add essential query optimization indexes.
     */
    public function up(): void
    {
        // 1. Orders table indexes
        Schema::table('orders', function (Blueprint $table) {
            $table->index('status', 'orders_status_idx');
            $table->index('created_at', 'orders_created_at_idx');
            $table->index(['status', 'created_at'], 'orders_status_created_at_idx');
            $table->index('customer_email', 'orders_customer_email_idx');
            if (Schema::hasColumn('orders', 'voucher_code')) {
                $table->index('voucher_code', 'orders_voucher_code_idx');
            }
        });

        // 2. Products table indexes
        Schema::table('products', function (Blueprint $table) {
            $table->index('is_active', 'products_is_active_idx');
            if (Schema::hasColumn('products', 'approval_status')) {
                $table->index('approval_status', 'products_approval_status_idx');
                $table->index(['is_active', 'approval_status'], 'products_active_approval_idx');
            }
            if (Schema::hasColumn('products', 'views')) {
                $table->index('views', 'products_views_idx');
            }
            if (Schema::hasColumn('products', 'sales_count')) {
                $table->index('sales_count', 'products_sales_count_idx');
            }
        });

        // 3. Campaigns table indexes
        Schema::table('campaigns', function (Blueprint $table) {
            $table->index('status', 'campaigns_status_idx');
            $table->index('code', 'campaigns_code_idx');
            $table->index(['status', 'code'], 'campaigns_status_code_idx');
        });

        // 4. Seller Ads table indexes
        if (Schema::hasTable('seller_ads')) {
            Schema::table('seller_ads', function (Blueprint $table) {
                $table->index('status', 'seller_ads_status_idx');
                $table->index(['status', 'product_id'], 'seller_ads_status_product_idx');
            });
        }

        // 5. Stores table indexes
        Schema::table('stores', function (Blueprint $table) {
            if (Schema::hasColumn('stores', 'ad_balance')) {
                $table->index('ad_balance', 'stores_ad_balance_idx');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_status_idx');
            $table->dropIndex('orders_created_at_idx');
            $table->dropIndex('orders_status_created_at_idx');
            $table->dropIndex('orders_customer_email_idx');
            if (Schema::hasColumn('orders', 'voucher_code')) {
                $table->dropIndex('orders_voucher_code_idx');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_is_active_idx');
            if (Schema::hasColumn('products', 'approval_status')) {
                $table->dropIndex('products_approval_status_idx');
                $table->dropIndex('products_active_approval_idx');
            }
            if (Schema::hasColumn('products', 'views')) {
                $table->dropIndex('products_views_idx');
            }
            if (Schema::hasColumn('products', 'sales_count')) {
                $table->dropIndex('products_sales_count_idx');
            }
        });

        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropIndex('campaigns_status_idx');
            $table->dropIndex('campaigns_code_idx');
            $table->dropIndex('campaigns_status_code_idx');
        });

        if (Schema::hasTable('seller_ads')) {
            Schema::table('seller_ads', function (Blueprint $table) {
                $table->dropIndex('seller_ads_status_idx');
                $table->dropIndex('seller_ads_status_product_idx');
            });
        }

        Schema::table('stores', function (Blueprint $table) {
            if (Schema::hasColumn('stores', 'ad_balance')) {
                $table->dropIndex('stores_ad_balance_idx');
            }
        });
    }
};

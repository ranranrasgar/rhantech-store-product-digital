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
        Schema::table('campaigns', function (Blueprint $table) {
            $table->string('applies_to')->default('all')->after('type'); // all, category, product
            $table->json('category_ids')->nullable()->after('applies_to');
            $table->json('product_ids')->nullable()->after('category_ids');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn(['applies_to', 'category_ids', 'product_ids']);
        });
    }
};

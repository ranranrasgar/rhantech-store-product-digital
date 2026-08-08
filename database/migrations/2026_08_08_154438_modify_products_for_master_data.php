<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('category');
            $table->foreignId('product_category_id')->nullable()->after('description')->constrained('product_categories')->nullOnDelete();
            $table->foreignId('product_type_id')->nullable()->after('product_category_id')->constrained('product_types')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('category')->nullable()->after('description');
            $table->dropForeign(['product_category_id']);
            $table->dropForeign(['product_type_id']);
            $table->dropColumn(['product_category_id', 'product_type_id']);
        });
    }
};

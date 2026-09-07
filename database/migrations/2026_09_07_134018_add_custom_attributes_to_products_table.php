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
            $table->json('highlights')->nullable()->after('download_links');
            $table->json('package_includes')->nullable()->after('highlights');
            $table->json('system_requirements')->nullable()->after('package_includes');
            $table->json('guarantees')->nullable()->after('system_requirements');
            $table->json('faqs')->nullable()->after('guarantees');
            $table->decimal('rating_override', 3, 1)->nullable()->after('faqs');
            $table->unsignedInteger('reviews_count')->nullable()->after('rating_override');
            $table->unsignedInteger('sales_count')->nullable()->after('reviews_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'highlights',
                'package_includes',
                'system_requirements',
                'guarantees',
                'faqs',
                'rating_override',
                'reviews_count',
                'sales_count',
            ]);
        });
    }
};

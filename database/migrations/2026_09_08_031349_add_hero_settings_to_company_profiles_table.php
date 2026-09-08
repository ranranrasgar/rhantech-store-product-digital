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
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->string('hero_mode')->default('custom')->after('mission'); // 'custom', 'top_stores', or 'both'
            $table->string('hero_badge')->nullable()->after('hero_mode');
            $table->string('hero_title')->nullable()->after('hero_badge');
            $table->text('hero_subtitle')->nullable()->after('hero_title');
            $table->string('hero_image')->nullable()->after('hero_subtitle');
            $table->string('hero_btn_primary_text')->nullable()->after('hero_image');
            $table->string('hero_btn_primary_url')->nullable()->after('hero_btn_primary_text');
            $table->string('hero_btn_secondary_text')->nullable()->after('hero_btn_primary_url');
            $table->string('hero_btn_secondary_url')->nullable()->after('hero_btn_secondary_text');
            $table->string('hero_stats_val')->nullable()->after('hero_btn_secondary_url');
            $table->string('hero_stats_label')->nullable()->after('hero_stats_val');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'hero_mode',
                'hero_badge',
                'hero_title',
                'hero_subtitle',
                'hero_image',
                'hero_btn_primary_text',
                'hero_btn_primary_url',
                'hero_btn_secondary_text',
                'hero_btn_secondary_url',
                'hero_stats_val',
                'hero_stats_label',
            ]);
        });
    }
};

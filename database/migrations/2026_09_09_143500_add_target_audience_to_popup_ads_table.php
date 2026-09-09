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
        Schema::table('popup_ads', function (Blueprint $table) {
            $table->string('target_audience')->default('all')->after('link_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('popup_ads', function (Blueprint $table) {
            $table->dropColumn('target_audience');
        });
    }
};

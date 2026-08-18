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
        Schema::table('help_articles', function (Blueprint $table) {
            $table->unsignedInteger('helpful_yes')->default(0)->after('views');
            $table->unsignedInteger('helpful_no')->default(0)->after('helpful_yes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('help_articles', function (Blueprint $table) {
            $table->dropColumn(['helpful_yes', 'helpful_no']);
        });
    }
};

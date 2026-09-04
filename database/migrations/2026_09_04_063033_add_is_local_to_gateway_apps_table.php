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
        Schema::table('gateway_apps', function (Blueprint $table) {
            // Jika true, webhook diproses lokal (di aplikasi ini), bukan diteruskan ke callback_url
            $table->boolean('is_local')->default(false)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gateway_apps', function (Blueprint $table) {
            $table->dropColumn('is_local');
        });
    }
};

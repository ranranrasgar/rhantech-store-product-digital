<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('pro_plans')) {
            // Pastikan paket Tahunan (yearly) ditandai sebagai rekomendasi utama
            DB::table('pro_plans')->where('slug', 'yearly')->update([
                'is_popular' => true,
                'badge' => 'Paling Hemat (Diskon 32%)',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};

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
        Schema::create('pro_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('price', 15, 2);
            $table->integer('duration_days')->nullable(); // null for lifetime
            $table->string('duration_label')->nullable(); // e.g. "/ 30 hari", "/ 1 tahun"
            $table->string('badge')->nullable(); // e.g. "Paling Hemat (Diskon 32%)"
            $table->text('description')->nullable();
            $table->text('features')->nullable(); // newline or json separated
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed 3 paket bawaan
        \Illuminate\Support\Facades\DB::table('pro_plans')->insert([
            [
                'name' => 'Bulanan',
                'slug' => 'monthly',
                'price' => 49000,
                'duration_days' => 30,
                'duration_label' => '/ 30 hari',
                'badge' => 'Paket Fleksibel',
                'description' => 'Cocok untuk mencoba seluruh fitur PRO tanpa komitmen jangka panjang.',
                'features' => "Fee payout 1% aktif 30 hari\nBadge Toko PRO Terverifikasi\nKirim WA Broadcast ke Pelanggan\nModul Portofolio & Proyek",
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tahunan',
                'slug' => 'yearly',
                'price' => 399000,
                'duration_days' => 365,
                'duration_label' => '/ 1 tahun',
                'badge' => 'Paling Hemat (Diskon 32%)',
                'description' => 'Hanya ~Rp 33.000 / bulan. Sangat hemat untuk pemilik toko aktif.',
                'features' => "Fee payout 1% aktif penuh 365 hari\nBadge Toko PRO Terverifikasi\nKirim WA Broadcast ke Pelanggan\nModul Portofolio & Proyek\nPrioritas Dukungan Admin",
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lifetime',
                'slug' => 'lifetime',
                'price' => 799000,
                'duration_days' => null,
                'duration_label' => '/ sekali bayar',
                'badge' => 'Akses Selamanya',
                'description' => 'Bayar 1x dan nikmati seluruh benefit PRO selamanya tanpa batas waktu.',
                'features' => "Akses PRO seumur hidup\nFee penarikan saldo 1% permanen\nWA Broadcast tanpa batas waktu\nModul Portofolio & Galeri Karya\nBadge Emas Eksklusif PRO",
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pro_plans');
    }
};

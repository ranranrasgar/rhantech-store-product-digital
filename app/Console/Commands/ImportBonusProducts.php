<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Str;

class ImportBonusProducts extends Command
{
    protected $signature = 'products:import-bonus';
    protected $description = 'Import bonus products from G drive directory';

    public function handle()
    {
        $dir = 'G:\Materi Lynk ID\BONUS 50 SOURCE CODE -20230726T114724Z-001';
        
        if (!is_dir($dir)) {
            $this->error("Directory not found: $dir");
            return;
        }

        $files = scandir($dir);
        $count = 0;

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            // Clean up the name
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            if (!in_array(strtolower($ext), ['zip', 'rar'])) continue;
            
            $rawName = pathinfo($file, PATHINFO_FILENAME);
            
            // Generate a better name
            $name = str_replace(['_', '-'], ' ', $rawName);
            $name = preg_replace('/[0-9]+/', '', $name); // remove numbers
            $name = ucwords(trim($name));

            if (empty($name)) {
                $name = "Source Code " . strtoupper($rawName);
            } else {
                $name = "Aplikasi " . $name;
            }

            // Make it sound better
            if (stripos($name, 'elearning') !== false || stripos($name, 'e learning') !== false) {
                $name = "Source Code E-Learning System";
            }
            if (stripos($name, 'perpus') !== false) {
                $name = "Aplikasi Sistem Informasi Perpustakaan";
            }
            if (stripos($name, 'simpanpinjam') !== false) {
                $name = "Aplikasi Koperasi Simpan Pinjam";
            }
            if (stripos($name, 'kepegawaian') !== false) {
                $name = "Sistem Informasi Kepegawaian";
            }
            if (stripos($name, 'hotel') !== false) {
                $name = "Sistem Manajemen Hotel";
            }
            
            $name = trim($name);
            
            // Basic description
            $desc = "Ini adalah source code lengkap untuk " . $name . ". Cocok digunakan sebagai bahan pembelajaran, tugas akhir, skripsi, maupun untuk dikembangkan lebih lanjut menjadi aplikasi komersial. Dikembangkan menggunakan PHP dan MySQL.";

            // Random price between 50k and 200k
            $price = rand(5, 20) * 10000;
            $discount = rand(0, 1) ? $price - (rand(1, 3) * 10000) : null;

            Product::create([
                'name' => $name,
                'slug' => Str::slug($name) . '-' . Str::random(4),
                'description' => $desc,
                'price' => $price,
                'discount_price' => $discount,
                'demo_url' => null, // Drive links would be manual or derived
                'is_active' => true,
                'file_path' => 'dummy/' . $file // just a placeholder
            ]);

            $count++;
            $this->info("Imported: $name");
        }

        $this->info("Total imported: $count products");
    }
}

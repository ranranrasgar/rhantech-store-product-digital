<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductCategory;
use App\Models\ProductType;
use Illuminate\Support\Str;

class ProductCategoryAndTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Kategori Produk (Fungsional Bisnis & Software Domain + Sinkronisasi Kategori Portofolio/Project)
        $categories = [
            'Accounting & Finance',
            'Keuangan',
            'Billing',
            'Point of Sale (POS) & Kasir',
            'E-Commerce & Online Store',
            'Inventory & Manajemen Stok',
            'Human Resource (HRIS) & Payroll',
            'Enterprise Resource Planning (ERP)',
            'Cooperative',
            'Workshop',
            'Automotive',
            'Entertainment',
            'Corporate',
            'Education',
            'Education / Finance',
            'Sistem Informasi Sekolah & Akademik',
            'Kesehatan, Klinik & Apotek',
            'Customer Relationship Management (CRM)',
            'Company Profile & Landing Page',
            'Project Management & Kolaborasi',
            'Booking & Reservasi Sistem',
            'Ticketing & Helpdesk Support',
            'Digital Marketing & SEO Tools',
            'Graphic Design, Asset & UI/UX',
            'Keamanan & Sistem Monitoring',
            'Blog',
            'Koleksi E-Book & Tutorial Programming',
            'Multi-tenant SaaS Platform',
        ];

        // Juga sinkronisasi otomatis dari ProjectCategory jika ada yang belum terdaftar
        if (class_exists(\App\Models\ProjectCategory::class)) {
            $existingProjectCats = \App\Models\ProjectCategory::pluck('name')->toArray();
            $categories = array_values(array_unique(array_merge($categories, $existingProjectCats)));
        }

        // 2. Tipe Produk (Bentuk / Arsitektur / Platform Software + Sinkronisasi Portofolio/Project Types)
        $types = [
            'Web Based Application',
            'Desktop Application',
            'Mobile Application',
            'REST API / Backend',
            'Web Application',
            'Mobile App (Android / iOS)',
            'Fullstack System (Web + Mobile)',
            'Website Template & Theme',
            'Plugin / Addon / Extension',
            'UI Kit & Design System',
            'E-Book & Digital Document',
            'Spreadsheet / Excel Automation',
        ];

        // Juga sinkronisasi otomatis dari ProjectType jika ada
        if (class_exists(\App\Models\ProjectType::class)) {
            $existingProjectTypes = \App\Models\ProjectType::pluck('name')->toArray();
            $types = array_values(array_unique(array_merge($types, $existingProjectTypes)));
        }

        // Seed Categories (Global / Platform level -> store_id = null)
        foreach ($categories as $catName) {
            $slug = Str::slug($catName);
            ProductCategory::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $catName,
                    'store_id' => null,
                ]
            );
        }

        // Seed Types (Global / Platform level -> store_id = null)
        foreach ($types as $typeName) {
            $slug = Str::slug($typeName);
            ProductType::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $typeName,
                    'store_id' => null,
                ]
            );
        }

        $this->command->info('Product categories and types have been seeded successfully!');
    }
}

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
        // 1. Kategori Produk (Fungsional Bisnis & Software Domisili)
        $categories = [
            'Accounting & Finance',
            'Point of Sale (POS) & Kasir',
            'E-Commerce & Online Store',
            'Inventory & Manajemen Stok',
            'Human Resource (HRIS) & Payroll',
            'Enterprise Resource Planning (ERP)',
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
            'Koleksi E-Book & Tutorial Programming',
            'Multi-tenant SaaS Platform',
        ];

        // 2. Tipe Produk (Bentuk / Arsitektur / Platform Software)
        $types = [
            'Web Application',
            'Desktop Application',
            'Mobile App (Android / iOS)',
            'Fullstack System (Web + Mobile)',
            'REST API & Microservice',
            'Website Template & Theme',
            'Plugin / Addon / Extension',
            'UI Kit & Design System',
            'E-Book & Digital Document',
            'Spreadsheet / Excel Automation',
        ];

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

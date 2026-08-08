<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Laravel E-Commerce Source Code',
                'description' => 'A complete e-commerce solution built with Laravel 11. Includes admin dashboard, payment gateway integration, and user management.',
                'price' => 500000,
                'discount_price' => 350000,
                'demo_url' => 'https://drive.google.com/file/d/demo-link-1/view?usp=sharing'
            ],
            [
                'name' => 'PHP Native POS System',
                'description' => 'Point of Sale system built with native PHP and MySQL. Perfect for small businesses and retail shops. Easy to customize.',
                'price' => 300000,
                'discount_price' => null,
                'demo_url' => 'https://drive.google.com/file/d/demo-link-2/view?usp=sharing'
            ],
            [
                'name' => 'CodeIgniter 4 Inventory Management',
                'description' => 'Advanced inventory management system with barcode generator, stock alerts, and detailed reporting built on CI4.',
                'price' => 450000,
                'discount_price' => 250000,
                'demo_url' => 'https://drive.google.com/file/d/demo-link-3/view?usp=sharing'
            ],
            [
                'name' => 'C# Desktop Application - Hospital ERP',
                'description' => 'Comprehensive desktop application for hospital management. Handles patients, doctors, appointments, and billing.',
                'price' => 1500000,
                'discount_price' => 1200000,
                'demo_url' => 'https://drive.google.com/file/d/demo-link-4/view?usp=sharing'
            ],
            [
                'name' => 'Laravel Company Profile Template',
                'description' => 'Premium company profile template with dynamic content management, blog module, and SEO optimization.',
                'price' => 250000,
                'discount_price' => 150000,
                'demo_url' => 'https://drive.google.com/file/d/demo-link-5/view?usp=sharing'
            ],
            [
                'name' => 'React Native Delivery App UI',
                'description' => 'Clean and modern UI kit for food delivery applications. Built with React Native and Expo.',
                'price' => 200000,
                'discount_price' => null,
                'demo_url' => 'https://drive.google.com/file/d/demo-link-6/view?usp=sharing'
            ],
            [
                'name' => 'PHP Native School Management',
                'description' => 'Manage students, classes, attendance, and grades with this lightweight PHP application.',
                'price' => 400000,
                'discount_price' => 300000,
                'demo_url' => 'https://drive.google.com/file/d/demo-link-7/view?usp=sharing'
            ],
            [
                'name' => 'Vue.js + Laravel SaaS Starter Kit',
                'description' => 'Jumpstart your SaaS business with this boilerplate. Includes authentication, billing (Stripe), and user roles.',
                'price' => 800000,
                'discount_price' => 600000,
                'demo_url' => 'https://drive.google.com/file/d/demo-link-8/view?usp=sharing'
            ],
            [
                'name' => 'CodeIgniter 4 HR Management',
                'description' => 'Complete Human Resource Management system handling employee records, payroll, leaves, and performance.',
                'price' => 600000,
                'discount_price' => 500000,
                'demo_url' => 'https://drive.google.com/file/d/demo-link-9/view?usp=sharing'
            ],
            [
                'name' => 'Laravel Multi-Vendor Marketplace',
                'description' => 'Build your own marketplace like Amazon or Tokopedia. Features vendor dashboards, commissions, and shipping integration.',
                'price' => 2500000,
                'discount_price' => 1900000,
                'demo_url' => 'https://drive.google.com/file/d/demo-link-10/view?usp=sharing'
            ],
        ];

        foreach ($products as $item) {
            DB::table('products')->insert([
                'name' => $item['name'],
                'slug' => Str::slug($item['name']) . '-' . Str::random(5),
                'description' => $item['description'],
                'price' => $item['price'],
                'discount_price' => $item['discount_price'],
                'demo_url' => $item['demo_url'],
                'file_path' => 'dummy/path.zip', // dummy path for now
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

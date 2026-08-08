<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductCategory;
use App\Models\ProductType;
use App\Models\Product;
use Illuminate\Support\Str;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Laravel', 'CodeIgniter', 'PHP Native', 'C#', 'React', 'Next.js'];
        $types = ['Source Code', 'Premium', 'Template', 'E-Book', 'Produk Digital'];

        foreach ($categories as $catName) {
            ProductCategory::firstOrCreate(
                ['slug' => Str::slug($catName)],
                ['name' => $catName]
            );
        }

        foreach ($types as $typeName) {
            ProductType::firstOrCreate(
                ['slug' => Str::slug($typeName)],
                ['name' => $typeName]
            );
        }

        $allCategories = ProductCategory::pluck('id')->toArray();
        $allTypes = ProductType::pluck('id')->toArray();

        if (empty($allCategories) || empty($allTypes)) return;

        // Assign to existing products if they don't have one
        Product::all()->each(function ($product) use ($allCategories, $allTypes) {
            if (!$product->product_category_id) {
                $product->product_category_id = $allCategories[array_rand($allCategories)];
            }
            if (!$product->product_type_id) {
                $product->product_type_id = $allTypes[array_rand($allTypes)];
            }
            $product->save();
        });
    }
}

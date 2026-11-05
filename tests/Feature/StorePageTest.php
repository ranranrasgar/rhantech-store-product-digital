<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorePageTest extends TestCase
{
    use RefreshDatabase;

    private function storeWith(array $widgets, array $productAttributes = []): Store
    {
        $store = Store::forceCreate([
            'user_id' => User::factory()->create()->id,
            'name' => 'Gudang',
            'slug' => 'gudang-uji',
            'store_mode' => 'store',
            'appearance_data' => json_encode($widgets),
        ]);

        Product::forceCreate(array_merge([
            'store_id' => $store->id,
            'name' => 'Produk Toko',
            'slug' => 'produk-toko',
            'description' => 'Deskripsi',
            'price' => 100000,
            'is_active' => true,
            'approval_status' => 'approved',
        ], $productAttributes));

        return $store;
    }

    public function test_store_renders_with_each_widget(): void
    {
        foreach (['banner', 'image', 'text', 'flash_sale', 'voucher', 'products'] as $type) {
            Store::query()->delete();
            Product::query()->delete();
            User::query()->delete();
            $this->storeWith([['id' => 'a', 'type' => $type, 'data' => []]]);

            $this->get('/gudang-uji')->assertOk();
        }
    }

    public function test_flash_sale_survives_free_product_with_discount_price(): void
    {
        $this->storeWith([['id' => 'a', 'type' => 'flash_sale', 'data' => []]], ['price' => 0, 'discount_price' => 5000]);

        $this->get('/gudang-uji')->assertOk();
    }

    public function test_view_all_links_point_to_the_catalog(): void
    {
        $this->storeWith([
            ['id' => 'a', 'type' => 'flash_sale', 'data' => []],
            ['id' => 'b', 'type' => 'products', 'data' => []],
        ], ['discount_price' => 5000]);

        $this->get('/gudang-uji')
            ->assertSee('href="' . route('products.index', ['store' => Store::first()->id]) . '"', false)
            ->assertDontSee('<a href="#"', false);
    }

    public function test_every_route_points_to_an_existing_controller_method(): void
    {
        $missing = [];

        foreach (app('router')->getRoutes() as $route) {
            $action = $route->getActionName();

            if (str_contains($action, '@') && ! method_exists(...explode('@', $action))) {
                $missing[] = $route->uri() . ' -> ' . $action;
            }
        }

        $this->assertSame([], $missing);
    }
}

<?php

namespace Tests\Feature;

use App\Models\CompanyProfile;
use App\Models\HelpArticle;
use App\Models\HelpCategory;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    private function makeProduct(array $attributes = []): Product
    {
        $name = $attributes['name'] ?? 'Aplikasi Bengkel';

        return Product::forceCreate(array_merge([
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::random(5),
            'description' => 'Deskripsi produk',
            'price' => 150000,
            'is_active' => true,
            'approval_status' => 'approved',
        ], $attributes));
    }

    private function productSchema(string $html): array
    {
        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);

        foreach ($matches[1] as $block) {
            $data = json_decode($block, true);
            $this->assertNotNull($data, 'JSON-LD tidak valid: ' . $block);

            if (($data['@type'] ?? null) === 'Product') {
                return $data;
            }
        }

        $this->fail('Schema Product tidak ditemukan');
    }

    public function test_fcm_test_route_is_closed_to_guests(): void
    {
        $this->get('/test-fcm-push')->assertRedirect(route('login'));
    }

    public function test_fcm_test_route_is_closed_to_non_admin_users(): void
    {
        $user = User::factory()->create(['role' => 'tenant']);

        $this->actingAs($user)->get('/test-fcm-push')->assertRedirect(route('tenant.dashboard'));
    }

    public function test_sitemap_lists_only_public_urls(): void
    {
        Cache::flush();
        $published = $this->makeProduct();
        $hidden = $this->makeProduct(['name' => 'Produk Nonaktif', 'is_active' => false]);

        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $this->assertStringContainsString('application/xml', $response->headers->get('Content-Type'));
        $this->assertNotFalse(simplexml_load_string($response->getContent()));
        $response->assertSee('<loc>' . url('/') . '</loc>', false);
        $response->assertSee('<loc>' . route('products.index') . '</loc>', false);
        $response->assertSee(route('products.show', $published->slug), false);
        $response->assertDontSee($hidden->slug, false);
    }

    public function test_homepage_is_served_at_root_without_redirect(): void
    {
        CompanyProfile::forceCreate(['company_name' => 'R-Tech']);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertViewIs('welcome');
        $response->assertSee('<link rel="canonical" href="' . url('/') . '"', false);
    }

    public function test_bonus_launcher_is_hidden_on_mobile(): void
    {
        $this->get('/products')
            ->assertSee('Bonus Rp 500rb')
            ->assertSee('class="hidden md:block fixed bottom-5', false);
    }

    public function test_catalog_page_two_has_self_canonical_and_stays_indexable(): void
    {
        foreach (range(1, 13) as $i) {
            $this->makeProduct(['name' => "Produk {$i}"]);
        }

        $response = $this->get('/products?page=2');

        $response->assertSee('<link rel="canonical" href="' . route('products.index', ['page' => 2]) . '"', false);
        $response->assertSee('Halaman 2');
        $response->assertDontSee('noindex', false);
    }

    public function test_catalog_search_results_are_noindex(): void
    {
        $this->get('/products?search=kasir')->assertSee('content="noindex, follow"', false);
    }

    public function test_product_without_reviews_has_no_rating_schema(): void
    {
        $product = $this->makeProduct();

        $response = $this->get(route('products.show', $product->slug));

        $this->assertArrayNotHasKey('aggregateRating', $this->productSchema($response->getContent()));
    }

    public function test_product_with_reviews_has_real_rating_schema(): void
    {
        $product = $this->makeProduct();
        ProductReview::forceCreate([
            'product_id' => $product->id,
            'customer_name' => 'Pembeli',
            'rating' => 4,
            'comment' => 'Bagus',
            'is_visible' => true,
        ]);

        $schema = $this->productSchema($this->get(route('products.show', $product->slug))->getContent());

        $this->assertEquals(4.0, $schema['aggregateRating']['ratingValue']);
        $this->assertEquals(1, $schema['aggregateRating']['ratingCount']);
    }

    public function test_product_schema_survives_special_characters(): void
    {
        $product = $this->makeProduct([
            'name' => "Kasir Bu'Tini \"Pro\"",
            'short_description' => "Baris satu\nbaris dua",
        ]);

        $schema = $this->productSchema($this->get(route('products.show', $product->slug))->getContent());

        $this->assertSame("Kasir Bu'Tini \"Pro\"", $schema['name']);
    }

    public function test_help_article_has_meta_description_and_canonical(): void
    {
        $category = HelpCategory::forceCreate(['name' => 'Umum', 'slug' => 'umum']);
        $article = HelpArticle::forceCreate([
            'help_category_id' => $category->id,
            'title' => 'Cara Membeli',
            'slug' => 'cara-membeli',
            'content' => '<p>Panduan membeli produk.</p>',
            'is_published' => true,
        ]);

        $response = $this->get(route('help.show', $article->slug));

        $response->assertSee('<meta name="description" content="Panduan membeli produk."', false);
        $response->assertSee('<link rel="canonical" href="' . route('help.show', $article->slug) . '"', false);
    }

    public function test_help_search_results_are_noindex(): void
    {
        $this->get('/help?q=kasir')->assertSee('content="noindex, follow"', false);
    }

    public function test_store_page_has_canonical(): void
    {
        $user = User::factory()->create();
        $store = Store::forceCreate(['user_id' => $user->id, 'name' => 'Warung Uji', 'slug' => 'warung-uji']);

        $this->get('/' . $store->slug)
            ->assertSee('<link rel="canonical" href="' . url('/' . $store->slug) . '"', false);
    }

    public function test_store_slug_may_start_with_reserved_word(): void
    {
        $user = User::factory()->create();
        Store::forceCreate(['user_id' => $user->id, 'name' => 'Toko Budi', 'slug' => 'toko-budi']);

        $this->get('/toko-budi')->assertOk();
    }

    public function test_store_link_detail_slug_may_start_with_reserved_word(): void
    {
        $user = User::factory()->create();
        Store::forceCreate(['user_id' => $user->id, 'name' => 'Toko Budi', 'slug' => 'toko-budi']);

        // Toko ditemukan tetapi link tidak ada: dialihkan ke halaman toko, bukan 404 dari route.
        $this->get('/toko-budi/link-tidak-ada')->assertRedirect('/toko-budi');
    }

    public function test_reserved_words_are_not_treated_as_store_slugs(): void
    {
        $user = User::factory()->create();
        Store::forceCreate(['user_id' => $user->id, 'name' => 'Toko Bantuan', 'slug' => 'help']);

        $this->get('/help')->assertViewIs('help.index');
        $this->get('/products/tidak-ada')->assertNotFound();
        $this->get('/help/tidak-ada')->assertNotFound();
    }

    public function test_missing_page_returns_helpful_404(): void
    {
        $response = $this->get('/halaman-yang-tidak-ada');

        $response->assertNotFound();
        $response->assertSee('Halaman tidak ditemukan');
        $response->assertSee(route('products.index'), false);
    }

    public function test_analytics_and_verification_render_only_when_configured(): void
    {
        $this->get('/products')
            ->assertDontSee('googletagmanager', false)
            ->assertDontSee('google-site-verification', false);

        config([
            'services.google.analytics_id' => 'G-TEST123',
            'services.google.site_verification' => 'kode-verifikasi',
        ]);

        $this->get('/products')
            ->assertSee('gtag/js?id=G-TEST123', false)
            ->assertSee('<meta name="google-site-verification" content="kode-verifikasi"', false);
    }

    public function test_public_layout_has_organization_schema(): void
    {
        CompanyProfile::forceCreate([
            'company_name' => 'R-Tech',
            'instagram' => 'https://instagram.com/rhantech',
            'facebook' => 'bukan-url',
        ]);

        $this->get('/about')
            ->assertSee('"@type":"Organization"', false)
            ->assertSee('"sameAs":["https://instagram.com/rhantech"]', false);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\HelpArticle;
use App\Models\Product;
use App\Models\Project;
use App\Models\Store;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $xml = Cache::remember('seo:sitemap', 3600, function () {
            return '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . view('sitemap', ['urls' => $this->urls()])->render();
        });

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    /**
     * @return array<int, array{loc: string, lastmod: string|null}>
     */
    private function urls(): array
    {
        return array_merge(
            $this->staticUrls(),
            $this->productUrls(),
            $this->projectUrls(),
            $this->helpUrls(),
            $this->storeUrls()
        );
    }

    /**
     * @return array<int, array{loc: string, lastmod: string|null, priority?: string, changefreq?: string, image?: string|null, image_title?: string|null}>
     */
    private function staticUrls(): array
    {
        $staticPages = [
            ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => route('products.index'), 'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => route('about'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => route('projects.index'), 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['loc' => route('clients.index'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => route('contact'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => route('help.index'), 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['loc' => route('legal.terms'), 'priority' => '0.5', 'changefreq' => 'yearly'],
            ['loc' => route('legal.privacy'), 'priority' => '0.5', 'changefreq' => 'yearly'],
            ['loc' => route('legal.copyright'), 'priority' => '0.5', 'changefreq' => 'yearly'],
            ['loc' => route('legal.refund'), 'priority' => '0.5', 'changefreq' => 'yearly'],
        ];

        return array_map(fn (array $page): array => [
            'loc' => $page['loc'],
            'lastmod' => null,
            'priority' => $page['priority'],
            'changefreq' => $page['changefreq'],
        ], $staticPages);
    }

    /**
     * @return array<int, array{loc: string, lastmod: string|null, priority?: string, changefreq?: string, image?: string|null, image_title?: string|null}>
     */
    private function productUrls(): array
    {
        $urls = [];
        $items = Product::query()->published()->with('images')->get(['id', 'slug', 'name', 'updated_at']);

        foreach ($items as $item) {
            $img = $item->images->first();
            $urls[] = [
                'loc' => route('products.show', $item->slug),
                'lastmod' => $item->updated_at?->toAtomString(),
                'priority' => '0.9',
                'changefreq' => 'weekly',
                'image' => $img ? asset('storage/' . $img->image_path) : null,
                'image_title' => $item->name,
            ];
        }

        return $urls;
    }

    /**
     * @return array<int, array{loc: string, lastmod: string|null, priority?: string, changefreq?: string, image?: string|null, image_title?: string|null}>
     */
    private function projectUrls(): array
    {
        $urls = [];
        $items = Project::query()->where('status', 'published')->get(['id', 'slug', 'title', 'thumbnail', 'updated_at']);

        foreach ($items as $item) {
            $urls[] = [
                'loc' => route('projects.show', $item->slug),
                'lastmod' => $item->updated_at?->toAtomString(),
                'priority' => '0.8',
                'changefreq' => 'monthly',
                'image' => $item->thumbnail ? (str_starts_with($item->thumbnail, 'http') ? $item->thumbnail : asset('storage/' . $item->thumbnail)) : null,
                'image_title' => $item->title,
            ];
        }

        return $urls;
    }

    /**
     * @return array<int, array{loc: string, lastmod: string|null, priority?: string, changefreq?: string}>
     */
    private function helpUrls(): array
    {
        $urls = [];
        $items = HelpArticle::query()->where('is_published', true)->get(['id', 'slug', 'title', 'updated_at']);

        foreach ($items as $item) {
            $urls[] = [
                'loc' => route('help.show', $item->slug),
                'lastmod' => $item->updated_at?->toAtomString(),
                'priority' => '0.6',
                'changefreq' => 'monthly',
            ];
        }

        return $urls;
    }

    /**
     * @return array<int, array{loc: string, lastmod: string|null, priority?: string, changefreq?: string, image?: string|null, image_title?: string|null}>
     */
    private function storeUrls(): array
    {
        $urls = [];
        $items = Store::query()->where('status', 'active')
            ->whereHas('products', fn ($q) => $q->published())
            ->get(['id', 'slug', 'name', 'logo', 'updated_at']);

        foreach ($items as $item) {
            $urls[] = [
                'loc' => url('/' . $item->slug),
                'lastmod' => $item->updated_at?->toAtomString(),
                'priority' => '0.8',
                'changefreq' => 'weekly',
                'image' => $item->logo ? asset('storage/' . $item->logo) : null,
                'image_title' => $item->name,
            ];
        }

        return $urls;
    }
}

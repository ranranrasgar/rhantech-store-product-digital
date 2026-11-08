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
     * @return array<int, array{loc: string, lastmod: string|null}>
     */
    private function staticUrls(): array
    {
        $staticPages = [
            url('/'),
            route('products.index'),
            route('about'),
            route('projects.index'),
            route('clients.index'),
            route('contact'),
            route('help.index'),
            route('legal.terms'),
            route('legal.privacy'),
            route('legal.copyright'),
            route('legal.refund'),
        ];

        return array_map(fn (string $loc): array => [
            'loc' => $loc,
            'lastmod' => null,
        ], $staticPages);
    }

    /**
     * @return array<int, array{loc: string, lastmod: string|null}>
     */
    private function productUrls(): array
    {
        $urls = [];
        $items = Product::query()->published()->get(['slug', 'updated_at']);

        foreach ($items as $item) {
            $urls[] = [
                'loc' => route('products.show', $item->slug),
                'lastmod' => $item->updated_at?->toAtomString(),
            ];
        }

        return $urls;
    }

    /**
     * @return array<int, array{loc: string, lastmod: string|null}>
     */
    private function projectUrls(): array
    {
        $urls = [];
        $items = Project::query()->where('status', 'published')->get(['slug', 'updated_at']);

        foreach ($items as $item) {
            $urls[] = [
                'loc' => route('projects.show', $item->slug),
                'lastmod' => $item->updated_at?->toAtomString(),
            ];
        }

        return $urls;
    }

    /**
     * @return array<int, array{loc: string, lastmod: string|null}>
     */
    private function helpUrls(): array
    {
        $urls = [];
        $items = HelpArticle::query()->where('is_published', true)->get(['slug', 'updated_at']);

        foreach ($items as $item) {
            $urls[] = [
                'loc' => route('help.show', $item->slug),
                'lastmod' => $item->updated_at?->toAtomString(),
            ];
        }

        return $urls;
    }

    /**
     * @return array<int, array{loc: string, lastmod: string|null}>
     */
    private function storeUrls(): array
    {
        $urls = [];
        $items = Store::query()->where('status', 'active')
            ->whereHas('products', fn ($q) => $q->published())
            ->get(['id', 'slug', 'updated_at']);

        foreach ($items as $item) {
            $urls[] = [
                'loc' => url('/' . $item->slug),
                'lastmod' => $item->updated_at?->toAtomString(),
            ];
        }

        return $urls;
    }
}

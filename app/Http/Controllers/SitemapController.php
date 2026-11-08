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
        $urls = [];

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

        foreach ($staticPages as $loc) {
            $urls[] = ['loc' => $loc, 'lastmod' => null];
        }

        $products = Product::query()->published()->get(['slug', 'updated_at']);
        foreach ($products as $item) {
            $urls[] = [
                'loc' => route('products.show', $item->slug),
                'lastmod' => $item->updated_at?->toAtomString(),
            ];
        }

        $projects = Project::query()->where('status', 'published')->get(['slug', 'updated_at']);
        foreach ($projects as $item) {
            $urls[] = [
                'loc' => route('projects.show', $item->slug),
                'lastmod' => $item->updated_at?->toAtomString(),
            ];
        }

        $helpArticles = HelpArticle::query()->where('is_published', true)->get(['slug', 'updated_at']);
        foreach ($helpArticles as $item) {
            $urls[] = [
                'loc' => route('help.show', $item->slug),
                'lastmod' => $item->updated_at?->toAtomString(),
            ];
        }

        $stores = Store::query()->where('status', 'active')
            ->whereHas('products', fn ($q) => $q->published())
            ->get(['id', 'slug', 'updated_at']);
        foreach ($stores as $item) {
            $urls[] = [
                'loc' => url('/' . $item->slug),
                'lastmod' => $item->updated_at?->toAtomString(),
            ];
        }

        return $urls;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HelpCategory;
use App\Models\HelpArticle;

class HelpController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        
        if ($query) {
            $articles = HelpArticle::where('is_published', true)
                ->where(function($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('content', 'like', "%{$query}%");
                })
                ->with('category')
                ->get();
                
            return view('help.search', compact('articles', 'query'));
        }

        $categories = HelpCategory::orderBy('sort_order')
            ->with(['articles' => function($q) {
                $q->where('is_published', true)->orderBy('views', 'desc')->limit(5);
            }])
            ->get();
            
        $popularArticles = HelpArticle::where('is_published', true)
            ->orderBy('views', 'desc')
            ->limit(10)
            ->get();

        return view('help.index', compact('categories', 'popularArticles'));
    }

    public function show($slug)
    {
        $aliases = [
            'panduan-lengkap-penarikan-saldo-penjualan-toko-payout-withdraw' => 'panduan-aturan-resmi-penarikan-dana-payout-hasil-penjualan-tenant',
        ];

        if (isset($aliases[$slug])) {
            return redirect()->route('help.show', $aliases[$slug]);
        }

        $article = HelpArticle::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Increment views
        $article->increment('views');

        // Get related articles in the same category
        $relatedArticles = HelpArticle::where('help_category_id', $article->help_category_id)
            ->where('is_published', true)
            ->where('id', '!=', $article->id)
            ->orderBy('views', 'desc')
            ->limit(5)
            ->get();

        return view('help.show', compact('article', 'relatedArticles'));
    }

    public function feedback(Request $request, $id)
    {
        $article = HelpArticle::findOrFail($id);
        
        $type = $request->input('type');
        if ($type === 'yes') {
            $article->increment('helpful_yes');
        } elseif ($type === 'no') {
            $article->increment('helpful_no');
        }

        return response()->json(['success' => true, 'message' => 'Terima kasih atas tanggapan Anda.']);
    }
}

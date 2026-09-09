<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SearchAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductSearch::query();

        // Filter: Semua, Ditemukan, atau Belum Tersedia (Hasil = 0)
        if ($request->filter === 'not_found') {
            $query->where('results_count', 0);
        } elseif ($request->filter === 'found') {
            $query->where('results_count', '>', 0);
        }

        if ($request->filled('q')) {
            $query->where('keyword', 'like', '%' . trim($request->q) . '%');
        }

        // Sorting
        if ($request->sort === 'latest') {
            $query->orderBy('last_searched_at', 'desc');
        } elseif ($request->sort === 'least') {
            $query->orderBy('hits', 'asc');
        } else {
            $query->orderBy('hits', 'desc')->orderBy('last_searched_at', 'desc');
        }

        $searches = $query->paginate(20)->withQueryString();

        // Statistik Ringkas
        $totalSearches = ProductSearch::count();
        $totalHits = ProductSearch::sum('hits');
        $unmetDemandsCount = ProductSearch::where('results_count', 0)->count();

        return view('admin.searches.index', compact('searches', 'totalSearches', 'totalHits', 'unmetDemandsCount'));
    }

    public function destroy(ProductSearch $search)
    {
        $search->delete();
        Cache::forget('popular_search_keywords');

        return back()->with('success', 'Riwayat kata kunci pencarian berhasil dihapus.');
    }
}

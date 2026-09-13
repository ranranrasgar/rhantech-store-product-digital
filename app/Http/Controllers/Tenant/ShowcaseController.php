<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ShowcaseController extends Controller
{
    /**
     * Tampilkan katalog produk yang bisa ditambahkan ke etalase toko (Showcase).
     */
    public function index(Request $request)
    {
        $store = Store::where('user_id', Auth::id())->first();

        if (!$store) {
            return redirect()->route('tenant.dashboard')->with('error', 'Anda harus memiliki toko untuk mengelola etalase showcase.');
        }

        // Ambil ID produk yang sudah ada di showcase toko ini
        $myShowcaseIds = $store->showcaseProducts()->pluck('products.id')->toArray();

        // Query produk yang BISA diafiliasikan:
        // - Harus published (aktif & disetujui)
        // - BUKAN produk milik toko ini sendiri
        $query = Product::published()
            ->where(function($q) use ($store) {
                $q->where('store_id', '!=', $store->id)
                  ->orWhereNull('store_id'); // Produk platform resmi
            })
            ->with(['store', 'category', 'images']);

        // Filter tab: 'semua', 'platform', 'tenant', 'terpasang'
        $tab = $request->get('tab', 'semua');
        if ($tab === 'platform') {
            $query->whereNull('store_id');
        } elseif ($tab === 'tenant') {
            $query->whereNotNull('store_id');
        } elseif ($tab === 'terpasang') {
            $query->whereIn('id', $myShowcaseIds);
        }

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('store', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $products = $query->latest()->paginate(16)->withQueryString();

        return view('tenant.showcase.index', compact('store', 'products', 'myShowcaseIds', 'tab'));
    }

    /**
     * Tambahkan atau hapus produk dari etalase toko.
     */
    public function toggle(Request $request, Product $product)
    {
        $store = Store::where('user_id', Auth::id())->first();

        if (!$store) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Anda harus memiliki toko untuk mengelola etalase showcase.'], 403);
            }
            return back()->with('error', 'Toko tidak ditemukan.');
        }

        // Tidak boleh menambahkan produk sendiri ke showcase afiliasi
        if ($product->store_id === $store->id) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Produk ini adalah produk toko Anda sendiri.'], 422);
            }
            return back()->with('error', 'Produk ini adalah produk toko Anda sendiri.');
        }

        if ($store->showcaseProducts()->where('product_id', $product->id)->exists()) {
            $store->showcaseProducts()->detach($product->id);
            $count = $store->showcaseProducts()->count();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'is_installed' => false,
                    'count' => $count,
                    'message' => "Produk '{$product->name}' berhasil dicopot dari etalase toko Anda.",
                ]);
            }
            return back()->with('success', "Produk '{$product->name}' berhasil dicopot dari etalase toko Anda.");
        } else {
            $store->showcaseProducts()->attach($product->id, ['is_active' => true]);
            $count = $store->showcaseProducts()->count();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'is_installed' => true,
                    'count' => $count,
                    'message' => "Produk '{$product->name}' berhasil dipajang di etalase toko Anda!",
                ]);
            }
            return back()->with('success', "Produk '{$product->name}' berhasil dipajang di etalase toko Anda!");
        }
    }
}

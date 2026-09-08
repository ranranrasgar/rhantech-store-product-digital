<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class PublicStoreController extends Controller
{
    /**
     * Display the public store profile page.
     */
    public function show(Request $request, string $slug)
    {
        $store = Store::where('slug', $slug)->firstOrFail();

        // Check affiliate referral tracking
        if ($request->filled('ref')) {
            $refCode = $request->query('ref');
            $affiliate = \App\Models\Affiliate::where('referral_code', $refCode)->first();
            if ($affiliate) {
                // Simpan di session agar bisa dipakai saat checkout
                session(['affiliate_ref' => $affiliate->referral_code]);
                // Increment click jika belum di-count di sesi ini
                $sessionKey = 'aff_clicked_' . $affiliate->id;
                if (!session()->has($sessionKey)) {
                    $clicks = (int) $affiliate->clicks_count + 1;
                    $affiliate->update(['clicks_count' => (string)$clicks]);
                    session([$sessionKey => true]);
                }
            }
        }
        
        // Set session affiliate_ref otomatis ke slug toko ini agar jika pembeli membeli produk showcase, komisi otomatis masuk ke toko ini
        session(['affiliate_ref' => $store->slug]);

        // Ambil ID produk milik toko sendiri dan ID produk showcase yang dipajang oleh toko ini
        $ownProductIds = $store->products()->published()->pluck('products.id');
        $showcaseProductIds = $store->showcaseProducts()->published()->pluck('products.id');
        $allProductIds = $ownProductIds->merge($showcaseProductIds)->unique()->values();

        // Query semua produk (produk sendiri + produk showcase yang dipajang)
        $productsQuery = \App\Models\Product::whereIn('id', $allProductIds)
            ->published()
            ->with(['store', 'category', 'images', 'type', 'reviews'])
            ->withCount(['orders' => function($q) {
                $q->whereIn('status', ['paid', 'downloaded']);
            }]);

        // Filter kategori jika ada query param
        if ($request->filled('category')) {
            $productsQuery->where('product_category_id', $request->query('category'));
        }

        $products = $productsQuery->latest()->paginate(12)->withQueryString();

        // Fetch appearance settings
        $appearance = is_string($store->appearance_data) ? json_decode($store->appearance_data, true) : $store->appearance_data;
        if (!is_array($appearance)) {
            $appearance = [];
        }
        
        $isFollowing = false;
        if (Auth::check()) {
            $isFollowing = $store->followers()->where('user_id', Auth::id())->exists();
        }
        
        // Fetch categories from all displayed products
        $categories = \App\Models\ProductCategory::whereHas('products', function($q) use ($allProductIds) {
            $q->whereIn('id', $allProductIds)->published();
        })->get();
        
        return view('store.show', compact('store', 'products', 'appearance', 'isFollowing', 'categories'));
    }

    /**
     * Toggle follow status for the store.
     */
    public function toggleFollow(Store $store)
    {
        $user = Auth::user();
        
        if ($store->followers()->where('user_id', $user->id)->exists()) {
            $store->followers()->detach($user->id);
            return response()->json(['following' => false, 'message' => 'Berhenti mengikuti toko.']);
        } else {
            $store->followers()->attach($user->id);
            return response()->json(['following' => true, 'message' => 'Berhasil mengikuti toko.']);
        }
    }
}

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
        
        // Only show active & approved products for store page (Produk Milik Sendiri)
        $products = $store->products()->published()->paginate(12);

        // Ambil produk showcase afiliasi yang dipajang oleh toko ini
        $showcaseProducts = $store->showcaseProducts()
            ->published()
            ->with(['store', 'category', 'images'])
            ->get();

        // Fetch appearance settings
        $appearance = is_string($store->appearance_data) ? json_decode($store->appearance_data, true) : $store->appearance_data;
        if (!is_array($appearance)) {
            $appearance = [];
        }
        
        $isFollowing = false;
        if (Auth::check()) {
            $isFollowing = $store->followers()->where('user_id', Auth::id())->exists();
        }
        
        // Fetch categories from products
        $categories = \App\Models\ProductCategory::whereHas('products', function($q) use ($store) {
            $q->where('store_id', $store->id)->published();
        })->get();
        
        return view('store.show', compact('store', 'products', 'showcaseProducts', 'appearance', 'isFollowing', 'categories'));
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

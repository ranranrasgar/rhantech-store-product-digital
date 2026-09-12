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

        // Increment visitor / view count untuk toko
        $storeSessionKey = 'viewed_store_' . $store->id;
        if (!session()->has($storeSessionKey)) {
            $store->increment('views');
            session([$storeSessionKey => true]);
        }

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
            ->with(['store:id,name,slug,logo', 'category:id,name', 'images', 'type:id,name', 'reviews:id,product_id,rating,is_visible'])
            ->withCount(['orders' => function($q) {
                $q->whereIn('orders.status', ['paid', 'downloaded']);
            }]);

        // Filter search jika ada query param
        if ($request->filled('q') || $request->filled('search')) {
            $keyword = trim($request->query('q', $request->query('search')));
            $productsQuery->where(function($q) use ($keyword) {
                $q->where('products.name', 'like', "%{$keyword}%")
                  ->orWhere('products.description', 'like', "%{$keyword}%");
            });
        }

        // Filter kategori jika ada query param
        if ($request->filled('category')) {
            $productsQuery->where('products.product_category_id', $request->query('category'));
        }

        $products = $productsQuery->latest()->paginate(12)->withQueryString();

        // Fetch appearance settings
        $rawAppearance = is_string($store->appearance_data) ? json_decode($store->appearance_data, true) : $store->appearance_data;
        $appearance = [];
        if (is_array($rawAppearance)) {
            foreach ($rawAppearance as $k => $block) {
                if (is_numeric($k) && is_array($block) && !empty($block['type'])) {
                    $appearance[] = $block;
                }
            }
        }
        
        $isFollowing = false;
        if (Auth::check()) {
            $isFollowing = $store->followers()->where('user_id', Auth::id())->exists();
        }
        
        // Fetch categories from all displayed products
        $categories = \App\Models\ProductCategory::whereHas('products', function($q) use ($allProductIds) {
            $q->whereIn('products.id', $allProductIds)->published();
        })->select(['id', 'name'])->get();
        
        // Fetch active vouchers/campaigns of the store
        $campaigns = \App\Models\Campaign::where('store_id', $store->id)
            ->active()
            ->latest()
            ->get(['id', 'store_id', 'code', 'name', 'type', 'discount_type', 'discount_value',
                   'minimum_spend', 'start_date', 'end_date', 'usage_limit', 'used_count', 'color']);

        // Route ke view sesuai store_mode (bisa di-override lewat ?view=store/profile/hybrid)
        $mode = request('view') ?? ($store->store_mode ?? 'store');
        $profileLinks = is_array($store->profile_links)
            ? collect($store->profile_links)->filter(fn($l) => !isset($l['is_active']) || !empty($l['is_active']))->values()
            : collect();

        $sharedData = compact('store', 'products', 'appearance', 'isFollowing', 'categories', 'campaigns', 'profileLinks');

        if ($mode === 'profile') {
            return view('store.profile', $sharedData);
        }

        if ($mode === 'hybrid') {
            return view('store.hybrid', $sharedData);
        }

        return view('store.show', $sharedData);
    }

    /**
     * Display the dedicated detail page for a profile/showcase link (ala Lynk.id)
     */
    public function showLinkDetail(Request $request, string $slug, string $linkId)
    {
        $store = Store::where('slug', $slug)->firstOrFail();
        
        $profileLinks = is_array($store->profile_links)
            ? collect($store->profile_links)
            : collect();

        // Cari item link berdasarkan ID, slug, atau index
        $matchedLink = null;
        $matchedIndex = null;

        foreach ($profileLinks as $index => $item) {
            $itemId = $item['id'] ?? null;
            $itemSlug = !empty($item['slug']) 
                ? $item['slug'] 
                : (!empty($item['title']) ? \Illuminate\Support\Str::slug($item['title']) : 'item-' . ($index + 1));
            $itemIndexId = 'item-' . ($index + 1);
            $itemDirectIndex = (string)$index;
            
            if ($linkId === $itemId || $linkId === $itemSlug || $linkId === $itemIndexId || $linkId === $itemDirectIndex) {
                $matchedLink = $item;
                $matchedIndex = $index;
                break;
            }
        }

        // Fallback jika tidak match persis, coba cari jika linkId mengandung substring atau index
        if (!$matchedLink && is_numeric($linkId) && isset($profileLinks[(int)$linkId])) {
            $matchedLink = $profileLinks[(int)$linkId];
            $matchedIndex = (int)$linkId;
        }

        if (!$matchedLink) {
            return redirect()->route('store.show', $store->slug);
        }

        // Format WhatsApp URL untuk tombol WhatsApp
        $socialLinks = is_array($store->social_links) ? $store->social_links : [];
        $waLink = collect($socialLinks)->firstWhere('platform', 'whatsapp')['url'] ?? null;
        if (!$waLink && !empty($store->user?->phone)) {
            $phone = preg_replace('/[^0-9]/', '', $store->user->phone);
            if (str_starts_with($phone, '0')) {
                $phone = '62' . substr($phone, 1);
            }
            $waLink = 'https://wa.me/' . $phone;
        }

        if ($waLink) {
            $msg = 'Halo, saya tertarik dengan "' . ($matchedLink['title'] ?? 'produk/portofolio') . '" di toko ' . $store->name . '. Boleh minta info lebih lanjut?';
            $waLink = $waLink . (str_contains($waLink, '?') ? '&' : '?') . 'text=' . urlencode($msg);
        }

        return view('store.link_detail', [
            'store'        => $store,
            'link'         => $matchedLink,
            'linkIndex'    => $matchedIndex,
            'waLink'       => $waLink,
            'profileLinks' => $profileLinks,
        ]);
    }

    /**
     * Toggle follow status for the store.
     */
    public function toggleFollow($store)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Silakan login terlebih dahulu untuk mengikuti toko.'], 401);
        }

        // Resolusi model store baik dari instance, ID numerik, maupun slug
        $storeModel = $store instanceof Store 
            ? $store 
            : (is_numeric($store) ? Store::find($store) : Store::where('slug', $store)->first());

        if (!$storeModel) {
            return response()->json(['message' => 'Toko tidak ditemukan.'], 404);
        }

        // Cegah pemilik toko mengikuti toko miliknya sendiri
        if ($storeModel->user_id === $user->id) {
            return response()->json([
                'following' => false,
                'followers_count' => $storeModel->followers()->count(),
                'message' => 'Anda tidak dapat mengikuti toko milik Anda sendiri.'
            ], 422);
        }

        if ($storeModel->followers()->where('user_id', $user->id)->exists()) {
            $storeModel->followers()->detach($user->id);
            return response()->json([
                'following' => false,
                'followers_count' => $storeModel->followers()->count(),
                'message' => 'Berhenti mengikuti toko ' . $storeModel->name . '.'
            ]);
        } else {
            $storeModel->followers()->attach($user->id);
            return response()->json([
                'following' => true,
                'followers_count' => $storeModel->followers()->count(),
                'message' => 'Berhasil mengikuti toko ' . $storeModel->name . '!'
            ]);
        }
    }
}


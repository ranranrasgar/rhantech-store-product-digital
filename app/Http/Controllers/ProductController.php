<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $isAjax = ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest' || $request->boolean('ajax'));

        $query = Product::with(['store:id,name,slug', 'images', 'category:id,name', 'type:id,name', 'activeAd'])
            ->published();

        if ($request->filled('category')) {
            $catVal = $request->category;
            $query->where(function($q) use ($catVal) {
                $q->where('product_category_id', $catVal);
                if (is_string($catVal) && !is_numeric($catVal)) {
                    $q->orWhereHas('category', function($cq) use ($catVal) {
                        $cq->where('name', $catVal);
                    });
                }
            });
        }

        if ($request->filled('type')) {
            $typeVal = $request->type;
            $query->where(function($q) use ($typeVal) {
                $q->where('product_type_id', $typeVal);
                if (is_string($typeVal) && !is_numeric($typeVal)) {
                    $q->orWhereHas('type', function($tq) use ($typeVal) {
                        $tq->where('name', $typeVal);
                    });
                }
            });
        }

        if ($request->filled('store')) {
            $query->where('products.store_id', $request->store);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $cleanedSearch = ltrim($search, '@');
            $query->where(function ($q) use ($search, $cleanedSearch) {
                $q->where('products.name', 'like', "%{$search}%")
                    ->orWhere('products.tags', 'like', "%{$search}%")
                    ->orWhere('products.short_description', 'like', "%{$search}%")
                    ->orWhere('products.description', 'like', "%{$search}%")
                    ->orWhereHas('store', function ($sq) use ($search, $cleanedSearch) {
                        $sq->where('stores.name', 'like', "%{$search}%")
                            ->orWhere('stores.slug', 'like', "%{$cleanedSearch}%")
                            ->orWhereHas('user', function($uq) use ($search) {
                                $uq->where('users.name', 'like', "%{$search}%");
                            });
                    });
            });
        }

        if ($request->filled('min_price')) {
            $query->whereRaw('COALESCE(discount_price, price) >= ?', [$request->min_price]);
        }

        if ($request->filled('max_price')) {
            $query->whereRaw('COALESCE(discount_price, price) <= ?', [$request->max_price]);
        }

        // Prioritaskan produk beriklan aktif berdasarkan BID TERTINGGI (Ad Auction / Peringkat Iklan)
        // Bid lebih tinggi (misal Rp1.000 vs Rp100) otomatis menempati posisi nomor 1 teratas
        $query->orderByRaw('(
            SELECT COALESCE(MAX(seller_ads.bid_price), 0) 
            FROM seller_ads 
            JOIN stores ON stores.id = seller_ads.store_id 
            WHERE seller_ads.product_id = products.id 
              AND seller_ads.status = "active" 
              AND stores.ad_balance > 0
        ) DESC');

        if ($request->sort == 'best_seller') {
            $query->withCount(['orders' => function ($q) {
                $q->where('status', 'paid');
            }])->orderBy('orders_count', 'desc');
        } elseif ($request->sort == 'popular') {
            $query->orderBy('views', 'desc');
        } elseif ($request->sort == 'price_low') {
            $query->orderByRaw('COALESCE(discount_price, price) ASC');
        } elseif ($request->sort == 'price_high') {
            $query->orderByRaw('COALESCE(discount_price, price) DESC');
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        // Rekam tayangan/impresi iklan untuk produk beriklan yang tampil
        $sponsoredProductIds = $products->filter(fn($p) => !is_null($p->activeAd))->pluck('id');
        if ($sponsoredProductIds->isNotEmpty()) {
            \App\Models\SellerAd::whereIn('product_id', $sponsoredProductIds)
                ->where('status', 'active')
                ->increment('views_count');
        }

        // Rekam kata kunci pencarian pembeli untuk analitik & tren populer
        if ($request->filled('search')) {
            \App\Models\ProductSearch::record($request->search, $products->total());
        }

        // Jika AJAX request, langsung render list produk tanpa query metadata katalog
        if ($isAjax) {
            return view('products._list', compact('products'))->render();
        }

        // Metadata hanya dimuat pada saat full page load
        $categories = \App\Models\ProductCategory::select(['id', 'store_id', 'name'])
            ->whereHas('products', function ($q) {
                $q->published();
            })
            ->withCount(['products' => function ($q) {
                $q->published();
            }])
            ->with('store:id,name')
            ->orderByRaw('store_id IS NULL DESC, name ASC')
            ->get();

        $types = \App\Models\ProductType::select(['id', 'store_id', 'name'])
            ->whereHas('products', function ($q) {
                $q->published();
            })
            ->withCount(['products' => function ($q) {
                $q->published();
            }])
            ->with('store:id,name')
            ->orderByRaw('store_id IS NULL DESC, name ASC')
            ->get();

        $stores = \App\Models\Store::select(['id', 'name', 'slug'])->get();
        $banners = \App\Models\Banner::where('is_active', true)->get()->keyBy('position');

        // Produk unggulan yang paling banyak diklik / dilihat + relasi store dan image
        $topProducts = Product::with(['store:id,name,slug', 'images'])
            ->published()
            ->select(['id', 'store_id', 'name', 'slug', 'price', 'discount_price', 'views', 'sales_count'])
            ->orderBy('views', 'desc')
            ->orderBy('sales_count', 'desc')
            ->limit(5)
            ->get();

        // Banner Toko Rekomendasi & Beriklan (Carousel Slide Bergantian)
        // Prioritas 1: Toko yang beriklan aktif (Toko Rekomendasi Bersponsor)
        $adStores = \App\Models\Store::where('ad_balance', '>', 0)
            ->whereHas('ads', function ($q) {
                $q->activeAndFunded()->whereNotNull('product_id');
            })
            ->with([
                'ads' => function ($q) {
                    $q->activeAndFunded()->whereNotNull('product_id')->with(['product.images', 'product.category:id,name'])->orderBy('bid_price', 'desc');
                },
                'products' => function ($q) {
                    $q->published()->select(['products.id', 'products.store_id', 'products.name', 'products.slug', 'products.price', 'products.discount_price'])->with('images')->latest('products.created_at')->take(6);
                },
                'showcaseProducts' => function ($q) {
                    $q->published()->select(['products.id', 'products.store_id', 'products.name', 'products.slug', 'products.price', 'products.discount_price'])->with('images')->latest('products.created_at')->take(6);
                }
            ])
            ->get(['id', 'name', 'slug', 'logo', 'description', 'ad_balance', 'views']);

        $adStores->each(function ($store) {
            $store->is_sponsored_ad = true;
        });

        // Prioritas 2: Toko yang paling banyak views (Toko Populer) agar slide selalu berputar
        $adStoreIds = $adStores->pluck('id');
        $popularStores = \App\Models\Store::whereNotIn('id', $adStoreIds)
            ->where(function ($q) {
                $q->whereHas('products', fn($p) => $p->published())
                  ->orWhereHas('showcaseProducts', fn($p) => $p->published());
            })
            ->with([
                'ads' => fn($q) => $q->whereRaw('0 = 1'),
                'products' => function ($q) {
                    $q->published()->select(['products.id', 'products.store_id', 'products.name', 'products.slug', 'products.price', 'products.discount_price'])->with('images')->latest('products.created_at')->take(6);
                },
                'showcaseProducts' => function ($q) {
                    $q->published()->select(['products.id', 'products.store_id', 'products.name', 'products.slug', 'products.price', 'products.discount_price'])->with('images')->latest('products.created_at')->take(6);
                }
            ])
            ->orderBy('views', 'desc')
            ->orderBy('id', 'desc')
            ->take(6)
            ->get(['id', 'name', 'slug', 'logo', 'description', 'ad_balance', 'views']);

        $popularStores->each(function ($store) {
            $store->is_sponsored_ad = false;
        });

        // Gabungkan: Toko beriklan di posisi awal sebagai rekomendasi, diikuti toko dengan views terbanyak
        $sponsoredStores = $adStores->concat($popularStores);

        // Prioritas Produk Beriklan Aktif (Diurutkan berdasarkan bid_price tertinggi, lalu iklan terbaru)
        $sponsoredAds = \App\Models\SellerAd::activeAndFunded()
            ->whereNotNull('product_id')
            ->with([
                'product' => function ($q) {
                    $q->published()->with('images');
                },
                'store:id,name,slug'
            ])
            ->orderBy('bid_price', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->filter(fn($ad) => !is_null($ad->product))
            ->unique('product_id');

        // Rekam impresi iklan untuk produk beriklan di banner strip
        if ($sponsoredAds->isNotEmpty()) {
            \App\Models\SellerAd::whereIn('id', $sponsoredAds->pluck('id'))
                ->increment('views_count');
        }

        // Toko / Akun yang cocok dengan kata kunci pencarian
        $matchedStores = collect();
        if ($request->filled('search')) {
            $searchKeyword = trim($request->search);
            $cleanedKeyword = ltrim($searchKeyword, '@');
            $matchedStores = \App\Models\Store::query()
                ->with(['user:id,name,avatar'])
                ->withCount(['products' => function($q) {
                    $q->published();
                }])
                ->where(function($q) use ($searchKeyword, $cleanedKeyword) {
                    $q->where('name', 'like', "%{$searchKeyword}%")
                      ->orWhere('slug', 'like', "%{$cleanedKeyword}%")
                      ->orWhere('description', 'like', "%{$searchKeyword}%")
                      ->orWhereHas('user', function($uq) use ($searchKeyword, $cleanedKeyword) {
                          $uq->where('name', 'like', "%{$searchKeyword}%")
                             ->orWhere('email', 'like', "%{$cleanedKeyword}%");
                      });
                })
                ->take(3)
                ->get();
        }

        return view('products.index', compact('products', 'categories', 'types', 'stores', 'banners', 'topProducts', 'sponsoredStores', 'matchedStores', 'sponsoredAds'));
    }

    public function show(Request $request, string $slug)
    {
        // Tangkap referensi afiliasi/toko jika ada di URL (?ref=slug_toko atau kode_referral)
        if ($request->filled('ref')) {
            $ref = $request->query('ref');
            session(['affiliate_ref' => $ref]);

            $affiliate = \App\Models\Affiliate::where('referral_code', $ref)->first();
            if ($affiliate) {
                $sessionKey = 'aff_clicked_' . $affiliate->id;
                if (!session()->has($sessionKey)) {
                    $clicks = (int) $affiliate->clicks_count + 1;
                    $affiliate->update(['clicks_count' => (string)$clicks]);
                    session([$sessionKey => true]);
                }
            }
        }

        $product = Product::with([
            'store:id,name,slug,logo', 
            'helpCategory.articles' => function($q) {
                $q->where('is_published', true);
            },
            'reviews.user'
        ])->where('slug', $slug)->published()->firstOrFail();

        // Increment views count saat produk dibuka
        $product->increment('views');

        // Rekam & proses pemotongan saldo CPC iklan bersponsor (?ad_id=...)
        if ($request->filled('ad_id')) {
            $adId = $request->query('ad_id');
            $ad = \App\Models\SellerAd::with('store')->find($adId);

            if ($ad && $ad->status === 'active' && $ad->store) {
                $currentUserId = \Illuminate\Support\Facades\Auth::id();
                $isOwner = $currentUserId && ($ad->store->user_id === $currentUserId);
                $sessionKey = 'ad_click_charged_' . $ad->id;

                // Proteksi anti-fraud & gratis klik bagi pemilik toko sendiri
                if (!$isOwner && !session()->has($sessionKey)) {
                    session([$sessionKey => true]);

                    // Cek batas alokasi modal harian jika jenis budget adalah 'daily'
                    $canCharge = true;
                    if ($ad->budget_type === 'daily' && $ad->daily_budget > 0) {
                        $todaySpent = \App\Models\AdTransaction::where('store_id', $ad->store_id)
                            ->where('type', 'deduction')
                            ->where('description', 'like', '%#AD-' . $ad->id . '%')
                            ->whereDate('created_at', \Carbon\Carbon::today())
                            ->sum('amount');

                        if ($todaySpent >= (float) $ad->daily_budget) {
                            $canCharge = false;
                        }
                    }

                    $cpcCost = (float) ($ad->bid_price ?: 500);
                    $currentBalance = (float) ($ad->store->ad_balance ?? 0);
                    $actualDeduct = $canCharge ? min($cpcCost, max(0, $currentBalance)) : 0;

                    if ($actualDeduct > 0) {
                        $ad->store->decrement('ad_balance', $actualDeduct);

                        // Catat mutasi transaksi pemotongan saldo iklan
                        \App\Models\AdTransaction::create([
                            'store_id' => $ad->store_id,
                            'reference_no' => 'ADCLK-' . $ad->id . '-' . strtoupper(\Illuminate\Support\Str::random(4)) . '-' . time(),
                            'type' => 'deduction',
                            'amount' => $actualDeduct,
                            'tax_amount' => 0,
                            'total_amount' => $actualDeduct,
                            'payment_method' => 'ad_balance',
                            'status' => 'completed',
                            'description' => 'Biaya Klik Iklan (CPC #AD-' . $ad->id . ') - ' . \Illuminate\Support\Str::limit($product->name, 40),
                        ]);
                    }

                    // Update statistik klik & akumulasi pengeluaran iklan
                    $ad->increment('clicks_count');
                    if ($actualDeduct > 0) {
                        $ad->increment('spent_amount', $actualDeduct);
                    }

                    // Jika saldo toko telah habis (<= 0), otomatis jeda (pause) iklan agar saldo tidak pernah minus
                    if (($currentBalance - $actualDeduct) <= 0) {
                        $ad->update(['status' => 'paused']);
                    }
                }
            }
        }

        $hasPurchased = false;
        $userReview = null;
        $userOrder = null;

        if (\Illuminate\Support\Facades\Auth::check()) {
            $user = \Illuminate\Support\Facades\Auth::user();
            $userOrder = \App\Models\Order::where('customer_email', $user->email)
                ->whereIn('status', ['paid', 'downloaded'])
                ->whereHas('orderItems', function ($q) use ($product) {
                    $q->where('product_id', $product->id);
                })
                ->latest()
                ->first();

            if ($userOrder) {
                $hasPurchased = true;
                $userReview = \App\Models\ProductReview::where('product_id', $product->id)
                    ->where('user_id', $user->id)
                    ->first();
            }
        }

        return view('products.show', compact('product', 'hasPurchased', 'userReview', 'userOrder'));
    }

    public function brochure(string $slug)
    {
        $product = Product::with(['category', 'type', 'images', 'store'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        $company = \App\Models\CompanyProfile::first(['*']);

        return view('products.brochure', compact('product', 'company'));
    }

    public function suggest(Request $request)
    {
        $query = trim($request->input('q', ''));
        if (strlen($query) < 2) {
            return response()->json(['stores' => [], 'products' => []]);
        }

        $cleanQuery = ltrim($query, '@');

        // Cari toko / akun
        $stores = \App\Models\Store::query()
            ->withCount(['products' => function($q) {
                $q->published();
            }])
            ->where(function($q) use ($query, $cleanQuery) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('slug', 'like', "%{$cleanQuery}%")
                  ->orWhereHas('user', function($uq) use ($query) {
                      $uq->where('name', 'like', "%{$query}%");
                  });
            })
            ->take(4)
            ->get()
            ->map(function($store) {
                return [
                    'id' => $store->id,
                    'name' => $store->name,
                    'slug' => $store->slug,
                    'logo' => $store->logo ? asset('storage/' . $store->logo) : 'https://ui-avatars.com/api/?name=' . urlencode($store->name) . '&background=0284c7&color=fff',
                    'products_count' => $store->products_count,
                    'url' => route('store.show', $store->slug),
                    'is_pro' => (bool)$store->is_pro,
                ];
            });

        // Cari produk
        $products = Product::query()
            ->published()
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('tags', 'like', "%{$query}%");
            })
            ->with(['images'])
            ->take(4)
            ->get()
            ->map(function($p) {
                $img = $p->images->firstWhere('is_main', true) ?? $p->images->first();
                $price = ($p->discount_price && $p->discount_price > 0 && $p->discount_price < $p->price) ? $p->discount_price : $p->price;
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'price_formatted' => 'Rp' . number_format($price, 0, ',', '.'),
                    'image' => $img ? asset('storage/' . $img->image_path) : null,
                    'url' => route('products.show', $p->slug),
                ];
            });

        return response()->json([
            'stores' => $stores,
            'products' => $products,
        ]);
    }
}

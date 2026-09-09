<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
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
            ->orderBy('views', 'desc')
            ->orderBy('sales_count', 'desc')
            ->limit(5)
            ->get();

        $query = Product::with(['store:id,name,slug', 'images', 'category:id,name', 'type:id,name'])
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
            $query->where('store_id', $request->store);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('tags', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('store', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('min_price')) {
            $query->whereRaw('COALESCE(discount_price, price) >= ?', [$request->min_price]);
        }

        if ($request->filled('max_price')) {
            $query->whereRaw('COALESCE(discount_price, price) <= ?', [$request->max_price]);
        }

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

        // Rekam kata kunci pencarian pembeli untuk analitik & tren populer
        if ($request->filled('search')) {
            \App\Models\ProductSearch::record($request->search, $products->total());
        }

        // Jika AJAX request
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest' || $request->boolean('ajax')) {
            return view('products._list', compact('products'))->render();
        }

        return view('products.index', compact('products', 'categories', 'types', 'stores', 'banners', 'topProducts'));
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
}

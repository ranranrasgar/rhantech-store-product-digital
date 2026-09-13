<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Mail\StoreStatusChangedMail;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    /**
     * Tampilkan daftar seluruh toko tenant beserta ringkasan perkembangan platform
     */
    public function index(Request $request)
    {
        // 1. Metrik Agregat Platform (Perkembangan Seluruh Toko)
        $totalStores = Store::count();
        $activeStores = Store::where(function ($q) {
            $q->whereNull('status')->orWhere('status', 'active');
        })->count();
        $suspendedStores = Store::where('status', 'suspended')->count();
        $bannedStores = Store::where('status', 'banned')->count();
        $proStores = Store::where('is_pro', true)->count();

        $totalTenantProducts = Product::whereNotNull('store_id')->count();
        $totalActiveProducts = Product::whereNotNull('store_id')->where('is_active', 1)->count();
        $totalPlatformOrders = Order::count();
        $totalCompletedOrders = Order::whereIn('status', ['paid', 'downloaded'])->count();
        
        $totalSalesVolume = (float) Order::whereIn('status', ['paid', 'downloaded'])->sum('amount');
        $totalStoreBalances = (float) Store::sum('balance');
        $totalAdBalances = (float) Store::sum('ad_balance');

        $platformSummary = [
            'total_stores' => $totalStores,
            'active_stores' => $activeStores,
            'suspended_stores' => $suspendedStores,
            'banned_stores' => $bannedStores,
            'pro_stores' => $proStores,
            'total_products' => $totalTenantProducts,
            'active_products' => $totalActiveProducts,
            'total_orders' => $totalPlatformOrders,
            'completed_orders' => $totalCompletedOrders,
            'total_sales' => $totalSalesVolume,
            'total_store_balance' => $totalStoreBalances,
            'total_ad_balance' => $totalAdBalances,
        ];

        // 2. Query Toko dengan Relasi & Hitungan
        $query = Store::query()
            ->with(['user:id,name,email,avatar,phone', 'bannedBy:id,name'])
            ->withCount([
                'products',
                'products as active_products_count' => function ($q) {
                    $q->where('is_active', 1);
                },
                'payoutRequests',
                'ads',
                'followers',
            ]);

        // Filter: Search Keyword
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter: Status Operasional
        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'active') {
                $query->where(function ($q) {
                    $q->whereNull('status')->orWhere('status', 'active');
                });
            } elseif ($request->status === 'pro') {
                $query->where('is_pro', true);
            } else {
                $query->where('status', $request->status);
            }
        }

        // Filter: Store Mode
        if ($request->filled('mode') && $request->mode !== 'all') {
            $query->where('store_mode', $request->mode);
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'oldest' => $query->oldest(),
            'balance_desc' => $query->orderByDesc('balance'),
            'ad_balance_desc' => $query->orderByDesc('ad_balance'),
            'views_desc' => $query->orderByDesc('views'),
            'name_asc' => $query->orderBy('name', 'asc'),
            'products_desc' => $query->orderByDesc('products_count'),
            default => $query->latest(),
        };

        $stores = $query->paginate(15)->withQueryString();

        // 3. Batch Hitung Metrik Penjualan & Pesanan per Toko untuk Toko di Halaman Ini
        $storeIds = $stores->pluck('id')->filter()->all();
        $orderMetrics = [];

        if (!empty($storeIds)) {
            $metricsRaw = OrderItem::query()
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereIn('products.store_id', $storeIds)
                ->selectRaw("
                    products.store_id,
                    COUNT(DISTINCT orders.id) as total_orders,
                    SUM(CASE WHEN orders.status IN ('paid', 'downloaded') THEN 1 ELSE 0 END) as paid_orders,
                    SUM(CASE WHEN orders.status = 'pending' THEN 1 ELSE 0 END) as pending_orders,
                    SUM(CASE WHEN orders.status IN ('paid', 'downloaded') THEN (order_items.price * order_items.quantity) ELSE 0 END) as gross_sales
                ")
                ->groupBy('products.store_id')
                ->get()
                ->keyBy('store_id');

            // Tambahkan juga pesanan direct referral
            $referralCounts = Order::query()
                ->whereIn('referrer_store_id', $storeIds)
                ->selectRaw("
                    referrer_store_id as store_id,
                    COUNT(*) as ref_total,
                    SUM(CASE WHEN status IN ('paid', 'downloaded') THEN 1 ELSE 0 END) as ref_paid
                ")
                ->groupBy('referrer_store_id')
                ->get()
                ->keyBy('store_id');

            foreach ($storeIds as $id) {
                $itemMetric = $metricsRaw->get($id);
                $refMetric = $referralCounts->get($id);

                $orderMetrics[$id] = [
                    'total_orders' => (int) ($itemMetric->total_orders ?? ($refMetric->ref_total ?? 0)),
                    'paid_orders' => (int) ($itemMetric->paid_orders ?? ($refMetric->ref_paid ?? 0)),
                    'pending_orders' => (int) ($itemMetric->pending_orders ?? 0),
                    'gross_sales' => (float) ($itemMetric->gross_sales ?? 0),
                ];
            }
        }

        return view('admin.stores.index', compact('stores', 'platformSummary', 'orderMetrics'));
    }

    /**
     * Ambil data relasi lengkap untuk modal info / konfirmasi hapus (JSON endpoint)
     */
    public function relatedData(Store $store)
    {
        $productsCount = $store->products()->count();
        $activeProductsCount = $store->products()->where('is_active', 1)->count();
        
        $ordersCount = Order::where(function ($q) use ($store) {
            $q->whereHas('orderItems.product', function ($sub) use ($store) {
                $sub->where('products.store_id', $store->id);
            })->orWhere('referrer_store_id', $store->id);
        })->count();

        $completedOrdersCount = Order::where(function ($q) use ($store) {
            $q->whereHas('orderItems.product', function ($sub) use ($store) {
                $sub->where('products.store_id', $store->id);
            })->orWhere('referrer_store_id', $store->id);
        })->whereIn('status', ['paid', 'downloaded'])->count();

        $payoutsCount = $store->payoutRequests()->count();
        $adsCount = $store->ads()->count();
        $chatCount = \App\Models\ChatMessage::where('store_id', $store->id)->count();
        $followersCount = $store->followers()->count();
        $categoriesCount = \App\Models\ProductCategory::where('store_id', $store->id)->count();
        $typesCount = \App\Models\ProductType::where('store_id', $store->id)->count();

        return response()->json([
            'success' => true,
            'store' => [
                'id' => $store->id,
                'name' => $store->name,
                'slug' => $store->slug,
                'balance' => (float) $store->balance,
                'ad_balance' => (float) $store->ad_balance,
                'status' => $store->status ?? 'active',
                'ban_reason' => $store->ban_reason,
                'owner_name' => $store->user->name ?? 'Tidak Diketahui',
                'owner_email' => $store->user->email ?? '-',
                'created_at' => $store->created_at->format('d M Y, H:i'),
            ],
            'related' => [
                'products' => $productsCount,
                'active_products' => $activeProductsCount,
                'orders' => $ordersCount,
                'completed_orders' => $completedOrdersCount,
                'payouts' => $payoutsCount,
                'ads' => $adsCount,
                'chats' => $chatCount,
                'followers' => $followersCount,
                'categories' => $categoriesCount,
                'types' => $typesCount,
            ]
        ]);
    }

    /**
     * Perbarui data profil, paket PRO, fee, dan saldo toko
     */
    public function update(Request $request, Store $store)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|alpha_dash|unique:stores,slug,' . $store->id,
            'description' => 'nullable|string|max:1000',
            'balance' => 'nullable|numeric|min:0',
            'default_affiliate_commission' => 'nullable|numeric|min:0|max:100',
            'is_pro' => 'nullable|boolean',
            'pro_expires_at' => 'nullable|date',
            'pro_plan' => 'nullable|string|max:100',
            'custom_payout_fee_percentage' => 'nullable|numeric|min:0|max:100',
            'store_mode' => 'nullable|in:store,profile,hybrid',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'slug' => strtolower($validated['slug']),
            'description' => $validated['description'] ?? $store->description,
            'default_affiliate_commission' => $validated['default_affiliate_commission'] ?? 0,
            'is_pro' => $request->boolean('is_pro'),
            'pro_plan' => $validated['pro_plan'] ?? null,
            'pro_expires_at' => $validated['pro_expires_at'] ?? null,
            'custom_payout_fee_percentage' => $validated['custom_payout_fee_percentage'] ?? null,
        ];

        if (isset($validated['store_mode'])) {
            $updateData['store_mode'] = $validated['store_mode'];
        }

        // Admin override balance jika diisi
        if ($request->filled('balance')) {
            $updateData['balance'] = (float) $validated['balance'];
        }

        $store->update($updateData);

        return back()->with('success', "Data toko '{$store->name}' berhasil diperbarui.");
    }

    /**
     * Banned / Bekukan Toko (dengan alasan, kirim notifikasi + email)
     */
    public function ban(Request $request, Store $store)
    {
        $validated = $request->validate([
            'target_status' => 'required|in:suspended,banned',
            'reason' => 'required|string|min:5|max:1000',
            'send_email' => 'nullable|boolean',
            'send_notification' => 'nullable|boolean',
        ]);

        $targetStatus = $validated['target_status'];
        $reason = trim($validated['reason']);

        $store->update([
            'status' => $targetStatus,
            'ban_reason' => $reason,
            'banned_at' => now(),
            'banned_by' => Auth::id(),
        ]);

        $statusLabel = $targetStatus === 'banned' ? 'diblokir permanen' : 'ditangguhkan sementara';

        // 1. Kirim Push Notification & Notifikasi Lonceng In-App jika dicentang
        if ($request->boolean('send_notification', true)) {
            try {
                app(FirebaseService::class)->notifyStoreStatusChanged($store, $targetStatus, $reason);
            } catch (\Throwable $e) {
                Log::error('Gagal mengirim push notification status toko: ' . $e->getMessage());
            }
        }

        // 2. Kirim Email Notifikasi jika dicentang dan user memiliki email
        if ($request->boolean('send_email', true) && $store->user && $store->user->email) {
            try {
                $adminName = Auth::user()->name ?? 'Tim Platform';
                Mail::to($store->user->email)->send(
                    new StoreStatusChangedMail($store, $targetStatus, $reason, $adminName)
                );
            } catch (\Throwable $e) {
                Log::error('Gagal mengirim email pembekuan toko: ' . $e->getMessage());
            }
        }

        return back()->with('success', "Toko '{$store->name}' berhasil {$statusLabel}. Notifikasi telah dikirimkan ke pemilik toko.");
    }

    /**
     * Pulihkan / Aktifkan Kembali Toko (Unban)
     */
    public function unban(Request $request, Store $store)
    {
        $store->update([
            'status' => 'active',
            'ban_reason' => null,
            'banned_at' => null,
            'banned_by' => null,
        ]);

        // Kirim notifikasi lonceng & FCM
        try {
            app(FirebaseService::class)->notifyStoreStatusChanged(
                $store,
                'active',
                'Toko Anda telah diaktifkan kembali oleh Admin Platform dan kini dapat menerima transaksi.'
            );
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim notifikasi unban toko: ' . $e->getMessage());
        }

        // Kirim email pemulihan
        if ($store->user && $store->user->email) {
            try {
                $adminName = Auth::user()->name ?? 'Tim Platform';
                Mail::to($store->user->email)->send(
                    new StoreStatusChangedMail($store, 'active', 'Toko Anda telah dipulihkan dan dapat beroperasi normal kembali.', $adminName)
                );
            } catch (\Throwable $e) {
                Log::error('Gagal mengirim email pemulihan toko: ' . $e->getMessage());
            }
        }

        return back()->with('success', "Toko '{$store->name}' telah dipulihkan ke status AKTIF. Pemberitahuan telah dikirimkan ke pemilik.");
    }

    /**
     * Hapus Toko beserta pembersihan aman terhadap seluruh relasi tabel
     */
    public function destroy(Request $request, Store $store)
    {
        $storeName = $store->name;

        DB::beginTransaction();
        try {
            // 1. Bersihkan relasi pivot
            DB::table('followers')->where('store_id', $store->id)->delete();
            DB::table('store_showcase_products')->where('store_id', $store->id)->delete();

            // 2. Bersihkan Iklan Toko (Seller Ads & Transaksi Iklan)
            $store->adTransactions()->delete();
            $store->ads()->delete();

            // 3. Bersihkan Pesan Chat Terkait Toko
            \App\Models\ChatMessage::where('store_id', $store->id)->delete();

            // 4. Bersihkan Payout Requests & Pro Subscriptions
            $store->payoutRequests()->delete();
            $store->proSubscriptions()->delete();

            // 5. Bersihkan Campaign Toko
            \App\Models\Campaign::where('store_id', $store->id)->delete();

            // 6. Amankan Pesanan Terkait (Null-kan referrer_store_id agar riwayat order pembeli tidak hilang)
            Order::where('referrer_store_id', $store->id)->update(['referrer_store_id' => null]);

            // 7. Bersihkan Kategori & Tipe Produk Toko
            \App\Models\ProductCategory::where('store_id', $store->id)->delete();
            \App\Models\ProductType::where('store_id', $store->id)->delete();

            // 8. Hapus Produk Toko (Gambar dan File terkait)
            $products = $store->products()->get();
            foreach ($products as $product) {
                // Hapus gambar produk dari storage
                foreach ($product->images as $img) {
                    if ($img->image_path && Storage::disk('public')->exists($img->image_path)) {
                        Storage::disk('public')->delete($img->image_path);
                    }
                }
                $product->images()->delete();
                $product->reviews()->delete();

                // Hapus file produk jika lokal
                if ($product->file_path && Storage::disk('public')->exists($product->file_path)) {
                    Storage::disk('public')->delete($product->file_path);
                }

                $product->delete();
            }

            // 9. Hapus Project Toko jika ada
            $projects = $store->projects()->get();
            foreach ($projects as $project) {
                $project->delete();
            }

            // 10. Hapus File Logo & Banner Toko
            if ($store->logo && Storage::disk('public')->exists($store->logo)) {
                Storage::disk('public')->delete($store->logo);
            }
            if ($store->banner && Storage::disk('public')->exists($store->banner)) {
                Storage::disk('public')->delete($store->banner);
            }

            // 11. Hapus record toko itu sendiri
            $store->delete();

            DB::commit();

            return redirect()->route('admin.stores.index')->with('success', "Toko '{$storeName}' beserta seluruh produk dan data terkait berhasil dihapus permanen.");
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Gagal menghapus toko {$storeName}: " . $e->getMessage());
            return back()->with('error', "Gagal menghapus toko: " . $e->getMessage());
        }
    }
}

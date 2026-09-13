<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\Order;
use App\Models\Store;
use App\Models\PayoutRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Ambil data notifikasi untuk lonceng navbar (Admin, Tenant, Pembeli)
     */
    public function getNotifications(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['unread_count' => 0, 'notifications' => []]);
        }

        $isAdmin = ($user->role === 'Admin');
        $hasStore = (bool) $user->store;
        $requestedRole = $request->query('role', 'buyer');

        // Query notifikasi dari tabel app_notifications
        $query = AppNotification::latest();

        if ($requestedRole === 'admin' && $isAdmin) {
            // Admin Platform: melihat notifikasi platform (admin)
            $query->where(function ($q) use ($user) {
                $q->where('target_role', 'admin')
                  ->orWhereNull('target_role')
                  ->orWhere('user_id', $user->id);
            });
        } elseif ($hasStore) {
            // User memiliki toko:
            if ($requestedRole === 'tenant') {
                // Di dashboard toko: tampilkan notifikasi toko miliknya (+ notifikasi pembeli miliknya)
                $query->where('user_id', $user->id)
                      ->whereIn('target_role', ['tenant', 'buyer']);
            } else {
                // Di halaman umum: notifikasi untuk user ini
                $query->where('user_id', $user->id);
            }
        } else {
            // User TIDAK memiliki toko (pembeli/customer biasa):
            // HANYA notifikasi pembelian miliknya sendiri, DILARANG menampilkan notifikasi penjualan/tenant toko lain!
            $query->where('user_id', $user->id)
                  ->where('target_role', 'buyer');
        }

        // Jika riwayat notifikasi pembeli masih kosong, sinkronkan dari pesanan riil pembeli
        if ($query->count() === 0 && !$isAdmin) {
            $this->seedInitialNotifications($hasStore ? 'tenant' : 'buyer', $user);
        }

        $rawNotifications = $query->take(20)->get();

        // Validasi integritas: jika notifikasi pesanan tetapi pesanannya sudah dihapus dari sistem, otomatis bersihkan
        $validNotifications = $rawNotifications->filter(function ($item) use ($user, $hasStore, $isAdmin) {
            if (in_array($item->type, ['order_created', 'order_pending', 'order_paid', 'order_failed'])) {
                $orderId = $item->data['order_id'] ?? null;
                $invoice = $item->data['invoice'] ?? null;

                // Ekstrak nomor invoice jika tersimpan di title / body (#RHN-...)
                if (!$invoice && preg_match('/#([A-Za-z0-9\-]+)/', $item->title . ' ' . $item->body, $matches)) {
                    $invoice = $matches[1];
                }

                $order = null;
                if ($orderId) {
                    $order = \App\Models\Order::find($orderId);
                } elseif ($invoice) {
                    $order = \App\Models\Order::where('invoice_number', $invoice)->first();
                }

                // Jika pesanan sudah dihapus di admin atau sistem -> bersihkan notifikasi
                if (!$order) {
                    $item->delete();
                    return false;
                }

                // Jika user adalah buyer murni (tanpa toko), pastikan order ini milik email user
                if (!$isAdmin && !$hasStore) {
                    if ($order->customer_email !== $user->email) {
                        $item->delete();
                        return false;
                    }
                }
            }
            return true;
        })->values();

        $notifications = $validNotifications->take(8)->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'body' => $item->body,
                'icon' => $item->icon ?: 'notifications',
                'url' => $item->url ?: '#',
                'is_read' => (bool) $item->is_read,
                'time_ago' => $item->created_at->diffForHumans(),
                'type' => $item->type,
            ];
        });

        $unreadCount = $validNotifications->where('is_read', false)->count();

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $notifications,
            'has_store' => $hasStore,
        ]);
    }

    /**
     * Tandai semua notifikasi sebagai telah dibaca
     */
    public function markAllAsRead(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false]);
        }

        $isAdmin = ($user->role === 'Admin');
        $hasStore = (bool) $user->store;
        $requestedRole = $request->input('role', 'buyer');

        $query = AppNotification::where('is_read', false);

        if ($requestedRole === 'admin' && $isAdmin) {
            $query->where(function ($q) use ($user) {
                $q->where('target_role', 'admin')
                  ->orWhereNull('target_role')
                  ->orWhere('user_id', $user->id);
            });
        } elseif ($hasStore) {
            $query->where('user_id', $user->id);
        } else {
            $query->where('user_id', $user->id)
                  ->where('target_role', 'buyer');
        }

        $query->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Isi awal dari transaksi riil yang ada jika tabel belum terisi
     *
     * @param string $role
     * @param \App\Models\User|null $user
     * @return void
     */
    protected function seedInitialNotifications(string $role, ?User $user): void
    {
        try {
            if ($role === 'admin') {
                // Notifikasi dari order terbaru
                foreach (Order::latest()->take(5)->get() as $o) {
                    $isPaid = in_array($o->status, ['paid', 'downloaded']);
                    AppNotification::create([
                        'user_id' => null,
                        'target_role' => 'admin',
                        'type' => $isPaid ? 'order_paid' : 'order_created',
                        'title' => ($isPaid ? '💰 Penjualan Sukses: #' : '🛒 Pesanan Masuk: #') . $o->invoice_number,
                        'body' => "Order #{$o->invoice_number} senilai Rp " . number_format($o->amount, 0, ',', '.') . ($isPaid ? ' lunas dibayar.' : ' menunggu konfirmasi.'),
                        'icon' => $isPaid ? 'paid' : 'shopping_cart',
                        'url' => route('admin.orders.index'),
                        'created_at' => $o->created_at,
                    ]);
                }

                // Notifikasi toko terbaru
                foreach (Store::latest()->take(3)->get() as $s) {
                    AppNotification::create([
                        'user_id' => null,
                        'target_role' => 'admin',
                        'type' => 'new_store',
                        'title' => "🏪 Toko Baru Terdaftar: {$s->name}",
                        'body' => "Mitra baru mendaftarkan toko '{$s->name}'.",
                        'icon' => 'storefront',
                        'url' => route('admin.stores.index'),
                        'created_at' => $s->created_at,
                    ]);
                }
            } elseif ($role === 'tenant' && $user && $user->store) {
                $store = $user->store;
                $orders = Order::whereHas('orderItems.product', function ($q) use ($store) {
                    $q->where('store_id', $store->id);
                })->latest()->take(6)->get();

                foreach ($orders as $o) {
                    $isPaid = in_array($o->status, ['paid', 'downloaded']);
                    AppNotification::create([
                        'user_id' => $user->id,
                        'target_role' => 'tenant',
                        'type' => $isPaid ? 'order_paid' : 'order_created',
                        'title' => ($isPaid ? '💰 Penjualan Sukses: #' : '🛒 Pesanan Baru: #') . $o->invoice_number,
                        'body' => "Pesanan #{$o->invoice_number} senilai Rp " . number_format($o->amount, 0, ',', '.') . ($isPaid ? ' berhasil lunas!' : ' menunggu pembayaran.'),
                        'icon' => $isPaid ? 'paid' : 'shopping_bag',
                        'url' => route('tenant.orders.index'),
                        'created_at' => $o->created_at,
                    ]);
                }
            } elseif ($role === 'buyer' && $user) {
                // Notifikasi pembeli HANYA berdasarkan email akun terdaftar
                $orders = Order::where('customer_email', $user->email)
                    ->latest()->take(6)->get();

                foreach ($orders as $o) {
                    $isPaid = in_array($o->status, ['paid', 'downloaded']);
                    AppNotification::create([
                        'user_id' => $user->id,
                        'target_role' => 'buyer',
                        'type' => $isPaid ? 'order_paid' : 'order_created',
                        'title' => ($isPaid ? '✅ Pembelian Sukses: #' : '⏳ Menunggu Pembayaran: #') . $o->invoice_number,
                        'body' => "Pesanan #{$o->invoice_number} senilai Rp " . number_format($o->amount, 0, ',', '.') . ($isPaid ? ' siap diunduh di akun Anda.' : ' menunggu pembayaran.'),
                        'icon' => $isPaid ? 'check_circle' : 'pending',
                        'url' => route('tenant.purchases.index'),
                        'data' => [
                            'order_id' => $o->id,
                            'invoice' => $o->invoice_number,
                        ],
                        'created_at' => $o->created_at,
                    ]);
                }
            }
        } catch (\Throwable $e) {}
    }
}

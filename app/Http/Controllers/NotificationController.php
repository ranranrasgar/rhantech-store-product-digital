<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\Order;
use App\Models\Store;
use App\Models\PayoutRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Ambil data notifikasi untuk lonceng navbar (Admin, Tenant, Pembeli)
     */
    public function getNotifications(Request $request)
    {
        $role = $request->query('role', 'buyer');
        $user = Auth::user();

        // Query notifikasi dari tabel app_notifications
        $query = AppNotification::latest();

        if ($role === 'admin') {
            $query->where(function ($q) use ($user) {
                $q->where('target_role', 'admin')
                  ->orWhereNull('target_role');
                if ($user) {
                    $q->orWhere('user_id', $user->id);
                }
            });
        } elseif ($role === 'tenant') {
            if ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhere('target_role', 'tenant');
                });
            } else {
                $query->where('target_role', 'tenant');
            }
        } else {
            // Buyer
            if ($user) {
                $query->where('user_id', $user->id);
            } else {
                return response()->json(['unread_count' => 0, 'notifications' => []]);
            }
        }

        // Jika tabel notifikasi masih kosong, buat otomatis dari data riil
        if ($query->count() === 0) {
            $this->seedInitialNotifications($role, $user);
        }

        $notifications = $query->take(8)->get()->map(function ($item) {
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

        $unreadCount = $query->where('is_read', false)->count();

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Tandai semua notifikasi sebagai telah dibaca
     */
    public function markAllAsRead(Request $request)
    {
        $role = $request->input('role', 'buyer');
        $user = Auth::user();

        $query = AppNotification::where('is_read', false);

        if ($role === 'admin') {
            $query->where(function ($q) use ($user) {
                $q->where('target_role', 'admin')
                  ->orWhereNull('target_role');
                if ($user) {
                    $q->orWhere('user_id', $user->id);
                }
            });
        } elseif ($role === 'tenant') {
            if ($user) {
                $query->where('user_id', $user->id);
            }
        } else {
            if ($user) {
                $query->where('user_id', $user->id);
            }
        }

        $query->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Isi awal dari transaksi riil yang ada jika tabel belum terisi
     */
    protected function seedInitialNotifications(string $role, $user): void
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
                $orders = Order::where('customer_email', $user->email)
                    ->orWhere('customer_phone', $user->phone ?? '')
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
                        'created_at' => $o->created_at,
                    ]);
                }
            }
        } catch (\Throwable $e) {}
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Store;
use App\Models\Order;
use App\Models\FcmToken;
use App\Services\FirebaseService;

class TestPushNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:test {--type=all : Jenis test: all, store, order, paid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uji coba pengiriman Push Notification FCM ke Admin, Tenant, dan Pembeli';

    /**
     * Execute the console command.
     */
    public function handle(FirebaseService $firebase)
    {
        $this->info("=== UJI COBA PUSH NOTIFICATION (FCM) ===");

        $tokenCount = FcmToken::count();
        $this->line("Total FCM Token terdaftar di database: <fg=yellow>{$tokenCount}</>");

        foreach (FcmToken::with('user')->get() as $t) {
            $u = $t->user;
            $userInfo = $u ? "User #{$u->id} {$u->name} (Role: {$u->role}, Email: {$u->email})" : "No User";
            $this->line(" - Token #{$t->id}: {$userInfo} | " . substr($t->token, 0, 25) . "...");
        }

        if ($tokenCount === 0) {
            $this->warn("PERINGATAN: Belum ada browser/device yang mendaftarkan FCM Token.");
            $this->line("Silakan buka aplikasi di browser (misal Chrome / Edge), login, dan klik 'Allow / Izinkan' pada popup notifikasi.");
        }

        $type = $this->option('type') ?? 'all';

        if ($type === 'store' || $type === 'all') {
            $this->info("\n1. Menguji Notifikasi Toko Baru Terdaftar (Target: Admin Platform)...");
            $store = Store::with('user')->first();
            if (!$store) {
                $user = User::first() ?? User::factory()->create();
                $store = Store::create([
                    'user_id' => $user->id,
                    'name' => 'Demo Store Notif',
                    'slug' => 'demo-store-notif',
                    'balance' => 0,
                    'store_mode' => 'hybrid',
                ]);
            }
            $firebase->notifyNewStoreCreated($store);
            $this->info("✓ Notifikasi Toko Baru berhasil dikirim ke Admin!");
        }

        if ($type === 'order' || $type === 'all') {
            $this->info("\n2. Menguji Notifikasi Pesanan Baru / Pending (Target: Tenant, Admin, Pembeli)...");
            $order = Order::with('orderItems.product.store')->first();
            if ($order) {
                $firebase->notifyOrderCreated($order);
                $this->info("✓ Notifikasi Pesanan Baru (#{$order->invoice_number}) berhasil dikirim!");
            } else {
                $this->line("- Belum ada data pesanan di tabel orders, melewati step ini.");
            }
        }

        if ($type === 'paid' || $type === 'all') {
            $this->info("\n3. Menguji Notifikasi Pembayaran Sukses / Lunas (Target: Pembeli, Tenant, Admin)...");
            $order = Order::with('orderItems.product.store')->first();
            if ($order) {
                $firebase->notifyOrderStatusChanged($order, 'paid');
                $this->info("✓ Notifikasi Pembayaran Sukses (#{$order->invoice_number}) berhasil dikirim!");
            } else {
                $this->line("- Belum ada data pesanan di tabel orders, melewati step ini.");
            }
        }

        // Broadcast langsung ke SEMUA token yang terdaftar untuk memastikan browser menerima
        $allTokens = FcmToken::pluck('token')->unique()->toArray();
        if (!empty($allTokens)) {
            $this->info("\n4. Mengirim notifikasi tes langsung ke SEMUA (" . count($allTokens) . ") token perangkat aktif...");
            $firebase->sendNotificationToTokens(
                $allTokens,
                '🔔 Tes Push Notifikasi RHanTech',
                'Halo! Jika Anda melihat ini, sistem Push Notifikasi FCM berfungsi 100% sempurna!',
                url('/'),
                ['type' => 'system_test']
            );
            $this->info("✓ Selesai disiarkan ke semua token!");
        }

        $this->info("\n=== PENGUJIAN SELESAI ===");
        return 0;
    }
}

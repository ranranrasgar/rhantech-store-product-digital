<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseService
{
    protected ?Messaging $messaging = null;
    protected ?string $projectId;
    protected ?string $credentialsPath;

    public function __construct()
    {
        $this->projectId = config('services.firebase.project_id') ?: env('FIREBASE_PROJECT_ID');
        $this->credentialsPath = config('services.firebase.credentials') ?: env('FIREBASE_CREDENTIALS', 'storage/app/firebase-credentials.json');

        $resolvedPath = $this->resolveCredentialsPath();
        if ($resolvedPath && file_exists($resolvedPath)) {
            try {
                $factory = (new Factory)->withServiceAccount($resolvedPath);
                $this->messaging = $factory->createMessaging();
            } catch (\Throwable $e) {
                Log::warning('Kreait Firebase Factory init warning: ' . $e->getMessage());
            }
        }
    }

    /**
     * Mengambil path absolut ke file credentials JSON
     */
    public function resolveCredentialsPath(): ?string
    {
        if (empty($this->credentialsPath)) {
            $default = storage_path('app/firebase-credentials.json');
            return file_exists($default) ? $default : null;
        }

        if (file_exists($this->credentialsPath)) {
            return $this->credentialsPath;
        }

        $base = base_path($this->credentialsPath);
        if (file_exists($base)) {
            return $base;
        }

        $storagePath = storage_path('app/' . basename($this->credentialsPath));
        if (file_exists($storagePath)) {
            return $storagePath;
        }

        return null;
    }

    /**
     * Memeriksa apakah kredensial Service Account terkonfigurasi
     */
    public function isConfigured(): bool
    {
        $path = $this->resolveCredentialsPath();
        return !empty($this->projectId) && $path && file_exists($path);
    }

    /**
     * Kirim notifikasi ke satu User (kompatibilitas lama + FCM HTTP v1)
     *
     * @param User|int|string|null $user
     * @param string $title
     * @param string $body
     * @param array $data
     * @param string|null $clickUrl
     * @return void
     */
    public function sendNotificationToUser(User|int|string|null $user, string $title, string $body, array $data = [], ?string $clickUrl = null): void
    {
        if (is_numeric($user) || is_string($user)) {
            $user = User::find($user);
        }

        if (!$user || !($user instanceof User)) {
            return;
        }

        $tokens = $user->fcmTokens()->pluck('token')->unique()->toArray();
        if (empty($tokens)) {
            return;
        }

        $this->sendNotificationToTokens($tokens, $title, $body, $clickUrl, $data);
    }

    /**
     * Alias helper sendToUser
     *
     * @param User|int|string|null $user
     * @param string $title
     * @param string $body
     * @param string|null $clickUrl
     * @param array $data
     * @return void
     */
    public function sendToUser(User|int|string|null $user, string $title, string $body, ?string $clickUrl = null, array $data = []): void
    {
        $this->sendNotificationToUser($user, $title, $body, $data, $clickUrl);
    }

    /**
     * Mengirim notifikasi langsung ke daftar token perangkat
     */
    public function sendNotificationToTokens(array $tokens, string $title, string $body, ?string $clickUrl = null, array $data = []): void
    {
        $tokens = array_filter(array_unique($tokens));
        if (empty($tokens)) {
            return;
        }

        $clickUrl = $clickUrl ?: url('/');

        // 1. Coba kirim via Kreait SDK jika aktif
        if ($this->messaging) {
            try {
                $payloadData = array_merge([
                    'title' => (string) $title,
                    'body' => (string) $body,
                    'click_action' => (string) $clickUrl,
                    'url' => (string) $clickUrl,
                    'timestamp' => (string) now()->timestamp,
                ], array_map('strval', $data));

                $message = CloudMessage::new()
                    ->withNotification(Notification::create($title, $body))
                    ->withData($payloadData);

                $this->messaging->sendMulticast($message, array_values($tokens));
                return;
            } catch (\Throwable $e) {
                Log::warning('Kreait multicast failed, falling back to direct FCM HTTP v1: ' . $e->getMessage());
            }
        }

        // 2. Direct FCM HTTP v1 API jika kredensial tersedia
        if ($this->isConfigured()) {
            try {
                $accessToken = $this->getOAuth2AccessToken();
                $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

                foreach ($tokens as $token) {
                    $message = [
                        'token' => $token,
                        'notification' => [
                            'title' => $title,
                            'body' => $body,
                        ],
                        'data' => array_merge([
                            'title' => (string) $title,
                            'body' => (string) $body,
                            'click_action' => (string) $clickUrl,
                            'url' => (string) $clickUrl,
                            'timestamp' => (string) now()->timestamp,
                        ], array_map('strval', $data)),
                        'webpush' => [
                            'headers' => ['Urgency' => 'high'],
                            'notification' => [
                                'title' => $title,
                                'body' => $body,
                                'icon' => url('/images/logo.png'),
                                'requireInteraction' => true,
                                'tag' => 'rhantech-' . md5($title . $clickUrl),
                            ],
                            'fcm_options' => [
                                'link' => $clickUrl,
                            ],
                        ],
                    ];

                    Http::withToken($accessToken)
                        ->withHeaders(['Content-Type' => 'application/json; UTF-8'])
                        ->timeout(5)
                        ->post($url, ['message' => $message]);
                }
            } catch (\Throwable $ex) {
                Log::error('FCM HTTP v1 Direct Send Error: ' . $ex->getMessage());
            }
        } else {
            Log::info("FCM Notification (Simulated/Not Configured): [{$title}] {$body} -> Target: " . count($tokens) . " tokens");
        }
    }

    /**
     * Kirim notifikasi ke seluruh Admin Platform
     */
    public function notifyAdmins(string $title, string $body, ?string $clickUrl = null, array $data = []): void
    {
        try {
            $admins = User::where('role', 'Admin')->with('fcmTokens')->get();
            $tokens = [];
            foreach ($admins as $admin) {
                foreach ($admin->fcmTokens as $t) {
                    if (!empty($t->token)) {
                        $tokens[] = $t->token;
                    }
                }
            }

            if (!empty($tokens)) {
                $this->sendNotificationToTokens($tokens, $title, $body, $clickUrl, $data);
            }
        } catch (\Throwable $e) {
            Log::error('notifyAdmins Error: ' . $e->getMessage());
        }
    }

    /**
     * Kirim notifikasi ke Pemilik Toko (Tenant)
     */
    public function notifyStoreOwner(Store $store, string $title, string $body, ?string $clickUrl = null, array $data = []): void
    {
        try {
            if ($store->user) {
                $this->sendNotificationToUser($store->user, $title, $body, $data, $clickUrl);
            }
        } catch (\Throwable $e) {
            Log::error('notifyStoreOwner Error: ' . $e->getMessage());
        }
    }

    /**
     * Kirim notifikasi status perubahan toko (banned / suspended / active) ke Tenant
     */
    public function notifyStoreStatusChanged(Store $store, string $status, ?string $reason = null): void
    {
        try {
            $statusTitles = [
                'banned' => "🚫 Toko Anda Diblokir Platform: {$store->name}",
                'suspended' => "⚠️ Toko Anda Ditangguhkan Sementara: {$store->name}",
                'active' => "✅ Toko Anda Telah Dipulihkan: {$store->name}",
            ];

            $statusBodies = [
                'banned' => "Toko '{$store->name}' telah dinonaktifkan/diblokir oleh Platform." . ($reason ? " Alasan: {$reason}" : ''),
                'suspended' => "Toko '{$store->name}' ditangguhkan sementara oleh Platform." . ($reason ? " Alasan: {$reason}" : ''),
                'active' => "Toko '{$store->name}' telah diaktifkan kembali dan kini dapat beroperasi normal.",
            ];

            $icons = [
                'banned' => 'block',
                'suspended' => 'warning',
                'active' => 'check_circle',
            ];

            $title = $statusTitles[$status] ?? "Pemberitahuan Status Toko: {$store->name}";
            $body = $statusBodies[$status] ?? "Status toko Anda telah diperbarui menjadi {$status}.";
            $icon = $icons[$status] ?? 'notifications';
            $clickUrl = url('/tenant/dashboard');

            // 1. Catat ke lonceng Tenant
            if ($store->user_id) {
                $this->recordNotification($store->user_id, 'tenant', 'store_status', $title, $body, $clickUrl, $icon, [
                    'store_id' => $store->id,
                    'status' => $status,
                    'reason' => $reason,
                ]);
            }

            // 2. Kirim Push Notification FCM ke perangkat pemilik toko
            $this->notifyStoreOwner($store, $title, $body, $clickUrl, [
                'type' => 'store_status',
                'status' => $status,
                'store_id' => (string) $store->id,
            ]);
        } catch (\Throwable $e) {
            Log::error('notifyStoreStatusChanged Error: ' . $e->getMessage());
        }
    }

    /**
     * Kirim notifikasi ke Pembeli (Customer)
     */
    public function notifyBuyer(Order $order, string $title, string $body, ?string $clickUrl = null, array $data = []): void
    {
        try {
            $buyer = User::where('email', $order->customer_email)->first();
            if ($buyer) {
                $this->sendNotificationToUser($buyer, $title, $body, $data, $clickUrl);
            }
        } catch (\Throwable $e) {
            Log::error('notifyBuyer Error: ' . $e->getMessage());
        }
    }

    /**
     * Catat notifikasi ke database agar muncul di lonceng navbar (Admin, Tenant, Pembeli)
     */
    public function recordNotification(?int $userId, ?string $targetRole, string $type, string $title, string $body, ?string $url = null, string $icon = 'notifications', array $data = []): void
    {
        try {
            \App\Models\AppNotification::create([
                'user_id' => $userId,
                'target_role' => $targetRole,
                'type' => $type,
                'title' => $title,
                'body' => $body,
                'url' => $url,
                'icon' => $icon,
                'is_read' => false,
                'data' => $data,
            ]);
        } catch (\Throwable $e) {
            Log::warning('recordNotification failed: ' . $e->getMessage());
        }
    }

    /**
     * Event 1: Ketika ada Tenant / Toko baru terdaftar
     * Penerima: Admin Platform
     */
    public function notifyNewStoreCreated(Store $store): void
    {
        $ownerName = $store->user->name ?? 'Mitra Baru';
        $title = "🏪 Toko Baru Terdaftar: {$store->name}";
        $body = "Mitra {$ownerName} baru saja mendaftarkan toko '{$store->name}'. Tinjau toko di admin panel.";
        $clickUrl = route('admin.stores.index');

        // Catat ke lonceng Admin
        $this->recordNotification(null, 'admin', 'new_store', $title, $body, $clickUrl, 'storefront', [
            'store_id' => $store->id,
            'store_name' => $store->name,
        ]);

        $this->notifyAdmins($title, $body, $clickUrl, [
            'type' => 'new_store',
            'store_id' => (string) $store->id,
            'store_name' => (string) $store->name,
        ]);
    }

    /**
     * Event 2: Ketika ada Pesanan Baru Masuk (Pending)
     * Penerima: Tenant, Admin Platform, dan Pembeli
     */
    public function notifyOrderCreated(Order $order): void
    {
        $amountFormatted = 'Rp ' . number_format($order->amount, 0, ',', '.');
        $productTitle = $order->orderItems->first()->product->title ?? 'Produk Digital';

        // 1. Notifikasi ke Tenant (Pemilik Toko terkait)
        $notifiedStores = [];
        foreach ($order->orderItems as $item) {
            $store = $item->product->store ?? null;
            if ($store && !in_array($store->id, $notifiedStores)) {
                $notifiedStores[] = $store->id;
                $tenantTitle = "🛒 Pesanan Baru Masuk: #{$order->invoice_number}";
                $tenantBody = "Ada pesanan baru untuk '{$item->product->title}' sebesar {$amountFormatted} (Menunggu Pembayaran).";
                $tenantUrl = route('tenant.orders.index');

                // Catat ke lonceng Tenant
                $this->recordNotification($store->user_id, 'tenant', 'order_created', $tenantTitle, $tenantBody, $tenantUrl, 'shopping_cart', [
                    'order_id' => $order->id,
                    'invoice' => $order->invoice_number,
                ]);

                $this->notifyStoreOwner($store, $tenantTitle, $tenantBody, $tenantUrl, [
                    'type' => 'order_pending',
                    'order_id' => (string) $order->id,
                    'invoice' => (string) $order->invoice_number,
                ]);
            }
        }

        // 2. Notifikasi ke Admin Platform
        $adminTitle = "🛍️ Pesanan Baru di Platform: #{$order->invoice_number}";
        $adminBody = "Pesanan senilai {$amountFormatted} dibuat oleh {$order->customer_name}.";
        $adminUrl = route('admin.orders.index');

        // Catat ke lonceng Admin
        $this->recordNotification(null, 'admin', 'order_created', $adminTitle, $adminBody, $adminUrl, 'shopping_bag', [
            'order_id' => $order->id,
            'invoice' => $order->invoice_number,
        ]);

        $this->notifyAdmins($adminTitle, $adminBody, $adminUrl, [
            'type' => 'order_created',
            'order_id' => (string) $order->id,
            'invoice' => (string) $order->invoice_number,
        ]);

        // 3. Notifikasi ke Pembeli
        $buyerTitle = "⏳ Menunggu Pembayaran: #{$order->invoice_number}";
        $buyerBody = "Pesanan Anda sebesar {$amountFormatted} berhasil dibuat. Segera selesaikan pembayaran sebelum kedaluwarsa.";
        $buyerUrl = url('/tenant/purchases');
        $buyer = User::where('email', $order->customer_email)->first();

        // Catat ke lonceng Pembeli
        if ($buyer) {
            $this->recordNotification($buyer->id, 'buyer', 'order_pending', $buyerTitle, $buyerBody, $buyerUrl, 'hourglass_empty', [
                'order_id' => $order->id,
                'invoice' => $order->invoice_number,
            ]);
        }

        $this->notifyBuyer($order, $buyerTitle, $buyerBody, $buyerUrl, [
            'type' => 'order_pending',
            'order_id' => (string) $order->id,
            'invoice' => (string) $order->invoice_number,
        ]);
    }

    /**
     * Event 3: Ketika Status Pembelian Berubah (Sukses/Paid atau Batal/Failed)
     * Penerima: Pembeli, Tenant, dan Admin
     */
    public function notifyOrderStatusChanged(Order $order, string $status): void
    {
        $amountFormatted = 'Rp ' . number_format($order->amount, 0, ',', '.');

        if ($status === 'paid' || $status === 'downloaded') {
            // A. PEMBAYARAN SUKSES / LUNAS

            // 1. Notifikasi ke Pembeli (Beri link unduh langsung)
            $buyerTitle = "🎉 Pembayaran Berhasil: #{$order->invoice_number}";
            $buyerBody = "Pembayaran {$amountFormatted} telah lunas! File produk digital Anda siap langsung diunduh.";
            $buyerUrl = url('/tenant/purchases');
            $buyer = User::where('email', $order->customer_email)->first();

            if ($buyer) {
                $this->recordNotification($buyer->id, 'buyer', 'order_paid', $buyerTitle, $buyerBody, $buyerUrl, 'download', [
                    'order_id' => $order->id,
                    'invoice' => $order->invoice_number,
                ]);
            }

            $this->notifyBuyer($order, $buyerTitle, $buyerBody, $buyerUrl, [
                'type' => 'order_paid',
                'order_id' => (string) $order->id,
                'invoice' => (string) $order->invoice_number,
            ]);

            // 2. Notifikasi ke Tenant (Saldo bertambah)
            $notifiedStores = [];
            foreach ($order->orderItems as $item) {
                $store = $item->product->store ?? null;
                if ($store && !in_array($store->id, $notifiedStores)) {
                    $notifiedStores[] = $store->id;
                    $itemTotalFormatted = 'Rp ' . number_format($item->price * $item->quantity, 0, ',', '.');
                    $tenantTitle = "💰 Penjualan Sukses: #{$order->invoice_number}";
                    $tenantBody = "Pembayaran untuk '{$item->product->title}' sebesar {$itemTotalFormatted} berhasil! Saldo toko Anda bertambah.";
                    $tenantUrl = route('tenant.orders.index');

                    // Catat ke lonceng Tenant
                    $this->recordNotification($store->user_id, 'tenant', 'order_paid', $tenantTitle, $tenantBody, $tenantUrl, 'paid', [
                        'order_id' => $order->id,
                        'invoice' => $order->invoice_number,
                    ]);

                    $this->notifyStoreOwner($store, $tenantTitle, $tenantBody, $tenantUrl, [
                        'type' => 'order_paid',
                        'order_id' => (string) $order->id,
                        'invoice' => (string) $order->invoice_number,
                    ]);
                }
            }

            // 3. Notifikasi ke Admin Platform
            $adminTitle = "💵 Pembayaran Dikonfirmasi: #{$order->invoice_number}";
            $adminBody = "Transaksi #{$order->invoice_number} senilai {$amountFormatted} berhasil lunas dibayar via Midtrans.";
            $adminUrl = route('admin.orders.index');

            // Catat ke lonceng Admin
            $this->recordNotification(null, 'admin', 'order_paid', $adminTitle, $adminBody, $adminUrl, 'check_circle', [
                'order_id' => $order->id,
                'invoice' => $order->invoice_number,
            ]);

            $this->notifyAdmins($adminTitle, $adminBody, $adminUrl, [
                'type' => 'order_paid',
                'order_id' => (string) $order->id,
                'invoice' => (string) $order->invoice_number,
            ]);

        } elseif (in_array($status, ['failed', 'cancel', 'deny', 'expire'])) {
            // B. PEMBAYARAN DIBATALKAN / EXPIRED

            // 1. Notifikasi ke Pembeli
            $buyerTitle = "❌ Pesanan Dibatalkan: #{$order->invoice_number}";
            $buyerBody = "Batas waktu pembayaran pesanan #{$order->invoice_number} telah kedaluwarsa atau dibatalkan.";
            $buyerUrl = url('/tenant/purchases');
            $buyer = User::where('email', $order->customer_email)->first();

            if ($buyer) {
                $this->recordNotification($buyer->id, 'buyer', 'order_failed', $buyerTitle, $buyerBody, $buyerUrl, 'cancel', [
                    'order_id' => $order->id,
                    'invoice' => $order->invoice_number,
                ]);
            }

            $this->notifyBuyer($order, $buyerTitle, $buyerBody, $buyerUrl, [
                'type' => 'order_failed',
                'order_id' => (string) $order->id,
                'invoice' => (string) $order->invoice_number,
            ]);

            // 2. Notifikasi ke Tenant
            $notifiedStores = [];
            foreach ($order->orderItems as $item) {
                $store = $item->product->store ?? null;
                if ($store && !in_array($store->id, $notifiedStores)) {
                    $notifiedStores[] = $store->id;
                    $tenantTitle = "⚠️ Pesanan Dibatalkan: #{$order->invoice_number}";
                    $tenantBody = "Pesanan #{$order->invoice_number} senilai {$amountFormatted} kedaluwarsa atau dibatalkan.";
                    $tenantUrl = route('tenant.orders.index');

                    // Catat ke lonceng Tenant
                    $this->recordNotification($store->user_id, 'tenant', 'order_failed', $tenantTitle, $tenantBody, $tenantUrl, 'cancel', [
                        'order_id' => $order->id,
                        'invoice' => $order->invoice_number,
                    ]);

                    $this->notifyStoreOwner($store, $tenantTitle, $tenantBody, $tenantUrl, [
                        'type' => 'order_failed',
                        'order_id' => (string) $order->id,
                        'invoice' => (string) $order->invoice_number,
                    ]);
                }
            }
        }
    }

    /**
     * Menghasilkan OAuth2 Access Token dari Google Service Account JSON
     */
    protected function getOAuth2AccessToken(): string
    {
        return Cache::remember('fcm_google_oauth2_token_' . $this->projectId, 50 * 60, function () {
            $credPath = $this->resolveCredentialsPath();
            if (!$credPath || !file_exists($credPath)) {
                throw new \Exception("File kredensial Firebase Service Account tidak ditemukan di: {$credPath}");
            }

            $credentials = json_decode(file_get_contents($credPath), true);
            if (!isset($credentials['private_key'], $credentials['client_email'])) {
                throw new \Exception("Format file service-account.json tidak valid.");
            }

            $now = time();
            $header = ['alg' => 'RS256', 'typ' => 'JWT'];
            $claim = [
                'iss' => $credentials['client_email'],
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud' => 'https://oauth2.googleapis.com/token',
                'exp' => $now + 3600,
                'iat' => $now,
            ];

            $encodedHeader = $this->base64UrlEncode(json_encode($header));
            $encodedClaim = $this->base64UrlEncode(json_encode($claim));
            $dataToSign = $encodedHeader . '.' . $encodedClaim;

            $signature = '';
            $success = openssl_sign($dataToSign, $signature, $credentials['private_key'], OPENSSL_ALGO_SHA256);
            if (!$success) {
                throw new \Exception("Gagal menandatangani JWT dengan OpenSSL private key.");
            }

            $jwt = $dataToSign . '.' . $this->base64UrlEncode($signature);

            $res = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]);

            if (!$res->successful()) {
                throw new \Exception("Gagal memperoleh OAuth2 token dari Google: " . $res->body());
            }

            $data = $res->json();
            return $data['access_token'];
        });
    }

    protected function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}

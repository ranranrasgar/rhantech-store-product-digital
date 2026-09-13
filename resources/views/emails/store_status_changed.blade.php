<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Pemberitahuan Status Toko</title>
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #1f2937; background-color: #f3f4f6; margin: 0; padding: 24px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
        
        {{-- Header Status --}}
        @php
            $headerBg = match($status) {
                'banned' => '#dc2626',
                'suspended' => '#d97706',
                'active' => '#16a34a',
                default => '#2563eb'
            };
            $statusLabel = match($status) {
                'banned' => 'DIBLOKIR / BANNED',
                'suspended' => 'DITANGGUHKAN SEMENTARA',
                'active' => 'AKTIF KEMBALI / PULIH',
                default => strtoupper($status)
            };
            $statusIcon = match($status) {
                'banned' => '🚫',
                'suspended' => '⚠️',
                'active' => '✅',
                default => 'ℹ️'
            };
        @endphp
        <div style="background-color: {{ $headerBg }}; padding: 24px; text-align: center; color: #ffffff;">
            <div style="font-size: 32px; margin-bottom: 8px;">{{ $statusIcon }}</div>
            <h1 style="margin: 0; font-size: 20px; font-weight: 800; letter-spacing: 0.5px;">PEMBERITAHUAN STATUS TOKO</h1>
            <p style="margin: 6px 0 0 0; font-size: 14px; opacity: 0.9;">Platform Marketplace Produk Digital</p>
        </div>

        {{-- Body Content --}}
        <div style="padding: 28px;">
            <p style="font-size: 15px; margin-top: 0;">Halo <strong>{{ $store->user->name ?? 'Pemilik Toko' }}</strong>,</p>
            
            <p style="font-size: 14px; color: #4b5563;">
                Melalui surat elektronik resmi ini, kami menginformasikan mengenai perubahan status operasional untuk toko Anda pada platform kami:
            </p>

            <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; margin: 20px 0;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <tr>
                        <td style="padding: 6px 0; color: #6b7280; width: 140px;">Nama Toko:</td>
                        <td style="padding: 6px 0; font-weight: bold; color: #111827;">{{ $store->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #6b7280;">Username / Slug:</td>
                        <td style="padding: 6px 0; font-family: monospace; color: #2563eb;">{{ $store->slug }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #6b7280;">Status Baru:</td>
                        <td style="padding: 6px 0;">
                            <span style="display: inline-block; background-color: {{ $headerBg }}15; color: {{ $headerBg }}; font-weight: bold; padding: 2px 10px; border-radius: 9999px; font-size: 12px; border: 1px solid {{ $headerBg }}40;">
                                {{ $statusLabel }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #6b7280;">Tanggal Efektif:</td>
                        <td style="padding: 6px 0; color: #374151;">{{ now()->translatedFormat('d F Y, H:i') }} WIB</td>
                    </tr>
                </table>
            </div>

            {{-- Alasan Tindakan --}}
            @if(!empty($reason))
                <div style="margin-bottom: 20px;">
                    <h3 style="font-size: 14px; color: #111827; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Alasan & Catatan dari Tim Platform:</h3>
                    <div style="background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 14px; border-radius: 4px; font-size: 14px; color: #92400e; line-height: 1.5;">
                        {{ $reason }}
                    </div>
                </div>
            @endif

            {{-- Dampak Status --}}
            <div style="background-color: #f3f4f6; border-radius: 8px; padding: 16px; margin-bottom: 24px; font-size: 13px; color: #4b5563;">
                @if($status === 'banned')
                    <p style="margin: 0 0 8px 0; font-weight: bold; color: #991b1b;">Dampak Pemblokiran:</p>
                    <ul style="margin: 0; padding-left: 20px; space-y: 4px;">
                        <li>Halaman etalase toko dan produk digital Anda tidak dapat diakses atau dibeli oleh publik.</li>
                        <li>Penerimaan pesanan baru dihentikan secara permanen.</li>
                        <li>Riwayat transaksi dan saldo toko tetap dicatat sesuai kebijakan platform.</li>
                    </ul>
                @elseif($status === 'suspended')
                    <p style="margin: 0 0 8px 0; font-weight: bold; color: #92400e;">Dampak Penangguhan Sementara:</p>
                    <ul style="margin: 0; padding-left: 20px; space-y: 4px;">
                        <li>Toko Anda sedang dalam peninjauan kepatuhan platform.</li>
                        <li>Pengunjung toko akan melihat status penangguhan dan tidak dapat melakukan checkout produk.</li>
                        <li>Setelah persyaratan terpenuhi atau klarifikasi disetujui, toko Anda dapat diaktifkan kembali.</li>
                    </ul>
                @else
                    <p style="margin: 0 0 8px 0; font-weight: bold; color: #166534;">Toko Anda Telah Dipulihkan:</p>
                    <ul style="margin: 0; padding-left: 20px; space-y: 4px;">
                        <li>Seluruh produk digital Anda kini aktif kembali dan dapat dibeli seperti biasa.</li>
                        <li>Tautan etalase publik toko Anda dapat diakses kembali oleh pengunjung.</li>
                    </ul>
                @endif
            </div>

            {{-- Call To Action --}}
            <div style="text-align: center; margin: 28px 0 16px 0;">
                <a href="{{ url('/tenant/dashboard') }}" style="display: inline-block; background-color: #111827; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: bold; font-size: 14px;">
                    Buka Dashboard Tenant
                </a>
            </div>

            <p style="font-size: 13px; color: #6b7280; text-align: center; margin-top: 20px;">
                Jika Anda merasa ini adalah kekeliruan atau ingin mengajukan banding, silakan hubungi tim dukungan kami melalui menu Pusat Bantuan atau balas pesan ini.
            </p>
        </div>

        {{-- Footer --}}
        <div style="border-top: 1px solid #e5e7eb; background-color: #f9fafb; padding: 16px 28px; text-align: center; font-size: 12px; color: #9ca3af;">
            <p style="margin: 0 0 4px 0;">Email otomatis ini dikirim oleh sistem pengelolaan {{ config('app.name', 'Platform') }}.</p>
            <p style="margin: 0;">&copy; {{ date('Y') }} {{ config('app.name', 'Platform') }}. Seluruh hak cipta dilindungi.</p>
        </div>
    </div>
</body>
</html>

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Service;

class ServicesAndProjectsSeeder extends Seeder
{
    /**
     * Hanya me-reset dan mengisi ulang tabel services.
     * Tabel projects, project_categories, project_types TIDAK disentuh
     * karena dikelola manual oleh admin via panel.
     */
    public function run(): void
    {
        DB::table('services')->truncate();

        $services = [
            [
                'name'              => 'Jual Source Code & Aplikasi Siap Pakai',
                'slug'              => 'jual-source-code-aplikasi',
                'icon'              => 'code',
                'short_description' => 'Jual produk digital Anda — source code, template, plugin, atau SaaS — langsung ke ribuan developer dan pebisnis digital di seluruh Indonesia.',
                'description'       => 'Platform marketplace kami memungkinkan developer & software house untuk memonetisasi hasil karya mereka. Upload produk digital, atur harga, dan terima pembayaran otomatis via Midtrans (QRIS, transfer bank, e-wallet).',
                'is_active'         => true,
                'sort_order'        => 1,
            ],
            [
                'name'              => 'Toko Online Developer (Seller Store)',
                'slug'              => 'toko-online-developer',
                'icon'              => 'storefront',
                'short_description' => 'Buka toko digital Anda sendiri dengan halaman profil, banner kustom, sistem voucher, dan fitur afiliasi — semua dalam satu platform.',
                'description'       => 'Setiap developer dapat memiliki halaman toko yang dipersonalisasi lengkap dengan katalog produk, ulasan pembeli, badge PRO, dan sistem komisi afiliasi untuk memperluas jangkauan penjualan.',
                'is_active'         => true,
                'sort_order'        => 2,
            ],
            [
                'name'              => 'Marketplace Produk Digital Terverifikasi',
                'slug'              => 'marketplace-produk-digital',
                'icon'              => 'verified',
                'short_description' => 'Setiap produk melewati proses kurasi & persetujuan admin sebelum tayang — memastikan kualitas dan keamanan bagi setiap pembeli.',
                'description'       => 'Kami menerapkan sistem approval produk, review terverifikasi, dan perlindungan pembeli. Produk hanya dapat diakses setelah pembayaran dikonfirmasi otomatis melalui gateway resmi.',
                'is_active'         => true,
                'sort_order'        => 3,
            ],
            [
                'name'              => 'Sistem Pembayaran & Payout Otomatis',
                'slug'              => 'pembayaran-payout-otomatis',
                'icon'              => 'payments',
                'short_description' => 'Integrasi Midtrans untuk pembayaran instan. Seller dapat mengajukan payout saldo kapan saja langsung ke rekening bank atau e-wallet.',
                'description'       => 'Kami mendukung QRIS, transfer bank (BCA, Mandiri, BNI, BRI), GoPay, OVO, ShopeePay, dan metode lainnya. Proses download produk berjalan otomatis begitu pembayaran terkonfirmasi tanpa campur tangan manual.',
                'is_active'         => true,
                'sort_order'        => 4,
            ],
            [
                'name'              => 'Program Afiliasi & Komisi Reseller',
                'slug'              => 'program-afiliasi-komisi',
                'icon'              => 'share',
                'short_description' => 'Dapatkan komisi setiap kali seseorang membeli produk melalui link referral Anda. Cocok untuk content creator, developer, dan komunitas tech.',
                'description'       => 'Sistem afiliasi kami memungkinkan siapa saja mendaftar sebagai affiliate dan menghasilkan komisi dari setiap konversi. Seller juga dapat mengatur persentase komisi per produk untuk mendorong reseller mempromosikan dagangan mereka.',
                'is_active'         => true,
                'sort_order'        => 5,
            ],
            [
                'name'              => 'Iklan Berbayar & Promosi Produk',
                'slug'              => 'iklan-berbayar-promosi',
                'icon'              => 'campaign',
                'short_description' => 'Tingkatkan visibilitas produk Anda dengan slot iklan berbayar, voucher promo, dan kampanye diskon yang muncul di halaman utama marketplace.',
                'description'       => 'Fitur iklan berbayar (Seller Ads) memungkinkan produk tampil di posisi teratas hasil pencarian dan halaman katalog. Didukung sistem voucher & kampanye diskon yang dapat dikonfigurasi fleksibel per produk maupun per toko.',
                'is_active'         => true,
                'sort_order'        => 6,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        $this->command->info('Services berhasil di-seed (' . count($services) . ' data)');
    }
}

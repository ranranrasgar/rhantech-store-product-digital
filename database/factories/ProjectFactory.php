<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fakerId = \Faker\Factory::create('id_ID');

        $umkmApps = [
            [
                'title' => 'Aplikasi Accounting & Keuangan UMKM',
                'desc' => 'Sistem pencatatan keuangan lengkap untuk mencatat pemasukan, pengeluaran, laba rugi, dan neraca UMKM.',
                'features' => "1. Pencatatan Jurnal Umum\n2. Laporan Laba Rugi & Neraca\n3. Manajemen Hutang Piutang\n4. Export Excel/PDF\n5. Multi-User Access",
                'price' => 'Rp 2.500.000 (Lisensi Seumur Hidup)',
                'category' => 'Aplikasi Accounting'
            ],
            [
                'title' => 'Sistem Point of Sales (POS) Toko Retail',
                'desc' => 'Aplikasi kasir modern untuk toko kelontong, minimarket, atau butik dengan fitur manajemen stok.',
                'features' => "1. Kasir / Transaksi Penjualan\n2. Manajemen Stok Barang (Inventory)\n3. Scan Barcode\n4. Cetak Struk Printer Thermal\n5. Laporan Penjualan Harian/Bulanan",
                'price' => 'Rp 1.500.000 / Tahun',
                'category' => 'Aplikasi Kasir (POS)'
            ],
            [
                'title' => 'Aplikasi Manajemen Distributor & Gudang',
                'desc' => 'Sistem ERP ringan khusus untuk distributor barang, melacak pergerakan barang dari gudang ke sales.',
                'features' => "1. Manajemen Multi-Gudang\n2. Surat Jalan & DO\n3. Tracking Salesman\n4. Manajemen Harga Grosir/Tier\n5. Laporan Stok Opname",
                'price' => 'Rp 5.000.000 (Lisensi Seumur Hidup)',
                'category' => 'Manajemen Gudang'
            ],
            [
                'title' => 'Software Manajemen Rumah Cuci (Laundry)',
                'desc' => 'Aplikasi khusus untuk mengelola operasional laundry kiloan maupun satuan, termasuk notifikasi WhatsApp ke pelanggan.',
                'features' => "1. Transaksi Kiloan & Satuan\n2. Status Pengerjaan (Cuci, Setrika, Selesai)\n3. Notifikasi WhatsApp Otomatis\n4. Laporan Kasir Shift\n5. Manajemen Karyawan",
                'price' => 'Rp 1.200.000 / Tahun',
                'category' => 'Aplikasi Jasa'
            ],
            [
                'title' => 'Sistem Informasi Bengkel & Sparepart',
                'desc' => 'Aplikasi manajemen bengkel motor/mobil untuk mencatat service kendaraan, mekanik, dan penjualan sparepart.',
                'features' => "1. Pendaftaran Service & Antrian\n2. Rekam Jejak Service Kendaraan (History)\n3. Penjualan Sparepart\n4. Komisi Mekanik\n5. Pengingat Service Berkala via WA",
                'price' => 'Rp 3.000.000 (Lisensi Seumur Hidup)',
                'category' => 'Aplikasi Bengkel'
            ],
            [
                'title' => 'Aplikasi Dealer Motor & Showroom',
                'desc' => 'Sistem khusus untuk dealer motor atau showroom mobil bekas untuk melacak unit kendaraan dan cicilan.',
                'features' => "1. Database Unit Kendaraan (No Rangka/Mesin)\n2. Penjualan Cash / Kredit\n3. Pengingat Jatuh Tempo Cicilan\n4. Manajemen Leasing\n5. Laporan Profit Penjualan",
                'price' => 'Rp 4.500.000 (Lisensi Seumur Hidup)',
                'category' => 'Aplikasi Dealer'
            ],
            [
                'title' => 'Aplikasi Service HP & Elektronik',
                'desc' => 'Software pengelolaan antrian dan perbaikan HP atau barang elektronik untuk melacak garansi dan status perbaikan.',
                'features' => "1. Penerimaan Barang & Tanda Terima\n2. Tracking Status Perbaikan Online\n3. Stok Sparepart IC/LCD\n4. Laporan Laba Rugi Service\n5. Garansi & Retur",
                'price' => 'Rp 1.800.000 / Tahun',
                'category' => 'Aplikasi Jasa'
            ],
            [
                'title' => 'Aplikasi Manajemen Klinik & Apotek',
                'desc' => 'Sistem informasi klinik untuk mencatat rekam medis pasien dan penjualan obat apotek terintegrasi.',
                'features' => "1. Rekam Medis Elektronik (RME)\n2. Antrian Dokter\n3. Resep & Penjualan Obat Apotek\n4. Stok Obat & Expired Date\n5. Laporan Kunjungan Pasien",
                'price' => 'Rp 6.000.000 (Lisensi Seumur Hidup)',
                'category' => 'Aplikasi Kesehatan'
            ]
        ];

        $app = $fakerId->randomElement($umkmApps);
        $title = $app['title'] . ' - ' . $fakerId->company();

        $images = [
            'clients/5uKlsHixcbvMTNFU5uakoFPK50yJauF6LTwV6Huh.jpg',
            'clients/dtnKl61EmBQKX7zN7wlnAkcH8Hr6CdgCAKGmJV5P.jpg',
            'clients/e5BLDmbiPooA9qC96xRYKZraN8eaavOC3XaMqufe.jpg',
        ];

        $fullDescription = "<p>{$app['desc']}</p>\n<h3>Fitur Utama:</h3>\n<pre>{$app['features']}</pre>\n<h3>Harga Retail / UMKM:</h3>\n<p><strong>{$app['price']}</strong></p>";

        return [
            'client_id' => Client::query()->inRandomOrder()->value('id') ?? Client::factory(),
            'title' => $title,
            'slug' => Str::slug($title) . '-' . uniqid(),
            'short_description' => $app['desc'],
            'description' => $fullDescription,
            'project_category_id' => \App\Models\ProjectCategory::query()->inRandomOrder()->value('id') ?? \App\Models\ProjectCategory::create(['name' => 'Default Category', 'slug' => 'default-category'])->id,
            'thumbnail' => $fakerId->randomElement($images),
            'project_url' => $fakerId->url(),
            'technologies' => json_encode($fakerId->randomElements(['Laravel', 'React', 'Vue', 'Tailwind CSS', 'Node.js', 'Python', 'Flutter', 'CodeIgniter', 'MySQL'], rand(2, 5))),
            'completed_at' => $fakerId->optional()->date(),
            'is_featured' => $fakerId->boolean(20), // 20% featured
            'status' => 'published',
        ];
    }
}

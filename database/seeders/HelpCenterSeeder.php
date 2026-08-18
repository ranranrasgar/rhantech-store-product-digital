<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\HelpCategory;
use App\Models\HelpArticle;

class HelpCenterSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Akun & Keamanan',
                'icon' => 'manage_accounts',
                'description' => 'Panduan mengelola profil, login, dan keamanan akun Anda.',
                'sort_order' => 1,
                'articles' => [
                    ['title' => 'Cara Membuat Akun Baru', 'content' => '
                        <h2>Panduan Pendaftaran Akun</h2>
                        <p>Memiliki akun di platform kami memberikan Anda akses penuh untuk berbelanja, mengunduh aset digital, bahkan membuka toko (tenant) Anda sendiri. Berikut adalah panduan langkah demi langkah untuk membuat akun baru.</p>

                        <h3>1. Akses Halaman Registrasi</h3>
                        <p>Buka situs web kami dan perhatikan bagian kanan atas halaman utama. Anda akan melihat tombol <strong>Log In / Daftar</strong>. Klik tombol tersebut untuk masuk ke halaman otentikasi.</p>
                        <p>Di halaman log in, klik tautan <strong>Daftar Sekarang</strong> yang berada di bagian bawah formulir.</p>
                        <img src="https://placehold.co/800x400/0d1117/00838f?text=Halaman+Registrasi" alt="Halaman Registrasi">

                        <h3>2. Mengisi Formulir Pendaftaran</h3>
                        <p>Anda akan melihat sebuah formulir yang meminta beberapa data dasar. Pastikan Anda mengisi data yang valid untuk memudahkan proses pemulihan akun di masa depan.</p>
                        <ul>
                            <li><strong>Nama Lengkap:</strong> Isi dengan nama asli Anda atau nama brand yang akan Anda gunakan.</li>
                            <li><strong>Email:</strong> Masukkan alamat email aktif. Kami akan mengirimkan tautan verifikasi ke email ini.</li>
                            <li><strong>Nomor Handphone:</strong> (Opsional) Masukkan nomor HP aktif untuk mempermudah komunikasi dengan dukungan pelanggan atau notifikasi pesanan.</li>
                            <li><strong>Password:</strong> Buat kata sandi yang kuat (minimal 8 karakter, mengandung huruf besar, huruf kecil, dan angka).</li>
                        </ul>
                        <img src="https://placehold.co/800x400/0d1117/00838f?text=Formulir+Isian+Data" alt="Formulir Pendaftaran">

                        <h3>3. Verifikasi Keamanan (CAPTCHA)</h3>
                        <p>Untuk memastikan bahwa pendaftaran dilakukan oleh manusia sungguhan dan menjaga platform tetap aman dari spam, silakan centang kotak verifikasi <em>Cloudflare Turnstile</em> yang bertuliskan "Verify you are human".</p>

                        <h3>4. Submit dan Verifikasi Email</h3>
                        <p>Setelah semua data terisi, klik tombol <strong>DAFTAR</strong>.</p>
                        <p>Jika pendaftaran berhasil, Anda akan otomatis masuk ke Dashboard. Namun, sebelum bisa menikmati semua fitur (seperti membuka toko), Anda harus <strong>memverifikasi email Anda</strong>. Buka kotak masuk (inbox) email Anda, cari email dari platform kami, dan klik tautan verifikasi di dalamnya.</p>
                        <p><strong>Tips:</strong> Jika Anda tidak melihat email di kotak masuk, periksa juga folder <em>Spam</em> atau <em>Junk</em> Anda.</p>
                        
                        <h3>Alternatif: Daftar Menggunakan Google</h3>
                        <p>Untuk proses yang lebih cepat, Anda juga bisa mendaftar hanya dengan 1 kali klik menggunakan akun Google Anda. Cukup klik tombol <strong>Lanjutkan dengan Google</strong> di halaman pendaftaran, pilih akun Google Anda, dan Anda akan langsung terdaftar!</p>
                    '],
                    ['title' => 'Mengapa Akun Saya Terkunci?', 'content' => '<p>Akun dapat terkunci jika terjadi aktivitas mencurigakan. Silakan hubungi Customer Support kami.</p>'],
                    ['title' => 'Cara Mengaktifkan 2FA', 'content' => '<p>Keamanan tambahan menggunakan Two-Factor Authentication sangat disarankan. Buka menu Pengaturan &gt; Keamanan.</p>']
                ]
            ],
            [
                'name' => 'Toko & Produk Digital',
                'icon' => 'storefront',
                'description' => 'Pelajari cara membuka toko dan menjual aset digital (source code, template).',
                'sort_order' => 2,
                'articles' => [
                    ['title' => 'Syarat Membuka Toko', 'content' => '<p>Anda harus melengkapi profil dan memverifikasi email sebelum dapat mengaktifkan fitur toko (Tenant).</p>'],
                    ['title' => 'Cara Upload Source Code / Produk', 'content' => '
                        <h2>Panduan Lengkap Mengunggah Produk Digital</h2>
                        <p>Mengunggah produk digital (seperti source code, template website, atau aset desain) di platform kami sangatlah mudah. Dengan mengikuti langkah-langkah di bawah ini, produk Anda akan segera tayang dan siap dibeli oleh jutaan pengguna.</p>
                        
                        <h3>Langkah 1: Masuk ke Dashboard Toko</h3>
                        <p>Pertama-tama, pastikan Anda sudah login ke akun Anda dan telah menyelesaikan proses pendaftaran toko. Setelah itu, klik menu <strong>Toko Saya</strong> yang berada di pojok kanan atas layar Anda. Menu ini akan mengarahkan Anda ke Dashboard khusus penjual (Tenant Dashboard).</p>
                        <img src="https://placehold.co/800x400/0d1117/00838f?text=Dashboard+Toko" alt="Tampilan Dashboard Toko">
                        <p>Di dashboard ini, Anda dapat melihat ringkasan pendapatan, total pesanan, dan berbagai menu manajemen toko lainnya di sidebar sebelah kiri.</p>

                        <h3>Langkah 2: Buka Menu Produk</h3>
                        <p>Pada sidebar sebelah kiri, cari dan klik menu <strong>Produk</strong>. Di halaman ini, Anda akan melihat daftar semua produk yang pernah Anda unggah. Jika ini adalah produk pertama Anda, daftarnya akan kosong.</p>
                        <p>Untuk menambahkan produk baru, klik tombol biru bertuliskan <strong>+ Tambah Produk Baru</strong> di sudut kanan atas halaman.</p>
                        <img src="https://placehold.co/800x400/0d1117/00838f?text=Tombol+Tambah+Produk" alt="Tampilan Menu Produk">

                        <h3>Langkah 3: Isi Detail Produk Anda</h3>
                        <p>Anda akan dihadapkan pada formulir pengisian data produk. Pastikan Anda mengisi semua kolom yang diwajibkan (ditandai dengan bintang merah) dengan detail dan akurat agar pembeli tertarik.</p>
                        <ul>
                            <li><strong>Nama Produk:</strong> Gunakan judul yang jelas dan mengandung kata kunci pencarian. Contoh: <em>Source Code Sistem Informasi Akademik Laravel 10</em>.</li>
                            <li><strong>Kategori:</strong> Pilih kategori yang paling sesuai (misalnya: Web Template, PHP Script, Mobile App).</li>
                            <li><strong>Harga:</strong> Masukkan harga jual dalam mata uang Rupiah tanpa titik (contoh: 150000). Jika Anda ingin memberikan diskon, isi kolom Harga Coret.</li>
                            <li><strong>Deskripsi Produk:</strong> Ceritakan secara lengkap fitur-fitur aplikasi, teknologi yang digunakan, serta panduan instalasi. Semakin lengkap deskripsinya, semakin besar kemungkinan produk terjual.</li>
                        </ul>
                        <img src="https://placehold.co/800x400/0d1117/00838f?text=Form+Detail+Produk" alt="Tampilan Form Produk">

                        <h3>Langkah 4: Unggah File Digital (Source Code)</h3>
                        <p>Bagian terpenting dari produk digital adalah file utamanya. Scroll ke bagian <strong>File Produk</strong>.</p>
                        <p>Anda harus mengompres (*compress*) seluruh folder source code Anda menjadi satu file berformat <strong>.zip</strong> atau <strong>.rar</strong>. Maksimal ukuran file yang diizinkan untuk diunggah langsung adalah 100MB.</p>
                        <p><strong>Tips Keamanan:</strong> Jangan sertakan file konfigurasi yang sensitif (seperti `.env` yang berisi password database *production* Anda) di dalam file ZIP tersebut. Sertakan file `.env.example` sebagai panduan bagi pembeli.</p>
                        <img src="https://placehold.co/800x400/0d1117/00838f?text=Upload+File+ZIP" alt="Area Upload File">

                        <h3>Langkah 5: Unggah Gambar Thumbnail (Preview)</h3>
                        <p>Gambar adalah hal pertama yang dilihat oleh calon pembeli. Unggah minimal 1 gambar thumbnail (disarankan ukuran 1280x720 pixel atau rasio 16:9). Anda juga dapat menambahkan beberapa screenshot dari aplikasi Anda sebagai galeri gambar.</p>

                        <h3>Langkah 6: Simpan dan Terbitkan</h3>
                        <p>Setelah semua data terisi dengan benar dan file berhasil diunggah, periksa kembali semuanya. Jika sudah yakin, klik tombol <strong>Simpan Produk</strong> di bagian paling bawah form.</p>
                        <p>Produk Anda kini telah aktif dan akan langsung muncul di halaman utama katalog produk digital kami! Bagikan tautan produk Anda ke media sosial untuk meningkatkan penjualan.</p>
                    '],
                    ['title' => 'Ketentuan File Produk Digital', 'content' => '<p>File harus aman dari virus dan tidak melanggar hak cipta pihak ketiga.</p>']
                ]
            ],
            [
                'name' => 'Pesanan & Transaksi',
                'icon' => 'receipt_long',
                'description' => 'Informasi tentang status pesanan, pembayaran, dan invoice.',
                'sort_order' => 3,
                'articles' => [
                    ['title' => 'Metode Pembayaran yang Tersedia', 'content' => '<p>Kami mendukung Virtual Account, e-Wallet (OVO, GoPay, Dana), dan QRIS melalui Midtrans.</p>'],
                    ['title' => 'Cara Melacak Status Pesanan', 'content' => '<p>Masuk ke menu <strong>Pembelian Saya</strong> untuk melihat status dan mengunduh produk digital yang sudah dibayar.</p>'],
                    ['title' => 'Mengapa Pembayaran Saya Gagal?', 'content' => '<p>Pastikan saldo Anda mencukupi dan koneksi internet stabil. Jika masih gagal, coba metode pembayaran lain.</p>']
                ]
            ],
            [
                'name' => 'Penarikan Dana (Payout)',
                'icon' => 'payments',
                'description' => 'Panduan menarik saldo penghasilan toko ke rekening bank Anda.',
                'sort_order' => 4,
                'articles' => [
                    ['title' => 'Kapan Dana Bisa Ditarik?', 'content' => '<p>Dana dari penjualan akan masuk ke Saldo Anda 3 hari setelah transaksi selesai.</p>'],
                    ['title' => 'Cara Setting Rekening Bank', 'content' => '<p>Buka menu <strong>Keuangan</strong> &gt; Rekening Bank, lalu isi nomor rekening yang valid dan atas nama Anda sendiri.</p>'],
                    ['title' => 'Biaya Admin Penarikan', 'content' => '<p>Penarikan ke bank selain BCA, Mandiri, BNI, BRI dikenakan biaya admin Rp 4.500.</p>']
                ]
            ],
            [
                'name' => 'Program Afiliasi',
                'icon' => 'handshake',
                'description' => 'Dapatkan penghasilan tambahan dengan mengundang pengguna lain.',
                'sort_order' => 5,
                'articles' => [
                    ['title' => 'Cara Daftar Program Afiliasi', 'content' => '<p>Program afiliasi terbuka untuk semua pengguna. Buka menu Afiliasi untuk mendapatkan link referral Anda.</p>'],
                    ['title' => 'Berapa Komisi yang Didapat?', 'content' => '<p>Anda mendapatkan komisi 10% dari setiap penjualan produk yang berasal dari link afiliasi Anda.</p>']
                ]
            ],
            [
                'name' => 'Iklan & Promosi',
                'icon' => 'campaign',
                'description' => 'Tingkatkan penjualan dengan fitur promosi dan iklan.',
                'sort_order' => 6,
                'articles' => [
                    ['title' => 'Cara Membuat Kampanye Iklan', 'content' => '<p>Buka menu Iklan (Campaign). Anda bisa mengatur budget harian dan target kata kunci pencarian.</p>'],
                    ['title' => 'Tips Agar Produk Tampil di Halaman Depan', 'content' => '<p>Gunakan gambar thumbnail yang menarik, deskripsi jelas, dan pertimbangkan untuk menggunakan fitur Featured Product.</p>']
                ]
            ],
            [
                'name' => 'Live Chat & Komunikasi',
                'icon' => 'forum',
                'description' => 'Panduan menggunakan fitur chat antara pembeli dan penjual.',
                'sort_order' => 7,
                'articles' => [
                    ['title' => 'Aturan Berkomunikasi', 'content' => '<p>Dilarang keras membagikan nomor WhatsApp atau kontak pribadi di live chat untuk menghindari penipuan di luar sistem.</p>']
                ]
            ],
        ];

        foreach ($categories as $catData) {
            $catSlug = Str::slug($catData['name']);
            
            $category = HelpCategory::updateOrCreate(
                ['slug' => $catSlug],
                [
                    'name' => $catData['name'],
                    'icon' => $catData['icon'],
                    'description' => $catData['description'],
                    'sort_order' => $catData['sort_order'],
                ]
            );

            foreach ($catData['articles'] as $artData) {
                $artSlug = Str::slug($artData['title']);
                
                HelpArticle::updateOrCreate(
                    ['slug' => $artSlug],
                    [
                        'help_category_id' => $category->id,
                        'title' => $artData['title'],
                        'content' => $artData['content'],
                        'is_published' => true,
                        'views' => rand(10, 500),
                    ]
                );
            }
        }
    }
}

<?php

namespace Database\Seeders;

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
                'name' => 'Iklan & Promosi Toko',
                'icon' => 'campaign',
                'description' => 'Panduan lengkap fitur Rhantech Ads, saldo iklan Rp500.000, kenaikan kunjungan +30%, dan bidding iklan.',
                'sort_order' => 1,
                'articles' => [
                    [
                        'title' => 'Panduan Lengkap Pusat Iklan Toko (Rhantech Ads)',
                        'content' => '
                            <h2>Mengenal Pusat Iklan Toko Rhantech Ads</h2>
                            <p><strong>Rhantech Ads</strong> adalah fitur periklanan bersponsor resmi dari platform Rhantech yang dirancang khusus untuk membantu pemilik toko digital (tenant) mendongkrak visibilitas produk, menjangkau ribuan calon pembeli aktif, dan mempercepat penjualan source code atau template digital Anda.</p>

                            <h3>Mengapa Harus Beriklan di Rhantech?</h3>
                            <ul>
                                <li><strong>Prioritas Posisi Teratas:</strong> Produk yang Anda iklankan secara otomatis ditampilkan di baris pertama hasil pencarian katalog ketika pembeli mencari kategori atau kata kunci relevan.</li>
                                <li><strong>Badge Khusus "Iklan":</strong> Kartu produk Anda akan dilengkapi dengan lencana eksklusif <em>"Iklan"</em> beraksen lampu biru langit yang menarik perhatian pembeli hingga 3x lipat dibanding produk biasa.</li>
                                <li><strong>Pelacakan Transparan:</strong> Anda dapat memantau secara real-time berapa kali iklan Anda dilihat (Impresi), berapa banyak calon pembeli yang mengklik (Klik), dan total biaya yang dikeluarkan.</li>
                            </ul>

                            <h3>Di Mana Iklan Akan Ditampilkan?</h3>
                            <p>Iklan Anda akan ditampilkan di lokasi-lokasi strategis dengan konversi tinggi:</p>
                            <ol>
                                <li><strong>Halaman Hasil Pencarian Marketplace (<code>/products?search=...</code>):</strong> Muncul di baris teratas mendahului produk non-iklan.</li>
                                <li><strong>Halaman Katalog Kategori:</strong> Ditampilkan secara terdepan saat pembeli memfilter berdasarkan kategori web, mobile, atau script.</li>
                                <li><strong>Rekomendasi Beranda Produk Pilihan:</strong> Algoritma platform secara dinamis menyisipkan produk beriklan pada widget rekomendasi beranda.</li>
                            </ol>
                        '
                    ],
                    [
                        'title' => 'Cara Kerja Saldo Iklan & Bonus Saldo Rp500.000',
                        'content' => '
                            <h2>Ke Mana Larinya Saldo Rp500.000?</h2>
                            <p>Banyak penjual baru bertanya: <em>"Ke mana saldo promo Rp500.000 ini masuk?"</em></p>
                            <p>Saldo <strong>Rp500.000</strong> adalah <strong>Modal Promosi Gratis</strong> yang disubsidi 100% oleh platform Rhantech untuk mendukung percepatan toko digital Anda sejak hari pertama buka toko.</p>

                            <h3>Alur Masuknya Saldo ke Akun Anda:</h3>
                            <ol>
                                <li><strong>Otomatis Saat Buka Toko:</strong> Ketika Anda mengklik tombol <em>"Buka Toko & Klaim Rp500.000"</em> di Dashboard dan mengaktifkan nama toko Anda, sistem secara otomatis memasukkan <strong>Rp500.000</strong> ke <strong>Saldo Iklan Toko</strong> Anda.</li>
                                <li><strong>Pemeriksaan Saldo:</strong> Buka menu sidebar <strong>Marketing & Promosi &rarr; Iklan Promosi Toko</strong> (<code>/tenant/ads</code>). Anda akan melihat kartu <strong>Saldo Saya: Rp500.000</strong> dalam status aktif dan siap digunakan.</li>
                                <li><strong>Tercatat di Mutasi Resmi:</strong> Setiap pemberian saldo voucher tercatat di riwayat mutasi keuangan iklan (<em>Ad Transactions</em>) dengan metode <code>promo_voucher</code> berstatus <code>completed</code>.</li>
                            </ol>

                            <h3>Apakah Saldo 500rb Ini Bisa Ditarik ke Rekening Bank?</h3>
                            <p><strong>Tidak.</strong> Saldo iklan Rp500.000 adalah kredit promosi internal yang khusus diperuntukkan sebagai modal pasang iklan produk. Namun, <strong>keuntungan dari hasil penjualan produk</strong> yang datang dari iklan tersebut adalah 100% milik Anda dan dapat dicairkan langsung ke rekening bank pribadi Anda kapan saja!</p>
                        '
                    ],
                    [
                        'title' => 'Bagaimana Kunjungan Toko Naik +30% & Posisi Teratas di Pencarian',
                        'content' => '
                            <h2>Mekanisme Kenaikan Kunjungan Toko +30%</h2>
                            <p>Salah satu keunggulan utama beriklan di Rhantech adalah lonjakan pengunjung tertarget hingga rata-rata <strong>+30%</strong> lebih tinggi dibandingkan toko tanpa promosi.</p>

                            <h3>Bagaimana Cara Kunjungan Ini Masuk ke Toko Anda?</h3>
                            <ol>
                                <li><strong>Algoritma Prioritas Pencarian (Search Boost):</strong> Saat calon pembeli mengetik kata kunci pencarian (contoh: <em>"Laravel"</em>, <em>"POS Kasir"</em>, <em>"E-Commerce"</em>), sistem katalog Rhantech memfilter produk yang memiliki kampanye iklan aktif dengan saldo mencukupi, lalu menempatkannya di ranking teratas (urutan #1).</li>
                                <li><strong>Peningkatan CTR (Click-Through Rate):</strong> Menurut data analitik platform, produk yang berada di posisi 3 teratas menerima lebih dari 70% total klik pembeli. Ditambah lencana sponsor yang eye-catching, calon pembeli cenderung mengklik produk Anda terlebih dahulu.</li>
                                <li><strong>Dukungan Subsidi Promosi Platform:</strong> Platform Rhantech mendistribusikan lalu lintas dari kampanye marketing eksternal ke halaman pencarian yang menampilkan produk-produk bersponsor.</li>
                            </ol>

                            <blockquote>
                                <strong>Estimasi Omzet Rp1.350.000 - Rp2.425.000:</strong><br>
                                Berdasarkan histori performa toko-toko yang mengaktifkan iklan produk unggulan dengan modal awal Rp500.000, rata-rata konversi penjualan menghasilkan nilai transaksi Rp1,3 juta hingga Rp2,4 juta dalam 7 - 14 hari pertama.
                            </blockquote>
                        '
                    ],
                    [
                        'title' => 'Panduan Memasang Iklan Produk: Bidding Manual vs Otomatis',
                        'content' => '
                            <h2>Langkah demi Langkah Membuat Iklan Produk Baru</h2>
                            <p>Untuk memulai kampanye iklan produk digital Anda, ikuti panduan berikut:</p>

                            <h3>Langkah 1: Masuk ke Menu Buat Iklan</h3>
                            <p>Buka menu <strong>Marketing & Promosi &rarr; Iklan Toko</strong> lalu klik tombol <strong>+ Buat Iklan Baru</strong> di pojok kanan atas (atau akses <code>/tenant/ads/create</code>).</p>

                            <h3>Langkah 2: Pengaturan Dasar Iklan</h3>
                            <ul>
                                <li><strong>Pilih Produk:</strong> Pilih produk digital yang ingin Anda promosikan. Disarankan memilih produk unggulan dengan gambar thumbnail menarik dan deskripsi lengkap.</li>
                                <li><strong>Nama Iklan:</strong> Berikan nama kampanye untuk mempermudah identifikasi (contoh: <em>Iklan Promo Source Code Kasir</em>).</li>
                                <li><strong>Tipe Modal (Anggaran):</strong> Anda bisa memilih <em>Tidak Terbatas</em> (iklan berjalan terus selama saldo mencukupi) atau <em>Atur Modal Harian</em> (misal: Rp25.000 per hari) agar pengeluaran modal terkontrol rapi.</li>
                                <li><strong>Periode Iklan:</strong> Tentukan apakah iklan berjalan tanpa batas waktu atau memiliki tanggal mulai dan berakhir.</li>
                            </ul>

                            <h3>Langkah 3: Memilih Mode Bidding (Penawaran)</h3>
                            <p>Tersedia dua opsi penawaran biaya per klik (CPC):</p>
                            <table class="w-full border border-gray-200 text-sm my-3">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="p-2 border font-bold">Fitur</th>
                                        <th class="p-2 border font-bold">Mode Manual (Rekomendasi)</th>
                                        <th class="p-2 border font-bold">Mode Otomatis</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="p-2 border font-semibold">Kontrol Biaya</td>
                                        <td class="p-2 border">Anda menentukan sendiri biaya per klik (mulai Rp100 - Rp1.000). Disarankan: Rp500/klik.</td>
                                        <td class="p-2 border">Sistem mengoptimalkan biaya bid secara otomatis sesuai tingkat persaingan.</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2 border font-semibold">Kata Kunci</td>
                                        <td class="p-2 border">Anda dapat memilih kata kunci spesifik dan menambahkan saran kata kunci populer.</td>
                                        <td class="p-2 border">Sistem mencocokkan kata kunci secara otomatis berdasarkan judul produk Anda.</td>
                                    </tr>
                                </tbody>
                            </table>

                            <h3>Langkah 4: Tetapkan Kata Pencarian (Keywords)</h3>
                            <p>Ketik kata kunci yang sering dicari pembeli, lalu tekan <strong>Enter</strong> atau klik tombol <strong>+ Tambah</strong>. Anda juga dapat mengklik saran kata kunci populer yang kami sediakan langsung dari database tren pencarian pembeli.</p>

                            <h3>Langkah 5: Simpan & Tampilkan Iklan</h3>
                            <p>Klik <strong>Tampilkan & Buat Iklan</strong>. Iklan Anda akan langsung aktif dan mulai ditayangkan di marketplace!</p>
                        '
                    ],
                    [
                        'title' => 'Cara Top-Up (Isi Ulang) Saldo Iklan Toko',
                        'content' => '
                            <h2>Panduan Top-Up Saldo Iklan</h2>
                            <p>Jika saldo promosi Anda telah habis atau Anda ingin memperbesar jangkauan promosi toko, Anda dapat melakukan isi ulang saldo iklan kapan saja.</p>

                            <h3>Langkah-langkah Top-Up Saldo:</h3>
                            <ol>
                                <li>Buka menu <strong>Marketing & Promosi &rarr; Iklan Toko</strong>.</li>
                                <li>Pada kartu <em>Saldo Saya</em>, klik tombol <strong>+ Isi Saldo</strong> (atau akses <code>/tenant/ads/top-up</code>).</li>
                                <li>Pilih nominal paket instan yang tersedia:
                                    <ul>
                                        <li>Rp25.000</li>
                                        <li>Rp50.000</li>
                                        <li>Rp100.000</li>
                                        <li>Rp200.000</li>
                                        <li>Rp500.000</li>
                                        <li>Rp1.000.000 s/d Rp50.000.000</li>
                                        <li>Atau masukkan <em>Nominal Kustom</em> sesuai kebutuhan Anda (minimal Rp10.000).</li>
                                    </ul>
                                </li>
                                <li>Pilih Metode Pembayaran:
                                    <ul>
                                        <li><strong>Potong Saldo Penjualan Toko:</strong> Jika toko Anda sudah memiliki akumulasi keuntungan dari penjualan produk, saldo iklan akan langsung bertambah seketika tanpa perlu transfer uang keluar!</li>
                                        <li><strong>Pembayaran Online / QRIS / Transfer:</strong> Anda akan diarahkan ke payment gateway instan untuk pembayaran via QRIS, Virtual Account, atau e-Wallet.</li>
                                    </ul>
                                </li>
                                <li>Selesaikan pembayaran. Saldo iklan Anda akan langsung bertambah dan seluruh iklan yang sempat dijeda akan otomatis berjalan kembali.</li>
                            </ol>
                        '
                    ],
                    [
                        'title' => 'Panduan Rumus Modal Iklan, Harga Bid Per Klik (CPC) & Sistem Pemotongan Saldo',
                        'content' => '
                            <h2>Kapan Saldo Iklan Dipotong? (Ketika Diklik atau Ketika Dibeli?)</h2>
                            <p>Banyak penjual menanyakan: <em>"Apakah saldo iklan dipotong saat produk saya diklik atau saat ada orang yang membeli?"</em></p>
                            <p><strong>Jawabannya:</strong> Saldo iklan dipotong <strong>ketika produk DIKLIK oleh calon pembeli</strong> (bukan saat dibeli).</p>
                            <p>Sistem ini merupakan standar resmi industri e-commerce global (seperti Shopee Ads, Tokopedia TopAds, dan Google Search Ads) yang dikenal dengan istilah <strong>CPC (Cost Per Click)</strong>.</p>

                            <h3>Tabel Perbandingan Aksi Pengunjung:</h3>
                            <ul>
                                <li><strong>Hanya Melihat (Impresi):</strong> 100% GRATIS. Produk Anda tampil di hasil pencarian dilihat ribuan orang tanpa biaya sepeser pun (Rp0).</li>
                                <li><strong>Mengklik Produk:</strong> YA, Saldo Dipotong. Calon pembeli tertarik dan membuka detail produk Anda. Saldo dipotong sesuai harga bid yang Anda tentukan (misal Rp500 / klik).</li>
                                <li><strong>Membeli Produk:</strong> TIDAK DIPOTONG LAGI. Uang hasil penjualan produk sepenuhnya masuk ke saldo penghasilan toko Anda tanpa ada potongan iklan ganda.</li>
                            </ul>

                            <h3>Mengapa Sistem Menggunakan Per-Klik (CPC), Bukan Per-Pembelian?</h3>
                            <p>Platform periklanan bertugas mengantarkan <strong>pengunjung potensial (traffic)</strong> yang sedang aktif mencari produk ke halaman tokomu. Sementara keputusan pembeli untuk bertransaksi dipengaruhi oleh daya tarik produk (kelengkapan source code, tangkapan layar/screenshot, link demo langsung, dan harga kompetitif).</p>
                            <p><em>Jika pemotongan biaya hanya terjadi saat produk terjual, sistem tersebut disebut sebagai <strong>Komisi Afiliasi (CPA)</strong>, bukan iklan pencarian bersponsor.</em></p>

                            <hr>

                            <h2>Rumus Harga Bid Per Klik & Modal Iklan</h2>

                            <h3>1. Harga Bid Per Klik (CPC)</h3>
                            <p>Besaran nominal saldo yang akan dipotong setiap kali ada 1 pengunjung unik mengklik produk beriklan Anda.</p>
                            <ul>
                                <li><strong>Mode Otomatis (Rekomendasi):</strong> Sistem menetapkan tarif default optimal yaitu <strong>Rp500 / klik</strong>.</li>
                                <li><strong>Mode Manual:</strong> Anda bebas mengatur besaran bid (minimal Rp100 / klik). Semakin tinggi bid, semakin besar prioritas produk Anda di posisi teratas.</li>
                            </ul>

                            <h3>2. Rumus Modal Iklan (Budget)</h3>
                            
                            <h4>A. Mode Modal Tak Terbatas (Unlimited)</h4>
                            <p>Iklan berjalan terus-menerus selama Saldo Iklan toko masih mencukupi.</p>
                            <p><strong>Rumus Estimasi Total Pengunjung (Klik):</strong><br>
                            <code>Total Pengunjung = Saldo Iklan / Harga Bid</code></p>
                            <p><em>Contoh:</em> Saldo Iklan Rp500.000, Harga Bid Rp500 / klik.<br>
                            <code>Rp500.000 / Rp500 = 1.000 Pengunjung</code> siap mendatangi produk Anda.</p>

                            <h4>B. Mode Modal Harian (Daily Budget)</h4>
                            <p>Digunakan jika Anda ingin membatasi pengeluaran per hari agar saldo iklan tidak langsung habis dalam satu hari.</p>
                            <p>Jika Anda mengiklankan <strong>lebih dari 1 produk sekaligus</strong>:</p>
                            <p><strong>Rumus Batas Modal Harian Total:</strong><br>
                            <code>Total Modal Harian = Modal Harian per Produk &times; Jumlah Produk yang Dipilih</code></p>
                            <p><strong>Rumus Estimasi Klik per Hari:</strong><br>
                            <code>Estimasi Klik per Hari = Total Modal Harian / Harga Bid</code></p>
                            <p><em>Contoh Kasus:</em> Anda memilih <strong>4 produk</strong>, modal harian <strong>Rp25.000/produk</strong>, bid <strong>Rp500/klik</strong>.<br>
                            - Kuota Pengeluaran per Hari = <code>Rp25.000 &times; 4 = Rp100.000 / hari</code><br>
                            - Potensi Trafik per Hari = <code>Rp100.000 / Rp500 = 200 Klik / hari</code></p>
                            <p>Jika kuota 200 klik tercapai pada hari itu, penayangan iklan otomatis istirahat sementara dan akan aktif kembali esok hari.</p>

                            <hr>

                            <h2>Perbedaan Bid Rp100, Rp500, vs Rp1.000 (Sistem Lelang / Ad Auction)</h2>
                            <p>Banyak penjual bertanya: <em>"Jika penjual bisa mengatur bid kustom misalnya Rp100 per klik, apakah tidak adil bagi yang pasang Rp500 atau Rp1.000?"</em></p>
                            <p>Justru di sinilah letak <strong>keadilan dan transparansi Sistem Lelang Iklan (Ad Auction)</strong>, yang berjalan persis seperti Shopee Ads, Tokopedia TopAds, dan Google Search Ads:</p>

                            <h3>1. Urutan Posisi Tampil di Hasil Pencarian (Ad Rank)</h3>
                            <p>Algoritma platform secara otomatis mengurutkan produk beriklan berdasarkan <strong>Nilai Bid Tertinggi</strong>:</p>
                            <ul>
                                <li><strong>🥇 Bid Tinggi (Rp1.000 / klik):</strong> Mendapatkan <strong>Posisi #1 Terdepan</strong> di baris pertama hasil pencarian marketplace. Sangat mencolok dan langsung dilihat calon pembeli pertama kali.</li>
                                <li><strong>🥈 Bid Rekomendasi (Rp500 / klik):</strong> Mendapatkan posisi strategis tepat di bawah bid tertinggi.</li>
                                <li><strong>🥉 Bid Minimum (Rp100 - Rp200 / klik):</strong> Tetap dipromosikan, tetapi harus rela <strong>mengantre di urutan bawah</strong> setelah kompetitor dengan bid lebih tinggi.</li>
                                <li><strong>⚪ Produk Non-Iklan:</strong> Tampil paling belakang setelah seluruh produk beriklan.</li>
                            </ul>

                            <h3>2. Peluang Menang Bersaing (Win Rate)</h3>
                            <p>Jika ada 2 atau lebih toko mengiklankan produk pada kata kunci yang sama (contoh: <em>"Aplikasi Kasir"</em>), toko dengan tawaran bid lebih tinggi yang akan memenangkan slot penayangan utama.</p>

                            <h3>3. Kecepatan Mendapatkan Pembeli</h3>
                            <ul>
                                <li><strong>Bid Lebih Tinggi:</strong> Pengunjung datang <strong>sangat cepat & banyak</strong> karena produk berada di etalase terdepan. Sangat cocok saat baru rilis produk atau mengejar penjualan cepat.</li>
                                <li><strong>Bid Lebih Kecil:</strong> Biaya saldo per klik sangat hemat, tetapi pengunjung datang <strong>lebih lambat</strong> dan harus sabar menunggu giliran tampil.</li>
                            </ul>

                            <hr>

                            <h2>Sistem Keamanan & Proteksi Saldo Penjual (Anti-Fraud)</h2>
                            <ol>
                                <li><strong>Klik Pemilik Toko 100% Gratis:</strong> Jika Anda mengklik produk Anda sendiri, sistem mendeteksi akun toko Anda dan saldo tidak akan pernah dipotong.</li>
                                <li><strong>Proteksi Anti-Spam (Session Deduplication):</strong> Klik berulang dari pengunjung yang sama dalam satu sesi browsing hanya dipotong 1 kali saja.</li>
                                <li><strong>Proteksi Saldo Minus:</strong> Jika saldo iklan tersisa Rp0, seluruh iklan otomatis dijeda (paused) sehingga saldo toko tidak akan pernah menjadi negatif / minus.</li>
                            </ol>
                        '
                    ]
                ]
            ],
            [
                'name' => 'Toko & Produk Digital',
                'icon' => 'storefront',
                'description' => 'Pelajari cara membuka toko, dekorasi profil, upload source code, dan pengaturan slug toko.',
                'sort_order' => 2,
                'articles' => [
                    [
                        'title' => 'Panduan Buka Toko Digital Gratis dalam 1 Menit',
                        'content' => '
                            <h2>Mulai Berjualan Produk Digital di Rhantech</h2>
                            <p>Membuka toko digital di platform Rhantech 100% <strong>GRATIS</strong> dan tanpa biaya pendaftaran sepeser pun. Siapapun Anda—programmer, UI/UX designer, content creator, atau tech enthusiast—bisa langsung menjual karyanya ke ribuan pengguna.</p>

                            <h3>Cara Buka Toko Melalui Dashboard:</h3>
                            <ol>
                                <li>Pastikan Anda sudah login ke akun Anda.</li>
                                <li>Kunjungi <code>/dashboard</code>. Anda akan disambut dengan banner penawaran khusus pembukaan toko baru.</li>
                                <li>Klik tombol <strong>Buka Toko & Klaim Rp500.000</strong>.</li>
                                <li>Sebuah jendela popup akan muncul. Masukkan <strong>Nama Toko Anda</strong> (contoh: <em>Karya Digital Tech</em>) dan deskripsi singkat tentang apa yang Anda jual.</li>
                                <li>Klik <strong>Aktifkan Toko & Dapatkan Saldo Rp500.000</strong>.</li>
                                <li>Toko Anda langsung aktif seketika! Anda akan diarahkan ke Seller Dashboard dan langsung menerima saldo iklan gratis Rp500.000.</li>
                            </ol>
                        '
                    ],
                    [
                        'title' => 'Panduan Kustomisasi Tampilan & Dekorasi Toko (Appearance)',
                        'content' => '
                            <h2>Tingkatkan Kepercayaan Pembeli dengan Tampilan Toko Profesional</h2>
                            <p>Toko yang memiliki identitas visual rapi dan jelas terbukti mendapatkan konversi penjualan 2,5 kali lebih tinggi. Rhantech menyediakan modul dekorasi toko yang sangat fleksibel.</p>

                            <h3>Elemen yang Dapat Anda Kustomisasi:</h3>
                            <ul>
                                <li><strong>Banner Toko:</strong> Pasang gambar banner berukuran 1200x400 pixel yang mencerminkan tema produk Anda (source code aplikasi, script otomasi, web template).</li>
                                <li><strong>Logo Toko:</strong> Unggah avatar atau logo brand Anda agar mudah dikenali pembeli di kartu produk.</li>
                                <li><strong>Tautan Media Sosial:</strong> Tambahkan tombol interaktif WhatsApp, Instagram, TikTok, dan YouTube toko Anda agar pembeli bisa terhubung langsung.</li>
                                <li><strong>Lokasi Google Maps:</strong> Cantumkan alamat atau titik lokasi maps untuk membangun kredibilitas profesional toko Anda.</li>
                                <li><strong>Widget Beranda Toko:</strong> Di menu <em>Dekorasi Toko</em>, Anda bisa mengatur susunan etalase: menampilkan blok Flash Sale diskon, voucher toko, hingga daftar produk terlaris.</li>
                            </ul>
                        '
                    ],
                    [
                        'title' => 'Pengaturan URL / Slug Toko Kustom (Personal Branding)',
                        'content' => '
                            <h2>Miliki Link Toko yang Elegan dan Mudah Diingat</h2>
                            <p>Setiap toko di Rhantech berhak memiliki alamat URL publik sendiri yang bersih, misalnya: <code>rhantech.com/gudang-source-code</code>.</p>

                            <h3>Cara Mengatur Slug Toko:</h3>
                            <ol>
                                <li>Masuk ke Dashboard Penjual, pilih menu <strong>Pengaturan Toko</strong> (<code>/dashboard/store</code>).</li>
                                <li>Pada kolom <strong>URL / Slug Toko</strong>, masukkan teks slug yang Anda inginkan (hanya huruf, angka, dan tanda strip).</li>
                                <li>Pastikan nama slug belum digunakan oleh toko lain.</li>
                                <li>Klik <strong>Simpan Perubahan</strong>.</li>
                                <li>Kini Anda dapat membagikan link toko resmi Anda ke media sosial, profil GitHub, atau bio LinkedIn!</li>
                            </ol>
                        '
                    ],
                    [
                        'title' => 'Panduan Unggah & Format File Produk Digital',
                        'content' => '
                            <h2>Ketentuan dan Panduan Mengunggah File Produk</h2>
                            <p>Sebagai platform jual beli produk digital, kami mengutamakan keamanan dan kepuasan pembeli saat menerima file yang dibeli.</p>

                            <h3>Ketentuan Format File:</h3>
                            <ul>
                                <li><strong>Format Arsip:</strong> Seluruh file source code, asset, database SQL, dan dokumentasi wajib dikompres menjadi satu file arsip berformat <strong>.ZIP</strong> atau <strong>.RAR</strong>.</li>
                                <li><strong>Dokumentasi Instalasi (README):</strong> Sangat disarankan menyertakan file <code>README.md</code> atau panduan PDF yang menjelaskan cara instalasi aplikasi, kebutuhan versi PHP/Node.js, dan langkah impor database.</li>
                                <li><strong>Keamanan Data:</strong> DILARANG KERAS menyertakan kredensial sensitif pribadi (seperti file <code>.env</code> dengan password database production, API key privat, dsb.). Gunakan file template seperti <code>.env.example</code>.</li>
                                <li><strong>Bebas Malware:</strong> Seluruh file yang diunggah akan dipindai secara otomatis. File yang mengandung backdoor, virus, atau script berbahaya akan otomatis dihapus dan toko dapat dibekukan.</li>
                            </ul>
                        '
                    ],
                    [
                        'title' => 'Fitur Diskon Coret & Brosur Produk Otomatis',
                        'content' => '
                            <h2>Strategi Menarik Pembeli dengan Diskon & Brosur</h2>
                            <p>Rhantech menyediakan dua fitur promosi cerdas yang terintegrasi di setiap produk:</p>

                            <h3>1. Fitur Diskon & Harga Coret</h3>
                            <p>Saat Anda mengunggah atau mengedit produk, Anda dapat mengisi <strong>Harga Normal</strong> dan <strong>Harga Diskon</strong>. Di halaman katalog, harga normal akan otomatis dicoret dan menampilkan badge persentase diskon (contoh: <em>-30%</em>). Trik psikologis ini terbukti ampuh mendorong calon pembeli segera bertransaksi.</p>

                            <h3>2. Fitur Unduh Brosur Produk Otomatis</h3>
                            <p>Platform secara otomatis membuatkan brosur profil produk resmi yang dapat diunduh oleh pembeli atau klien dalam format dokumen rapi. Brosur ini memuat ringkasan spesifikasi, lisensi penggunaan, harga, dan kontak toko Anda, sangat cocok untuk presentasi pengadaan software ke instansi/perusahaan.</p>
                        '
                    ]
                ]
            ],
            [
                'name' => 'Pesanan & Transaksi',
                'icon' => 'receipt_long',
                'description' => 'Informasi tentang cara membeli, metode pembayaran instan, dan cara download file produk.',
                'sort_order' => 3,
                'articles' => [
                    [
                        'title' => 'Panduan Membeli & Checkout Produk Digital',
                        'content' => '
                            <h2>Cara Berbelanja Produk Digital di Rhantech</h2>
                            <p>Membeli source code atau template digital di Rhantech sangat cepat dan instan dengan sistem pengiriman otomatis 24 jam nonstop.</p>

                            <h3>Langkah-langkah Pembelian:</h3>
                            <ol>
                                <li><strong>Pilih Produk:</strong> Cari produk yang Anda inginkan di halaman katalog atau toko penjual.</li>
                                <li><strong>Cek Spesifikasi & Demo:</strong> Baca deskripsi produk, ulasan pembeli, dan lihat preview gambar aplikasi.</li>
                                <li><strong>Klik Beli / Tambah ke Keranjang:</strong> Anda bisa langsung klik <em>Beli Sekarang</em> untuk langsung ke kasir atau masukkan ke keranjang belanja jika ingin membeli lebih dari satu item.</li>
                                <li><strong>Pilih Metode Pembayaran:</strong> Pilih metode pembayaran yang paling praktis untuk Anda (QRIS, GoPay, OVO, Dana, ShopeePay, atau Virtual Account Bank).</li>
                                <li><strong>Selesaikan Pembayaran:</strong> Begitu pembayaran terverifikasi oleh payment gateway (biasanya dalam hitungan detik), pesanan otomatis dinyatakan LUNAS.</li>
                            </ol>
                        '
                    ],
                    [
                        'title' => 'Cara Mengunduh File Source Code Setelah Pembayaran',
                        'content' => '
                            <h2>Akses File Download Tanpa Batas Waktu</h2>
                            <p>Setelah pembayaran Anda diverifikasi, file produk digital Anda dapat langsung diunduh secara instan.</p>

                            <h3>Di Mana Saya Bisa Mengunduh File?</h3>
                            <ol>
                                <li>Di halaman invoice setelah pembayaran berhasil, akan langsung muncul tombol hijau <strong>Download File Produk</strong>.</li>
                                <li>Anda juga dapat masuk ke menu profil Anda di pojok kanan atas &rarr; pilih <strong>Pembelian Saya</strong> (<code>/dashboard/purchases</code>).</li>
                                <li>Di sana tercatat seluruh riwayat produk digital yang pernah Anda beli. Klik tombol <strong>Download</strong> pada produk yang bersangkutan.</li>
                            </ol>

                            <h3>Apakah File Bisa Didownload Berulang Kali?</h3>
                            <p><strong>Ya, tentu saja!</strong> Pembelian di Rhantech memberikan Anda hak akses unduhan berulang seumur hidup. Jika komputer Anda mengalami kendala atau file Anda terhapus, Anda cukup login kembali ke akun Rhantech dan mendownload ulang file tersebut kapan saja.</p>
                        '
                    ],
                    [
                        'title' => 'Metode Pembayaran yang Tersedia',
                        'content' => '
                            <h2>Pilihan Metode Pembayaran Lengkap & Otomatis</h2>
                            <p>Untuk memudahkan transaksi dari seluruh wilayah Indonesia, Rhantech terhubung langsung dengan payment gateway berlisensi resmi:</p>
                            <ul>
                                <li><strong>QRIS (Semua Pembayaran QR):</strong> Scan menggunakan GoPay, OVO, DANA, LinkAja, ShopeePay, BCA Mobile, Livin by Mandiri, BRImo, atau aplikasi perbankan lainnya.</li>
                                <li><strong>Virtual Account Bank:</strong> BCA, Mandiri, BNI, BRI, Permata (verifikasi otomatis tanpa perlu upload bukti transfer).</li>
                                <li><strong>Potong Saldo Akun:</strong> Pengguna juga dapat menggunakan saldo dompet platform jika memiliki saldo yang cukup.</li>
                            </ul>
                        '
                    ]
                ]
            ],
            [
                'name' => 'Penarikan Dana (Payout)',
                'icon' => 'payments',
                'description' => 'Panduan lengkap cara, aturan, dan transparansi pencairan saldo penjualan toko ke rekening bank Anda.',
                'sort_order' => 4,
                'articles' => [
                    [
                        'title' => 'Panduan & Aturan Resmi Penarikan Dana (Payout) Hasil Penjualan Tenant',
                        'content' => '
                            <h2>Panduan Pencairan Saldo Penjualan Toko (Payout / Withdraw)</h2>
                            <p>Sebagai platform marketplace produk digital, <strong>Rhantech</strong> berkomitmen menyediakan ekosistem finansial yang aman, transparan, cepat, dan saling menguntungkan antara pemilik toko (tenant) dan platform. Setiap pendapatan dari penjualan produk digital Anda maupun komisi dari etalase afiliasi akan langsung terkumpul di <strong>Saldo Dompet Toko (Store Balance)</strong> dan siap dicairkan ke rekening bank pribadi Anda.</p>

                            <div class="my-5 p-4 rounded-xl bg-sky-50 border border-sky-200 text-sky-900 text-sm">
                                <strong>💡 Komitmen Kemitraan Rhantech:</strong><br>
                                Kami percaya bahwa kreator digital berhak menikmati hasil karya mereka secepat dan semudah mungkin. Oleh sebab itu, kami merancang aturan pencairan yang sangat bersahabat bagi kreator pemula maupun profesional.
                            </div>

                            <h3>3 Aturan Pokok Pencairan Dana Platform:</h3>
                            <table class="w-full border border-gray-200 text-sm my-4 rounded-lg overflow-hidden">
                                <thead class="bg-gray-50 border-b border-gray-200 text-gray-700">
                                    <tr>
                                        <th class="p-3 text-left font-bold w-1/3">Ketentuan</th>
                                        <th class="p-3 text-left font-bold">Rincian & Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr>
                                        <td class="p-3 font-semibold text-gray-800">1. Minimal Penarikan</td>
                                        <td class="p-3">
                                            <strong>Rp 10.000</strong><br>
                                            <span class="text-xs text-gray-600">Sangat ringan dan bersahabat bagi kreator pemula. Anda tidak perlu menunggu saldo menumpuk ratusan ribu untuk dapat mencairkan hasil penjualan pertama Anda.</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-3 font-semibold text-gray-800">2. Estimasi Waktu Cair</td>
                                        <td class="p-3">
                                            <strong>Maksimal 1x24 Jam (Hari Kerja)</strong><br>
                                            <span class="text-xs text-gray-600">Diproses pada hari kerja (Senin &ndash; Jumat). Pengajuan pada hari libur nasional atau akhir pekan (Sabtu &ndash; Minggu) akan diproses pada hari kerja berikutnya mengikuti jadwal kliring sistem perbankan nasional (BI-FAST / SKNBI).</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-3 font-semibold text-gray-800">3. Biaya Layanan Platform</td>
                                        <td class="p-3">
                                            <strong>2,5% per penarikan</strong><br>
                                            <span class="text-xs text-gray-600">Dipotong otomatis saat pencairan disetujui untuk biaya transfer switching antar-bank, infrastruktur server file download 24 jam, pemeliharaan sistem keamanan transaksi, serta pendampingan operasional toko.</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <h3>Mengapa Dikenakan Biaya Layanan 2,5%?</h3>
                            <p>Kami menerapkan prinsip <em>fair partnership</em> (kemitraan yang adil dan transparan). Biaya layanan platform sebesar <strong>2,5%</strong> dialokasikan untuk menjaga ekosistem bisnis toko Anda tetap andal:</p>
                            <ul>
                                <li><strong>Biaya Transfer & Switching Bank:</strong> Memastikan transfer dana ke rekening bank Anda (BCA, Mandiri, BRI, BNI, Jago, SeaBank, dll.) berjalan lancar tanpa potongan biaya flat bank yang mahal.</li>
                                <li><strong>Infrastruktur Server Download Berkecepatan Tinggi:</strong> Menyimpan file source code, aset digital, dan database produk Anda di cloud server berkecepatan tinggi agar pembeli dapat mengunduhnya secara instan 24 jam nonstop tanpa server down.</li>
                                <li><strong>Sistem Keamanan & Anti-Fraud:</strong> Melindungi akun, saldo toko, serta hak cipta karya digital Anda dari penyalahgunaan dan transaksi fiktif.</li>
                                <li><strong>Layanan Bantuan & Customer Support:</strong> Tim finance dan CS kami siap membantu jika ada kendala transaksi atau pencairan rekening Anda.</li>
                            </ul>

                            <h3>Simulasi Perhitungan Dana yang Diterima Tenant:</h3>
                            <p>Perhitungan dilakukan secara transparan tanpa ada biaya tersembunyi (<em>no hidden fees</em>):</p>
                            <table class="w-full border border-gray-200 text-sm my-3">
                                <thead class="bg-gray-50 border-b">
                                    <tr>
                                        <th class="p-2.5 border text-left font-bold">Nominal Penarikan</th>
                                        <th class="p-2.5 border text-center font-bold">Fee Platform (2,5%)</th>
                                        <th class="p-2.5 border text-right font-bold text-emerald-700">Dana Bersih Masuk Rekening</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="p-2.5 border font-mono">Rp 10.000</td>
                                        <td class="p-2.5 border text-center font-mono text-gray-600">Rp 250</td>
                                        <td class="p-2.5 border text-right font-mono font-bold text-emerald-600">Rp 9.750</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2.5 border font-mono">Rp 50.000</td>
                                        <td class="p-2.5 border text-center font-mono text-gray-600">Rp 1.250</td>
                                        <td class="p-2.5 border text-right font-mono font-bold text-emerald-600">Rp 48.750</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2.5 border font-mono">Rp 100.000</td>
                                        <td class="p-2.5 border text-center font-mono text-gray-600">Rp 2.500</td>
                                        <td class="p-2.5 border text-right font-mono font-bold text-emerald-600">Rp 97.500</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2.5 border font-mono">Rp 500.000</td>
                                        <td class="p-2.5 border text-center font-mono text-gray-600">Rp 12.500</td>
                                        <td class="p-2.5 border text-right font-mono font-bold text-emerald-600">Rp 487.500</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2.5 border font-mono">Rp 1.000.000</td>
                                        <td class="p-2.5 border text-center font-mono text-gray-600">Rp 25.000</td>
                                        <td class="p-2.5 border text-right font-mono font-bold text-emerald-600">Rp 975.000</td>
                                    </tr>
                                </tbody>
                            </table>

                            <h3>Langkah demi Langkah Mengajukan Penarikan Dana:</h3>
                            <ol>
                                <li><strong>Pastikan Rekening Bank Terdaftar:</strong> Masuk ke Dashboard Toko &rarr; Pengaturan Toko &rarr; pastikan informasi nama bank, nomor rekening, dan nama pemilik rekening sudah terisi dengan benar.</li>
                                <li><strong>Buka Menu Keuangan:</strong> Akses menu <strong>Pusat Keuangan & Penghasilan</strong> (<code>/tenant/payouts</code>).</li>
                                <li><strong>Cek Saldo Siap Ditarik:</strong> Pastikan saldo Anda mencukupi (minimal Rp 10.000).</li>
                                <li><strong>Masukkan Nominal:</strong> Pada kartu Saldo, ketik jumlah saldo yang ingin dicairkan pada kolom nominal.</li>
                                <li><strong>Klik "Ajukan Penarikan":</strong> Sistem akan memotong saldo toko Anda seketika dan mencatat riwayat penarikan berstatus <code>Pending</code>.</li>
                                <li><strong>Verifikasi & Transfer oleh Tim Platform:</strong> Tim finance platform akan memproses transfer ke rekening bank Anda dalam tempo maksimal 1x24 jam kerja. Setelah transfer berhasil dikirim, status berubah menjadi <code>Approved / Selesai</code>.</li>
                            </ol>

                            <h3>Hal-Hal Penting yang Perlu Diperhatikan:</h3>
                            <ul>
                                <li><strong>Kesesuaian Nama Rekening:</strong> Pastikan nama pada buku tabungan/rekening sesuai dengan nama pemilik toko untuk mencegah penolakan transfer oleh sistem bank.</li>
                                <li><strong>Bank yang Didukung:</strong> Kami mendukung seluruh bank nasional di Indonesia (BCA, Mandiri, BRI, BNI, CIMB Niaga, Permata, Danamon, Bank Jago, SeaBank, dll.).</li>
                                <li><strong>Bantuan Pencairan:</strong> Jika penarikan belum masuk setelah 1x24 jam hari kerja, silakan hubungi tim CS kami melalui Live Chat Toko dengan melampirkan nomor ID penarikan Anda.</li>
                            </ul>
                        '
                    ],
                    [
                        'title' => 'Ketentuan Minimal Penarikan (Rp10.000) & Biaya Layanan Platform (2,5%)',
                        'content' => '
                            <h2>Transparansi Batas Minimal & Biaya Penarikan Dana</h2>
                            <p>Kami di Rhantech berkomitmen menerapkan keterbukaan informasi finansial tanpa ada biaya siluman atau potongan tersembunyi.</p>

                            <h3>1. Mengapa Minimal Penarikan Hanya Rp 10.000?</h3>
                            <p>Banyak platform lain mewajibkan saldo mencapai Rp 50.000 atau bahkan Rp 100.000 sebelum bisa dicairkan. Di Rhantech, kami menetapkan batas <strong>Rp 10.000</strong> agar kreator yang baru memulai dan baru mendapatkan 1 penjualan pertama pun bisa langsung menikmati hasil keringatnya tanpa harus menunggu lama.</p>

                            <h3>2. Rincian Biaya Layanan Platform 2,5%</h3>
                            <p>Setiap penarikan dana dikenakan biaya layanan sebesar <strong>2,5%</strong> dari total nominal yang Anda tarik.</p>
                            <ul>
                                <li><strong>Rumus Perhitungan:</strong><br>
                                    <code>Dana Diterima = Nominal Penarikan - (Nominal Penarikan &times; 2,5%)</code>
                                </li>
                                <li><strong>Contoh:</strong> Jika Anda menarik Rp 100.000, biaya platform adalah Rp 2.500, dan dana bersih yang kami transfer ke rekening Anda adalah <strong>Rp 97.500</strong>.</li>
                                <li><strong>Tidak Ada Potongan Flat Tambahan:</strong> Anda tidak akan dikenakan biaya transfer antarbank tambahan di luar persentase 2,5% ini.</li>
                            </ul>

                            <h3>Ke Mana Alokasi Biaya 2,5% Tersebut?</h3>
                            <p>Biaya ini digunakan sepenuhnya untuk menopang kelangsungan operasional toko Anda:</p>
                            <ol>
                                <li>Biaya switching transfer kliring antar-bank mitra platform.</li>
                                <li>Biaya penyimpanan cloud storage dan bandwidth unduhan produk pembeli 24 jam nonstop.</li>
                                <li>Pemeliharaan firewall dan enkripsi data transaksi digital.</li>
                            </ol>
                        '
                    ],
                    [
                        'title' => 'Jadwal & Waktu Pemrosesan Pencairan Dana (Maksimal 1x24 Jam Kerja)',
                        'content' => '
                            <h2>Kapan Dana Penarikan Masuk ke Rekening Saya?</h2>
                            <p>Seluruh pengajuan penarikan dana (withdraw) oleh tenant diproses dengan Service Level Agreement (SLA) <strong>maksimal 1x24 jam pada Hari Kerja</strong>.</p>

                            <h3>Jadwal Operasional Tim Finance:</h3>
                            <ul>
                                <li><strong>Hari Kerja:</strong> Senin &ndash; Jumat (Pukul 09.00 &ndash; 17.00 WIB).</li>
                                <li><strong>Akhir Pekan & Hari Libur:</strong> Sabtu, Minggu, dan Libur Nasional perbankan libur operasional.</li>
                            </ul>

                            <h3>Simulasi Jadwal Pencairan:</h3>
                            <table class="w-full border border-gray-200 text-sm my-3">
                                <thead class="bg-gray-50 border-b">
                                    <tr>
                                        <th class="p-2.5 border text-left font-bold">Waktu Anda Mengajukan</th>
                                        <th class="p-2.5 border text-left font-bold">Estimasi Dana Cair ke Rekening</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="p-2.5 border">Senin pukul 10.00 WIB</td>
                                        <td class="p-2.5 border text-emerald-700 font-semibold">Senin siang atau paling lambat Selasa pukul 10.00 WIB</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2.5 border">Jumat pukul 14.00 WIB</td>
                                        <td class="p-2.5 border text-emerald-700 font-semibold">Jumat sore atau paling lambat Senin berikutnya pukul 14.00 WIB</td>
                                    </tr>
                                    <tr>
                                        <td class="p-2.5 border">Sabtu atau Minggu</td>
                                        <td class="p-2.5 border text-emerald-700 font-semibold">Diproses pada hari Senin (paling lambat Selasa)</td>
                                    </tr>
                                </tbody>
                            </table>

                            <blockquote>
                                <strong>Catatan:</strong> Kecepatan dana masuk juga dipengaruhi oleh jenis rekening bank tujuan Anda. Untuk bank-bank besar yang terhubung jaringan BI-FAST (BCA, Mandiri, BRI, BNI, Permata, Jago, dll.), dana umumnya masuk dalam hitungan menit setelah status penarikan di-approve oleh finance.
                            </blockquote>
                        '
                    ],
                    [
                        'title' => 'Cara Mendaftarkan & Mengubah Rekening Bank Toko',
                        'content' => '
                            <h2>Pengaturan Rekening Bank Pencairan</h2>
                            <p>Demi keamanan dana dan kepatuhan finansial, pastikan nama pemilik rekening bank yang Anda daftarkan sesuai dengan identitas pemilik toko.</p>

                            <h3>Cara Input Rekening:</h3>
                            <ol>
                                <li>Masuk ke Dashboard Penjual &rarr; menu <strong>Pengaturan Toko</strong> atau klik tautan <em>Atur Rekening Bank</em> di halaman Keuangan (<code>/tenant/payouts</code>).</li>
                                <li>Pilih atau ketik nama bank Anda (BCA, Mandiri, BRI, BNI, CIMB Niaga, Permata, Jago, SeaBank, dll.).</li>
                                <li>Masukkan nomor rekening dan nama pemilik rekening secara akurat sesuai buku tabungan.</li>
                                <li>Simpan perubahan. Rekening ini akan menjadi rekening tujuan otomatis untuk seluruh permintaan payout Anda.</li>
                            </ol>

                            <h3>Tips Keamanan Rekening:</h3>
                            <ul>
                                <li>Jangan pernah membagikan kata sandi akun atau kode OTP kepada siapapun, termasuk pihak yang mengatasnamakan Rhantech.</li>
                                <li>Jika ingin mengubah nomor rekening tujuan, pastikan Anda melakukannya sebelum mengajukan penarikan baru.</li>
                            </ul>
                        '
                    ]
                ]
            ],
            [
                'name' => 'Program Afiliasi',
                'icon' => 'handshake',
                'description' => 'Panduan lengkap cara kerja sistem mitra afiliasi toko, pembagian komisi otomatis, link referral, etalase showcase, dan pencairan saldo.',
                'sort_order' => 5,
                'articles' => [
                    [
                        'title' => 'Panduan Lengkap Cara Kerja Program Afiliasi Toko (Mitra Toko vs Etalase Afiliasi)',
                        'content' => '
                            <h2>Mengenal Ekosistem Program Afiliasi di Rhantech</h2>
                            <p>Program Afiliasi adalah fitur kolaborasi penjualan modern di mana pemilik toko (merchant) dan mitra promotor (affiliate) saling menguntungkan tanpa modal berisiko:</p>
                            <ul>
                                <li><strong>Bagi Pemilik Toko (Merchant):</strong> Anda dapat merekrut kreator, influencer, atau teman untuk membantu mempromosikan produk toko Anda. Anda <strong>hanya membayar komisi jika terjadi penjualan nyata (CPA - Cost Per Action)</strong>, sehingga 100% bebas risiko boncos dibandingkan iklan berbayar.</li>
                                <li><strong>Bagi Mitra Afiliasi (Promotor):</strong> Anda bisa menghasilkan pendapatan pasif dengan membagikan link referral toko atau memajang produk orang lain di etalase toko Anda tanpa perlu repot membuat source code sendiri.</li>
                            </ul>

                            <hr>

                            <h2>Perbedaan Dua Fitur Utama Afiliasi di Dashboard</h2>
                            <table class="w-full border border-gray-200 text-sm my-4">
                                <thead class="bg-gray-50 border-b">
                                    <tr>
                                        <th class="p-3 border font-bold text-left">Fitur Dashboard</th>
                                        <th class="p-3 border font-bold text-left">Tujuan & Fungsi</th>
                                        <th class="p-3 border font-bold text-left">Siapa yang Diuntungkan?</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="p-3 border font-semibold text-sky-600">1. Mitra Toko Saya (<code>/tenant/affiliates</code>)</td>
                                        <td class="p-3 border">Halaman tempat Anda mengelola orang-orang yang menjadi <strong>tim promotor untuk produk toko Anda sendiri</strong>. Di sini Anda menentukan persentase bagi hasil dan memberikan link referral unik kepada mereka.</td>
                                        <td class="p-3 border"><strong>Toko Anda</strong> mendapat lonjakan pembeli baru, dan <strong>Mitra Anda</strong> mendapatkan persenan komisi.</td>
                                    </tr>
                                    <tr>
                                        <td class="p-3 border font-semibold text-emerald-600">2. Etalase Afiliasi / Showcase (<code>/tenant/showcase</code>)</td>
                                        <td class="p-3 border">Halaman tempat Anda memilih produk digital milik platform atau toko tenant lain untuk <strong>dipajang langsung di etalase toko Anda sendiri</strong>.</td>
                                        <td class="p-3 border"><strong>Toko Anda</strong> bisa berjualan puluhan produk langsung dan mendapatkan komisi tanpa perlu coding atau membuat produk dari nol!</td>
                                    </tr>
                                </tbody>
                            </table>

                            <hr>

                            <h2>Perbedaan Program Afiliasi vs Iklan Berbayar (Rhantech Ads)</h2>
                            <ul>
                                <li><strong>Iklan Berbayar (CPC):</strong> Saldo toko Anda dipotong setiap kali produk diklik pembeli di hasil pencarian marketplace. Sangat efektif untuk mendongkrak trafik secara instan dalam hitungan jam.</li>
                                <li><strong>Program Afiliasi (CPA):</strong> 100% GRATIS di awal. Saldo toko Anda <strong>HANYA dipotong setelah pembeli selesai membayar pesanan</strong>. Sangat aman untuk strategi pemasaran jangka panjang berkelanjutan.</li>
                            </ul>
                        '
                    ],
                    [
                        'title' => 'Panduan Cara Merekrut Mitra Afiliasi & Pengaturan Bagi Hasil Komisi',
                        'content' => '
                            <h2>Langkah Merekrut Mitra Promotor Baru untuk Toko Anda</h2>
                            <p>Untuk menghubungkan akun pengguna lain sebagai affiliate resmi toko Anda, ikuti langkah-langkah berikut:</p>

                            <h3>Langkah 1: Buka Menu Mitra Afiliasi</h3>
                            <p>Masuk ke Dashboard Seller &rarr; klik menu <strong>Program Affiliate</strong> (<code>/tenant/affiliates</code>) di sidebar &rarr; lalu klik tombol <strong>+ Tambah Mitra Affiliate</strong>.</p>

                            <h3>Langkah 2: Pilih Akun Pengguna Terdaftar</h3>
                            <p>Sistem akan menampilkan daftar seluruh akun yang sudah terdaftar di platform Rhantech (baik sesama pemilik toko maupun pengguna pembeli umum). Pilih akun mitra yang ingin Anda ajak bekerjasama.</p>

                            <h3>Langkah 3: Tentukan Persentase Bagi Hasil Komisi</h3>
                            <p>Masukkan persentase komisi yang akan diberikan kepada mitra tersebut dari setiap transaksi berhasil:</p>
                            <ul>
                                <li><strong>Standar Industri:</strong> Umumnya <strong>10% s/d 20%</strong> dari harga produk.</li>
                                <li><strong>Promo Peluncuran:</strong> Anda bisa memberikan <strong>25% s/d 30%</strong> untuk memotivasi influencer atau affiliate top agar gencar membagikan link toko Anda.</li>
                            </ul>

                            <h3>Langkah 4: Pembuatan Kode & Link Referral Otomatis</h3>
                            <p>Setelah Anda mengklik <strong>Hubungkan Akun Mitra</strong>, sistem otomatis:</p>
                            <ol>
                                <li>Membuatkan <strong>Kode Referral Unik</strong> berdasarkan nama akun mitra (misal: <code>RICO2026</code>).</li>
                                <li>Membuatkan <strong>Tautan Referral Toko Lengkap</strong> (contoh: <code>https://domain.com/store/namatoko?ref=RICO2026</code>).</li>
                            </ol>

                            <h3>Langkah 5: Bagikan Link ke Mitra</h3>
                            <p>Pada tabel daftar mitra, Anda dapat langsung mengklik ikon <strong>Salin Link</strong> atau mengklik ikon <strong>WhatsApp</strong> untuk mengirimkan link referral langsung ke nomor WA mitra Anda.</p>
                        '
                    ],
                    [
                        'title' => 'Panduan Teknis: Pelacakan Klik, Otomatisasi Saldo Komisi & Penarikan Dana Mitra',
                        'content' => '
                            <h2>Bagaimana Sistem Menghitung dan Mentransfer Komisi?</h2>
                            <p>Sistem afiliasi Rhantech bekerja secara 100% otomatis tanpa perlu verifikasi manual dari pemilik toko:</p>

                            <h3>1. Pelacakan Kunjungan (Tracking Klik)</h3>
                            <p>Saat calon pembeli membuka tautan referral mitra (<code>/store/namatoko?ref=KODE</code>):</p>
                            <ol>
                                <li>Sistem secara otomatis mendeteksi kode referral valid.</li>
                                <li>Counter <strong>Jumlah Klik</strong> pada mitra tersebut langsung bertambah +1.</li>
                                <li>Sistem mengikat sesi penjelajahan calon pembeli dengan ID mitra referral tersebut.</li>
                            </ol>

                            <h3>2. Perhitungan Komisi Saat Pembelian</h3>
                            <p>Ketika pembeli melakukan checkout produk digital dari toko Anda:</p>
                            <ul>
                                <li>Sistem mengkalkulasi nominal komisi: <code>Komisi = Harga Produk &times; (Persentase / 100)</code>.</li>
                                <li>Contoh: Produk seharga Rp200.000 dengan komisi 15% menghasilkan komisi mitra sebesar <strong>Rp30.000</strong>.</li>
                                <li>Data pesanan menyimpan rincian <code>affiliate_id</code> dan nilai komisi secara transparan.</li>
                            </ul>

                            <h3>3. Otomatisasi Masuknya Uang ke Saldo Mitra</h3>
                            <p>Seketika saat pembayaran pesanan terkonfirmasi (baik melalui payment gateway otomatis seperti QRIS, Virtual Account, e-Wallet, maupun persetujuan manual admin):</p>
                            <ol>
                                <li><strong>Saldo Toko Mitra Bertambah:</strong> Dana komisi (contoh: Rp30.000) langsung ditambahkan ke <strong>Saldo Penghasilan Toko</strong> milik mitra yang bersangkutan (<code>balance + komisi</code>).</li>
                                <li><strong>Statistik Penjualan Terupdate:</strong> Counter <strong>Pesanan Sukses</strong> pada tabel afiliasi bertambah +1 dan kolom <strong>Rentang Penjualan (Sales Range)</strong> diperbarui secara kumulatif.</li>
                            </ol>

                            <hr>

                            <h2>Cara Mitra Mencairkan Uang Komisi (Withdraw / Payout)</h2>
                            <p>Uang komisi yang masuk ke akun mitra adalah <strong>Uang Riil (Bukan Saldo Iklan)</strong> dan dapat ditarik ke rekening bank pribadi kapan saja:</p>
                            <ol>
                                <li>Mitra masuk ke menu <strong>Pusat Keuangan & Penghasilan</strong> (<code>/tenant/payouts</code>).</li>
                                <li>Mitra dapat melihat total saldo penghasilan yang siap ditarik.</li>
                                <li>Ketik nominal penarikan (<strong>Minimal Penarikan Hanya Rp 10.000</strong>).</li>
                                <li>Pilih rekening bank tujuan (BCA, Mandiri, BRI, BNI, CIMB, Seabank, Bank Jago, dll.).</li>
                                <li>Klik <strong>Ajukan Penarikan</strong>. Dana akan ditransfer oleh tim finance platform dalam waktu maksimal 1x24 jam kerja!</li>
                            </ol>

                            <blockquote>
                                <strong>Keamanan Anti-Fraud Transaksi Afiliasi:</strong><br>
                                Sistem memproteksi pemilik toko dari kecurangan; jika pembeli yang mengklik link adalah pemilik toko itu sendiri, komisi tidak akan dihitung ganda untuk mencegah self-referral abuse.
                            </blockquote>
                        '
                    ],
                    [
                        'title' => 'Panduan Memasang Produk Toko Lain di Etalase Toko Saya (Showcase)',
                        'content' => '
                            <h2>Jualan Puluhan Produk Digital Tanpa Perlu Bikin Sendiri</h2>
                            <p>Fitur <strong>Etalase Afiliasi (Showcase)</strong> di <code>/tenant/showcase</code> memungkinkan Anda melengkapi toko Anda dengan ratusan produk digital siap jual dari platform official atau toko tenant lain.</p>

                            <h3>Cara Menambahkan Produk ke Etalase Toko:</h3>
                            <ol>
                                <li>Buka menu <strong>Etalase Afiliasi</strong> di dashboard toko Anda.</li>
                                <li>Gunakan tab filter untuk melihat <em>Semua Produk</em>, <em>Produk Official Platform</em>, atau <em>Produk Toko Tenant Lain</em>.</li>
                                <li>Pilih produk yang sesuai dengan niche toko Anda (misal: Source Code Web, Template Canva, Plugin WordPress, atau Desain UI/UX).</li>
                                <li>Klik tombol <strong>+ Pasang di Etalase Toko</strong>. Produk tersebut akan langsung muncul di katalog etalase toko online Anda (<code>/store/{slug-toko-anda}</code>).</li>
                            </ol>

                            <h3>Bagaimana Cara Kerjanya Saat Ada Pembeli?</h3>
                            <ol>
                                <li>Pengunjung membuka toko Anda dan membeli produk afiliasi yang Anda pasang.</li>
                                <li>Pengiriman file produk digital ditangani secara otomatis oleh platform dan pemilik produk asli.</li>
                                <li>Anda tidak perlu pusing mengurus upload file atau update source code; Anda cukup mempromosikan toko Anda dan menikmati bagi hasil komisi penjualan yang otomatis masuk ke saldo rekening Anda!</li>
                            </ol>
                        '
                    ]
                ]
            ],
            [
                'name' => 'Live Chat & Komunikasi',
                'icon' => 'forum',
                'description' => 'Panduan menggunakan fitur chat real-time antara penjual dan pembeli.',
                'sort_order' => 6,
                'articles' => [
                    [
                        'title' => 'Panduan Menggunakan Live Chat Toko dengan Pembeli Real-Time',
                        'content' => '
                            <h2>Komunikasi Langsung Mempercepat Transaksi</h2>
                            <p>Calon pembeli produk digital seringkali ingin bertanya tentang teknologi yang digunakan, kecocokan hosting, atau meminta bantuan instalasi sebelum membeli. Fitur <strong>Live Chat Seller Center</strong> hadir untuk memfasilitasi komunikasi tersebut secara real-time.</p>

                            <h3>Fitur-Fitur Live Chat:</h3>
                            <ul>
                                <li><strong>Chat dari Halaman Produk:</strong> Pembeli dapat menekan tombol <em>Chat Penjual</em> di halaman detail produk untuk langsung memulai obrolan terkait produk spesifik tersebut.</li>
                                <li><strong>Notifikasi Pesan Masuk:</strong> Penjual akan menerima notifikasi badge dan tanda suara saat ada pesan baru masuk.</li>
                                <li><strong>Kirim Rekomendasi Produk:</strong> Penjual dapat merekomendasikan produk lain langsung di dalam kolom obrolan.</li>
                            </ul>

                            <h3>Aturan Keamanan Berkomunikasi:</h3>
                            <p>Demi melindungi keamanan kedua belah pihak dari penipuan, dilarang keras membagikan kontak pribadi di luar platform atau melakukan pembayaran di luar sistem resmi Rhantech.</p>
                        '
                    ]
                ]
            ],
            [
                'name' => 'Akun & Keamanan',
                'icon' => 'manage_accounts',
                'description' => 'Panduan mengelola profil, login Google, verifikasi email, dan keamanan akun Anda.',
                'sort_order' => 7,
                'articles' => [
                    [
                        'title' => 'Cara Membuat Akun Baru & Verifikasi Email',
                        'content' => '
                            <h2>Panduan Pendaftaran Akun Pengguna & Seller</h2>
                            <p>Pendaftaran akun di Rhantech dapat dilakukan dengan dua cara mudah:</p>
                            <ol>
                                <li><strong>Daftar dengan Google One-Tap:</strong> Cukup klik tombol <em>Lanjutkan dengan Google</em> di halaman Login/Register untuk proses instan dalam 1 detik tanpa perlu mengetik kata sandi manual.</li>
                                <li><strong>Daftar dengan Email:</strong> Masukkan nama lengkap, alamat email aktif, dan password minimal 8 karakter. Setelah mendaftar, buka inbox email Anda dan klik tautan verifikasi akun yang dikirimkan.</li>
                            </ol>
                        '
                    ],
                    [
                        'title' => 'Panduan Keamanan Akun & Solusi Lupa Password',
                        'content' => '
                            <h2>Mengamankan Akun Anda</h2>
                            <p>Jika Anda lupa kata sandi akun Anda, klik tautan <strong>Lupa Password?</strong> di halaman login. Masukkan email Anda dan sistem akan mengirimkan tautan reset kata sandi baru ke inbox email Anda.</p>
                            <p>Pastikan Anda selalu menggunakan kata sandi yang kuat dan tidak membagikan kredensial login Anda kepada siapapun.</p>
                        '
                    ]
                ]
            ],
            [
                'name' => 'Kebijakan, Hak Cipta & Regulasi RI',
                'icon' => 'gavel',
                'description' => 'Ketentuan hukum, perlindungan HAKI (UU 28/2014), UU PDP, izin PSE Komdigi/Kominfo, SIUPMSE, dan perpajakan Indonesia.',
                'sort_order' => 8,
                'articles' => [
                    [
                        'title' => 'Dasar Hukum & Kepatuhan Regulasi Platform Rhantech di Indonesia',
                        'content' => '
                            <h2>Landasan Hukum Penyelenggaraan Sistem Elektronik</h2>
                            <p>Platform <strong>Rhantech</strong> diselenggarakan sesuai dengan ketentuan peraturan perundang-undangan Negara Kesatuan Republik Indonesia:</p>
                            <ul>
                                <li><strong>UU ITE No. 1 Tahun 2024</strong> (Perubahan Kedua UU No. 11/2008 tentang Informasi dan Transaksi Elektronik): Menjamin keabsahan transaksi elektronik dan kontrak elektronik antara pembeli, penjual, dan platform.</li>
                                <li><strong>PP No. 80 Tahun 2019</strong> & <strong>Permendag No. 31 Tahun 2023</strong> tentang Perdagangan Melalui Sistem Elektronik (PMSE): Mengatur tata kelola marketplace, perlindungan konsumen, verifikasi identitas pedagang (merchant), dan kepatuhan perizinan.</li>
                                <li><strong>UU Perlindungan Data Pribadi (UU No. 27 Tahun 2022)</strong>: Menjamin keamanan pemrosesan data pribadi, persetujuan eksplisit, serta hak pengguna atas kerahasiaan informasinya.</li>
                                <li><strong>UU Hak Cipta (UU No. 28 Tahun 2014)</strong>: Melindungi karya cipta source code, software, dan karya digital dari pembajakan dan penyalahgunaan.</li>
                            </ul>
                            <h3>Status Pendaftaran PSE (Penyelenggara Sistem Elektronik)</h3>
                            <p>Sebagai platform PMSE Lingkup Privat, Rhantech berkomitmen memenuhi kewajiban pendaftaran tanda daftar PSE pada Kementerian Komunikasi dan Digital RI (Komdigi/Kominfo) dan memenuhi standar keamanan informasi.</p>
                        '
                    ],
                    [
                        'title' => 'Kebijakan Hak Cipta, Lisensi & Larangan Script Bajakan (Nulled)',
                        'content' => '
                            <h2>Perlindungan Kekayaan Intelektual Kreator & Developer</h2>
                            <p>Rhantech menerapkan kebijakan <strong>Zero Tolerance</strong> terhadap pembajakan digital:</p>
                            <ol>
                                <li><strong>HAKI Tetap Milik Kreator:</strong> Seluruh Hak Cipta atas script, template, atau software tetap menjadi milik pembuatnya (UU Hak Cipta No. 28/2014). Pembelian di platform memberikan Hak Pakai Lisensi (License to Use), bukan pengalihan hak cipta secara mutlak.</li>
                                <li><strong>Larangan Keras Script Nulled / Cracked:</strong> Dilarang keras mengunggah script hasil bajakan, modul curian, atau produk GPL pihak ketiga yang telah disusupi backdoor/malware.</li>
                                <li><strong>Sanksi Pelanggaran:</strong> Akun toko yang terbukti menjual produk bajakan akan langsung <strong>diblokir permanen</strong>, saldo penjualan disita untuk ganti rugi pembeli, dan data identitas dapat diteruskan kepada aparat penegak hukum atas tuntutan pemilik hak cipta yang sah.</li>
                            </ol>
                            <h3>Batas Penggunaan Lisensi</h3>
                            <p>Setiap produk memiliki lisensi standar (Single Use untuk 1 proyek) atau lisensi Extended (Komersial). Pengguna dilarang membagikan ulang secara gratis (redistribusi terbuka) atau menjual ulang tanpa hak Resale/PLR yang sah.</p>
                        '
                    ],
                    [
                        'title' => 'Prosedur Pelaporan Pelanggaran Hak Cipta (Notice and Takedown)',
                        'content' => '
                            <h2>Mekanisme Perlindungan Safe Harbor (SE Menkominfo No. 5/2016)</h2>
                            <p>Apabila Anda adalah pemilik hak cipta resmi yang mendapati karya cipta Anda diunggah atau dijual tanpa izin oleh toko lain di Rhantech, Anda berhak mengajukan permohonan <em>Notice and Takedown</em>.</p>
                            <h3>Syarat Dokumen Laporan:</h3>
                            <ul>
                                <li>Nama lengkap & identitas pelapor (KTP/Paspor) atau Surat Kuasa resmi dari pemegang hak cipta.</li>
                                <li>Bukti kepemilikan ciptaan sah (sertifikat pencatatan ciptaan Kemenkumham, link repositori asli resmi, atau bukti rilis awal bertanggal).</li>
                                <li>Tautan (URL) produk di Rhantech yang diduga melanggar hak cipta Anda.</li>
                                <li>Pernyataan sumpah tertulis bahwa Anda bertindak dengan itikad baik dan data yang diberikan benar adanya.</li>
                            </ul>
                            <h3>Alur Penanganan Laporan:</h3>
                            <p>Kirimkan berkas ke email: <strong>legal@rhantech.id</strong> atau <strong>copyright@rhantech.id</strong>. Tim Moderasi akan melakukan suspensi sementara terhadap produk terlapor dalam waktu maksimal 1x24 jam kerja untuk proses verifikasi dan mediasi penjual.</p>
                        '
                    ],
                    [
                        'title' => 'Syarat Legalitas & Perizinan Berjualan bagi Tenant (NIB & KBLI)',
                        'content' => '
                            <h2>Apa yang Perlu Disiapkan untuk Bisnis Platform & Tenant di Indonesia?</h2>
                            <p>Bagi pemilik platform dan para pengembang yang ingin menjalankan usaha digital secara legal dan berskala nasional di Indonesia, berikut dokumen yang harus disiapkan:</p>
                            <ol>
                                <li><strong>Badan Hukum / Usaha:</strong> Direkomendasikan mendirikan PT (Perseroan Terbatas) atau PT Perorangan untuk kemudahan membuka rekening bank institusi, integrasi payment gateway, dan perizinan.</li>
                                <li><strong>Nomor Induk Berusaha (NIB) OSS-RBA:</strong> Diterbitkan via portal OSS Kementerian Investasi/BKPM dengan pilihan KBLI relevan:
                                    <ul>
                                        <li><em>KBLI 63122:</em> Portal Web dan/atau Platform Digital dengan Tujuan Komersial (khusus marketplace/platform digital).</li>
                                        <li><em>KBLI 62019:</em> Aktivitas Pemrograman dan Pengembangan Aplikasi Komputer Lainnya.</li>
                                        <li><em>KBLI 47912:</em> Perdagangan Eceran Barang Digital Melalui Media Internet.</li>
                                    </ul>
                                </li>
                                <li><strong>Tanda Daftar PSE Komdigi/Kominfo:</strong> Wajib didaftarkan melalui oss.go.id & layananpse.komdigi.go.id sebagai PSE Lingkup Privat.</li>
                                <li><strong>Izin Usaha PMSE (SIUPMSE):</strong> Untuk penyelenggara marketplace yang memfasilitasi transaksi pihak ketiga secara komersial (Permendag 31/2023).</li>
                                <li><strong>NPWP Badan & Kepatuhan Pajak:</strong> NPWP aktif untuk pelaporan SPT Tahunan, pemotongan PPh Pasal 23/21 atas komisi tenant, dan pendaftaran pemungut PPN PMSE (11%).</li>
                            </ol>
                        '
                    ],
                    [
                        'title' => 'Kebijakan Pengembalian Dana (Refund) & Perlindungan Konsumen',
                        'content' => '
                            <h2>Kebijakan Garansi Produk Digital</h2>
                            <p>Mengingat produk digital (source code, e-book, lisensi software) dapat diakses dan diunduh seketika setelah pembayaran berhasil, seluruh transaksi pada dasarnya bersifat final (<em>Non-Refundable</em>).</p>
                            <h3>Pengecualian yang Berhak Menerima Refund:</h3>
                            <ul>
                                <li><strong>File Corrupt / Rusak:</strong> Berkas unduhan rusak dan penjual gagal menyediakan tautan alternatif pengganti dalam waktu 2x24 jam.</li>
                                <li><strong>Fitur Menyesatkan (Misleading):</strong> Produk tidak berfungsi sama sekali atau berbeda drastis dari deskripsi tanpa adanya itikad baik perbaikan dari penjual.</li>
                                <li><strong>Pembayaran Ganda:</strong> Terjadi kesalahan duplikasi debit pada sistem payment gateway.</li>
                            </ul>
                            <h3>Layanan Pengaduan Konsumen Nasional:</h3>
                            <p>Sesuai amanat UU Perlindungan Konsumen No. 8/1999 dan Permendag 31/2023, pengguna dapat menyampaikan keluhan ke Unit Layanan Konsumen Rhantech (<code>pengaduan@rhantech.id</code>) atau Ditjen Perlindungan Konsumen dan Tertib Niaga (PKTN) Kemendag RI (WhatsApp: 0853-1111-1010, Web: simpktn.kemendag.go.id).</p>
                        '
                    ],
                    [
                        'title' => 'Ketentuan Perlindungan Data Pribadi (UU PDP No. 27/2022)',
                        'content' => '
                            <h2>Hak Pengguna atas Data Pribadi di Rhantech</h2>
                            <p>Kami mematuhi seluruh asas pemrosesan data pribadi berdasarkan <strong>UU Perlindungan Data Pribadi (UU PDP No. 27 Tahun 2022)</strong>:</p>
                            <ul>
                                <li><strong>Data yang Diproses:</strong> Nama, alamat email, nomor telepon/WhatsApp, dan riwayat transaksi untuk kepentingan otentikasi, pengiriman invoice, dan tautan unduhan produk.</li>
                                <li><strong>Kerahasiaan Kredensial Keuangan:</strong> Rhantech TIDAK PERNAH menyimpan nomor kartu kredit atau PIN bank pengguna. Seluruh pemrosesan pembayaran ditangani langsung oleh Payment Gateway resmi berlisensi Bank Indonesia melalui enkripsi standar PCI-DSS.</li>
                                <li><strong>Tidak Ada Penjualan Data:</strong> Data pribadi Anda tidak akan pernah dijual atau disewakan kepada pihak ketiga mana pun untuk tujuan telemarketing atau periklanan ilegal.</li>
                                <li><strong>Hak Penghapusan Data (Right to be Forgotten):</strong> Pengguna berhak mengajukan penutupan akun dan penghapusan data pribadi dengan menghubungi tim privasi kami di <code>privacy@rhantech.id</code>.</li>
                            </ul>
                        '
                    ]
                ]
            ],
            [
                'name' => 'Layanan Toko PRO & Langganan',
                'icon' => 'workspace_premium',
                'description' => 'Panduan lengkap fitur Toko PRO, fee penarikan hanya 1%, WhatsApp Broadcast, portofolio proyek, pembayaran instan QRIS Midtrans, serta panduan manajemen paket untuk admin.',
                'sort_order' => 9,
                'articles' => [
                    [
                        'title' => 'Panduan Lengkap Layanan Toko PRO: Keuntungan dan Fitur Eksklusif',
                        'content' => '
                            <h2>Apa itu Layanan Toko PRO di Rhantech?</h2>
                            <p><strong>Toko PRO</strong> adalah program keanggotaan (membership) premium resmi di platform Rhantech yang dirancang khusus bagi para kreator, pengembang web/aplikasi, dan pemilik agensi digital yang ingin memaksimalkan keuntungan serta membangun reputasi profesional dalam memasarkan source code dan produk digital.</p>
                            
                            <p>Dengan meningkatkan status toko Anda ke level PRO, Anda akan mendapatkan berbagai keistimewaan eksklusif mulai dari penghematan biaya penarikan, fitur otomasi pemasaran via WhatsApp, hingga lencana kepercayaan resmi yang membedakan toko Anda dari penjual biasa.</p>

                            <h3>Tabel Perbandingan: Toko Reguler vs Toko PRO</h3>
                            <table class="w-full border border-gray-200 text-sm my-4 rounded-xl overflow-hidden shadow-sm">
                                <thead class="bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-gray-200 font-bold">
                                    <tr>
                                        <th class="p-3 border-b text-left">Fitur & Fasilitas</th>
                                        <th class="p-3 border-b text-center">Toko Reguler (Gratis)</th>
                                        <th class="p-3 border-b text-center bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400 font-black">Toko PRO (Premium)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                                    <tr>
                                        <td class="p-3 font-semibold">Biaya Penarikan Dana (Payout Fee)</td>
                                        <td class="p-3 text-center text-rose-600 font-semibold">2.5% per penarikan</td>
                                        <td class="p-3 text-center text-emerald-600 bg-amber-50/50 dark:bg-amber-950/20 font-black">Hanya 1.0% (Hemat 60%!)</td>
                                    </tr>
                                    <tr>
                                        <td class="p-3 font-semibold">Lencana Toko Terverifikasi</td>
                                        <td class="p-3 text-center text-gray-400">Standar</td>
                                        <td class="p-3 text-center text-amber-600 bg-amber-50/50 dark:bg-amber-950/20 font-bold">Badge Emas "PRO SELLER"</td>
                                    </tr>
                                    <tr>
                                        <td class="p-3 font-semibold">WhatsApp Broadcast Pelanggan</td>
                                        <td class="p-3 text-center text-gray-400">Terkunci / Nonaktif</td>
                                        <td class="p-3 text-center text-emerald-600 bg-amber-50/50 dark:bg-amber-950/20 font-bold">Aktif Tanpa Batas</td>
                                    </tr>
                                    <tr>
                                        <td class="p-3 font-semibold">Modul Portofolio & Proyek Kustom</td>
                                        <td class="p-3 text-center text-gray-400">Tidak Tersedia</td>
                                        <td class="p-3 text-center text-emerald-600 bg-amber-50/50 dark:bg-amber-950/20 font-bold">Showcase Publik Terbuka</td>
                                    </tr>
                                    <tr>
                                        <td class="p-3 font-semibold">Prioritas Penanganan Bantuan CS</td>
                                        <td class="p-3 text-center text-gray-500">Antrean Normal</td>
                                        <td class="p-3 text-center text-purple-600 bg-amber-50/50 dark:bg-amber-950/20 font-bold">Fast-Track Prioritas Utama</td>
                                    </tr>
                                    <tr>
                                        <td class="p-3 font-semibold">Dukungan Rekomendasi Algoritma</td>
                                        <td class="p-3 text-center text-gray-500">Standar</td>
                                        <td class="p-3 text-center text-sky-600 bg-amber-50/50 dark:bg-amber-950/20 font-bold">Prioritas Tampil di Rekomendasi</td>
                                    </tr>
                                </tbody>
                            </table>

                            <h3>Rincian Keuntungan Utama Toko PRO</h3>

                            <h4>1. Penghematan Luar Biasa Fee Penarikan Dana (Fee 1.0%)</h4>
                            <p>Sebagai penjual aktif, keuntungan bersih Anda adalah prioritas nomor satu. Pada akun reguler, platform mengenakan biaya operasional dan pemrosesan penarikan sebesar <strong>2.5%</strong>. Namun bagi pemilik <strong>Toko PRO</strong>, biaya penarikan dipangkas drastis menjadi hanya <strong>1.0%</strong>.</p>
                            <blockquote>
                                <strong>Simulasi Keuntungan Finansial:</strong><br>
                                Jika toko Anda melakukan penarikan omzet sebesar <strong>Rp10.000.000</strong>:<br>
                                &bull; Akun Reguler (2.5%): Biaya potongan = Rp250.000.<br>
                                &bull; Akun PRO (1.0%): Biaya potongan = Rp100.000.<br>
                                <strong>Anda menghemat Rp150.000 dalam satu kali transaksi penarikan saja!</strong> Nilai penghematan ini sudah langsung menutupi biaya paket langganan bulanan Anda.
                            </blockquote>

                            <h4>2. Fitur WhatsApp Broadcast Pelanggan</h4>
                            <p>Pembeli yang pernah membeli source code di toko Anda adalah audiens dengan konversi pembelian ulang (repeat order) tertinggi. Fitur <strong>WA Broadcast</strong> memungkinkan Anda mengirimkan notifikasi update rilis versi baru, pengumuman voucher diskon eksklusif, maupun penawaran jasa instalasi langsung ke nomor WhatsApp pelanggan secara resmi dan tertarget.</p>

                            <h4>3. Modul Portofolio & Jasa Pembuatan Proyek Publik</h4>
                            <p>Banyak pembeli produk digital yang juga mencari developer handal untuk memodifikasi atau membuat aplikasi kustom (custom development). Halaman toko PRO Anda dilengkapi modul portofolio interaktif untuk memajang karya terbaik, tangkapan layar sistem, dan studi kasus proyek yang pernah Anda kerjakan, membuka peluang bisnis proyek bernilai jutaan rupiah di luar penjualan produk template.</p>

                            <h4>4. Lencana Verifikasi Emas "PRO SELLER"</h4>
                            <p>Di pasar produk digital, kredibilitas source code dan layanan pasca-jual adalah pertimbangan utama calon pembeli. Lencana emas resmi <em>"PRO SELLER"</em> yang disematkan pada nama toko dan kartu produk Anda di halaman utama marketplace terbukti meningkatkan rasa aman pembeli dan mendongkrak tingkat konversi penjualan hingga 2x lipat.</p>
                        '
                    ],
                    [
                        'title' => 'Panduan Cara Upgrade Toko PRO: Pembayaran QRIS dan Potong Saldo',
                        'content' => '
                            <h2>Langkah demi Langkah Upgrade ke Akun Toko PRO</h2>
                            <p>Proses upgrade akun ke Toko PRO di Rhantech sangat cepat, praktis, dan 100% otomatis. Anda tidak perlu menunggu konfirmasi manual atau mengirim bukti struk transfer.</p>

                            <h3>Langkah 1: Masuk ke Menu Layanan PRO</h3>
                            <p>Buka <strong>Dashboard Penjual</strong> Anda, lalu klik menu <strong>Layanan PRO</strong> pada bilah navigasi kiri, atau akses langsung melalui URL <code>/dashboard/pro</code> (atau <code>/tenant/pro</code>).</p>

                            <h3>Langkah 2: Pilih Paket Berlangganan yang Sesuai</h3>
                            <p>Pilih paket langganan yang paling menguntungkan untuk model bisnis Anda:</p>
                            <ul>
                                <li><strong>Paket Bulanan (Monthly):</strong> Pilihan ideal untuk uji coba atau bagi toko baru yang ingin merasakan akselerasi penjualan dan penghematan fee penarikan.</li>
                                <li><strong>Paket Tahunan (Yearly):</strong> Paling hemat dan direkomendasikan! Mendapatkan diskon harga signifikan dibandingkan membayar bulanan secara terpisah selama 365 hari.</li>
                                <li><strong>Paket Selamanya (Lifetime):</strong> Bayar sekali untuk akses permanen seumur hidup tanpa biaya langganan berulang selamanya.</li>
                            </ul>

                            <h3>Langkah 3: Memilih Metode Pembayaran</h3>
                            <p>Tersedia 2 opsi pembayaran fleksibel:</p>

                            <h4>Metode A: Pembayaran Instan QRIS / Midtrans (Rekomendasi Utama)</h4>
                            <ol>
                                <li>Pilih opsi radio <strong>"QRIS / Midtrans (Instan & Otomatis)"</strong>.</li>
                                <li>Klik tombol <strong>"Konfirmasi & Bayar Upgrade"</strong>.</li>
                                <li>Layar akan otomatis menampilkan pop-up pembayaran <strong>Midtrans Snap</strong> dengan kode QRIS resmi berstandar Bank Indonesia.</li>
                                <li>Buka aplikasi e-wallet Anda (GoPay, OVO, DANA, ShopeePay, AstraPay, LinkAja) atau aplikasi Mobile Banking pilihan Anda (BCA Mobile, Livin by Mandiri, BRImo, BNI Mobile, Seabank, Bank Jago, dll).</li>
                                <li>Pindai (scan) kode QR yang tampil di layar dan lakukan konfirmasi pembayaran.</li>
                                <li><strong>Aktivasi Otomatis Real-time:</strong> Sistem transaksi Rhantech menggunakan kode invoice berawalan <code>RHN-</code> (contoh: <code>RHN-PRO-2609-XXXXX</code>) yang terhubung langsung dengan webhook payment gateway. Begitu dana terdebet dari rekening Anda, sistem dalam hitungan detik langsung mengaktifkan status PRO toko Anda tanpa jeda!</li>
                            </ol>

                            <h4>Metode B: Potong Saldo Toko (Wallet Internal)</h4>
                            <ol>
                                <li>Jika toko Anda telah memiliki akumulasi saldo penjualan yang mencukupi dari transaksi produk digital Anda, Anda dapat memilih opsi <strong>"Potong Saldo Toko"</strong>.</li>
                                <li>Klik tombol <strong>"Konfirmasi & Bayar Upgrade"</strong>.</li>
                                <li>Sistem secara otomatis mendebit saldo dompet toko Anda sesuai nominal paket tanpa biaya transfer tambahan, dan akun PRO Anda langsung aktif saat itu juga.</li>
                            </ol>

                            <h3>Pertanyaan Umum (FAQ) Seputar Upgrade PRO</h3>
                            <p><strong>Q: Apakah masa aktif PRO saya diakumulasikan jika saya memperpanjang sebelum kedaluwarsa?</strong><br>
                            <em>A: Ya! Jika Anda saat ini memiliki sisa 10 hari dan membeli paket 30 hari lagi, total masa aktif toko Anda akan otomatis terakumulasi menjadi 40 hari.</em></p>

                            <p><strong>Q: Di mana saya bisa melihat sisa masa aktif langganan saya?</strong><br>
                            <em>A: Anda dapat melihat tanggal berakhirnya langganan secara transparan di kartu banner atas halaman <code>/dashboard/pro</code> kapan saja.</em></p>

                            <p><strong>Q: Apa yang terjadi jika masa aktif PRO saya berakhir?</strong><br>
                            <em>A: Toko Anda akan kembali ke tarif penarikan standar (2.5%) dan fitur premium dinonaktifkan sementara hingga Anda melakukan perpanjangan paket kembali. Seluruh data produk dan portofolio Anda tetap aman tersimpan.</em></p>
                        '
                    ],
                    [
                        'title' => 'Panduan Admin: Manajemen Paket Langganan Toko PRO',
                        'content' => '
                            <h2>Mengelola Paket Toko PRO bagi Administrator Platform</h2>
                            <p>Sebagai Pengelola Platform (Admin), Anda memiliki kendali penuh untuk membuat paket baru, mengatur durasi, menentukan tarif biaya, menambahkan label promo, dan menyusun daftar fitur penawaran secara dinamis melalui antarmuka Admin Panel tanpa perlu menyentuh kode program.</p>

                            <h3>Lokasi Menu Manajemen Paket PRO</h3>
                            <p>Buka <strong>Admin Panel</strong> &rarr; pilih menu <strong>Tenant</strong> pada sidebar &rarr; klik submenu <strong>Paket Toko PRO</strong> (URL: <code>/admin/pro_plans</code>).</p>

                            <h3>Panduan Menambahkan Paket PRO Baru</h3>
                            <p>Klik tombol <strong>+ Tambah Paket PRO</strong> di pojok kanan atas untuk membuka formulir pembuatan paket baru:</p>
                            <ol>
                                <li><strong>Nama Paket:</strong> Masukkan nama komersial paket yang ramah dibaca (contoh: <em>Paket Bulanan</em>, <em>Paket Semesteran 6 Bulan</em>, <em>Paket Tahunan Juara</em>).</li>
                                <li><strong>Slug / Kode Unik:</strong> Identifikasi internal sistem dalam huruf kecil dan garis bawah (contoh: <code>monthly</code>, <code>semi_annual</code>, <code>yearly</code>, <code>lifetime</code>). Pastikan slug ini belum pernah digunakan oleh paket lain.</li>
                                <li><strong>Durasi Hari:</strong> Masukkan angka jumlah hari masa aktif langganan:
                                    <ul>
                                        <li>Isi <code>30</code> untuk durasi 1 bulan.</li>
                                        <li>Isi <code>180</code> untuk durasi 6 bulan.</li>
                                        <li>Isi <code>365</code> untuk durasi 1 tahun.</li>
                                        <li>Isi <code>0</code> untuk durasi Lifetime (Akses Selamanya tanpa batas waktu).</li>
                                    </ul>
                                </li>
                                <li><strong>Harga (IDR):</strong> Masukkan tarif harga dalam bentuk angka tanpa titik atau koma (contoh: <code>99000</code> untuk Rp99.000).</li>
                                <li><strong>Badge / Label Promo (Opsional):</strong> Masukkan teks pemanis marketing untuk menarik perhatian calon seller (contoh: <em>HEMAT 25%</em>, <em>BEST SELLER</em>, <em>POPULER</em>).</li>
                                <li><strong>Tandai Sebagai Paling Populer (Checkbox):</strong> Jika dicentang, paket ini akan terpilih secara otomatis (default selection) saat penjual membuka halaman upgrade PRO.</li>
                                <li><strong>Deskripsi Singkat:</strong> Ringkasan keuntungan atau target penjual untuk paket tersebut.</li>
                                <li><strong>Daftar Fitur & Keuntungan (Per Baris):</strong> Tuliskan poin-poin keuntungan paket di textarea, dengan <strong>satu poin per baris</strong>. Sistem akan secara otomatis mengonversi setiap baris teks menjadi ikon centang hijau yang estetik pada kartu pilihan tenant.</li>
                                <li><strong>Urutan Tampilan:</strong> Nomor urut urutan kartu dari kiri ke kanan (angka terkecil tampil paling depan).</li>
                                <li><strong>Status Aktif:</strong> Pastikan opsi status berada dalam kondisi <em>Aktif</em> agar paket segera terlihat di halaman tenant.</li>
                            </ol>

                            <h3>Tindakan Cepat pada Daftar Paket (Tabel Admin)</h3>
                            <ul>
                                <li><strong>Toggle Aktif / Nonaktif Sekali Klik:</strong> Anda dapat mengaktifkan atau menonaktifkan suatu paket secara instan langsung dari tabel data tanpa memuat ulang (reload) halaman. Paket yang dinonaktifkan tidak akan lagi muncul di dashboard penjual.</li>
                                <li><strong>Edit Paket:</strong> Klik tombol ikon pensil untuk mengubah harga, merevisi teks fitur, atau mengganti badge promo sewaktu-waktu (misalnya saat menggelar program flash sale bulanan).</li>
                                <li><strong>Hapus Paket:</strong> Hapus paket yang sudah usang atau tidak lagi ditawarkan.</li>
                                <li><strong>Lihat di Dashboard Tenant:</strong> Tombol pintasan langsung untuk meninjau secara nyata tampilan kartu paket yang dilihat oleh para penjual.</li>
                            </ul>

                            <h3>Standar Keamanan Transaksi QRIS Platform</h3>
                            <p>Sistem langganan Toko PRO terintegrasi dengan Payment Gateway berstandar industri dengan pengamanan invoice berawalan <code>RHN-</code>. Setiap transaksi dicatat secara lengkap pada riwayat transaksi langganan dan histori akun toko, menjamin rekonsiliasi keuangan platform selalu akurat dan terverifikasi.</p>
                        '
                    ]
                ]
            ]
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
                        'content' => trim($artData['content']),
                        'is_published' => true,
                        'views' => rand(50, 650),
                    ]
                );
            }
        }
    }
}

@extends('layouts.public')

@section('title', 'Kebijakan Pengembalian Dana (Refund Policy) - Rhantech Store')

@section('content')
<div class="min-h-screen bg-slate-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Breadcrumb & Title -->
        <div class="mb-8">
            <nav class="flex items-center text-xs font-medium text-slate-500 mb-3 gap-2">
                <a href="{{ url('/') }}" class="hover:text-sky-600 transition">Beranda</a>
                <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-slate-800 font-semibold">Kebijakan Pengembalian Dana</span>
            </nav>
            <div class="flex items-center gap-3">
                <span class="p-2.5 bg-sky-100 text-sky-600 rounded-xl">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                    </svg>
                </span>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Kebijakan Pengembalian Dana (Refund Policy)</h1>
                    <p class="text-sm text-slate-500 mt-1">Ketentuan Garansi & Refund Produk Digital Berdasarkan UU Perlindungan Konsumen No. 8/1999</p>
                </div>
            </div>
        </div>

        <!-- Content Card -->
        <div class="bg-white rounded-2xl p-6 sm:p-10 shadow-sm border border-slate-200/80 text-slate-700 leading-relaxed space-y-8">
            
            <!-- Prinsip Barang Digital -->
            <div class="p-4 bg-amber-50 border border-amber-200/80 rounded-xl text-sm text-amber-900">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <p class="font-semibold mb-1">Karakteristik Khusus Produk Digital (Intangible Goods)</p>
                        <p class="text-xs text-amber-800">Mengingat produk digital (source code, e-book, template, lisensi perangkat lunak) dapat diunduh, disalin, dan langsung diakses seketika setelah pembayaran terkonfirmasi, secara umum <strong>seluruh transaksi bersifat final dan tidak dapat dibatalkan (Non-Refundable)</strong>, kecuali memenuhi kriteria khusus di bawah ini.</p>
                    </div>
                </div>
            </div>

            <!-- Section 1 -->
            <div>
                <h2 class="text-lg font-bold text-slate-900 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-sky-500 text-white text-xs flex items-center justify-center font-semibold">1</span>
                    Kondisi yang Memenuhi Syarat Pengembalian Dana
                </h2>
                <div class="space-y-3 text-sm text-slate-600 pl-8">
                    <p>Pembeli berhak mengajukan klaim pengembalian dana (refund) dalam kurun waktu <strong>maksimal 3 (tiga) hari kalender</strong> sejak tanggal transaksi, apabila terjadi kondisi berikut:</p>
                    <div class="space-y-2">
                        <div class="flex items-start gap-2.5 p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                            <span class="text-emerald-600 font-bold text-base">✓</span>
                            <div class="text-xs">
                                <span class="font-semibold text-slate-800">File Rusak atau Tidak Dapat Diunduh (Corrupt/Dead Link):</span>
                                <p class="text-slate-600 mt-0.5">Tautan unduhan tidak berfungsi, berkas arsip rusak (corrupted archive), dan tenant penjual gagal menyediakan tautan alternatif pengganti dalam tempo 2x24 jam kerja.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-2.5 p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                            <span class="text-emerald-600 font-bold text-base">✓</span>
                            <div class="text-xs">
                                <span class="font-semibold text-slate-800">Kesesuaian Fitur yang Menyesatkan (Misleading/False Advertising):</span>
                                <p class="text-slate-600 mt-0.5">Produk berbeda secara signifikan dari fitur utama yang dijanjikan pada halaman deskripsi produk, dan tidak dapat dibuktikan fungsionalitas dasarnya oleh penjual.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-2.5 p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                            <span class="text-emerald-600 font-bold text-base">✓</span>
                            <div class="text-xs">
                                <span class="font-semibold text-slate-800">Duplikasi Transaksi / Overpayment:</span>
                                <p class="text-slate-600 mt-0.5">Sistem payment gateway memotong saldo pembeli lebih dari satu kali untuk nomor invoice pesanan yang sama.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2 -->
            <div>
                <h2 class="text-lg font-bold text-slate-900 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-sky-500 text-white text-xs flex items-center justify-center font-semibold">2</span>
                    Kondisi yang Tidak Memenuhi Syarat Refund
                </h2>
                <div class="space-y-2 text-sm text-slate-600 pl-8">
                    <p>Pengajuan pengembalian dana <strong>akan ditolak</strong> jika disebabkan oleh faktor-faktor berikut:</p>
                    <ul class="list-disc pl-5 space-y-1 text-xs text-slate-600">
                        <li>Pembeli berubah pikiran (change of mind) setelah berkas produk berhasil diunduh.</li>
                        <li>Ketidakmampuan teknis atau kurangnya keahlian pembeli dalam menginstal, mengonfigurasi, atau menjalankan software (misal: belum paham cara install composer/node.js/hosting).</li>
                        <li>Spesifikasi server/hosting pembeli tidak memenuhi syarat minimum yang telah dicantumkan secara jelas pada deskripsi produk.</li>
                        <li>Pembeli mengklaim ada bug kecil yang dapat diselesaikan oleh penjual melalui dukungan teknis perbaikan (bug fix update).</li>
                        <li>Produk yang dibeli merupakan produk diskon kilat (flash sale) atau paket promosi khusus berstatus <em>non-refundable</em>.</li>
                    </ul>
                </div>
            </div>

            <!-- Section 3 -->
            <div>
                <h2 class="text-lg font-bold text-slate-900 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-sky-500 text-white text-xs flex items-center justify-center font-semibold">3</span>
                    Alur Mediasi & Prosedur Pengajuan Refund
                </h2>
                <div class="space-y-3 text-sm text-slate-600 pl-8">
                    <ol class="list-decimal pl-5 space-y-2 text-xs text-slate-600">
                        <li><strong>Komunikasi dengan Penjual (Tenant):</strong> Pembeli diwajibkan terlebih dahulu menghubungi tenant penjual melalui fitur chat toko atau kontak resmi toko untuk mencari solusi teknis.</li>
                        <li><strong>Eskalasi Sengketa ke Platform Rhantech:</strong> Jika tenant tidak merespons dalam waktu 1x24 jam kerja atau tidak mencapai kesepakatan, pembeli dapat mengajukan tiket sengketa melalui Help Center atau email <a href="mailto:support@rhantech.id" class="text-sky-600 font-semibold">support@rhantech.id</a> dengan menyertakan:
                            <ul class="list-disc pl-5 mt-1 space-y-0.5 text-slate-500">
                                <li>Nomor Invoice Pesanan</li>
                                <li>Tangkapan layar (screenshot) atau bukti rekaman error/ketidaksesuaian</li>
                                <li>Riwayat percakapan dengan penjual</li>
                            </ul>
                        </li>
                        <li><strong>Investigasi Independen:</strong> Tim Quality & Dispute Rhantech akan meninjau kode/berkas produk dan melakukan pengetesan fungsional dalam waktu maksimal 2 x 24 jam kerja.</li>
                        <li><strong>Penyelesaian:</strong> Jika klaim disetujui, dana akan dikembalikan ke saldo dompet pembeli atau rekening asal via payment gateway dalam tempo 1-3 hari kerja. Lisensi pembeli atas produk tersebut akan otomatis dicabut.</li>
                    </ol>
                </div>
            </div>

            <!-- Section 4 -->
            <div>
                <h2 class="text-lg font-bold text-slate-900 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-sky-500 text-white text-xs flex items-center justify-center font-semibold">4</span>
                    Perlindungan Konsumen & Layanan Pengaduan Pemerintah
                </h2>
                <div class="space-y-2 text-sm text-slate-600 pl-8">
                    <p class="text-xs">Sesuai dengan <strong>Undang-Undang Republik Indonesia No. 8 Tahun 1999 tentang Perlindungan Konsumen</strong> dan <strong>Permendag No. 31 Tahun 2023</strong>, kami menyediakan saluran pengaduan konsumen independen jika mediasi platform tidak memuaskan:</p>
                    
                    <div class="grid sm:grid-cols-2 gap-3 mt-3">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <span class="text-xs font-bold text-slate-800 block mb-1">Unit Pengaduan Konsumen Internal</span>
                            <p class="text-xs text-slate-600">PT Rhantech Digital Globalindo</p>
                            <p class="text-xs text-slate-600">Email: pengaduan@rhantech.id</p>
                            <p class="text-xs text-slate-600">WhatsApp: +62 822-xxxx-xxxx</p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <span class="text-xs font-bold text-slate-800 block mb-1">Ditjen PKTN Kemendag RI</span>
                            <p class="text-xs text-slate-600">Direktorat Perlindungan Konsumen & Tertib Niaga</p>
                            <p class="text-xs text-slate-600">WhatsApp: +62 853-1111-1010</p>
                            <p class="text-xs text-slate-600">Website: https://simpktn.kemendag.go.id</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Footer Help -->
        <div class="mt-8 text-center text-xs text-slate-500">
            Perlu bantuan pengajuan refund atau penyelesaian kendala produk? 
            <a href="{{ url('/help') }}" class="text-sky-600 font-semibold hover:underline">Kunjungi Pusat Bantuan (Help Center)</a>
        </div>

    </div>
</div>
@endsection

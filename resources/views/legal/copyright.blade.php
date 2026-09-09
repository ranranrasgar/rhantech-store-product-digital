@extends('layouts.public')

@section('title', 'Kebijakan Hak Cipta & Kekayaan Intelektual - Rhantech Store')

@section('content')
<div class="min-h-screen bg-slate-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Breadcrumb & Title -->
        <div class="mb-8">
            <nav class="flex items-center text-xs font-medium text-slate-500 mb-3 gap-2">
                <a href="{{ url('/') }}" class="hover:text-sky-600 transition">Beranda</a>
                <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-slate-800 font-semibold">Kebijakan Hak Cipta (HAKI)</span>
            </nav>
            <div class="flex items-center gap-3">
                <span class="p-2.5 bg-sky-100 text-sky-600 rounded-xl">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </span>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Kebijakan Hak Cipta & Kekayaan Intelektual</h1>
                    <p class="text-sm text-slate-500 mt-1">Kepatuhan Berdasarkan UU No. 28 Tahun 2014 tentang Hak Cipta & Surat Edaran Menkominfo No. 5 Tahun 2016</p>
                </div>
            </div>
        </div>

        <!-- Content Card -->
        <div class="bg-white rounded-2xl p-6 sm:p-10 shadow-sm border border-slate-200/80 text-slate-700 leading-relaxed space-y-8">
            
            <!-- Ringkasan Hukum -->
            <div class="p-4 bg-sky-50 border border-sky-100 rounded-xl text-sm text-sky-900">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-sky-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="font-semibold mb-1">Prinsip Perlindungan Hak Kekayaan Intelektual</p>
                        <p class="text-xs text-sky-800">Rhantech berkomitmen menjunjung tinggi hak cipta karya intelektual digital (source code, desain grafis, plugin, audio, template, dan lisensi software). Platform melarang keras penjualan karya bajakan, nulled script, cracked software, atau aset tanpa hak distribusi yang sah.</p>
                    </div>
                </div>
            </div>

            <!-- Section 1 -->
            <div>
                <h2 class="text-lg font-bold text-slate-900 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-sky-500 text-white text-xs flex items-center justify-center font-semibold">1</span>
                    Kepemilikan Hak Cipta Produk Digital
                </h2>
                <div class="space-y-2 text-sm text-slate-600 pl-8">
                    <p>1.1. Seluruh Hak Moral dan Hak Ekonomi atas produk digital yang diperjualbelikan melalui toko tenant tetap sepenuhnya menjadi hak milik Kreator/Developer atau pemegang lisensi resmi awal, sesuai <strong>Pasal 5 s.d. Pasal 10 UU No. 28 Tahun 2014</strong>.</p>
                    <p>1.2. Transaksi pembelian produk digital di platform Rhantech merupakan pemberian <strong>Hak Pakai Lisensi (Non-Exclusive License to Use)</strong>, dan <em>bukan merupakan pemindahtanganan hak cipta</em> secara mutlak, kecuali jika dinyatakan lain dalam lisensi Extended/Exclusive secara tertulis oleh Kreator.</p>
                </div>
            </div>

            <!-- Section 2 -->
            <div>
                <h2 class="text-lg font-bold text-slate-900 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-sky-500 text-white text-xs flex items-center justify-center font-semibold">2</span>
                    Jenis Lisensi & Batas Penggunaan
                </h2>
                <div class="space-y-3 text-sm text-slate-600 pl-8">
                    <p>Setiap produk digital yang dijual di platform wajib mencantumkan jenis lisensi penggunaannya:</p>
                    <div class="grid sm:grid-cols-2 gap-3 mt-2">
                        <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/50">
                            <span class="text-xs font-bold text-sky-600 uppercase tracking-wider block mb-1">Lisensi Personal (Single Use)</span>
                            <p class="text-xs text-slate-600">Digunakan hanya untuk 1 (satu) proyek atau kebutuhan pribadi pembeli. Dilarang mendistribusikan ulang, menjual kembali (resale), atau mempublikasikan source code secara terbuka.</p>
                        </div>
                        <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/50">
                            <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider block mb-1">Lisensi Komersial / Developer</span>
                            <p class="text-xs text-slate-600">Digunakan untuk proyek komersial klien akhir. Tidak memberikan hak kepada pembeli untuk menjual produk digital tersebut sebagai produk mandiri atau redistribusi bundle gratis.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3 -->
            <div>
                <h2 class="text-lg font-bold text-slate-900 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-sky-500 text-white text-xs flex items-center justify-center font-semibold">3</span>
                    Larangan Konten Ilegal & Anti-Warez
                </h2>
                <div class="space-y-2 text-sm text-slate-600 pl-8">
                    <p>Setiap tenant dan penjual di platform Rhantech dilarang keras mengunggah atau memperjualbelikan:</p>
                    <ul class="list-disc pl-5 space-y-1 text-xs text-slate-600">
                        <li>Source code, script, modul, template yang diperoleh dari situs bajakan (nulled, cracked, rip-off).</li>
                        <li>Produk digital pihak ketiga yang didistribusikan tanpa bukti izin, hak jual kembali (PLR/MRR yang sah), atau pelanggaran EULA vendor awal.</li>
                        <li>Software atau kode yang disisipi malware, backdoor, web shell, miner kripto, trojan, atau payload berbahaya lainnya.</li>
                        <li>Konten digital yang melanggar norma hukum, kesusilaan (UU Pornografi), atau perjudian (UU ITE).</li>
                    </ul>
                    <p class="text-xs text-rose-600 font-medium mt-2">Pelanggaran kategori ini akan mengakibatkan pembekuan akun tenant permanen, penahanan saldo, serta pelaporan kepada pihak penegak hukum yang berwenang.</p>
                </div>
            </div>

            <!-- Section 4 -->
            <div>
                <h2 class="text-lg font-bold text-slate-900 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-sky-500 text-white text-xs flex items-center justify-center font-semibold">4</span>
                    Prosedur Pelaporan Pelanggaran (Notice & Takedown)
                </h2>
                <div class="space-y-3 text-sm text-slate-600 pl-8">
                    <p>Berdasarkan <strong>Surat Edaran Menkominfo No. 5 Tahun 2016</strong> tentang Batasan Tanggung Jawab Penyelenggara Platform (Safe Harbor Principle), Rhantech menerapkan mekanisme Notice and Takedown bagi pemegang hak cipta sah yang menemukan dugaan pelanggaran:</p>
                    
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-2">
                        <p class="text-xs font-semibold text-slate-800">Syarat Pengajuan Laporan Pelanggaran Hak Cipta:</p>
                        <ol class="list-decimal pl-5 space-y-1 text-xs text-slate-600">
                            <li>Identitas lengkap pemegang hak cipta atau kuasa hukum yang sah (KTP/Paspor & Surat Kuasa).</li>
                            <li>Bukti kepemilikan hak cipta (Sertifikat HAKI Kemenkumham, timestamp repositori asli, link publikasi awal).</li>
                            <li>Tautan (URL) spesifik produk yang diduga melakukan pelanggaran di platform Rhantech.</li>
                            <li>Pernyataan itikad baik bertanda tangan bahwa laporan dibuat dengan fakta yang sebenarnya.</li>
                        </ol>
                        <div class="pt-2">
                            <p class="text-xs text-slate-700">Kirimkan dokumen laporan resmi ke:</p>
                            <a href="mailto:copyright@rhantech.id" class="text-xs font-bold text-sky-600 hover:underline">legal@rhantech.id / copyright@rhantech.id</a>
                            <span class="text-xs text-slate-500 ml-2">Subjek: [HAKI Notice] Dugaan Pelanggaran Hak Cipta</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-600">Tim Legal Rhantech akan meninjau dan melakukan <strong>penonaktifan sementara (temporary takedown)</strong> terhadap produk terlapor dalam waktu maksimal 1 x 24 jam kerja sejak laporan lengkap diterima untuk proses verifikasi dan mediasi (Counter-Notice).</p>
                </div>
            </div>

            <!-- Section 5 -->
            <div>
                <h2 class="text-lg font-bold text-slate-900 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-sky-500 text-white text-xs flex items-center justify-center font-semibold">5</span>
                    Merek Dagang & Aset Platform Rhantech
                </h2>
                <div class="space-y-2 text-sm text-slate-600 pl-8">
                    <p>Nama "Rhantech", logo, elemen desain UI/UX, maskot, ikon sistem, dan kode program platform adalah hak kekayaan intelektual milik Rhantech. Pihak mana pun dilarang menggunakan, meniru, atau merekayasa ulang (reverse engineering) tanpa izin tertulis dari manajemen Rhantech.</p>
                </div>
            </div>

        </div>

        <!-- Footer Help -->
        <div class="mt-8 text-center text-xs text-slate-500">
            Ada pertanyaan seputar hak cipta atau lisensi? Hubungi Tim Legal kami di 
            <a href="mailto:legal@rhantech.id" class="text-sky-600 font-semibold hover:underline">legal@rhantech.id</a>
        </div>

    </div>
</div>
@endsection

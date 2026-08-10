@extends('layouts.tenant')

@section('title', 'Pengaturan Toko')

@section('content')
<div class="flex-1 overflow-y-auto bg-[#f6f6f6] dark:bg-[#0d1117] text-on-surface dark:text-white font-body-md" x-data="{ tab: 'akun' }">
    
    <!-- Top Navigation Tabs (PPOB Style) -->
    <div class="bg-white dark:bg-[#161b22] px-6 flex items-center gap-6 text-sm font-semibold overflow-x-auto whitespace-nowrap shadow-sm border-b border-gray-200 dark:border-[#30363d] sticky top-0 z-30">
        <button @click="tab = 'akun'" :class="tab === 'akun' ? 'border-b-2 border-error text-error' : 'border-b-2 border-transparent text-gray-700 dark:text-gray-300 hover:text-error'" class="py-4 px-2 transition-colors">Akun & Keamanan</button>
        <button class="py-4 px-2 border-b-2 border-transparent text-gray-700 dark:text-gray-300 hover:text-error transition-colors">Pengiriman</button>
        <button class="py-4 px-2 border-b-2 border-transparent text-gray-700 dark:text-gray-300 hover:text-error transition-colors">Pembayaran</button>
        <button class="py-4 px-2 border-b-2 border-transparent text-gray-700 dark:text-gray-300 hover:text-error transition-colors">Produk</button>
        <button class="py-4 px-2 border-b-2 border-transparent text-gray-700 dark:text-gray-300 hover:text-error transition-colors">Chat</button>
        <button @click="tab = 'notifikasi'" :class="tab === 'notifikasi' ? 'border-b-2 border-error text-error' : 'border-b-2 border-transparent text-gray-700 dark:text-gray-300 hover:text-error'" class="py-4 px-2 transition-colors">Notifikasi</button>
        <button @click="tab = 'libur'" :class="tab === 'libur' ? 'border-b-2 border-error text-error' : 'border-b-2 border-transparent text-gray-700 dark:text-gray-300 hover:text-error'" class="py-4 px-2 transition-colors">Mode Libur</button>
        <button class="py-4 px-2 border-b-2 border-transparent text-gray-700 dark:text-gray-300 hover:text-error transition-colors">Aplikasi Pihak Ketiga</button>
    </div>

    <div class="p-6 space-y-6 max-w-6xl mx-auto min-h-[calc(100vh-140px)]">

        <!-- TAB 1: AKUN & KEAMANAN -->
        <div x-show="tab === 'akun'" class="space-y-6">
            
            <!-- Card 1: Informasi Akun -->
            <div class="bg-white dark:bg-[#161b22] rounded shadow-sm border border-gray-200 dark:border-[#30363d] overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-[#30363d]">
                    <h3 class="font-bold text-base text-gray-800 dark:text-white">Informasi Akun</h3>
                </div>
                
                <div class="divide-y divide-gray-100 dark:divide-[#30363d]">
                    <!-- Item -->
                    <div class="flex items-center px-6 py-5 hover:bg-gray-50 dark:hover:bg-[#0d1117] transition-colors">
                        <div class="w-1/3 md:w-1/4 text-sm text-gray-500 dark:text-gray-400">Profil Saya</div>
                        <div class="flex-1 flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Store') }}&background=0D8ABC&color=fff" class="w-8 h-8 rounded-full border border-gray-200 object-cover">
                            <span class="text-sm text-gray-800 dark:text-gray-200">{{ auth()->user()->name ?? 'User Name' }}</span>
                        </div>
                        <button class="px-4 py-1.5 text-xs font-semibold border border-gray-300 dark:border-gray-600 rounded text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Ubah</button>
                    </div>

                    <!-- Item -->
                    <div class="flex items-center px-6 py-5 hover:bg-gray-50 dark:hover:bg-[#0d1117] transition-colors">
                        <div class="w-1/3 md:w-1/4 text-sm text-gray-500 dark:text-gray-400">Telepon</div>
                        <div class="flex-1 text-sm text-gray-800 dark:text-gray-200">
                            ******71
                        </div>
                        <button class="px-4 py-1.5 text-xs font-semibold border border-gray-300 dark:border-gray-600 rounded text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Ubah</button>
                    </div>

                    <!-- Item -->
                    <div class="flex items-center px-6 py-5 hover:bg-gray-50 dark:hover:bg-[#0d1117] transition-colors">
                        <div class="w-1/3 md:w-1/4 text-sm text-gray-500 dark:text-gray-400">Email</div>
                        <div class="flex-1 text-sm text-gray-800 dark:text-gray-200">
                            ra*****@gmail.com
                        </div>
                        <button class="px-4 py-1.5 text-xs font-semibold border border-gray-300 dark:border-gray-600 rounded text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Ubah</button>
                    </div>

                    <!-- Item -->
                    <div class="flex items-center px-6 py-5 hover:bg-gray-50 dark:hover:bg-[#0d1117] transition-colors">
                        <div class="w-1/3 md:w-1/4 text-sm text-gray-500 dark:text-gray-400">Password akun</div>
                        <div class="flex-1 text-sm text-gray-400 dark:text-gray-500">
                            Harap ganti password secara berkala untuk meningkatkan keamanan akunmu.
                        </div>
                        <button class="px-4 py-1.5 text-xs font-semibold border border-gray-300 dark:border-gray-600 rounded text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Update</button>
                    </div>

                    <!-- Item -->
                    <div class="flex items-center px-6 py-5 hover:bg-gray-50 dark:hover:bg-[#0d1117] transition-colors">
                        <div class="w-1/3 md:w-1/4 text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1">Platform Sub Akun <span class="material-symbols-outlined text-[14px]">help</span></div>
                        <div class="flex-1 text-sm text-gray-400 dark:text-gray-500">
                            Tidak terhubung
                        </div>
                        <button class="px-4 py-1.5 text-xs font-semibold border border-gray-300 dark:border-gray-600 rounded text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors flex items-center gap-1">Lihat <span class="material-symbols-outlined text-[14px]">expand_more</span></button>
                    </div>
                </div>
            </div>

            <!-- Card 2: Proteksi Akun -->
            <div class="bg-white dark:bg-[#161b22] rounded shadow-sm border border-gray-200 dark:border-[#30363d] overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-[#30363d]">
                    <h3 class="font-bold text-base text-gray-800 dark:text-white">Proteksi Akun</h3>
                </div>
                
                <!-- Verifikasi Akun -->
                <div class="px-6 py-5 border-b border-gray-200 dark:border-[#30363d]">
                    <h4 class="font-bold text-sm text-gray-800 dark:text-white mb-2">Verifikasi Akun</h4>
                    <div class="flex justify-between items-start gap-4">
                        <div class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed max-w-2xl">
                            <span class="font-semibold text-gray-700 dark:text-gray-300 block mb-1">Aktifkan Metode SMS untuk Verifikasi Perlindungan Akunmu.</span>
                            Jika tombol dimatikan, verifikasi tambahan akan dikirim melalui metode non-SMS (Contoh: Email atau QR).
                        </div>
                        
                        <!-- Toggle Switch -->
                        <div x-data="{ on: true }" @click="on = !on" class="relative inline-flex h-5 w-9 cursor-pointer items-center rounded-full transition-colors duration-300 focus:outline-none" :class="on ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600'">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition duration-300 shadow" :class="on ? 'translate-x-4' : 'translate-x-1'"></span>
                        </div>
                    </div>
                </div>

                <!-- Proteksi Tindakan Berisiko Tinggi -->
                <div class="px-6 py-5">
                    <h4 class="font-bold text-sm text-gray-800 dark:text-white mb-2">Proteksi Tindakan Berisiko Tinggi</h4>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                        <span class="font-semibold text-gray-700 dark:text-gray-300 block mb-1">Metode Verifikasi Akun</span>
                        Atur Pertanyaan Keamanan dan tambah min. 1 metode lainnya untuk jaga keamanan akunmu.
                    </div>

                    <table class="w-full text-left text-sm border border-gray-200 dark:border-[#30363d] rounded overflow-hidden">
                        <thead class="bg-gray-50 dark:bg-[#0d1117] text-gray-500 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-2 font-normal border-b border-gray-200 dark:border-[#30363d]">Metode</th>
                                <th class="px-4 py-2 font-normal border-b border-gray-200 dark:border-[#30363d]">Status</th>
                                <th class="px-4 py-2 font-normal border-b border-gray-200 dark:border-[#30363d] text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-[#30363d]">
                            <tr>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">Pertanyaan Keamanan</td>
                                <td class="px-4 py-3 text-gray-400 flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Belum Diatur</td>
                                <td class="px-4 py-3 text-right"><button class="text-error border border-error hover:bg-error/5 px-3 py-1 rounded text-xs transition-colors">Atur Sekarang</button></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">Perangkat Tepercaya</td>
                                <td class="px-4 py-3 text-gray-400 flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Belum Diatur</td>
                                <td class="px-4 py-3 text-right"><button class="text-error border border-error hover:bg-error/5 px-3 py-1 rounded text-xs transition-colors">Atur Sekarang</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- TAB 2: NOTIFIKASI -->
        <div x-show="tab === 'notifikasi'" class="space-y-6" style="display: none;">
            
            <div class="bg-white dark:bg-[#161b22] rounded shadow-sm border border-gray-200 dark:border-[#30363d] overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-[#30363d] flex justify-between items-center">
                    <h3 class="font-bold text-base text-gray-800 dark:text-white">Notifikasi Email</h3>
                    <button class="px-4 py-1.5 text-xs border border-gray-300 dark:border-gray-600 rounded text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Nonaktifkan Email</button>
                </div>
                
                <div class="p-6 space-y-8">
                    
                    <!-- Section 1 -->
                    <div>
                        <h4 class="font-bold text-sm text-gray-800 dark:text-white mb-4">Informasi Pesanan & Produk</h4>
                        <div class="space-y-6">
                            <!-- Toggle 1 -->
                            <div class="flex justify-between items-center gap-4 border-b border-gray-100 dark:border-gray-800 pb-4">
                                <div>
                                    <div class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Pesanan</div>
                                    <div class="text-xs text-gray-400">Informasi terbaru dari status pesanan</div>
                                </div>
                                <div x-data="{ on: true }" @click="on = !on" class="relative inline-flex h-5 w-9 cursor-pointer items-center rounded-full transition-colors duration-300 focus:outline-none" :class="on ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600'"><span class="inline-block h-4 w-4 transform rounded-full bg-white transition duration-300 shadow" :class="on ? 'translate-x-4' : 'translate-x-1'"></span></div>
                            </div>
                            <!-- Toggle 2 -->
                            <div class="flex justify-between items-center gap-4 border-b border-gray-100 dark:border-gray-800 pb-4">
                                <div>
                                    <div class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Produk</div>
                                    <div class="text-xs text-gray-400">Informasi tentang status terkini dari produkmu</div>
                                </div>
                                <div x-data="{ on: true }" @click="on = !on" class="relative inline-flex h-5 w-9 cursor-pointer items-center rounded-full transition-colors duration-300 focus:outline-none" :class="on ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600'"><span class="inline-block h-4 w-4 transform rounded-full bg-white transition duration-300 shadow" :class="on ? 'translate-x-4' : 'translate-x-1'"></span></div>
                            </div>
                            <!-- Toggle 3 -->
                            <div class="flex justify-between items-center gap-4">
                                <div>
                                    <div class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Kebijakan</div>
                                    <div class="text-xs text-gray-400">Informasi perubahan penting tentang kebijakan, peraturan, inisiatif</div>
                                </div>
                                <div x-data="{ on: true }" @click="on = !on" class="relative inline-flex h-5 w-9 cursor-pointer items-center rounded-full transition-colors duration-300 focus:outline-none" :class="on ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600'"><span class="inline-block h-4 w-4 transform rounded-full bg-white transition duration-300 shadow" :class="on ? 'translate-x-4' : 'translate-x-1'"></span></div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2 -->
                    <div>
                        <h4 class="font-bold text-sm text-gray-800 dark:text-white mb-4">Informasi Media Sosial & Promosi</h4>
                        <div class="space-y-6">
                            <!-- Toggle 1 -->
                            <div class="flex justify-between items-center gap-4 border-b border-gray-100 dark:border-gray-800 pb-4">
                                <div>
                                    <div class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Promosi</div>
                                    <div class="text-xs text-gray-400">Informasi eksklusif tentang promo dan penawaran yang akan datang</div>
                                </div>
                                <div x-data="{ on: true }" @click="on = !on" class="relative inline-flex h-5 w-9 cursor-pointer items-center rounded-full transition-colors duration-300 focus:outline-none" :class="on ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600'"><span class="inline-block h-4 w-4 transform rounded-full bg-white transition duration-300 shadow" :class="on ? 'translate-x-4' : 'translate-x-1'"></span></div>
                            </div>
                            <!-- Toggle 2 -->
                            <div class="flex justify-between items-center gap-4">
                                <div>
                                    <div class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Survei Pembeli</div>
                                    <div class="text-xs text-gray-400">Terima survei untuk memberi penilaian dan saran untuk pelayanan kami yang lebih baik</div>
                                </div>
                                <div x-data="{ on: true }" @click="on = !on" class="relative inline-flex h-5 w-9 cursor-pointer items-center rounded-full transition-colors duration-300 focus:outline-none" :class="on ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600'"><span class="inline-block h-4 w-4 transform rounded-full bg-white transition duration-300 shadow" :class="on ? 'translate-x-4' : 'translate-x-1'"></span></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>


        <!-- TAB 3: MODE LIBUR -->
        <div x-show="tab === 'libur'" class="space-y-6" style="display: none;">
            
            <div class="bg-white dark:bg-[#161b22] rounded shadow-sm border border-gray-200 dark:border-[#30363d] overflow-hidden p-6">
                
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="font-bold text-sm text-gray-800 dark:text-white flex items-center gap-2 mb-1">Mode Libur <span class="bg-gray-100 dark:bg-gray-800 text-gray-400 px-2 py-0.5 rounded text-[10px] font-normal">Nonaktif</span></h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Aktifkan Mode Libur jika tokomu membutuhkan jeda untuk menerima pesanan. Pesanan yang masuk sebelum libur dimulai harus tetap diselesaikan sebelum batas waktu.</p>
                    </div>
                    <button class="bg-error hover:bg-[#d73f22] text-white px-4 py-1.5 font-semibold text-xs rounded transition-colors whitespace-nowrap flex items-center gap-1 shadow-sm"><span class="material-symbols-outlined text-[14px]">add</span> Aktifkan Mode Libur</button>
                </div>

                <div class="bg-gray-50 dark:bg-[#0d1117] border border-gray-200 dark:border-[#30363d] rounded p-4 text-xs text-gray-600 dark:text-gray-400 flex items-center justify-between">
                    <span><span class="font-bold text-gray-800 dark:text-gray-200">Chat auto-reply saat ini:</span> "Terima Kasih telah menghubungi kami. Semua jenis aplikasi bisa di custom."</span>
                    <a href="#" class="text-[#0055aa] dark:text-[#58a6ff] hover:underline">Atur Sekarang</a>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection

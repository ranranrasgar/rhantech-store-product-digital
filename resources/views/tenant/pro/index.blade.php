@extends('layouts.tenant')

@section('title', 'Layanan Toko PRO')

@section('content')
<div class="p-4 md:p-6 lg:p-8 max-w-5xl mx-auto w-full">
    
    @if($store->isPro())
        <!-- Active PRO Dashboard -->
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-3xl p-6 md:p-10 text-white shadow-xl relative overflow-hidden mb-8">
            <div class="absolute inset-0 bg-white/10" style="clip-path: polygon(0 0, 100% 0, 100% 100%, 0 80%);"></div>
            <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/20 backdrop-blur-md border border-white/30 rounded-full text-xs font-bold mb-4 shadow-sm">
                        <span class="material-symbols-outlined text-[16px] text-yellow-300">verified</span>
                        Status Aktif
                    </div>
                    <h1 class="text-3xl md:text-4xl font-black mb-2 flex items-center gap-2">Toko Anda <span class="text-yellow-300">PRO</span> <span class="material-symbols-outlined text-[32px] text-yellow-300">stars</span></h1>
                    <p class="text-white/90 text-sm md:text-base font-medium max-w-lg mb-6">
                        Terima kasih telah menggunakan layanan Toko PRO! Nikmati semua fitur eksklusif dan potongan biaya platform yang lebih rendah.
                    </p>
                    <div class="flex items-center gap-3">
                        <div class="bg-black/20 px-4 py-2 rounded-xl border border-white/10 flex items-center gap-2 backdrop-blur-sm">
                            <span class="material-symbols-outlined text-white/70">event</span>
                            <span class="text-sm font-bold">Berlaku sampai: <span class="text-yellow-200">{{ $store->pro_expires_at ? $store->pro_expires_at->format('d M Y') : 'Selamanya' }}</span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-surface rounded-2xl p-5 border border-outline-variant shadow-sm flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-sky-100 dark:bg-sky-900/30 text-sky-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl">percent</span>
                </div>
                <div>
                    <h3 class="font-bold text-on-surface mb-1">Fee Platform 1%</h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">Potongan penarikan saldo Anda saat ini hanya 1% (lebih rendah dari toko reguler).</p>
                </div>
            </div>
            <div class="bg-surface rounded-2xl p-5 border border-outline-variant shadow-sm flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl">send_to_mobile</span>
                </div>
                <div>
                    <h3 class="font-bold text-on-surface mb-1">WA Broadcast Aktif</h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">Kirim promo langsung ke pelanggan lewat WhatsApp dengan sekali klik.</p>
                </div>
            </div>
            <div class="bg-surface rounded-2xl p-5 border border-outline-variant shadow-sm flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/30 text-purple-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl">work</span>
                </div>
                <div>
                    <h3 class="font-bold text-on-surface mb-1">Modul Portofolio</h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">Pamerkan proyek dan hasil kerja terbaik Anda di halaman toko publik.</p>
                </div>
            </div>
        </div>
        
    @else
        <!-- Upgrade Pitch Dashboard -->
        <div class="text-center mb-16 mt-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-tr from-amber-400 to-yellow-300 text-white shadow-[0_0_40px_rgba(251,191,36,0.4)] mb-6 border-4 border-white dark:border-slate-800">
                <span class="material-symbols-outlined text-[40px]">workspace_premium</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-slate-800 dark:text-white mb-4 tracking-tight">Tingkatkan ke Toko <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-yellow-500">PRO</span></h1>
            <p class="text-slate-500 dark:text-slate-400 text-lg max-w-2xl mx-auto font-medium">Buka seluruh potensi bisnis Anda. Dapatkan fitur eksklusif, bangun kredibilitas, dan tingkatkan penjualan tanpa batas.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 max-w-5xl mx-auto mb-16 items-center">
            <!-- Reguler -->
            <div class="bg-white dark:bg-slate-800/50 rounded-[2rem] p-8 md:p-10 border border-slate-200 dark:border-slate-700/50 shadow-lg relative transform transition-all duration-300 hover:-translate-y-1">
                <div class="text-slate-400 font-bold text-sm mb-3 tracking-widest uppercase">Paket Saat Ini</div>
                <h2 class="text-3xl font-bold text-slate-800 dark:text-white mb-8">Toko Reguler</h2>
                <div class="space-y-5">
                    <div class="flex items-start gap-4">
                        <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5"><span class="material-symbols-outlined text-[14px] font-bold">check</span></div>
                        <span class="text-base text-slate-600 dark:text-slate-300 font-medium">Buka Toko Gratis</span>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5"><span class="material-symbols-outlined text-[14px] font-bold">check</span></div>
                        <span class="text-base text-slate-600 dark:text-slate-300 font-medium">Katalog Produk Standar</span>
                    </div>
                    <div class="flex items-start gap-4 opacity-50 grayscale pt-4 border-t border-slate-100 dark:border-slate-700">
                        <div class="w-6 h-6 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center shrink-0 mt-0.5"><span class="material-symbols-outlined text-[14px] font-bold">close</span></div>
                        <span class="text-base text-slate-500 font-medium line-through">Potongan Payout 1% (Reguler 2.5%)</span>
                    </div>
                    <div class="flex items-start gap-4 opacity-50 grayscale">
                        <div class="w-6 h-6 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center shrink-0 mt-0.5"><span class="material-symbols-outlined text-[14px] font-bold">close</span></div>
                        <span class="text-base text-slate-500 font-medium line-through">Badge & Avatar Animasi PRO</span>
                    </div>
                    <div class="flex items-start gap-4 opacity-50 grayscale">
                        <div class="w-6 h-6 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center shrink-0 mt-0.5"><span class="material-symbols-outlined text-[14px] font-bold">close</span></div>
                        <span class="text-base text-slate-500 font-medium line-through">Custom Dekorasi Warna/Gradient</span>
                    </div>
                    <div class="flex items-start gap-4 opacity-50 grayscale">
                        <div class="w-6 h-6 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center shrink-0 mt-0.5"><span class="material-symbols-outlined text-[14px] font-bold">close</span></div>
                        <span class="text-base text-slate-500 font-medium line-through">WA Broadcast ke Pelanggan</span>
                    </div>
                    <div class="flex items-start gap-4 opacity-50 grayscale">
                        <div class="w-6 h-6 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center shrink-0 mt-0.5"><span class="material-symbols-outlined text-[14px] font-bold">close</span></div>
                        <span class="text-base text-slate-500 font-medium line-through">Modul Portofolio & Project Toko</span>
                    </div>
                </div>
            </div>

            <!-- PRO -->
            <div class="bg-slate-900 rounded-[2rem] p-8 md:p-10 border-2 border-yellow-400/50 shadow-[0_20px_50px_rgba(0,0,0,0.3)] text-white relative transform transition-all duration-300 hover:-translate-y-2 lg:scale-105">
                <div class="absolute inset-0 rounded-[2rem] bg-gradient-to-b from-yellow-400/10 to-transparent pointer-events-none"></div>
                <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-yellow-400 text-slate-900 font-black text-xs px-4 py-1.5 rounded-full shadow-lg shadow-yellow-400/30 uppercase tracking-widest flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">star</span> Rekomendasi
                </div>
                
                <div class="relative z-10">
                    <h2 class="text-4xl font-black mb-2 flex items-center gap-2">
                        Toko <span class="text-yellow-400">PRO</span>
                    </h2>
                    <div class="text-slate-400 text-base mb-8 pb-8 border-b border-slate-700/80">
                        Investasi terbaik untuk dominasi pasar dan kepercayaan klien.
                    </div>
                    
                    <div class="space-y-5 mb-10">
                        <div class="flex items-start gap-4">
                            <div class="w-6 h-6 rounded-full bg-yellow-400/20 text-yellow-400 flex items-center justify-center shrink-0 mt-0.5 border border-yellow-400/30"><span class="material-symbols-outlined text-[14px] font-bold">check</span></div>
                            <span class="text-base text-slate-200 font-medium">Potongan Payout Spesial hanya <strong class="text-yellow-400">1%</strong></span>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-6 h-6 rounded-full bg-yellow-400/20 text-yellow-400 flex items-center justify-center shrink-0 mt-0.5 border border-yellow-400/30"><span class="material-symbols-outlined text-[14px] font-bold">check</span></div>
                            <span class="text-base text-slate-200 font-medium">Badge & Avatar Animasi Premium</span>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-6 h-6 rounded-full bg-yellow-400/20 text-yellow-400 flex items-center justify-center shrink-0 mt-0.5 border border-yellow-400/30"><span class="material-symbols-outlined text-[14px] font-bold">check</span></div>
                            <span class="text-base text-slate-200 font-medium">Dekorasi banner toko tanpa batas</span>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-6 h-6 rounded-full bg-yellow-400/20 text-yellow-400 flex items-center justify-center shrink-0 mt-0.5 border border-yellow-400/30"><span class="material-symbols-outlined text-[14px] font-bold">check</span></div>
                            <span class="text-base text-slate-200 font-medium">Fitur <strong class="text-white">WhatsApp Broadcast</strong> ke pelanggan</span>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-6 h-6 rounded-full bg-yellow-400/20 text-yellow-400 flex items-center justify-center shrink-0 mt-0.5 border border-yellow-400/30"><span class="material-symbols-outlined text-[14px] font-bold">check</span></div>
                            <span class="text-base text-slate-200 font-medium">Modul Portofolio & Galeri Project Toko</span>
                        </div>
                    </div>

                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $company->phone ?? '') }}?text=Halo%20Admin,%20Saya%20ingin%20upgrade%20Toko%20{{ $store->name }}%20ke%20akun%20PRO" target="_blank" class="block w-full py-4 bg-yellow-400 hover:bg-yellow-300 text-slate-900 rounded-2xl font-black text-center shadow-lg shadow-yellow-400/25 transition-all duration-300 flex items-center justify-center gap-2 group">
                        Hubungi Admin untuk Upgrade 
                        <span class="material-symbols-outlined text-[20px] transition-transform duration-300 group-hover:translate-x-1">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
    @endif
    
</div>
@endsection

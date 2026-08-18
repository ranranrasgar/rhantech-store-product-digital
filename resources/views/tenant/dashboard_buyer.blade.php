@extends('layouts.tenant')

@section('title', 'Dashboard')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-8 bg-[#f8fafc] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] transition-colors duration-200">
    <div class="max-w-5xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
                Halo, {{ explode(' ', auth()->user()->name)[0] ?? 'User' }}! 👋
            </h1>
            <p class="text-sm md:text-base text-slate-500 dark:text-slate-400 mt-2 max-w-2xl">
                Selamat datang di Dashboard Anda. Kelola pembelian Anda atau mulai perjalanan baru sebagai kreator produk digital.
            </p>
        </div>

        <!-- Call to Action (Seller Promo) Banner -->
        <div class="relative bg-gradient-to-br from-sky-600 via-blue-600 to-indigo-700 rounded-3xl p-8 md:p-12 overflow-hidden shadow-xl border border-sky-500/20">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 -mt-16 -mr-16 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 -mb-16 -ml-16 w-48 h-48 bg-sky-300/20 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="max-w-xl">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md border border-white/20 text-white text-xs font-bold mb-4">
                        <span class="material-symbols-outlined text-[14px]">rocket_launch</span>
                        Peluang Penghasilan Baru
                    </div>
                    <h2 class="text-3xl md:text-4xl font-black text-white leading-tight mb-4">
                        Ubah Karya Digital Anda Menjadi <span class="text-sky-200">Penghasilan Nyata</span>
                    </h2>
                    <p class="text-sky-100 text-sm md:text-base mb-8 leading-relaxed">
                        Punya *source code*, *ebook*, *template* desain, atau aplikasi buatan sendiri? Jangan biarkan menumpuk di laptop! Buka toko Anda sekarang secara gratis dan jangkau ribuan pembeli di platform kami.
                    </p>
                    
                    <a href="{{ route('tenant.store.index') }}" class="inline-flex items-center gap-2 bg-white text-sky-700 hover:bg-sky-50 px-6 py-3.5 rounded-xl font-bold shadow-lg transition-all transform hover:-translate-y-0.5">
                        <span class="material-symbols-outlined">storefront</span>
                        Buka Toko Gratis Sekarang
                    </a>
                </div>
                
                <div class="hidden md:flex flex-col items-center justify-center relative">
                    <!-- Icon/Illustration placeholder -->
                    <div class="w-48 h-48 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center border-4 border-white/20 shadow-2xl relative z-10">
                        <span class="material-symbols-outlined text-8xl text-white drop-shadow-md">account_balance_wallet</span>
                    </div>
                    <!-- Floating badges -->
                    <div class="absolute top-0 -left-6 bg-emerald-500 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-lg rotate-[-12deg] z-20">
                        Rp 10.000.000+
                    </div>
                    <div class="absolute bottom-4 -right-4 bg-amber-500 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-lg rotate-[10deg] z-20 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">trending_up</span> Laris!
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links / Shortcuts -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            
            <!-- Pembelian Card -->
            <a href="{{ route('tenant.purchases.index') }}" class="group bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                <div class="flex items-start justify-between mb-8">
                    <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-[24px]">shopping_bag</span>
                    </div>
                    <span class="material-symbols-outlined text-slate-300 dark:text-slate-600 group-hover:text-emerald-500 transition-colors">arrow_forward</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">Riwayat Pembelian</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Lihat semua pesanan produk digital Anda dan download ulang kapan saja.</p>
                </div>
            </a>

            <!-- Katalog Card -->
            <a href="{{ route('products.index') }}" class="group bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                <div class="flex items-start justify-between mb-8">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-[24px]">explore</span>
                    </div>
                    <span class="material-symbols-outlined text-slate-300 dark:text-slate-600 group-hover:text-purple-500 transition-colors">arrow_forward</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">Eksplorasi Produk</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Temukan *source code* dan aplikasi menarik lainnya di katalog kami.</p>
                </div>
            </a>

        </div>

    </div>
</div>
@endsection

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    @include('components.theme-init')
    @include('components.pwa-head')
    <title>@yield('title', $store->name ?? 'Toko Digital')</title>
    <meta name="description" content="@yield('meta_description', $store->description ?? 'Toko digital resmi penyedia produk dan template terpercaya.')"/>
    <meta property="og:title" content="@yield('title', $store->name ?? 'Toko Digital')"/>
    <meta property="og:description" content="@yield('meta_description', $store->description ?? 'Toko digital resmi penyedia produk dan template terpercaya.')"/>
    <meta property="og:image" content="@yield('meta_image', $store->logo ? asset('storage/'.$store->logo) : '')"/>
    <meta property="og:type" content="website"/>
    <link rel="icon" type="image/png" href="{{ $store->logo ? asset('storage/'.$store->logo) : (isset($company) && $company->favicon ? '/storage/'.$company->favicon : '/favicon.ico') }}" />
    <link rel="shortcut icon" type="image/png" href="{{ $store->logo ? asset('storage/'.$store->logo) : (isset($company) && $company->favicon ? '/storage/'.$company->favicon : '/favicon.ico') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- Material Symbols: non-blocking preload --}}
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block" onload="this.rel='stylesheet'" />
    <noscript><link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block" rel="stylesheet" /></noscript>
    {{-- CDN Tailwind dihapus: sudah di-build oleh Vite (lihat @vite di bawah) --}}
    @include('components.theme-styles')
    @vite(['resources/css/app.css'])
    <style>
        .material-symbols-outlined {
            font-family: 'Material Symbols Outlined';
            font-weight: normal;
            font-style: normal;
            font-size: 24px;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            display: inline-block;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            -webkit-font-feature-settings: 'liga';
            -webkit-font-smoothing: antialiased;
        }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @livewireStyles
</head>
<body class="bg-background text-on-background antialiased flex flex-col min-h-screen">

    {{-- Top Store Navbar --}}
    <nav x-data="{ mobileMenuOpen: false }" class="bg-surface/90 dark:bg-slate-900/90 backdrop-blur-md sticky top-0 w-full z-50 border-b border-outline-variant/50 shadow-xs transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
            
            {{-- Left: Store Brand Identity (Hanya Desktop) --}}
            <div class="hidden md:flex items-center gap-2.5 min-w-0 pr-2">
                <a href="{{ route('store.show', $store->slug) }}" class="flex items-center gap-2.5 group min-w-0">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl overflow-hidden bg-surface-container border border-outline-variant shrink-0 shadow-xs flex items-center justify-center">
                        @if(!empty($store->logo))
                            <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="w-full h-full object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($store->name) }}&background=0D8ABC&color=fff&size=80" alt="{{ $store->name }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-1">
                            <span class="font-black text-sm sm:text-lg text-on-surface dark:text-white group-hover:text-primary transition-colors tracking-tight leading-none truncate">
                                {{ $store->name }}
                            </span>
                            <span class="material-symbols-outlined text-[15px] sm:text-[16px] text-primary shrink-0" title="Verified Store">verified</span>
                        </div>
                        <p class="text-[10px] sm:text-[11px] text-on-surface-variant font-medium mt-0.5 truncate max-w-[120px] sm:max-w-xs">
                            {{ $store->description ? Str::limit($store->description, 30) : 'Official Store' }}
                        </p>
                    </div>
                </a>
            </div>

            {{-- Mobile: Kotak Pencarian Memanjang (Menggantikan identitas toko yang duplikat) --}}
            <div class="md:hidden flex-1 min-w-0 mr-2">
                <form action="{{ route('store.show', $store->slug) }}" method="GET" class="relative flex items-center w-full">
                    <div class="relative w-full flex items-center">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-primary">
                            <span class="material-symbols-outlined text-[18px] leading-none">search</span>
                        </div>
                        <input type="text" 
                               name="q" 
                               value="{{ request('q', request('search', '')) }}"
                               placeholder="Cari produk di {{ $store->name }}..." 
                               class="w-full pl-9 pr-8 py-2 text-xs bg-surface-container/90 dark:bg-slate-800/90 border border-outline-variant rounded-full text-on-surface placeholder:text-on-surface-variant/70 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all font-medium shadow-2xs">
                        @if(request('q') || request('search'))
                            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center">
                                <a href="{{ route('store.show', $store->slug) }}" class="text-on-surface-variant hover:text-error flex items-center justify-center" title="Hapus">
                                    <span class="material-symbols-outlined text-[16px] leading-none">cancel</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Right: Return to Public Store & User Actions --}}
            <div class="flex items-center gap-1.5 sm:gap-3 shrink-0">
                {{-- Tombol Kembali ke Marketplace / Store Publik --}}
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center gap-1.5 p-2 sm:px-3.5 sm:py-1.5 rounded-xl sm:rounded-lg bg-surface-container border border-outline-variant hover:border-primary hover:text-primary text-xs font-bold text-on-surface transition-all shadow-xs shrink-0" 
                   title="Jelajahi Toko & Produk Lain">
                    <span class="material-symbols-outlined text-[18px] sm:text-[16px] text-primary">storefront</span>
                    <span class="hidden sm:inline">Jelajahi Toko Lain</span>
                </a>

                @guest
                    <a href="{{ route('login') }}" class="text-xs font-bold text-on-surface-variant hover:text-on-surface px-2 py-1.5 rounded-lg transition-colors shrink-0">
                        Masuk
                    </a>
                @else
                    {{-- User Dropdown --}}
                    @php
                        $userAvatar = auth()->user()->avatar 
                            ? (Str::startsWith(auth()->user()->avatar, 'http') ? auth()->user()->avatar : asset('storage/' . auth()->user()->avatar))
                            : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=0284c7&color=fff';
                    @endphp
                    <div class="relative shrink-0" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false" class="flex items-center focus:outline-none rounded-full ring-2 ring-transparent hover:ring-primary/20 transition-all p-0.5">
                            <img src="{{ $userAvatar }}" 
                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0284c7&color=fff';" 
                                 alt="{{ auth()->user()->name }}" 
                                 class="w-8 h-8 rounded-full object-cover border border-outline-variant/60">
                        </button>
                        
                        <div x-show="open" style="display: none;" class="absolute right-0 mt-2 w-52 bg-surface dark:bg-slate-800 border border-outline-variant rounded-xl shadow-none py-2 z-50">
                            <div class="px-4 py-2 border-b border-outline-variant">
                                <p class="text-xs font-bold text-on-surface dark:text-white truncate">{{ auth()->user()->name }}</p>
                                <p class="text-[11px] text-on-surface-variant truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('tenant.profile.index') }}" class="flex items-center gap-2 px-4 py-2 text-xs text-on-surface dark:text-slate-200 hover:bg-surface-container transition-colors">
                                <span class="material-symbols-outlined text-[16px]">settings</span> Profil &amp; Pengaturan
                            </a>
                            <a href="{{ route('tenant.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-xs text-on-surface dark:text-slate-200 hover:bg-surface-container transition-colors">
                                <span class="material-symbols-outlined text-[16px]">storefront</span> Dashboard Toko
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="border-t border-outline-variant mt-1 pt-1">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-xs text-error hover:bg-error/10 transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">logout</span> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>

        </div>
    </nav>

    {{-- Main Store View Body --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Store Specific Footer --}}
    <footer class="bg-surface dark:bg-slate-900 border-t border-outline-variant/60 py-8 md:py-12 mt-auto text-on-surface-variant text-xs">
        <!-- Desktop Footer (Tetap lengkap) -->
        <div class="hidden md:grid max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid-cols-3 gap-8 items-center">
            
            {{-- Store Info --}}
            <div class="space-y-2">
                <div class="flex items-center gap-2.5">
                    @if(!empty($store->logo))
                        <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="w-7 h-7 rounded-lg object-cover">
                    @endif
                    <span class="font-black text-base text-on-surface dark:text-white">{{ $store->name }}</span>
                </div>
                <p class="text-on-surface-variant leading-relaxed max-w-sm">
                    {{ $store->description ?: 'Toko resmi mitra platform. Menyediakan berbagai solusi dan aset digital terbaik untuk kebutuhan Anda.' }}
                </p>
                <div class="text-[11px] text-on-surface-variant/70">
                    © {{ date('Y') }} {{ $store->name }}. Hak Cipta Dilindungi.
                </div>
            </div>

            {{-- Quick Links Store --}}
            <div class="flex flex-col gap-2">
                <h4 class="font-bold text-xs uppercase tracking-wider text-on-surface dark:text-white">Navigasi Toko</h4>
                <div class="flex flex-col gap-1.5">
                    <a href="{{ route('store.show', $store->slug) }}" class="hover:text-primary transition-colors">Produk &amp; Katalog {{ $store->name }}</a>
                    <a href="{{ route('help.index') }}" class="hover:text-primary transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">policy</span>
                        Kebijakan &amp; Ketentuan Platform
                    </a>
                    <a href="{{ route('products.index') }}" class="hover:text-primary transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">arrow_back</span>
                        Cari Produk &amp; Toko Lainnya di Marketplace
                    </a>
                </div>
            </div>

            {{-- Security & Support --}}
            <div class="bg-surface-container dark:bg-slate-800/50 p-4 rounded-xl border border-outline-variant/50 space-y-2">
                <div class="flex items-center gap-2 text-on-surface dark:text-white font-bold text-xs">
                    <span class="material-symbols-outlined text-[18px] text-emerald-500">verified_user</span>
                    Transaksi &amp; Garansi Aman
                </div>
                <p class="text-[11px] leading-relaxed">
                    Setiap pembelian produk dari toko ini diproses dan dilindungi secara instan melalui gateway pembayaran otomatis platform.
                </p>
            </div>

        </div>

        <!-- Mobile Footer (Bagian info toko yang berulang dihilangkan, ditambahkan link kebijakan platform) -->
        <div class="md:hidden max-w-md mx-auto px-4 space-y-3.5 text-center">
            <div class="bg-surface-container dark:bg-slate-800/50 p-3.5 rounded-2xl border border-outline-variant/50 space-y-1.5 text-left">
                <div class="flex items-center gap-2 text-on-surface dark:text-white font-bold text-xs">
                    <span class="material-symbols-outlined text-[18px] text-emerald-500">verified_user</span>
                    Transaksi &amp; Garansi Aman
                </div>
                <p class="text-[11px] leading-relaxed text-on-surface-variant">
                    Setiap transaksi dilindungi sistem escrow otomatis platform dengan jaminan unduhan instan.
                </p>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-2 text-xs pt-0.5">
                <a href="{{ route('help.index') }}" class="inline-flex items-center gap-1 text-primary hover:underline font-bold">
                    <span class="material-symbols-outlined text-[16px]">policy</span>
                    Kebijakan &amp; Ketentuan Platform
                </a>
                <span class="text-outline-variant">•</span>
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1 hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[14px]">storefront</span>
                    Jelajahi Produk Lain
                </a>
            </div>

            <div class="text-[11px] text-on-surface-variant/60">
                © {{ date('Y') }} {{ config('app.name', 'R-Tech') }}. Hak Cipta Dilindungi.
            </div>
        </div>
    </footer>

    @include('components.theme-manager')
    @auth
        @include('components.firebase-init')
        <div x-data="firebaseManager" x-init="initFirebase()" style="display:none;"></div>
    @endauth
    @include('components.chat-widget')
    @include('components.new-member-bonus-bubble')
    @include('components.pwa-install-prompt')
    @livewireScripts
</body>
</html>

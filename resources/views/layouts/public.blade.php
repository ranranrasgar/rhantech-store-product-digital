<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0, viewport-fit=cover" name="viewport"/>
    @include('components.theme-init')
    @include('components.pwa-head')
    <title>@yield('title', ($company->company_name ?? 'rhantech') . ' - We Build Digital Experiences')</title>
    <meta name="description" content="@yield('meta_description', $company->about_text ?? 'We build scalable, modern, and impactful digital solutions for businesses worldwide.')"/>
    <meta name="keywords" content="@yield('meta_keywords', 'software house indonesia, jasa pembuatan website, jual source code, web development, mobile app development, UI/UX design')"/>
    <meta name="author" content="{{ $company->company_name ?? 'rhantech' }}"/>
    <meta name="robots" content="@yield('meta_robots', 'index, follow, max-image-preview:large')"/>
    <link rel="canonical" href="@yield('canonical_url', url()->current())" />

    <meta property="og:locale" content="id_ID"/>
    <meta property="og:type" content="@yield('og_type', 'website')"/>
    <meta property="og:site_name" content="{{ $company->company_name ?? 'rhantech' }}"/>
    <meta property="og:title" content="@yield('title', ($company->company_name ?? 'rhantech') . ' - We Build Digital Experiences')"/>
    <meta property="og:description" content="@yield('meta_description', $company->about_text ?? 'We build scalable, modern, and impactful digital solutions for businesses worldwide.')"/>
    <meta property="og:url" content="@yield('canonical_url', url()->current())"/>
    <meta property="og:image" content="@yield('meta_image', isset($company) && $company->logo ? asset('storage/'.$company->logo) : '')"/>

    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:title" content="@yield('title', ($company->company_name ?? 'rhantech') . ' - We Build Digital Experiences')"/>
    <meta name="twitter:description" content="@yield('meta_description', $company->about_text ?? 'We build scalable, modern, and impactful digital solutions for businesses worldwide.')"/>
    <meta name="twitter:image" content="@yield('meta_image', isset($company) && $company->logo ? asset('storage/'.$company->logo) : '')"/>
    @yield('schema_json_ld')
    <link rel="icon" type="image/png" href="{{ isset($company) && $company->favicon ? '/storage/'.$company->favicon : '/favicon.ico' }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "secondary-container": "#57dffe",
                        "on-tertiary-container": "#7073ff",
                        "surface-container-low": "rgb(var(--theme-surface-low) / <alpha-value>)",
                        "tertiary-fixed-dim": "#c0c1ff",
                        "secondary-fixed-dim": "#fb923c",
                        "surface-variant": "rgb(var(--theme-surface-variant) / <alpha-value>)",
                        "background": "rgb(var(--theme-background) / <alpha-value>)",
                        "on-secondary-container": "#0369a1",
                        "error-container": "#ffdad6",
                        "surface-dim": "#cbdbf5",
                        "on-secondary-fixed-variant": "#075985",
                        "surface-container-lowest": "rgb(var(--theme-surface-lowest) / <alpha-value>)",
                        "secondary": "#ea580c",
                        "surface-container-highest": "rgb(var(--theme-surface-highest) / <alpha-value>)",
                        "tertiary-container": "#07006c",
                        "on-primary": "#ffffff",
                        "inverse-surface": "#213145",
                        "on-secondary": "#ffffff",
                        "on-error-container": "#93000a",
                        "inverse-on-surface": "#eaf1ff",
                        "on-primary-fixed-variant": "#3f465c",
                        "on-error": "#ffffff",
                        "inverse-primary": "#bec6e0",
                        "outline": "rgb(var(--theme-outline) / <alpha-value>)",
                        "outline-variant": "rgb(var(--theme-outline-variant) / <alpha-value>)",
                        "surface": "rgb(var(--theme-surface) / <alpha-value>)",
                        "surface-tint": "#565e74",
                        "surface-container-high": "rgb(var(--theme-surface-high) / <alpha-value>)",
                        "on-background": "rgb(var(--theme-on-background) / <alpha-value>)",
                        "on-surface": "rgb(var(--theme-on-surface) / <alpha-value>)",
                        "on-primary-container": "rgb(var(--theme-on-primary-container) / <alpha-value>)",
                        "tertiary": "#000000",
                        "primary": "rgb(var(--theme-primary) / <alpha-value>)",
                        "on-secondary-fixed": "#001f26",
                        "tertiary-fixed": "#e1e0ff",
                        "error": "#ba1a1a",
                        "secondary-fixed": "#acedff",
                        "on-tertiary": "#ffffff",
                        "on-primary-fixed": "#131b2e",
                        "primary-fixed-dim": "#bec6e0",
                        "on-tertiary-fixed-variant": "#2f2ebe",
                        "on-tertiary-fixed": "#07006c",
                        "on-surface-variant": "rgb(var(--theme-on-surface-variant) / <alpha-value>)",
                        "primary-fixed": "#dae2fd",
                        "surface-bright": "#f8f9ff",
                        "primary-container": "#131b2e",
                        "surface-container": "rgb(var(--theme-surface-container) / <alpha-value>)"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "2xl": "80px",
                        "xl": "48px",
                        "md": "16px",
                        "container-max": "1280px",
                        "sm": "8px",
                        "xs": "4px",
                        "unit": "4px",
                        "lg": "24px",
                        "gutter": "24px"
                    },
                    "fontFamily": {
                        "sans": ["'Plus Jakarta Sans'", "-apple-system", "BlinkMacSystemFont", "'Segoe UI'", "sans-serif"],
                        "headline-xl": ["'Plus Jakarta Sans'", "-apple-system", "BlinkMacSystemFont", "'Segoe UI'", "sans-serif"],
                        "body-md": ["'Plus Jakarta Sans'", "-apple-system", "BlinkMacSystemFont", "'Segoe UI'", "sans-serif"],
                        "headline-lg": ["'Plus Jakarta Sans'", "-apple-system", "BlinkMacSystemFont", "'Segoe UI'", "sans-serif"],
                        "display-lg-mobile": ["'Plus Jakarta Sans'", "-apple-system", "BlinkMacSystemFont", "'Segoe UI'", "sans-serif"],
                        "body-lg": ["'Plus Jakarta Sans'", "-apple-system", "BlinkMacSystemFont", "'Segoe UI'", "sans-serif"],
                        "code-sm": ["ui-monospace, SFMono-Regular, SF Mono, Menlo, Consolas, Liberation Mono, monospace"],
                        "label-md": ["'Plus Jakarta Sans'", "-apple-system", "BlinkMacSystemFont", "'Segoe UI'", "sans-serif"],
                        "display-lg": ["'Plus Jakarta Sans'", "-apple-system", "BlinkMacSystemFont", "'Segoe UI'", "sans-serif"]
                    },
                    "fontSize": {
                        "headline-xl": ["48px", { "lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "600" }],
                        "body-md": ["14px", { "lineHeight": "21px", "fontWeight": "400" }],
                        "headline-lg": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                        "display-lg-mobile": ["40px", { "lineHeight": "48px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "body-lg": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "code-sm": ["13px", { "lineHeight": "18px", "fontWeight": "400" }],
                        "label-md": ["14px", { "lineHeight": "20px", "fontWeight": "600" }],
                        "display-lg": ["72px", { "lineHeight": "80px", "letterSpacing": "-0.04em", "fontWeight": "700" }]
                    }
                }
            }
        }
    </script>
    @include('components.theme-styles')
    <style>
        .material-symbols-outlined {
            font-family: 'Material Symbols Outlined' !important;
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
            font-feature-settings: 'liga' 1;
            -webkit-font-feature-settings: 'liga';
            -webkit-font-smoothing: antialiased;
        }
    </style>
@livewireStyles
</head>
<body class="bg-background text-on-background font-body-md text-body-md antialiased selection:bg-primary-container selection:text-on-secondary-container flex flex-col min-h-screen">

<style>
/* ── Modern Tech Header (Synchronized with Products / Marketplace) ── */
.site-header {
    background: #0a1628;
    position: fixed; top: 0; left: 0; right: 0;
    z-index: 50;
    border-bottom: 1px solid rgba(255,255,255,0.08);
    padding-top: env(safe-area-inset-top, 0px);
}
.site-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: url('/images/batik-pattern.svg');
    background-repeat: repeat;
    background-size: 110px 110px;
    opacity: 0.10;
    pointer-events: none;
    z-index: 0;
}
.site-header > * {
    position: relative;
    z-index: 1;
}

.header-logo {
    font-size: 22px; font-weight: 900;
    letter-spacing: -0.5px;
    color: #ffffff;
    text-decoration: none;
    display: flex; align-items: center; gap: 8px;
    transition: opacity 0.2s;
}
.header-logo:hover { opacity: 0.85; }
.header-logo .logo-dot {
    width: 8px; height: 8px;
    background: #ea580c;
    border-radius: 50%;
    box-shadow: 0 0 10px rgba(2, 132, 199, 0.4);
    animation: pulse-dot 2s infinite;
}
@keyframes pulse-dot {
    0%, 100% { box-shadow: 0 0 6px rgba(2, 132, 199, 0.4); }
    50%       { box-shadow: 0 0 12px rgba(2, 132, 199, 0.7); }
}
.search-bar-wrap {
    display: flex; align-items: center;
    background: rgba(255,255,255,0.08);
    border: 1.5px solid rgba(255,255,255,0.15);
    border-radius: 12px;
    overflow: hidden;
    transition: border-color 0.2s, background 0.2s;
}
.search-bar-wrap:focus-within {
    border-color: #ea580c;
    background: rgba(255,255,255,0.12);
    box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.2);
}
.search-bar-wrap input {
    background: transparent;
    border: none !important;
    outline: none !important;
    box-shadow: none !important;
    color: #fff;
    font-size: 14px;
    padding: 10px 16px;
    flex: 1;
}
.search-bar-wrap input::placeholder { color: rgba(255,255,255,0.45); }
.search-bar-wrap button {
    background: #ea580c;
    border: none; color: #fff;
    padding: 10px 18px;
    cursor: pointer; transition: background 0.15s;
    display: flex; align-items: center; justify-content: center;
    line-height: 1;
}
.search-bar-wrap button .material-symbols-outlined {
    line-height: 1;
    display: block;
}
.search-bar-wrap button:hover { background: #0369a1; }
.header-action-btn {
    display: flex; align-items: center; gap: 6px;
    color: rgba(255,255,255,0.75);
    font-size: 13px; font-weight: 500;
    padding: 6px 14px;
    border-radius: 8px;
    transition: all 0.18s;
    text-decoration: none;
    white-space: nowrap;
}
.header-action-btn:hover { background: rgba(255,255,255,0.1); color: #fff; }
.header-action-btn.primary {
    background: #ea580c;
    color: #fff;
    font-weight: 700;
}
.header-action-btn.primary:hover { background: #0369a1; }
.search-tag {
    color: rgba(255,255,255,0.55); font-size: 11.5px;
    transition: color 0.15s;
    cursor: pointer; text-decoration: none;
}
.search-tag:hover { color: #fb923c; }
.search-tag-sep { color: rgba(255,255,255,0.2); margin: 0 2px; }

/* Responsive fallbacks to prevent header elements duplication before Tailwind CDN loads */
@media (min-width: 768px) {
    .site-header.md\:hidden,
    header.md\:hidden,
    .header-action-btn.md\:hidden,
    .md\:hidden {
        display: none !important;
    }
}
@media (max-width: 767px) {
    .site-header .hidden.md\:flex,
    .hidden.md\:flex,
    .hidden.md\:block {
        display: none !important;
    }
}
</style>

    <!-- Desktop TopNavBar -->
    <nav x-data="{ mobileMenuOpen: false }" aria-label="Main Navigation" class="hidden md:block bg-surface/80 dark:bg-surface-container-lowest/80 backdrop-blur-md text-primary dark:text-on-surface font-headline-lg text-headline-lg font-body-md text-body-md font-label-md text-label-md fixed top-0 w-full z-50 border-b border-outline-variant/30">
        <div class="flex justify-between items-center px-lg py-md max-w-container-max mx-auto">
            <a aria-label="{{ $company->company_name ?? 'rhantech' }} Home" class="flex items-center gap-2 text-body-lg font-headline-xl font-bold text-on-background dark:text-white" href="{{ url('/') }}" wire:navigate>
                <img src="{{ isset($company) && $company->logo ? asset('storage/' . $company->logo) : asset('logo.png') }}" alt="{{ $company->company_name ?? 'rhantech' }}" class="h-8 w-auto">
                {{ $company->company_name ?? 'rhantech' }}
            </a>
            <div class="hidden md:flex items-center gap-lg nav-links">
                <a class="nav-link {{ request()->is('/') ? 'active text-sky-600 dark:text-sky-400 font-bold' : 'text-on-surface-variant dark:text-on-surface-variant/80' }} hover:text-sky-600 dark:hover:text-sky-400 transition-colors duration-200" href="{{ url('/#home') }}">Home</a>
                <a class="nav-link {{ request()->routeIs('about') ? 'active text-sky-600 dark:text-sky-400 font-bold' : 'text-on-surface-variant dark:text-on-surface-variant/80' }} hover:text-sky-600 dark:hover:text-sky-400 transition-colors duration-200" href="{{ route('about') }}" wire:navigate>About</a>
                <a class="nav-link text-on-surface-variant dark:text-on-surface-variant/80 hover:text-sky-600 dark:hover:text-sky-400 transition-colors duration-200" href="{{ url('/#services') }}">Services</a>
                <a class="nav-link {{ request()->routeIs('projects.*') ? 'active text-sky-600 dark:text-sky-400 font-bold' : 'text-on-surface-variant dark:text-on-surface-variant/80' }} hover:text-sky-600 dark:hover:text-sky-400 transition-colors duration-200" href="{{ route('projects.index') }}" wire:navigate>Portfolio</a>
                <a class="nav-link {{ request()->routeIs('products.*') || request()->routeIs('checkout.*') ? 'active text-sky-600 dark:text-sky-400 font-bold' : 'text-on-surface-variant dark:text-on-surface-variant/80' }} hover:text-sky-600 dark:hover:text-sky-400 transition-colors duration-200" href="{{ route('products.index') }}">Store</a>
                <a class="nav-link {{ request()->routeIs('clients.*') ? 'active text-sky-600 dark:text-sky-400 font-bold' : 'text-on-surface-variant dark:text-on-surface-variant/80' }} hover:text-sky-600 dark:hover:text-sky-400 transition-colors duration-200" href="{{ route('clients.index') }}" wire:navigate>Clients</a>
                <a class="nav-link {{ request()->routeIs('contact') ? 'active text-sky-600 dark:text-sky-400 font-bold' : 'text-on-surface-variant dark:text-on-surface-variant/80' }} hover:text-sky-600 dark:hover:text-sky-400 transition-colors duration-200" href="{{ url('/contact') }}" wire:navigate>Contact</a>
            </div>
            <div class="flex items-center gap-2">
                @guest
                    <a class="text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-200 hover:text-sky-600 dark:hover:text-sky-400 px-3 py-2 transition-colors flex items-center gap-1" href="{{ route('login') }}">
                        <span class="material-symbols-outlined text-[17px]">login</span>
                        <span>Login</span>
                    </a>
                    <a class="hidden md:inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white rounded-xl font-bold text-xs sm:text-sm active:scale-95 transition-all duration-150 shadow-none" href="{{ route('register') }}">
                        <span class="material-symbols-outlined text-[17px]">rocket_launch</span>
                        <span>Buat Website / Project</span>
                    </a>
                @else
                    <div class="flex items-center gap-3 ml-2">
                        <!-- Notification Bell -->
                        <x-navbar-notification-bell role="buyer" />

                        <!-- User Avatar Dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button type="button" @click="open = !open" class="flex items-center gap-2 focus:outline-none rounded-full ring-2 ring-transparent hover:ring-primary/20 transition-all cursor-pointer">
                                <x-user-avatar class="w-9 h-9" />
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="open" x-cloak style="display: none;" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-56 bg-surface-container-lowest border border-outline-variant rounded-md shadow-none py-2 z-50">
                                <a href="{{ route('tenant.profile.index') }}" class="block px-4 py-3 border-b border-outline-variant/50 mb-1 hover:bg-surface-container-low transition-colors">
                                    <p class="text-sm font-bold text-on-surface truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-on-surface-variant truncate mb-1">{{ auth()->user()->email }}</p>
                                    <div class="flex items-center gap-1 text-[11px] text-primary font-semibold">
                                        <span class="material-symbols-outlined text-[12px]">settings</span> Profil &amp; Pengaturan
                                    </div>
                                </a>
                                
                                @if(strtolower(auth()->user()->role ?? '') === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-on-surface hover:bg-surface-container-low transition-colors">
                                    <span class="material-symbols-outlined text-[1.1rem]">admin_panel_settings</span> Dashboard Admin
                                </a>
                                @endif
                                
                                <a href="{{ route('tenant.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-on-surface hover:bg-surface-container-low transition-colors">
                                    <span class="material-symbols-outlined text-[1.1rem]">storefront</span> Dashboard Toko
                                </a>

                                <a href="{{ route('tenant.purchases.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-on-surface hover:bg-surface-container-low transition-colors">
                                    <span class="material-symbols-outlined text-[1.1rem]">receipt_long</span> Riwayat Pembelian
                                </a>
                                
                                <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t border-outline-variant/50 pt-1">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-sm text-error hover:bg-error/10 transition-colors">
                                        <span class="material-symbols-outlined text-[1.1rem]">logout</span> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </nav>

    <!-- Mobile Header (1-Row, block md:hidden) -->
    <header class="block md:hidden site-header fixed top-0 left-0 right-0 z-50">
        <div class="px-3 py-2 flex items-center gap-2">
            <!-- Search Input (flex-1) with Live Suggest for Store / Account / Product -->
            <div class="flex-1 min-w-0 relative"
                 x-data="{
                     query: '{{ addslashes(request('search')) }}',
                     results: null,
                     open: false,
                     loading: false,
                     fetchSuggest() {
                         const q = this.query.trim();
                         if (q.length < 2) {
                             this.results = null;
                             this.open = false;
                             return;
                         }
                         this.loading = true;
                         fetch('{{ route('api.search.suggest') }}?q=' + encodeURIComponent(q))
                             .then(res => res.json())
                             .then(data => {
                                 this.results = data;
                                 this.open = (data.stores && data.stores.length > 0) || (data.products && data.products.length > 0);
                             })
                             .catch(() => { this.results = null; this.open = false; })
                             .finally(() => { this.loading = false; });
                     }
                 }"
                 @click.outside="open = false"
                 @keydown.escape.window="open = false">
                <form action="{{ route('products.index') }}" method="GET" class="m-0">
                    <div class="search-bar-wrap !border-white/20 !bg-white/10 focus-within:!border-[#ea580c]">
                        <input type="text" name="search" x-model="query"
                            @input.debounce.250ms="fetchSuggest()"
                            @focus="if(query.trim().length >= 2) fetchSuggest()"
                            placeholder="Cari produk, toko, akun..."
                            class="!py-2 !px-3 !text-xs"
                            autocomplete="off">
                        <button type="submit" aria-label="Cari" class="!py-2 !px-3 shrink-0">
                            <span class="material-symbols-outlined text-[18px]">search</span>
                        </button>
                    </div>
                </form>

                <!-- Live Suggest Dropdown -->
                <div x-show="open" 
                     x-cloak
                     style="display: none;"
                     class="absolute top-full left-0 right-0 mt-1.5 bg-white dark:bg-slate-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-none overflow-hidden z-50 text-xs">
                    
                    <!-- Toko / Akun Suggestion -->
                    <template x-if="results && results.stores && results.stores.length > 0">
                        <div class="p-2 border-b border-slate-100 dark:border-slate-800 bg-sky-50/50 dark:bg-sky-950/20">
                            <div class="text-[10px] font-bold uppercase tracking-wider text-sky-600 dark:text-sky-400 px-1 mb-1.5 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[13px]">storefront</span>
                                <span>Toko / Akun</span>
                            </div>
                            <div class="space-y-1">
                                <template x-for="st in results.stores" :key="'store-'+st.id">
                                    <a :href="st.url" class="flex items-center justify-between gap-2 p-1.5 rounded-lg hover:bg-white dark:hover:bg-slate-800 transition-colors">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <img :src="st.logo" class="w-6 h-6 rounded-lg object-cover border border-zinc-200 dark:border-zinc-800 shrink-0">
                                            <div class="min-w-0">
                                                <div class="font-bold text-zinc-800 dark:text-zinc-100 truncate text-[11px]" x-text="st.name"></div>
                                                <div class="text-[9px] text-slate-400 font-mono" x-text="'/@' + st.slug"></div>
                                            </div>
                                        </div>
                                        <span class="text-[10px] text-sky-600 dark:text-sky-400 font-bold shrink-0 flex items-center gap-0.5">
                                            <span>Lihat Toko</span>
                                            <span class="material-symbols-outlined text-[11px]">arrow_forward</span>
                                        </span>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Produk Suggestion -->
                    <template x-if="results && results.products && results.products.length > 0">
                        <div class="p-2">
                            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-1 mb-1.5 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[13px]">inventory_2</span>
                                <span>Produk</span>
                            </div>
                            <div class="space-y-1">
                                <template x-for="pr in results.products" :key="'prod-'+pr.id">
                                    <a :href="pr.url" class="flex items-center justify-between gap-2 p-1.5 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <template x-if="pr.image">
                                                <img :src="pr.image" class="w-6 h-6 rounded-md object-cover border border-zinc-200 dark:border-zinc-800 shrink-0">
                                            </template>
                                            <div class="font-medium text-slate-700 dark:text-slate-200 truncate text-[11px]" x-text="pr.name"></div>
                                        </div>
                                        <span class="font-bold text-[#ea580c] text-[10px] shrink-0" x-text="pr.price_formatted"></span>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- View all link -->
                    <a :href="'{{ route('products.index') }}?search=' + encodeURIComponent(query)"
                       class="block py-2 px-3 text-center bg-zinc-50 dark:bg-zinc-900/80 hover:bg-slate-100 dark:hover:bg-slate-800 text-sky-600 dark:text-sky-400 font-bold text-[11px] border-t border-slate-100 dark:border-slate-800">
                        <span x-text="'Lihat semua hasil untuk &quot;' + query + '&quot;'"></span> →
                    </a>
                </div>
            </div>

            <!-- Keranjang & Tombol Masuk / Akun (Side by side with Search) -->
            <div class="flex items-center gap-1.5 shrink-0">
                @php $cartCount = count(session('cart', [])); @endphp
                <a href="{{ route('cart.index') }}" class="header-action-btn relative !p-2 !rounded-xl !bg-white/10 hover:!bg-white/20 border border-white/10 transition-all flex items-center justify-center" title="Keranjang">
                    <span class="material-symbols-outlined text-[20px] text-white">shopping_cart</span>
                    <span data-cart-count
                        class="absolute -top-1 -right-1 bg-sky-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full min-w-[16px] text-center leading-none shadow"
                        style="{{ $cartCount > 0 ? '' : 'display:none' }}">{{ $cartCount }}</span>
                </a>

                @guest
                    <a href="{{ route('login') }}" class="header-action-btn primary text-xs !py-2 !px-3 !rounded-xl font-bold whitespace-nowrap shadow-none">Login</a>
                @else
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button type="button"
                                @click="open = !open" 
                                class="flex items-center focus:outline-none p-0.5 rounded-full ring-2 ring-sky-500/40 cursor-pointer active:scale-95 transition-transform"
                                aria-haspopup="true"
                                :aria-expanded="open"
                                title="Menu Akun">
                            <x-user-avatar class="w-8 h-8" />
                        </button>
                        <div x-show="open" 
                             x-cloak 
                             style="display: none;" 
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-52 bg-white dark:bg-slate-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-none py-2 z-50 text-xs">
                            <div class="px-3.5 py-2 border-b border-slate-100 dark:border-slate-800">
                                <p class="font-bold text-zinc-800 dark:text-zinc-100 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-[11px] text-zinc-500 dark:text-zinc-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            @if(strtolower(auth()->user()->role ?? '') === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-3.5 py-2 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                <span class="material-symbols-outlined text-[17px] text-primary">admin_panel_settings</span> Dashboard Admin
                            </a>
                            @endif
                            <a href="{{ route('tenant.dashboard') }}" class="flex items-center gap-2.5 px-3.5 py-2 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                <span class="material-symbols-outlined text-[17px] text-sky-500">storefront</span> Dashboard Toko
                            </a>
                            <a href="{{ route('tenant.purchases.index') }}" class="flex items-center gap-2.5 px-3.5 py-2 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                <span class="material-symbols-outlined text-[17px] text-amber-500">receipt_long</span> Riwayat Belanja
                            </a>
                            <a href="{{ route('tenant.profile.index') }}" class="flex items-center gap-2.5 px-3.5 py-2 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                <span class="material-symbols-outlined text-[17px] text-emerald-500">person</span> Profil Saya
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100 dark:border-slate-800 mt-1 pt-1">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center gap-2.5 px-3.5 py-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors font-medium">
                                    <span class="material-symbols-outlined text-[17px]">logout</span> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </header>

    <main class="flex-1 mt-[calc(env(safe-area-inset-top,0px)+58px)] md:mt-20">
        @yield('content')
    </main>

    <!-- Footer (Desktop Only) -->
    <footer aria-label="Footer" class="hidden md:block bg-surface-container dark:bg-surface-container-lowest text-on-surface dark:text-on-surface-variant font-body-md text-body-md font-label-md text-label-md w-full border-t border-outline-variant mt-auto">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-8 px-6 lg:px-10 py-12 max-w-7xl mx-auto">
            <div class="col-span-1 md:col-span-2">
                <a class="font-headline-lg text-headline-lg font-black text-primary dark:text-on-primary-container flex items-center gap-2 mb-4" href="{{ url('/') }}" wire:navigate>
                    <img src="{{ isset($company) && $company->logo ? asset('storage/' . $company->logo) : asset('logo.png') }}" alt="{{ $company->company_name ?? 'rhantech' }}" class="h-8 w-auto">
                    {{ $company->company_name ?? 'rhantech' }}
                </a>
                <p class="text-on-surface-variant text-sm max-w-sm mb-4 leading-relaxed">Platform Marketplace Produk Digital, Source Code & Lisensi Resmi Indonesia. Memfasilitasi transaksi aman, terpercaya, dan patuh regulasi nasional.</p>
                <div class="text-xs text-on-surface-variant/70 space-y-1">
                    <div>© {{ date('Y') }} {{ $company->company_name ?? 'PT Rhantech Digital Globalindo' }}.</div>
                    <div class="text-[11px] text-slate-500">Penyelenggara Sistem Elektronik (PSE) Terdaftar Komdigi/Kominfo</div>
                </div>

                @php
                    $allSocial = [];
                    if (!empty($company->social_links) && is_array($company->social_links)) {
                        $allSocial = $company->social_links;
                    } else {
                        if (!empty($company->website))   $allSocial[] = ['platform' => 'website',   'url' => $company->website,   'name' => 'Website'];
                        if (!empty($company->facebook ?? $company->facebook_url))  $allSocial[] = ['platform' => 'facebook',  'url' => $company->facebook ?? $company->facebook_url,  'name' => 'Facebook'];
                        if (!empty($company->instagram ?? $company->instagram_url)) $allSocial[] = ['platform' => 'instagram', 'url' => $company->instagram ?? $company->instagram_url, 'name' => 'Instagram'];
                        if (!empty($company->linkedin ?? $company->linkedin_url))  $allSocial[] = ['platform' => 'linkedin',  'url' => $company->linkedin ?? $company->linkedin_url,  'name' => 'LinkedIn'];
                        if (!empty($company->youtube))   $allSocial[] = ['platform' => 'youtube',   'url' => $company->youtube,   'name' => 'YouTube'];
                    }
                @endphp
                @if(!empty($allSocial))
                <div class="flex flex-wrap items-center gap-2 pt-3">
                    @foreach($allSocial as $soc)
                        @php
                            $pKey = strtolower($soc['platform'] ?? 'custom');
                            $pUrl = $soc['url'] ?? '#';
                            $pName = $soc['name'] ?? ucfirst($pKey);
                        @endphp
                        @if(!empty($pUrl) && $pUrl !== '#')
                            <a href="{{ $pUrl }}" target="_blank" rel="noopener noreferrer" title="{{ $pName }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface-variant hover:text-primary transition-colors">
                                @if($pKey === 'website')
                                    <svg class="w-4 h-4 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                                @elseif($pKey === 'facebook')
                                    <svg class="w-4 h-4 fill-current text-[#1877f2]" viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                @elseif($pKey === 'instagram')
                                    <svg class="w-4 h-4 fill-current text-[#e4405f]" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                @elseif($pKey === 'linkedin')
                                    <svg class="w-4 h-4 fill-current text-[#0a66c2]" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                @elseif($pKey === 'youtube')
                                    <svg class="w-4 h-4 fill-current text-[#ff0000]" viewBox="0 0 24 24" aria-hidden="true"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                @elseif($pKey === 'tiktok')
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" aria-hidden="true"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-1.01v8.42c0 1.95-.53 3.94-1.68 5.51-1.52 2.06-4.04 3.23-6.61 3.12-2.31-.09-4.52-1.2-5.89-3.04-1.64-2.19-1.95-5.2-.8-7.65 1.11-2.39 3.48-4.04 6.1-4.29.39-.03.78-.03 1.17 0v4.06c-.46-.07-.93-.05-1.39.04-1.07.21-2.02.89-2.54 1.84-.66 1.2-.59 2.76.18 3.88.66.97 1.83 1.55 3.01 1.48 1.13-.06 2.18-.72 2.68-1.74.25-.51.37-1.09.37-1.67V.02h-1.12z"/></svg>
                                @elseif($pKey === 'whatsapp')
                                    <svg class="w-4 h-4 fill-current text-[#25d366]" viewBox="0 0 24 24" aria-hidden="true"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                @elseif($pKey === 'x' || $pKey === 'twitter')
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                @elseif($pKey === 'telegram')
                                    <svg class="w-4 h-4 fill-current text-[#229ed9]" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.121l-6.871 4.326-2.962-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.194 1.006.131.832.942z"/></svg>
                                @elseif($pKey === 'github')
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                @else
                                    <span class="material-symbols-outlined text-[16px]">link</span>
                                @endif
                            </a>
                        @endif
                    @endforeach
                </div>
                @endif
            </div>
            <div>
                <h4 class="font-label-md text-label-md text-on-background dark:text-white font-bold mb-4 uppercase tracking-wider text-xs">Perusahaan</h4>
                <ul class="flex flex-col gap-2.5 text-xs">
                    <li><a class="text-on-surface-variant dark:text-on-surface-variant/60 hover:underline hover:text-primary transition-colors" href="{{ route('about') }}" wire:navigate>Tentang Kami</a></li>
                    <li><a class="text-on-surface-variant dark:text-on-surface-variant/60 hover:underline hover:text-primary transition-colors" href="{{ url('/#services') }}">Layanan</a></li>
                    <li><a class="text-on-surface-variant dark:text-on-surface-variant/60 hover:underline hover:text-primary transition-colors" href="{{ url('/projects') }}" wire:navigate>Portofolio</a></li>
                    <li><a class="text-on-surface-variant dark:text-on-surface-variant/60 hover:underline hover:text-primary transition-colors" href="{{ url('/products') }}">Katalog Produk</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-label-md text-label-md text-on-background dark:text-white font-bold mb-4 uppercase tracking-wider text-xs">Bantuan & Seller</h4>
                <ul class="flex flex-col gap-2.5 text-xs">
                    <li><a class="text-on-surface-variant dark:text-on-surface-variant/60 hover:underline hover:text-primary transition-colors font-semibold flex items-center gap-1.5" href="{{ route('help.index') }}"><span class="material-symbols-outlined text-[16px]">help</span> Pusat Bantuan</a></li>
                    <li><a class="text-on-surface-variant dark:text-on-surface-variant/60 hover:underline hover:text-primary transition-colors" href="{{ url('/contact') }}" wire:navigate>Hubungi Kami</a></li>
                    <li><a class="text-on-surface-variant dark:text-on-surface-variant/60 hover:underline hover:text-primary transition-colors" href="{{ route('login') }}">Login Akun / Mitra</a></li>
                    <li><a class="text-on-surface-variant dark:text-on-surface-variant/60 hover:underline hover:text-primary transition-colors" href="{{ route('register') }}">Buka Toko Digital</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-label-md text-label-md text-on-background dark:text-white font-bold mb-4 uppercase tracking-wider text-xs">Kebijakan & Regulasi</h4>
                <ul class="flex flex-col gap-2.5 text-xs">
                    <li><a class="text-on-surface-variant dark:text-on-surface-variant/60 hover:underline hover:text-primary transition-colors" href="{{ route('legal.terms') }}">Syarat & Ketentuan (PMSE)</a></li>
                    <li><a class="text-on-surface-variant dark:text-on-surface-variant/60 hover:underline hover:text-primary transition-colors" href="{{ route('legal.privacy') }}">Kebijakan Privasi (UU PDP)</a></li>
                    <li><a class="text-on-surface-variant dark:text-on-surface-variant/60 hover:underline hover:text-primary transition-colors" href="{{ route('legal.copyright') }}">Hak Cipta & Lisensi (HAKI)</a></li>
                    <li><a class="text-on-surface-variant dark:text-on-surface-variant/60 hover:underline hover:text-primary transition-colors" href="{{ route('legal.refund') }}">Kebijakan Refund Konsumen</a></li>
                    <li class="pt-1"><a class="text-[11px] text-sky-600 hover:underline flex items-center gap-1 font-medium" href="https://simpktn.kemendag.go.id" target="_blank" rel="noopener noreferrer">Layanan Ditjen PKTN Kemendag ↗</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <!-- Global Testimonial Toast -->
    @php
        $toastTestimonials = \App\Models\Testimonial::with('client')
            ->where('is_active', true)
            ->inRandomOrder()
            ->take(5)
            ->get()
            ->map(function($t) {
                return [
                    'name' => $t->company_name,
                    'content' => $t->content,
                    'position' => $t->position . ($t->client ? ' at ' . $t->client->company_name : ''),
                    'avatar' => $t->photo ? asset('storage/' . $t->photo) : ($t->client && $t->client->logo ? asset('storage/' . $t->client->logo) : null)
                ];
            });
    @endphp
    @if($toastTestimonials->count() > 0)
    <div id="testimonial-toast" class="fixed bottom-4 right-4 max-w-sm w-full bg-surface-container-high rounded-md shadow-[0px_20px_25px_-5px_rgba(15,23,42,0.1)] border border-outline-variant p-md transform translate-y-12 opacity-0 pointer-events-none transition-all duration-500 z-50 hidden md:flex gap-md items-start">
        <div id="toast-avatar" class="w-10 h-10 rounded-full border border-outline-variant flex items-center justify-center bg-surface text-on-surface-variant flex-shrink-0 overflow-hidden">
            <span class="material-symbols-outlined">person</span>
        </div>
        <div class="flex-1 min-w-0">
            <p id="toast-content" class="font-body-sm text-on-surface line-clamp-2 italic mb-1 text-sm"></p>
            <div class="font-label-sm text-primary font-bold truncate text-sm" id="toast-name"></div>
            <div class="font-code-sm text-on-surface-variant truncate text-xs" id="toast-position"></div>
        </div>
        <button onclick="hideToast()" class="text-on-surface-variant hover:text-error transition-colors flex-shrink-0 pointer-events-auto">
            <span class="material-symbols-outlined text-sm">close</span>
        </button>
    </div>

    <script>
        const toastData = @json($toastTestimonials);

        let toastIndex = 0;
        const toastEl = document.getElementById('testimonial-toast');
        
        function showNextToast() {
            if (toastData.length === 0) return;
            
            const t = toastData[toastIndex];
            document.getElementById('toast-content').innerText = `"${t.content}"`;
            document.getElementById('toast-name').innerText = t.name;
            document.getElementById('toast-position').innerText = t.position;
            
            const avatarContainer = document.getElementById('toast-avatar');
            if (t.avatar) {
                avatarContainer.innerHTML = `<img src="${t.avatar}" class="w-full h-full object-cover">`;
            } else {
                avatarContainer.innerHTML = `<span class="material-symbols-outlined">person</span>`;
            }

            // Show
            toastEl.classList.remove('translate-y-12', 'opacity-0', 'pointer-events-none');
            toastEl.classList.add('translate-y-0', 'opacity-100', 'pointer-events-auto');

            // Hide after 6 seconds
            setTimeout(() => {
                hideToast();
            }, 6000);

            toastIndex = (toastIndex + 1) % toastData.length;
        }

        function hideToast() {
            toastEl.classList.add('translate-y-12', 'opacity-0', 'pointer-events-none');
            toastEl.classList.remove('translate-y-0', 'opacity-100', 'pointer-events-auto');
        }

        // Show a single testimonial toast shortly after page load
        if (toastData.length > 0) {
            toastIndex = Math.floor(Math.random() * toastData.length);
            setTimeout(() => {
                showNextToast();
            }, 3000); // initial delay
        }
    </script>
    @endif

    <!-- Navigation Active State Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const currentPath = window.location.pathname;
            
            // Only apply scroll spy on the home page
            if (currentPath === '/' || currentPath === '/index.php') {
                const sections = document.querySelectorAll('section[id]');
                const navLinks = document.querySelectorAll('.nav-link');
                
                const observerOptions = {
                    root: null,
                    rootMargin: '-50% 0px -50% 0px', // Trigger halfway through viewport
                    threshold: 0
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const id = entry.target.getAttribute('id');
                            
                            // Remove active class from all hash links
                            navLinks.forEach(link => {
                                const href = link.getAttribute('href');
                                if (href && href.includes('/#')) {
                                    link.classList.remove('active', 'text-sky-600', 'dark:text-sky-400', 'font-bold', 'text-primary', 'dark:text-primary-fixed-dim', 'font-semibold');
                                    link.classList.add('text-on-surface-variant', 'dark:text-on-surface-variant/80');
                                }
                            });

                            // Add active class to corresponding link
                            const activeLink = document.querySelector(`.nav-link[href$="/#${id}"]`);
                            if (activeLink) {
                                activeLink.classList.remove('text-on-surface-variant', 'dark:text-on-surface-variant/80');
                                activeLink.classList.add('active', 'text-sky-600', 'dark:text-sky-400', 'font-bold');
                            }
                        }
                    });
                }, observerOptions);

                sections.forEach(section => {
                    observer.observe(section);
                });
            }
        });
    </script>
    @include('components.theme-manager')
    @auth
        @include('components.firebase-init')
        <div x-data="firebaseManager" x-init="initFirebase()" style="display:none;"></div>
    @endauth
    @include('components.chat-widget')
    @include('components.new-member-bonus-bubble')
    @include('components.popup-ad-modal')
    @include('components.pwa-install-prompt')
    @include('components.file-size-guard')
    @livewireScripts
    @stack('scripts')
</body>
</html>

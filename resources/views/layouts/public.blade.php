<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    @include('components.theme-init')
    @include('components.pwa-head')
    <title>@yield('title', ($company->company_name ?? 'rhantech') . ' - We Build Digital Experiences')</title>
    <meta name="description" content="@yield('meta_description', $company->about_text ?? 'We build scalable, modern, and impactful digital solutions for businesses worldwide.')"/>
    <meta name="keywords" content="digital agency, web development, mobile app development, UI/UX design, cloud infrastructure"/>
    <meta property="og:title" content="@yield('title', ($company->company_name ?? 'rhantech') . ' - We Build Digital Experiences')"/>
    <meta property="og:description" content="@yield('meta_description', $company->about_text ?? 'We build scalable, modern, and impactful digital solutions for businesses worldwide.')"/>
    <meta property="og:image" content="@yield('meta_image', isset($company) && $company->logo ? asset('storage/'.$company->logo) : '')"/>
    <meta property="og:type" content="website"/>
    <meta name="twitter:card" content="summary_large_image"/>
    <link rel="icon" type="image/png" href="{{ isset($company) && $company->favicon ? '/storage/'.$company->favicon : '/favicon.ico' }}" />
    <link rel="shortcut icon" type="image/png" href="{{ isset($company) && $company->favicon ? '/storage/'.$company->favicon : '/favicon.ico' }}" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
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
                        "secondary-fixed-dim": "#4cd7f6",
                        "surface-variant": "rgb(var(--theme-surface-variant) / <alpha-value>)",
                        "background": "rgb(var(--theme-background) / <alpha-value>)",
                        "on-secondary-container": "#006172",
                        "error-container": "#ffdad6",
                        "surface-dim": "#cbdbf5",
                        "on-secondary-fixed-variant": "#004e5c",
                        "surface-container-lowest": "rgb(var(--theme-surface-lowest) / <alpha-value>)",
                        "secondary": "#00687a",
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
                        "headline-xl": ["-apple-system, BlinkMacSystemFont, 'Segoe UI', 'Noto Sans', Helvetica, Arial, sans-serif"],
                        "body-md": ["-apple-system, BlinkMacSystemFont, 'Segoe UI', 'Noto Sans', Helvetica, Arial, sans-serif"],
                        "headline-lg": ["-apple-system, BlinkMacSystemFont, 'Segoe UI', 'Noto Sans', Helvetica, Arial, sans-serif"],
                        "display-lg-mobile": ["-apple-system, BlinkMacSystemFont, 'Segoe UI', 'Noto Sans', Helvetica, Arial, sans-serif"],
                        "body-lg": ["-apple-system, BlinkMacSystemFont, 'Segoe UI', 'Noto Sans', Helvetica, Arial, sans-serif"],
                        "code-sm": ["ui-monospace, SFMono-Regular, SF Mono, Menlo, Consolas, Liberation Mono, monospace"],
                        "label-md": ["-apple-system, BlinkMacSystemFont, 'Segoe UI', 'Noto Sans', Helvetica, Arial, sans-serif"],
                        "display-lg": ["-apple-system, BlinkMacSystemFont, 'Segoe UI', 'Noto Sans', Helvetica, Arial, sans-serif"]
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
    </style>
@livewireStyles
</head>
<body class="bg-background text-on-background font-body-md text-body-md antialiased selection:bg-secondary-container selection:text-on-secondary-container flex flex-col min-h-screen">

<style>
/* ── Modern Tech Header (Synchronized with Products / Marketplace) ── */
.site-header {
    background: linear-gradient(135deg, #0a1628 0%, #0d2240 60%, #0a3352 100%);
    position: fixed; top: 0; left: 0; right: 0;
    z-index: 50;
    box-shadow: 0 2px 20px rgba(0,0,0,0.3);
}
.site-header::after {
    content: '';
    position: absolute; bottom: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, #00d4ff, #00b3cc, transparent);
}
.header-logo {
    font-size: 22px; font-weight: 900;
    letter-spacing: -0.5px;
    background: linear-gradient(135deg, #ffffff, #a8e6f0);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
    text-decoration: none;
    display: flex; align-items: center; gap: 8px;
    transition: opacity 0.2s;
}
.header-logo:hover { opacity: 0.85; }
.header-logo .logo-dot {
    width: 8px; height: 8px;
    background: #00d4ff;
    border-radius: 50%;
    box-shadow: 0 0 10px #00d4ff;
    animation: pulse-dot 2s infinite;
}
@keyframes pulse-dot {
    0%, 100% { box-shadow: 0 0 8px #00d4ff; }
    50%       { box-shadow: 0 0 18px #00d4ff, 0 0 30px rgba(0,212,255,0.4); }
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
    border-color: #00d4ff;
    background: rgba(255,255,255,0.12);
    box-shadow: 0 0 0 3px rgba(0,212,255,0.15);
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
    background: linear-gradient(135deg, #00b3cc, #0077a8);
    border: none; color: #fff;
    padding: 10px 18px;
    cursor: pointer; transition: opacity 0.2s;
    display: flex; align-items: center;
}
.search-bar-wrap button:hover { opacity: 0.85; }
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
    background: linear-gradient(135deg, #00b3cc, #0077a8);
    color: #fff;
    font-weight: 700;
    box-shadow: 0 2px 10px rgba(0,179,204,0.35);
}
.header-action-btn.primary:hover { opacity: 0.9; background: linear-gradient(135deg, #00b3cc, #0077a8); }
.search-tag {
    color: rgba(255,255,255,0.55); font-size: 11.5px;
    transition: color 0.15s;
    cursor: pointer; text-decoration: none;
}
.search-tag:hover { color: #00d4ff; }
.search-tag-sep { color: rgba(255,255,255,0.2); margin: 0 2px; }
</style>

    <!-- Desktop TopNavBar -->
    <nav x-data="{ mobileMenuOpen: false }" aria-label="Main Navigation" class="hidden md:block bg-surface/80 dark:bg-surface-container-lowest/80 backdrop-blur-md text-primary dark:text-on-surface font-headline-lg text-headline-lg font-body-md text-body-md font-label-md text-label-md fixed top-0 w-full z-50 border-b border-outline-variant/30">
        <div class="flex justify-between items-center px-lg py-md max-w-container-max mx-auto">
            <a aria-label="{{ $company->company_name ?? 'rhantech' }} Home" class="flex items-center gap-2 text-body-lg font-headline-xl font-bold text-on-background dark:text-white" href="{{ url('/') }}" wire:navigate>
                <img src="{{ isset($company) && $company->logo ? asset('storage/' . $company->logo) : asset('logo.png') }}" alt="{{ $company->company_name ?? 'rhantech' }}" class="h-8 w-auto">
                {{ $company->company_name ?? 'rhantech' }}
            </a>
            <div class="hidden md:flex items-center gap-lg nav-links">
                <a class="nav-link {{ request()->is('/') ? 'active text-secondary dark:text-secondary-fixed-dim font-semibold' : 'text-on-surface-variant dark:text-on-surface-variant/80' }} hover:text-secondary transition-colors duration-200" href="{{ url('/#home') }}">Home</a>
                <a class="nav-link {{ request()->routeIs('about') ? 'active text-secondary dark:text-secondary-fixed-dim font-semibold' : 'text-on-surface-variant dark:text-on-surface-variant/80' }} hover:text-secondary transition-colors duration-200" href="{{ route('about') }}" wire:navigate>About</a>
                <a class="nav-link text-on-surface-variant dark:text-on-surface-variant/80 hover:text-secondary transition-colors duration-200" href="{{ url('/#services') }}">Services</a>
                <a class="nav-link {{ request()->routeIs('projects.*') ? 'active text-secondary dark:text-secondary-fixed-dim font-semibold' : 'text-on-surface-variant dark:text-on-surface-variant/80' }} hover:text-secondary transition-colors duration-200" href="{{ route('projects.index') }}" wire:navigate>Portfolio</a>
                <a class="nav-link {{ request()->routeIs('products.*') || request()->routeIs('checkout.*') ? 'active text-secondary dark:text-secondary-fixed-dim font-semibold' : 'text-on-surface-variant dark:text-on-surface-variant/80' }} hover:text-secondary transition-colors duration-200" href="{{ route('products.index') }}" wire:navigate>Store</a>
                <a class="nav-link {{ request()->routeIs('clients.*') ? 'active text-secondary dark:text-secondary-fixed-dim font-semibold' : 'text-on-surface-variant dark:text-on-surface-variant/80' }} hover:text-secondary transition-colors duration-200" href="{{ route('clients.index') }}" wire:navigate>Clients</a>
                <a class="nav-link {{ request()->routeIs('contact') ? 'active text-secondary dark:text-secondary-fixed-dim font-semibold' : 'text-on-surface-variant dark:text-on-surface-variant/80' }} hover:text-secondary transition-colors duration-200" href="{{ url('/contact') }}" wire:navigate>Contact</a>
            </div>
            <div class="flex items-center gap-sm">
                <x-theme-toggle />
                
                @guest
                    <a class="hidden md:inline-flex items-center justify-center px-5 py-2 bg-gradient-to-r from-cyan-500 to-blue-500 text-white rounded-lg font-label-md text-label-md font-bold shadow-md hover:shadow-lg transition-all hover:scale-105" href="{{ route('register') }}">
                        <span class="material-symbols-outlined text-[1rem] mr-1">storefront</span> Jualan Sekarang!
                    </a>
                @else
                    <div class="flex items-center gap-3 ml-2">
                        <!-- Notification Bell -->
                        <button class="relative p-2 text-on-surface-variant hover:bg-surface-container-high rounded-full transition-colors">
                            <span class="material-symbols-outlined">notifications</span>
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-error rounded-full border border-surface"></span>
                        </button>

                        <!-- User Avatar Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 focus:outline-none rounded-full ring-2 ring-transparent hover:ring-primary/20 transition-all">
                                <img src="{{ auth()->user()->avatar ? (Str::startsWith(auth()->user()->avatar, 'http') ? auth()->user()->avatar : asset('storage/' . auth()->user()->avatar)) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=0284c7&color=fff' }}" alt="Avatar" referrerpolicy="no-referrer" class="w-9 h-9 rounded-full object-cover">
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="open" style="display: none;" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-56 bg-surface-container-lowest border border-outline-variant rounded-md shadow-xl py-2 z-50">
                                <a href="{{ route('tenant.profile.index') }}" class="block px-4 py-3 border-b border-outline-variant/50 mb-1 hover:bg-surface-container-low transition-colors">
                                    <p class="text-sm font-bold text-on-surface truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-on-surface-variant truncate mb-1">{{ auth()->user()->email }}</p>
                                    <div class="flex items-center gap-1 text-[11px] text-primary font-semibold">
                                        <span class="material-symbols-outlined text-[12px]">edit</span> Edit Profil
                                    </div>
                                </a>
                                
                                @if(auth()->user()->role === 'admin')
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

    <!-- Mobile Header (2-Row, block md:hidden) -->
    <header class="block md:hidden site-header fixed top-0 left-0 right-0 z-50">
        <div class="px-4 py-2 flex flex-col gap-2">
            <!-- Row 1: Logo, Cart, Masuk/User -->
            <div class="flex items-center justify-between">
                <a href="{{ url('/') }}" class="header-logo shrink-0" wire:navigate>
                    <span class="logo-dot"></span>
                    {{ $company->company_name ?? 'rhantech' }}
                </a>
                
                <div class="flex items-center gap-2 shrink-0">
                    @php $cartCount = count(session('cart', [])); @endphp
                    <a href="{{ route('cart.index') }}" class="header-action-btn relative p-1.5" title="Keranjang" wire:navigate>
                        <span class="material-symbols-outlined text-[20px]">shopping_cart</span>
                        <span data-cart-count
                            class="absolute -top-1 -right-1 bg-[#00d4ff] text-[#0a1628] text-[9px] font-black px-1.5 py-0.5 rounded-full min-w-[16px] text-center leading-none"
                            style="{{ $cartCount > 0 ? '' : 'display:none' }}">{{ $cartCount }}</span>
                    </a>

                    @guest
                        <a href="{{ route('login') }}" class="header-action-btn primary text-xs !py-1 !px-3">Masuk</a>
                    @else
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.outside="open = false" class="flex items-center focus:outline-none">
                                <img src="{{ auth()->user()->avatar ? (Str::startsWith(auth()->user()->avatar, 'http') ? auth()->user()->avatar : asset('storage/' . auth()->user()->avatar)) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=0d2240&color=00d4ff' }}" class="w-7 h-7 rounded-full border border-white/30 object-cover">
                            </button>
                            <div x-show="open" style="display: none;" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl py-2 z-50 text-xs">
                                <div class="px-3 py-1.5 border-b border-gray-100 dark:border-gray-800">
                                    <p class="font-bold text-gray-800 dark:text-white truncate">{{ auth()->user()->name }}</p>
                                </div>
                                @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <span class="material-symbols-outlined text-[16px]">admin_panel_settings</span> Admin
                                </a>
                                @endif
                                <a href="{{ route('tenant.dashboard') }}" class="flex items-center gap-2 px-3 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <span class="material-symbols-outlined text-[16px]">storefront</span> Dashboard Toko
                                </a>
                                <a href="{{ route('tenant.purchases.index') }}" class="flex items-center gap-2 px-3 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <span class="material-symbols-outlined text-[16px]">receipt_long</span> Riwayat Belanja
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100 dark:border-gray-800 mt-1 pt-1">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center gap-2 px-3 py-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20">
                                        <span class="material-symbols-outlined text-[16px]">logout</span> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>

            <!-- Row 2: Search Input -->
            <div class="w-full">
                <form action="{{ route('products.index') }}" method="GET">
                    <div class="search-bar-wrap">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari produk digital, source code, aplikasi..."
                            autocomplete="off">
                        <button type="submit" aria-label="Cari">
                            <span class="material-symbols-outlined text-[18px]">search</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </header>

    <main class="flex-1 mt-[95px] md:mt-20">
        @yield('content')
    </main>

    <!-- Footer (Desktop Only) -->
    <footer aria-label="Footer" class="hidden md:block bg-surface-container dark:bg-surface-container-lowest text-on-surface dark:text-on-surface-variant font-body-md text-body-md font-label-md text-label-md w-full border-t border-outline-variant mt-auto">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-lg px-lg py-2xl max-w-container-max mx-auto">
            <div class="col-span-1 md:col-span-2">
                <a class="font-headline-lg text-headline-lg font-black text-primary dark:text-on-primary-container flex items-center gap-2 mb-4" href="{{ url('/') }}" wire:navigate>
                    <img src="{{ isset($company) && $company->logo ? asset('storage/' . $company->logo) : asset('logo.png') }}" alt="{{ $company->company_name ?? 'rhantech' }}" class="h-8 w-auto">
                    {{ $company->company_name ?? 'rhantech' }}
                </a>
                <p class="text-on-surface-variant max-w-sm mb-6">Building scalable, modern, and impactful digital solutions for businesses worldwide. Precision engineering meets elegant design.</p>
                <div class="font-label-md text-label-md text-on-surface-variant/60">
                    © {{ date('Y') }} {{ $company->company_name ?? 'rhantech' }}. All rights reserved.
                </div>
            </div>
            <div>
                <h4 class="font-label-md text-label-md text-on-background dark:text-white font-bold mb-4 uppercase tracking-wider">Company</h4>
                <ul class="flex flex-col gap-3">
                    <li><a class="text-on-surface-variant dark:text-on-surface-variant/60 hover:underline hover:text-primary transition-colors" href="{{ route('about') }}" wire:navigate>About Us</a></li>
                    <li><a class="text-on-surface-variant dark:text-on-surface-variant/60 hover:underline hover:text-primary transition-colors" href="{{ url('/#services') }}">Services</a></li>
                    <li><a class="text-on-surface-variant dark:text-on-surface-variant/60 hover:underline hover:text-primary transition-colors" href="{{ url('/projects') }}" wire:navigate>Portfolio</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-label-md text-label-md text-on-background dark:text-white font-bold mb-4 uppercase tracking-wider">Support</h4>
                <ul class="flex flex-col gap-3">
                    <li><a class="text-on-surface-variant dark:text-on-surface-variant/60 hover:underline hover:text-primary transition-colors" href="{{ url('/contact') }}" wire:navigate>Contact Us</a></li>
                    <li><a class="text-on-surface-variant dark:text-on-surface-variant/60 hover:underline hover:text-primary transition-colors" href="{{ route('login') }}">Admin Login</a></li>
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
                                    link.classList.remove('active', 'text-secondary', 'dark:text-secondary-fixed-dim', 'font-semibold');
                                    link.classList.add('text-on-surface-variant', 'dark:text-on-surface-variant/80');
                                }
                            });

                            // Add active class to corresponding link
                            const activeLink = document.querySelector(`.nav-link[href$="/#${id}"]`);
                            if (activeLink) {
                                activeLink.classList.remove('text-on-surface-variant', 'dark:text-on-surface-variant/80');
                                activeLink.classList.add('active', 'text-secondary', 'dark:text-secondary-fixed-dim', 'font-semibold');
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
    @include('components.pwa-install-prompt')
    @livewireScripts
</body>
</html>

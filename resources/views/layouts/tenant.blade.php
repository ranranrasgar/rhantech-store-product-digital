<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('components.theme-init')
@include('components.pwa-head')
<title>@yield('title', 'Admin Panel') - {{ $company->company_name ?? 'Admin' }}</title>
<link rel="icon" type="image/png" href="{{ isset($company) && $company->favicon ? '/storage/'.$company->favicon : '/favicon.ico' }}" />
    <link rel="shortcut icon" type="image/png" href="{{ isset($company) && $company->favicon ? '/storage/'.$company->favicon : '/favicon.ico' }}" />
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              "colors": {
                      "on-secondary-fixed": "#001f26",
                      "secondary-container": "#57dffe",
                      "on-primary": "#ffffff",
                      "secondary-fixed": "#acedff",
                      "on-tertiary-container": "#7073ff",
                      "primary-fixed": "#dae2fd",
                      "surface-bright": "rgb(var(--theme-surface) / <alpha-value>)",
                      "error": "#ba1a1a",
                      "outline-variant": "rgb(var(--theme-outline-variant) / <alpha-value>)",
                      "on-tertiary-fixed": "#07006c",
                      "tertiary": "#000000",
                      "secondary": "#00687a",
                      "surface": "rgb(var(--theme-surface) / <alpha-value>)",
                      "error-container": "#ffdad6",
                      "on-secondary-fixed-variant": "#004e5c",
                      "on-tertiary": "#ffffff",
                      "inverse-primary": "#bec6e0",
                      "surface-container-highest": "rgb(var(--theme-surface-highest) / <alpha-value>)",
                      "surface-container": "rgb(var(--theme-surface-container) / <alpha-value>)",
                      "tertiary-fixed-dim": "#c0c1ff",
                      "tertiary-fixed": "#e1e0ff",
                      "inverse-on-surface": "#eaf1ff",
                      "on-surface": "rgb(var(--theme-on-surface) / <alpha-value>)",
                      "on-error-container": "#93000a",
                      "surface-container-lowest": "rgb(var(--theme-surface-lowest) / <alpha-value>)",
                      "on-primary-fixed-variant": "#3f465c",
                      "surface-dim": "#cbdbf5",
                      "background": "rgb(var(--theme-background) / <alpha-value>)",
                      "on-secondary": "#ffffff",
                      "outline": "rgb(var(--theme-outline) / <alpha-value>)",
                      "primary-fixed-dim": "#bec6e0",
                      "on-error": "#ffffff",
                      "surface-container-high": "rgb(var(--theme-surface-high) / <alpha-value>)",
                      "on-primary-container": "rgb(var(--theme-on-primary-container) / <alpha-value>)",
                      "inverse-surface": "#213145",
                      "on-surface-variant": "rgb(var(--theme-on-surface-variant) / <alpha-value>)",
                      "on-tertiary-fixed-variant": "#2f2ebe",
                      "surface-variant": "rgb(var(--theme-surface-variant) / <alpha-value>)",
                      "secondary-fixed-dim": "#4cd7f6",
                      "tertiary-container": "#07006c",
                      "on-background": "rgb(var(--theme-on-background) / <alpha-value>)",
                      "primary-container": "#131b2e",
                      "on-primary-fixed": "#131b2e",
                      "primary": "rgb(var(--theme-primary) / <alpha-value>)",
                      "surface-tint": "#565e74",
                      "surface-container-low": "rgb(var(--theme-surface-low) / <alpha-value>)",
                      "on-secondary-container": "#006172"
              },
              "borderRadius": {
                      "DEFAULT": "0.25rem",
                      "lg": "0.5rem",
                      "xl": "0.75rem",
                      "full": "9999px"
              },
              "spacing": {
                      "md": "16px",
                      "container-max": "1280px",
                      "xl": "48px",
                      "lg": "24px",
                      "2xl": "80px",
                      "xs": "4px",
                      "unit": "4px",
                      "gutter": "24px",
                      "sm": "8px"
              }
            }
          }
        }
</script>
@include('components.theme-styles')
@vite(['resources/css/app.css'])
@livewireStyles
<style>
/* Hilangkan semua shadow blur & degradasi pada object di dashboard tenant */
*, ::before, ::after {
    --tw-shadow: 0 0 #0000 !important;
    --tw-shadow-colored: 0 0 #0000 !important;
    --tw-drop-shadow: 0 0 #0000 !important;
}
[class*="shadow-"], [class*="shadow"], [class*="drop-shadow"] {
    box-shadow: none !important;
    filter: none !important;
}
.hide-scrollbar::-webkit-scrollbar { display: none; }
.hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
html, body {
    overflow-x: hidden;
    max-width: 100vw;
    width: 100%;
}
body {
    background: #f0f4f8;
    color: #1a202c;
    min-height: 100vh;
    font-family: 'Geist', sans-serif;
    margin: 0;
    padding: 0;
}
/* Dark mode override */
body.dark, html.dark body {
    background: #0d1117;
    color: #fff;
}

/* ── Sidebar ── */
.tenant-sidebar {
    background: #ffffff;
    border-right: 1px solid #e5e7eb;
    position: fixed; left:0; top:0;
    height: 100vh; width: 240px;
    display: flex; flex-direction: column;
    z-index: 50; overflow-y: auto;
    transition: background 0.2s, border-color 0.2s;
}
html.dark .tenant-sidebar { background: #010409; border-right-color: #30363d; }

/* Brand */
.sidebar-brand {
    padding: 20px 20px 16px;
    border-bottom: 1px solid #e5e7eb;
    display: flex; align-items: center; gap: 10px;
    text-decoration: none; flex-shrink: 0;
    transition: border-color 0.2s;
}
html.dark .sidebar-brand { border-bottom-color: #30363d; }

.brand-dot-s {
    width: 8px; height: 8px; border-radius: 50%;
    background: #0f172a;
    flex-shrink: 0; display:inline-block;
}
html.dark .brand-dot-s { background: #ffffff; }

.brand-name-s {
    font-size: 16px; font-weight: 900; letter-spacing: -0.3px;
    color: #1a202c; line-height: 1.1;
    transition: color 0.2s;
}
html.dark .brand-name-s {
    color: #ffffff;
    background: none;
    -webkit-text-fill-color: initial;
}
.brand-sub-s { font-size: 10px; color: #9ca3af; letter-spacing: 0.5px; margin-top: 2px; }
html.dark .brand-sub-s { color: rgba(255,255,255,0.3); }

/* Nav section label */
.nav-section-label {
    padding: 14px 16px 4px;
    font-size: 10px; font-weight: 700;
    color: #9ca3af; letter-spacing: 1.5px;
    text-transform: uppercase;
}
html.dark .nav-section-label { color: #8b949e; }

/* Nav link */
.nav-link {
    display: flex; align-items: center; gap: 10px;
    padding: 9px 12px; border-radius: 10px; margin: 1px 4px;
    font-size: 13px; font-weight: 500;
    color: #4b5563; text-decoration: none;
    transition: all 0.15s ease; position: relative;
}
.nav-link:hover { background: #f3f4f6; color: #111827; }
.nav-link.active { background: #0f172a; color: #ffffff; font-weight: 600; }
.nav-link.active::before {
    content: ''; position: absolute;
    left: -4px; top: 6px; bottom: 6px;
    width: 3px; border-radius: 0 3px 3px 0;
    background: #0f172a;
}

html.dark .nav-link { color: #8b949e; }
html.dark .nav-link:hover { background: #161b22; color: #c9d1d9; }
html.dark .nav-link.active { background: #ffffff; color: #0f172a; font-weight: 600; }
html.dark .nav-link.active::before { background: #ffffff; }

.nav-link .material-symbols-outlined { font-size: 18px; width: 20px; text-align: center; flex-shrink: 0; opacity: 0.7; }
.nav-link.active .material-symbols-outlined { opacity: 1; color: inherit; }

/* Sidebar user */
.sidebar-user {
    padding: 14px 16px;
    border-top: 1px solid #e5e7eb;
    display: flex; align-items: center; gap: 10px; flex-shrink: 0;
    transition: border-color 0.2s;
}
html.dark .sidebar-user { border-top-color: #30363d; }
.sidebar-user-name { font-size: 12px; font-weight: 600; color: #111827; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
html.dark .sidebar-user-name { color: #fff; }
.sidebar-user-email { font-size: 10px; color: #9ca3af; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
html.dark .sidebar-user-email { color: rgba(255,255,255,0.3); }

/* ── Top bar ── */
.tenant-topbar {
    background: #ffffff;
    border-bottom: 1px solid #e5e7eb;
    position: sticky; top: 0; z-index: 40;
    height: 56px; display: flex; align-items: center;
    padding: 0 12px; justify-content: space-between;
    gap: 8px;
    transition: background 0.2s, border-color 0.2s;
}
@media (min-width: 768px) {
    .tenant-topbar { padding: 0 24px; gap: 16px; }
}
html.dark .tenant-topbar { background: #010409; border-bottom-color: #30363d; }

/* Icon btn */
.topbar-icon-btn {
    width: 34px; height: 34px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    color: #6b7280; cursor: pointer;
    transition: all 0.15s; border: none; background: transparent;
}
.topbar-icon-btn:hover { background: #f3f4f6; color: #111827; }
html.dark .topbar-icon-btn { color: rgba(255,255,255,0.5); }
html.dark .topbar-icon-btn:hover { background: rgba(255,255,255,0.08); color: #fff; }

/* Dropdown */
.topbar-dropdown {
    position: absolute; right: 0; top: calc(100% + 8px);
    width: 200px; border-radius: 12px; padding: 6px;
    box-shadow: none; z-index: 100;
    background: #fff; border: 1px solid #e5e7eb;
}
html.dark .topbar-dropdown { background: #161b22; border-color: #30363d; box-shadow: none; }
.dropdown-user-name { font-size: 13px; font-weight: 600; color: #111827; }
html.dark .dropdown-user-name { color: #fff; }
.dropdown-user-email { font-size: 11px; color: #6b7280; }
html.dark .dropdown-user-email { color: rgba(255,255,255,0.3); }
.dropdown-item-link {
    display: flex; align-items: center; gap: 8px;
    padding: 9px 12px; border-radius: 8px; font-size: 13px;
    color: #4b5563; text-decoration: none; transition: all 0.15s;
}
.dropdown-item-link:hover { background: #f3f4f6; color: #111827; }
html.dark .dropdown-item-link { color: rgba(255,255,255,0.65); }
html.dark .dropdown-item-link:hover { background: rgba(255,255,255,0.07); color: #fff; }
.dropdown-logout-btn {
    display: flex; align-items: center; gap: 8px;
    padding: 9px 12px; border-radius: 8px; font-size: 13px;
    color: #dc2626; width: 100%; text-align: left;
    background: transparent; border: none; cursor: pointer; transition: all 0.15s;
}
.dropdown-logout-btn:hover { background: #fee2e2; }
html.dark .dropdown-logout-btn { color: rgba(248,113,113,0.8); }
html.dark .dropdown-logout-btn:hover { background: rgba(239,68,68,0.1); color: #f87171; }
.sidebar-logout-btn {
    background: transparent; border: none; cursor: pointer;
    color: #9ca3af; padding: 4px; border-radius: 6px;
    display: flex; transition: color 0.15s;
}
.sidebar-logout-btn:hover { color: #dc2626; }
html.dark .sidebar-logout-btn { color: rgba(255,255,255,0.3); }
html.dark .sidebar-logout-btn:hover { color: #f87171; }
.html-dark-border { border-color: #e5e7eb !important; }
html.dark .html-dark-border { border-bottom-color: #30363d !important; }

/* Responsive overrides */
.tenant-main {
    margin-left: 240px;
    width: calc(100% - 240px);
    max-width: calc(100% - 240px);
    min-width: 0;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    box-sizing: border-box;
    transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1), width 0.3s cubic-bezier(0.4, 0, 0.2, 1), max-width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

@media (max-width: 1023px) {
    .tenant-sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .tenant-sidebar.open {
        transform: translateX(0);
        box-shadow: none;
    }
    .tenant-main {
        margin-left: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }
    .topbar-search {
        display: none !important;
    }
}
@media (min-width: 1024px) {
    .tenant-sidebar.collapsed {
        transform: translateX(-100%);
    }
    .tenant-main.collapsed {
        margin-left: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }
}
</style>
</head>
<body x-data="{ 
    sidebarOpen: false, 
    sidebarCollapsed: localStorage.getItem('tenant_sidebar_collapsed') === 'true',
    toggleSidebar() {
        if (window.innerWidth < 1024) {
            this.sidebarOpen = !this.sidebarOpen;
        } else {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            localStorage.setItem('tenant_sidebar_collapsed', this.sidebarCollapsed);
            setTimeout(() => { window.dispatchEvent(new Event('resize')); }, 320);
        }
    }
}">
<!-- Mobile Sidebar Overlay -->
<div x-show="sidebarOpen" @click="sidebarOpen = false" style="position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:45; display:none;" x-transition.opacity></div>

<!-- Sidebar -->
<aside class="tenant-sidebar" :class="{ 'open': sidebarOpen, 'collapsed': sidebarCollapsed }">
    <!-- Brand -->
    <a href="{{ url('/') }}" class="sidebar-brand">
        <span class="brand-dot-s"></span>
        <div>
            <div class="brand-name-s" style="display:flex; align-items:center; gap:4px;">
                {{ $company->company_name ?? 'rhantech' }}
                @if(auth()->check() && auth()->user()->store && auth()->user()->store->isPro())
                    <span style="background:#f59e0b; color:#fff; font-size:9px; padding:2px 5px; border-radius:4px; font-weight:800; text-transform:uppercase;">PRO</span>
                @endif
            </div>
            <div class="brand-sub-s">Seller Center</div>
        </div>
    </a>

    <!-- Nav -->
    <nav style="flex:1; padding:8px 0;">
        <a href="{{ route('tenant.dashboard') }}" class="nav-link {{ request()->routeIs('tenant.dashboard') ? 'active' : '' }}">
            <span class="material-symbols-outlined">space_dashboard</span> Dashboard
        </a>

        <div class="nav-section-label">Akun Saya</div>
        <a href="{{ route('tenant.purchases.index') }}" class="nav-link {{ request()->routeIs('tenant.purchases.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">shopping_bag</span> Pembelian Saya
        </a>
        <a href="{{ route('tenant.following') }}" class="nav-link {{ request()->routeIs('tenant.following') ? 'active' : '' }}">
            <span class="material-symbols-outlined">storefront</span> Toko yang Diikuti
        </a>
        <a href="{{ route('tenant.profile.index') }}" class="nav-link {{ request()->routeIs('tenant.profile.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">settings</span> Profil &amp; Pengaturan
        </a>

        @if(auth()->user()->store)
        <div class="nav-section-label">Manajemen Toko</div>
        <a href="{{ route('tenant.products.index') }}" class="nav-link {{ request()->routeIs('tenant.products.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">inventory_2</span> Katalog Produk
        </a>
        <a href="{{ route('tenant.orders.index') ?? '#' }}" class="nav-link {{ request()->routeIs('tenant.orders.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">receipt_long</span> Penjualan
        </a>
        <a href="{{ route('tenant.chat.index') }}" class="nav-link {{ request()->routeIs('tenant.chat.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">chat</span> Chat Pelanggan
        </a>

        <div class="nav-section-label">Marketing & Promosi</div>
        <a href="{{ route('tenant.broadcast.index') }}" class="nav-link {{ request()->routeIs('tenant.broadcast.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">send_to_mobile</span> WA Broadcast
        </a>
        <a href="{{ route('tenant.ads.index') }}" class="nav-link {{ request()->routeIs('tenant.ads.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">ads_click</span> Iklan & Promosi
        </a>
        <a href="{{ route('tenant.showcase.index') }}" class="nav-link {{ request()->routeIs('tenant.showcase.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">shopping_basket</span> Etalase Afiliasi
        </a>
        <a href="{{ route('tenant.affiliates.index') }}" class="nav-link {{ request()->routeIs('tenant.affiliates.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">handshake</span> Mitra Afiliasi
        </a>
        <a href="{{ route('tenant.campaigns.index') }}" class="nav-link {{ request()->routeIs('tenant.campaigns.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">campaign</span> Diskon & Voucher
        </a>

        <div class="nav-section-label">Halaman & Profil</div>
        <a href="{{ route('tenant.appearance.index') ?? '#' }}" class="nav-link {{ request()->routeIs('tenant.appearance.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">palette</span> Desain Tampilan
        </a>
        <a href="{{ route('tenant.store.index') }}" class="nav-link {{ request()->routeIs('tenant.store.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">manage_accounts</span> Profil & Pengaturan
        </a>
        <a href="{{ route('tenant.projects.index') }}" class="nav-link {{ request()->routeIs('tenant.projects.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">folder_special</span> Portofolio Karya
        </a>
        <a href="{{ route('tenant.pro.index') }}" class="nav-link {{ request()->routeIs('tenant.pro.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined text-amber-500">workspace_premium</span> Fitur & Akun PRO
        </a>

        <div class="nav-section-label">Keuangan</div>
        <a href="{{ route('tenant.payouts.index') ?? '#' }}" class="nav-link {{ request()->routeIs('tenant.payouts.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">account_balance_wallet</span> Penghasilan
        </a>
        <a href="{{ route('tenant.balance.index') ?? '#' }}" class="nav-link {{ request()->routeIs('tenant.balance.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">account_balance</span> Saldo Penjual
        </a>
        <a href="{{ route('tenant.bank.index') ?? '#' }}" class="nav-link {{ request()->routeIs('tenant.bank.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">credit_card</span> Rekening Bank
        </a>

        <div class="nav-section-label">Analitik</div>
        <a href="{{ route('tenant.performance.index') ?? '#' }}" class="nav-link {{ request()->routeIs('tenant.performance.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">monitoring</span> Analitik & Performa
        </a>
        <div class="nav-section-label">Pusat Bantuan</div>
        <a href="{{ route('help.index') }}" target="_blank" class="nav-link">
            <span class="material-symbols-outlined">help</span> Panduan Aplikasi
        </a>
        @else
        <div class="nav-section-label">Halaman Kreator</div>
        <a href="{{ route('tenant.store.index') }}" class="nav-link {{ request()->routeIs('tenant.store.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">hub</span> Buat Toko &amp; Bio Link
        </a>
        <div class="nav-section-label">Pusat Bantuan</div>
        <a href="{{ route('help.index') }}" target="_blank" class="nav-link">
            <span class="material-symbols-outlined">help</span> Panduan Aplikasi
        </a>
        @endif
    </nav>

    <!-- User -->
    <div class="sidebar-user">
        <x-user-avatar style="width:34px; height:34px; border-radius:50%; border:2px solid rgba(0,179,204,0.4); flex-shrink:0;" />
        <div style="flex:1; min-width:0;">
            <div class="sidebar-user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
            <div class="sidebar-user-email">{{ auth()->user()->email ?? '' }}</div>
        </div>
        <form action="{{ route('logout') }}" method="POST" style="flex-shrink:0;">
            @csrf
            <button type="submit" title="Logout" class="sidebar-logout-btn">
                <span class="material-symbols-outlined" style="font-size:18px;">logout</span>
            </button>
        </form>
    </div>
</aside>

<!-- Main Content -->
<main class="tenant-main pb-20 md:pb-0" :class="{ 'collapsed': sidebarCollapsed }">
    <!-- Top bar -->
    <header class="tenant-topbar">
        <div class="flex items-center gap-2 md:gap-3 flex-1 min-w-0 pr-1 md:pr-2">
            <button type="button" @click="toggleSidebar()" class="topbar-icon-btn active:scale-95 transition-transform" style="display:flex; width:36px; height:36px; shrink-0;" title="Toggle Sidebar">
                <span class="material-symbols-outlined" style="font-size:22px;" x-text="sidebarCollapsed ? 'menu_open' : 'menu'">menu</span>
            </button>

            <!-- Universal Live Search: Produk & Toko (Mendekati) -->
            <div class="relative flex-1 max-w-sm md:max-w-md lg:max-w-xl min-w-0" x-data="tenantGlobalSearch()">
                <div class="relative flex items-center w-full">
                    <span class="material-symbols-outlined absolute left-3 text-slate-400 dark:text-slate-500 pointer-events-none text-[18px]">search</span>
                    <input 
                        type="text" 
                        x-model="query" 
                        @input.debounce.250ms="search()" 
                        @focus="if(query.trim().length >= 2) open = true"
                        @keydown.escape="open = false"
                        @keydown.enter.prevent="submitSearch()"
                        placeholder="Cari produk atau toko..." 
                        autocomplete="off"
                        class="w-full pl-9 pr-8 py-1.5 md:py-2 text-xs md:text-sm bg-slate-100 dark:bg-[#161b22] border border-slate-200 dark:border-[#30363d] rounded-xl text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-[#00838f] focus:ring-2 focus:ring-[#00838f]/20 transition-all"
                    >
                    <button 
                        type="button" 
                        x-show="query.length > 0 && !loading" 
                        @click="query = ''; results = { stores: [], products: [] }; open = false" 
                        class="absolute right-2.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-0.5 rounded-full"
                        style="display: none;"
                    >
                        <span class="material-symbols-outlined text-[16px]">close</span>
                    </button>
                    <div x-show="loading" class="absolute right-2.5" style="display: none;">
                        <span class="inline-block w-4 h-4 border-2 border-[#00838f] border-t-transparent rounded-full animate-spin"></span>
                    </div>
                </div>

                <!-- Dropdown Results Autocomplete -->
                <div 
                    x-show="open && (results.stores.length > 0 || results.products.length > 0 || noResults)" 
                    @click.outside="open = false" 
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-1"
                    class="absolute left-0 right-0 top-full mt-1.5 bg-white dark:bg-[#161b22] border border-slate-200 dark:border-[#30363d] rounded-2xl shadow-xl z-50 overflow-hidden max-h-[75vh] overflow-y-auto"
                    style="display: none;"
                >
                    <!-- Stores Section -->
                    <template x-if="results.stores.length > 0">
                        <div class="p-2 border-b border-slate-100 dark:border-[#222f49]">
                            <div class="px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 flex items-center justify-between">
                                <span>Toko Terkait</span>
                                <span class="text-[10px] lowercase" x-text="results.stores.length + ' ditemukan'"></span>
                            </div>
                            <div class="space-y-1 mt-1">
                                <template x-for="st in results.stores" :key="'store-'+st.id">
                                    <a :href="st.url" class="flex items-center justify-between p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-colors group">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <img :src="st.logo" :alt="st.name" class="w-8 h-8 rounded-full object-cover border border-slate-200 dark:border-slate-700 shrink-0">
                                            <div class="min-w-0">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate group-hover:text-[#00838f] transition-colors" x-text="st.name"></span>
                                                    <template x-if="st.is_pro">
                                                        <span class="bg-amber-500 text-white text-[9px] px-1 py-0.2 rounded font-black tracking-wide">PRO</span>
                                                    </template>
                                                </div>
                                                <div class="text-[11px] text-slate-400 truncate" x-text="'@' + st.slug + ' • ' + st.products_count + ' produk'"></div>
                                            </div>
                                        </div>
                                        <span class="material-symbols-outlined text-slate-300 dark:text-slate-600 group-hover:text-[#00838f] text-[18px] shrink-0">chevron_right</span>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Products Section -->
                    <template x-if="results.products.length > 0">
                        <div class="p-2">
                            <div class="px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 flex items-center justify-between">
                                <span>Produk Digital</span>
                                <span class="text-[10px] lowercase" x-text="results.products.length + ' ditemukan'"></span>
                            </div>
                            <div class="space-y-1 mt-1">
                                <template x-for="prod in results.products" :key="'prod-'+prod.id">
                                    <div class="flex items-center justify-between p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-colors group">
                                        <a :href="prod.url" class="flex items-center gap-2.5 min-w-0 flex-1">
                                            <template x-if="prod.image">
                                                <img :src="prod.image" :alt="prod.name" class="w-9 h-9 rounded-lg object-cover border border-slate-200 dark:border-slate-700 shrink-0">
                                            </template>
                                            <template x-if="!prod.image">
                                                <div class="w-9 h-9 rounded-lg bg-teal-50 dark:bg-teal-950/40 border border-teal-100 dark:border-teal-900/40 text-[#00838f] flex items-center justify-center shrink-0">
                                                    <span class="material-symbols-outlined text-[20px]">package_2</span>
                                                </div>
                                            </template>
                                            <div class="min-w-0 flex-1">
                                                <div class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate group-hover:text-[#00838f] transition-colors" x-text="prod.name"></div>
                                                <div class="flex items-center gap-2 text-[11px] text-slate-400">
                                                    <span class="font-semibold text-emerald-600 dark:text-emerald-400" x-text="prod.price_formatted"></span>
                                                    <template x-if="prod.store_name">
                                                        <span class="truncate" x-text="'• ' + prod.store_name"></span>
                                                    </template>
                                                    <template x-if="prod.is_mine">
                                                        <span class="px-1.5 py-0.2 rounded bg-teal-100 dark:bg-teal-950 text-[#00838f] dark:text-teal-300 text-[10px] font-bold">Milik Anda</span>
                                                    </template>
                                                </div>
                                            </div>
                                        </a>
                                        <template x-if="prod.is_mine && prod.edit_url">
                                            <a :href="prod.edit_url" class="ml-2 px-2 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-[#00838f] hover:text-white text-slate-600 dark:text-slate-300 text-[11px] font-bold transition-colors shrink-0" title="Edit Produk">
                                                Edit
                                            </a>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- No Results -->
                    <div x-show="noResults" class="p-6 text-center text-xs text-slate-400">
                        <span class="material-symbols-outlined text-3xl mb-1 text-slate-300 dark:text-slate-600">search_off</span>
                        <p>Tidak ada produk atau toko yang cocok dengan "<span class="font-bold text-slate-700 dark:text-slate-200" x-text="query"></span>"</p>
                    </div>

                    <!-- Footer: Search All in Public Products -->
                    <div class="p-2 bg-slate-50 dark:bg-slate-900/80 border-t border-slate-100 dark:border-[#222f49] text-center">
                        <button type="button" @click="submitSearch()" class="text-xs font-bold text-[#00838f] hover:underline flex items-center justify-center gap-1 w-full py-1 cursor-pointer">
                            <span>Lihat semua hasil di katalog produk</span>
                            <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div style="display:flex; align-items:center; gap:8px; shrink-0;">
            <a href="{{ route('help.index') }}" target="_blank" class="topbar-icon-btn" title="Pusat Bantuan & Panduan">
                <span class="material-symbols-outlined" style="font-size:20px;">help</span>
            </a>
            @php
                $bellRole = (auth()->check() && auth()->user()->store) ? 'tenant' : 'buyer';
            @endphp
            <x-navbar-notification-bell :role="$bellRole" />
            <div style="position:relative;" x-data="{ open: false }">
                <button class="topbar-icon-btn" @click="open = !open" @click.outside="open = false" style="padding:0; width:34px; height:34px; border-radius:50%; overflow:hidden; border:2px solid transparent; transition:border-color 0.2s;">
                    <x-user-avatar style="width:100%; height:100%; object-fit:cover;" />
                </button>
                <div x-show="open" class="topbar-dropdown" style="display:none;" x-transition>
                    <a href="{{ route('tenant.profile.index') }}" style="display:block; padding:10px 12px 8px; border-bottom:1px solid #e5e7eb; margin-bottom:4px; text-decoration:none; transition:background 0.2s;" class="html-dark-border hover-bg-gray">
                        <div class="dropdown-user-name" style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ auth()->user()->name ?? 'Admin' }}</div>
                        <div class="dropdown-user-email" style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; margin-bottom:4px;">{{ auth()->user()->email ?? '' }}</div>
                        <div style="font-size:11px; color:#00b3cc; font-weight:600; display:flex; align-items:center; gap:2px;">
                            <span class="material-symbols-outlined" style="font-size:12px;">edit</span> Edit Profil
                        </div>
                    </a>
                    @php
                        $tenantStore = auth()->user()->store ?? null;
                        $storeUrl = $tenantStore ? route('store.show', $tenantStore->slug) : route('products.index');
                    @endphp
                    <a href="{{ $storeUrl }}" target="_blank" class="dropdown-item-link">
                        <span class="material-symbols-outlined" style="font-size:16px;">storefront</span> Lihat Toko
                    </a>
                    <a href="{{ route('tenant.store.index', ['tab' => 'sistem']) }}" class="dropdown-item-link">
                        <span class="material-symbols-outlined" style="font-size:16px;">tune</span> Pengaturan Sistem
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-logout-btn">
                            <span class="material-symbols-outlined" style="font-size:16px;">logout</span> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>
    <!-- Page Content -->
    @yield('content')
</main>

<!-- Mobile Native Bottom Navigation Bar (Dock Bar) -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 dark:bg-[#0d1117]/95 backdrop-blur-md border-t border-slate-200/80 dark:border-slate-800/80 px-2 pt-2 pb-[max(0.5rem,env(safe-area-inset-bottom))] shadow-lg transition-transform duration-200" style="-webkit-tap-highlight-color: transparent;">
    <div class="grid grid-cols-5 items-center justify-around max-w-md mx-auto text-center">
        <!-- Beranda -->
        <a href="{{ route('tenant.dashboard') }}" class="flex flex-col items-center justify-center py-1 select-none active:scale-90 transition-transform {{ request()->routeIs('tenant.dashboard') ? 'text-[#00838f] dark:text-teal-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200' }}">
            <div class="relative">
                <span class="material-symbols-outlined text-[24px] {{ request()->routeIs('tenant.dashboard') ? 'fill-1' : '' }}">space_dashboard</span>
                @if(request()->routeIs('tenant.dashboard'))
                    <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1.5 h-1.5 rounded-full bg-[#00838f] dark:bg-teal-400"></span>
                @endif
            </div>
            <span class="text-[10px] mt-0.5 tracking-tight">Beranda</span>
        </a>

        <!-- Katalog -->
        <a href="{{ route('tenant.products.index') }}" class="flex flex-col items-center justify-center py-1 select-none active:scale-90 transition-transform {{ request()->routeIs('tenant.products.*') ? 'text-[#00838f] dark:text-teal-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200' }}">
            <div class="relative">
                <span class="material-symbols-outlined text-[24px]">inventory_2</span>
                @if(request()->routeIs('tenant.products.*'))
                    <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1.5 h-1.5 rounded-full bg-[#00838f] dark:bg-teal-400"></span>
                @endif
            </div>
            <span class="text-[10px] mt-0.5 tracking-tight">Produk</span>
        </a>

        <!-- Pesanan -->
        <a href="{{ route('tenant.orders.index') }}" class="flex flex-col items-center justify-center py-1 select-none active:scale-90 transition-transform relative {{ request()->routeIs('tenant.orders.*') ? 'text-[#00838f] dark:text-teal-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200' }}">
            <div class="relative">
                <span class="material-symbols-outlined text-[24px]">receipt_long</span>
                @if(request()->routeIs('tenant.orders.*'))
                    <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1.5 h-1.5 rounded-full bg-[#00838f] dark:bg-teal-400"></span>
                @endif
            </div>
            <span class="text-[10px] mt-0.5 tracking-tight">Pesanan</span>
        </a>

        <!-- Keuangan -->
        <a href="{{ route('tenant.payouts.index') }}" class="flex flex-col items-center justify-center py-1 select-none active:scale-90 transition-transform {{ request()->routeIs('tenant.payouts.*') || request()->routeIs('tenant.balance.*') ? 'text-[#00838f] dark:text-teal-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200' }}">
            <div class="relative">
                <span class="material-symbols-outlined text-[24px]">account_balance_wallet</span>
                @if(request()->routeIs('tenant.payouts.*') || request()->routeIs('tenant.balance.*'))
                    <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1.5 h-1.5 rounded-full bg-[#00838f] dark:bg-teal-400"></span>
                @endif
            </div>
            <span class="text-[10px] mt-0.5 tracking-tight">Keuangan</span>
        </a>

        <!-- Menu Toko (Drawer Trigger) -->
        <button type="button" @click="sidebarOpen = true" class="flex flex-col items-center justify-center py-1 select-none active:scale-90 transition-transform text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 cursor-pointer">
            <span class="material-symbols-outlined text-[24px]">grid_view</span>
            <span class="text-[10px] mt-0.5 tracking-tight">Menu Toko</span>
        </button>
    </div>
</nav>

@include('components.firebase-init')
<div x-data="firebaseManager" x-init="initFirebase()" style="display:none;"></div>
@include('components.theme-manager')
@include('components.popup-ad-modal')
@include('components.pwa-install-prompt')
<script>
function tenantGlobalSearch() {
    return {
        query: '',
        open: false,
        loading: false,
        noResults: false,
        results: { stores: [], products: [] },
        async search() {
            const q = this.query.trim();
            if (q.length < 2) {
                this.results = { stores: [], products: [] };
                this.open = false;
                this.noResults = false;
                return;
            }
            this.loading = true;
            try {
                const res = await fetch(`/api/search-suggest?q=${encodeURIComponent(q)}`);
                if (res.ok) {
                    const data = await res.json();
                    this.results = data;
                    const hasStores = data.stores && data.stores.length > 0;
                    const hasProducts = data.products && data.products.length > 0;
                    this.noResults = !hasStores && !hasProducts;
                    this.open = true;
                }
            } catch (err) {
                console.error('Error fetching search results:', err);
            } finally {
                this.loading = false;
            }
        },
        submitSearch() {
            const q = this.query.trim();
            if (q.length > 0) {
                window.location.href = `/products?search=${encodeURIComponent(q)}`;
            }
        }
    };
}
</script>
@include('components.file-size-guard')
@livewireScripts
@stack('scripts')
</body></html>

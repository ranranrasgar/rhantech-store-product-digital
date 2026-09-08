<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
@include('components.theme-init')
@include('components.pwa-head')
<title>@yield('title', 'Admin Panel') - {{ $company->company_name ?? 'Admin' }}</title>
<link rel="icon" type="image/png" href="{{ isset($company) && $company->favicon ? '/storage/'.$company->favicon : '/favicon.ico' }}" />
    <link rel="shortcut icon" type="image/png" href="{{ isset($company) && $company->favicon ? '/storage/'.$company->favicon : '/favicon.ico' }}" />
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
/* ── Light/Dark adaptive sidebar & topbar ── */
body {
    background: #f0f4f8;
    color: #1a202c;
    min-height: 100vh;
    display: flex;
    font-family: 'Geist', sans-serif;
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
    background: #00b3cc; box-shadow: 0 0 8px rgba(0,179,204,0.5);
    flex-shrink: 0; animation: pd 2s ease-in-out infinite; display:inline-block;
}
html.dark .brand-dot-s { background: #2f81f7; box-shadow: 0 0 10px rgba(47,129,247,0.4); }
@keyframes pd {
    0%,100%{box-shadow:0 0 8px rgba(0,179,204,0.5)} 50%{box-shadow:0 0 18px rgba(0,179,204,0.8)}
}
html.dark .brand-dot-s { background: #2f81f7; box-shadow: 0 0 10px rgba(47,129,247,0.4); }
@keyframes pd-dark { 0%,100%{box-shadow:0 0 8px rgba(47,129,247,0.5)} 50%{box-shadow:0 0 18px rgba(47,129,247,0.8)} }
.brand-name-s {
    font-size: 16px; font-weight: 900; letter-spacing: -0.3px;
    color: #1a202c; line-height: 1.1;
    transition: color 0.2s;
}
html.dark .brand-name-s {
    background: linear-gradient(135deg,#fff,#a8e6f0);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
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
.nav-link.active { background: #e0f7fa; color: #00838f; font-weight: 600; }
.nav-link.active::before {
    content: ''; position: absolute;
    left: -4px; top: 6px; bottom: 6px;
    width: 3px; border-radius: 0 3px 3px 0;
    background: #00b3cc;
}

html.dark .nav-link { color: #8b949e; }
html.dark .nav-link:hover { background: #161b22; color: #c9d1d9; }
html.dark .nav-link.active { background: #161b22; color: #e6edf3; }
html.dark .nav-link.active::before { background: #2f81f7; }

.nav-link .material-symbols-outlined { font-size: 18px; width: 20px; text-align: center; flex-shrink: 0; opacity: 0.7; }
.nav-link.active .material-symbols-outlined { opacity: 1; }

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
    padding: 0 24px; justify-content: space-between;
    transition: background 0.2s, border-color 0.2s;
}
html.dark .tenant-topbar { background: #010409; border-bottom-color: #30363d; }
.topbar-title { font-size: 15px; font-weight: 700; color: #111827; letter-spacing: -0.3px; margin: 0; }
html.dark .topbar-title { color: #fff; }

/* Search */
.topbar-search {
    display: flex; align-items: center;
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
    border-radius: 8px; padding: 0 12px;
    gap: 8px; height: 36px; width: 220px;
    transition: border-color 0.2s, background 0.2s;
}
.topbar-search:focus-within { border-color: #00b3cc; box-shadow: 0 0 0 2px rgba(0,179,204,0.15); }
.topbar-search input { background: transparent; border: none; outline: none; color: #111827; font-size: 13px; width: 100%; }
.topbar-search input::placeholder { color: #9ca3af; }

html.dark .topbar-search { background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.1); }
html.dark .topbar-search:focus-within { border-color: #2f81f7; box-shadow: 0 0 0 2px rgba(47,129,247,0.12); }
html.dark .topbar-search input { color: #fff; }
html.dark .topbar-search input::placeholder { color: rgba(255,255,255,0.3); }

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
    box-shadow: 0 8px 32px rgba(0,0,0,0.15); z-index: 100;
    background: #fff; border: 1px solid #e5e7eb;
}
html.dark .topbar-dropdown { background: #161b22; border-color: #30363d; box-shadow: 0 4px 12px rgba(0,0,0,0.5); }
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
</style>

<style>
/* Responsive overrides */
@media (max-width: 768px) {
    .tenant-sidebar { transform: translateX(-100%); transition: transform 0.3s ease-in-out; }
    .tenant-sidebar.open { transform: translateX(0); }
    .tenant-main { margin-left: 0 !important; width: 100%; }
    .topbar-search { display: none !important; }
}
</style>
</head>
<body x-data="{ sidebarOpen: false }">
<!-- Mobile Sidebar Overlay -->
<div x-show="sidebarOpen" @click="sidebarOpen = false" style="position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:45; display:none;" x-transition.opacity></div>

<!-- Sidebar -->
<aside class="tenant-sidebar" :class="sidebarOpen ? 'open' : ''">
    <!-- Brand -->
    <a href="{{ url('/') }}" class="sidebar-brand">
        <span class="brand-dot-s"></span>
        <div>
            <div class="brand-name-s">{{ $company->company_name ?? 'rhantech' }}</div>
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

        <div class="nav-section-label">Marketing & Afiliasi</div>
        <a href="{{ route('tenant.showcase.index') }}" class="nav-link {{ request()->routeIs('tenant.showcase.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">shopping_basket</span> Etalase Afiliasi
        </a>
        <a href="{{ route('tenant.affiliates.index') }}" class="nav-link {{ request()->routeIs('tenant.affiliates.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">handshake</span> Mitra Toko Saya
        </a>
        <a href="{{ route('tenant.campaigns.index') }}" class="nav-link {{ request()->routeIs('tenant.campaigns.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">campaign</span> Campaign & Promo
        </a>

        <div class="nav-section-label">Toko</div>
        <a href="{{ route('tenant.appearance.index') ?? '#' }}" class="nav-link {{ request()->routeIs('tenant.appearance.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">storefront</span> Dekorasi Toko
        </a>
        <a href="{{ route('tenant.store.index') }}" class="nav-link {{ request()->routeIs('tenant.store.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">settings</span> Pengaturan Toko
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
            <span class="material-symbols-outlined">monitoring</span> Performa Toko
        </a>
        @else
        <div class="nav-section-label">Toko Saya</div>
        <a href="{{ route('tenant.store.index') }}" class="nav-link {{ request()->routeIs('tenant.store.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">storefront</span> Mulai Berjualan
        </a>
        @endif
    </nav>

    <!-- User -->
    <div class="sidebar-user">
        @if(auth()->user()->avatar)
            <img src="{{ Str::startsWith(auth()->user()->avatar, 'http') ? auth()->user()->avatar : asset('storage/' . auth()->user()->avatar) }}" 
                 referrerpolicy="no-referrer"
                 style="width:34px; height:34px; border-radius:50%; border:2px solid rgba(0,179,204,0.4); flex-shrink:0; object-fit:cover;">
        @else
            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=0d2240&color=00b3cc"
                 style="width:34px; height:34px; border-radius:50%; border:2px solid rgba(0,179,204,0.4); flex-shrink:0; object-fit:cover;">
        @endif
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
<main class="tenant-main" style="flex:1; margin-left:240px; display:flex; flex-direction:column; min-height:100vh;">
    <!-- Top bar -->
    <header class="tenant-topbar">
        <div style="display:flex; align-items:center; gap:12px;">
            <button @click="sidebarOpen = !sidebarOpen" class="topbar-icon-btn md:hidden" style="display:flex; width:36px; height:36px;">
                <span class="material-symbols-outlined" style="font-size:20px;">menu</span>
            </button>
            <h2 class="topbar-title truncate max-w-[120px] sm:max-w-xs md:max-w-none" style="display: block;">@yield('title')</h2>
        </div>
        <div style="display:flex; align-items:center; gap:8px;">
            <x-theme-toggle />
            <div class="topbar-search hidden md:flex">
                <span class="material-symbols-outlined" style="font-size:16px; color:rgba(255,255,255,0.3);">search</span>
                <input type="text" placeholder="Cari...">
            </div>
            <button class="topbar-icon-btn">
                <span class="material-symbols-outlined" style="font-size:20px;">notifications</span>
            </button>
            <div style="position:relative;" x-data="{ open: false }">
                <button class="topbar-icon-btn" @click="open = !open" @click.outside="open = false" style="padding:0; width:34px; height:34px; border-radius:50%; overflow:hidden; border:2px solid transparent; transition:border-color 0.2s;">
                    @if(auth()->user()->avatar)
                        <img src="{{ Str::startsWith(auth()->user()->avatar, 'http') ? auth()->user()->avatar : asset('storage/' . auth()->user()->avatar) }}" referrerpolicy="no-referrer" style="width:100%; height:100%; object-fit:cover;">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=00b3cc&color=fff" style="width:100%; height:100%; object-fit:cover;">
                    @endif
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
@include('components.firebase-init')
<div x-data="firebaseManager" x-init="initFirebase()" style="display:none;"></div>
@include('components.theme-manager')
@include('components.pwa-install-prompt')
@livewireScripts
</body></html>

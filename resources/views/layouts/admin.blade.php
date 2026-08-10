<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
@include('components.theme-init')
<title>@yield('title', 'Admin Panel') - {{ $company->company_name ?? 'Admin' }}</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
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
              },
              "fontFamily": {
                      "display-lg-mobile": [
                              "-apple-system, BlinkMacSystemFont, 'Segoe UI', 'Noto Sans', Helvetica, Arial, sans-serif"
                      ],
                      "label-md": [
                              "-apple-system, BlinkMacSystemFont, 'Segoe UI', 'Noto Sans', Helvetica, Arial, sans-serif"
                      ],
                      "body-lg": [
                              "-apple-system, BlinkMacSystemFont, 'Segoe UI', 'Noto Sans', Helvetica, Arial, sans-serif"
                      ],
                      "headline-xl": [
                              "-apple-system, BlinkMacSystemFont, 'Segoe UI', 'Noto Sans', Helvetica, Arial, sans-serif"
                      ],
                      "headline-lg": [
                              "-apple-system, BlinkMacSystemFont, 'Segoe UI', 'Noto Sans', Helvetica, Arial, sans-serif"
                      ],
                      "body-md": [
                              "-apple-system, BlinkMacSystemFont, 'Segoe UI', 'Noto Sans', Helvetica, Arial, sans-serif"
                      ],
                      "code-sm": [
                              "ui-monospace, SFMono-Regular, SF Mono, Menlo, Consolas, Liberation Mono, monospace"
                      ],
                      "display-lg": [
                              "-apple-system, BlinkMacSystemFont, 'Segoe UI', 'Noto Sans', Helvetica, Arial, sans-serif"
                      ]
              }
            }
          }
        }
</script>
@include('components.theme-styles')
@livewireStyles
</head>
<body class="bg-background text-on-background font-body-md min-h-screen flex">
<!-- SideNavBar -->
<aside class="fixed left-0 top-0 h-screen w-64 bg-background z-50 flex flex-col justify-between overflow-y-auto">
<div class="flex flex-col gap-1 p-4">
<div class="mb-lg px-sm">
<h1 class="font-headline-lg text-headline-lg font-black text-on-background dark:text-white dark:text-on-primary-container flex items-center gap-2">
    @if(isset($company) && $company->logo)
        <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->company_name }}" class="h-8 w-auto">
    @endif
    {{ $company->company_name ?? 'Admin Panel' }}
</h1>
<p class="font-label-md text-label-md text-on-surface-variant">Management System</p>
</div>
<nav class="flex flex-col gap-1">
<a class="font-label-md text-label-md {{ request()->routeIs('admin.dashboard') ? 'bg-surface-variant font-semibold before:absolute before:left-0 before:top-2 before:bottom-2 before:w-[3px] before:bg-[#58a6ff] before:rounded-r-md relative' : 'text-on-surface hover:bg-surface-variant/50 hover:underline' }} px-3 py-1.5 flex items-center gap-2 transition-all rounded-md" href="{{ route('admin.dashboard') }}" wire:navigate>
<span class="material-symbols-outlined text-[1rem]">dashboard</span>
                    Dashboard
                </a>
<a class="font-label-md text-label-md {{ request()->routeIs('admin.company.*') ? 'bg-surface-variant font-semibold before:absolute before:left-0 before:top-2 before:bottom-2 before:w-[3px] before:bg-[#58a6ff] before:rounded-r-md relative' : 'text-on-surface hover:bg-surface-variant/50 hover:underline' }} px-3 py-1.5 flex items-center gap-2 transition-all rounded-md" href="{{ route('admin.company.index') }}" wire:navigate>
<span class="material-symbols-outlined text-[1rem]">business</span>
                    Company Profile
                </a>
<a class="font-label-md text-label-md {{ request()->routeIs('admin.services.*') ? 'bg-surface-variant font-semibold before:absolute before:left-0 before:top-2 before:bottom-2 before:w-[3px] before:bg-[#58a6ff] before:rounded-r-md relative' : 'text-on-surface hover:bg-surface-variant/50 hover:underline' }} px-3 py-1.5 flex items-center gap-2 transition-all rounded-md" href="{{ route('admin.services.index') }}" wire:navigate>
<span class="material-symbols-outlined text-[1rem]">layers</span>
                    Services
                </a>
<a class="font-label-md text-label-md {{ request()->routeIs('admin.projects.*') ? 'bg-surface-variant font-semibold before:absolute before:left-0 before:top-2 before:bottom-2 before:w-[3px] before:bg-[#58a6ff] before:rounded-r-md relative' : 'text-on-surface hover:bg-surface-variant/50 hover:underline' }} px-3 py-1.5 flex items-center gap-2 transition-all rounded-md" href="{{ route('admin.projects.index') }}" wire:navigate>
<span class="material-symbols-outlined text-[1rem]">folder</span>
                    Projects
                </a>
<a class="font-label-md text-label-md {{ request()->routeIs('admin.project_categories.*') ? 'bg-surface-variant font-semibold before:absolute before:left-0 before:top-2 before:bottom-2 before:w-[3px] before:bg-[#58a6ff] before:rounded-r-md relative' : 'text-on-surface hover:bg-surface-variant/50 hover:underline' }} pl-9 pr-3 py-1.5 flex items-center gap-2 transition-all rounded-md text-sm" href="{{ route('admin.project_categories.index') }}" wire:navigate>
<span class="material-symbols-outlined text-[1rem]">category</span>
                    Categories
                </a>
<a class="font-label-md text-label-md {{ request()->routeIs('admin.clients.*') ? 'bg-surface-variant font-semibold before:absolute before:left-0 before:top-2 before:bottom-2 before:w-[3px] before:bg-[#58a6ff] before:rounded-r-md relative' : 'text-on-surface hover:bg-surface-variant/50 hover:underline' }} px-3 py-1.5 flex items-center gap-2 transition-all rounded-md" href="{{ route('admin.clients.index') }}" wire:navigate>
<span class="material-symbols-outlined text-[1rem]">groups</span>
                    Clients
                </a>
<a class="font-label-md text-label-md {{ request()->routeIs('admin.products.*') ? 'bg-surface-variant font-semibold before:absolute before:left-0 before:top-2 before:bottom-2 before:w-[3px] before:bg-[#58a6ff] before:rounded-r-md relative' : 'text-on-surface hover:bg-surface-variant/50 hover:underline' }} px-3 py-1.5 flex items-center gap-2 transition-all rounded-md" href="{{ route('admin.products.index') }}" wire:navigate>
<span class="material-symbols-outlined text-[1rem]">inventory_2</span>
                    Digital Products
                </a>
<a class="font-label-md text-label-md {{ request()->routeIs('admin.product_categories.*') ? 'bg-surface-variant font-semibold before:absolute before:left-0 before:top-2 before:bottom-2 before:w-[3px] before:bg-[#58a6ff] before:rounded-r-md relative' : 'text-on-surface hover:bg-surface-variant/50 hover:underline' }} pl-9 pr-3 py-1.5 flex items-center gap-2 transition-all rounded-md text-sm" href="{{ route('admin.product_categories.index') }}" wire:navigate>
<span class="material-symbols-outlined text-[1rem]">category</span>
                    Categories
                </a>
<a class="font-label-md text-label-md {{ request()->routeIs('admin.product_types.*') ? 'bg-surface-variant font-semibold before:absolute before:left-0 before:top-2 before:bottom-2 before:w-[3px] before:bg-[#58a6ff] before:rounded-r-md relative' : 'text-on-surface hover:bg-surface-variant/50 hover:underline' }} pl-9 pr-3 py-1.5 flex items-center gap-2 transition-all rounded-md text-sm" href="{{ route('admin.product_types.index') }}" wire:navigate>
<span class="material-symbols-outlined text-[1rem]">style</span>
                    Types
                </a>
<a class="font-label-md text-label-md {{ request()->routeIs('admin.orders.*') ? 'bg-surface-variant font-semibold before:absolute before:left-0 before:top-2 before:bottom-2 before:w-[3px] before:bg-[#58a6ff] before:rounded-r-md relative' : 'text-on-surface hover:bg-surface-variant/50 hover:underline' }} px-3 py-1.5 flex items-center gap-2 transition-all rounded-md" href="{{ route('admin.orders.index') }}" wire:navigate>
<span class="material-symbols-outlined text-[1rem]">receipt_long</span>
                    Sales Orders
                </a>
<a class="font-label-md text-label-md {{ request()->routeIs('admin.testimonials.*') ? 'bg-surface-variant font-semibold before:absolute before:left-0 before:top-2 before:bottom-2 before:w-[3px] before:bg-[#58a6ff] before:rounded-r-md relative' : 'text-on-surface hover:bg-surface-variant/50 hover:underline' }} px-3 py-1.5 flex items-center gap-2 transition-all rounded-md" href="{{ route('admin.testimonials.index') }}" wire:navigate>
<span class="material-symbols-outlined text-[1rem]">format_quote</span>
                    Testimonials
                </a>
<a class="font-label-md text-label-md {{ request()->routeIs('admin.messages.*') ? 'bg-surface-variant font-semibold before:absolute before:left-0 before:top-2 before:bottom-2 before:w-[3px] before:bg-[#58a6ff] before:rounded-r-md relative' : 'text-on-surface hover:bg-surface-variant/50 hover:underline' }} px-3 py-1.5 flex items-center gap-2 transition-all rounded-md" href="{{ route('admin.messages.index') }}" wire:navigate>
<span class="material-symbols-outlined text-[1rem]">mail</span>
                    Messages
                </a>
<a class="font-label-md text-label-md {{ request()->routeIs('admin.popup_ads.*') ? 'bg-surface-variant font-semibold before:absolute before:left-0 before:top-2 before:bottom-2 before:w-[3px] before:bg-[#58a6ff] before:rounded-r-md relative' : 'text-on-surface hover:bg-surface-variant/50 hover:underline' }} px-3 py-1.5 flex items-center gap-2 transition-all rounded-md" href="{{ route('admin.popup_ads.index') }}" wire:navigate>
<span class="material-symbols-outlined text-[1rem]">ad_units</span>
                    Popup Ads
                </a>

<!-- Multi-Tenant -->
<div class="pt-3 mt-3 mb-1 border-t border-outline-variant/30 text-xs font-bold text-on-surface-variant tracking-wider px-3">Tenant</div>
<a class="font-label-md text-label-md {{ request()->routeIs('admin.stores.*') ? 'bg-surface-variant font-semibold before:absolute before:left-0 before:top-2 before:bottom-2 before:w-[3px] before:bg-[#58a6ff] before:rounded-r-md relative' : 'text-on-surface hover:bg-surface-variant/50 hover:underline' }} px-3 py-1.5 flex items-center gap-2 transition-all rounded-md" href="{{ route('admin.stores.index') }}" wire:navigate>
<span class="material-symbols-outlined text-[1rem]">storefront</span>
                    Stores
                </a>
<a class="font-label-md text-label-md {{ request()->routeIs('admin.payouts.*') ? 'bg-surface-variant font-semibold before:absolute before:left-0 before:top-2 before:bottom-2 before:w-[3px] before:bg-[#58a6ff] before:rounded-r-md relative' : 'text-on-surface hover:bg-surface-variant/50 hover:underline' }} px-3 py-1.5 flex items-center gap-2 transition-all rounded-md" href="{{ route('admin.payouts.index') }}" wire:navigate>
<span class="material-symbols-outlined text-[1rem]">payments</span>
                    Payouts
                </a>
<a class="font-label-md text-label-md {{ request()->routeIs('admin.gateway_apps.*') ? 'bg-surface-variant font-semibold before:absolute before:left-0 before:top-2 before:bottom-2 before:w-[3px] before:bg-[#58a6ff] before:rounded-r-md relative' : 'text-on-surface hover:bg-surface-variant/50 hover:underline' }} px-3 py-1.5 flex items-center gap-2 transition-all rounded-md" href="{{ route('admin.gateway_apps.index') }}" wire:navigate>
<span class="material-symbols-outlined text-[1rem]">api</span>
                    Gateway Apps
                </a>

                <a class="font-label-md text-label-md {{ request()->routeIs('admin.users.*') ? 'bg-surface-variant font-semibold before:absolute before:left-0 before:top-2 before:bottom-2 before:w-[3px] before:bg-[#58a6ff] before:rounded-r-md relative' : 'text-on-surface hover:bg-surface-variant/50 hover:underline' }} px-3 py-1.5 flex items-center gap-2 transition-all rounded-md" href="{{ route('admin.users.index') }}" wire:navigate>
<span class="material-symbols-outlined text-[1rem]">manage_accounts</span>
                    Users
                </a>
</nav>
</div>
<div class="p-4 border-t border-outline-variant/30 flex items-center gap-3 w-full overflow-hidden">
<img alt="Admin Avatar" class="w-9 h-9 rounded-full bg-surface-variant object-cover flex-shrink-0" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=random"/>
<div class="flex flex-col flex-1 min-w-0">
<span class="font-label-md text-label-md font-bold truncate">{{ auth()->user()->name ?? 'Admin User' }}</span>
<span class="text-xs text-on-surface-variant truncate">{{ auth()->user()->email ?? 'admin@rhantech.com' }}</span>
</div>
<form action="{{ route('logout') }}" method="POST" class="inline flex-shrink-0">
    @csrf
    <button type="submit" class="text-on-surface-variant hover:text-error transition-colors p-1 rounded hover:bg-error/10" title="Logout">
        <span class="material-symbols-outlined text-[1.25rem]">logout</span>
    </button>
</form>
</div>
</aside>
<!-- Main Content Area -->
<main class="flex-1 ml-64 flex flex-col min-h-screen">
<!-- TopNavBar -->
<header class="sticky top-0 z-40 bg-surface dark:bg-[#010409] border-b border-outline-variant/30 flex justify-between items-center h-[56px] px-lg w-full text-on-surface dark:text-white">
<div class="flex items-center">
<h2 class="font-headline-lg-mobile text-headline-lg-mobile md:font-headline-lg md:text-headline-lg font-bold text-on-surface dark:text-white">@yield('title')</h2>
</div>
<div class="flex items-center gap-md">
<x-theme-toggle />
<div class="relative hidden md:block">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant dark:text-gray-400">search</span>
<input class="pl-10 pr-4 py-1.5 bg-background dark:bg-white/10 border border-outline-variant dark:border-gray-600 rounded-md font-body-md text-body-md focus:outline-none focus:border-primary dark:focus:border-blue-500 transition-all w-64 text-on-surface dark:text-white placeholder:text-on-surface-variant dark:placeholder:text-gray-400" placeholder="Type / to search" type="text"/>
</div>
<button class="text-on-surface-variant dark:text-gray-300 hover:text-on-surface dark:hover:text-white hover:bg-surface-variant/50 dark:hover:bg-white/10 p-2 rounded-md transition-colors relative">
<span class="material-symbols-outlined text-[1.25rem]">notifications</span>
</button>
<div class="relative" x-data="{ open: false }">
<button @click="open = !open" @click.outside="open = false" class="text-on-surface-variant dark:text-gray-300 hover:text-on-surface dark:hover:text-white hover:bg-surface-variant/50 dark:hover:bg-white/10 p-2 rounded-md transition-colors focus:outline-none">
<span class="material-symbols-outlined text-[1.25rem]">account_circle</span>
</button>
<div x-show="open" style="display: none;" x-transition class="absolute right-0 mt-2 w-48 bg-surface border border-outline-variant rounded-md shadow-lg py-1 z-50 text-on-surface">
    <div class="px-4 py-2 border-b border-outline-variant/50 mb-1">
        <div class="text-sm font-bold text-on-surface truncate">{{ auth()->user()->name ?? 'Admin' }}</div>
        <div class="text-xs text-on-surface-variant truncate">{{ auth()->user()->email ?? '' }}</div>
    </div>
    <a href="{{ route('home') }}" target="_blank" class="block px-4 py-2 text-sm text-on-surface hover:bg-surface-container-low transition-colors" wire:navigate>
        <span class="flex items-center gap-2"><span class="material-symbols-outlined text-[1rem]">open_in_new</span> View Site</span>
    </a>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-error hover:bg-error/10 transition-colors">
            <span class="flex items-center gap-2"><span class="material-symbols-outlined text-[1rem]">logout</span> Logout</span>
        </button>
    </form>
</div>
</div>
</div>
</header>
<!-- Page Content -->
@yield('content')
</main>
@include('components.theme-manager')
@livewireScripts
</body></html>

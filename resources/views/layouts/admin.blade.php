<!DOCTYPE html>
<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
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
                      "surface-bright": "#f8f9ff",
                      "error": "#ba1a1a",
                      "outline-variant": "#c6c6cd",
                      "on-tertiary-fixed": "#07006c",
                      "tertiary": "#000000",
                      "secondary": "#00687a",
                      "surface": "#f8f9ff",
                      "error-container": "#ffdad6",
                      "on-secondary-fixed-variant": "#004e5c",
                      "on-tertiary": "#ffffff",
                      "inverse-primary": "#bec6e0",
                      "surface-container-highest": "#d3e4fe",
                      "surface-container": "#e5eeff",
                      "tertiary-fixed-dim": "#c0c1ff",
                      "tertiary-fixed": "#e1e0ff",
                      "inverse-on-surface": "#eaf1ff",
                      "on-surface": "#0b1c30",
                      "on-error-container": "#93000a",
                      "surface-container-lowest": "#ffffff",
                      "on-primary-fixed-variant": "#3f465c",
                      "surface-dim": "#cbdbf5",
                      "background": "#f8f9ff",
                      "on-secondary": "#ffffff",
                      "outline": "#76777d",
                      "primary-fixed-dim": "#bec6e0",
                      "on-error": "#ffffff",
                      "surface-container-high": "#dce9ff",
                      "on-primary-container": "#7c839b",
                      "inverse-surface": "#213145",
                      "on-surface-variant": "#45464d",
                      "on-tertiary-fixed-variant": "#2f2ebe",
                      "surface-variant": "#d3e4fe",
                      "secondary-fixed-dim": "#4cd7f6",
                      "tertiary-container": "#07006c",
                      "on-background": "#0b1c30",
                      "primary-container": "#131b2e",
                      "on-primary-fixed": "#131b2e",
                      "primary": "#000000",
                      "surface-tint": "#565e74",
                      "surface-container-low": "#eff4ff",
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
                              "Geist"
                      ],
                      "label-md": [
                              "Geist"
                      ],
                      "body-lg": [
                              "Geist"
                      ],
                      "headline-xl": [
                              "Geist"
                      ],
                      "headline-lg": [
                              "Geist"
                      ],
                      "body-md": [
                              "Geist"
                      ],
                      "code-sm": [
                              "Geist"
                      ],
                      "display-lg": [
                              "Geist"
                      ]
              }
            }
          }
        }
</script>
</head>
<body class="bg-background text-on-background font-body-md min-h-screen flex">
<!-- SideNavBar -->
<aside class="fixed left-0 top-0 h-screen w-64 bg-surface-container-low dark:bg-surface-container-lowest border-r border-outline-variant z-50 flex flex-col justify-between">
<div class="flex flex-col gap-sm p-md">
<div class="mb-lg px-sm">
<h1 class="font-headline-lg text-headline-lg font-black text-primary dark:text-on-primary-container flex items-center gap-2">
    @if(isset($company) && $company->logo)
        <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->company_name }}" class="h-8 w-auto">
    @endif
    {{ $company->company_name ?? 'Admin Panel' }}
</h1>
<p class="font-label-md text-label-md text-on-surface-variant">Management System</p>
</div>
<nav class="flex flex-col gap-xs">
<a class="font-label-md text-label-md {{ request()->routeIs('admin.dashboard') ? 'bg-secondary-container dark:bg-secondary text-on-secondary-container dark:text-on-secondary shadow-sm font-bold translate-x-1' : 'text-on-surface-variant dark:text-on-surface-variant hover:bg-surface-variant/50 hover:bg-surface-variant dark:hover:bg-surface-variant/20' }} px-md py-sm flex items-center gap-sm transition-all rounded-lg" href="{{ route('admin.dashboard') }}">
<span class="material-symbols-outlined">dashboard</span>
                    Dashboard
                </a>
<a class="font-label-md text-label-md {{ request()->routeIs('admin.company.*') ? 'bg-secondary-container dark:bg-secondary text-on-secondary-container dark:text-on-secondary shadow-sm font-bold translate-x-1' : 'text-on-surface-variant dark:text-on-surface-variant hover:bg-surface-variant/50 hover:bg-surface-variant dark:hover:bg-surface-variant/20' }} px-md py-sm flex items-center gap-sm transition-all rounded-lg" href="{{ route('admin.company.index') }}">
<span class="material-symbols-outlined">business</span>
                    Company Profile
                </a>
<a class="font-label-md text-label-md {{ request()->routeIs('admin.services.*') ? 'bg-secondary-container dark:bg-secondary text-on-secondary-container dark:text-on-secondary shadow-sm font-bold translate-x-1' : 'text-on-surface-variant dark:text-on-surface-variant hover:bg-surface-variant/50 hover:bg-surface-variant dark:hover:bg-surface-variant/20' }} px-md py-sm flex items-center gap-sm transition-all rounded-lg" href="{{ route('admin.services.index') }}">
<span class="material-symbols-outlined">layers</span>
                    Services
                </a>
<a class="font-label-md text-label-md {{ request()->routeIs('admin.projects.*') ? 'bg-secondary-container dark:bg-secondary text-on-secondary-container dark:text-on-secondary shadow-sm font-bold translate-x-1' : 'text-on-surface-variant dark:text-on-surface-variant hover:bg-surface-variant/50 hover:bg-surface-variant dark:hover:bg-surface-variant/20' }} px-md py-sm flex items-center gap-sm transition-all rounded-lg" href="{{ route('admin.projects.index') }}">
<span class="material-symbols-outlined">folder</span>
                    Projects
                </a>
<a class="font-label-md text-label-md {{ request()->routeIs('admin.clients.*') ? 'bg-secondary-container dark:bg-secondary text-on-secondary-container dark:text-on-secondary shadow-sm font-bold translate-x-1' : 'text-on-surface-variant dark:text-on-surface-variant hover:bg-surface-variant/50 hover:bg-surface-variant dark:hover:bg-surface-variant/20' }} px-md py-sm flex items-center gap-sm transition-all rounded-lg" href="{{ route('admin.clients.index') }}">
<span class="material-symbols-outlined">groups</span>
                    Clients
                </a>
<a class="font-label-md text-label-md {{ request()->routeIs('admin.products.*') ? 'bg-secondary-container dark:bg-secondary text-on-secondary-container dark:text-on-secondary shadow-sm font-bold translate-x-1' : 'text-on-surface-variant dark:text-on-surface-variant hover:bg-surface-variant/50 hover:bg-surface-variant dark:hover:bg-surface-variant/20' }} px-md py-sm flex items-center gap-sm transition-all rounded-lg" href="{{ route('admin.products.index') }}">
<span class="material-symbols-outlined">inventory_2</span>
                    Digital Products
                </a>
<a class="font-label-md text-label-md {{ request()->routeIs('admin.product_categories.*') ? 'bg-secondary-container dark:bg-secondary text-on-secondary-container dark:text-on-secondary shadow-sm font-bold translate-x-1' : 'text-on-surface-variant dark:text-on-surface-variant hover:bg-surface-variant/50 hover:bg-surface-variant dark:hover:bg-surface-variant/20' }} pl-12 pr-md py-sm flex items-center gap-sm transition-all rounded-lg text-sm" href="{{ route('admin.product_categories.index') }}">
<span class="material-symbols-outlined text-[1rem]">category</span>
                    Categories
                </a>
<a class="font-label-md text-label-md {{ request()->routeIs('admin.product_types.*') ? 'bg-secondary-container dark:bg-secondary text-on-secondary-container dark:text-on-secondary shadow-sm font-bold translate-x-1' : 'text-on-surface-variant dark:text-on-surface-variant hover:bg-surface-variant/50 hover:bg-surface-variant dark:hover:bg-surface-variant/20' }} pl-12 pr-md py-sm flex items-center gap-sm transition-all rounded-lg text-sm" href="{{ route('admin.product_types.index') }}">
<span class="material-symbols-outlined text-[1rem]">style</span>
                    Types
                </a>
<a class="font-label-md text-label-md {{ request()->routeIs('admin.orders.*') ? 'bg-secondary-container dark:bg-secondary text-on-secondary-container dark:text-on-secondary shadow-sm font-bold translate-x-1' : 'text-on-surface-variant dark:text-on-surface-variant hover:bg-surface-variant/50 hover:bg-surface-variant dark:hover:bg-surface-variant/20' }} px-md py-sm flex items-center gap-sm transition-all rounded-lg" href="{{ route('admin.orders.index') }}">
<span class="material-symbols-outlined">receipt_long</span>
                    Sales Orders
                </a>
<a class="font-label-md text-label-md {{ request()->routeIs('admin.testimonials.*') ? 'bg-secondary-container dark:bg-secondary text-on-secondary-container dark:text-on-secondary shadow-sm font-bold translate-x-1' : 'text-on-surface-variant dark:text-on-surface-variant hover:bg-surface-variant/50 hover:bg-surface-variant dark:hover:bg-surface-variant/20' }} px-md py-sm flex items-center gap-sm transition-all rounded-lg" href="{{ route('admin.testimonials.index') }}">
<span class="material-symbols-outlined">format_quote</span>
                    Testimonials
                </a>
<a class="font-label-md text-label-md {{ request()->routeIs('admin.messages.*') ? 'bg-secondary-container dark:bg-secondary text-on-secondary-container dark:text-on-secondary shadow-sm font-bold translate-x-1' : 'text-on-surface-variant dark:text-on-surface-variant hover:bg-surface-variant/50 hover:bg-surface-variant dark:hover:bg-surface-variant/20' }} px-md py-sm flex items-center gap-sm transition-all rounded-lg" href="{{ route('admin.messages.index') }}">
<span class="material-symbols-outlined">mail</span>
                    Messages
                </a>
<a class="font-label-md text-label-md {{ request()->routeIs('admin.users.*') ? 'bg-secondary-container dark:bg-secondary text-on-secondary-container dark:text-on-secondary shadow-sm font-bold translate-x-1' : 'text-on-surface-variant dark:text-on-surface-variant hover:bg-surface-variant/50 hover:bg-surface-variant dark:hover:bg-surface-variant/20' }} px-md py-sm flex items-center gap-sm transition-all rounded-lg" href="{{ route('admin.users.index') }}">
<span class="material-symbols-outlined">manage_accounts</span>
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
<header class="sticky top-0 z-40 bg-surface dark:bg-surface-container border-b border-outline-variant shadow-sm flex justify-between items-center h-16 px-lg w-full">
<div class="flex items-center">
<h2 class="font-headline-lg-mobile text-headline-lg-mobile md:font-headline-lg md:text-headline-lg font-bold text-primary">@yield('title')</h2>
</div>
<div class="flex items-center gap-md">
<div class="relative hidden md:block">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
<input class="pl-10 pr-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg font-body-md text-body-md focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary/20 transition-all w-64 text-on-surface placeholder:text-on-surface-variant" placeholder="Search..." type="text"/>
</div>
<button class="text-on-surface-variant hover:bg-surface-container-high p-2 rounded-full transition-colors relative">
<span class="material-symbols-outlined">notifications</span>
</button>
<div class="relative" x-data="{ open: false }">
<button @click="open = !open" @click.outside="open = false" class="text-on-surface-variant hover:bg-surface-container-high p-2 rounded-full transition-colors focus:outline-none">
<span class="material-symbols-outlined">account_circle</span>
</button>
<div x-show="open" style="display: none;" x-transition class="absolute right-0 mt-2 w-48 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-lg py-1 z-50">
    <div class="px-4 py-2 border-b border-outline-variant/50 mb-1">
        <div class="text-sm font-bold text-on-surface truncate">{{ auth()->user()->name ?? 'Admin' }}</div>
        <div class="text-xs text-on-surface-variant truncate">{{ auth()->user()->email ?? '' }}</div>
    </div>
    <a href="{{ route('home') }}" target="_blank" class="block px-4 py-2 text-sm text-on-surface hover:bg-surface-container-low transition-colors">
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
</body></html>

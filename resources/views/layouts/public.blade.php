<!DOCTYPE html>
<html class="scroll-smooth" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    @include('components.theme-init')
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
    <!-- Alpine.js for interactive components like dropdowns -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
<body class="bg-background text-on-background font-body-md text-body-md antialiased overflow-x-hidden selection:bg-secondary-container selection:text-on-secondary-container flex flex-col min-h-screen">

<style>
/* ── Simplified Search-Focused Navbar ── */
.pub-header {
    background: linear-gradient(135deg, #0a1628 0%, #0d2240 60%, #0a3352 100%);
    position: fixed; top: 0; left: 0; right: 0;
    z-index: 50;
    box-shadow: 0 2px 20px rgba(0,0,0,0.3);
}
.pub-header::after {
    content: '';
    position: absolute; bottom: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, #00d4ff, #00b3cc, transparent);
}
.pub-logo {
    font-size: 20px; font-weight: 900; letter-spacing: -0.5px;
    background: linear-gradient(135deg, #ffffff, #a8e6f0);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
    text-decoration: none;
    display: flex; align-items: center; gap: 8px;
}
.pub-logo .logo-dot {
    width: 7px; height: 7px;
    background: #00d4ff; border-radius: 50%;
    box-shadow: 0 0 10px #00d4ff;
    animation: pubDotPulse 2s infinite;
    flex-shrink: 0;
}
@keyframes pubDotPulse {
    0%,100% { box-shadow: 0 0 8px #00d4ff; }
    50% { box-shadow: 0 0 18px #00d4ff, 0 0 30px rgba(0,212,255,0.4); }
}
.pub-search-wrap {
    display: flex; align-items: center;
    background: rgba(255,255,255,0.09);
    border: 1.5px solid rgba(255,255,255,0.15);
    border-radius: 12px; overflow: hidden;
    transition: border-color 0.2s, background 0.2s;
    flex: 1;
}
.pub-search-wrap:focus-within {
    border-color: #00d4ff;
    background: rgba(255,255,255,0.13);
    box-shadow: 0 0 0 3px rgba(0,212,255,0.15);
}
.pub-search-wrap input {
    background: transparent; border: none !important;
    outline: none !important; box-shadow: none !important;
    color: #fff; font-size: 14px;
    padding: 10px 14px; flex: 1; min-width: 0;
}
.pub-search-wrap input::placeholder { color: rgba(255,255,255,0.4); }
.pub-search-wrap button {
    background: linear-gradient(135deg, #00b3cc, #0077a8);
    border: none; color: #fff;
    padding: 10px 16px;
    cursor: pointer; transition: opacity 0.2s;
    display: flex; align-items: center;
    flex-shrink: 0;
}
.pub-search-wrap button:hover { opacity: 0.85; }
.pub-cta-buy {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 8px 14px;
    background: linear-gradient(135deg, #00b3cc, #0077a8);
    color: #fff; font-size: 13px; font-weight: 700;
    border-radius: 10px; text-decoration: none;
    transition: all 0.2s; white-space: nowrap;
    box-shadow: 0 2px 12px rgba(0,179,204,0.35);
}
.pub-cta-buy:hover { opacity: 0.9; transform: translateY(-1px); }
.pub-cta-sell {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 8px 14px;
    background: rgba(255,255,255,0.1);
    border: 1.5px solid rgba(255,255,255,0.25);
    color: rgba(255,255,255,0.9); font-size: 13px; font-weight: 700;
    border-radius: 10px; text-decoration: none;
    transition: all 0.2s; white-space: nowrap;
}
.pub-cta-sell:hover { background: rgba(255,255,255,0.18); color: #fff; }
</style>

    <!-- Simplified Search-Focused Header -->
    <header class="pub-header">
        <div class="max-w-[1280px] mx-auto px-4 md:px-6 py-3 flex items-center gap-3 md:gap-4">
            {{-- Logo --}}
            <a href="{{ url('/') }}" class="pub-logo shrink-0" wire:navigate>
                <span class="logo-dot"></span>
                <span class="hidden sm:inline">{{ $company->company_name ?? 'rhantech' }}</span>
                <span class="sm:hidden text-sm">{{ Str::limit($company->company_name ?? 'rhantech', 8) }}</span>
            </a>

            {{-- Search Bar (center, largest element) --}}
            <form action="{{ route('products.index') }}" method="GET" class="pub-search-wrap">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari produk digital, aplikasi, source code..."
                    autocomplete="off">
                <button type="submit" aria-label="Cari">
                    <span class="material-symbols-outlined text-[20px]">search</span>
                </button>
            </form>

            {{-- CTAs --}}
            <div class="flex items-center gap-2 shrink-0">
                {{-- Buy Products --}}
                <a href="{{ route('products.index') }}" class="pub-cta-buy" wire:navigate>
                    <span class="material-symbols-outlined text-[16px]">shopping_bag</span>
                    <span class="hidden md:inline">Beli Produk</span>
                </a>

                {{-- Open Store / Dashboard --}}
                @guest
                <a href="{{ route('register') }}" class="pub-cta-sell" wire:navigate>
                    <span class="material-symbols-outlined text-[16px]">storefront</span>
                    <span class="hidden md:inline">Buka Toko</span>
                </a>
                @else
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="flex items-center gap-2 rounded-full ring-2 ring-transparent hover:ring-white/20 transition-all focus:outline-none">
                        <img src="{{ auth()->user()->avatar ? (Str::startsWith(auth()->user()->avatar, 'http') ? auth()->user()->avatar : asset('storage/' . auth()->user()->avatar)) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=0d2240&color=00d4ff' }}"
                            alt="Avatar" class="w-9 h-9 rounded-full object-cover border-2 border-white/20">
                    </button>
                    <div x-show="open" style="display:none;"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-52 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl py-2 z-50">
                        <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-800">
                            <p class="text-sm font-bold text-gray-800 dark:text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800">
                            <span class="material-symbols-outlined text-[18px]">admin_panel_settings</span> Admin
                        </a>
                        @endif
                        <a href="{{ route('tenant.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800">
                            <span class="material-symbols-outlined text-[18px]">storefront</span> Dashboard Toko
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100 dark:border-gray-800 mt-1 pt-1">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20">
                                <span class="material-symbols-outlined text-[18px]">logout</span> Keluar
                            </button>
                        </form>
                    </div>
                </div>
                @endguest

                {{-- Theme toggle --}}
                <x-theme-toggle />
            </div>
        </div>
    </header>

    <main class="flex-1 mt-16 md:mt-[62px]">
        @yield('content')
    </main>

    {{-- Footer: fully hidden (mobile-app experience) --}}

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
    @livewireScripts
</body>
</html>

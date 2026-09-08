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
<style>
/* ── Modern Tech Header ── */
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

<header class="site-header">
    {{-- Top micro bar --}}
    <div class="hidden md:flex max-w-[1280px] mx-auto px-6 items-center justify-between py-1.5 text-[12px]">
        <div class="flex items-center gap-5 text-white/60">
            <a href="{{ route('tenant.dashboard') }}" class="hover:text-white/90 transition-colors">
                <span class="material-symbols-outlined text-[13px] align-middle">storefront</span> Client Area
            </a>
            <span class="h-3 w-px bg-white/15"></span>
            <span class="text-white/40">Ikuti kami:</span>
            @if(isset($company) && $company->facebook)
                <a href="{{ $company->facebook }}" target="_blank" class="hover:text-[#00d4ff] transition-colors">
                    <span class="material-symbols-outlined text-[14px]">facebook</span>
                </a>
            @endif
            @if(isset($company) && $company->instagram)
                <a href="{{ $company->instagram }}" target="_blank" class="hover:text-[#00d4ff] transition-colors">
                    <span class="material-symbols-outlined text-[14px]">photo_camera</span>
                </a>
            @endif
            @if(isset($company) && $company->youtube)
                <a href="{{ $company->youtube }}" target="_blank" class="hover:text-[#00d4ff] transition-colors">
                    <span class="material-symbols-outlined text-[14px]">play_circle</span>
                </a>
            @endif
        </div>
        <div class="flex items-center gap-2">
            @guest
                <a href="{{ route('register') }}" class="header-action-btn">Daftar</a>
                <a href="{{ route('login') }}" class="header-action-btn primary">Masuk</a>
            @else
                <div class="flex items-center gap-2 text-white/75">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0d2240&color=00d4ff" class="w-5 h-5 rounded-full border border-white/30">
                    <span class="text-[12px]">{{ auth()->user()->name }}</span>
                </div>
            @endguest
        </div>
    </div>

    {{-- Main header row --}}
    <div class="max-w-[1280px] mx-auto px-4 md:px-6 py-3 flex flex-wrap md:flex-nowrap items-center justify-between md:justify-start gap-3 md:gap-6">
        {{-- Logo --}}
        <a href="{{ url('/') }}" class="header-logo shrink-0" wire:navigate>
            <span class="logo-dot"></span>
            {{ $company->company_name ?? 'rhantech' }}
        </a>

        {{-- Search --}}
        <div class="flex-1 order-3 md:order-2 w-full md:w-auto">
            <form action="{{ route('products.index') }}" method="GET">
                <div class="search-bar-wrap">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari produk digital, source code, aplikasi...">
                    <button type="submit">
                        <span class="material-symbols-outlined text-[20px]">search</span>
                    </button>
                </div>
            </form>
            <div class="hidden md:flex items-center gap-1 mt-1.5 px-1">
                <span class="text-white/35 text-[11px]">Populer:</span>
                <a href="{{ route('products.index', ['search' => 'Source Code']) }}" class="search-tag">Source Code</a>
                <span class="search-tag-sep">·</span>
                <a href="{{ route('products.index', ['search' => 'Laravel']) }}" class="search-tag">Laravel</a>
                <span class="search-tag-sep">·</span>
                <a href="{{ route('products.index', ['search' => 'CodeIgniter']) }}" class="search-tag">CodeIgniter</a>
                <span class="search-tag-sep">·</span>
                <a href="{{ route('products.index', ['search' => 'React Native']) }}" class="search-tag">React Native</a>
                <span class="search-tag-sep">·</span>
                <a href="{{ route('products.index', ['search' => 'Flutter']) }}" class="search-tag">Flutter</a>
            </div>
        </div>

        {{-- Right actions --}}
        <div class="flex items-center gap-1 shrink-0 order-2 md:order-3">
            {{-- Cart --}}
            @php $cartCount = count(session('cart', [])); @endphp
            <a href="{{ route('cart.index') }}" class="header-action-btn relative" title="Keranjang">
                <span class="material-symbols-outlined text-[22px]">shopping_cart</span>
                <span data-cart-count
                    class="absolute -top-1 -right-1 bg-[#00d4ff] text-[#0a1628] text-[9px] font-black px-1.5 py-0.5 rounded-full min-w-[18px] text-center leading-none"
                    style="{{ $cartCount > 0 ? '' : 'display:none' }}">{{ $cartCount }}</span>
            </a>
            {{-- Mobile login --}}
            @guest
            <a href="{{ route('login') }}" class="header-action-btn primary md:hidden text-xs px-3 py-2">Masuk</a>
            @endguest
        </div>
    </div>
</header>

    <main class="flex-1 mt-[90px] md:mt-[108px]">
        @yield('content')
    </main>


    <!-- Footer (Hidden on mobile for app-like search & catalog experience) -->
    <footer aria-label="Footer" class="hidden md:block bg-surface-container dark:bg-surface-container-lowest text-on-surface dark:text-on-surface-variant font-body-md text-body-md font-label-md text-label-md w-full border-t border-outline-variant mt-auto">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-lg px-4 md:px-lg py-12 md:py-2xl max-w-container-max mx-auto">
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
                    <li><a class="text-on-surface-variant dark:text-on-surface-variant/60 hover:underline hover:text-primary transition-colors" href="{{ url('/#about') }}">About Us</a></li>
                    <li><a class="text-on-surface-variant dark:text-on-surface-variant/60 hover:underline hover:text-primary transition-colors" href="{{ url('/#services') }}">Services</a></li>
                    <li><a class="text-on-surface-variant dark:text-on-surface-variant/60 hover:underline hover:text-primary transition-colors" href="{{ url('/projects') }}" wire:navigate>Projects</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-label-md text-label-md text-on-background dark:text-white font-bold mb-4 uppercase tracking-wider">Support</h4>
                <ul class="flex flex-col gap-3">
                    <li><a class="text-on-surface-variant dark:text-on-surface-variant/60 hover:underline hover:text-primary transition-colors" href="{{ url('/contact') }}" wire:navigate>Contact Us</a></li>
                    <li><a class="text-on-surface-variant dark:text-on-surface-variant/60 hover:underline hover:text-primary transition-colors" href="{{ route('login') }}" wire:navigate>Admin Login</a></li>
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
    <div id="testimonial-toast" class="fixed bottom-4 left-4 max-w-sm w-full bg-surface-container-high rounded-md shadow-[0px_20px_25px_-5px_rgba(15,23,42,0.1)] border border-outline-variant p-md transform translate-y-12 opacity-0 pointer-events-none transition-all duration-500 z-50 hidden md:flex gap-md items-start">
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
    
    @include('components.chat-widget')

    @livewireScripts
</body>
</html>

<!DOCTYPE html>
<html class="scroll-smooth" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', ($company->company_name ?? 'rhantech') . ' - We Build Digital Experiences')</title>
    <meta name="description" content="@yield('meta_description', $company->about_text ?? 'We build scalable, modern, and impactful digital solutions for businesses worldwide.')"/>
    <meta name="keywords" content="digital agency, web development, mobile app development, UI/UX design, cloud infrastructure"/>
    <meta property="og:title" content="@yield('title', ($company->company_name ?? 'rhantech') . ' - We Build Digital Experiences')"/>
    <meta property="og:description" content="@yield('meta_description', $company->about_text ?? 'We build scalable, modern, and impactful digital solutions for businesses worldwide.')"/>
    <meta property="og:image" content="@yield('meta_image', isset($company) && $company->logo ? asset('storage/'.$company->logo) : '')"/>
    <meta property="og:type" content="website"/>
    <meta name="twitter:card" content="summary_large_image"/>
    <link rel="icon" href="{{ isset($company) && $company->favicon ? asset('storage/'.$company->favicon) : asset('favicon.ico') }}" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "secondary-container": "#57dffe",
                        "on-tertiary-container": "#7073ff",
                        "surface-container-low": "#eff4ff",
                        "tertiary-fixed-dim": "#c0c1ff",
                        "secondary-fixed-dim": "#4cd7f6",
                        "surface-variant": "#d3e4fe",
                        "background": "#f8f9ff",
                        "on-secondary-container": "#006172",
                        "error-container": "#ffdad6",
                        "surface-dim": "#cbdbf5",
                        "on-secondary-fixed-variant": "#004e5c",
                        "surface-container-lowest": "#ffffff",
                        "secondary": "#00687a",
                        "surface-container-highest": "#d3e4fe",
                        "tertiary-container": "#07006c",
                        "on-primary": "#ffffff",
                        "inverse-surface": "#213145",
                        "on-secondary": "#ffffff",
                        "on-error-container": "#93000a",
                        "inverse-on-surface": "#eaf1ff",
                        "on-primary-fixed-variant": "#3f465c",
                        "on-error": "#ffffff",
                        "inverse-primary": "#bec6e0",
                        "outline": "#76777d",
                        "outline-variant": "#c6c6cd",
                        "surface": "#f8f9ff",
                        "surface-tint": "#565e74",
                        "surface-container-high": "#dce9ff",
                        "on-background": "#0b1c30",
                        "on-surface": "#0b1c30",
                        "on-primary-container": "#7c839b",
                        "tertiary": "#000000",
                        "primary": "#000000",
                        "on-secondary-fixed": "#001f26",
                        "tertiary-fixed": "#e1e0ff",
                        "error": "#ba1a1a",
                        "secondary-fixed": "#acedff",
                        "on-tertiary": "#ffffff",
                        "on-primary-fixed": "#131b2e",
                        "primary-fixed-dim": "#bec6e0",
                        "on-tertiary-fixed-variant": "#2f2ebe",
                        "on-tertiary-fixed": "#07006c",
                        "on-surface-variant": "#45464d",
                        "primary-fixed": "#dae2fd",
                        "surface-bright": "#f8f9ff",
                        "primary-container": "#131b2e",
                        "surface-container": "#e5eeff"
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
                        "headline-xl": ["Geist"],
                        "body-md": ["Geist"],
                        "headline-lg": ["Geist"],
                        "display-lg-mobile": ["Geist"],
                        "body-lg": ["Geist"],
                        "code-sm": ["Geist"],
                        "label-md": ["Geist"],
                        "display-lg": ["Geist"]
                    },
                    "fontSize": {
                        "headline-xl": ["48px", { "lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "600" }],
                        "body-md": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "headline-lg": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                        "display-lg-mobile": ["40px", { "lineHeight": "48px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "body-lg": ["18px", { "lineHeight": "32px", "fontWeight": "400" }],
                        "code-sm": ["13px", { "lineHeight": "18px", "fontWeight": "400" }],
                        "label-md": ["14px", { "lineHeight": "20px", "letterSpacing": "0.02em", "fontWeight": "500" }],
                        "display-lg": ["72px", { "lineHeight": "80px", "letterSpacing": "-0.04em", "fontWeight": "700" }]
                    }
                }
            }
        }
    </script>
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
</head>
<body class="bg-background text-on-background font-body-md text-body-md antialiased overflow-x-hidden selection:bg-secondary-container selection:text-on-secondary-container flex flex-col min-h-screen">
    <!-- TopNavBar -->
    <nav aria-label="Main Navigation" class="bg-surface/80 dark:bg-surface-container-lowest/80 backdrop-blur-md text-primary dark:text-on-surface font-headline-lg text-headline-lg font-body-md text-body-md font-label-md text-label-md fixed top-0 w-full z-50 border-b border-outline-variant/30 shadow-sm">
        <div class="flex justify-between items-center px-lg py-md max-w-container-max mx-auto">
            <a aria-label="{{ $company->company_name ?? 'rhantech' }} Home" class="flex items-center gap-2 text-body-lg font-headline-xl font-bold text-primary dark:text-on-primary-container" href="{{ url('/') }}">
                <img src="{{ isset($company) && $company->logo ? asset('storage/' . $company->logo) : asset('logo.png') }}" alt="{{ $company->company_name ?? 'rhantech' }}" class="h-8 w-auto">
                {{ $company->company_name ?? 'rhantech' }}
            </a>
            <div class="hidden md:flex items-center gap-lg">
                <a class="text-secondary dark:text-secondary-fixed-dim hover:text-secondary transition-colors duration-200" href="{{ url('/#home') }}">Home</a>
                <a class="text-on-surface-variant dark:text-on-surface-variant/80 hover:text-secondary transition-colors duration-200" href="{{ url('/#about') }}">About</a>
                <a class="text-secondary dark:text-secondary-fixed-dim hover:text-secondary transition-colors duration-200" href="{{ url('/#services') }}">Services</a>
                <a class="text-secondary dark:text-secondary-fixed-dim hover:text-secondary transition-colors duration-200" href="{{ route('projects.index') }}">Projects</a>
                <a class="text-secondary dark:text-secondary-fixed-dim hover:text-secondary transition-colors duration-200" href="{{ route('products.index') }}">Store</a>
                <a class="text-secondary dark:text-secondary-fixed-dim hover:text-secondary transition-colors duration-200" href="{{ route('clients.index') }}">Clients</a>
                <a class="text-on-surface-variant dark:text-on-surface-variant/80 hover:text-secondary transition-colors duration-200" href="{{ url('/contact') }}">Contact</a>
            </div>
            <a class="hidden md:inline-flex items-center justify-center px-6 py-2.5 bg-[#06B6D4] text-on-primary rounded-lg font-label-md text-label-md hover:opacity-90 transition-opacity" href="{{ url('/contact') }}">
                Let's Talk
            </a>
            <button aria-label="Open menu" class="md:hidden p-2 text-on-surface">
                <span class="material-symbols-outlined" data-icon="menu">menu</span>
            </button>
        </div>
    </nav>
    
    <main class="flex-1 mt-24">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer aria-label="Footer" class="bg-surface-container dark:bg-surface-container-lowest text-on-surface dark:text-on-surface-variant font-body-md text-body-md font-label-md text-label-md w-full border-t border-outline-variant mt-auto">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-lg px-lg py-2xl max-w-container-max mx-auto">
            <div class="col-span-1 md:col-span-2">
                <a class="font-headline-lg text-headline-lg font-black text-primary dark:text-on-primary-container flex items-center gap-2 mb-4" href="{{ url('/') }}">
                    <img src="{{ isset($company) && $company->logo ? asset('storage/' . $company->logo) : asset('logo.png') }}" alt="{{ $company->company_name ?? 'rhantech' }}" class="h-8 w-auto">
                    {{ $company->company_name ?? 'rhantech' }}
                </a>
                <p class="text-on-surface-variant max-w-sm mb-6">Building scalable, modern, and impactful digital solutions for businesses worldwide. Precision engineering meets elegant design.</p>
                <div class="font-label-md text-label-md text-on-surface-variant/60">
                    © {{ date('Y') }} {{ $company->company_name ?? 'rhantech' }}. All rights reserved.
                </div>
            </div>
            <div>
                <h4 class="font-label-md text-label-md text-primary font-bold mb-4 uppercase tracking-wider">Company</h4>
                <ul class="flex flex-col gap-3">
                    <li><a class="text-on-surface-variant dark:text-on-surface-variant/60 hover:underline hover:text-primary transition-colors" href="{{ url('/#about') }}">About Us</a></li>
                    <li><a class="text-on-surface-variant dark:text-on-surface-variant/60 hover:underline hover:text-primary transition-colors" href="{{ url('/#services') }}">Services</a></li>
                    <li><a class="text-on-surface-variant dark:text-on-surface-variant/60 hover:underline hover:text-primary transition-colors" href="{{ url('/projects') }}">Projects</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-label-md text-label-md text-primary font-bold mb-4 uppercase tracking-wider">Support</h4>
                <ul class="flex flex-col gap-3">
                    <li><a class="text-on-surface-variant dark:text-on-surface-variant/60 hover:underline hover:text-primary transition-colors" href="{{ url('/contact') }}">Contact Us</a></li>
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
    <div id="testimonial-toast" class="fixed bottom-4 right-4 max-w-sm w-full bg-surface-container-high rounded-xl shadow-[0px_20px_25px_-5px_rgba(15,23,42,0.1)] border border-outline-variant p-md transform translate-y-12 opacity-0 pointer-events-none transition-all duration-500 z-50 flex gap-md items-start">
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

        // Start showing toasts every 15 seconds
        if (toastData.length > 0) {
            setTimeout(() => {
                showNextToast();
                setInterval(showNextToast, 15000);
            }, 3000); // initial delay
        }
    </script>
    @endif
</body>
</html>

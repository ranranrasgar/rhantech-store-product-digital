<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('components.theme-init')
    @include('components.pwa-head')
    <title>Log In — {{ $company->company_name ?? 'Rhantech' }} Seller & Customer</title>
    <link rel="icon" type="image/png" href="{{ isset($company) && $company->favicon ? '/storage/'.$company->favicon : '/favicon.ico' }}" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "rgb(var(--theme-primary, 6 182 212) / <alpha-value>)",
                        secondary: "rgb(var(--theme-secondary, 2 132 199) / <alpha-value>)",
                        surface: "rgb(var(--theme-surface, 255 255 255) / <alpha-value>)",
                        "on-surface": "rgb(var(--theme-on-surface, 27 28 30) / <alpha-value>)",
                        "on-surface-variant": "rgb(var(--theme-on-surface-variant, 90 95 102) / <alpha-value>)",
                        "surface-container": "rgb(var(--theme-surface-container, 243 244 246) / <alpha-value>)",
                        "surface-container-high": "rgb(var(--theme-surface-high, 230 234 238) / <alpha-value>)",
                        "surface-container-low": "rgb(var(--theme-surface-low, 248 249 250) / <alpha-value>)",
                        "surface-container-lowest": "rgb(var(--theme-surface-lowest, 255 255 255) / <alpha-value>)",
                        "outline-variant": "rgb(var(--theme-outline-variant, 226 232 240) / <alpha-value>)",
                        background: "rgb(var(--theme-background, 248 250 252) / <alpha-value>)",
                        "on-background": "rgb(var(--theme-on-background, 15 23 42) / <alpha-value>)",
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                        }
                    }
                }
            }
        };
    </script>
    @include('components.theme-styles')
    <!-- Cloudflare Turnstile -->
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 20;
        }
    </style>
</head>
<body class="min-h-full bg-[#f6f9fa] dark:bg-[#0d1117] text-slate-800 dark:text-slate-200 antialiased flex flex-col justify-between transition-colors duration-200">

    <!-- Top Header / Navbar (Marketplace Style) -->
    <header class="w-full bg-white dark:bg-[#161b22] border-b border-slate-200/80 dark:border-slate-800/80 py-3.5 px-4 sm:px-8 lg:px-16">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5">
                    @if(isset($company) && $company->logo)
                        <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->company_name ?? 'Logo' }}" class="h-9 w-auto object-contain">
                    @else
                        <div class="w-9 h-9 rounded-xl bg-sky-500 flex items-center justify-center text-white font-bold text-base">
                            {{ strtoupper(substr($company->company_name ?? 'R', 0, 1)) }}
                        </div>
                    @endif
                    <span class="text-xl font-bold tracking-tight text-sky-600 dark:text-sky-400">
                        {{ $company->company_name ?? 'Rhantech' }}
                    </span>
                </a>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('help.show', 'panduan-keamanan-akun-solusi-lupa-password') }}" 
                   title="Panduan Masuk & Keamanan Akun" 
                   aria-label="Panduan Masuk & Keamanan Akun" 
                   class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-500 hover:text-sky-600 dark:text-slate-400 dark:hover:text-sky-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    <span class="material-symbols-outlined text-[22px]">help_center</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Section (Split Layout) -->
    <main class="flex-1 flex items-center justify-center py-6 sm:py-10 px-4 sm:px-8 lg:px-16">
        <div class="max-w-6xl w-full mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
            
            <!-- Left Side: Marketplace Illustration & Branding (Desktop Only) -->
            <div class="hidden lg:flex lg:col-span-7 flex-col items-start text-left space-y-6">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white leading-tight">
                        Pusat Jual Beli, Bio Link &amp; <span class="text-sky-600 dark:text-sky-400">Kreator Digital</span>
                    </h1>
                    <p class="mt-3 text-sm sm:text-base text-slate-600 dark:text-slate-300 max-w-lg leading-relaxed">
                        Kelola tokomu, buat bio link medsos, pantau transaksi pelanggan, dan dapatkan akses instan ke seluruh template, source code, dan aset digital.
                    </p>
                </div>

                <!-- Showcase Illustration / Feature Card -->
                <div class="relative w-full max-w-md bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 sm:p-8">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white dark:bg-[#161b22] p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-sky-50 dark:bg-sky-950/40 flex items-center justify-center text-sky-600 dark:text-sky-400">
                                <span class="material-symbols-outlined text-2xl">hub</span>
                            </div>
                            <div class="text-left">
                                <div class="text-xs text-slate-500 dark:text-slate-400">Kreator &amp; Toko</div>
                                <div class="text-xs font-bold text-slate-800 dark:text-white">Toko &amp; Bio Link</div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#161b22] p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-sky-50 dark:bg-sky-950/40 flex items-center justify-center text-sky-600 dark:text-sky-400">
                                <span class="material-symbols-outlined text-2xl">flash_on</span>
                            </div>
                            <div class="text-left">
                                <div class="text-xs text-slate-500 dark:text-slate-400">Pengiriman</div>
                                <div class="text-xs font-bold text-slate-800 dark:text-white">Instan Otomatis</div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#161b22] p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-700 dark:text-slate-300">
                                <span class="material-symbols-outlined text-2xl">verified_user</span>
                            </div>
                            <div class="text-left">
                                <div class="text-xs text-slate-500 dark:text-slate-400">Keamanan</div>
                                <div class="text-xs font-bold text-slate-800 dark:text-white">Pembayaran Aman</div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#161b22] p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-700 dark:text-slate-300">
                                <span class="material-symbols-outlined text-2xl">chat</span>
                            </div>
                            <div class="text-left">
                                <div class="text-xs text-slate-500 dark:text-slate-400">Live Chat</div>
                                <div class="text-xs font-bold text-slate-800 dark:text-white">Toko & Buyer</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Clean Auth Card (Flat Modern Style) -->
            <div class="lg:col-span-5 w-full max-w-md mx-auto">
                <div class="bg-white dark:bg-[#161b22] p-6 sm:p-8 rounded-2xl border border-slate-200 dark:border-slate-800">
                    
                    <div class="mb-5">
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                            Log In
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            Masuk ke akun Anda untuk melanjutkan
                        </p>
                    </div>

                    @if($errors->any())
                        <div class="mb-5 rounded-xl bg-rose-50 dark:bg-rose-950/40 p-3.5 border border-rose-200 dark:border-rose-800/60">
                            <div class="flex items-start gap-2.5">
                                <span class="material-symbols-outlined text-rose-600 dark:text-rose-400 text-lg flex-shrink-0 mt-0.5">error</span>
                                <div class="text-xs text-rose-700 dark:text-rose-300 font-medium">
                                    {{ $errors->first() }}
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('status'))
                        <div class="mb-5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 p-3.5 border border-emerald-200 dark:border-emerald-800/60">
                            <div class="flex items-start gap-2.5">
                                <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-lg flex-shrink-0 mt-0.5">check_circle</span>
                                <div class="text-xs text-emerald-700 dark:text-emerald-300 font-medium">
                                    {{ session('status') }}
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <!-- Email Input -->
                        <div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                placeholder="Email / Username"
                                class="block w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3.5 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 focus:outline-none transition-all">
                        </div>

                        <!-- Password Input -->
                        <div class="relative">
                            <input id="password" type="password" name="password" required
                                placeholder="Password"
                                class="block w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3.5 pr-10 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 focus:outline-none transition-all">
                            <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                                <span id="password-toggle-icon" class="material-symbols-outlined text-[18px]">visibility</span>
                            </button>
                        </div>

                        <!-- Cloudflare Turnstile Widget -->
                        <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key', env('TURNSTILE_SITE_KEY')) }}" data-theme="auto" data-size="flexible"></div>

                        <!-- Submit Button (Solid Primary Flat Color) -->
                        <div class="pt-1">
                            <button type="submit" 
                                class="w-full py-3 px-4 rounded-lg text-sm font-bold tracking-wide uppercase text-white bg-sky-500 hover:bg-sky-600 active:scale-[0.99] transition-colors cursor-pointer">
                                LOG IN
                            </button>
                        </div>

                        <!-- Forgot Password & Remember -->
                        <div class="flex items-center justify-between text-xs pt-1">
                            <label class="flex items-center gap-2 cursor-pointer text-slate-600 dark:text-slate-400">
                                <input type="checkbox" name="remember" class="rounded border-slate-300 dark:border-slate-700 text-sky-600 focus:ring-sky-500">
                                <span>Ingat Saya</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sky-600 dark:text-sky-400 hover:underline">
                                    Lupa Password?
                                </a>
                            @endif
                        </div>
                    </form>

                    <!-- Divider -->
                    <div class="relative my-5 text-center">
                        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200 dark:border-slate-800"></div></div>
                        <span class="relative bg-white dark:bg-[#161b22] px-3 text-xs text-slate-400 uppercase">atau masuk dengan</span>
                    </div>

                    <!-- Google Sign In Button (Flat Border) -->
                    <div>
                        <a href="{{ route('social.redirect', 'google') }}" 
                            class="w-full flex items-center justify-center gap-2.5 py-2.5 px-4 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-[#161b22] hover:bg-slate-50 dark:hover:bg-slate-800 text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200 transition-colors">
                            <svg class="w-4 h-4" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                            </svg>
                            <span>Lanjutkan dengan Google</span>
                        </a>
                    </div>

                    <!-- Register Link -->
                    <div class="mt-5 text-center text-xs sm:text-sm text-slate-600 dark:text-slate-400">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="font-bold text-sky-600 dark:text-sky-400 hover:underline ml-1">
                            Daftar Sekarang
                        </a>
                    </div>
                </div>

                <!-- Back to Home / Catalog -->
                <div class="mt-4 text-center">
                    <a href="{{ url('/products') }}" class="text-xs text-slate-500 hover:text-sky-600 dark:text-slate-400 dark:hover:text-sky-400 inline-flex items-center gap-1">
                        <span class="material-symbols-outlined text-[15px]">arrow_back</span>
                        <span>Kembali ke Katalog Produk</span>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Bottom Footer (Shopee Marketplace Style) -->
    <footer class="w-full border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-[#161b22] py-4 px-4 text-center text-xs text-slate-500 dark:text-slate-400">
        <p>&copy; {{ date('Y') }} {{ $company->company_name ?? 'Rhantech' }}. Hak Cipta Dilindungi.</p>
    </footer>

    @include('components.theme-manager')

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('password-toggle-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                passwordInput.type = 'password';
                icon.textContent = 'visibility';
            }
        }
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('components.theme-init')
    @include('components.pwa-head')
    <title>Daftar Buka Toko — {{ $company->company_name ?? 'Rhantech' }}</title>
    <link rel="icon" type="image/png" href="{{ isset($company) && $company->favicon ? '/storage/'.$company->favicon : '/favicon.ico' }}" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "rgb(var(--theme-primary, 0 179 204) / <alpha-value>)",
                        secondary: "rgb(var(--theme-secondary, 0 104 122) / <alpha-value>)",
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
                            50: '#e6f7f9',
                            100: '#cceef3',
                            500: '#00838f',
                            600: '#00727d',
                            700: '#005b64',
                            800: '#00474e',
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
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#00838f] to-teal-500 flex items-center justify-center text-white font-black text-base shadow-sm">
                            {{ strtoupper(substr($company->company_name ?? 'R', 0, 1)) }}
                        </div>
                    @endif
                    <span class="text-xl font-bold tracking-tight text-[#00838f] dark:text-teal-400">
                        {{ $company->company_name ?? 'Rhantech' }}
                    </span>
                </a>
                <span class="hidden sm:inline-block text-xl text-slate-300 dark:text-slate-600 font-light">|</span>
                <span class="text-base sm:text-lg font-semibold text-slate-800 dark:text-slate-200">
                    Daftar Akun
                </span>
            </div>

            <div class="flex items-center gap-4">
                <x-theme-toggle />
                <a href="{{ route('help.index') }}" class="text-xs sm:text-sm font-semibold text-[#00838f] dark:text-teal-400 hover:underline inline-flex items-center gap-1">
                    <span class="material-symbols-outlined text-[18px]">help_center</span>
                    <span>Pusat Bantuan</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Section (Split Layout) -->
    <main class="flex-1 flex items-center justify-center py-10 px-4 sm:px-8 lg:px-16">
        <div class="max-w-6xl w-full mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
            
            <!-- Left Side: Marketplace Illustration & Branding -->
            <div class="lg:col-span-7 flex flex-col items-center lg:items-start text-center lg:text-left space-y-6">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white leading-tight">
                        Mulai Jual Produk, Bio Link &amp; <span class="text-[#00838f] dark:text-teal-400">Raih Penghasilan</span>
                    </h1>
                    <p class="mt-3 text-sm sm:text-base text-slate-600 dark:text-slate-300 max-w-lg leading-relaxed">
                        Bergabunglah dengan ribuan kreator &amp; developer. Buat toko digital atau bio link medsos, terima pembayaran otomatis, dan cairkan saldo kapan saja.
                    </p>
                </div>

                <!-- Showcase Feature Grid -->
                <div class="relative w-full max-w-md bg-gradient-to-br from-teal-50 to-cyan-50/50 dark:from-slate-800/60 dark:to-teal-950/20 border border-teal-100 dark:border-slate-700/60 rounded-3xl p-6 sm:p-8 shadow-sm">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white dark:bg-[#161b22] p-4 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-teal-500/10 flex items-center justify-center text-[#00838f] dark:text-teal-400">
                                <span class="material-symbols-outlined text-2xl">hub</span>
                            </div>
                            <div class="text-left">
                                <div class="text-xs text-slate-500 dark:text-slate-400">Halaman Kreator</div>
                                <div class="text-xs font-bold text-slate-800 dark:text-white">Toko &amp; Bio Link</div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#161b22] p-4 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-cyan-500/10 flex items-center justify-center text-cyan-600 dark:text-cyan-400">
                                <span class="material-symbols-outlined text-2xl">payments</span>
                            </div>
                            <div class="text-left">
                                <div class="text-xs text-slate-500 dark:text-slate-400">Payout Cepat</div>
                                <div class="text-xs font-bold text-slate-800 dark:text-white">Tarik Saldo Instan</div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#161b22] p-4 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                                <span class="material-symbols-outlined text-2xl">security</span>
                            </div>
                            <div class="text-left">
                                <div class="text-xs text-slate-500 dark:text-slate-400">Anti Pembajakan</div>
                                <div class="text-xs font-bold text-slate-800 dark:text-white">Link Aman</div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#161b22] p-4 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                                <span class="material-symbols-outlined text-2xl">monitoring</span>
                            </div>
                            <div class="text-left">
                                <div class="text-xs text-slate-500 dark:text-slate-400">Statistik</div>
                                <div class="text-xs font-bold text-slate-800 dark:text-white">Laporan Real-time</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Clean Auth Card (Shopee/Tokopedia Form Style) -->
            <div class="lg:col-span-5 w-full max-w-md mx-auto">
                <div class="bg-white dark:bg-[#161b22] p-6 sm:p-8 rounded-2xl shadow-lg shadow-slate-200/60 dark:shadow-black/50 border border-slate-200/90 dark:border-slate-800">
                    
                    <div class="mb-5">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">
                            Daftar Akun Baru
                        </h2>
                    </div>

                    @if($errors->any())
                        <div class="mb-5 rounded-xl bg-rose-50 dark:bg-rose-950/40 p-3.5 border border-rose-200 dark:border-rose-800/60">
                            <div class="flex items-start gap-2.5">
                                <span class="material-symbols-outlined text-rose-600 dark:text-rose-400 text-lg flex-shrink-0 mt-0.5">error</span>
                                <div class="text-xs text-rose-700 dark:text-rose-300 font-medium">
                                    <ul class="list-disc pl-4 space-y-0.5">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" class="space-y-3.5">
                        @csrf

                        <!-- Name Input -->
                        <div>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                                placeholder="Nama Lengkap"
                                class="block w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:border-[#00838f] focus:ring-1 focus:ring-[#00838f] focus:outline-none transition-all">
                        </div>

                        <!-- Email Input -->
                        <div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                placeholder="Alamat Email"
                                class="block w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:border-[#00838f] focus:ring-1 focus:ring-[#00838f] focus:outline-none transition-all">
                        </div>

                        <!-- Password Input -->
                        <div>
                            <input id="password" type="password" name="password" required
                                placeholder="Password (Min. 8 Karakter)"
                                class="block w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:border-[#00838f] focus:ring-1 focus:ring-[#00838f] focus:outline-none transition-all">
                        </div>

                        <!-- Password Confirmation Input -->
                        <div>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                placeholder="Konfirmasi Password"
                                class="block w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:border-[#00838f] focus:ring-1 focus:ring-[#00838f] focus:outline-none transition-all">
                        </div>

                        <!-- Cloudflare Turnstile Widget -->
                        <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key', env('TURNSTILE_SITE_KEY')) }}" data-theme="auto" data-size="flexible"></div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit" 
                                class="w-full py-3 px-4 rounded-lg shadow-sm text-sm font-bold tracking-wide uppercase text-white bg-[#00838f] hover:bg-[#00727d] active:scale-[0.99] transition-all">
                                DAFTAR SEKARANG
                            </button>
                        </div>
                    </form>

                    <!-- Divider -->
                    <div class="relative my-5 text-center">
                        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200 dark:border-slate-800"></div></div>
                        <span class="relative bg-white dark:bg-[#161b22] px-3 text-xs text-slate-400 uppercase">atau daftar dengan</span>
                    </div>

                    <!-- Google Sign Up Button -->
                    <div>
                        <a href="{{ route('social.redirect', 'google') }}" 
                            class="w-full flex items-center justify-center gap-2.5 py-2.5 px-4 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-[#161b22] hover:bg-slate-50 dark:hover:bg-slate-800/80 text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200 shadow-sm transition-all">
                            <svg class="w-4 h-4" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                            </svg>
                            <span>Daftar dengan Google</span>
                        </a>
                    </div>

                    <!-- Login Link -->
                    <div class="mt-5 text-center text-xs sm:text-sm text-slate-600 dark:text-slate-400">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="font-bold text-[#00838f] dark:text-teal-400 hover:underline ml-1">
                            Masuk di sini
                        </a>
                    </div>
                </div>

                <!-- Back to Home / Catalog -->
                <div class="mt-4 text-center">
                    <a href="{{ url('/products') }}" class="text-xs text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 inline-flex items-center gap-1">
                        <span class="material-symbols-outlined text-[15px]">arrow_back</span>
                        <span>Kembali ke Katalog Produk</span>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Bottom Footer (Marketplace Style) -->
    <footer class="w-full border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-[#161b22] py-4 px-4 text-center text-xs text-slate-500 dark:text-slate-400">
        <p>&copy; {{ date('Y') }} {{ $company->company_name ?? 'Rhantech' }}. Hak Cipta Dilindungi.</p>
    </footer>

    @include('components.theme-manager')
</body>
</html>

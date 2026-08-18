<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('components.theme-init')
    <title>Log In — {{ $company->company_name ?? 'Rhantech' }} Seller & Customer</title>
    <link rel="icon" href="{{ isset($company) && $company->favicon ? asset('storage/'.$company->favicon) : asset('favicon.ico') }}" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Cloudflare Turnstile -->
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
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
        }
    </script>
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
                    Log In
                </span>
            </div>

            <div class="flex items-center gap-4">
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
                        Pusat Jual Beli & Kelola <span class="text-[#00838f] dark:text-teal-400">Produk Digital</span>
                    </h1>
                    <p class="mt-3 text-sm sm:text-base text-slate-600 dark:text-slate-300 max-w-lg leading-relaxed">
                        Kelola tokomu, pantau transaksi pelanggan, dan dapatkan akses instan ke seluruh template, source code, dan aset digital terverifikasi.
                    </p>
                </div>

                <!-- Showcase Illustration / Feature Card -->
                <div class="relative w-full max-w-md bg-gradient-to-br from-teal-50 to-cyan-50/50 dark:from-slate-800/60 dark:to-teal-950/20 border border-teal-100 dark:border-slate-700/60 rounded-3xl p-6 sm:p-8 shadow-sm">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white dark:bg-[#161b22] p-4 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-teal-500/10 flex items-center justify-center text-[#00838f] dark:text-teal-400">
                                <span class="material-symbols-outlined text-2xl">storefront</span>
                            </div>
                            <div class="text-left">
                                <div class="text-xs text-slate-500 dark:text-slate-400">Merchant</div>
                                <div class="text-xs font-bold text-slate-800 dark:text-white">Buka Toko</div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#161b22] p-4 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-cyan-500/10 flex items-center justify-center text-cyan-600 dark:text-cyan-400">
                                <span class="material-symbols-outlined text-2xl">flash_on</span>
                            </div>
                            <div class="text-left">
                                <div class="text-xs text-slate-500 dark:text-slate-400">Pengiriman</div>
                                <div class="text-xs font-bold text-slate-800 dark:text-white">Instan Otomatis</div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#161b22] p-4 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                                <span class="material-symbols-outlined text-2xl">verified_user</span>
                            </div>
                            <div class="text-left">
                                <div class="text-xs text-slate-500 dark:text-slate-400">Keamanan</div>
                                <div class="text-xs font-bold text-slate-800 dark:text-white">Pembayaran Aman</div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#161b22] p-4 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
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

            <!-- Right Side: Clean Auth Card (Shopee/Tokopedia Form Style) -->
            <div class="lg:col-span-5 w-full max-w-md mx-auto">
                <div class="bg-white dark:bg-[#161b22] p-6 sm:p-8 rounded-2xl shadow-lg shadow-slate-200/60 dark:shadow-black/50 border border-slate-200/90 dark:border-slate-800">
                    
                    <div class="mb-5">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">
                            Log In
                        </h2>
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
                                class="block w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3.5 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:border-[#00838f] focus:ring-1 focus:ring-[#00838f] focus:outline-none transition-all">
                        </div>

                        <!-- Password Input -->
                        <div class="relative">
                            <input id="password" type="password" name="password" required
                                placeholder="Password"
                                class="block w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3.5 pr-10 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:border-[#00838f] focus:ring-1 focus:ring-[#00838f] focus:outline-none transition-all">
                            <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                                <span id="password-toggle-icon" class="material-symbols-outlined text-[18px]">visibility</span>
                            </button>
                        </div>

                        <!-- Cloudflare Turnstile Widget -->
                        <div class="cf-turnstile" data-sitekey="{{ env('TURNSTILE_SITE_KEY', '1x00000000000000000000AA') }}" data-theme="auto" data-size="flexible"></div>

                        <!-- Submit Button (Solid Brand Color) -->
                        <div class="pt-1">
                            <button type="submit" 
                                class="w-full py-3 px-4 rounded-lg shadow-sm text-sm font-bold tracking-wide uppercase text-white bg-[#00838f] hover:bg-[#00727d] active:scale-[0.99] transition-all">
                                LOG IN
                            </button>
                        </div>

                        <!-- Forgot Password & Remember -->
                        <div class="flex items-center justify-between text-xs pt-1">
                            <label class="flex items-center gap-2 cursor-pointer text-slate-600 dark:text-slate-400">
                                <input type="checkbox" name="remember" class="rounded border-slate-300 dark:border-slate-700 text-[#00838f] focus:ring-[#00838f]">
                                <span>Ingat Saya</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[#00838f] dark:text-teal-400 hover:underline">
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

                    <!-- Google Sign In Button -->
                    <div>
                        <a href="{{ route('social.redirect', 'google') }}" 
                            class="w-full flex items-center justify-center gap-2.5 py-2.5 px-4 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-[#161b22] hover:bg-slate-50 dark:hover:bg-slate-800/80 text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200 shadow-sm transition-all">
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
                        <a href="{{ route('register') }}" class="font-bold text-[#00838f] dark:text-teal-400 hover:underline ml-1">
                            Daftar Sekarang
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

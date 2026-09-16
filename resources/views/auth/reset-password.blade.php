<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('components.theme-init')
    <title>Reset Password — {{ $company->company_name ?? 'Rhantech' }}</title>
    <link rel="icon" type="image/png" href="{{ isset($company) && $company->favicon ? '/storage/'.$company->favicon : '/favicon.ico' }}" />
    <link rel="shortcut icon" type="image/png" href="{{ isset($company) && $company->favicon ? '/storage/'.$company->favicon : '/favicon.ico' }}" />
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
<body class="min-h-full bg-[#f6f9fa] dark:bg-[#0b0f17] text-slate-800 dark:text-slate-200 antialiased flex flex-col justify-between transition-colors duration-200">

    <!-- Top Header / Navbar (Marketplace Style) -->
    <header class="w-full bg-white dark:bg-[#161c28] border-b border-slate-200/80 dark:border-slate-800/80 py-3.5 px-4 sm:px-8 lg:px-16">
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
                   title="Panduan Pemulihan Kata Sandi" 
                   aria-label="Panduan Pemulihan Kata Sandi" 
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
                        Buat Kata Sandi <span class="text-sky-600 dark:text-sky-400">Baru yang Kuat</span>
                    </h1>
                    <p class="mt-3 text-sm sm:text-base text-slate-600 dark:text-slate-300 max-w-lg leading-relaxed">
                        Gunakan kombinasi huruf, angka, dan simbol untuk melindungi akun toko serta transaksi produk digital Anda.
                    </p>
                </div>

                <!-- Showcase Feature Grid -->
                <div class="relative w-full max-w-md bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 sm:p-8">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white dark:bg-[#161c28] p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-sky-50 dark:bg-sky-950/40 flex items-center justify-center text-sky-600 dark:text-sky-400">
                                <span class="material-symbols-outlined text-2xl">lock_reset</span>
                            </div>
                            <div class="text-left">
                                <div class="text-xs text-slate-500 dark:text-slate-400">Pembaruan</div>
                                <div class="text-xs font-bold text-slate-800 dark:text-white">Instan & Langsung</div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#161c28] p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-700 dark:text-slate-300">
                                <span class="material-symbols-outlined text-2xl">shield</span>
                            </div>
                            <div class="text-left">
                                <div class="text-xs text-slate-500 dark:text-slate-400">Keamanan</div>
                                <div class="text-xs font-bold text-slate-800 dark:text-white">Perlindungan Akun</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Clean Auth Card (Flat Modern Style) -->
            <div class="lg:col-span-5 w-full max-w-md mx-auto">
                <div class="bg-white dark:bg-[#161c28] p-6 sm:p-8 rounded-2xl border border-slate-200 dark:border-slate-800">
                    
                    <div class="mb-5">
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                            Reset Password
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            Buat kata sandi baru untuk akun Anda
                        </p>
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

                    <form method="POST" action="{{ route('password.update') }}" class="space-y-3.5">
                        @csrf

                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $token ?? (isset($request) ? $request->route()?->parameter('token') : request()->route('token')) }}">

                        <!-- Email Input -->
                        <div>
                            <input id="email" type="email" name="email" value="{{ old('email', isset($request) ? $request->email : request('email')) }}" required autofocus
                                placeholder="Alamat Email"
                                class="block w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 focus:outline-none transition-all">
                        </div>

                        <!-- Password Input -->
                        <div>
                            <input id="password" type="password" name="password" required
                                placeholder="Password Baru (Min. 8 Karakter)"
                                class="block w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 focus:outline-none transition-all">
                        </div>

                        <!-- Password Confirmation Input -->
                        <div>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                placeholder="Konfirmasi Password Baru"
                                class="block w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3.5 py-2.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 focus:outline-none transition-all">
                        </div>

                        <!-- Submit Button (Solid Primary Flat Color) -->
                        <div class="pt-2">
                            <button type="submit" 
                                class="w-full py-3 px-4 rounded-lg text-sm font-bold tracking-wide uppercase text-white bg-sky-500 hover:bg-sky-600 active:scale-[0.99] transition-colors cursor-pointer">
                                SIMPAN PASSWORD BARU
                            </button>
                        </div>
                    </form>

                    <!-- Divider -->
                    <div class="relative my-5 text-center">
                        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200 dark:border-slate-800"></div></div>
                        <span class="relative bg-white dark:bg-[#161c28] px-3 text-xs text-slate-400 uppercase">atau</span>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center text-xs sm:text-sm text-slate-600 dark:text-slate-400">
                        Kembali ke halaman
                        <a href="{{ route('login') }}" class="font-bold text-sky-600 dark:text-sky-400 hover:underline ml-1">
                            Masuk
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

    <!-- Bottom Footer (Marketplace Style) -->
    <footer class="w-full border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-[#161c28] py-4 px-4 text-center text-xs text-slate-500 dark:text-slate-400">
        <p>&copy; {{ date('Y') }} {{ $company->company_name ?? 'Rhantech' }}. Hak Cipta Dilindungi.</p>
    </footer>

    @include('components.theme-manager')
</body>
</html>

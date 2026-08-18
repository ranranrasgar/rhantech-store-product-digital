<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('components.theme-init')
    <title>Lupa Password — {{ $company->company_name ?? 'Rhantech' }}</title>
    <link rel="icon" type="image/png" href="{{ isset($company) && $company->favicon ? '/storage/'.$company->favicon : '/favicon.ico' }}" />
    <link rel="shortcut icon" type="image/png" href="{{ isset($company) && $company->favicon ? '/storage/'.$company->favicon : '/favicon.ico' }}" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
<body class="min-h-full bg-[#f6f9fa] dark:bg-[#0b0f17] text-slate-800 dark:text-slate-200 antialiased flex flex-col justify-between transition-colors duration-200">

    <!-- Top Header / Navbar (Marketplace Style) -->
    <header class="w-full bg-white dark:bg-[#161c28] border-b border-slate-200/80 dark:border-slate-800/80 py-3.5 px-4 sm:px-8 lg:px-16">
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
                    Atur Ulang Kata Sandi
                </span>
            </div>

            <div class="flex items-center gap-4">
                @if(!empty($company->phone) || !empty($company->whatsapp))
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $company->whatsapp ?? $company->phone) }}?text=Halo,%20saya%20butuh%20bantuan%20reset%20password" target="_blank" class="text-xs sm:text-sm font-semibold text-[#00838f] dark:text-teal-400 hover:underline inline-flex items-center gap-1">
                        <span class="material-symbols-outlined text-[18px]">support_agent</span>
                        <span>Butuh bantuan?</span>
                    </a>
                @else
                    <a href="{{ url('/') }}#contact" class="text-xs sm:text-sm font-semibold text-[#00838f] dark:text-teal-400 hover:underline">
                        Butuh bantuan?
                    </a>
                @endif
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
                        Pemulihan Akun <span class="text-[#00838f] dark:text-teal-400">Aman & Cepat</span>
                    </h1>
                    <p class="mt-3 text-sm sm:text-base text-slate-600 dark:text-slate-300 max-w-lg leading-relaxed">
                        Masukkan email yang terdaftar pada akun Anda. Kami akan mengirimkan tautan verifikasi untuk membuat kata sandi baru.
                    </p>
                </div>

                <!-- Showcase Feature Grid -->
                <div class="relative w-full max-w-md bg-gradient-to-br from-teal-50 to-cyan-50/50 dark:from-slate-800/60 dark:to-teal-950/20 border border-teal-100 dark:border-slate-700/60 rounded-3xl p-6 sm:p-8 shadow-sm">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white dark:bg-[#161c28] p-4 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-teal-500/10 flex items-center justify-center text-[#00838f] dark:text-teal-400">
                                <span class="material-symbols-outlined text-2xl">mark_email_read</span>
                            </div>
                            <div class="text-left">
                                <div class="text-xs text-slate-500 dark:text-slate-400">Email Instan</div>
                                <div class="text-xs font-bold text-slate-800 dark:text-white">Tautan Reset</div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#161c28] p-4 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                                <span class="material-symbols-outlined text-2xl">verified_user</span>
                            </div>
                            <div class="text-left">
                                <div class="text-xs text-slate-500 dark:text-slate-400">Keamanan</div>
                                <div class="text-xs font-bold text-slate-800 dark:text-white">Terenkripsi 100%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Clean Auth Card -->
            <div class="lg:col-span-5 w-full max-w-md mx-auto">
                <div class="bg-white dark:bg-[#161c28] p-6 sm:p-8 rounded-2xl shadow-lg shadow-slate-200/60 dark:shadow-black/50 border border-slate-200/90 dark:border-slate-800">
                    
                    <div class="mb-5">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">
                            Lupa Password
                        </h2>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            Masukkan email terdaftar Anda untuk menerima link reset.
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

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                        @csrf

                        <!-- Email Input -->
                        <div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                placeholder="Alamat Email Terdaftar"
                                class="block w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3.5 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:border-[#00838f] focus:ring-1 focus:ring-[#00838f] focus:outline-none transition-all">
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-1">
                            <button type="submit" 
                                class="w-full py-3 px-4 rounded-lg shadow-sm text-sm font-bold tracking-wide uppercase text-white bg-[#00838f] hover:bg-[#00727d] active:scale-[0.99] transition-all">
                                KIRIM LINK RESET PASSWORD
                            </button>
                        </div>
                    </form>

                    <!-- Divider -->
                    <div class="relative my-6 text-center">
                        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200 dark:border-slate-800"></div></div>
                        <span class="relative bg-white dark:bg-[#161c28] px-3 text-xs text-slate-400 uppercase">atau</span>
                    </div>

                    <!-- Back to Login Link -->
                    <div class="text-center text-xs sm:text-sm text-slate-600 dark:text-slate-400">
                        Ingat kata sandi Anda?
                        <a href="{{ route('login') }}" class="font-bold text-[#00838f] dark:text-teal-400 hover:underline ml-1">
                            Masuk Sekarang
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
    <footer class="w-full border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-[#161c28] py-4 px-4 text-center text-xs text-slate-500 dark:text-slate-400">
        <p>&copy; {{ date('Y') }} {{ $company->company_name ?? 'Rhantech' }}. Hak Cipta Dilindungi.</p>
    </footer>

    @include('components.theme-manager')
</body>
</html>

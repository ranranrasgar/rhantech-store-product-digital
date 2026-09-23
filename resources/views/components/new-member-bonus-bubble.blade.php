@if(!auth()->check() || (auth()->user()->role !== 'admin' && !auth()->user()->store))
<!-- Center Promotional Modal & Floating Launcher: 100% Solid White & Center Position -->
<div x-data="{
        showCard: false,
        showBubbleBtn: true,
        dismiss() {
            this.showCard = false;
            this.showBubbleBtn = true;
        },
        expand() {
            this.showBubbleBtn = false;
            this.showCard = true;
        }
    }">

    <!-- 1. Centered Modal (Solid Opaque White, Perfectly Centered) -->
    <div x-show="showCard"
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">

        <div @click.outside="dismiss()"
             class="w-full max-w-[420px] bg-white dark:bg-[#000000] rounded-2xl sm:rounded-3xl border border-slate-200 dark:border-slate-700 p-6 sm:p-7 shadow-none relative text-slate-900 dark:text-slate-100 max-h-[92vh] overflow-y-auto">

            <!-- Close Button -->
            <button type="button"
                    @click="dismiss()"
                    class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 flex items-center justify-center transition-colors cursor-pointer"
                    title="Tutup informasi"
                    aria-label="Tutup">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>

            <!-- Minimalist Tag -->
            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-[10px] font-bold tracking-wider uppercase mb-3">
                <span class="material-symbols-outlined text-[13px]">hub</span>
                <span>Program Kreator</span>
            </div>

            <!-- Content Title & Description -->
            <div class="mb-4 pr-6">
                <h3 class="text-base sm:text-lg font-black text-slate-900 dark:text-white leading-snug tracking-tight">
                    Toko Digital, Bio Link &amp; Portofolio
                </h3>
                <p class="text-xs text-slate-600 dark:text-slate-300 mt-1.5 leading-relaxed">
                    Aktifkan satu tautan profil untuk menjual produk digital, membagikan link media sosial, dan menampilkan karya Anda. Dapatkan modal promosi awal Rp 500.000.
                </p>
            </div>

            <!-- Solid Stat Box (High contrast, elegant) -->
            <div class="grid grid-cols-2 gap-3 p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 mb-4">
                <div>
                    <div class="text-[10px] uppercase font-bold tracking-wider text-slate-500 dark:text-slate-400">Modal Iklan</div>
                    <div class="text-sm font-black text-slate-900 dark:text-white mt-0.5">Rp 500.000</div>
                </div>
                <div class="border-l border-slate-200 dark:border-slate-700 pl-3">
                    <div class="text-[10px] uppercase font-bold tracking-wider text-slate-500 dark:text-slate-400">Visibilitas</div>
                    <div class="text-sm font-black text-slate-900 dark:text-white mt-0.5">+30% Kunjungan</div>
                </div>
            </div>

            <!-- Clean Note -->
            <div class="flex items-start gap-2 text-xs text-slate-600 dark:text-slate-300 mb-5 leading-normal">
                <span class="material-symbols-outlined text-[16px] text-slate-400 mt-0.5 shrink-0">info</span>
                <span>
                    Saldo promosi digunakan untuk visibilitas di katalog. Keuntungan 100% dari transaksi penjualan dapat dicairkan langsung ke rekening bank Anda.
                </span>
            </div>

            <!-- Action Button -->
            @auth
                @if(!auth()->user()->store)
                    <div class="space-y-2">
                        <a href="{{ route('tenant.dashboard') }}"
                           class="w-full py-3 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 active:bg-slate-950 text-white dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100 font-bold text-xs sm:text-sm shadow-none flex items-center justify-center gap-1.5 transition-colors">
                            <span>Buka Halaman &amp; Klaim Saldo</span>
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </a>
                    </div>
                @endif
            @else
                <div class="space-y-3">
                    <a href="{{ route('register') }}"
                       class="w-full py-3 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 active:bg-slate-950 text-white dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100 font-bold text-xs sm:text-sm shadow-none flex items-center justify-center gap-1.5 transition-colors">
                        <span>Daftar &amp; Klaim Saldo Rp 500.000</span>
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </a>

                    <div class="flex items-center justify-between text-xs px-1 text-slate-500 dark:text-slate-400">
                        <span>Sudah memiliki akun?</span>
                        <a href="{{ route('login') }}" class="font-bold text-slate-900 dark:text-white hover:underline">
                            Masuk di sini
                        </a>
                    </div>
                </div>
            @endauth

        </div>
    </div>

    <!-- 2. Floating Launcher Button (Minimized bottom-right position) -->
    <div class="fixed bottom-5 right-4 sm:right-6 sm:bottom-6 z-40">
        <button type="button"
                x-show="showBubbleBtn"
                @click="expand()"
                x-transition:enter="transition ease-out duration-200 transform"
                x-transition:enter-start="opacity-0 translate-y-3 scale-90"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-150 transform"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-3 scale-90"
                class="flex items-center gap-2 py-2.5 px-4 rounded-full bg-slate-900 hover:bg-slate-800 text-white dark:bg-white dark:hover:bg-slate-100 dark:text-slate-900 shadow-none border border-slate-700/50 dark:border-slate-300 transition-all duration-200 cursor-pointer"
                title="Program Kreator: Bonus Saldo Iklan Rp 500.000"
                aria-label="Program Kreator: Bonus Saldo Iklan Rp 500.000">

            <span class="material-symbols-outlined text-[18px]">hub</span>
            <span class="text-xs font-bold tracking-tight">Bonus Rp 500rb</span>
        </button>
    </div>

</div>
@endif

@if(!auth()->check() || (auth()->user()->role !== 'admin' && !auth()->user()->store))
<!-- Floating Promotional Bubble: Elegant & Professional Modal -->
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
    }"
    class="pointer-events-none fixed bottom-5 right-4 sm:right-6 sm:bottom-6 z-50 flex flex-col items-end max-w-sm sm:max-w-[380px]">

    <!-- 1. Expanded Card (Clean, Minimalist, Elegant) -->
    <div x-show="showCard"
         @click.outside="dismiss()"
         x-transition:enter="transition ease-out duration-200 transform"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150 transform"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         class="pointer-events-auto w-full sm:w-[380px] self-center sm:self-end bg-white/98 dark:bg-[#111827]/98 backdrop-blur-md rounded-2xl border border-slate-200/90 dark:border-slate-800 p-5 shadow-xl shadow-slate-900/10 relative overflow-hidden transition-all text-slate-900 dark:text-slate-100"
         style="display: none;">

        <div class="relative z-10">
            <!-- Header with Minimalist Tag & Close Button -->
            <div class="flex items-center justify-between gap-2 mb-3">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-[10px] font-bold tracking-wider uppercase">
                    <span class="material-symbols-outlined text-[13px]">hub</span>
                    <span>Program Kreator</span>
                </div>

                <button type="button"
                        @click="dismiss()"
                        class="w-7 h-7 rounded-full bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 flex items-center justify-center transition-colors cursor-pointer"
                        title="Tutup informasi"
                        aria-label="Tutup">
                    <span class="material-symbols-outlined text-[16px]">close</span>
                </button>
            </div>

            <!-- Content Header -->
            <div class="mb-3.5">
                <h3 class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white leading-snug tracking-tight">
                    Toko Digital, Bio Link &amp; Portofolio
                </h3>
                <p class="text-[12px] text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                    Aktifkan satu tautan profil untuk menjual produk digital, membagikan link media sosial, dan menampilkan karya Anda. Dapatkan modal promosi awal Rp 500.000.
                </p>
            </div>

            <!-- Clean Stat Box (Monochromatic & Elegant, No Multiple Rainbow Colors) -->
            <div class="grid grid-cols-2 gap-2 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/70 dark:border-slate-700/60 mb-3.5">
                <div>
                    <div class="text-[10px] uppercase font-bold tracking-wider text-slate-400 dark:text-slate-400">Modal Iklan</div>
                    <div class="text-xs sm:text-sm font-black text-slate-900 dark:text-white mt-0.5">Rp 500.000</div>
                </div>
                <div class="border-l border-slate-200 dark:border-slate-700 pl-3">
                    <div class="text-[10px] uppercase font-bold tracking-wider text-slate-400 dark:text-slate-400">Visibilitas</div>
                    <div class="text-xs sm:text-sm font-black text-slate-900 dark:text-white mt-0.5">+30% Kunjungan</div>
                </div>
            </div>

            <!-- Refined Neutral Note (No Loud Yellow/Amber Box) -->
            <div class="flex items-start gap-2 text-[11px] text-slate-500 dark:text-slate-400 mb-4 leading-normal">
                <span class="material-symbols-outlined text-[15px] text-slate-400 mt-0.5 shrink-0">info</span>
                <span>
                    Saldo promosi digunakan untuk visibilitas di platform. Keuntungan 100% dari transaksi adalah milik Anda dan dapat dicairkan langsung ke rekening bank.
                </span>
            </div>

            <!-- Action Buttons (Solid, High-End Minimalist) -->
            @auth
                @if(!auth()->user()->store)
                    <div class="space-y-2">
                        <a href="{{ route('tenant.dashboard') }}"
                           class="w-full py-2.5 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 active:bg-slate-950 text-white dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100 font-bold text-xs shadow-xs flex items-center justify-center gap-1.5 transition-colors">
                            <span>Buka Halaman &amp; Klaim Saldo</span>
                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </a>
                    </div>
                @endif
            @else
                <div class="space-y-2.5">
                    <a href="{{ route('register') }}"
                       class="w-full py-2.5 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 active:bg-slate-950 text-white dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100 font-bold text-xs shadow-xs flex items-center justify-center gap-1.5 transition-colors">
                        <span>Daftar &amp; Klaim Saldo Rp 500.000</span>
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>

                    <div class="flex items-center justify-between text-[11px] px-1 text-slate-500 dark:text-slate-400">
                        <span>Sudah memiliki akun?</span>
                        <a href="{{ route('login') }}" class="font-bold text-slate-900 dark:text-white hover:underline">
                            Masuk di sini
                        </a>
                    </div>
                </div>
            @endauth
        </div>
    </div>

    <!-- 2. Minimalist Floating Launcher Button (Elegant & Professional) -->
    <button type="button"
            x-show="showBubbleBtn"
            @click="expand()"
            x-transition:enter="transition ease-out duration-200 transform"
            x-transition:enter-start="opacity-0 translate-y-3 scale-90"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-150 transform"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-3 scale-90"
            class="pointer-events-auto relative flex items-center gap-2 py-2 px-3.5 rounded-full bg-slate-900 hover:bg-slate-800 text-white dark:bg-white dark:hover:bg-slate-100 dark:text-slate-900 shadow-lg hover:shadow-xl border border-slate-700/50 dark:border-slate-300 transition-all duration-200 cursor-pointer"
            title="Program Kreator: Bonus Saldo Iklan Rp 500.000"
            aria-label="Program Kreator: Bonus Saldo Iklan Rp 500.000">

        <span class="material-symbols-outlined text-[18px]">hub</span>
        <span class="text-xs font-bold tracking-tight">Bonus Rp 500rb</span>
    </button>
</div>
@endif

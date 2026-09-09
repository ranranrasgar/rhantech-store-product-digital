@guest
<!-- Floating Promotional Bubble: New Member Welcome Bonus (Rp 25.000) -->
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
    class="pointer-events-none fixed bottom-5 right-4 sm:right-6 sm:bottom-6 z-50 flex flex-col items-end max-w-sm sm:max-w-[390px]">

    <!-- 1. Expanded Promo Bubble / Card (Opens when icon clicked) -->
    <div x-show="showCard"
         @click.outside="dismiss()"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-8 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-8 scale-95"
         class="pointer-events-auto w-full sm:w-[390px] self-center sm:self-end bg-surface/95 dark:bg-[#161b22]/95 backdrop-blur-md rounded-2xl md:rounded-3xl border-2 border-primary/20 dark:border-primary/30 p-5 shadow-2xl relative overflow-hidden transition-all text-on-surface"
         style="display: none;">

        <!-- Ambient decorative gradient glow -->
        <div class="absolute -top-12 -right-12 w-36 h-36 bg-primary/15 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-12 -left-12 w-32 h-32 bg-amber-500/15 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10">
            <!-- Header with Badge & Close Button -->
            <div class="flex items-center justify-between gap-2 mb-3">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-gradient-to-r from-amber-500/15 to-primary/15 border border-amber-500/30 text-amber-700 dark:text-amber-300 text-[11px] font-black tracking-wide">
                    <span class="animate-pulse">🎁</span>
                    <span>BONUS PENGGUNA BARU</span>
                </div>

                <button type="button"
                        @click="dismiss()"
                        class="w-7 h-7 rounded-full bg-surface-container-high hover:bg-surface-container-highest text-on-surface-variant hover:text-on-surface flex items-center justify-center transition-colors cursor-pointer"
                        title="Tutup info bonus"
                        aria-label="Tutup">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>

            <!-- Content Body -->
            <div class="flex items-start gap-3 mb-3.5">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 via-amber-500 to-amber-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-amber-500/20">
                    <span class="material-symbols-outlined text-[26px]">redeem</span>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="text-sm sm:text-base font-black text-on-background dark:text-white leading-snug">
                        Daftar Hari Ini, Dapatkan Saldo <span class="text-primary font-extrabold">Rp 25.000!</span>
                    </h3>
                    <p class="text-[12px] text-on-surface-variant mt-1 leading-relaxed">
                        Daftar akun sekarang dan dapatkan saldo bonus langsung untuk membeli berbagai produk digital pilihan.
                    </p>
                </div>
            </div>

            <!-- Disclaimer Notice Box (Important: Saldo tidak bisa dicairkan) -->
            <div class="p-2.5 rounded-xl bg-amber-500/10 dark:bg-amber-500/15 border border-amber-500/25 flex items-start gap-2 text-[11px] text-amber-800 dark:text-amber-300 mb-4 leading-normal">
                <span class="material-symbols-outlined text-[16px] text-amber-600 dark:text-amber-400 shrink-0 mt-0.5">info</span>
                <span>
                    <strong>Catatan:</strong> Saldo bonus ini khusus digunakan untuk berbelanja produk di platform dan <u>tidak dapat dicairkan (non-withdrawable)</u>.
                </span>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-2">
                <a href="{{ route('register') }}"
                   class="w-full py-2.5 px-4 rounded-xl bg-primary hover:bg-primary/90 text-white font-black text-xs shadow-md shadow-primary/20 flex items-center justify-center gap-1.5 transition-all hover:scale-[1.01] active:scale-98">
                    <span>Daftar & Klaim Saldo Sekarang</span>
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>

                <div class="flex items-center justify-between text-[11px] px-1 pt-0.5 text-on-surface-variant">
                    <span>Sudah punya akun?</span>
                    <a href="{{ route('login') }}" class="font-bold text-primary hover:underline">
                        Masuk di sini
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Compact Floating Icon Launcher (Default state: Hanya Icon Saja) -->
    <button type="button"
            x-show="showBubbleBtn"
            @click="expand()"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-y-4 scale-75"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 scale-75"
            class="pointer-events-auto relative flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-gradient-to-tr from-amber-500 via-orange-500 to-amber-600 text-white shadow-xl hover:shadow-2xl shadow-amber-500/30 hover:shadow-amber-500/50 transition-all duration-300 hover:scale-110 active:scale-95 group border-2 border-white/60 dark:border-white/30 cursor-pointer"
            title="Klaim Bonus Pengguna Baru Rp 25.000"
            aria-label="Klaim Bonus Pengguna Baru Rp 25.000">

        <!-- Gift Icon with subtle hover wiggle -->
        <span class="text-2xl sm:text-[26px] group-hover:rotate-12 group-hover:scale-110 transition-transform duration-300 select-none">🎁</span>

        <!-- Badge 25K Notification -->
        <span class="absolute -top-1 -right-1 flex items-center justify-center bg-rose-500 text-white text-[9px] sm:text-[10px] font-black px-1.5 py-0.5 rounded-full shadow border-2 border-white dark:border-slate-900 leading-none">
            25K
        </span>

        <!-- Ripple ping indicator -->
        <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-amber-300 animate-ping pointer-events-none opacity-75"></span>
    </button>
</div>
@endguest

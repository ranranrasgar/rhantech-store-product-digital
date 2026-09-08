@guest
<!-- Floating Promotional Bubble: New Member Welcome Bonus (Rp 25.000) -->
<div x-data="{
        showCard: false,
        showBubbleBtn: false,
        init() {
            const dismissed = sessionStorage.getItem('new_member_bonus_dismissed');
            if (!dismissed) {
                // Muncul otomatis setelah 800ms ketika pertama kali dibuka
                setTimeout(() => {
                    this.showCard = true;
                }, 800);
            } else {
                // Jika sudah pernah ditutup dalam sesi ini, tampilkan bubble icon kecil
                this.showBubbleBtn = true;
            }
        },
        dismiss() {
            this.showCard = false;
            this.showBubbleBtn = true;
            sessionStorage.setItem('new_member_bonus_dismissed', '1');
        },
        expand() {
            this.showBubbleBtn = false;
            this.showCard = true;
        }
    }"
    class="pointer-events-none fixed bottom-4 right-4 left-4 sm:left-auto sm:right-6 sm:bottom-6 z-50 flex flex-col items-end max-w-sm sm:max-w-[390px]"
    style="display: none;"
    x-show="showCard || showBubbleBtn">

    <!-- 1. Expanded Promo Bubble / Card -->
    <div x-show="showCard"
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
                   class="w-full py-2.5 px-4 rounded-xl bg-primary hover:bg-primary/90 text-white font-black text-xs shadow-md shadow-primary/20 flex items-center justify-center gap-1.5 transition-all hover:scale-[1.01] active:scale-98"
                   wire:navigate>
                    <span>Daftar & Klaim Saldo Sekarang</span>
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>

                <div class="flex items-center justify-between text-[11px] px-1 pt-0.5 text-on-surface-variant">
                    <span>Sudah punya akun?</span>
                    <a href="{{ route('login') }}" class="font-bold text-primary hover:underline" wire:navigate>
                        Masuk di sini
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Minimized Floating Bubble Launcher (Appears when closed/dismissed) -->
    <button type="button"
            x-show="showBubbleBtn"
            @click="expand()"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-y-4 scale-75"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 scale-75"
            class="pointer-events-auto self-end inline-flex items-center gap-2 py-2.5 px-4 rounded-full bg-gradient-to-r from-amber-500 via-amber-500 to-primary text-white font-black text-xs shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105 active:scale-95 group border-2 border-white/30 dark:border-white/20 cursor-pointer"
            style="display: none;"
            title="Klaim Bonus Saldo Rp 25.000">
        <span class="text-base group-hover:scale-125 transition-transform">🎁</span>
        <span class="tracking-tight">Klaim Saldo Rp 25.000</span>
        <span class="w-2 h-2 rounded-full bg-white animate-ping"></span>
    </button>
</div>
@endguest

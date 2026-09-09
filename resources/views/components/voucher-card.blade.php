@props([
    'campaign',
    'mode' => 'browse', // 'browse' (halaman produk/toko) atau 'checkout' (halaman checkout)
    'applied' => false,
    'hasUsed' => false,
])

@php
    $isFree = $campaign->isFree();
    $isPercentage = ($campaign->discount_type === 'percentage');
    $discountVal = (float) $campaign->discount_value;
    $minSpend = (float) ($campaign->minimum_spend ?? 0);
    $storeName = $campaign->store->name ?? 'Toko';
    $code = strtoupper($campaign->code ?? '');
    $daysLeft = now()->diffInDays($campaign->end_date, false);
    $hoursLeft = now()->diffInHours($campaign->end_date, false);

    if ($hasUsed) {
        $bgGradient = 'linear-gradient(135deg, #64748b 0%, #475569 100%)';
        $badgeText = 'SUDAH KLAIM';
        $iconName = 'lock';
        $discountLabel = 'TERPAKAI';
    } elseif ($isFree) {
        $bgGradient = 'linear-gradient(135deg, #059669 0%, #0d9488 50%, #047857 100%)';
        $badgeText = 'GRATIS 100%';
        $iconName = 'redeem';
        $discountLabel = '100% OFF';
    } elseif ($isPercentage) {
        $bgGradient = 'linear-gradient(135deg, #f59e0b 0%, #ea580c 100%)';
        $badgeText = 'DISKON ' . rtrim(rtrim($discountVal, '0'), '.') . '%';
        $iconName = 'confirmation_number';
        $discountLabel = rtrim(rtrim($discountVal, '0'), '.') . '% OFF';
    } else {
        $bgGradient = 'linear-gradient(135deg, #0284c7 0%, #2563eb 100%)';
        $badgeText = 'POTONGAN';
        $iconName = 'local_offer';
        $discountLabel = 'Rp ' . number_format($discountVal / 1000, 0) . 'rb OFF';
    }
@endphp

<div class="relative flex flex-col sm:flex-row bg-white dark:bg-[#161b22] border {{ $hasUsed ? 'border-gray-300 dark:border-gray-800 opacity-75' : ($applied ? 'border-primary ring-2 ring-primary/30' : 'border-gray-200 dark:border-[#30363d]') }} rounded-2xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden group">
    
    <!-- Left Section / Header Badge Karcis (Bulletproof Inline Gradient & Watermark) -->
    <div class="sm:w-36 p-4 flex flex-row sm:flex-col items-center justify-between sm:justify-center text-center relative border-b sm:border-b-0 sm:border-r border-dashed border-white/40 text-white shrink-0 shadow-inner overflow-hidden"
         style="background: {{ $bgGradient }};">
        
        <!-- Watermark / Decorative Background Icon Motif -->
        <div class="absolute -right-3 -bottom-3 text-white/15 pointer-events-none select-none z-0">
            <span class="material-symbols-outlined text-[85px] leading-none">{{ $iconName }}</span>
        </div>

        <!-- Ticket Semi-circle Cutout Notch -->
        <div class="hidden sm:block absolute -top-3 -right-3 w-6 h-6 rounded-full border border-gray-200 dark:border-[#30363d] z-10 shadow-inner" style="background-color: var(--theme-background, #f8fafc);"></div>
        <div class="hidden sm:block absolute -bottom-3 -right-3 w-6 h-6 rounded-full border border-gray-200 dark:border-[#30363d] z-10 shadow-inner" style="background-color: var(--theme-background, #f8fafc);"></div>

        <div class="flex sm:flex-col items-center gap-2 sm:gap-1.5 relative z-1">
            <div class="w-10 h-10 rounded-2xl bg-white/20 backdrop-blur-xs flex items-center justify-center shrink-0 shadow-xs border border-white/30">
                <span class="material-symbols-outlined text-white text-[22px]">
                    {{ $iconName }}
                </span>
            </div>
            <span class="text-[10px] font-black uppercase tracking-wider bg-black/25 backdrop-blur-xs text-white px-2.5 py-0.5 rounded-full border border-white/20">
                {{ $badgeText }}
            </span>
        </div>

        <div class="sm:mt-2 text-right sm:text-center relative z-1">
            <div class="text-sm sm:text-base font-black tracking-tight leading-tight text-white drop-shadow-xs">
                {{ $discountLabel }}
            </div>
            <div class="text-[9px] text-white/90 font-semibold truncate max-w-[100px] sm:max-w-none mt-0.5">
                {{ $storeName }}
            </div>
        </div>
    </div>

    <!-- Right Section / Detail Voucher -->
    <div class="flex-1 p-3.5 sm:p-4 flex flex-col justify-between relative bg-white dark:bg-[#161b22]">
        <div>
            <div class="flex items-start justify-between gap-2 mb-1.5">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-1.5 flex-wrap mb-1">
                        <span class="inline-flex items-center gap-1 text-[10px] font-extrabold px-2 py-0.5 rounded-full {{ $isFree ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800' : 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800' }}">
                            <span class="material-symbols-outlined text-[12px]">{{ $isFree ? 'redeem' : 'sell' }}</span>
                            {{ $isFree ? 'Kupon Gratis' : 'Kupon Diskon' }}
                        </span>
                        <span class="inline-flex items-center gap-0.5 text-[9.5px] font-bold px-2 py-0.5 rounded-full bg-sky-50 dark:bg-sky-950/60 text-sky-700 dark:text-sky-300 border border-sky-200 dark:border-sky-800" title="Maksimal 1 kali pakai per akun pelanggan">
                            <span class="material-symbols-outlined text-[11px]">person</span>
                            1x Pakai
                        </span>
                        @if($hasUsed)
                            <span class="inline-flex items-center gap-0.5 text-[9.5px] font-bold px-2 py-0.5 rounded-full bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                                <span class="material-symbols-outlined text-[11px]">check_circle</span>
                                Sudah Terpakai
                            </span>
                        @endif
                    </div>
                    <h4 class="text-sm font-black text-slate-900 dark:text-white line-clamp-1">
                        {{ $campaign->name }}
                    </h4>
                </div>

                <!-- Code Pill (Dashed Border Voucher Style) -->
                <div class="px-2.5 py-1 rounded-lg bg-teal-50 dark:bg-teal-950/50 text-[#00838f] dark:text-teal-400 font-mono font-black text-xs border border-dashed border-[#00838f]/40 tracking-wider shrink-0 select-all shadow-2xs"
                     title="Kode Voucher: {{ $code }}">
                    {{ $code }}
                </div>
            </div>

            <p class="text-[11px] text-slate-500 dark:text-slate-400 line-clamp-1 leading-relaxed">
                {{ $campaign->description ?? 'Gunakan kupon ini saat checkout untuk mendapatkan potongan harga spesial.' }}
            </p>
        </div>

        <!-- Footer Meta (Min spend & Expiry timer) -->
        <div class="mt-3 pt-2.5 border-t border-slate-100 dark:border-slate-800 flex flex-wrap items-center justify-between gap-2 text-[11px]">
            <div class="flex items-center gap-3 text-slate-500 dark:text-slate-400">
                <!-- Minimum Spend -->
                <div class="flex items-center gap-1 font-medium">
                    <span class="material-symbols-outlined text-[15px] text-[#00838f] dark:text-teal-400">shopping_bag</span>
                    <span>Min. Rp{{ number_format($minSpend, 0, ',', '.') }}</span>
                </div>

                <!-- Countdown / Expiry -->
                <div class="flex items-center gap-1 font-medium {{ $daysLeft <= 2 ? 'text-rose-600 dark:text-rose-400 font-bold' : '' }}">
                    <span class="material-symbols-outlined text-[15px]">schedule</span>
                    <span>
                        @if($hoursLeft <= 0)
                            Berakhir hari ini
                        @elseif($hoursLeft < 48)
                            Sisa {{ $hoursLeft }} jam
                        @else
                            s.d. {{ $campaign->end_date->format('d M Y') }}
                        @endif
                    </span>
                </div>
            </div>

            <!-- Action Button -->
            <div>
                @if($mode === 'checkout')
                    @if($hasUsed)
                        <button type="button" 
                                disabled 
                                class="px-3.5 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 border border-slate-200 dark:border-slate-700 text-xs font-bold cursor-not-allowed flex items-center gap-1">
                            <span class="material-symbols-outlined text-[13px]">block</span>
                            <span>Sudah Digunakan</span>
                        </button>
                    @elseif($applied)
                        <button type="button" 
                                onclick="removeVoucher()" 
                                class="px-3.5 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800 text-xs font-bold transition-colors flex items-center gap-1 cursor-pointer">
                            <span class="material-symbols-outlined text-[13px]">check_circle</span>
                            <span>Terpasang (Batal)</span>
                        </button>
                    @else
                        <button type="button" 
                                onclick="applyVoucherCode('{{ $code }}')" 
                                class="px-4 py-1.5 rounded-lg bg-[#00838f] hover:bg-[#00727d] text-white text-xs font-bold transition-all shadow-xs flex items-center gap-1 hover:scale-105 active:scale-95 cursor-pointer">
                            <span>Gunakan</span>
                            <span class="material-symbols-outlined text-[13px]">arrow_forward</span>
                        </button>
                    @endif
                @else
                    <!-- Mode Browse (Copy Code) -->
                    <button type="button" 
                            onclick="copyVoucherCode('{{ $code }}')" 
                            class="px-3.5 py-1.5 rounded-lg bg-[#00838f] hover:bg-[#00727d] text-white text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 hover:scale-105 active:scale-95 cursor-pointer">
                        <span class="material-symbols-outlined text-[14px]">content_copy</span>
                        <span>Salin Kode</span>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

@once
<script>
if (typeof window.copyVoucherCode !== 'function') {
    window.copyVoucherCode = function(code) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(code).then(() => {
                if (typeof showCartToast === 'function') {
                    showCartToast('Kode kupon ' + code + ' berhasil disalin! Gunakan saat checkout.');
                } else {
                    alert('Kode kupon ' + code + ' berhasil disalin! Tempelkan di halaman checkout.');
                }
            }).catch(() => {
                prompt('Salin kode kupon ini:', code);
            });
        } else {
            prompt('Salin kode kupon ini:', code);
        }
    };
}
</script>
@endonce

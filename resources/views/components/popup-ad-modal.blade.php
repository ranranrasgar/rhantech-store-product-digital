@php
    $canShowOnCurrentPage = request()->routeIs('home') 
        || request()->is('/') 
        || request()->routeIs('tenant.dashboard') 
        || request()->is('dashboard')
        || request()->has('preview_ad');
@endphp

@if($canShowOnCurrentPage && isset($popupAd) && $popupAd && ($popupAd->is_active || request()->has('preview_ad')))
@php
    $target = $popupAd->target_audience ?? 'all';
    $targetBadge = [
        'tenant' => ['label' => 'KHUSUS MITRA TOKO', 'sub' => 'Pengumuman Penting untuk Penjual', 'icon' => 'storefront', 'bg' => 'bg-purple-500/15 text-purple-700 dark:text-purple-300'],
        'guest' => ['label' => 'PENGUNJUNG BARU', 'sub' => 'Penawaran Spesial Pendaftaran Akun', 'icon' => 'person_add', 'bg' => 'bg-amber-500/15 text-amber-700 dark:text-amber-300'],
        'customer' => ['label' => 'MEMBER SPESIAL', 'sub' => 'Promo Eksklusif Pengguna Terdaftar', 'icon' => 'shopping_bag', 'bg' => 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300'],
        'all' => ['label' => 'PENGUMUMAN RESMI', 'sub' => 'Informasi & Penawaran Spesial', 'icon' => 'campaign', 'bg' => 'bg-primary/15 text-primary dark:text-cyan-300'],
    ][$target] ?? ['label' => 'PENGUMUMAN RESMI', 'sub' => 'Informasi & Penawaran Spesial', 'icon' => 'campaign', 'bg' => 'bg-primary/15 text-primary dark:text-cyan-300'];
@endphp

<!-- Popup Ad Modal: Promosi & Pengumuman -->
<div x-data="{
        isOpen: false,
        activeImageIndex: 0,
        totalImages: {{ ($popupAd->images && is_array($popupAd->images)) ? count($popupAd->images) : 0 }},
        init() {
            const isPreview = {{ request()->has('preview_ad') ? 'true' : 'false' }};
            const dismissed = sessionStorage.getItem('popup_ad_seen_{{ $popupAd->id }}');
            if (!dismissed || isPreview) {
                setTimeout(() => {
                    this.isOpen = true;
                }, 600);
            }
        },
        close() {
            this.isOpen = false;
            sessionStorage.setItem('popup_ad_seen_{{ $popupAd->id }}', '1');
        },
        nextImage() {
            if (this.totalImages > 1) {
                this.activeImageIndex = (this.activeImageIndex + 1) % this.totalImages;
            }
        },
        prevImage() {
            if (this.totalImages > 1) {
                this.activeImageIndex = (this.activeImageIndex - 1 + this.totalImages) % this.totalImages;
            }
        }
    }"
    x-show="isOpen"
    @keydown.escape.window="close()"
    style="display: none;"
    class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
    role="dialog"
    aria-modal="true"
    aria-labelledby="popup-ad-title-{{ $popupAd->id }}">

    <!-- Backdrop overlay with blur and fade transition -->
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="close()"
         class="fixed inset-0 bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm transition-opacity"></div>

    <!-- Modal Card -->
    <div x-show="isOpen"
         x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-400"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4"
         @click.stop
         class="relative w-full max-w-md sm:max-w-lg bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden z-10 my-auto">

        <!-- Top Close Button -->
        <button type="button"
                @click="close()"
                class="absolute top-3.5 right-3.5 z-20 w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-black/40 hover:bg-black/60 text-white flex items-center justify-center backdrop-blur-md transition-all shadow-md cursor-pointer group"
                aria-label="Tutup Pop-up">
            <span class="material-symbols-outlined text-[20px] group-hover:scale-110 transition-transform">close</span>
        </button>

        <!-- Banner / Image Area -->
        @if($popupAd->images && count($popupAd->images) > 0)
            <div class="relative bg-slate-100 dark:bg-slate-800 overflow-hidden">
                <!-- Target pill on top of image -->
                <div class="absolute top-3.5 left-3.5 z-10">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black tracking-wider uppercase backdrop-blur-md bg-black/50 text-white border border-white/20">
                        <span class="material-symbols-outlined text-[13px]">{{ $targetBadge['icon'] }}</span>
                        {{ $targetBadge['label'] }}
                    </span>
                </div>

                @if(count($popupAd->images) === 1)
                    <img src="{{ asset('storage/' . $popupAd->images[0]) }}"
                         alt="{{ $popupAd->title }}"
                         class="w-full max-h-64 sm:max-h-72 object-cover object-center">
                @else
                    <!-- Multi-image slider -->
                    <div class="relative w-full max-h-64 sm:max-h-72 overflow-hidden">
                        @foreach($popupAd->images as $index => $img)
                            <div x-show="activeImageIndex === {{ $index }}"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 style="display: none;">
                                <img src="{{ asset('storage/' . $img) }}"
                                     alt="{{ $popupAd->title }} - {{ $index + 1 }}"
                                     class="w-full max-h-64 sm:max-h-72 object-cover object-center">
                            </div>
                        @endforeach

                        <!-- Controls for Slider -->
                        <button type="button"
                                @click="prevImage()"
                                class="absolute left-2.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-black/40 hover:bg-black/60 text-white flex items-center justify-center backdrop-blur-md transition-all">
                            <span class="material-symbols-outlined text-sm">chevron_left</span>
                        </button>
                        <button type="button"
                                @click="nextImage()"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-black/40 hover:bg-black/60 text-white flex items-center justify-center backdrop-blur-md transition-all">
                            <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </button>

                        <!-- Slider Dots -->
                        <div class="absolute bottom-2.5 inset-x-0 flex items-center justify-center gap-1.5 z-10">
                            @foreach($popupAd->images as $index => $img)
                                <button type="button"
                                        @click="activeImageIndex = {{ $index }}"
                                        :class="activeImageIndex === {{ $index }} ? 'w-5 bg-white' : 'w-1.5 bg-white/60 hover:bg-white'"
                                        class="h-1.5 rounded-full transition-all"></button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @else
            <!-- Eye-catching header gradient when no custom image is uploaded -->
            <div class="relative px-6 pt-7 pb-5 bg-gradient-to-br from-primary/15 via-secondary/10 to-amber-500/10 dark:from-primary/25 dark:via-slate-800 dark:to-amber-500/15 border-b border-slate-100 dark:border-slate-800 overflow-hidden">
                <div class="absolute -top-10 -right-10 w-36 h-36 bg-primary/20 rounded-full blur-2xl pointer-events-none"></div>
                <div class="absolute -bottom-8 -left-8 w-32 h-32 bg-secondary/20 rounded-full blur-2xl pointer-events-none"></div>

                <div class="relative z-10 flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-primary to-secondary text-white flex items-center justify-center shadow-lg shadow-primary/25 shrink-0">
                        <span class="material-symbols-outlined text-[26px]">{{ $targetBadge['icon'] }}</span>
                    </div>
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black tracking-wider uppercase {{ $targetBadge['bg'] }} mb-1">
                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-current animate-ping"></span>
                            {{ $targetBadge['label'] }}
                        </span>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">{{ $targetBadge['sub'] }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Content Body -->
        <div class="p-5 sm:p-6">
            <h3 id="popup-ad-title-{{ $popupAd->id }}"
                class="text-lg sm:text-xl font-black text-slate-900 dark:text-white tracking-tight leading-snug mb-2.5">
                {{ $popupAd->title }}
            </h3>

            @if($popupAd->description)
                <div class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed max-h-52 overflow-y-auto pr-1">
                    {!! nl2br(e($popupAd->description)) !!}
                </div>
            @endif

            <!-- Actions -->
            <div class="mt-6 flex flex-col sm:flex-row items-center gap-2.5">
                @if($popupAd->link_url)
                    <a href="{{ $popupAd->link_url }}"
                       @click="close()"
                       class="w-full sm:flex-1 inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl sm:rounded-2xl bg-gradient-to-r from-primary to-secondary hover:opacity-95 text-white font-bold text-xs sm:text-sm shadow-md shadow-primary/25 transition-all text-center">
                        <span>{{ $popupAd->link_text ?: 'Lihat Selengkapnya' }}</span>
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>
                    <button type="button"
                            @click="close()"
                            class="w-full sm:w-auto px-4 py-3 rounded-xl sm:rounded-2xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold text-xs sm:text-sm transition-colors text-center cursor-pointer">
                        Tutup
                    </button>
                @else
                    <button type="button"
                            @click="close()"
                            class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl sm:rounded-2xl bg-gradient-to-r from-primary to-secondary hover:opacity-95 text-white font-bold text-xs sm:text-sm shadow-md shadow-primary/25 transition-all text-center cursor-pointer">
                        <span>{{ $popupAd->link_text ?: 'Mengerti & Tutup' }}</span>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

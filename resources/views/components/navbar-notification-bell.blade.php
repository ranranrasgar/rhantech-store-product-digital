@props(['role' => 'buyer'])

<div class="relative" x-data="navbarNotificationBell('{{ $role }}')" x-init="initBell()">
    <!-- Bell Button -->
    <button @click="toggleDropdown()" @click.outside="open = false" 
            class="topbar-icon-btn relative inline-flex items-center justify-center w-9 h-9 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all focus:outline-none"
            style="position: relative; cursor: pointer;"
            :class="{ 'animate-bounce': hasNewNotif }"
            title="Pemberitahuan">
        <span class="material-symbols-outlined" style="font-size: 20px; line-height: 1;">notifications</span>
        
        <!-- Red Badge Pill (Unread Count) -->
        <span x-show="unreadCount > 0" 
              style="display: none;" 
              x-transition
              class="absolute -top-1 -right-1 min-w-[18px] h-[18px] bg-red-600 text-white text-[10px] font-black rounded-full flex items-center justify-center px-1 border-2 border-white dark:border-slate-900 shadow-md ring-1 ring-red-400/50">
            <span x-text="unreadCount > 99 ? '99+' : unreadCount"></span>
        </span>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" 
         style="display: none;" 
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
         class="absolute right-0 mt-2 w-80 sm:w-96 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl py-0 z-50 overflow-hidden text-slate-800 dark:text-slate-100">
        
        <!-- Dropdown Header -->
        <div class="px-4 py-3 bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-blue-600 dark:text-blue-400">notifications</span>
                <span class="font-bold text-sm text-slate-900 dark:text-white">Notifikasi</span>
                <span x-show="unreadCount > 0" class="px-2 py-0.5 bg-red-500/10 text-red-600 dark:text-red-400 text-[11px] font-bold rounded-full" x-text="unreadCount + ' Baru'"></span>
            </div>
            <div class="flex items-center gap-2">
                <template x-if="notificationPermission === 'granted'">
                    <button type="button" @click="testDesktopNotification()" title="Uji coba pop-up notifikasi desktop Windows" class="text-[10px] font-semibold text-slate-500 hover:text-blue-600 dark:text-slate-400 flex items-center gap-0.5 cursor-pointer">
                        <span class="material-symbols-outlined text-[13px]">desktop_windows</span>
                        <span>Tes Pop-up</span>
                    </button>
                </template>
                <button x-show="unreadCount > 0" 
                        @click="markAllAsRead()" 
                        class="text-[11px] font-semibold text-blue-600 dark:text-blue-400 hover:underline transition-colors focus:outline-none cursor-pointer">
                    Tandai Dibaca
                </button>
            </div>
        </div>

        <!-- Banner Izin Notifikasi Desktop Windows jika belum diizinkan -->
        <div x-show="notificationPermission !== 'granted'" class="px-3.5 py-2.5 bg-amber-500/10 border-b border-amber-500/20 flex items-center justify-between gap-2">
            <div class="text-[11px] text-amber-800 dark:text-amber-200 font-semibold flex items-center gap-1.5 min-w-0">
                <span class="material-symbols-outlined text-[16px] text-amber-600 shrink-0">notifications_active</span>
                <span class="truncate">Aktifkan pop-up Windows</span>
            </div>
            <button type="button" @click="requestDesktopPermission()" class="text-[10px] font-bold bg-amber-600 hover:bg-amber-700 text-white px-2.5 py-1 rounded-lg transition shadow-xs shrink-0 cursor-pointer">
                Izinkan Pop-up
            </button>
        </div>

        <!-- Notification Items List -->
        <div class="max-h-84 overflow-y-auto divide-y divide-outline-variant/20 dark:divide-slate-800/60" style="max-height: 360px;">
            <template x-if="loading && items.length === 0">
                <div class="py-8 text-center text-xs text-on-surface-variant dark:text-slate-400">
                    <span class="material-symbols-outlined animate-spin text-[24px] mb-1">progress_activity</span>
                    <p>Memuat notifikasi...</p>
                </div>
            </template>

            <template x-if="!loading && items.length === 0">
                <div class="py-10 px-4 text-center text-xs text-on-surface-variant dark:text-slate-400">
                    <div class="w-12 h-12 rounded-full bg-emerald-500/10 text-emerald-500 flex items-center justify-center mx-auto mb-2">
                        <span class="material-symbols-outlined text-[24px]">done_all</span>
                    </div>
                    <p class="font-bold text-sm text-on-surface dark:text-slate-200">Semua Beres!</p>
                    <p class="text-[11px] mt-0.5">Tidak ada notifikasi baru saat ini.</p>
                </div>
            </template>

            <template x-for="item in items" :key="item.id">
                <a :href="item.url || '#'" 
                   class="block px-4 py-3 hover:bg-surface-container-low dark:hover:bg-slate-800/50 transition-colors relative"
                   :class="{ 'bg-primary/5 dark:bg-primary/10': !item.is_read }">
                    <div class="flex items-start gap-3">
                        <!-- Icon Avatar -->
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5 font-bold shadow-sm"
                             :class="{
                                'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400': item.type === 'order_paid',
                                'bg-amber-500/15 text-amber-600 dark:text-amber-400': item.type === 'order_created' || item.type === 'order_pending',
                                'bg-blue-500/15 text-blue-600 dark:text-blue-400': item.type === 'new_store',
                                'bg-rose-500/15 text-rose-600 dark:text-rose-400': item.type === 'order_failed',
                                'bg-purple-500/15 text-purple-600 dark:text-purple-400': item.type === 'payout'
                             }">
                            <span class="material-symbols-outlined text-[20px]" x-text="item.icon || 'notifications'"></span>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-1">
                                <p class="text-xs font-bold text-on-surface dark:text-white truncate" x-text="item.title"></p>
                                <span x-show="!item.is_read" class="w-2 h-2 rounded-full bg-primary flex-shrink-0"></span>
                            </div>
                            <p class="text-xs text-on-surface-variant dark:text-slate-300 line-clamp-2 mt-0.5" x-text="item.body"></p>
                            <span class="text-[10px] text-on-surface-variant/70 dark:text-slate-400 mt-1 block" x-text="item.time_ago"></span>
                        </div>
                    </div>
                </a>
            </template>
        </div>

        <!-- Dropdown Footer -->
        <div class="p-2.5 bg-surface-container-low dark:bg-slate-800/80 border-t border-outline-variant/30 dark:border-slate-800 text-center">
            <template x-if="'{{ $role }}' === 'admin'">
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-primary hover:underline flex items-center justify-center gap-1">
                    <span>Kelola Semua Pesanan</span>
                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
            </template>
            <template x-if="'{{ $role }}' === 'tenant' && hasStore">
                <a href="{{ route('tenant.orders.index') }}" class="text-xs font-bold text-primary hover:underline flex items-center justify-center gap-1">
                    <span>Lihat Riwayat Penjualan</span>
                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
            </template>
            <template x-if="'{{ $role }}' !== 'admin' && ('{{ $role }}' === 'buyer' || !hasStore)">
                <a href="{{ route('tenant.purchases.index') }}" class="text-xs font-bold text-primary hover:underline flex items-center justify-center gap-1">
                    <span>Lihat Pembelian Saya</span>
                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
            </template>
        </div>
    </div>
</div>

<script>
if (typeof navbarNotificationBell === 'undefined') {
    function navbarNotificationBell(role) {
        return {
            role: role,
            open: false,
            loading: false,
            unreadCount: 0,
            hasNewNotif: false,
            hasStore: false,
            notificationPermission: ('Notification' in window) ? Notification.permission : 'denied',

            initBell() {
                this.fetchData();

                // Check permission status berkala
                if ('Notification' in window) {
                    this.notificationPermission = Notification.permission;
                }

                // Listen untuk FCM foreground message jika ada event baru
                window.addEventListener('fcm-message-received', (e) => {
                    this.hasNewNotif = true;
                    if (window.playNotificationChime) {
                        window.playNotificationChime();
                    }
                    this.fetchData();
                    setTimeout(() => { this.hasNewNotif = false; }, 3000);
                });

                // Polling update berkala setiap 25 detik
                this.pollTimer = setInterval(() => {
                    this.fetchData(false);
                }, 25000);
            },

            async requestDesktopPermission() {
                if (!('Notification' in window)) {
                    alert('Browser Anda belum mendukung notifikasi desktop.');
                    return;
                }

                try {
                    const permission = await Notification.requestPermission();
                    this.notificationPermission = permission;
                    if (permission === 'granted') {
                        // Tampilkan notifikasi pop-up uji coba langsung di Windows
                        this.triggerNativePopup(
                            "🔔 Pop-up Windows Berhasil Aktif!",
                            "Notifikasi desktop Anda kini telah aktif. Setiap pesanan baru atau konfirmasi sukses akan memunculkan pop-up ini."
                        );
                    } else if (permission === 'denied') {
                        alert('Izin notifikasi diblokir di browser. Silakan klik ikon setelan situs (di samping URL localhost:8000) dan ubah "Notifications" menjadi "Allow/Izinkan".');
                    }
                } catch (e) {
                    console.error('[Request Permission Error]', e);
                }
            },

            testDesktopNotification() {
                if (!('Notification' in window)) return;

                if (Notification.permission === 'granted') {
                    this.triggerNativePopup(
                        "🎉 Uji Coba Pop-up Windows Sukses!",
                        "Push notification desktop berjalan normal di layar komputer Anda."
                    );
                } else {
                    this.requestDesktopPermission();
                }
            },

            triggerNativePopup(title, body) {
                if (window.playNotificationChime) {
                    window.playNotificationChime();
                }

                try {
                    if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
                        navigator.serviceWorker.ready.then(reg => {
                            reg.showNotification(title, {
                                body: body,
                                icon: '/images/logo.png',
                                badge: '/images/logo.png',
                                tag: 'rhn-test-' + Date.now(),
                                requireInteraction: true
                            });
                        }).catch(() => {
                            new Notification(title, { body: body, icon: '/images/logo.png' });
                        });
                    } else {
                        new Notification(title, { body: body, icon: '/images/logo.png' });
                    }
                } catch (e) {
                    try {
                        new Notification(title, { body: body, icon: '/images/logo.png' });
                    } catch (err) {
                        console.warn('[Native popup error]', err);
                    }
                }
            },

            toggleDropdown() {
                this.open = !this.open;
                if (this.open) {
                    this.fetchData(true);
                }
            },

            async fetchData(showLoading = false) {
                if (showLoading) this.loading = true;
                try {
                    const res = await fetch(`/notifications/data?role=${this.role}`);
                    const data = await res.json();
                    this.unreadCount = data.unread_count || 0;
                    this.items = data.notifications || [];
                    this.hasStore = (data.has_store === true);
                } catch (e) {
                    console.warn('[Bell Notification]', e);
                } finally {
                    this.loading = false;
                }
            },

            async markAllAsRead() {
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    await fetch('/notifications/mark-as-read', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ role: this.role })
                    });
                    this.unreadCount = 0;
                    this.items.forEach(item => item.is_read = true);
                } catch (e) {
                    console.warn('[Mark as read]', e);
                }
            }
        };
    }
}
</script>

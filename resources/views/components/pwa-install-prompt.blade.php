<!-- PWA Installation Banner & Prompt (Only on Mobile Device / HP) -->
<div id="pwa-install-banner" class="block md:hidden fixed bottom-4 left-4 right-4 z-50 transform translate-y-32 opacity-0 pointer-events-none transition-all duration-500 ease-out" style="display: none;">
    <div class="relative overflow-hidden rounded-2xl bg-slate-900 border border-slate-700 p-4 text-white">
        <div class="flex items-start gap-3 relative z-10">
            <!-- App Icon -->
            <div class="w-12 h-12 rounded-xl bg-slate-800 p-0.5 border border-slate-700 shrink-0">
                <img src="{{ asset('icons/icon-96x96.png') }}" alt="{{ $company->company_name ?? 'Rhantech' }}" class="w-full h-full object-cover rounded-[10px]">
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-1 mb-0.5">
                    <h3 class="font-black text-sm text-white truncate leading-tight flex items-center gap-1.5">
                        <span>Install Aplikasi {{ $company->company_name ?? 'Rhantech' }}</span>
                        <span class="inline-flex px-1.5 py-0.5 text-[9px] font-bold rounded bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">PWA</span>
                    </h3>
                    <button id="pwa-close-btn" class="text-white/50 hover:text-white transition-colors p-1 -mr-1 -mt-1 cursor-pointer" aria-label="Tutup">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>

                <!-- Android/Desktop Prompt Text -->
                <p id="pwa-android-text" class="text-[11.5px] text-white/75 leading-snug mb-3">
                    Pasang di layar utama HP untuk akses lebih cepat, lancar tanpa buka browser, dan hemat kuota.
                </p>

                <!-- iOS Safari Prompt Text -->
                <div id="pwa-ios-text" class="text-[11.5px] text-white/85 leading-snug mb-3" style="display: none;">
                    Untuk memasang di iPhone/iPad: Tekan ikon <strong class="text-cyan-300">Bagikan (Share)</strong>
                    <span class="inline-block align-middle mx-0.5 px-1 py-0.5 bg-white/10 rounded text-[10px]">📤</span>
                    lalu pilih <strong class="text-cyan-300">'Tambah ke Layar Utama'</strong> (Add to Home Screen)
                    <span class="inline-block align-middle mx-0.5 px-1 py-0.5 bg-white/10 rounded text-[10px]">➕</span>.
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2">
                    <button id="pwa-install-btn" class="flex-1 py-2 px-3.5 bg-sky-500 hover:bg-sky-600 text-white font-bold text-xs rounded-xl transition-colors flex items-center justify-center gap-1.5 cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">install_mobile</span>
                        <span>Install Sekarang</span>
                    </button>
                    <button id="pwa-dismiss-btn" class="py-2 px-3 text-[11px] font-semibold text-white/60 hover:text-white transition-colors cursor-pointer">
                        Nanti Saja
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    // Only show on mobile devices (HP / Smartphone)
    const isMobileDevice = /android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()) || 
                           (window.matchMedia && window.matchMedia('(max-width: 767px)').matches);

    if (!isMobileDevice) {
        return; // Do not show install prompt on desktop/PC
    }

    // Check if already running in standalone PWA mode
    const isStandalone = window.matchMedia('(display-mode: standalone)').matches || 
                         window.navigator.standalone === true || 
                         document.referrer.includes('android-app://');

    if (isStandalone) {
        return; // Don't show prompt if app is already running as installed PWA
    }

    // Check dismissal cookie/localStorage (dismiss for 5 days)
    const dismissKey = 'rhantech_pwa_dismissed_until';
    const dismissedUntil = localStorage.getItem(dismissKey);
    if (dismissedUntil && parseInt(dismissedUntil, 10) > Date.now()) {
        return;
    }

    const banner = document.getElementById('pwa-install-banner');
    const installBtn = document.getElementById('pwa-install-btn');
    const closeBtn = document.getElementById('pwa-close-btn');
    const dismissBtn = document.getElementById('pwa-dismiss-btn');
    const androidText = document.getElementById('pwa-android-text');
    const iosText = document.getElementById('pwa-ios-text');

    let deferredPrompt = null;

    function showBanner() {
        if (!banner) return;
        banner.style.display = 'block';
        setTimeout(function() {
            banner.classList.remove('translate-y-32', 'opacity-0', 'pointer-events-none');
            banner.classList.add('translate-y-0', 'opacity-100', 'pointer-events-auto');
        }, 100);
    }

    function hideBanner(daysToDismiss = 5) {
        if (!banner) return;
        banner.classList.remove('translate-y-0', 'opacity-100', 'pointer-events-auto');
        banner.classList.add('translate-y-32', 'opacity-0', 'pointer-events-none');
        setTimeout(function() {
            banner.style.display = 'none';
        }, 500);

        if (daysToDismiss > 0) {
            localStorage.setItem(dismissKey, Date.now() + (daysToDismiss * 24 * 60 * 60 * 1000));
        }
    }

    // 1. Android / Chrome / Edge BeforeInstallPrompt
    window.addEventListener('beforeinstallprompt', function(e) {
        e.preventDefault();
        deferredPrompt = e;

        // Show install prompt banner after a brief delay (3.5 seconds after page loads)
        setTimeout(function() {
            showBanner();
        }, 3500);
    });

    // 2. iOS Safari detection
    const isIos = /iphone|ipad|ipod/.test(window.navigator.userAgent.toLowerCase());
    const isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);

    if (isIos && isSafari && !isStandalone) {
        // Switch to iOS specific guidance
        if (androidText) androidText.style.display = 'none';
        if (iosText) iosText.style.display = 'block';
        if (installBtn) {
            installBtn.innerHTML = '<span class="material-symbols-outlined text-[16px]">touch_app</span> <span>Lihat Cara Pasang</span>';
            installBtn.onclick = function() {
                alert("Cara Pasang Aplikasi di iOS Safari:\n\n1. Ketuk tombol 'Bagikan' (Share icon kotak bertanda panah ke atas) di menu bawah Safari.\n2. Gulir ke bawah lalu pilih 'Tambah ke Layar Utama' (Add to Home Screen).\n3. Ketuk 'Tambah' (Add) di pojok kanan atas.\n\nAplikasi Rhantech akan langsung terpasang di layar utama iPhone Anda!");
                hideBanner(7);
            };
        }

        setTimeout(function() {
            showBanner();
        }, 4500);
    }

    // Install button clicked (Android / Desktop)
    if (installBtn && !isIos) {
        installBtn.addEventListener('click', async function() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const choiceResult = await deferredPrompt.userChoice;
                if (choiceResult.outcome === 'accepted') {
                    // console.log('User accepted PWA installation');
                    hideBanner(30); // don't show again for 30 days
                } else {
                    hideBanner(3);
                }
                deferredPrompt = null;
            } else {
                hideBanner(2);
            }
        });
    }

    if (closeBtn) closeBtn.addEventListener('click', function() { hideBanner(5); });
    if (dismissBtn) dismissBtn.addEventListener('click', function() { hideBanner(5); });

    // When app is successfully installed
    window.addEventListener('appinstalled', function() {
        hideBanner(365);
        deferredPrompt = null;
    });
})();
</script>

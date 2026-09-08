<!-- PWA Meta Tags & Web App Manifest -->
<link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
<meta name="theme-color" id="pwa-theme-color" content="#0a1628">
<meta name="msapplication-TileColor" id="pwa-ms-tile" content="#0a1628">
<meta name="msapplication-TileImage" content="{{ asset('icons/icon-144x144.png') }}">
<meta name="msapplication-navbutton-color" id="pwa-ms-nav" content="#0a1628">
<meta name="mobile-web-app-capable" content="yes">
<meta name="application-name" content="{{ $company->company_name ?? 'Rhantech' }}">

<!-- iOS Safari PWA Support -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" id="pwa-apple-status-bar" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="{{ $company->company_name ?? 'Rhantech' }}">
<link rel="apple-touch-icon" href="{{ asset('icons/apple-touch-icon.png') }}">
<link rel="apple-touch-icon" sizes="152x152" href="{{ asset('icons/icon-152x152.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icons/apple-touch-icon.png') }}">
<link rel="apple-touch-icon" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">

<!-- Standard Icons -->
<link rel="icon" type="image/png" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">
<link rel="icon" type="image/png" sizes="96x96" href="{{ asset('icons/icon-96x96.png') }}">

<!-- Register Service Worker & Dynamic Status Bar Sync -->
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/sw.js', { scope: '/' })
                .catch(function(err) {
                    console.warn('[PWA] Service worker registration error:', err);
                });
        });
    }

    // Dynamic Mobile Status Bar Sync for Android & iOS
    function syncMobileStatusBar() {
        const path = window.location.pathname;
        const isDark = document.documentElement.classList.contains('dark');
        const themeColorMeta = document.getElementById('pwa-theme-color');
        const appleStatusMeta = document.getElementById('pwa-apple-status-bar');
        const msNavMeta = document.getElementById('pwa-ms-nav');
        const msTileMeta = document.getElementById('pwa-ms-tile');

        let targetColor = '#0a1628'; // Default dark tech blue for root, products, etc.
        let appleStyle = 'black-translucent';

        if (path.startsWith('/store/')) {
            // Store profile page has light surface navbar in light mode, slate-900 in dark mode
            if (isDark) {
                targetColor = '#0f172a';
                appleStyle = 'black-translucent';
            } else {
                targetColor = '#ffffff';
                appleStyle = 'default';
            }
        } else if (path.startsWith('/login') || path.startsWith('/register') || path.startsWith('/password')) {
            if (isDark) {
                targetColor = '#0a1628';
                appleStyle = 'black-translucent';
            } else {
                targetColor = '#ffffff';
                appleStyle = 'default';
            }
        } else if (path.startsWith('/tenant/')) {
            if (isDark) {
                targetColor = '#0b1329';
                appleStyle = 'black-translucent';
            } else {
                targetColor = '#ffffff';
                appleStyle = 'default';
            }
        } else {
            // Homepage, Catalog, Products have fixed dark blue header (#0a1628)
            targetColor = '#0a1628';
            appleStyle = 'black-translucent';
        }

        if (themeColorMeta) themeColorMeta.setAttribute('content', targetColor);
        if (appleStatusMeta) appleStatusMeta.setAttribute('content', appleStyle);
        if (msNavMeta) msNavMeta.setAttribute('content', targetColor);
        if (msTileMeta) msTileMeta.setAttribute('content', targetColor);
    }

    // Unlock any trapped scroll on page navigation
    function resetPageScrollState() {
        document.body.style.overflow = '';
        document.documentElement.style.overflow = '';
        syncMobileStatusBar();
    }

    document.addEventListener('DOMContentLoaded', syncMobileStatusBar);
    document.addEventListener('livewire:navigated', resetPageScrollState);
    window.addEventListener('pageshow', resetPageScrollState);

    // Observe theme switch (dark mode toggle)
    if (typeof MutationObserver !== 'undefined') {
        const themeObserver = new MutationObserver(function(mutations) {
            for (let m of mutations) {
                if (m.attributeName === 'class') {
                    syncMobileStatusBar();
                    break;
                }
            }
        });
        themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    }
</script>

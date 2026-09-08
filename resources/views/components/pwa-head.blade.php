<!-- PWA Meta Tags & Web App Manifest -->
<link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
<meta name="theme-color" content="#0a1628">
<meta name="msapplication-TileColor" content="#0a1628">
<meta name="msapplication-TileImage" content="{{ asset('icons/icon-144x144.png') }}">
<meta name="msapplication-navbutton-color" content="#0a1628">
<meta name="mobile-web-app-capable" content="yes">
<meta name="application-name" content="{{ $company->company_name ?? 'Rhantech' }}">

<!-- iOS Safari PWA Support -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="{{ $company->company_name ?? 'Rhantech' }}">
<link rel="apple-touch-icon" href="{{ asset('icons/apple-touch-icon.png') }}">
<link rel="apple-touch-icon" sizes="152x152" href="{{ asset('icons/icon-152x152.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icons/apple-touch-icon.png') }}">
<link rel="apple-touch-icon" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">

<!-- Standard Icons -->
<link rel="icon" type="image/png" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">
<link rel="icon" type="image/png" sizes="96x96" href="{{ asset('icons/icon-96x96.png') }}">

<!-- Register Service Worker -->
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/sw.js', { scope: '/' })
                .then(function(reg) {
                    // Service worker registered successfully
                })
                .catch(function(err) {
                    console.warn('[PWA] Service worker registration error:', err);
                });
        });
    }
</script>

@if($googleSiteVerification = config('services.google.site_verification'))
    <meta name="google-site-verification" content="{{ $googleSiteVerification }}"/>
@endif
@if($googleAnalyticsId = config('services.google.analytics_id'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $googleAnalyticsId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', @json($googleAnalyticsId));
    </script>
@endif

@php
    $organizationSchema = array_filter([
        '@context' => 'https://schema.org',
        '@type' => $organizationType ?? 'Organization',
        'name' => $company->company_name ?? 'Rhantech',
        'url' => url('/'),
        'logo' => isset($company) && $company->logo ? asset('storage/' . $company->logo) : asset('logo.png'),
        'description' => $organizationDescription ?? null,
        'sameAs' => array_values(array_filter(
            [$company->facebook ?? null, $company->instagram ?? null, $company->linkedin ?? null, $company->youtube ?? null],
            fn ($link) => is_string($link) && str_starts_with($link, 'http')
        )),
    ]);
@endphp
<script type="application/ld+json">{!! json_encode($organizationSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>

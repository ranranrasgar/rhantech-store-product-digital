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

    if (!empty($company->phone) || !empty($company->email) || !empty($company->whatsapp)) {
        $organizationSchema['contactPoint'] = array_filter([
            '@type' => 'ContactPoint',
            'telephone' => $company->phone ?: ($company->whatsapp ?? null),
            'email' => $company->email ?? null,
            'contactType' => 'customer service',
            'availableLanguage' => ['Indonesian', 'English'],
        ]);
    }

    if (!empty($company->address)) {
        $organizationSchema['address'] = [
            '@type' => 'PostalAddress',
            'streetAddress' => $company->address,
            'addressCountry' => 'ID',
        ];
    }

    $websiteSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => $company->company_name ?? config('app.name', 'Rhantech'),
        'url' => url('/'),
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => [
                '@type' => 'EntryPoint',
                'urlTemplate' => route('products.index') . '?search={search_term_string}',
            ],
            'query-input' => 'required name=search_term_string',
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($organizationSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@if(!isset($skipWebSiteSchema) || !$skipWebSiteSchema)
<script type="application/ld+json">{!! json_encode($websiteSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endif

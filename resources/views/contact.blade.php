@extends('layouts.public')
@section('title', 'Hubungi Kami - ' . ($company->name ?? 'rhantech'))

@section('content')
<section class="max-w-container-max mx-auto px-lg py-2xl">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-2xl">
        
        <!-- Contact Info -->
        <div>
            <span class="inline-block py-1 px-3 rounded-full bg-surface-container text-on-surface font-label-md text-label-md mb-6 border border-outline-variant/30">
                Hubungi Kami
            </span>
            <h1 class="font-display-lg-mobile md:font-headline-xl text-on-background dark:text-white mb-md">Mari bicarakan proyek Anda selanjutnya.</h1>
            <p class="font-body-lg text-on-surface-variant mb-xl">
                Apakah Anda memiliki pertanyaan, ide proyek, atau sekadar ingin menyapa, kami selalu terbuka untuk mendiskusikan peluang baru.
            </p>

            <div class="space-y-lg">
                @if(isset($company) && $company->email)
                <div class="flex items-start gap-md">
                    <div class="p-3 bg-surface-container-high rounded-full text-primary">
                        <span class="material-symbols-outlined">mail</span>
                    </div>
                    <div>
                        <h4 class="font-label-md font-bold text-on-background dark:text-white mb-1">Email</h4>
                        <a href="mailto:{{ $company->email }}" class="font-body-md text-primary hover:underline">{{ $company->email }}</a>
                    </div>
                </div>
                @endif
                
                @if(isset($company) && $company->phone)
                <div class="flex items-start gap-md">
                    <div class="p-3 bg-surface-container-high rounded-full text-primary">
                        <span class="material-symbols-outlined">call</span>
                    </div>
                    <div>
                        <h4 class="font-label-md font-bold text-on-background dark:text-white mb-1">Telepon</h4>
                        <p class="font-body-md text-on-surface">{{ $company->phone }}</p>
                    </div>
                </div>
                @endif

                @if(isset($company) && $company->address)
                <div class="flex items-start gap-md">
                    <div class="p-3 bg-surface-container-high rounded-full text-primary">
                        <span class="material-symbols-outlined">location_on</span>
                    </div>
                    <div>
                        <h4 class="font-label-md font-bold text-on-background dark:text-white mb-1">Alamat</h4>
                        <p class="font-body-md text-on-surface whitespace-pre-wrap">{{ $company->address }}</p>
                    </div>
                </div>
                @endif
            </div>
            
            @php
                $allSocial = [];
                if (!empty($company->social_links) && is_array($company->social_links)) {
                    $allSocial = $company->social_links;
                } else {
                    if (!empty($company->website))   $allSocial[] = ['platform' => 'website',   'url' => $company->website,   'name' => 'Website'];
                    if (!empty($company->facebook ?? $company->facebook_url))  $allSocial[] = ['platform' => 'facebook',  'url' => $company->facebook ?? $company->facebook_url,  'name' => 'Facebook'];
                    if (!empty($company->instagram ?? $company->instagram_url)) $allSocial[] = ['platform' => 'instagram', 'url' => $company->instagram ?? $company->instagram_url, 'name' => 'Instagram'];
                    if (!empty($company->youtube))   $allSocial[] = ['platform' => 'youtube',   'url' => $company->youtube,   'name' => 'YouTube'];
                    if (!empty($company->linkedin ?? $company->linkedin_url))  $allSocial[] = ['platform' => 'linkedin',  'url' => $company->linkedin ?? $company->linkedin_url,  'name' => 'LinkedIn'];
                    if (!empty($company->twitter_url)) $allSocial[] = ['platform' => 'x',       'url' => $company->twitter_url,   'name' => 'X / Twitter'];
                }
            @endphp
            @if(!empty($allSocial))
            <div class="mt-xl pt-lg border-t border-outline-variant/30">
                <h4 class="font-label-md font-bold text-on-background dark:text-white mb-4">Ikuti Kami</h4>
                <div class="flex flex-wrap items-center gap-3">
                    @foreach($allSocial as $soc)
                        @php
                            $pKey = strtolower($soc['platform'] ?? 'custom');
                            $pUrl = $soc['url'] ?? '#';
                            $pName = $soc['name'] ?? ucfirst($pKey);
                        @endphp
                        @if(!empty($pUrl) && $pUrl !== '#')
                            <a href="{{ $pUrl }}" target="_blank" rel="noopener noreferrer" title="{{ $pName }}" class="w-9 h-9 flex items-center justify-center bg-surface-container-high rounded-full text-on-surface-variant hover:text-primary hover:bg-surface-container-highest transition-colors">
                                @if($pKey === 'website')
                                    <svg class="w-4 h-4 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                                @elseif($pKey === 'facebook')
                                    <svg class="w-4 h-4 fill-current text-[#1877f2]" viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                @elseif($pKey === 'instagram')
                                    <svg class="w-4 h-4 fill-current text-[#e4405f]" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                @elseif($pKey === 'youtube')
                                    <svg class="w-4 h-4 fill-current text-[#ff0000]" viewBox="0 0 24 24" aria-hidden="true"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                @elseif($pKey === 'linkedin')
                                    <svg class="w-4 h-4 fill-current text-[#0a66c2]" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                @elseif($pKey === 'tiktok')
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" aria-hidden="true"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-1.01v8.42c0 1.95-.53 3.94-1.68 5.51-1.52 2.06-4.04 3.23-6.61 3.12-2.31-.09-4.52-1.2-5.89-3.04-1.64-2.19-1.95-5.2-.8-7.65 1.11-2.39 3.48-4.04 6.1-4.29.39-.03.78-.03 1.17 0v4.06c-.46-.07-.93-.05-1.39.04-1.07.21-2.02.89-2.54 1.84-.66 1.2-.59 2.76.18 3.88.66.97 1.83 1.55 3.01 1.48 1.13-.06 2.18-.72 2.68-1.74.25-.51.37-1.09.37-1.67V.02h-1.12z"/></svg>
                                @elseif($pKey === 'whatsapp')
                                    <svg class="w-4 h-4 fill-current text-[#25d366]" viewBox="0 0 24 24" aria-hidden="true"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                @elseif($pKey === 'x' || $pKey === 'twitter')
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                @elseif($pKey === 'telegram')
                                    <svg class="w-4 h-4 fill-current text-[#229ed9]" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.121l-6.871 4.326-2.962-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.194 1.006.131.832.942z"/></svg>
                                @elseif($pKey === 'github')
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                @else
                                    <span class="material-symbols-outlined text-[16px]">link</span>
                                @endif
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Contact Form -->
        <div class="bg-surface rounded-lg border border-outline-variant shadow-lg p-lg md:p-xl">
            <h3 class="font-headline-lg text-on-background dark:text-white mb-md">Kirim pesan kepada kami</h3>
            
            @if(session('success'))
            <div class="mb-lg p-md bg-[#E0F2FE] border border-[#BAE6FD] text-[#0369A1] rounded-lg font-label-md">
                {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('contact.store') }}" method="POST" class="flex flex-col gap-md" id="contact-form">
                @csrf
                {{-- Honeypot: bot akan mengisi field ini, manusia tidak melihatnya --}}
                <div style="position:absolute;left:-9999px;top:-9999px;opacity:0;pointer-events:none;" aria-hidden="true" tabindex="-1">
                    <label for="website_url">Website (biarkan kosong)</label>
                    <input type="text" id="website_url" name="website_url" value="" autocomplete="off" tabindex="-1">
                </div>
                {{-- Timestamp untuk deteksi submit terlalu cepat --}}
                <input type="hidden" name="form_loaded_at" id="form_loaded_at" value="">
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Nama Anda *</label>
                    <input type="text" name="name" required value="{{ old('name') }}" class="w-full pl-4 pr-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-primary focus:ring-1 focus:ring-primary/20">
                    @error('name')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Alamat Email *</label>
                        <input type="email" name="email" required value="{{ old('email') }}" class="w-full pl-4 pr-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-primary focus:ring-1 focus:ring-primary/20">
                        @error('email')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Nomor Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full pl-4 pr-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-primary focus:ring-1 focus:ring-primary/20">
                        @error('phone')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Subjek *</label>
                    <input type="text" name="subject" required value="{{ old('subject') }}" class="w-full pl-4 pr-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-primary focus:ring-1 focus:ring-primary/20">
                    @error('subject')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Pesan *</label>
                    <textarea name="message" required rows="5" class="w-full pl-4 pr-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-primary focus:ring-1 focus:ring-primary/20">{{ old('message') }}</textarea>
                    @error('message')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
                <div class="pt-sm border-t border-outline-variant/30 mt-sm">
                    <button type="submit" class="w-full px-lg py-4 bg-primary text-white rounded-lg font-label-md font-bold hover:brightness-110 transition shadow text-lg">Kirim Pesan</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Set timestamp saat halaman dimuat — dipakai untuk deteksi bot yang submit terlalu cepat
    document.getElementById('form_loaded_at').value = Math.floor(Date.now() / 1000);
</script>
@endpush

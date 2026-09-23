@extends('layouts.public')
@section('title', $project->title . ' - ' . ($company->company_name ?? 'Rhantech'))
@section('meta_description', Str::limit($project->short_description ?: strip_tags($project->description), 160))
@section('meta_image', $project->thumbnail ? media_url($project->thumbnail) : '')

@section('content')
@php
    $galleryUrls = [];
    if($project->thumbnail) {
        $galleryUrls[] = media_url($project->thumbnail);
    }
    if($project->images) {
        foreach($project->images as $img) {
            $galleryUrls[] = media_url($img->image);
        }
    }
    $allImagesJson = json_encode(array_values($galleryUrls));
@endphp

<section class="pt-xl pb-2xl px-lg md:px-xl max-w-container-max mx-auto" 
         x-data="{ 
             showLightbox: false, 
             images: {{ $allImagesJson }}, 
             currentIndex: 0,
             openLightbox(src) {
                 let idx = this.images.indexOf(src);
                 if (idx === -1) idx = 0;
                 this.currentIndex = idx;
                 this.showLightbox = true;
             },
             next() {
                 if (this.images.length > 0) {
                     this.currentIndex = (this.currentIndex + 1) % this.images.length;
                 }
             },
             prev() {
                 if (this.images.length > 0) {
                     this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
                 }
             }
         }">
    <div class="mb-lg">
        <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-xs text-on-surface-variant hover:text-primary transition-colors font-label-md" wire:navigate>
            <span class="material-symbols-outlined text-sm">arrow_back</span> Back to Portfolio
        </a>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-xl">
        <div class="lg:col-span-2 space-y-lg">
            <!-- Thumbnail with Zoom Click -->
            @if($project->thumbnail)
            <div class="relative group cursor-zoom-in rounded-lg overflow-hidden border border-outline-variant/30 bg-surface-container-low"
                 @click="openLightbox('{{ media_url($project->thumbnail) }}')">
                <img src="{{ media_url($project->thumbnail) }}" alt="{{ $project->title }}" class="w-full object-cover aspect-video transition-transform duration-300 group-hover:scale-[1.02]"/>
                <div class="absolute bottom-3 right-3 bg-surface/85 dark:bg-black/70 backdrop-blur-md px-3 py-1.5 rounded-md text-xs font-semibold text-on-surface flex items-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="material-symbols-outlined text-[16px] text-primary">zoom_in</span>
                    <span>Klik untuk memperbesar</span>
                </div>
            </div>
            @endif
            
            <div>
                <h1 class="font-display-lg-mobile md:font-headline-xl text-on-background dark:text-white mb-md font-bold">{{ $project->title }}</h1>
                
                {{-- Formatted Rich Text Description --}}
                @if($project->description)
                <div class="project-html-content text-on-surface/90 text-sm md:text-base leading-relaxed">
                    {!! $project->description !!}
                </div>
                @elseif($project->short_description)
                <div class="font-body-lg text-on-surface whitespace-pre-wrap leading-relaxed">
                    {{ $project->short_description }}
                </div>
                @endif
            </div>

            @if($project->images && $project->images->count() > 0)
            <h3 class="font-headline-lg mt-xl border-b border-outline-variant pb-2 mb-md font-bold text-on-surface">Gallery</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-md">
                @foreach($project->images as $img)
                <div class="relative group cursor-zoom-in rounded-md overflow-hidden border border-outline-variant/30 bg-surface-container-low h-40"
                     @click="openLightbox('{{ media_url($img->image) }}')">
                    <img src="{{ media_url($img->image) }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-[24px] drop-shadow-md">zoom_in</span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        
        <div class="lg:col-span-1">
            <div class="bg-surface rounded-md border border-outline-variant p-lg sticky top-24 shadow-xs">
                <h3 class="font-headline-sm font-bold text-on-background dark:text-white mb-md pb-xs border-b border-outline-variant">Project Info</h3>
                
                @php
                    $detailClients = $project->clients && $project->clients->count() > 0 ? $project->clients : ($project->client ? collect([$project->client]) : collect());
                @endphp
                @if($detailClients->count() > 0)
                <div class="mb-md">
                    <h4 class="font-label-md text-on-surface-variant uppercase tracking-wider mb-xs flex items-center justify-between">
                        <span>{{ $detailClients->count() > 1 ? 'Dipercaya & Digunakan Oleh' : 'Client' }}</span>
                        @if($detailClients->count() > 1)
                            <span class="text-[11px] bg-primary/10 text-primary font-bold px-1.5 py-0.5 rounded">{{ $detailClients->count() }} Klien</span>
                        @endif
                    </h4>
                    <div class="space-y-2 mt-1.5">
                        @foreach($detailClients as $cl)
                        <div class="flex items-center gap-2 p-2 rounded-lg bg-surface-container-low border border-outline-variant/50">
                            @if($cl->logo)
                                <img src="{{ media_url($cl->logo) }}" alt="{{ $cl->name }}" class="w-7 h-7 object-contain rounded bg-white p-0.5 border border-outline-variant/30 flex-shrink-0">
                            @else
                                <div class="w-7 h-7 rounded bg-primary-container/40 text-primary flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-[16px]">business</span>
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <p class="font-body-md text-sm text-on-surface font-semibold truncate">{{ $cl->name }}</p>
                                @if($cl->url || $cl->website)
                                    <a href="{{ $cl->url ?? $cl->website }}" target="_blank" class="text-[11px] text-primary hover:underline flex items-center gap-0.5 truncate">
                                        <span class="truncate">{{ parse_url($cl->url ?? $cl->website, PHP_URL_HOST) ?? ($cl->url ?? $cl->website) }}</span>
                                        <span class="material-symbols-outlined text-[10px]">open_in_new</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                @if($project->projectCategory)
                <div class="mb-md">
                    <h4 class="font-label-md text-on-surface-variant uppercase tracking-wider mb-xs">Category</h4>
                    <p class="font-body-md text-on-surface font-semibold">{{ $project->projectCategory->name }}</p>
                </div>
                @endif

                @if($project->projectType)
                <div class="mb-md">
                    <h4 class="font-label-md text-on-surface-variant uppercase tracking-wider mb-xs">Type / Platform</h4>
                    <p class="font-body-md text-on-surface font-semibold flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-primary text-[18px]">devices</span>
                        <span>{{ $project->projectType->name }}</span>
                    </p>
                </div>
                @endif

                @if($project->completed_at || $project->date)
                <div class="mb-md">
                    <h4 class="font-label-md text-on-surface-variant uppercase tracking-wider mb-xs">Date</h4>
                    <p class="font-body-md text-on-surface font-semibold">{{ \Carbon\Carbon::parse($project->completed_at ?? $project->date)->format('F Y') }}</p>
                </div>
                @endif
                
                <div class="mt-lg pt-md border-t border-outline-variant space-y-3">
                    @if($project->project_url || $project->url)
                    <a href="{{ $project->project_url ?? $project->url }}" target="_blank" class="w-full text-center px-md py-2.5 bg-primary text-white rounded-lg font-label-md font-bold hover:brightness-110 transition shadow-sm flex items-center justify-center gap-sm">
                        Visit Project <span class="material-symbols-outlined text-sm">open_in_new</span>
                    </a>
                    @endif

                    <!-- Tombol Download / Cetak Brosur (Autogenerated PDF / Print) -->
                    <a href="{{ route('projects.brochure', $project->slug) }}?print=1" target="_blank" class="w-full text-center px-md py-2.5 bg-primary hover:bg-primary/90 text-on-secondary rounded-lg font-label-md font-bold transition shadow-sm flex items-center justify-center gap-2 group">
                        <span class="material-symbols-outlined text-base group-hover:scale-110 transition-transform">picture_as_pdf</span>
                        <span>Download Brosur</span>
                    </a>

                    <!-- Tombol Order / Pembelian Produk -->
                    @if(!empty($project->order_url))
                    <a href="{{ $project->order_url }}" target="_blank" class="w-full text-center px-md py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-lg font-label-md font-bold transition flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-base">shopping_cart</span>
                        <span>Beli / Order Produk Ini</span>
                    </a>
                    @endif
                </div>

                <!-- Bagian Share ke Sosial Media -->
                @php
                    $currentUrl = request()->fullUrl();
                    $shareTitle = urlencode($project->title . ' - Rhantech Digital Solution');
                    $encodedUrl = urlencode($currentUrl);
                @endphp
                <div class="mt-md pt-md border-t border-outline-variant/60" x-data="{ copied: false }">
                    <h4 class="font-label-md text-xs uppercase tracking-wider text-on-surface-variant font-semibold mb-2.5 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-primary">share</span>
                        <span>Bagikan Portfolio Ini</span>
                    </h4>
                    
                    <div class="grid grid-cols-4 gap-2">
                        <!-- Facebook -->
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedUrl }}" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           title="Share ke Facebook"
                           class="flex flex-col items-center justify-center p-2 rounded-lg bg-[#1877F2]/10 hover:bg-[#1877F2] text-[#1877F2] hover:text-white transition-all group">
                            <svg class="w-4 h-4 fill-current mb-1" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            <span class="text-[10px] font-semibold">FB</span>
                        </a>

                        <!-- WhatsApp -->
                        <a href="https://api.whatsapp.com/send?text={{ $shareTitle }}%20{{ $encodedUrl }}" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           title="Share ke WhatsApp"
                           class="flex flex-col items-center justify-center p-2 rounded-lg bg-[#25D366]/10 hover:bg-[#25D366] text-[#25D366] hover:text-white transition-all group">
                            <svg class="w-4 h-4 fill-current mb-1" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                            <span class="text-[10px] font-semibold">WA</span>
                        </a>

                        <!-- X (Twitter) -->
                        <a href="https://twitter.com/intent/tweet?text={{ $shareTitle }}&url={{ $encodedUrl }}" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           title="Share ke X (Twitter)"
                           class="flex flex-col items-center justify-center p-2 rounded-lg bg-black/10 dark:bg-white/10 hover:bg-black dark:hover:bg-white text-black dark:text-white hover:text-white dark:hover:text-black transition-all group">
                            <svg class="w-3.5 h-3.5 fill-current mb-1" viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                            <span class="text-[10px] font-semibold">X</span>
                        </a>

                        <!-- Telegram -->
                        <a href="https://t.me/share/url?url={{ $encodedUrl }}&text={{ $shareTitle }}" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           title="Share ke Telegram"
                           class="flex flex-col items-center justify-center p-2 rounded-lg bg-[#229ED9]/10 hover:bg-[#229ED9] text-[#229ED9] hover:text-white transition-all group">
                            <svg class="w-4 h-4 fill-current mb-1" viewBox="0 0 24 24">
                                <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.643-.204-.657-.643.136-.953l11.57-4.458c.538-.196 1.006.128.832.941z"/>
                            </svg>
                            <span class="text-[10px] font-semibold">Tele</span>
                        </a>
                    </div>

                    <!-- Salin Link untuk Instagram, TikTok, dll -->
                    <div class="mt-2">
                        <button type="button" 
                                @click="navigator.clipboard.writeText('{{ $currentUrl }}'); copied = true; setTimeout(() => copied = false, 2500)"
                                class="w-full py-1.5 px-3 rounded-md bg-surface-container-low hover:bg-surface-container border border-outline-variant/60 text-xs font-semibold text-on-surface flex items-center justify-center gap-1.5 transition-all">
                            <span class="material-symbols-outlined text-sm" x-text="copied ? 'check' : 'content_copy'"></span>
                            <span x-text="copied ? 'Tautan Berhasil Disalin!' : 'Salin Link (Instagram / TikTok / Bio)'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fullscreen Lightbox Modal (Zoom Image) -->
    <div x-show="showLightbox" 
         x-transition.opacity.duration.300ms 
         style="display: none;"
         class="fixed inset-0 z-[100] bg-black/95 flex items-center justify-center p-4 md:p-8 backdrop-blur-md"
         @click.self="showLightbox = false"
         @keydown.window.escape="showLightbox = false"
         @keydown.window.arrow-right="if(showLightbox) next()"
         @keydown.window.arrow-left="if(showLightbox) prev()">
        
        <button @click="showLightbox = false" class="absolute top-4 right-4 md:top-6 md:right-6 text-white/70 hover:text-white transition-colors bg-white/10 hover:bg-white/20 rounded-full w-10 h-10 flex items-center justify-center z-10 backdrop-blur cursor-pointer">
            <span class="material-symbols-outlined text-2xl">close</span>
        </button>

        <button x-show="images.length > 1" @click.stop="prev()" class="absolute left-2 md:left-6 top-1/2 -translate-y-1/2 text-white/70 hover:text-white bg-white/10 hover:bg-white/20 rounded-full w-10 h-10 md:w-14 md:h-14 flex items-center justify-center z-10 backdrop-blur transition-colors cursor-pointer">
            <span class="material-symbols-outlined text-2xl md:text-4xl">chevron_left</span>
        </button>
        
        <button x-show="images.length > 1" @click.stop="next()" class="absolute right-2 md:right-6 top-1/2 -translate-y-1/2 text-white/70 hover:text-white bg-white/10 hover:bg-white/20 rounded-full w-10 h-10 md:w-14 md:h-14 flex items-center justify-center z-10 backdrop-blur transition-colors cursor-pointer">
            <span class="material-symbols-outlined text-2xl md:text-4xl">chevron_right</span>
        </button>

        <img :src="images[currentIndex]" class="max-w-full max-h-[88vh] object-contain rounded-xl transition-all duration-300 select-none" alt="Fullscreen Preview" @click.self="showLightbox = false">

        <div x-show="images.length > 1" class="absolute bottom-6 md:bottom-8 left-1/2 -translate-x-1/2 text-white bg-white/10 backdrop-blur px-5 py-2 rounded-full text-xs font-bold tracking-wider z-10">
            <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
        </div>
    </div>
</section>

<style>
    /* Styling Format Rich Text Quill di Halaman Publik */
    .project-html-content p {
        margin-bottom: 0.875rem;
        line-height: 1.7;
    }
    .project-html-content strong {
        font-weight: 700;
    }
    .project-html-content em {
        font-style: italic;
    }
    .project-html-content u {
        text-decoration: underline;
    }
    .project-html-content s {
        text-decoration: line-through;
    }
    .project-html-content ul {
        list-style-type: disc !important;
        margin-left: 1.5rem !important;
        margin-bottom: 1rem !important;
        padding-left: 0.5rem !important;
    }
    .project-html-content ol {
        list-style-type: decimal !important;
        margin-left: 1.5rem !important;
        margin-bottom: 1rem !important;
        padding-left: 0.5rem !important;
    }
    .project-html-content li {
        margin-bottom: 0.35rem;
        line-height: 1.6;
        display: list-item !important;
    }
    .project-html-content h1 {
        font-size: 1.75rem;
        font-weight: 700;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
    }
    .project-html-content h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-top: 1.25rem;
        margin-bottom: 0.75rem;
    }
    .project-html-content h3 {
        font-size: 1.25rem;
        font-weight: 600;
        margin-top: 1rem;
        margin-bottom: 0.5rem;
    }
    .project-html-content blockquote {
        border-left: 4px solid var(--theme-primary, #0284c7);
        padding-left: 1rem;
        margin: 1rem 0;
        font-style: italic;
        opacity: 0.9;
    }
    .project-html-content a {
        color: var(--theme-primary, #0284c7);
        text-decoration: underline;
    }
    .project-html-content a:hover {
        opacity: 0.8;
    }
    .project-html-content img {
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
        margin: 1rem 0;
    }
</style>
@endsection

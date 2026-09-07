@extends('layouts.public')
@section('title', $project->title . ' - ' . ($company->name ?? 'rhantech'))

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
                <div class="absolute bottom-3 right-3 bg-surface/85 dark:bg-black/70 backdrop-blur-md px-3 py-1.5 rounded-md text-xs font-semibold text-on-surface flex items-center gap-1.5 shadow-md opacity-0 group-hover:opacity-100 transition-opacity">
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
                                <div class="w-7 h-7 rounded bg-secondary-container/40 text-secondary flex items-center justify-center flex-shrink-0">
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
                
                @if($project->project_url || $project->url)
                <div class="mt-lg pt-md border-t border-outline-variant">
                    <a href="{{ $project->project_url ?? $project->url }}" target="_blank" class="w-full text-center px-md py-3 bg-primary text-white rounded-lg font-label-md font-bold hover:brightness-110 transition shadow flex items-center justify-center gap-sm" wire:navigate>
                        Visit Project <span class="material-symbols-outlined text-sm">open_in_new</span>
                    </a>
                </div>
                @endif
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

        <img :src="images[currentIndex]" class="max-w-full max-h-[88vh] object-contain rounded-xl shadow-2xl transition-all duration-300 select-none" alt="Fullscreen Preview" @click.self="showLightbox = false">

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

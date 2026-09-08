@extends('layouts.public')
@section('title', 'rhantech - We Build Digital Experiences')

@section('content')
<!-- Hero Section -->
<section class="pb-2xl px-lg md:px-xl max-w-container-max mx-auto min-h-[85vh] flex flex-col justify-center relative" id="home">
    <!-- Abstract Background Element -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-secondary-container/20 rounded-full blur-[100px] -z-10"></div>
    <div class="absolute bottom-20 left-10 w-[300px] h-[300px] bg-primary/5 rounded-full blur-[80px] -z-10"></div>
    
    <div class="grid grid-cols-1 md:grid-cols-12 gap-lg items-center">
        <div class="md:col-span-7 flex flex-col items-start z-10">
            <span class="inline-block py-1 px-3 rounded-full bg-surface-container text-on-surface font-label-md text-label-md mb-6 border border-outline-variant/30">
                Innovative Digital Solutions
            </span>
            <h1 class="font-display-lg-mobile md:font-display-lg text-display-lg-mobile md:text-display-lg text-on-background dark:text-white mb-6 text-balance">
                We Build <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#06B6D4] to-blue-500">Digital</span> Experiences
            </h1>
            <p class="font-body-lg text-body-lg text-on-surface-variant mb-xl max-w-2xl text-balance">
                Helping businesses build scalable, modern, and impactful digital solutions. We combine engineering excellence with compelling design to propel your brand forward.
            </p>
            <div class="flex flex-wrap items-center gap-4 w-full sm:w-auto">
                <a class="w-full sm:w-auto text-center px-8 py-4 bg-gradient-to-r from-[#06B6D4] to-blue-500 text-white rounded-lg font-label-md text-label-md hover:opacity-90 transition-all shadow-lg hover:-translate-y-1 border-0" href="{{ url('/projects') }}" wire:navigate>
                    View Our Work
                </a>
                <a class="w-full sm:w-auto text-center px-8 py-4 bg-transparent text-on-background dark:text-white border border-outline-variant rounded-lg font-label-md text-label-md hover:bg-surface-container-low transition-all" href="{{ url('/contact') }}" wire:navigate>
                    Let's Talk
                </a>
            </div>
        </div>
        
        <div class="md:col-span-5 relative mt-12 md:mt-0 z-10">
            <div class="relative rounded-2xl overflow-hidden shadow-[0px_20px_25px_-5px_rgba(15,23,42,0.1),0px_8px_10px_-6px_rgba(15,23,42,0.1)] border border-outline-variant/50 group">
                <div class="absolute inset-0 bg-primary/10 group-hover:bg-transparent transition-colors duration-500 z-10"></div>
                <img fetchpriority="high" alt="Tech workspace" class="w-full h-[600px] object-cover transition-transform duration-700 group-hover:scale-105" src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"/>
            </div>
            
            <!-- Floating Stats Card -->
            <div class="absolute -bottom-8 -left-8 bg-surface p-6 rounded-xl border border-outline-variant shadow-[0px_20px_25px_-5px_rgba(15,23,42,0.1)] z-20 animate-[bounce_3s_ease-in-out_infinite]">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-[#06B6D4]/10 rounded-full text-[#06B6D4]">
                        <span class="material-symbols-outlined" data-icon="rocket_launch">rocket_launch</span>
                    </div>
                    <div>
                        <div class="font-headline-lg text-headline-lg text-primary">99%</div>
                        <div class="font-label-md text-label-md text-on-surface-variant">Project Success Rate</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Aplikasi Yang Sering Dilihat Calon Pembeli --}}
    @if(isset($popularProducts) && $popularProducts->count() > 0)
    <div class="mt-12 pt-8 border-t border-outline-variant/30 z-10">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-5">
            <div class="flex items-center gap-2">
                <span class="p-1.5 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[18px]">trending_up</span>
                </span>
                <div>
                    <h2 class="text-sm md:text-base font-bold text-on-background dark:text-white leading-tight">
                        Aplikasi Populer Paling Sering Dilihat
                    </h2>
                    <p class="text-xs text-on-surface-variant">Produk & sistem digital rekomendasi yang paling diminati calon pembeli</p>
                </div>
            </div>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-primary hover:underline group shrink-0" wire:navigate>
                <span>Lihat Semua Katalog</span>
                <span class="material-symbols-outlined text-[14px] transition-transform group-hover:translate-x-0.5">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
            @foreach($popularProducts as $prod)
            @php
                $mainImg = $prod->images->where('is_main', true)->first() ?? $prod->images->first();
                $hasDiscount = $prod->discount_price && $prod->discount_price > 0 && $prod->discount_price < $prod->price;
                $effectivePrice = $hasDiscount ? $prod->discount_price : $prod->price;
                $soldCount = $prod->sales_count ?: ($prod->orders_count ?? 0);
                if ($soldCount < 5 && $prod->id % 2 === 0) {
                    $displaySold = $soldCount > 0 ? $soldCount : (10 + ($prod->id % 15));
                } else {
                    $displaySold = $soldCount > 0 ? $soldCount : 12;
                }

                // Rating & Reviews count
                $ratingDisplay = $prod->effective_rating;
                $reviewsDisplayCount = $prod->effective_reviews_count;

                // Sort description: Utamakan isi Short Description resmi toko. Jika kosong, baru ambil cuplikan sebagian dari full deskripsi
                if (!empty($prod->short_description)) {
                    $descText = trim($prod->short_description);
                } else {
                    $cleanDesc = trim(preg_replace('/\s+/', ' ', strip_tags($prod->description ?? '')));
                    $descText = Str::limit($cleanDesc, 110, '...');
                }
            @endphp
            <a href="{{ route('products.show', $prod->slug) }}" class="group bg-surface dark:bg-surface-container-low rounded-xl border border-outline-variant/60 hover:border-primary/50 overflow-hidden shadow-xs hover:shadow-md transition-all duration-200 flex flex-col hover:-translate-y-1" wire:navigate>
                <div class="relative aspect-4/3 w-full bg-surface-container overflow-hidden">
                    @if($mainImg)
                        <img src="{{ asset('storage/' . $mainImg->image_path) }}" alt="{{ $prod->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-outline-variant">
                            <span class="material-symbols-outlined text-3xl">inventory_2</span>
                        </div>
                    @endif

                    @if($hasDiscount)
                        <span class="absolute top-2 left-2 bg-red-500 text-white text-[9px] font-extrabold px-1.5 py-0.5 rounded shadow">
                            -{{ round((($prod->price - $prod->discount_price) / $prod->price) * 100) }}%
                        </span>
                    @endif

                    <div class="absolute bottom-1.5 right-1.5 bg-black/60 backdrop-blur-xs text-white text-[9px] font-medium px-1.5 py-0.5 rounded flex items-center gap-0.5">
                        <span class="material-symbols-outlined text-[10px]">visibility</span>
                        <span>{{ number_format($prod->views ?? 0) }}</span>
                    </div>
                </div>

                <div class="p-3.5 flex flex-col flex-1">
                    <div class="flex items-center justify-between gap-1 mb-1">
                        <span class="text-[10px] font-bold text-primary uppercase tracking-wider line-clamp-1">
                            {{ $prod->category->name ?? ($prod->type->name ?? 'Aplikasi') }}
                        </span>
                        {{-- Rating badge --}}
                        <span class="inline-flex items-center gap-0.5 text-[11px] font-bold text-amber-500 shrink-0">
                            <span class="material-symbols-outlined text-[12px] fill-current text-amber-500">star</span>
                            <span>{{ number_format((float)$ratingDisplay, 1) }}</span>
                            @if($reviewsDisplayCount > 0)
                                <span class="text-[9px] font-normal text-on-surface-variant">({{ $reviewsDisplayCount }})</span>
                            @endif
                        </span>
                    </div>

                    <h3 class="text-xs md:text-sm font-bold text-on-background dark:text-white line-clamp-2 leading-snug group-hover:text-primary transition-colors mb-1.5">
                        {{ $prod->name }}
                    </h3>

                    {{-- Toko --}}
                    <div class="flex items-center gap-1 text-[10px] text-on-surface-variant mb-2">
                        <span class="material-symbols-outlined text-[12px] text-primary">storefront</span>
                        <span class="truncate font-medium">{{ $prod->store ? $prod->store->name : ($company->company_name ?? 'Official Store') }}</span>
                    </div>

                    {{-- Sort Deskripsi Full agar calon pembeli bisa membaca --}}
                    @if(!empty($descText))
                        <div class="mb-3 p-2 rounded-lg bg-surface-container/60 border border-outline-variant/30 text-[11px] text-on-surface-variant leading-relaxed">
                            <p class="font-normal whitespace-pre-line break-words">{{ $descText }}</p>
                        </div>
                    @endif

                    <div class="pt-2 border-t border-outline-variant/40 flex items-center justify-between mt-auto">
                        <div>
                            @if($hasDiscount)
                                <p class="text-[10px] text-on-surface-variant line-through leading-none mb-0.5">
                                    Rp{{ number_format($prod->price, 0, ',', '.') }}
                                </p>
                            @endif
                            <p class="text-xs md:text-sm font-extrabold text-primary leading-tight">
                                Rp{{ number_format($effectivePrice, 0, ',', '.') }}
                            </p>
                        </div>
                        <span class="text-[9px] px-1.5 py-0.5 bg-surface-container rounded text-on-surface-variant font-medium shrink-0">
                            {{ $displaySold }} Terjual
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</section>

<!-- About Section -->
<section id="about" class="py-2xl border-t border-outline-variant/30">
    <div class="max-w-container-max mx-auto px-lg">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-xl items-center">
            <div>
                <h2 class="font-headline-xl text-on-background dark:text-white mb-md">About Us</h2>
                <div class="font-body-lg text-on-surface-variant whitespace-pre-wrap">{{ $company->about_text ?? 'We are a dedicated team of professionals focused on delivering the best results for our clients.' }}</div>
            </div>
            <div class="grid grid-cols-2 gap-md">
                <div class="bg-surface-container rounded-xl p-lg text-center border border-outline-variant/30">
                    <div class="font-display-lg text-secondary mb-xs">25+</div>
                    <div class="font-label-md text-on-surface">Years Experience</div>
                </div>
                <div class="bg-surface-container rounded-xl p-lg text-center border border-outline-variant/30">
                    <div class="font-display-lg text-secondary mb-xs">1500+</div>
                    <div class="font-label-md text-on-surface">Projects Delivered</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
@if(isset($services) && $services->count() > 0)
<section id="services" class="py-2xl bg-surface-container">
    <div class="max-w-container-max mx-auto px-lg">
        <div class="text-center mb-xl">
            <h2 class="font-headline-xl text-on-background dark:text-white mb-md">Our Services</h2>
            <p class="font-body-lg text-on-surface-variant max-w-2xl mx-auto">Comprehensive digital solutions tailored to your business needs.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-lg">
            @foreach($services as $service)
            <div class="bg-surface rounded-xl p-lg border border-outline-variant shadow-sm hover:shadow-md transition-shadow group">
                <div class="w-14 h-14 rounded-lg bg-secondary-container/20 text-secondary flex items-center justify-center mb-md group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-3xl">{{ $service->icon ?? 'layers' }}</span>
                </div>
                <h3 class="font-headline-sm font-bold text-on-background dark:text-white mb-sm">{{ $service->name }}</h3>
                <p class="font-body-md text-on-surface-variant">{{ $service->short_description ?: Str::limit($service->description, 120) }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Projects Preview -->
@if(isset($projects) && $projects->count() > 0)
<section id="projects" class="py-2xl">
    <div class="max-w-container-max mx-auto px-lg">
        <div class="flex flex-col md:flex-row justify-between items-end mb-xl gap-md">
            <div>
                <h2 class="font-headline-xl text-on-background dark:text-white mb-md">Featured Work</h2>
                <p class="font-body-lg text-on-surface-variant max-w-2xl">A glimpse into some of our recent successful partnerships.</p>
            </div>
            <a href="{{ route('projects.index') }}" class="px-lg py-3 rounded-lg border border-outline-variant font-label-md text-primary hover:bg-surface-container transition-colors inline-flex items-center gap-xs" wire:navigate>
                View All Projects <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
            @foreach($projects as $project)
            <div class="bg-surface rounded-xl border border-outline-variant overflow-hidden hover:shadow-lg transition-shadow duration-300 group">
                <div class="relative h-48 overflow-hidden">
                    @if($project->thumbnail)
                    <img loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ media_url($project->thumbnail) }}" alt="{{ $project->title }}"/>
                    @else
                    <div class="w-full h-full bg-surface-container flex items-center justify-center text-outline-variant">
                        <span class="material-symbols-outlined text-4xl">image</span>
                    </div>
                    @endif
                    <div class="absolute top-sm right-sm bg-surface-bright/90 backdrop-blur text-on-surface font-label-md px-sm py-xs rounded">
                        {{ $project->projectCategory->name ?? 'Uncategorized' }}
                    </div>
                </div>
                <div class="p-md">
                    <h3 class="font-headline-sm font-bold text-on-background dark:text-white mb-xs">{{ $project->title }}</h3>
                    <a class="inline-flex items-center gap-xs font-label-md text-secondary hover:text-secondary-fixed-dim transition-colors" href="{{ route('projects.show', $project->slug) }}" wire:navigate>
                        View Detail <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

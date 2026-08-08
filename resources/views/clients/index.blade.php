@extends('layouts.public')
@section('title', 'Our Clients - ' . ($company->name ?? 'rhantech'))

@section('content')
<section class="max-w-container-max mx-auto px-lg py-2xl text-center">
    <h1 class="font-display-lg text-display-lg text-primary mb-md hidden md:block">Our Clients</h1>
    <h1 class="font-display-lg-mobile text-display-lg-mobile text-primary mb-md md:hidden">Our Clients</h1>
    <p class="font-body-lg text-body-lg text-on-surface-variant max-w-3xl mx-auto">
        We are proud to have partnered with these amazing organizations.
    </p>
</section>

<!-- Clients Grid -->
<section class="max-w-container-max mx-auto mb-2xl overflow-hidden relative">
    <!-- Marquee Style -->
    <style>
        .marquee-wrapper {
            display: flex;
            overflow: hidden;
            width: 100%;
        }
        .marquee-content {
            display: grid;
            grid-template-rows: repeat(2, 1fr);
            grid-auto-flow: column;
            gap: 1rem;
            padding-right: 1rem;
            animation: scroll-marquee 2000s linear infinite;
        }
        .marquee-wrapper:hover .marquee-content {
            animation-play-state: paused;
        }
        @keyframes scroll-marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
    </style>

    <div class="marquee-wrapper px-lg">
        <div class="marquee-content">
            @if(count($clients) > 0)
                <!-- First Set -->
                @foreach($clients as $client)
                <div class="w-[280px] bg-surface rounded-xl border border-outline-variant p-lg flex items-center justify-center h-40 hover:shadow-md transition-shadow shrink-0">
                    @if($client->url)
                    <a href="{{ $client->url }}" target="_blank" title="{{ $client->name }}" class="flex flex-col items-center justify-center h-full gap-2 w-full">
                    @else
                    <div class="flex flex-col items-center justify-center h-full gap-2 w-full">
                    @endif
                        @if($client->logo)
                        <div class="h-16 flex items-center justify-center w-full">
                            <img src="{{ asset('storage/' . $client->logo) }}" alt="{{ $client->name }}" class="max-w-full max-h-full object-contain filter grayscale opacity-70 hover:grayscale-0 hover:opacity-100 transition-all duration-300">
                        </div>
                        @endif
                        <span class="font-label-md font-bold text-on-surface-variant text-center truncate w-full">{{ $client->name }}</span>
                    @if($client->url)
                    </a>
                    @else
                    </div>
                    @endif
                </div>
                @endforeach
                <!-- Duplicate Set for Seamless Loop -->
                @foreach($clients as $client)
                <div class="w-[280px] bg-surface rounded-xl border border-outline-variant p-lg flex items-center justify-center h-40 hover:shadow-md transition-shadow shrink-0">
                    @if($client->url)
                    <a href="{{ $client->url }}" target="_blank" title="{{ $client->name }}" class="flex flex-col items-center justify-center h-full gap-2 w-full">
                    @else
                    <div class="flex flex-col items-center justify-center h-full gap-2 w-full">
                    @endif
                        @if($client->logo)
                        <div class="h-16 flex items-center justify-center w-full">
                            <img src="{{ asset('storage/' . $client->logo) }}" alt="{{ $client->name }}" class="max-w-full max-h-full object-contain filter grayscale opacity-70 hover:grayscale-0 hover:opacity-100 transition-all duration-300">
                        </div>
                        @endif
                        <span class="font-label-md font-bold text-on-surface-variant text-center truncate w-full">{{ $client->name }}</span>
                    @if($client->url)
                    </a>
                    @else
                    </div>
                    @endif
                </div>
                @endforeach
            @else
                <div class="text-center py-xl text-on-surface-variant w-full">
                    <p>No clients available yet.</p>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Testimonials Section -->
@if($testimonials->count() > 0)
<section class="bg-surface-container py-2xl">
    <div class="max-w-container-max mx-auto px-lg">
        <div class="text-center mb-xl">
            <h2 class="font-headline-xl text-primary mb-md">What They Say</h2>
            <p class="font-body-lg text-on-surface-variant max-w-2xl mx-auto">Don't just take our word for it.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-lg">
            @foreach($testimonials as $testimonial)
            <div class="bg-surface rounded-xl border border-outline-variant p-lg shadow-sm">
                <div class="flex text-[#F59E0B] mb-md text-xl">
                    @for($i=0; $i<$testimonial->rating; $i++)
                    ★
                    @endfor
                </div>
                <p class="font-body-lg text-on-surface mb-lg italic">"{{ $testimonial->content }}"</p>
                
                <div class="flex items-center gap-md">
                    @if($testimonial->photo)
                    <img src="{{ asset('storage/' . $testimonial->photo) }}" alt="{{ $testimonial->name }}" class="w-12 h-12 rounded-full object-cover border border-outline-variant">
                    @elseif($testimonial->client && $testimonial->client->logo)
                    <img src="{{ asset('storage/' . $testimonial->client->logo) }}" alt="{{ $testimonial->name }}" class="w-12 h-12 rounded-full object-cover border border-outline-variant p-1 bg-white">
                    @else
                    <div class="w-12 h-12 rounded-full border border-outline-variant flex items-center justify-center bg-surface-container-high text-on-surface-variant">
                        <span class="material-symbols-outlined">person</span>
                    </div>
                    @endif
                    <div>
                        <div class="font-label-md text-primary font-bold">{{ $testimonial->name }}</div>
                        <div class="font-code-sm text-on-surface-variant">
                            {{ $testimonial->position }}
                            @if($testimonial->client)
                            at {{ $testimonial->client->name }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

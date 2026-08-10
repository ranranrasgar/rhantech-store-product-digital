@extends('layouts.public')
@section('title', 'Our Portfolio - ' . ($company->name ?? 'rhantech'))

@section('content')
<!-- Hero Section -->
<section class="max-w-container-max mx-auto px-lg py-2xl text-center">
    <h1 class="font-display-lg text-display-lg text-on-background dark:text-white mb-md hidden md:block">Our Portfolio</h1>
    <h1 class="font-display-lg-mobile text-display-lg-mobile text-on-background dark:text-white mb-md md:hidden">Our Portfolio</h1>
    <p class="font-body-lg text-body-lg text-on-surface-variant max-w-3xl mx-auto">
        Explore a curated selection of our finest digital achievements. We build solutions that scale.
    </p>
</section>

<!-- Project Grid -->
<section class="max-w-container-max mx-auto px-lg">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
        @forelse($projects as $project)
        <div class="bg-surface rounded-md border border-outline-variant overflow-hidden hover:shadow-lg transition-shadow duration-300 group">
            <div class="relative h-64 overflow-hidden">
                @if($project->thumbnail)
                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ asset('storage/' . $project->thumbnail) }}" alt="{{ $project->title }}"/>
                @else
                <div class="w-full h-full bg-surface-container flex items-center justify-center text-outline-variant">
                    <span class="material-symbols-outlined text-4xl">image</span>
                </div>
                @endif
                <div class="absolute top-sm right-sm bg-surface-bright/90 backdrop-blur text-on-surface font-label-md text-label-md px-sm py-xs rounded">
                    {{ $project->projectCategory->name ?? 'Uncategorized' }}
                </div>
            </div>
            <div class="p-lg">
                <h3 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-background dark:text-white mb-sm">{{ $project->title }}</h3>
                <p class="font-body-md text-body-md text-on-surface-variant mb-md">{{ Str::limit($project->short_description, 100) }}</p>
                <a class="inline-flex items-center gap-xs font-label-md text-label-md text-secondary hover:text-secondary-fixed-dim transition-colors" href="{{ route('projects.show', $project->slug) }}" wire:navigate>
                    View Detail <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-2xl text-on-surface-variant">
            <span class="material-symbols-outlined text-4xl mb-sm">inventory_2</span>
            <p>No projects available yet.</p>
        </div>
        @endforelse
    </div>
    
    @if(method_exists($projects, 'links'))
    <div class="mt-xl">
        {{ $projects->links() }}
    </div>
    @endif
</section>
@endsection

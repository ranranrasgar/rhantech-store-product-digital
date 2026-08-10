@extends('layouts.public')
@section('title', $project->title . ' - ' . ($company->name ?? 'rhantech'))

@section('content')
<section class="pt-xl pb-2xl px-lg md:px-xl max-w-container-max mx-auto">
    <div class="mb-lg">
        <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-xs text-on-surface-variant hover:text-primary transition-colors font-label-md" wire:navigate>
            <span class="material-symbols-outlined text-sm">arrow_back</span> Back to Projects
        </a>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-xl">
        <div class="lg:col-span-2 space-y-lg">
            <!-- Thumbnail -->
            @if($project->thumbnail)
            <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="{{ $project->title }}" class="w-full rounded-lg object-cover  border border-outline-variant/30 aspect-video"/>
            @endif
            
            <div>
                <h1 class="font-display-lg-mobile md:font-headline-xl text-on-background dark:text-white mb-md">{{ $project->title }}</h1>
                <div class="prose prose-lg dark:prose-invert max-w-none font-body-lg text-on-surface whitespace-pre-wrap">
                    {!! $project->description !!}
                </div>
            </div>

            @if($project->images && $project->images->count() > 0)
            <h3 class="font-headline-lg mt-xl border-b border-outline-variant pb-2 mb-md">Gallery</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-md">
                @foreach($project->images as $img)
                <img src="{{ asset('storage/' . $img->image) }}" class="rounded-md w-full h-40 object-cover  border border-outline-variant/30">
                @endforeach
            </div>
            @endif
        </div>
        
        <div class="lg:col-span-1">
            <div class="bg-surface rounded-md border border-outline-variant  p-lg sticky top-24">
                <h3 class="font-headline-sm font-bold text-on-background dark:text-white mb-md pb-xs border-b border-outline-variant">Project Info</h3>
                
                @if($project->client)
                <div class="mb-md">
                    <h4 class="font-label-md text-on-surface-variant uppercase tracking-wider mb-xs">Client</h4>
                    <p class="font-body-md text-on-surface font-semibold">{{ $project->client->name }}</p>
                </div>
                @endif
                
                @if($project->projectCategory)
                <div class="mb-md">
                    <h4 class="font-label-md text-on-surface-variant uppercase tracking-wider mb-xs">Category</h4>
                    <p class="font-body-md text-on-surface font-semibold">{{ $project->projectCategory->name }}</p>
                </div>
                @endif

                @if($project->date)
                <div class="mb-md">
                    <h4 class="font-label-md text-on-surface-variant uppercase tracking-wider mb-xs">Date</h4>
                    <p class="font-body-md text-on-surface font-semibold">{{ \Carbon\Carbon::parse($project->date)->format('F Y') }}</p>
                </div>
                @endif
                
                @if($project->url)
                <div class="mt-lg pt-md border-t border-outline-variant">
                    <a href="{{ $project->url }}" target="_blank" class="w-full text-center px-md py-3 bg-primary text-white rounded-lg font-label-md font-bold hover:brightness-110 transition shadow flex items-center justify-center gap-sm" wire:navigate>
                        Visit Project <span class="material-symbols-outlined text-sm">open_in_new</span>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

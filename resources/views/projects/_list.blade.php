<!-- Result Stats Bar -->
<div class="flex items-center justify-between mb-6 pb-3 border-b border-outline-variant/50">
    <p class="text-sm text-on-surface-variant">
        Menampilkan <span class="font-semibold text-on-background dark:text-white">{{ $projects->total() }}</span> project
        @if(request('search'))
            untuk kata kunci <span class="font-semibold text-primary">"{{ request('search') }}"</span>
        @endif
    </p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-gutter">
    @forelse($projects as $project)
    <div class="bg-surface rounded-xl border border-outline-variant overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group flex flex-col">
        <div class="relative h-56 overflow-hidden bg-surface-container">
            @if($project->thumbnail)
            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ media_url($project->thumbnail) }}" alt="{{ $project->title }}"/>
            @else
            <div class="w-full h-full bg-surface-container flex items-center justify-center text-outline-variant">
                <span class="material-symbols-outlined text-4xl">image</span>
            </div>
            @endif
            
            <!-- Category & Type Badges -->
            <div class="absolute top-3 right-3 flex flex-col items-end gap-1.5 z-10">
                @if($project->projectCategory)
                <div class="bg-surface/90 dark:bg-surface-container-highest/90 backdrop-blur text-on-surface text-xs font-semibold px-2.5 py-1 rounded-md shadow-sm border border-outline-variant/30">
                    {{ $project->projectCategory->name }}
                </div>
                @endif
                @if($project->projectType)
                <div class="bg-primary/90 backdrop-blur text-white text-[11px] font-semibold px-2 py-0.5 rounded shadow-sm flex items-center gap-1">
                    <span class="material-symbols-outlined text-[12px]">devices</span>
                    <span>{{ $project->projectType->name }}</span>
                </div>
                @endif
            </div>

            <!-- Client Badge (jika ada) -->
            @php
                $clientCount = $project->clients ? $project->clients->count() : ($project->client ? 1 : 0);
                $firstClient = $project->clients && $project->clients->count() > 0 ? $project->clients->first() : $project->client;
            @endphp
            @if($clientCount > 0)
            <div class="absolute bottom-3 left-3 bg-surface-container-lowest/90 dark:bg-black/75 backdrop-blur text-xs font-medium text-on-surface px-2.5 py-1 rounded-md flex items-center gap-1.5 shadow-sm border border-outline-variant/30">
                <span class="material-symbols-outlined text-xs text-secondary">verified</span>
                @if($clientCount === 1)
                    <span class="truncate max-w-[150px] font-semibold">{{ $firstClient->name }}</span>
                @else
                    <span class="font-semibold text-primary">Dipercaya {{ $clientCount }} Klien</span>
                @endif
            </div>
            @endif
        </div>
        <div class="p-5 flex-1 flex flex-col">
            <h3 class="font-headline-sm text-lg font-bold text-on-background dark:text-white mb-2 group-hover:text-primary transition-colors line-clamp-2">
                {{ $project->title }}
            </h3>
            <p class="font-body-sm text-sm text-on-surface-variant mb-4 line-clamp-2 flex-1">
                {{ Str::limit($project->short_description ?: strip_tags($project->description), 110) }}
            </p>
            <div class="pt-3 border-t border-outline-variant/40 flex items-center justify-between">
                <a class="inline-flex items-center gap-1 text-sm font-semibold text-secondary hover:text-secondary-fixed-dim transition-colors" href="{{ route('projects.show', $project->slug) }}">
                    View Detail <span class="material-symbols-outlined text-base">arrow_forward</span>
                </a>
                @if($clientCount > 0)
                <span class="text-xs text-on-surface-variant/80 truncate max-w-[130px]" title="{{ $clientCount === 1 ? 'Client: ' . $firstClient->name : 'Digunakan oleh ' . $clientCount . ' klien' }}">
                    {{ $clientCount === 1 ? $firstClient->name : $clientCount . ' Klien' }}
                </span>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-16 bg-surface rounded-xl border border-outline-variant/60">
        <span class="material-symbols-outlined text-5xl mb-3 text-on-surface-variant/50">inventory_2</span>
        <h4 class="font-semibold text-lg text-on-background dark:text-white mb-1">Project Tidak Ditemukan</h4>
        <p class="text-sm text-on-surface-variant max-w-md mx-auto mb-4">
            Tidak ada project yang cocok dengan filter atau kata kunci pencarian yang Anda masukkan.
        </p>
        <button type="button" @click="resetFilters()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary text-on-primary text-xs font-semibold rounded-lg hover:opacity-90 transition-opacity">
            <span class="material-symbols-outlined text-sm">refresh</span> Lihat Semua Project
        </button>
    </div>
    @endforelse
</div>

@if(method_exists($projects, 'links') && $projects->hasPages())
<div class="mt-8 project-pagination">
    {{ $projects->links() }}
</div>
@endif

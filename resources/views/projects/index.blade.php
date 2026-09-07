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

<!-- Main Portfolio Section with Left Sidebar Filter & AJAX Instant Update -->
<section class="max-w-container-max mx-auto px-lg pb-2xl" 
         x-data="{
             search: '{{ request('search') }}',
             category: '{{ request('category') }}',
             type: '{{ request('type') }}',
             loading: false,
             debounceTimer: null,
             
             init() {
                 window.addEventListener('popstate', (e) => {
                     const params = new URLSearchParams(window.location.search);
                     this.search = params.get('search') || '';
                     this.category = params.get('category') || '';
                     this.type = params.get('type') || '';
                     this.fetchProjects(false);
                 });
             },

             setCategory(catSlug) {
                 if (this.category === catSlug) return;
                 this.category = catSlug;
                 this.fetchProjects(true);
             },

             setType(typeSlug) {
                 if (this.type === typeSlug) return;
                 this.type = typeSlug;
                 this.fetchProjects(true);
             },

             onSearchInput() {
                 clearTimeout(this.debounceTimer);
                 this.debounceTimer = setTimeout(() => {
                     this.fetchProjects(true);
                 }, 350);
             },

             clearSearch() {
                 this.search = '';
                 this.fetchProjects(true);
             },

             resetFilters() {
                 this.search = '';
                 this.category = '';
                 this.type = '';
                 this.fetchProjects(true);
             },

             fetchProjects(updateUrl = true) {
                 this.loading = true;
                 const params = new URLSearchParams();
                 if (this.search) params.append('search', this.search);
                 if (this.category) params.append('category', this.category);
                 if (this.type) params.append('type', this.type);
                 params.append('ajax', '1');

                 const url = '{{ route('projects.index') }}?' + params.toString();

                 fetch(url, {
                     headers: {
                         'X-Requested-With': 'XMLHttpRequest'
                     }
                 })
                 .then(res => res.text())
                 .then(html => {
                     const container = document.getElementById('project-grid-container');
                     if (container) {
                         container.innerHTML = html;
                         // Bind click listener to pagination links so they load via AJAX without reload
                         this.bindPaginationLinks();
                     }
                     if (updateUrl) {
                         params.delete('ajax');
                         const newQuery = params.toString();
                         const newUrl = '{{ route('projects.index') }}' + (newQuery ? '?' + newQuery : '');
                         window.history.pushState({}, '', newUrl);
                     }
                 })
                 .catch(err => {
                     console.error('Error fetching projects:', err);
                 })
                 .finally(() => {
                     this.loading = false;
                 });
             },

             bindPaginationLinks() {
                 const container = document.getElementById('project-grid-container');
                 if (!container) return;
                 const links = container.querySelectorAll('.project-pagination a');
                 links.forEach(link => {
                     link.addEventListener('click', (e) => {
                         e.preventDefault();
                         const pageUrl = new URL(link.getAttribute('href'));
                         const page = pageUrl.searchParams.get('page');
                         if (!page) return;

                         this.loading = true;
                         const params = new URLSearchParams();
                         if (this.search) params.append('search', this.search);
                         if (this.category) params.append('category', this.category);
                         if (this.type) params.append('type', this.type);
                         params.append('page', page);
                         params.append('ajax', '1');

                         fetch('{{ route('projects.index') }}?' + params.toString(), {
                             headers: { 'X-Requested-With': 'XMLHttpRequest' }
                         })
                         .then(res => res.text())
                         .then(html => {
                             container.innerHTML = html;
                             this.bindPaginationLinks();
                             params.delete('ajax');
                             window.history.pushState({}, '', '{{ route('projects.index') }}?' + params.toString());
                             // Scroll smoothly to top of results
                             window.scrollTo({ top: container.offsetTop - 120, behavior: 'smooth' });
                         })
                         .finally(() => {
                             this.loading = false;
                         });
                     });
                 });
             }
         }"
         x-init="bindPaginationLinks()">
    <div class="flex flex-col lg:flex-row gap-8 items-start">
        <!-- Sidebar Filter (Left Side) -->
        <aside class="w-full lg:w-72 shrink-0">
            <div class="bg-surface rounded-xl border border-outline-variant p-5 sticky top-24 shadow-sm">
                <!-- Header Filter -->
                <div class="flex items-center justify-between pb-4 mb-5 border-b border-outline-variant/60">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-xl">tune</span>
                        <h2 class="font-headline-sm text-base font-bold text-on-background dark:text-white">Filter & Pencarian</h2>
                    </div>
                    <button type="button" 
                            x-show="search || category || type" 
                            x-cloak
                            @click="resetFilters()" 
                            class="text-xs font-semibold text-error hover:underline flex items-center gap-0.5 transition-opacity">
                        <span class="material-symbols-outlined text-sm">restart_alt</span> Reset
                    </button>
                </div>

                <!-- Form Filter & Pencarian -->
                <form @submit.prevent="fetchProjects(true)" class="space-y-4">
                    <!-- Pencarian Project / Client -->
                    <div>
                        <label for="project-search" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-2">
                            Pencarian
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="project-search" 
                                   x-model="search"
                                   @input="onSearchInput()" 
                                   placeholder="Cari project atau client..." 
                                   class="w-full pl-9 pr-8 py-2 text-sm bg-surface-container-lowest text-on-surface rounded-lg border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">
                                search
                            </span>
                            <button type="button"
                                    x-show="search" 
                                    x-cloak
                                    @click="clearSearch()" 
                                    class="absolute right-2.5 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-error transition-colors"
                                    title="Hapus pencarian">
                                <span class="material-symbols-outlined text-sm">close</span>
                            </button>
                        </div>
                        <p class="text-[11px] text-on-surface-variant mt-1.5 leading-relaxed">
                            Cari otomatis nama project, deskripsi, atau nama client saat mengetik.
                        </p>
                    </div>
                </form>

                <!-- Filter Kategori -->
                <div class="mt-6 pt-5 border-t border-outline-variant/60">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-3 flex items-center justify-between">
                        <span>Kategori Project</span>
                        <span class="text-[11px] font-normal lowercase opacity-75">({{ $categories->count() }})</span>
                    </h3>

                    <div class="space-y-1">
                        <!-- Semua Kategori -->
                        <button type="button" 
                                @click="setCategory('')" 
                                class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm text-left transition-all duration-150"
                                :class="!category ? 'bg-primary/10 text-primary font-semibold border-l-4 border-primary' : 'text-on-surface hover:bg-surface-container-high'">
                            <span class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-base" :class="!category ? 'text-primary' : 'text-on-surface-variant'">category</span>
                                Semua Kategori
                            </span>
                        </button>

                        @foreach($categories as $cat)
                        <button type="button" 
                                @click="setCategory('{{ $cat->slug }}')" 
                                class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm text-left transition-all duration-150"
                                :class="category === '{{ $cat->slug }}' || category === '{{ $cat->id }}' ? 'bg-primary/10 text-primary font-semibold border-l-4 border-primary' : 'text-on-surface hover:bg-surface-container-high'">
                            <span class="truncate pr-2">{{ $cat->name }}</span>
                            @if(isset($cat->projects_count))
                            <span class="text-xs px-2 py-0.5 rounded-full transition-colors"
                                  :class="category === '{{ $cat->slug }}' || category === '{{ $cat->id }}' ? 'bg-primary text-on-primary' : 'bg-surface-container-highest text-on-surface-variant'">
                                {{ $cat->projects_count }}
                            </span>
                            @endif
                        </button>
                        @endforeach
                    </div>
                </div>

                @if(isset($types) && $types->count() > 0)
                <!-- Filter Tipe / Platform -->
                <div class="mt-6 pt-5 border-t border-outline-variant/60">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-3 flex items-center justify-between">
                        <span>Tipe / Platform</span>
                        <span class="text-[11px] font-normal lowercase opacity-75">({{ $types->count() }})</span>
                    </h3>

                    <div class="space-y-1">
                        <!-- Semua Tipe -->
                        <button type="button" 
                                @click="setType('')" 
                                class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm text-left transition-all duration-150"
                                :class="!type ? 'bg-primary/10 text-primary font-semibold border-l-4 border-primary' : 'text-on-surface hover:bg-surface-container-high'">
                            <span class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-base" :class="!type ? 'text-primary' : 'text-on-surface-variant'">devices</span>
                                Semua Platform
                            </span>
                        </button>

                        @foreach($types as $tp)
                        <button type="button" 
                                @click="setType('{{ $tp->slug }}')" 
                                class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm text-left transition-all duration-150"
                                :class="type === '{{ $tp->slug }}' || type === '{{ $tp->id }}' ? 'bg-primary/10 text-primary font-semibold border-l-4 border-primary' : 'text-on-surface hover:bg-surface-container-high'">
                            <span class="truncate pr-2">{{ $tp->name }}</span>
                            @if(isset($tp->projects_count))
                            <span class="text-xs px-2 py-0.5 rounded-full transition-colors"
                                  :class="type === '{{ $tp->slug }}' || type === '{{ $tp->id }}' ? 'bg-primary text-on-primary' : 'bg-surface-container-highest text-on-surface-variant'">
                                {{ $tp->projects_count }}
                            </span>
                            @endif
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Active Filter Tags -->
                <div x-show="search || category || type" x-cloak class="mt-6 pt-4 border-t border-outline-variant/60">
                    <span class="text-[11px] font-medium text-on-surface-variant uppercase tracking-wider block mb-2">Filter Aktif:</span>
                    <div class="flex flex-wrap gap-1.5">
                        <template x-if="search">
                            <span class="inline-flex items-center gap-1 text-xs bg-surface-container-high text-on-surface px-2.5 py-1 rounded-full border border-outline-variant">
                                <span x-text="'&quot;' + (search.length > 15 ? search.substring(0, 15) + '...' : search) + '&quot;'"></span>
                                <button type="button" @click="clearSearch()" class="hover:text-error">
                                    <span class="material-symbols-outlined text-xs">close</span>
                                </button>
                            </span>
                        </template>

                        <template x-if="category">
                            <span class="inline-flex items-center gap-1 text-xs bg-primary/15 text-primary px-2.5 py-1 rounded-full font-medium">
                                <span x-text="category"></span>
                                <button type="button" @click="setCategory('')" class="hover:text-error">
                                    <span class="material-symbols-outlined text-xs">close</span>
                                </button>
                            </span>
                        </template>

                        <template x-if="type">
                            <span class="inline-flex items-center gap-1 text-xs bg-secondary/15 text-secondary px-2.5 py-1 rounded-full font-medium">
                                <span class="material-symbols-outlined text-xs">devices</span>
                                <span x-text="type"></span>
                                <button type="button" @click="setType('')" class="hover:text-error">
                                    <span class="material-symbols-outlined text-xs">close</span>
                                </button>
                            </span>
                        </template>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Project Grid (Right Side) -->
        <main class="flex-1 w-full min-w-0 relative">
            <!-- Loading Indicator Overlay -->
            <div x-show="loading" 
                 x-cloak
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 bg-surface/60 dark:bg-black/50 backdrop-blur-sm z-20 flex items-start justify-center pt-24 rounded-xl">
                <div class="bg-surface border border-outline-variant px-5 py-3 rounded-xl shadow-xl flex items-center gap-3">
                    <svg class="animate-spin h-5 w-5 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span class="text-sm font-semibold text-on-surface">Memperbarui project...</span>
                </div>
            </div>

            <!-- Dynamic Project Grid Container -->
            <div id="project-grid-container" class="transition-opacity duration-200" :class="loading ? 'opacity-40' : 'opacity-100'">
                @include('projects._list', ['projects' => $projects])
            </div>
        </main>
    </div>
</section>
@endsection

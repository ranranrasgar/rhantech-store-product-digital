@extends('layouts.public')
@section('title', 'Our Portfolio - ' . ($company->name ?? 'rhantech'))

@section('content')
<!-- Hero Section -->
<section class="max-w-container-max mx-auto px-lg py-8 sm:py-2xl text-center">
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
             mobileFilterOpen: false,
             
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
        <!-- Sidebar Filter (Left Side - Desktop Only) -->
        <aside class="hidden lg:block lg:w-72 shrink-0">
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
                        <div class="relative flex items-center">
                            <input type="text" 
                                   id="project-search" 
                                   x-model="search"
                                   @input="onSearchInput()" 
                                   placeholder="Cari project atau client..." 
                                   class="w-full pl-9 pr-8 py-2 text-sm bg-surface-container-lowest text-on-surface rounded-lg border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-on-surface-variant">
                                <span class="material-symbols-outlined text-lg leading-none">
                                    search
                                </span>
                            </div>
                            <button type="button"
                                    x-show="search" 
                                    x-cloak
                                    @click="clearSearch()" 
                                    class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-on-surface-variant hover:text-error transition-colors"
                                    title="Hapus pencarian">
                                <span class="material-symbols-outlined text-sm leading-none">close</span>
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

        <!-- Project Grid (Right Side on Desktop, Full Width on Mobile) -->
        <main class="flex-1 w-full min-w-0 relative">
            <!-- Mobile Filter Bar & Funnel Trigger (Visible only on mobile/tablet screens < lg) -->
            <div class="lg:hidden flex items-center justify-between gap-3 mb-5 px-1">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-bold text-on-background dark:text-white">Daftar Project</span>
                    <template x-if="category || type || search">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-primary/15 text-primary">
                            Terfilter
                        </span>
                    </template>
                </div>
                
                <!-- Icon Corong Filter Button -->
                <button type="button" 
                        @click="mobileFilterOpen = true" 
                        class="relative inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-surface border border-outline-variant text-on-surface hover:border-primary active:scale-95 transition-all text-xs font-semibold"
                        title="Buka Filter Portofolio"
                        aria-label="Buka Filter">
                    <span class="material-symbols-outlined text-[19px] text-primary">filter_alt</span>
                    <span>Filter</span>
                    <template x-if="(search ? 1 : 0) + (category ? 1 : 0) + (type ? 1 : 0) > 0">
                        <span class="w-2 h-2 rounded-full bg-primary inline-block"></span>
                    </template>
                </button>
            </div>

            <!-- Active Filter Badges on Mobile -->
            <div x-show="search || category || type" x-cloak class="lg:hidden mb-4 flex flex-wrap gap-1.5 items-center">
                <span class="text-[11px] font-medium text-on-surface-variant uppercase tracking-wider mr-1">Filter:</span>
                <template x-if="search">
                    <span class="inline-flex items-center gap-1 text-xs bg-surface-container-high text-on-surface px-2.5 py-1 rounded-full border border-outline-variant">
                        <span x-text="'&quot;' + (search.length > 12 ? search.substring(0, 12) + '...' : search) + '&quot;'"></span>
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
                <button type="button" @click="resetFilters()" class="text-xs text-error hover:underline ml-1 font-semibold">
                    Reset
                </button>
            </div>

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
                <div class="bg-surface border border-outline-variant px-5 py-3 rounded-xl flex items-center gap-3">
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

    <!-- Floating Funnel Button (Mobile FAB) -->
    <div class="lg:hidden fixed bottom-6 right-5 z-30">
        <button type="button" 
                @click="mobileFilterOpen = true" 
                class="relative flex items-center justify-center w-12 h-12 rounded-full bg-primary text-white active:scale-95 hover:bg-primary/90 transition-all"
                title="Buka Filter Portofolio"
                aria-label="Filter Portofolio">
            <span class="material-symbols-outlined text-[24px]">filter_alt</span>
            <template x-if="(search ? 1 : 0) + (category ? 1 : 0) + (type ? 1 : 0) > 0">
                <span class="absolute top-0 right-0 w-3.5 h-3.5 bg-amber-400 border-2 border-surface rounded-full"></span>
            </template>
        </button>
    </div>

    <!-- Mobile Filter Bottom Sheet / Modal -->
    <div x-show="mobileFilterOpen" 
         x-cloak
         class="fixed inset-0 z-50 lg:hidden"
         role="dialog" 
         aria-modal="true">
        <!-- Backdrop -->
        <div x-show="mobileFilterOpen"
             x-transition:enter="transition-opacity ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="mobileFilterOpen = false" 
             class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>

        <!-- Drawer Panel -->
        <div class="fixed inset-x-0 bottom-0 max-h-[85vh] flex flex-col bg-surface rounded-t-3xl border-t border-outline-variant overflow-hidden"
             x-show="mobileFilterOpen"
             x-transition:enter="transition ease-out duration-250 transform"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full">
             
             <!-- Handle bar -->
             <div class="pt-3 pb-1 flex justify-center">
                 <div class="w-10 h-1 rounded-full bg-outline-variant/80"></div>
             </div>

             <!-- Drawer Header -->
             <div class="px-5 py-3 border-b border-outline-variant/60 flex items-center justify-between">
                 <div class="flex items-center gap-2">
                     <span class="material-symbols-outlined text-primary text-xl">filter_alt</span>
                     <h3 class="font-bold text-base text-on-background dark:text-white">Filter & Pencarian</h3>
                 </div>
                 <div class="flex items-center gap-2">
                     <button type="button" 
                             x-show="search || category || type" 
                             x-cloak
                             @click="resetFilters()" 
                             class="text-xs font-semibold text-error hover:underline flex items-center gap-0.5">
                         <span class="material-symbols-outlined text-sm">restart_alt</span> Reset
                     </button>
                     <button type="button" 
                             @click="mobileFilterOpen = false" 
                             class="w-8 h-8 rounded-full flex items-center justify-center text-on-surface-variant hover:bg-surface-container-high transition-colors"
                             title="Tutup">
                         <span class="material-symbols-outlined text-lg">close</span>
                     </button>
                 </div>
             </div>

             <!-- Drawer Body (Scrollable) -->
             <div class="p-5 overflow-y-auto space-y-5 flex-1">
                 <!-- Pencarian Project / Client -->
                 <div>
                     <label for="mobile-project-search" class="block text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-2">
                         Pencarian
                     </label>
                     <div class="relative flex items-center">
                         <input type="text" 
                                id="mobile-project-search" 
                                x-model="search"
                                @input="onSearchInput()" 
                                placeholder="Cari project atau client..." 
                                class="w-full pl-9 pr-8 py-2.5 text-sm bg-surface-container-lowest text-on-surface rounded-xl border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                         <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-on-surface-variant">
                             <span class="material-symbols-outlined text-lg leading-none">search</span>
                         </div>
                         <button type="button"
                                 x-show="search" 
                                 x-cloak
                                 @click="clearSearch()" 
                                 class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-on-surface-variant hover:text-error transition-colors"
                                 title="Hapus pencarian">
                             <span class="material-symbols-outlined text-sm leading-none">close</span>
                         </button>
                     </div>
                 </div>

                 <!-- Filter Kategori -->
                 <div class="pt-4 border-t border-outline-variant/60">
                     <h4 class="text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-2.5 flex items-center justify-between">
                         <span>Kategori Project</span>
                         <span class="text-[11px] font-normal lowercase opacity-75">({{ $categories->count() }})</span>
                     </h4>

                     <div class="grid grid-cols-1 gap-1">
                         <!-- Semua Kategori -->
                         <button type="button" 
                                 @click="setCategory(''); mobileFilterOpen = false" 
                                 class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm text-left transition-all duration-150"
                                 :class="!category ? 'bg-primary/10 text-primary font-semibold border-l-4 border-primary' : 'text-on-surface hover:bg-surface-container-high'">
                             <span class="flex items-center gap-2">
                                 <span class="material-symbols-outlined text-base" :class="!category ? 'text-primary' : 'text-on-surface-variant'">category</span>
                                 Semua Kategori
                             </span>
                         </button>

                         @foreach($categories as $cat)
                         <button type="button" 
                                 @click="setCategory('{{ $cat->slug }}'); mobileFilterOpen = false" 
                                 class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm text-left transition-all duration-150"
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
                 <div class="pt-4 border-t border-outline-variant/60">
                     <h4 class="text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-2.5 flex items-center justify-between">
                         <span>Tipe / Platform</span>
                         <span class="text-[11px] font-normal lowercase opacity-75">({{ $types->count() }})</span>
                     </h4>

                     <div class="grid grid-cols-1 gap-1">
                         <!-- Semua Tipe -->
                         <button type="button" 
                                 @click="setType(''); mobileFilterOpen = false" 
                                 class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm text-left transition-all duration-150"
                                 :class="!type ? 'bg-primary/10 text-primary font-semibold border-l-4 border-primary' : 'text-on-surface hover:bg-surface-container-high'">
                             <span class="flex items-center gap-2">
                                 <span class="material-symbols-outlined text-base" :class="!type ? 'text-primary' : 'text-on-surface-variant'">devices</span>
                                 Semua Platform
                             </span>
                         </button>

                         @foreach($types as $tp)
                         <button type="button" 
                                 @click="setType('{{ $tp->slug }}'); mobileFilterOpen = false" 
                                 class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm text-left transition-all duration-150"
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
             </div>

             <!-- Drawer Footer -->
             <div class="p-4 border-t border-outline-variant/60 bg-surface-container-low flex items-center gap-3">
                 <button type="button" 
                         @click="mobileFilterOpen = false" 
                         class="w-full py-3 px-4 rounded-xl bg-primary hover:bg-primary/90 text-on-primary font-bold text-sm shadow-sm active:scale-[0.99] transition-all text-center">
                     Tutup & Lihat Hasil
                 </button>
             </div>
        </div>
    </div>
</section>
@endsection

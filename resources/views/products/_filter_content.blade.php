{{-- ── FILTER CONTENT PARTIAL (REUSABLE FOR DESKTOP SIDEBAR & MOBILE DRAWER) ── --}}
<div class="space-y-4">
    {{-- Header Filter --}}
    <div class="flex items-center justify-between pb-3 mb-2 border-b border-gray-100 dark:border-gray-700">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-xl">tune</span>
            <h2 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">Filter & Pencarian</h2>
        </div>
        <button type="button" 
                x-show="search || category || type || store || sort !== 'latest'" 
                x-cloak
                @click="resetFilters()" 
                class="text-xs font-semibold text-rose-500 hover:underline flex items-center gap-0.5 transition-opacity">
            <span class="material-symbols-outlined text-sm">restart_alt</span> Reset
        </button>
    </div>

    {{-- Form Filter & Pencarian --}}
    <form @submit.prevent="fetchProducts(true)" class="space-y-4">
        <div>
            <label for="product-search-{{ $suffix ?? 'desktop' }}" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
                Pencarian Produk
            </label>
            <div class="relative flex items-center">
                <input type="text" 
                       id="product-search-{{ $suffix ?? 'desktop' }}" 
                       x-model="search"
                       @input="onSearchInput()" 
                       placeholder="Cari nama produk, toko..." 
                       class="w-full pl-9 pr-8 py-2 text-xs md:text-sm bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded-lg border border-gray-200 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-gray-400">
                    <span class="material-symbols-outlined text-base leading-none">
                        search
                    </span>
                </div>
                <button type="button"
                        x-show="search" 
                        x-cloak
                        @click="clearSearch()" 
                        class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-gray-400 hover:text-rose-500 transition-colors"
                        title="Hapus pencarian">
                    <span class="material-symbols-outlined text-sm leading-none">close</span>
                </button>
            </div>
        </div>

        {{-- Sort Dropdown --}}
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
                Urutkan Berdasarkan
            </label>
            <select x-model="sort" @change="fetchProducts(true)" class="w-full px-3 py-2 text-xs md:text-sm bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded-lg border border-gray-200 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                <option value="latest">Terbaru</option>
                <option value="popular">Paling Banyak Dilihat</option>
                <option value="best_seller">Terlaris</option>
                <option value="price_low">Harga Terendah</option>
                <option value="price_high">Harga Tertinggi</option>
            </select>
        </div>
    </form>

    {{-- Filter Kategori --}}
    <div class="pt-3 border-t border-gray-100 dark:border-gray-700">
        <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2.5 flex items-center justify-between">
            <span>Kategori Produk</span>
            <span class="text-[11px] font-normal lowercase opacity-75">({{ $categories->count() }})</span>
        </h3>

        <div class="space-y-1 max-h-56 overflow-y-auto pr-1">
            <button type="button" 
                    @click="setCategory('')" 
                    class="w-full flex items-center justify-between px-3 py-1.5 rounded-lg text-xs md:text-sm text-left transition-all"
                    :class="!category ? 'bg-primary/10 text-primary font-bold border-l-4 border-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'">
                <span class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm" :class="!category ? 'text-primary' : 'text-gray-400'">apps</span>
                    Semua Kategori
                </span>
            </button>

            @foreach($categories as $cat)
            <button type="button" 
                    @click="setCategory('{{ $cat->id }}')" 
                    class="w-full flex items-center justify-between px-3 py-1.5 rounded-lg text-xs md:text-sm text-left transition-all"
                    :class="category == '{{ $cat->id }}' ? 'bg-primary/10 text-primary font-bold border-l-4 border-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'">
                <div class="flex items-center gap-1.5 truncate pr-2">
                    <span class="truncate">{{ $cat->name }}</span>
                    @if($cat->store)
                        <span class="text-[9px] px-1 py-0.2 rounded bg-amber-500/10 text-amber-600 dark:text-amber-400 font-normal shrink-0" title="Kategori dari toko {{ $cat->store->name }}">
                            {{ Str::limit($cat->store->name, 10) }}
                        </span>
                    @endif
                </div>
                @if(isset($cat->products_count))
                <span class="text-[10px] px-1.5 py-0.5 rounded-full transition-colors shrink-0"
                      :class="category == '{{ $cat->id }}' ? 'bg-primary text-white font-bold' : 'bg-gray-100 dark:bg-gray-700 text-gray-500'">
                    {{ $cat->products_count }}
                </span>
                @endif
            </button>
            @endforeach
        </div>
    </div>

    {{-- Filter Tipe / Platform --}}
    @if(isset($types) && $types->count() > 0)
    <div class="pt-3 border-t border-gray-100 dark:border-gray-700">
        <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2.5 flex items-center justify-between">
            <span>Tipe / Platform</span>
            <span class="text-[11px] font-normal lowercase opacity-75">({{ $types->count() }})</span>
        </h3>

        <div class="space-y-1 max-h-56 overflow-y-auto pr-1">
            <button type="button" 
                    @click="setType('')" 
                    class="w-full flex items-center justify-between px-3 py-1.5 rounded-lg text-xs md:text-sm text-left transition-all"
                    :class="!type ? 'bg-primary/10 text-primary font-bold border-l-4 border-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'">
                <span class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm" :class="!type ? 'text-primary' : 'text-gray-400'">devices</span>
                    Semua Tipe
                </span>
            </button>

            @foreach($types as $tp)
            <button type="button" 
                    @click="setType('{{ $tp->id }}')" 
                    class="w-full flex items-center justify-between px-3 py-1.5 rounded-lg text-xs md:text-sm text-left transition-all"
                    :class="type == '{{ $tp->id }}' ? 'bg-primary/10 text-primary font-bold border-l-4 border-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'">
                <div class="flex items-center gap-1.5 truncate pr-2">
                    <span class="truncate">{{ $tp->name }}</span>
                    @if($tp->store)
                        <span class="text-[9px] px-1 py-0.2 rounded bg-sky-500/10 text-sky-600 dark:text-sky-400 font-normal shrink-0" title="Tipe dari toko {{ $tp->store->name }}">
                            {{ Str::limit($tp->store->name, 10) }}
                        </span>
                    @endif
                </div>
                @if(isset($tp->products_count))
                <span class="text-[10px] px-1.5 py-0.5 rounded-full transition-colors shrink-0"
                      :class="type == '{{ $tp->id }}' ? 'bg-primary text-white font-bold' : 'bg-gray-100 dark:bg-gray-700 text-gray-500'">
                    {{ $tp->products_count }}
                </span>
                @endif
            </button>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Filter Toko (Store) --}}
    @if(isset($stores) && $stores->count() > 0)
    <div class="pt-3 border-t border-gray-100 dark:border-gray-700">
        <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2.5 flex items-center justify-between">
            <span>Toko / Mitra</span>
            <span class="text-[11px] font-normal lowercase opacity-75">({{ $stores->count() }})</span>
        </h3>

        <div class="space-y-1 max-h-44 overflow-y-auto pr-1">
            <button type="button" 
                    @click="setStore('')" 
                    class="w-full flex items-center justify-between px-3 py-1.5 rounded-lg text-xs md:text-sm text-left transition-all"
                    :class="!store ? 'bg-primary/10 text-primary font-bold border-l-4 border-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'">
                <span class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm" :class="!store ? 'text-primary' : 'text-gray-400'">storefront</span>
                    Semua Toko
                </span>
            </button>

            @foreach($stores as $st)
            <button type="button" 
                    @click="setStore('{{ $st->id }}')" 
                    class="w-full flex items-center justify-between px-3 py-1.5 rounded-lg text-xs md:text-sm text-left transition-all"
                    :class="store == '{{ $st->id }}' ? 'bg-primary/10 text-primary font-bold border-l-4 border-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'">
                <span class="truncate pr-2">{{ $st->name }}</span>
            </button>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Active Filter Tags --}}
    <div x-show="search || category || type || store" x-cloak class="pt-3 border-t border-gray-100 dark:border-gray-700">
        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-2">Filter Aktif:</span>
        <div class="flex flex-wrap gap-1.5">
            <template x-if="search">
                <span class="inline-flex items-center gap-1 text-[11px] bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-2.5 py-0.5 rounded-full border border-gray-300 dark:border-gray-600">
                    <span x-text="'&quot;' + (search.length > 15 ? search.substring(0, 15) + '...' : search) + '&quot;'"></span>
                    <button type="button" @click="clearSearch()" class="hover:text-rose-500">
                        <span class="material-symbols-outlined text-xs">close</span>
                    </button>
                </span>
            </template>

            <template x-if="category">
                <span class="inline-flex items-center gap-1 text-[11px] bg-primary/15 text-primary px-2.5 py-0.5 rounded-full font-semibold">
                    <span>Kategori Terpilih</span>
                    <button type="button" @click="setCategory('')" class="hover:text-rose-500">
                        <span class="material-symbols-outlined text-xs">close</span>
                    </button>
                </span>
            </template>

            <template x-if="type">
                <span class="inline-flex items-center gap-1 text-[11px] bg-sky-500/15 text-sky-600 dark:text-sky-400 px-2.5 py-0.5 rounded-full font-semibold">
                    <span>Tipe Terpilih</span>
                    <button type="button" @click="setType('')" class="hover:text-rose-500">
                        <span class="material-symbols-outlined text-xs">close</span>
                    </button>
                </span>
            </template>

            <template x-if="store">
                <span class="inline-flex items-center gap-1 text-[11px] bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 px-2.5 py-0.5 rounded-full font-semibold">
                    <span>Toko Terpilih</span>
                    <button type="button" @click="setStore('')" class="hover:text-rose-500">
                        <span class="material-symbols-outlined text-xs">close</span>
                    </button>
                </span>
            </template>
        </div>
    </div>
</div>

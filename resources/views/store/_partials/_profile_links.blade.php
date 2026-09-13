{{-- Partial: Profile Links & Portofolio Showcase (Responsif Mobile, iPad & Desktop ala Lynk.id) --}}
@if($profileLinks->isNotEmpty())
<div class="w-full">
    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2.5 sm:gap-4">
        @foreach($profileLinks as $link)
            @php
                $layout = $link['layout'] ?? 'list';
                $color = !empty($link['color']) ? $link['color'] : '#0284c7';
                $rawUrl = !empty($link['url']) ? $link['url'] : '#';
                $linkSlug = !empty($link['slug']) 
                    ? $link['slug'] 
                    : (!empty($link['id']) ? $link['id'] : (!empty($link['title']) ? \Illuminate\Support\Str::slug($link['title']) : 'item-' . ($loop->index + 1)));

                $detailUrl = route('store.link_detail', ['slug' => $store->slug, 'linkId' => $linkSlug]);
                
                // Masuk ke halaman detail dulu jika has_detail diaktifkan, atau punya deskripsi, atau berbentuk kartu/grid portofolio
                $hasDetail = !isset($link['has_detail']) 
                    ? (!empty($link['description']) || in_array($layout, ['grid', 'card']))
                    : (bool)$link['has_detail'];

                $url = $hasDetail ? $detailUrl : $rawUrl;
                $isExternal = !$hasDetail && (str_starts_with($url, 'http://') || str_starts_with($url, 'https://'));
                $actionLabel = $hasDetail ? 'Lihat Detail & Info' : 'Buka Tautan';
            @endphp

            {{-- 1. LAYOUT: GRID (2 Kolom - Kartu Portofolio / Produk) --}}
            @if($layout === 'grid')
                <a href="{{ $url }}"
                   @if($isExternal) target="_blank" rel="noopener noreferrer" @endif
                   class="col-span-1 group flex flex-col bg-white dark:bg-[#161b22] border border-slate-200/80 dark:border-[#30363d] rounded-2xl overflow-hidden shadow-xs hover:shadow-lg hover:-translate-y-1 active:scale-[0.98] transition-all duration-200 text-left">
                    <div class="w-full aspect-square bg-slate-100 dark:bg-slate-800 relative overflow-hidden flex items-center justify-center">
                        @if(!empty($link['image']))
                            <img src="{{ $link['image'] }}" alt="{{ $link['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-white" style="background: {{ $color }};">
                                <span class="material-symbols-outlined text-3xl sm:text-4xl opacity-90">{{ $link['icon'] ?? 'link' }}</span>
                            </div>
                        @endif
                        @if(!empty($link['badge']))
                            <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md sm:rounded-lg bg-black/60 backdrop-blur-md text-white text-[8px] sm:text-[9px] font-black uppercase tracking-wider shadow-xs border border-white/10">
                                {{ $link['badge'] }}
                            </span>
                        @endif
                    </div>
                    <div class="p-2.5 sm:p-3.5 flex flex-col flex-1 justify-between bg-white dark:bg-[#161b22]">
                        <div>
                            <h4 class="font-extrabold text-xs sm:text-sm text-slate-900 dark:text-white line-clamp-2 leading-tight group-hover:text-primary transition-colors">
                                {{ $link['title'] }}
                            </h4>
                            @if(!empty($link['subtitle']))
                                <p class="text-[10px] sm:text-xs font-bold mt-1 truncate" style="color: {{ $color }};">{{ $link['subtitle'] }}</p>
                            @endif
                            @if(!empty($link['description']))
                                <p class="text-[9px] sm:text-[11px] text-slate-500 dark:text-slate-400 mt-1 line-clamp-2 leading-tight whitespace-pre-line">{{ $link['description'] }}</p>
                            @endif
                        </div>
                        <div class="mt-2.5 pt-2 sm:mt-3 sm:pt-2.5 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between text-[9px] sm:text-[11px] font-bold text-slate-400 dark:text-slate-500">
                            <span>{{ $actionLabel }}</span>
                            <span class="material-symbols-outlined text-[13px] sm:text-[15px] group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                        </div>
                    </div>
                </a>

            {{-- 2. LAYOUT: CARD (Gambar Besar 16:9 - Portofolio Unggulan / Paket) --}}
            @elseif($layout === 'card')
                <a href="{{ $url }}"
                   @if($isExternal) target="_blank" rel="noopener noreferrer" @endif
                   class="col-span-2 sm:col-span-2 md:col-span-3 lg:col-span-4 group flex flex-col bg-white dark:bg-[#161b22] border border-slate-200/80 dark:border-[#30363d] rounded-2xl overflow-hidden shadow-xs hover:shadow-xl hover:-translate-y-1 active:scale-[0.99] transition-all duration-200 text-left">
                    <div class="w-full aspect-[16/9] bg-slate-100 dark:bg-slate-800 relative overflow-hidden flex items-center justify-center">
                        @if(!empty($link['image']))
                            <img src="{{ $link['image'] }}" alt="{{ $link['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-white" style="background: {{ $color }};">
                                <span class="material-symbols-outlined text-4xl sm:text-5xl opacity-90">{{ $link['icon'] ?? 'link' }}</span>
                            </div>
                        @endif
                        @if(!empty($link['badge']))
                            <span class="absolute top-2.5 left-2.5 sm:top-3 sm:left-3 px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-lg sm:rounded-xl bg-black/60 backdrop-blur-md text-white text-[9px] sm:text-[10px] font-black uppercase tracking-wider shadow-xs border border-white/10">
                                {{ $link['badge'] }}
                            </span>
                        @endif
                    </div>
                    <div class="p-3.5 sm:p-4 bg-white dark:bg-[#161b22] flex-1 flex flex-col justify-between">
                        <div>
                            <h4 class="font-extrabold text-xs sm:text-base text-slate-900 dark:text-white leading-snug group-hover:text-primary transition-colors">
                                {{ $link['title'] }}
                            </h4>
                            @if(!empty($link['subtitle']))
                                <p class="text-[11px] sm:text-sm font-bold mt-1 line-clamp-1" style="color: {{ $color }};">{{ $link['subtitle'] }}</p>
                            @endif
                            @if(!empty($link['description']))
                                <div class="text-[10px] sm:text-xs text-slate-600 dark:text-slate-300 mt-1.5 sm:mt-2 leading-relaxed line-clamp-3 whitespace-pre-line">{{ $link['description'] }}</div>
                            @endif
                        </div>
                        <div class="mt-3 sm:mt-3.5 w-full py-2 sm:py-2.5 px-3.5 sm:px-4 rounded-xl text-white font-bold text-xs text-center flex items-center justify-center gap-1.5 shadow-sm group-hover:opacity-95 transition-opacity"
                             style="background: {{ $color }};">
                            <span>{{ $actionLabel }}</span>
                            <span class="material-symbols-outlined text-[15px] sm:text-[16px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </div>
                    </div>
                </a>

            {{-- 3. LAYOUT: LIST (Default 1 Baris Penuh) --}}
            @else
                <a href="{{ $url }}"
                   @if($isExternal) target="_blank" rel="noopener noreferrer" @endif
                   class="col-span-2 sm:col-span-2 md:col-span-3 lg:col-span-4 group flex items-center gap-2.5 sm:gap-3 w-full p-2.5 sm:p-3.5 rounded-2xl text-white shadow-sm hover:shadow-lg hover:-translate-y-0.5 active:scale-[0.99] transition-all duration-200 relative overflow-hidden"
                   style="background: {{ $color }};">
                    @if(!empty($link['badge']))
                        <span class="absolute top-1.5 right-2 px-2 py-0.5 rounded-full bg-white/25 backdrop-blur-md text-[8px] sm:text-[9px] font-black uppercase tracking-wider text-white">
                            {{ $link['badge'] }}
                        </span>
                    @endif
                    @if(!empty($link['image']))
                        <img src="{{ $link['image'] }}" alt="{{ $link['title'] }}" class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl object-cover shrink-0 shadow-xs border border-white/20">
                    @else
                        <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[20px] sm:text-[22px] opacity-95">{{ $link['icon'] ?? 'link' }}</span>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0 pr-1 sm:pr-2 text-left">
                        <h4 class="font-extrabold text-xs sm:text-sm text-white leading-snug line-clamp-1 group-hover:underline">
                            {{ $link['title'] }}
                        </h4>
                        @if(!empty($link['subtitle']))
                            <p class="text-[10px] sm:text-[11px] text-white/90 line-clamp-1 mt-0.5 font-medium">{{ $link['subtitle'] }}</p>
                        @endif
                        @if(!empty($link['description']))
                            <p class="text-[9px] sm:text-[10px] text-white/75 line-clamp-1 mt-0.5">{{ $link['description'] }}</p>
                        @endif
                    </div>
                    <span class="material-symbols-outlined text-[16px] sm:text-[18px] opacity-70 shrink-0 mr-0.5 sm:mr-1 group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </a>
            @endif
        @endforeach
    </div>
</div>
@endif

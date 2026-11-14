{{-- Tautan brand: warna tiap tautan hanya dipakai pada ikonnya supaya halaman tetap satu warna brand. --}}
<div class="bp-links-wrap">
    <p class="bp-links-label">Tautan</p>
    <div class="bp-links">
        @foreach($profileLinks as $link)
            @php
                $rawUrl = !empty($link['url']) ? $link['url'] : '#';
                $linkSlug = $link['slug'] ?? ($link['id'] ?? (!empty($link['title']) ? \Illuminate\Support\Str::slug($link['title']) : 'item-' . ($loop->index + 1)));
                // Langsung buka URL tujuan ketika diklik (tanpa perantara)
                $linkUrl = (!empty($rawUrl) && $rawUrl !== '#')
                    ? $rawUrl
                    : route('store.link_detail', ['slug' => $store->slug, 'linkId' => $linkSlug]);
                $isExternal = str_starts_with($linkUrl, 'http');
            @endphp
            <a href="{{ $linkUrl }}" class="bp-link" @if($isExternal) target="_blank" rel="noopener noreferrer" @endif>
                <span class="bp-link-tile" style="background: {{ $link['color'] ?? 'var(--brand)' }};">
                    @if(!empty($link['image']))
                        <img src="{{ $link['image'] }}" alt="" loading="lazy">
                    @else
                        <span class="material-symbols-outlined">{{ $link['icon'] ?? 'link' }}</span>
                    @endif
                </span>
                <span class="bp-link-text">
                    <span class="bp-link-title">{{ $link['title'] ?? 'Tautan' }}</span>
                    @if(!empty($link['subtitle']))
                        <span class="bp-link-sub">{{ $link['subtitle'] }}</span>
                    @endif
                </span>
                <i class="ph ph-caret-right bp-link-go"></i>
            </a>
        @endforeach
    </div>
</div>

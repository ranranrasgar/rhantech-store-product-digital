{{-- Tautan brand: warna tiap tautan hanya dipakai pada ikonnya supaya halaman tetap satu warna brand. --}}
<div class="bp-links-wrap">
    <p class="bp-links-label">Tautan</p>
    <div class="bp-links">
        @foreach($profileLinks as $link)
            @php
                $linkLayout = $link['layout'] ?? 'list';
                $linkSlug = $link['slug'] ?? ($link['id'] ?? (!empty($link['title']) ? \Illuminate\Support\Str::slug($link['title']) : 'item-' . ($loop->index + 1)));
                $hasDetail = isset($link['has_detail'])
                    ? (bool) $link['has_detail']
                    : (!empty($link['description']) || in_array($linkLayout, ['grid', 'card']));
                $linkUrl = $hasDetail
                    ? route('store.link_detail', ['slug' => $store->slug, 'linkId' => $linkSlug])
                    : ($link['url'] ?? '#');
                $isExternal = !$hasDetail && str_starts_with($linkUrl, 'http');
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

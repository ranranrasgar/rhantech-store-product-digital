<?php
$f = 'resources/views/products/index.blade.php';
$c = file_get_contents($f);

$oldBannerRow = <<<HTML
    {{-- ── BANNER ROW ── --}}
    <div class="flex gap-3 mb-6 h-[140px] md:h-[200px]">
        <div class="flex-[2] rounded-2xl overflow-hidden shadow-md relative group cursor-pointer">
            <img src="{{ asset('images/main_banner.png') }}" alt="Banner" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            <div class="absolute inset-0 bg-gradient-to-r from-black/30 to-transparent"></div>
        </div>
        <div class="flex-1 flex flex-col gap-3">
            <div class="flex-1 rounded-2xl overflow-hidden shadow-md cursor-pointer group">
                <img src="{{ asset('images/side_banner_1.png') }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            </div>
            <div class="flex-1 rounded-2xl overflow-hidden shadow-md cursor-pointer group">
                <img src="{{ asset('images/side_banner_2.png') }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            </div>
        </div>
    </div>
HTML;

$newBannerRow = <<<HTML
    {{-- ── BANNER ROW ── --}}
    <div class="flex gap-3 mb-6 h-[200px] md:h-[300px]">
        @if(isset(\$banners) && \$banners->has('main'))
            @php \$mainBanner = \$banners->get('main'); @endphp
            <a href="{{ \$mainBanner->link ?? '#' }}" class="flex-[2] overflow-hidden shadow-md relative group cursor-pointer block">
                <img src="{{ asset('storage/' . \$mainBanner->image_path) }}" alt="{{ \$mainBanner->title ?? 'Banner Utama' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-r from-black/30 to-transparent"></div>
            </a>
        @else
            <div class="flex-[2] overflow-hidden shadow-md relative group cursor-pointer bg-gray-200 dark:bg-gray-800 flex items-center justify-center">
                <span class="text-gray-400">Banner Utama (Kiri)</span>
            </div>
        @endif

        <div class="flex-1 flex flex-col gap-3">
            @if(isset(\$banners) && \$banners->has('side_1'))
                @php \$side1 = \$banners->get('side_1'); @endphp
                <a href="{{ \$side1->link ?? '#' }}" class="flex-1 overflow-hidden shadow-md cursor-pointer group block">
                    <img src="{{ asset('storage/' . \$side1->image_path) }}" alt="{{ \$side1->title ?? 'Banner Samping Atas' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </a>
            @else
                <div class="flex-1 overflow-hidden shadow-md cursor-pointer group bg-gray-200 dark:bg-gray-800 flex items-center justify-center">
                    <span class="text-gray-400 text-xs">Samping Atas</span>
                </div>
            @endif

            @if(isset(\$banners) && \$banners->has('side_2'))
                @php \$side2 = \$banners->get('side_2'); @endphp
                <a href="{{ \$side2->link ?? '#' }}" class="flex-1 overflow-hidden shadow-md cursor-pointer group block">
                    <img src="{{ asset('storage/' . \$side2->image_path) }}" alt="{{ \$side2->title ?? 'Banner Samping Bawah' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </a>
            @else
                <div class="flex-1 overflow-hidden shadow-md cursor-pointer group bg-gray-200 dark:bg-gray-800 flex items-center justify-center">
                    <span class="text-gray-400 text-xs">Samping Bawah</span>
                </div>
            @endif
        </div>
    </div>
HTML;

$c = str_replace($oldBannerRow, $newBannerRow, $c);
file_put_contents($f, $c);
echo "Successfully updated frontend banner HTML.\n";

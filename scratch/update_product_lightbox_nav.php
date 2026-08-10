<?php
$f = 'resources/views/products/show.blade.php';
$c = file_get_contents($f);

// 1. Add PHP mapping for URLs at the top of main and change x-data
$oldMain = '<main x-data="{ showLightbox: false, lightboxSrc: \'\' }" class="pt-[60px] md:pt-[100px] pb-12 min-h-screen font-sans">';
$newMain = <<<HTML
@php
    \$imageUrls = \$product->images->map(function(\$img) {
        return asset('storage/' . \$img->image_path);
    })->values()->toJson();
@endphp
<main x-data="{ 
        showLightbox: false, 
        images: {{ \$imageUrls }}, 
        currentIndex: 0,
        openLightbox(src) {
            let idx = this.images.indexOf(src);
            if(idx === -1) idx = 0;
            this.currentIndex = idx;
            this.showLightbox = true;
        },
        next() {
            if(this.images.length > 0) {
                this.currentIndex = (this.currentIndex + 1) % this.images.length;
            }
        },
        prev() {
            if(this.images.length > 0) {
                this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
            }
        }
    }" 
    class="pt-[60px] md:pt-[100px] pb-12 min-h-screen font-sans">
HTML;
$c = str_replace($oldMain, $newMain, $c);

// 2. Update Image Container click
$oldClick = '<div @click="lightboxSrc = document.getElementById(\'mainImage\').src; showLightbox = true" class="aspect-square w-full relative overflow-hidden mb-2 group cursor-zoom-in" id="image-container" onmousemove="zoomImage(event)" onmouseleave="resetZoomImage()">';
$newClick = '<div @click="openLightbox(document.getElementById(\'mainImage\').src)" class="aspect-square w-full relative overflow-hidden mb-2 group cursor-zoom-in" id="image-container" onmousemove="zoomImage(event)" onmouseleave="resetZoomImage()">';
$c = str_replace($oldClick, $newClick, $c);

// 3. Update Lightbox Modal HTML
$oldModalStart = '    <!-- Lightbox Modal -->';
$oldModalEnd = '    </div>'."\n".'</main>';
// Find the exact old modal string
$startPos = strpos($c, $oldModalStart);
$endPos = strpos($c, '</main>', $startPos);
if ($startPos !== false && $endPos !== false) {
    $oldModalHTML = substr($c, $startPos, $endPos - $startPos);
    
    $newModalHTML = <<<HTML
    <!-- Lightbox Modal -->
    <div x-show="showLightbox" x-transition.opacity.duration.300ms style="display: none;"
         class="fixed inset-0 z-[100] bg-black/95 flex items-center justify-center p-4 md:p-8 backdrop-blur-sm"
         @click.self="showLightbox = false"
         @keydown.window.escape="showLightbox = false"
         @keydown.window.arrow-right="if(showLightbox) next()"
         @keydown.window.arrow-left="if(showLightbox) prev()">
        
        <button @click="showLightbox = false" class="absolute top-4 right-4 md:top-6 md:right-6 text-white/70 hover:text-white transition-colors bg-white/10 hover:bg-white/20 rounded-full w-10 h-10 flex items-center justify-center z-10 backdrop-blur">
            <span class="material-symbols-outlined text-2xl">close</span>
        </button>

        <button x-show="images.length > 1" @click.stop="prev()" class="absolute left-2 md:left-6 top-1/2 -translate-y-1/2 text-white/70 hover:text-white bg-white/10 hover:bg-white/20 rounded-full w-10 h-10 md:w-14 md:h-14 flex items-center justify-center z-10 backdrop-blur transition-colors">
            <span class="material-symbols-outlined text-2xl md:text-4xl">chevron_left</span>
        </button>
        
        <button x-show="images.length > 1" @click.stop="next()" class="absolute right-2 md:right-6 top-1/2 -translate-y-1/2 text-white/70 hover:text-white bg-white/10 hover:bg-white/20 rounded-full w-10 h-10 md:w-14 md:h-14 flex items-center justify-center z-10 backdrop-blur transition-colors">
            <span class="material-symbols-outlined text-2xl md:text-4xl">chevron_right</span>
        </button>

        <img :src="images[currentIndex]" class="max-w-full max-h-full object-contain shadow-2xl transition-all duration-300 select-none" alt="Fullscreen" @click.self="showLightbox = false" x-transition>

        <div x-show="images.length > 1" class="absolute bottom-6 md:bottom-8 left-1/2 -translate-x-1/2 text-white bg-white/10 backdrop-blur px-5 py-2 rounded-full text-sm font-bold tracking-wider z-10">
            <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
        </div>
    </div>
\n
HTML;

    $c = str_replace($oldModalHTML, $newModalHTML, $c);
}

file_put_contents($f, $c);
echo "Successfully updated lightbox with navigation.\n";

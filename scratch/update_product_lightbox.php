<?php
$f = 'resources/views/products/show.blade.php';
$c = file_get_contents($f);

// 1. Add Alpine state to main
$c = str_replace(
    '<main class="pt-[60px] md:pt-[100px] pb-12 min-h-screen font-sans">',
    '<main x-data="{ showLightbox: false, lightboxSrc: \'\' }" class="pt-[60px] md:pt-[100px] pb-12 min-h-screen font-sans">',
    $c
);

// 2. Update Image Container
$c = str_replace(
    '<div class="aspect-square w-full relative overflow-hidden mb-2">',
    '<div @click="lightboxSrc = document.getElementById(\'mainImage\').src; showLightbox = true" class="aspect-square w-full relative overflow-hidden mb-2 group cursor-zoom-in" id="image-container" onmousemove="zoomImage(event)" onmouseleave="resetZoomImage()">',
    $c
);

// 3. Update main img class
$c = str_replace(
    '<img id="mainImage" src="{{ asset(\'storage/\' . $mainImg->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">',
    '<img id="mainImage" src="{{ asset(\'storage/\' . $mainImg->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover origin-center transition-transform duration-75 ease-out group-hover:scale-[2]">',
    $c
);

// 4. Add Lightbox Modal HTML
$lightboxHtml = <<<HTML
    <!-- Lightbox Modal -->
    <div x-show="showLightbox" x-transition.opacity.duration.300ms style="display: none;"
         class="fixed inset-0 z-[100] bg-black/90 flex items-center justify-center p-4 md:p-8 backdrop-blur-sm"
         @click.self="showLightbox = false"
         @keydown.window.escape="showLightbox = false">
        
        <button @click="showLightbox = false" class="absolute top-4 right-4 md:top-6 md:right-6 text-white/70 hover:text-white transition-colors bg-black/50 hover:bg-black/80 rounded-full w-10 h-10 flex items-center justify-center">
            <span class="material-symbols-outlined text-2xl">close</span>
        </button>

        <img :src="lightboxSrc" class="max-w-full max-h-full object-contain rounded-md shadow-2xl transition-transform duration-300" alt="Fullscreen" @click.self="showLightbox = false">
    </div>
HTML;
$c = str_replace('</main>', $lightboxHtml . "\n</main>", $c);

// 5. Add JS Script
$jsHtml = <<<HTML
<script>
function zoomImage(e) {
    const img = document.getElementById('mainImage');
    if (!img) return;
    const container = document.getElementById('image-container');
    const rect = container.getBoundingClientRect();
    const x = ((e.clientX - rect.left) / rect.width) * 100;
    const y = ((e.clientY - rect.top) / rect.height) * 100;
    img.style.transformOrigin = `\${x}% \${y}%`;
}
function resetZoomImage() {
    const img = document.getElementById('mainImage');
    if (!img) return;
    img.style.transformOrigin = 'center center';
}
</script>
HTML;
$c = str_replace('@endsection', $jsHtml . "\n\n@endsection", $c);

file_put_contents($f, $c);
echo "Successfully updated.\n";

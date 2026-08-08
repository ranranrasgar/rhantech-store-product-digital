@extends('layouts.public')
@section('title', $product->name . ' - Store')
@section('content')
<main class="pt-24 pb-2xl min-h-screen bg-surface dark:bg-surface-container-lowest">
    <div class="max-w-container-max mx-auto px-lg">
        
        <div class="mb-lg">
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-on-surface-variant hover:text-[#06B6D4] transition-colors font-label-md">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                Back to Store
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-xl">
            <!-- Image Gallery -->
            <div class="flex flex-col gap-4">
                <div class="aspect-video w-full bg-surface-container-high rounded-2xl overflow-hidden border border-outline-variant">
                    @if($product->images->count() > 0)
                        @php $mainImg = $product->images->where('is_main', true)->first() ?? $product->images->first(); @endphp
                        <img id="mainImage" src="{{ asset('storage/' . $mainImg->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-5xl">code</span>
                        </div>
                    @endif
                </div>
                
                @if($product->images->count() > 1)
                <div class="grid grid-cols-5 gap-2">
                    @foreach($product->images as $img)
                    <button onclick="document.getElementById('mainImage').src = '{{ asset('storage/' . $img->image_path) }}'" class="aspect-video rounded-lg overflow-hidden border border-outline-variant focus:ring-2 focus:ring-[#06B6D4]">
                        <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover hover:opacity-75 transition-opacity">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Product Details -->
            <div class="flex flex-col">
                <h1 class="font-headline-lg font-black text-on-surface mb-2">{{ $product->name }}</h1>
                <div class="flex gap-2 mb-4">
                    @if($product->category)
                    <span class="bg-secondary-container text-on-secondary-container text-xs font-bold px-3 py-1 rounded-full uppercase">{{ $product->category->name }}</span>
                    @endif
                    @if($product->type)
                    <span class="bg-tertiary-container text-on-tertiary-container text-xs font-bold px-3 py-1 rounded-full uppercase">{{ $product->type->name }}</span>
                    @endif
                </div>
                
                <div class="flex items-end gap-4 mb-6 pb-6 border-b border-outline-variant">
                    @if($product->discount_price)
                        <div class="font-headline-md font-bold text-[#06B6D4]">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</div>
                        <div class="text-on-surface-variant line-through text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        <div class="bg-error/10 text-error font-bold text-xs px-2 py-1 rounded">SALE</div>
                    @else
                        <div class="font-headline-md font-bold text-on-surface">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    @endif
                </div>

                <div class="prose dark:prose-invert max-w-none text-on-surface-variant mb-8 font-body-md whitespace-pre-line">
                    {{ $product->description }}
                </div>

                <div class="mt-auto">
                    <a href="{{ route('checkout.index', $product->slug) }}" class="w-full block text-center bg-[#06B6D4] text-white font-bold font-label-lg py-4 rounded-xl hover:bg-[#0891B2] transition shadow-md flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">shopping_cart_checkout</span>
                        Buy Now
                    </a>
                    
                    @if($product->demo_url)
                    <a href="{{ $product->demo_url }}" target="_blank" rel="noopener noreferrer" class="mt-3 w-full block text-center bg-surface-container-high text-on-surface font-bold font-label-lg py-4 rounded-xl hover:bg-surface-container-highest transition shadow-sm border border-outline-variant flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">open_in_new</span>
                        Live Demo
                    </a>
                    @endif

                    <p class="text-center text-xs text-on-surface-variant mt-4 flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-[1rem]">lock</span>
                        Secure payment via Midtrans. Instant delivery via Email.
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

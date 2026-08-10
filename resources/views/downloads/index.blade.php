@extends('layouts.public')
@section('title', 'Your Downloads - ' . $order->invoice_number)
@section('content')
<main class="pt-24 pb-2xl min-h-screen bg-surface dark:bg-surface-container-lowest">
    <div class="max-w-4xl mx-auto px-lg">
        
        <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-lg mb-lg shadow-sm">
            <h1 class="font-headline-md font-black text-on-surface mb-2">Pusat Unduhan</h1>
            <p class="text-on-surface-variant font-body-sm mb-6">Faktur: <strong>{{ $order->invoice_number }}</strong></p>

            <div class="space-y-4">
                @foreach($order->orderItems as $item)
                    <div class="border border-outline-variant rounded-lg p-4 flex flex-col md:flex-row gap-4 items-center justify-between">
                        <div class="flex items-center gap-4">
                            @if($item->product->images->count() > 0)
                                <img src="{{ asset('storage/' . $item->product->images->where('is_main', true)->first()->image_path ?? $item->product->images->first()->image_path) }}" class="w-16 h-16 object-cover rounded">
                            @else
                                <div class="w-16 h-16 rounded bg-surface-container-high flex items-center justify-center">
                                    <span class="material-symbols-outlined">description</span>
                                </div>
                            @endif
                            <div>
                                <h3 class="font-bold text-on-surface text-lg">{{ $item->product->name }}</h3>
                                <p class="text-xs text-on-surface-variant">{{ $item->product->store->name ?? 'Admin Store' }}</p>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            @if($item->product->file_path)
                                <a href="{{ route('products.download.file', ['token' => $order->download_token, 'item' => $item->id]) }}" class="bg-primary text-white px-4 py-2 rounded text-sm font-bold flex items-center gap-1 hover:brightness-110">
                                    <span class="material-symbols-outlined text-[18px]">download</span> Unduh File
                                </a>
                            @endif
                            
                            @if(!empty($item->product->download_links))
                                @foreach($item->product->download_links as $link)
                                    <a href="{{ $link['url'] }}" target="_blank" class="bg-surface-variant border border-primary text-primary px-4 py-2 rounded text-sm font-bold flex items-center gap-1 hover:bg-primary/10">
                                        <span class="material-symbols-outlined text-[18px]">link</span> {{ $link['name'] }}
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</main>
@endsection

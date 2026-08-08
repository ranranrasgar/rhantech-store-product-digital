@extends('layouts.public')
@section('title', 'Checkout - ' . $product->name)
@section('content')
<main class="pt-24 pb-2xl min-h-screen bg-surface dark:bg-surface-container-lowest">
    <div class="max-w-4xl mx-auto px-lg">
        
        <div class="mb-lg">
            <h1 class="font-headline-lg font-black text-on-surface">Secure Checkout</h1>
            <p class="text-on-surface-variant font-body-md">Complete your details to purchase this digital product.</p>
        </div>

        @if(session('error'))
            <div class="bg-error/10 text-error p-4 rounded-xl mb-md font-bold">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-xl">
            <div class="md:col-span-2">
                <form action="{{ route('checkout.process', $product->slug) }}" method="POST" class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-lg shadow-sm flex flex-col gap-lg">
                    @csrf
                    
                    <h2 class="font-headline-sm font-bold text-on-surface border-b border-outline-variant pb-2">Customer Details</h2>
                    
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Full Name *</label>
                        <input type="text" name="customer_name" required value="{{ old('customer_name') }}" class="w-full pl-4 pr-4 py-3 bg-surface-container-high border border-outline-variant rounded-lg font-body-md focus:border-[#06B6D4] focus:ring-1 focus:ring-[#06B6D4]/50">
                        @error('customer_name')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Email Address *</label>
                        <p class="text-xs text-on-surface-variant mb-2">The download link will be sent to this email automatically.</p>
                        <input type="email" name="customer_email" required value="{{ old('customer_email') }}" class="w-full pl-4 pr-4 py-3 bg-surface-container-high border border-outline-variant rounded-lg font-body-md focus:border-[#06B6D4] focus:ring-1 focus:ring-[#06B6D4]/50">
                        @error('customer_email')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">WhatsApp / Phone Number *</label>
                        <input type="text" name="customer_phone" required value="{{ old('customer_phone') }}" class="w-full pl-4 pr-4 py-3 bg-surface-container-high border border-outline-variant rounded-lg font-body-md focus:border-[#06B6D4] focus:ring-1 focus:ring-[#06B6D4]/50">
                        @error('customer_phone')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>

                    <button type="submit" class="mt-4 w-full bg-[#06B6D4] text-white font-bold font-label-lg py-4 rounded-xl hover:bg-[#0891B2] transition shadow-md">
                        Continue to Payment
                    </button>
                </form>
            </div>

            <div class="md:col-span-1">
                <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-lg shadow-sm sticky top-28">
                    <h2 class="font-headline-sm font-bold text-on-surface border-b border-outline-variant pb-2 mb-4">Order Summary</h2>
                    
                    <div class="flex gap-4 mb-6">
                        @if($product->images->count() > 0)
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="w-20 h-20 object-cover rounded border border-outline-variant">
                        @else
                            <div class="w-20 h-20 rounded bg-surface-container-high flex items-center justify-center">
                                <span class="material-symbols-outlined text-on-surface-variant">code</span>
                            </div>
                        @endif
                        <div>
                            <h3 class="font-bold text-on-surface line-clamp-2 text-sm">{{ $product->name }}</h3>
                            <div class="text-xs text-on-surface-variant mt-1">Digital Download</div>
                        </div>
                    </div>

                    <div class="border-t border-outline-variant pt-4 space-y-2 mb-4">
                        <div class="flex justify-between text-on-surface-variant font-body-sm">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>
                        @if($product->discount_price)
                        <div class="flex justify-between text-error font-body-sm">
                            <span>Discount</span>
                            <span>- Rp {{ number_format($product->price - $product->discount_price, 0, ',', '.') }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="border-t border-outline-variant pt-4 flex justify-between items-center">
                        <span class="font-bold text-on-surface">Total</span>
                        <span class="font-headline-sm font-black text-[#06B6D4]">
                            Rp {{ number_format($product->discount_price ?? $product->price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>
@endsection

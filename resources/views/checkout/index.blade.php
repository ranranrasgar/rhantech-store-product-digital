@extends('layouts.shopee')
@section('title', 'Checkout | PPOB')

@section('content')
<main class="bg-gray-100 min-h-screen pt-[130px] pb-24">
    <div class="max-w-[1200px] mx-auto px-4">
        
        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf
            
            <!-- Address/Customer Section -->
            <div class="bg-white rounded shadow-sm mb-4 relative overflow-hidden">
                <!-- Top border decoration -->
                <div class="absolute top-0 left-0 w-full h-[3px] bg-gradient-to-r from-red-500 via-blue-500 to-red-500" style="background-size: 116px 3px; background-image: repeating-linear-gradient(45deg, #ee4d2d 0, #ee4d2d 33px, transparent 0, transparent 41px, #5c7ee5 0, #5c7ee5 74px, transparent 0, transparent 82px);"></div>
                
                <div class="p-6">
                    <div class="flex items-center text-primary text-lg mb-4 gap-2">
                        <span class="material-symbols-outlined text-[24px]">location_on</span>
                        <h2 class="font-medium">Detail Pembeli</h2>
                    </div>
                    
                    <div class="flex flex-col md:flex-row gap-4 mb-4">
                        <div class="w-full md:w-1/3">
                            <label class="block text-sm text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="customer_name" required value="{{ old('customer_name') }}" class="w-full text-sm border-gray-300 rounded focus:border-primary focus:ring-1 focus:ring-primary/50" placeholder="Masukkan nama">
                            @error('customer_name')<span class="text-error text-xs">{{ $message }}</span>@enderror
                        </div>
                        <div class="w-full md:w-1/3">
                            <label class="block text-sm text-gray-700 mb-1">Email (Untuk pengiriman File)</label>
                            <input type="email" name="customer_email" required value="{{ old('customer_email') }}" class="w-full text-sm border-gray-300 rounded focus:border-primary focus:ring-1 focus:ring-primary/50" placeholder="Masukkan email">
                            @error('customer_email')<span class="text-error text-xs">{{ $message }}</span>@enderror
                        </div>
                        <div class="w-full md:w-1/3">
                            <label class="block text-sm text-gray-700 mb-1">Nomor WhatsApp</label>
                            <input type="text" name="customer_phone" required value="{{ old('customer_phone') }}" class="w-full text-sm border-gray-300 rounded focus:border-primary focus:ring-1 focus:ring-primary/50" placeholder="Masukkan no WA">
                            @error('customer_phone')<span class="text-error text-xs">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product List Section -->
            <div class="bg-white rounded shadow-sm mb-4">
                <div class="p-6 pb-2">
                    <div class="hidden md:flex text-gray-500 text-sm mb-4">
                        <div class="w-1/2">Produk Dipesan</div>
                        <div class="w-1/6 text-center">Harga Satuan</div>
                        <div class="w-1/6 text-center">Jumlah</div>
                        <div class="w-1/6 text-right">Subtotal Produk</div>
                    </div>
                </div>
                
                @php $totalAmount = 0; @endphp
                @foreach($cart as $id => $details)
                    @php 
                        $itemTotal = $details['price'] * $details['quantity'];
                        $totalAmount += $itemTotal;
                    @endphp
                    <!-- Store Name -->
                    <div class="px-6 py-2 border-t border-gray-100 flex items-center gap-2">
                        <span class="text-xs bg-primary text-white px-1 py-0.5 rounded-sm">Star</span>
                        <span class="text-sm font-bold text-gray-800">{{ $details['store_name'] }}</span>
                        <span class="material-symbols-outlined text-green-500 text-[18px]">chat</span>
                    </div>
                    
                    <!-- Item -->
                    <div class="px-4 md:px-6 py-4 flex flex-col md:flex-row border-b border-gray-100 last:border-b-0 md:items-center bg-gray-50/50 gap-4 md:gap-0">
                        <div class="w-full md:w-1/2 flex items-center gap-4">
                            <img src="{{ $details['image'] ? asset('storage/'.$details['image']) : 'https://placehold.co/80x80?text=No+Image' }}" class="w-12 h-12 object-cover border border-gray-200 shrink-0">
                            <div class="text-sm text-gray-800 line-clamp-2 leading-snug">{{ $details['name'] }}</div>
                        </div>
                        <div class="w-full flex justify-between items-center md:hidden border-t border-dashed border-gray-200 pt-2 mt-2">
                            <div class="text-sm text-gray-600">Rp{{ number_format($details['price'], 0, ',', '.') }} x {{ $details['quantity'] }}</div>
                            <div class="text-sm font-medium text-primary">Rp{{ number_format($itemTotal, 0, ',', '.') }}</div>
                        </div>
                        <div class="hidden md:block w-1/6 text-center text-sm text-gray-600">
                            Rp{{ number_format($details['price'], 0, ',', '.') }}
                        </div>
                        <div class="hidden md:block w-1/6 text-center text-sm text-gray-800">
                            {{ $details['quantity'] }}
                        </div>
                        <div class="hidden md:block w-1/6 text-right text-sm text-gray-800 font-medium">
                            Rp{{ number_format($itemTotal, 0, ',', '.') }}
                        </div>
                    </div>
                    
                    <!-- Notes & Shipping -->
                    <div class="px-4 md:px-6 py-4 flex flex-col md:flex-row border-b border-dashed border-gray-200 md:items-start gap-4">
                        <div class="w-full md:w-1/2 flex items-center gap-4 md:border-r border-gray-200 md:pr-4">
                            <span class="text-sm text-gray-800 shrink-0">Pesan:</span>
                            <input type="text" class="text-sm w-full border-gray-300 rounded focus:border-primary focus:ring-primary/50" placeholder="(Opsional) Pesan">
                        </div>
                        <div class="w-full md:w-1/2 md:pl-6 flex justify-between items-center">
                            <div class="text-sm text-gray-800">Opsi Pengiriman:</div>
                            <div class="text-right">
                                <div class="text-sm font-bold text-gray-800">Kirim File Digital</div>
                                <div class="text-sm font-bold text-gray-500">Rp0</div>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                <!-- Order Total -->
                <div class="px-4 md:px-6 py-4 bg-gray-50/50 flex justify-between md:justify-end items-center gap-4">
                    <div class="text-sm text-gray-500">Total Pesanan <span class="hidden md:inline">({{ count($cart) }} Produk)</span>:</div>
                    <div class="text-lg md:text-xl text-primary font-medium">Rp{{ number_format($totalAmount, 0, ',', '.') }}</div>
                </div>
            </div>

            <!-- Payment Method & Footer -->
            <div class="bg-white rounded shadow-sm mb-4">
                <div class="p-4 md:p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center gap-4 md:gap-0">
                    <div class="w-full md:w-48 text-gray-800 font-medium text-lg">Metode Pembayaran</div>
                    <div class="flex gap-2">
                        <div class="border border-primary text-primary bg-surface-variant px-4 py-1.5 text-sm rounded-sm font-medium">Otomatis / QRIS</div>
                    </div>
                </div>
                
                <!-- Calculation breakdown -->
                <div class="p-4 md:p-6 bg-gray-50/30">
                    <div class="flex flex-col items-end gap-2 md:gap-3 text-sm text-gray-600 mb-6">
                        <div class="flex w-full md:w-64 justify-between">
                            <span>Subtotal untuk Produk</span>
                            <span>Rp{{ number_format($totalAmount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex w-full md:w-64 justify-between">
                            <span>Total Ongkos Kirim</span>
                            <span>Rp0</span>
                        </div>
                        <div class="flex w-full md:w-64 justify-between">
                            <span>Biaya Layanan</span>
                            <span>Rp0</span>
                        </div>
                        <div class="flex w-full md:w-64 justify-between items-center mt-2 border-t border-dashed border-gray-200 md:border-none pt-2 md:pt-0">
                            <span class="text-gray-800 font-medium text-base md:text-sm">Total Pembayaran</span>
                            <span class="text-2xl md:text-3xl text-primary font-medium">Rp{{ number_format($totalAmount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col md:flex-row justify-between items-center border-t border-dashed border-gray-200 pt-6 gap-4 md:gap-0">
                        <p class="text-xs text-gray-500 w-full md:w-2/3 text-center md:text-left">
                            Dengan mengklik "Buat Pesanan", Anda menyetujui <a href="#" class="text-blue-500 hover:underline">Syarat & Ketentuan</a> kami. Pesanan digital ini akan diproses otomatis oleh Midtrans.
                        </p>
                        <button type="submit" class="bg-primary text-white px-8 md:px-12 py-3 rounded text-sm font-medium hover:brightness-110 transition shadow-sm w-full md:w-48">
                            Buat Pesanan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>
@endsection

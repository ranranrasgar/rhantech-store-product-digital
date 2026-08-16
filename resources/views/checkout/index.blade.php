@extends('layouts.shopee')
@section('title', 'Checkout Pesanan - ' . ($company->company_name ?? 'Rhantech'))

@section('content')
<main class="bg-background text-on-background min-h-screen pt-6 md:pt-10 pb-24 font-sans transition-colors duration-200">
    <div class="max-w-[1240px] mx-auto px-4 sm:px-6">
        
        <!-- Breadcrumb / Header -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <nav class="flex items-center text-xs md:text-sm text-on-surface-variant mb-2">
                    <a href="{{ route('home') }}" class="hover:text-primary transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">home</span>
                        Beranda
                    </a>
                    <span class="mx-2 text-outline-variant">/</span>
                    <a href="{{ route('cart.index') }}" class="hover:text-primary transition-colors">Keranjang</a>
                    <span class="mx-2 text-outline-variant">/</span>
                    <span class="text-on-surface font-semibold">Checkout Pesanan</span>
                </nav>
                <h1 class="text-xl md:text-2xl font-black text-on-surface tracking-tight">Checkout Produk Digital</h1>
            </div>
            <div class="flex items-center gap-2 text-xs text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-3 py-1.5 rounded-sm">
                <span class="material-symbols-outlined text-[18px]">verified_user</span>
                <span class="font-bold">Pembayaran Aman & Akses Otomatis</span>
            </div>
        </div>

        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf
            
            <!-- Address/Customer Section -->
            <div class="bg-surface rounded-sm border border-outline-variant shadow-xs mb-6 relative overflow-hidden">
                <!-- Top border accent -->
                <div class="absolute top-0 left-0 w-full h-[3px] bg-gradient-to-r from-primary via-sky-500 to-emerald-500"></div>
                
                <div class="p-4 md:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                        <div class="flex items-center text-primary text-base font-bold gap-2">
                            <span class="material-symbols-outlined text-[22px]">person_pin</span>
                            <h2 class="text-on-surface">Data Penerima / Akun Pembeli</h2>
                        </div>
                        @if(auth()->check())
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-sm bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-xs font-semibold">
                                <span class="material-symbols-outlined text-[15px]">account_circle</span>
                                Terisi otomatis dari akun Anda ({{ auth()->user()->email }})
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-sm bg-sky-500/10 border border-sky-500/20 text-sky-600 dark:text-sky-400 text-xs font-semibold">
                                <span class="material-symbols-outlined text-[15px]">info</span>
                                Akun pembeli & link aktivasi akan otomatis dibuatkan ke email ini
                            </span>
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" name="customer_name" required value="{{ old('customer_name', auth()->check() ? auth()->user()->name : '') }}" class="w-full text-sm bg-surface-container-low border border-outline-variant rounded-sm px-3.5 py-2.5 text-on-surface focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary/50 transition-colors" placeholder="Nama lengkap Anda">
                            @error('customer_name')<span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Email Pengiriman File <span class="text-rose-500">*</span></label>
                            <input type="email" name="customer_email" required value="{{ old('customer_email', auth()->check() ? auth()->user()->email : '') }}" class="w-full text-sm bg-surface-container-low border border-outline-variant rounded-sm px-3.5 py-2.5 text-on-surface focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary/50 transition-colors" placeholder="email@domain.com">
                            <span class="text-[11px] text-on-surface-variant mt-1 block">Link file & aktivasi akun dikirim ke email ini.</span>
                            @error('customer_email')<span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Nomor WhatsApp <span class="text-rose-500">*</span></label>
                            <input type="text" name="customer_phone" required value="{{ old('customer_phone') }}" class="w-full text-sm bg-surface-container-low border border-outline-variant rounded-sm px-3.5 py-2.5 text-on-surface focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary/50 transition-colors" placeholder="08xxxxxxxxxx">
                            <span class="text-[11px] text-on-surface-variant mt-1 block">Untuk notifikasi status invoice otomatis.</span>
                            @error('customer_phone')<span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product List Section -->
            <div class="bg-surface rounded-sm border border-outline-variant shadow-xs mb-6 overflow-hidden">
                <div class="p-4 md:p-6 pb-3 border-b border-outline-variant">
                    <div class="hidden md:grid grid-cols-12 text-xs font-bold uppercase tracking-wider text-on-surface-variant">
                        <div class="col-span-6">Produk Digital Dipesan</div>
                        <div class="col-span-2 text-center">Harga Satuan</div>
                        <div class="col-span-2 text-center">Jumlah</div>
                        <div class="col-span-2 text-right">Subtotal</div>
                    </div>
                    <div class="md:hidden text-sm font-bold text-on-surface">Daftar Item Pesanan</div>
                </div>
                
                @php $totalAmount = 0; @endphp
                @foreach($cart as $id => $details)
                    @php 
                        $itemTotal = $details['price'] * $details['quantity'];
                        $totalAmount += $itemTotal;
                    @endphp
                    <!-- Store Header Bar -->
                    <div class="px-4 md:px-6 py-2.5 bg-surface-container-low border-b border-outline-variant/60 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-primary">storefront</span>
                            <span class="text-xs font-bold text-on-surface">{{ $details['store_name'] ?? 'Official Store' }}</span>
                            <span class="material-symbols-outlined text-primary text-[14px]">verified</span>
                        </div>
                        <span class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold flex items-center gap-1">
                            <span class="material-symbols-outlined text-[13px]">bolt</span> Instant Delivery
                        </span>
                    </div>
                    
                    <!-- Item Row -->
                    <div class="p-4 md:p-6 flex flex-col md:grid md:grid-cols-12 items-start md:items-center gap-4 border-b border-outline-variant/40">
                        <div class="col-span-6 flex items-center gap-3.5 w-full">
                            <img src="{{ $details['image'] ? asset('storage/'.$details['image']) : 'https://placehold.co/80x80?text=No+Image' }}" class="w-14 h-14 object-cover rounded-sm border border-outline-variant shrink-0 bg-surface-container">
                            <div class="min-w-0">
                                <div class="text-sm font-bold text-on-surface line-clamp-2 leading-snug">{{ $details['name'] }}</div>
                                <div class="text-[11px] text-on-surface-variant mt-1 flex items-center gap-1.5">
                                    <span class="px-1.5 py-0.5 rounded-sm bg-surface-container text-on-surface-variant font-medium">Digital Product</span>
                                    <span>•</span>
                                    <span>Download Link</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-2 text-center w-full md:w-auto flex justify-between md:block text-xs md:text-sm text-on-surface-variant">
                            <span class="md:hidden">Harga:</span>
                            <span class="font-medium text-on-surface">Rp{{ number_format($details['price'], 0, ',', '.') }}</span>
                        </div>
                        <div class="col-span-2 text-center w-full md:w-auto flex justify-between md:block text-xs md:text-sm text-on-surface">
                            <span class="md:hidden text-on-surface-variant">Kuantitas:</span>
                            <span class="font-semibold px-2 py-1 rounded-sm bg-surface-container text-xs">{{ $details['quantity'] }}</span>
                        </div>
                        <div class="col-span-2 text-right w-full md:w-auto flex justify-between md:block text-sm font-black text-primary">
                            <span class="md:hidden text-on-surface-variant font-normal">Subtotal:</span>
                            <span>Rp{{ number_format($itemTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endforeach
                
                <!-- Notes & Delivery Options -->
                <div class="p-4 md:p-6 bg-surface-container-low border-t border-outline-variant flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="w-full md:w-1/2 flex items-center gap-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-on-surface-variant shrink-0">Catatan:</span>
                        <input type="text" name="notes" class="text-xs w-full bg-surface border border-outline-variant rounded-sm px-3 py-2 text-on-surface focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary/50" placeholder="(Opsional) Catatan khusus pesanan Anda">
                    </div>
                    <div class="w-full md:w-auto flex items-center justify-between md:justify-end gap-6 text-xs">
                        <span class="text-on-surface-variant">Pengiriman:</span>
                        <div class="text-right">
                            <span class="font-bold text-on-surface block">Kirim Link File Otomatis</span>
                            <span class="font-black text-emerald-600 dark:text-emerald-400">Gratis (Rp0)</span>
                        </div>
                    </div>
                </div>

                <!-- Order Total Bar -->
                <div class="p-4 md:p-6 bg-surface flex justify-between items-center border-t border-outline-variant">
                    <div class="text-xs md:text-sm text-on-surface-variant">Total Dipesan ({{ count($cart) }} Produk):</div>
                    <div class="text-lg md:text-xl font-black text-primary">Rp{{ number_format($totalAmount, 0, ',', '.') }}</div>
                </div>
            </div>

            <!-- Payment Method & Summary -->
            <div class="bg-surface rounded-sm border border-outline-variant shadow-xs mb-6 overflow-hidden">
                <div class="p-4 md:p-6 border-b border-outline-variant flex flex-col md:flex-row md:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-base font-bold text-on-surface">Metode Pembayaran</h2>
                        <p class="text-xs text-on-surface-variant mt-0.5">Pilih sistem gerbang pembayaran terverifikasi otomatis.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="border-2 border-primary text-primary bg-primary/5 px-3 py-1.5 text-xs rounded-sm font-bold flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">qr_code_scanner</span>
                            <span>QRIS / Bank Transfer / E-Wallet (Midtrans)</span>
                        </div>
                    </div>
                </div>
                
                <!-- Calculation Breakdown & Submit -->
                <div class="p-4 md:p-6 bg-surface-container-low">
                    <div class="flex flex-col items-end gap-2.5 text-xs md:text-sm text-on-surface-variant mb-6">
                        <div class="flex w-full md:w-72 justify-between">
                            <span>Subtotal Produk:</span>
                            <span class="font-bold text-on-surface">Rp{{ number_format($totalAmount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex w-full md:w-72 justify-between">
                            <span>Biaya Pengiriman Digital:</span>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400">Rp0</span>
                        </div>
                        <div class="flex w-full md:w-72 justify-between">
                            <span>Biaya Layanan Gerbang:</span>
                            <span class="font-bold text-on-surface">Rp0</span>
                        </div>
                        <div class="flex w-full md:w-72 justify-between items-baseline mt-2 pt-3 border-t border-outline-variant">
                            <span class="text-sm font-extrabold text-on-surface">Total Pembayaran:</span>
                            <span class="text-2xl md:text-3xl font-black text-primary">Rp{{ number_format($totalAmount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col md:flex-row justify-between items-center border-t border-outline-variant pt-5 gap-4">
                        <p class="text-xs text-on-surface-variant w-full md:w-2/3 text-center md:text-left leading-relaxed">
                            Dengan mengklik tombol <span class="font-bold text-on-surface">"Buat Pesanan"</span>, Anda menyetujui ketentuan transaksi produk digital kami. Invoice dan QR pembayaran otomatis diterbitkan melalui payment gateway Midtrans.
                        </p>
                        <button type="submit" class="w-full md:w-auto px-8 md:px-12 py-3.5 bg-primary hover:brightness-110 text-white rounded-sm text-sm font-bold transition-all shadow-md shadow-primary/20 flex items-center justify-center gap-2 shrink-0 cursor-pointer">
                            <span class="material-symbols-outlined text-[18px]">lock</span>
                            <span>Buat Pesanan</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>
@endsection


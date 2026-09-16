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
            <div class="bg-surface rounded-sm border border-outline-variant mb-6 relative overflow-hidden">
                <!-- Top border accent -->
                <div class="absolute top-0 left-0 w-full h-[3px] bg-sky-500"></div>
                
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
                            <input type="text" name="customer_name" required value="{{ old('customer_name', auth()->check() ? auth()->user()->name : '') }}" class="w-full text-sm bg-surface-container-low border border-outline-variant rounded-sm px-3.5 py-2.5 text-on-surface focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary/50 transition-colors {{ auth()->check() ? 'opacity-70 cursor-not-allowed bg-surface-container-high' : '' }}" placeholder="Nama lengkap Anda" {{ auth()->check() ? 'readonly' : '' }}>
                            @error('customer_name')<span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Email Pengiriman File <span class="text-rose-500">*</span></label>
                            <input type="email" name="customer_email" required value="{{ old('customer_email', auth()->check() ? auth()->user()->email : '') }}" class="w-full text-sm bg-surface-container-low border border-outline-variant rounded-sm px-3.5 py-2.5 text-on-surface focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary/50 transition-colors {{ auth()->check() ? 'opacity-70 cursor-not-allowed bg-surface-container-high' : '' }}" placeholder="email@domain.com" {{ auth()->check() ? 'readonly' : '' }}>
                            <span class="text-[11px] text-on-surface-variant mt-1 block">Link file & aktivasi akun dikirim ke email ini.</span>
                            @error('customer_email')<span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Nomor WhatsApp <span class="text-rose-500">*</span></label>
                            <input type="text" name="customer_phone" required value="{{ old('customer_phone', $defaultPhone ?? (auth()->check() ? auth()->user()->phone : '')) }}" class="w-full text-sm bg-surface-container-low border border-outline-variant rounded-sm px-3.5 py-2.5 text-on-surface focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary/50 transition-colors" placeholder="08xxxxxxxxxx">
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
                    <div class="text-lg md:text-xl font-black text-primary">Rp{{ number_format($subtotal ?? $totalAmount, 0, ',', '.') }}</div>
                </div>
            </div>

            <!-- Voucher & Kupon Toko Section -->
            <div class="bg-surface rounded-sm border border-outline-variant shadow-xs mb-6 overflow-hidden" id="voucherSection">
                <div class="p-4 md:p-6 border-b border-outline-variant flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-2 text-primary font-bold">
                        <span class="material-symbols-outlined text-[22px]">confirmation_number</span>
                        <h2 class="text-base text-on-surface">Voucher & Kupon Promo Toko</h2>
                    </div>
                    @if(isset($availableVouchers) && $availableVouchers->count() > 0)
                        <button type="button" 
                                onclick="openVoucherModal()" 
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-sm bg-primary/10 hover:bg-primary/20 text-primary text-xs font-bold transition-colors cursor-pointer self-start sm:self-auto border border-primary/20">
                            <span class="material-symbols-outlined text-[16px]">sell</span>
                            <span>Pilih Dari {{ $availableVouchers->count() }} Kupon Tersedia</span>
                        </button>
                    @endif
                </div>

                <div class="p-4 md:p-6">
                    <!-- Voucher Input Form -->
                    <div class="max-w-md">
                        <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Punya Kode Voucher?</label>
                        <div class="flex gap-2">
                            <input type="text" 
                                   id="voucherInputText" 
                                   placeholder="Contoh: MERDEKA100, DISKON50" 
                                   value="{{ $appliedVoucher['code'] ?? '' }}"
                                   class="flex-1 text-sm bg-surface-container-low border border-outline-variant rounded-sm px-3.5 py-2.5 text-on-surface font-mono font-bold uppercase focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary/50"
                                   {{ !empty($appliedVoucher) ? 'readonly' : '' }}>
                            
                            <button type="button" 
                                    id="btnApplyVoucher" 
                                    onclick="handleApplyInput()" 
                                    class="px-5 py-2.5 bg-primary hover:bg-primary-dark text-white rounded-sm text-xs font-bold transition-all shadow-xs shrink-0 cursor-pointer {{ !empty($appliedVoucher) ? 'hidden' : '' }}">
                                Terapkan
                            </button>

                            <button type="button" 
                                    id="btnRemoveVoucher" 
                                    onclick="removeVoucher()" 
                                    class="px-4 py-2.5 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800 rounded-sm text-xs font-bold transition-colors shrink-0 cursor-pointer {{ empty($appliedVoucher) ? 'hidden' : '' }}">
                                Hapus
                            </button>
                        </div>
                        <input type="hidden" name="voucher_code" id="hiddenVoucherCode" value="{{ $appliedVoucher['code'] ?? '' }}">
                    </div>

                    <!-- Applied Voucher Status Alert -->
                    <div id="voucherAlertSuccess" class="mt-3 {{ empty($appliedVoucher) ? 'hidden' : '' }}">
                        <div class="p-3 rounded-sm bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-300 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[20px] text-emerald-600 dark:text-emerald-400">check_circle</span>
                                <div>
                                    <span class="font-bold" id="voucherSuccessTitle">
                                        Kupon <span class="font-mono underline">{{ $appliedVoucher['code'] ?? '' }}</span> Berhasil Digunakan!
                                    </span>
                                    <span class="block text-[11px] text-emerald-700 dark:text-emerald-400" id="voucherSuccessDesc">
                                        @if(!empty($appliedVoucher['is_free']))
                                            🎉 Diskon 100% — Total Belanja Menjadi GRATIS (Rp 0)!
                                        @elseif(!empty($appliedVoucher))
                                            Hemat Rp {{ number_format($discountAmount ?? 0, 0, ',', '.') }} untuk pesanan ini.
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <button type="button" onclick="removeVoucher()" class="text-xs font-bold text-rose-600 hover:underline ml-2 shrink-0">Batal</button>
                        </div>
                    </div>

                    <!-- Voucher Alert Error -->
                    <div id="voucherAlertError" class="mt-3 hidden">
                        <div class="p-3 rounded-sm bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 flex items-center gap-2 text-xs">
                            <span class="material-symbols-outlined text-[18px] text-rose-500">error</span>
                            <span id="voucherErrorMsg">Kode voucher tidak valid.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Method & Summary -->
            <div class="bg-surface rounded-sm border border-outline-variant mb-6 overflow-hidden">
                <div class="p-4 md:p-6 border-b border-outline-variant flex flex-col md:flex-row md:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-base font-bold text-on-surface">Metode Pembayaran</h2>
                        <p class="text-xs text-on-surface-variant mt-0.5" id="paymentMethodSubtitle">
                            Pilih sistem gerbang pembayaran terverifikasi otomatis.
                        </p>
                    </div>
                    <div class="flex items-center gap-2" id="paymentMethodBadge">
                        @if(!empty($appliedVoucher['is_free']) || (isset($finalAmount) && $finalAmount <= 0))
                            <div class="border-2 border-emerald-500 text-emerald-600 bg-emerald-50 dark:bg-emerald-950/40 px-3 py-1.5 text-xs rounded-sm font-bold flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px]">redeem</span>
                                <span>GRATIS 100% (Aktivasi Langsung Tanpa Bayar)</span>
                            </div>
                        @else
                            <div class="border-2 border-primary text-primary bg-primary/5 px-3 py-1.5 text-xs rounded-sm font-bold flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px]">qr_code_scanner</span>
                                <span>QRIS / Bank Transfer / E-Wallet (Midtrans)</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Calculation Breakdown & Submit -->
                <div class="p-4 md:p-6 bg-surface-container-low">
                    <div class="flex flex-col items-end gap-2 text-xs md:text-sm text-on-surface-variant mb-6">
                        <div class="flex w-full md:w-80 justify-between">
                            <span>Subtotal Produk:</span>
                            <span class="font-bold text-on-surface" id="summarySubtotal">Rp{{ number_format($subtotal ?? $totalAmount, 0, ',', '.') }}</span>
                        </div>

                        <!-- Voucher Discount Row -->
                        <div class="flex w-full md:w-80 justify-between text-emerald-600 dark:text-emerald-400 font-bold {{ empty($discountAmount) || $discountAmount <= 0 ? 'hidden' : '' }}" id="rowDiscount">
                            <span class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">sell</span>
                                Diskon Kupon:
                            </span>
                            <span id="summaryDiscount">-Rp{{ number_format($discountAmount ?? 0, 0, ',', '.') }}</span>
                        </div>


                        <div class="flex w-full md:w-80 justify-between items-baseline mt-2 pt-3 border-t border-outline-variant">
                            <span class="text-sm font-extrabold text-on-surface">Total Pembayaran:</span>
                            <div class="text-right">
                                <span class="text-2xl md:text-3xl font-black {{ (!empty($appliedVoucher['is_free']) || (isset($finalAmount) && $finalAmount <= 0)) ? 'text-emerald-600 dark:text-emerald-400' : 'text-primary' }}" id="summaryTotal">
                                    @if(!empty($appliedVoucher['is_free']) || (isset($finalAmount) && $finalAmount <= 0))
                                        GRATIS (Rp 0)
                                    @else
                                        Rp{{ number_format($finalAmount ?? $totalAmount, 0, ',', '.') }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-col md:flex-row justify-between items-center border-t border-outline-variant pt-5 gap-4">
                        <p class="text-xs text-on-surface-variant w-full md:w-2/3 text-center md:text-left leading-relaxed" id="checkoutNoteText">
                            @if(!empty($appliedVoucher['is_free']) || (isset($finalAmount) && $finalAmount <= 0))
                                Kupon 100% diterapkan! Klik <span class="font-bold text-on-surface">"Klaim Produk Gratis"</span> untuk langsung mendapatkan akses file unduhan ke akun Anda.
                            @else
                                Dengan mengklik tombol <span class="font-bold text-on-surface">"Buat Pesanan"</span>, Anda menyetujui ketentuan transaksi produk digital kami. Invoice dan QR pembayaran otomatis diterbitkan melalui payment gateway Midtrans.
                            @endif
                        </p>
                        <button type="submit" id="btnSubmitOrder" class="w-full md:w-auto px-8 md:px-12 py-3.5 {{ (!empty($appliedVoucher['is_free']) || (isset($finalAmount) && $finalAmount <= 0)) ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-sky-500 hover:bg-sky-600' }} text-white rounded-sm text-sm font-bold transition-colors flex items-center justify-center gap-2 shrink-0 cursor-pointer">
                            <span class="material-symbols-outlined text-[18px]" id="btnSubmitIcon">{{ (!empty($appliedVoucher['is_free']) || (isset($finalAmount) && $finalAmount <= 0)) ? 'redeem' : 'lock' }}</span>
                            <span id="btnSubmitLabel">{{ (!empty($appliedVoucher['is_free']) || (isset($finalAmount) && $finalAmount <= 0)) ? 'Klaim Produk Gratis' : 'Buat Pesanan' }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Tokopedia Style Voucher Modal -->
    <div id="voucherModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs hidden">
        <div class="bg-white dark:bg-[#161b22] w-full max-w-2xl rounded-2xl border border-gray-200 dark:border-[#30363d] overflow-hidden flex flex-col max-h-[85vh] animate-in fade-in zoom-in-95 duration-200">
            <!-- Modal Header -->
            <div class="p-4 sm:p-5 border-b border-gray-200 dark:border-[#30363d] flex items-center justify-between bg-surface-container-low">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[24px]">confirmation_number</span>
                    <div>
                        <h3 class="text-base font-bold text-on-surface">Kupon Toko Tersedia</h3>
                        <p class="text-xs text-on-surface-variant">Pilih kupon diskon spesial untuk pesanan produk Anda</p>
                    </div>
                </div>
                <button type="button" onclick="closeVoucherModal()" class="w-8 h-8 rounded-full hover:bg-surface-container flex items-center justify-center text-on-surface-variant hover:text-on-surface transition-colors cursor-pointer">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <!-- Modal Content (List of Tokopedia Ticket Cards) -->
            <div class="p-4 sm:p-6 overflow-y-auto flex-1 divide-y divide-transparent space-y-4">
                @if(isset($availableVouchers) && $availableVouchers->count() > 0)
                    @foreach($availableVouchers as $v)
                        <x-voucher-card :campaign="$v" mode="checkout" :applied="!empty($appliedVoucher) && $appliedVoucher['id'] == $v->id" :hasUsed="in_array($v->id, $usedCampaignIds ?? [])" />
                    @endforeach
                @else
                    <div class="text-center py-10 text-on-surface-variant">
                        <span class="material-symbols-outlined text-5xl text-outline-variant mb-2">loyalty</span>
                        <p class="text-sm font-semibold">Tidak ada kupon voucher aktif saat ini.</p>
                        <p class="text-xs mt-1">Anda tetap bisa memasukkan kode kupon secara manual jika memilikinya.</p>
                    </div>
                @endif
            </div>

            <!-- Modal Footer -->
            <div class="p-4 border-t border-gray-200 dark:border-[#30363d] bg-surface-container-low flex justify-end">
                <button type="button" onclick="closeVoucherModal()" class="px-5 py-2 rounded-xl bg-surface border border-outline-variant hover:bg-surface-container text-xs font-bold text-on-surface transition-colors cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</main>

<script>
    function openVoucherModal() {
        document.getElementById('voucherModal').classList.remove('hidden');
    }

    function closeVoucherModal() {
        document.getElementById('voucherModal').classList.add('hidden');
    }

    function handleApplyInput() {
        const code = document.getElementById('voucherInputText').value.trim();
        if (!code) {
            showVoucherError('Silakan masukkan kode voucher terlebih dahulu.');
            return;
        }
        applyVoucherCode(code);
    }

    function applyVoucherCode(code) {
        hideVoucherAlerts();
        const btn = document.getElementById('btnApplyVoucher');
        if (btn) btn.disabled = true;

        const emailInput = document.querySelector('input[name="customer_email"]');
        const emailVal = emailInput ? emailInput.value.trim() : '';

        fetch('{{ route("checkout.apply_voucher") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ code: code, email: emailVal })
        })
        .then(r => r.json())
        .then(res => {
            if (btn) btn.disabled = false;
            if (res.success) {
                // Update UI elements
                document.getElementById('voucherInputText').value = res.voucher.code;
                document.getElementById('voucherInputText').readOnly = true;
                document.getElementById('hiddenVoucherCode').value = res.voucher.code;
                document.getElementById('btnApplyVoucher').classList.add('hidden');
                document.getElementById('btnRemoveVoucher').classList.remove('hidden');

                // Alert success
                document.getElementById('voucherAlertSuccess').classList.remove('hidden');
                document.getElementById('voucherSuccessTitle').innerHTML = `Kupon <span class="font-mono underline">${res.voucher.code}</span> Berhasil Digunakan!`;
                
                if (res.is_free) {
                    document.getElementById('voucherSuccessDesc').innerText = '🎉 Diskon 100% — Total Belanja Menjadi GRATIS (Rp 0)!';
                } else {
                    document.getElementById('voucherSuccessDesc').innerText = `Hemat ${res.discount_amount_formatted} untuk pesanan ini.`;
                }

                // Update calculation breakdown
                document.getElementById('rowDiscount').classList.remove('hidden');
                document.getElementById('summaryDiscount').innerText = `-${res.discount_amount_formatted}`;
                document.getElementById('summaryTotal').innerText = res.final_total_formatted;

                if (res.is_free) {
                    document.getElementById('summaryTotal').className = 'text-2xl md:text-3xl font-black text-emerald-600 dark:text-emerald-400';
                    document.getElementById('paymentMethodBadge').innerHTML = `
                        <div class="border-2 border-emerald-500 text-emerald-600 bg-emerald-50 dark:bg-emerald-950/40 px-3 py-1.5 text-xs rounded-sm font-bold flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">redeem</span>
                            <span>GRATIS 100% (Aktivasi Langsung Tanpa Bayar)</span>
                        </div>
                    `;
                    document.getElementById('btnSubmitOrder').className = 'w-full md:w-auto px-8 md:px-12 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-sm text-sm font-bold transition-all shadow-md flex items-center justify-center gap-2 shrink-0 cursor-pointer';
                    document.getElementById('btnSubmitIcon').innerText = 'redeem';
                    document.getElementById('btnSubmitLabel').innerText = 'Klaim Produk Gratis';
                    document.getElementById('checkoutNoteText').innerHTML = 'Kupon 100% diterapkan! Klik <span class="font-bold text-on-surface">"Klaim Produk Gratis"</span> untuk langsung mendapatkan akses file unduhan ke akun Anda.';
                } else {
                    document.getElementById('summaryTotal').className = 'text-2xl md:text-3xl font-black text-primary';
                }

                closeVoucherModal();
            } else {
                showVoucherError(res.message || 'Gagal menerapkan kupon.');
            }
        })
        .catch(err => {
            if (btn) btn.disabled = false;
            showVoucherError('Terjadi kesalahan jaringan saat menerapkan kupon.');
        });
    }

    function removeVoucher() {
        fetch('{{ route("checkout.remove_voucher") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(r => r.json())
        .then(res => {
            // Reset input
            document.getElementById('voucherInputText').value = '';
            document.getElementById('voucherInputText').readOnly = false;
            document.getElementById('hiddenVoucherCode').value = '';
            document.getElementById('btnApplyVoucher').classList.remove('hidden');
            document.getElementById('btnRemoveVoucher').classList.add('hidden');

            // Hide alert & discount row
            document.getElementById('voucherAlertSuccess').classList.add('hidden');
            document.getElementById('voucherAlertError').classList.add('hidden');
            document.getElementById('rowDiscount').classList.add('hidden');

            // Reset summary
            document.getElementById('summaryTotal').innerText = res.final_total_formatted;
            document.getElementById('summaryTotal').className = 'text-2xl md:text-3xl font-black text-primary';

            document.getElementById('paymentMethodBadge').innerHTML = `
                <div class="border-2 border-primary text-primary bg-primary/5 px-3 py-1.5 text-xs rounded-sm font-bold flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">qr_code_scanner</span>
                    <span>QRIS / Bank Transfer / E-Wallet (Midtrans)</span>
                </div>
            `;
            document.getElementById('btnSubmitOrder').className = 'w-full md:w-auto px-8 md:px-12 py-3.5 bg-primary hover:brightness-110 text-white rounded-sm text-sm font-bold transition-all shadow-md shadow-primary/20 flex items-center justify-center gap-2 shrink-0 cursor-pointer';
            document.getElementById('btnSubmitIcon').innerText = 'lock';
            document.getElementById('btnSubmitLabel').innerText = 'Buat Pesanan';
            document.getElementById('checkoutNoteText').innerHTML = 'Dengan mengklik tombol <span class="font-bold text-on-surface">"Buat Pesanan"</span>, Anda menyetujui ketentuan transaksi produk digital kami. Invoice dan QR pembayaran otomatis diterbitkan melalui payment gateway Midtrans.';
        });
    }

    function showVoucherError(msg) {
        document.getElementById('voucherAlertError').classList.remove('hidden');
        document.getElementById('voucherErrorMsg').innerText = msg;
    }

    function hideVoucherAlerts() {
        document.getElementById('voucherAlertError').classList.add('hidden');
    }
</script>
@endsection


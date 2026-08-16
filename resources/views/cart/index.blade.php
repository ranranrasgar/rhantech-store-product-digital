@extends('layouts.shopee')
@section('title', 'Keranjang Belanja - ' . ($company->company_name ?? 'Rhantech'))

@section('content')
<main class="bg-background text-on-background min-h-screen pt-6 md:pt-10 pb-24 font-sans transition-colors duration-200" x-data="cartState()" x-init="initCart()">
    <div class="max-w-[1240px] mx-auto px-4 sm:px-6">
        
        <!-- Header / Breadcrumb -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <nav class="flex items-center text-xs md:text-sm text-on-surface-variant mb-2">
                    <a href="{{ route('home') }}" class="hover:text-primary transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">home</span>
                        Beranda
                    </a>
                    <span class="mx-2 text-outline-variant">/</span>
                    <span class="text-on-surface font-semibold">Keranjang Belanja</span>
                </nav>
                <h1 class="text-xl md:text-2xl font-black text-on-surface tracking-tight">Keranjang Belanja</h1>
            </div>
            <a href="{{ route('products.index') }}" class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                Lanjut Eksplor Produk
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 px-4 py-3 rounded-sm mb-6 text-sm flex items-center gap-2" role="alert">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(count($cart) > 0)
            <!-- Cart Table Header -->
            <div class="hidden md:flex bg-surface rounded-sm border border-outline-variant items-center p-4 mb-4 text-on-surface-variant text-xs font-bold uppercase tracking-wider shadow-xs">
                <div class="w-1/2 flex items-center">
                    <input type="checkbox" id="selectAllHeader" class="mr-4 w-4 h-4 text-primary rounded-sm border-outline-variant focus:ring-primary cursor-pointer accent-primary"
                        :checked="allSelected" @click="toggleAll()">
                    <span>Produk Digital</span>
                </div>
                <div class="w-1/6 text-center">Harga Satuan</div>
                <div class="w-1/6 text-center">Kuantitas</div>
                <div class="w-1/6 text-center">Total Harga</div>
                <div class="w-1/6 text-center">Aksi</div>
            </div>

            <!-- Cart Items -->
            <div class="bg-surface rounded-sm border border-outline-variant shadow-xs mb-6 overflow-hidden">
                @foreach($cart as $id => $details)
                    <!-- Store Header -->
                    <div class="bg-surface-container-low border-b border-outline-variant/60 px-4 py-2.5 flex items-center text-xs font-bold text-on-surface">
                        <input type="checkbox" class="mr-3 w-4 h-4 text-primary rounded-sm border-outline-variant focus:ring-primary cursor-pointer accent-primary"
                            value="{{ $id }}" x-model="selectedItems">
                        <span class="material-symbols-outlined text-primary text-[16px] mr-1.5">storefront</span>
                        <span>{{ $details['store_name'] ?? 'Official Store' }}</span>
                    </div>
                    
                    <!-- Item Detail -->
                    <div class="p-4 md:p-5 flex flex-col md:flex-row md:items-center border-b border-outline-variant/40 last:border-b-0 gap-4 md:gap-0">
                        <div class="w-full md:w-1/2 flex items-start md:items-center gap-3.5">
                            <input type="checkbox" class="w-4 h-4 text-primary rounded-sm border-outline-variant focus:ring-primary mt-3 md:mt-0 cursor-pointer accent-primary"
                                value="{{ $id }}" x-model="selectedItems">
                            <img src="{{ $details['image'] ? asset('storage/'.$details['image']) : 'https://placehold.co/80x80?text=No+Image' }}" 
                                alt="{{ $details['name'] }}" class="w-16 h-16 object-cover rounded-sm border border-outline-variant shrink-0 bg-surface-container">
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('products.show', $details['slug']) }}" class="text-sm font-bold text-on-surface line-clamp-2 hover:text-primary transition leading-snug">
                                    {{ $details['name'] }}
                                </a>
                                <div class="text-[11px] text-on-surface-variant mt-1">Digital Access • Instant Delivery</div>
                                
                                <!-- Mobile Pricing & Actions -->
                                <div class="md:hidden mt-2 text-primary font-black text-sm mb-2">
                                    Rp{{ number_format($details['price'], 0, ',', '.') }}
                                </div>
                                <div class="flex items-center justify-between md:hidden pt-2 border-t border-outline-variant/60">
                                    <div class="text-xs text-on-surface-variant font-medium">Qty: 1</div>
                                    <form action="{{ route('cart.remove') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <button type="submit" class="text-xs text-rose-500 font-bold hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="hidden md:block w-1/6 text-center text-sm font-medium text-on-surface">
                            Rp{{ number_format($details['price'], 0, ',', '.') }}
                        </div>
                        <div class="hidden md:flex w-1/6 justify-center">
                            <span class="text-xs font-bold px-2.5 py-1 rounded-sm bg-surface-container text-on-surface border border-outline-variant">
                                1
                            </span>
                        </div>
                        <div class="hidden md:block w-1/6 text-center text-sm font-black text-primary"
                            x-text="items['{{ $id }}'] ? 'Rp' + formatRupiah(items['{{ $id }}'].price * items['{{ $id }}'].quantity) : ''">
                        </div>
                        <div class="hidden md:block w-1/6 text-center text-xs">
                            <form action="{{ route('cart.remove') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="id" value="{{ $id }}">
                                <button type="submit" class="text-on-surface-variant hover:text-rose-500 font-bold transition">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Cart Footer Bar -->
            <div class="bg-surface rounded-sm border border-outline-variant shadow-xs sticky bottom-4 p-4 flex flex-col sm:flex-row items-center justify-between z-40 gap-4">
                <div class="flex items-center shrink-0 gap-3 w-full sm:w-auto justify-between sm:justify-start">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="w-4 h-4 text-primary rounded-sm border-outline-variant focus:ring-primary cursor-pointer accent-primary"
                            :checked="allSelected" @click="toggleAll()">
                        <span class="text-xs font-bold text-on-surface cursor-pointer">
                            Pilih Semua (<span x-text="selectedItems.length"></span>)
                        </span>
                    </div>

                    <!-- Bulk Delete Button -->
                    <button type="button"
                        @click="removeSelected()"
                        :class="selectedItems.length > 0 ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'"
                        class="text-xs text-rose-500 hover:text-rose-600 font-bold border border-rose-500/30 px-2.5 py-1 rounded-sm transition">
                        Hapus Pilihan (<span x-text="selectedItems.length"></span>)
                    </button>
                </div>

                <div class="flex items-center gap-4 sm:gap-6 ml-auto w-full sm:w-auto justify-between sm:justify-end">
                    <div class="text-right">
                        <span class="text-xs text-on-surface-variant block sm:inline">Total (<span x-text="selectedItems.length"></span> item):</span>
                        <span class="text-base sm:text-2xl text-primary font-black sm:ml-2 block sm:inline"
                            x-text="'Rp' + formatRupiah(selectedTotalAmount)"></span>
                    </div>
                    <!-- Checkout button as form to pass selected IDs -->
                    <form id="checkoutForm" action="{{ route('checkout.select') }}" method="POST">
                        @csrf
                    </form>
                    <button type="button" @click="goCheckout()"
                        class="px-6 sm:px-10 py-3 bg-primary text-white text-xs sm:text-sm font-bold rounded-sm hover:brightness-110 transition shadow-md shadow-primary/20 whitespace-nowrap cursor-pointer">
                        Checkout
                    </button>
                </div>
            </div>

            <!-- Hidden bulk remove form -->
            <form id="bulkRemoveForm" action="{{ route('cart.remove') }}" method="POST" style="display:none;">
                @csrf
            </form>
        @else
            <!-- Empty Cart -->
            <div class="bg-surface rounded-sm border border-outline-variant shadow-xs p-16 text-center flex flex-col items-center justify-center">
                <span class="material-symbols-outlined text-outline-variant text-8xl mb-3">shopping_cart</span>
                <p class="text-on-surface-variant mb-6 text-base font-semibold">Keranjang belanja Anda masih kosong</p>
                <a href="{{ route('products.index') }}" class="px-8 py-3 bg-primary text-white text-xs font-bold rounded-sm hover:brightness-110 transition shadow-sm">
                    Mulai Belanja Produk Digital
                </a>
            </div>
        @endif
    </div>
</main>

<script>
function cartState() {
    return {
        selectedItems: [],
        items: {
            @foreach($cart as $id => $details)
            '{{ $id }}': {
                id: '{{ $id }}',
                price: {{ $details['price'] }},
                quantity: {{ $details['quantity'] }}
            },
            @endforeach
        },

        initCart() {
            this.selectedItems = Object.keys(this.items);
        },

        get allIds() {
            return Object.keys(this.items);
        },

        get allSelected() {
            return this.allIds.length > 0 && this.selectedItems.length === this.allIds.length;
        },

        toggleAll() {
            if (this.allSelected) {
                this.selectedItems = [];
            } else {
                this.selectedItems = [...this.allIds];
            }
        },

        get selectedTotalAmount() {
            return this.selectedItems.reduce((sum, id) => {
                const item = this.items[id];
                if (item) return sum + (item.price * item.quantity);
                return sum;
            }, 0);
        },

        formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        },

        updateQuantity(id, delta) {
            if (!this.items[id]) return;
            const newQty = this.items[id].quantity + delta;
            if (newQty < 1) return;
            this.items[id].quantity = newQty;

            fetch('{{ route("cart.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: id, quantity: newQty })
            });
        },

        removeSelected() {
            if (this.selectedItems.length === 0) return;
            if (!confirm('Hapus ' + this.selectedItems.length + ' produk yang dipilih dari keranjang?')) return;

            const form = document.getElementById('bulkRemoveForm');
            // Clear previous dynamic inputs
            form.querySelectorAll('input[name="ids[]"]').forEach(i => i.remove());

            this.selectedItems.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                form.appendChild(input);
            });
            form.submit();
        },

        goCheckout() {
            if (this.selectedItems.length === 0) {
                alert('Pilih minimal 1 produk untuk checkout.');
                return;
            }
            const form = document.getElementById('checkoutForm');
            // Clear previous dynamic inputs
            form.querySelectorAll('input[name="selected_ids[]"]').forEach(i => i.remove());

            this.selectedItems.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_ids[]';
                input.value = id;
                form.appendChild(input);
            });
            form.submit();
        }
    }
}
</script>

@endsection

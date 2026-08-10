@extends('layouts.shopee')
@section('title', 'Keranjang Belanja')

@section('content')
<main class="bg-gray-100 min-h-screen pt-[130px] pb-24" x-data="cartState()" x-init="initCart()">
    <div class="max-w-[1200px] mx-auto px-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(count($cart) > 0)
            <!-- Cart Header -->
            <div class="hidden md:flex bg-white rounded shadow-sm items-center p-4 mb-4 text-gray-500 text-sm">
                <div class="w-1/2 flex items-center">
                    <input type="checkbox" id="selectAllHeader" class="mr-4 w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary cursor-pointer"
                        :checked="allSelected" @click="toggleAll()">
                    <span>Produk</span>
                </div>
                <div class="w-1/6 text-center">Harga Satuan</div>
                <div class="w-1/6 text-center">Kuantitas</div>
                <div class="w-1/6 text-center">Total Harga</div>
                <div class="w-1/6 text-center">Aksi</div>
            </div>

            <!-- Cart Items -->
            <div class="bg-white rounded shadow-sm mb-4">
                @foreach($cart as $id => $details)
                    <!-- Store Header -->
                    <div class="border-b border-gray-100 p-4 flex items-center text-sm font-bold">
                        <input type="checkbox" class="mr-3 md:mr-4 w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary cursor-pointer"
                            value="{{ $id }}" x-model="selectedItems">
                        <span class="material-symbols-outlined text-primary text-lg mr-2">storefront</span>
                        {{ $details['store_name'] }}
                    </div>
                    
                    <!-- Item Detail -->
                    <div class="p-4 flex flex-col md:flex-row md:items-center border-b border-gray-100 last:border-b-0 gap-4 md:gap-0">
                        <div class="w-full md:w-1/2 flex items-start md:items-center gap-3 md:gap-4">
                            <input type="checkbox" class="w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary mt-3 md:mt-0 cursor-pointer"
                                value="{{ $id }}" x-model="selectedItems">
                            <img src="{{ $details['image'] ? asset('storage/'.$details['image']) : 'https://placehold.co/80x80?text=No+Image' }}" 
                                alt="{{ $details['name'] }}" class="w-20 h-20 object-cover border border-gray-200 shrink-0">
                            <div class="flex-1">
                                <a href="{{ route('products.show', $details['slug']) }}" class="text-sm text-gray-800 line-clamp-2 hover:text-primary transition leading-snug">
                                    {{ $details['name'] }}
                                </a>
                                <!-- Mobile Pricing & Actions -->
                                <div class="md:hidden mt-2 text-primary font-medium text-sm mb-2">
                                    Rp{{ number_format($details['price'], 0, ',', '.') }}
                                </div>
                                <div class="flex items-center justify-between md:hidden">
                                    <div class="flex items-center border border-gray-300 rounded">
                                        <button type="button" @click="updateQuantity('{{ $id }}', -1)"
                                            class="px-2 py-0.5 bg-white hover:bg-gray-50 border-r border-gray-300 text-gray-600">-</button>
                                        <input type="text" :value="items['{{ $id }}'] ? items['{{ $id }}'].quantity : 1"
                                            class="w-10 text-center text-xs border-none focus:ring-0 py-1" readonly>
                                        <button type="button" @click="updateQuantity('{{ $id }}', 1)"
                                            class="px-2 py-0.5 bg-white hover:bg-gray-50 border-l border-gray-300 text-gray-600">+</button>
                                    </div>
                                    <form action="{{ route('cart.remove') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <button type="submit" class="text-xs text-gray-500 hover:text-primary transition">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="hidden md:block w-1/6 text-center text-sm text-gray-600">
                            Rp{{ number_format($details['price'], 0, ',', '.') }}
                        </div>
                        <div class="hidden md:flex w-1/6 justify-center">
                            <div class="flex items-center border border-gray-300 rounded">
                                <button type="button" @click="updateQuantity('{{ $id }}', -1)"
                                    class="px-3 py-1 bg-white hover:bg-gray-50 border-r border-gray-300 text-gray-600">-</button>
                                <input type="text" :value="items['{{ $id }}'] ? items['{{ $id }}'].quantity : 1"
                                    class="w-12 text-center text-sm border-none focus:ring-0" readonly>
                                <button type="button" @click="updateQuantity('{{ $id }}', 1)"
                                    class="px-3 py-1 bg-white hover:bg-gray-50 border-l border-gray-300 text-gray-600">+</button>
                            </div>
                        </div>
                        <div class="hidden md:block w-1/6 text-center text-sm text-primary font-medium"
                            x-text="items['{{ $id }}'] ? 'Rp' + formatRupiah(items['{{ $id }}'].price * items['{{ $id }}'].quantity) : ''">
                        </div>
                        <div class="hidden md:block w-1/6 text-center text-sm">
                            <form action="{{ route('cart.remove') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="id" value="{{ $id }}">
                                <button type="submit" class="text-gray-800 hover:text-primary transition">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Cart Footer -->
            <div class="bg-white shadow-[0_-4px_10px_-4px_rgba(0,0,0,0.1)] md:shadow-sm sticky bottom-0 border-t border-gray-200 p-3 md:p-4 flex items-center justify-between z-40">
                <div class="flex items-center shrink-0 gap-2">
                    <input type="checkbox" class="mr-1 md:mr-2 w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary cursor-pointer"
                        :checked="allSelected" @click="toggleAll()">
                    <span class="text-xs md:text-sm text-gray-600 cursor-pointer hidden md:inline">
                        Pilih Semua (<span x-text="selectedItems.length"></span>)
                    </span>
                    <span class="text-xs text-gray-600 cursor-pointer md:hidden">Semua</span>

                    <!-- Bulk Delete Button -->
                    <button type="button"
                        @click="removeSelected()"
                        :class="selectedItems.length > 0 ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'"
                        class="ml-2 md:ml-4 text-xs md:text-sm text-red-500 hover:text-red-700 font-medium border border-red-300 px-2 md:px-3 py-1 rounded transition">
                        Hapus (<span x-text="selectedItems.length"></span>)
                    </button>
                </div>

                <div class="flex items-center gap-2 md:gap-6 ml-auto">
                    <div class="text-right flex flex-col md:flex-row md:items-center">
                        <span class="text-[10px] md:text-sm text-gray-600">
                            Total <span class="hidden md:inline">(<span x-text="selectedItems.length"></span> produk)</span>:
                        </span>
                        <span class="text-sm md:text-2xl text-primary font-medium md:ml-2"
                            x-text="'Rp' + formatRupiah(selectedTotalAmount)"></span>
                    </div>
                    <!-- Checkout button as form to pass selected IDs -->
                    <form id="checkoutForm" action="{{ route('checkout.select') }}" method="POST">
                        @csrf
                        <!-- selected ids will be injected here dynamically -->
                    </form>
                    <button type="button" @click="goCheckout()"
                        class="px-4 md:px-10 py-2 md:py-3 bg-primary text-white text-xs md:text-base font-medium rounded hover:brightness-110 transition shadow-sm whitespace-nowrap">
                        Checkout
                    </button>
                </div>
            </div>

            <!-- Hidden bulk remove form -->
            <form id="bulkRemoveForm" action="{{ route('cart.remove') }}" method="POST" style="display:none;">
                @csrf
                <!-- ids[] will be injected dynamically -->
            </form>

        @else
            <!-- Empty Cart -->
            <div class="bg-white rounded shadow-sm p-16 text-center flex flex-col items-center justify-center">
                <span class="material-symbols-outlined text-gray-300 text-9xl mb-4">shopping_cart</span>
                <p class="text-gray-500 mb-6 text-lg">Keranjang belanjamu kosong</p>
                <a href="{{ route('products.index') }}" class="px-10 py-3 bg-primary text-white font-medium rounded hover:brightness-110 transition shadow-sm">
                    Belanja Sekarang
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

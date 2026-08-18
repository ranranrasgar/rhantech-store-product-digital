@extends('layouts.tenant')

@section('title', 'Riwayat Penjualan')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-8 bg-[#f8fafc] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] transition-colors duration-200">
    <div class="max-w-7xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
                    Riwayat Penjualan
                </h1>
                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Pantau transaksi pelanggan, status verifikasi pembayaran, dan pesanan produk digital.
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('tenant.payouts.index') }}" class="px-4 py-2.5 rounded-xl bg-white dark:bg-[#111726] border border-slate-200 dark:border-[#222f49] hover:bg-slate-50 dark:hover:bg-[#161f33] text-slate-700 dark:text-slate-200 text-xs md:text-sm font-semibold transition-all shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] text-emerald-500">account_balance_wallet</span>
                    Pencairan Saldo
                </a>
            </div>
        </div>

        <!-- Main Card Container -->
        <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl shadow-sm overflow-hidden">
            
            <!-- Filter Tabs -->
            <div class="border-b border-slate-100 dark:border-[#222f49] px-6 flex items-center gap-8 overflow-x-auto hide-scrollbar bg-slate-50/50 dark:bg-[#0c1220]/50">
                <a href="{{ route('tenant.orders.index', array_merge(request()->query(), ['tab' => 'all', 'page' => null])) }}" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'all' ? 'text-sky-600 dark:text-sky-400 border-sky-600 dark:border-sky-400' : 'text-slate-500 dark:text-slate-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200' }}">
                    Semua Transaksi
                </a>
                <a href="{{ route('tenant.orders.index', array_merge(request()->query(), ['tab' => 'completed', 'page' => null])) }}" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'completed' ? 'text-sky-600 dark:text-sky-400 border-sky-600 dark:border-sky-400' : 'text-slate-500 dark:text-slate-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200' }}">
                    Sukses (Paid / Unduh)
                </a>
                <a href="{{ route('tenant.orders.index', array_merge(request()->query(), ['tab' => 'pending', 'page' => null])) }}" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'pending' ? 'text-sky-600 dark:text-sky-400 border-sky-600 dark:border-sky-400' : 'text-slate-500 dark:text-slate-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200' }}">
                    Menunggu Pembayaran
                </a>
                <a href="{{ route('tenant.orders.index', array_merge(request()->query(), ['tab' => 'cancelled', 'page' => null])) }}" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'cancelled' ? 'text-sky-600 dark:text-sky-400 border-sky-600 dark:border-sky-400' : 'text-slate-500 dark:text-slate-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200' }}">
                    Dibatalkan / Kedaluwarsa
                </a>
            </div>

            <!-- Search & Filters Row -->
            <div class="p-5 md:p-6 border-b border-slate-100 dark:border-[#222f49]">
                <form method="GET" action="{{ route('tenant.orders.index') }}" class="flex flex-col md:flex-row items-stretch md:items-center gap-3">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    
                    <div class="flex items-center flex-1">
                        <select name="search_type" class="px-3.5 py-2.5 text-xs md:text-sm bg-slate-100 dark:bg-[#161f33] border border-r-0 border-slate-200 dark:border-[#222f49] text-slate-700 dark:text-slate-200 rounded-l-xl focus:outline-none focus:border-sky-500 shrink-0">
                            <option value="invoice" {{ request('search_type') === 'invoice' ? 'selected' : '' }}>No. Invoice</option>
                            <option value="customer" {{ request('search_type') === 'customer' ? 'selected' : '' }}>Nama / Email Pembeli</option>
                        </select>
                        <input type="text" name="search_query" value="{{ request('search_query') }}" placeholder="Ketik kata kunci pencarian..." class="flex-1 px-4 py-2.5 text-xs md:text-sm bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-r-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 text-slate-900 dark:text-white transition-all">
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <select name="product_id" onchange="this.form.submit()" class="px-3.5 py-2.5 text-xs md:text-sm bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] text-slate-700 dark:text-slate-200 rounded-xl focus:outline-none focus:border-sky-500 transition-all">
                            <option value="">Semua Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                            @endforeach
                        </select>

                        <button type="submit" class="px-4 py-2.5 text-xs md:text-sm font-bold bg-sky-500 hover:bg-sky-400 text-white rounded-xl shadow-sm transition-colors">
                            Terapkan
                        </button>
                        <a href="{{ route('tenant.orders.index', ['tab' => $tab]) }}" class="px-4 py-2.5 text-xs md:text-sm font-semibold border border-slate-200 dark:border-[#222f49] text-slate-600 dark:text-slate-300 rounded-xl hover:bg-slate-100 dark:hover:bg-[#161f33] transition-colors">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Orders Table -->
            <div class="overflow-x-auto pb-12">
                <table class="w-full text-left text-xs md:text-sm whitespace-nowrap">
                    <thead class="bg-slate-50/80 dark:bg-[#0c1220]/80 border-b border-slate-100 dark:border-[#222f49] text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="p-4 md:px-6">No. Invoice & Tanggal</th>
                            <th class="p-4 md:px-6 min-w-[280px]">Produk Dipesan</th>
                            <th class="p-4 md:px-6">Pelanggan</th>
                            <th class="p-4 md:px-6 text-right">Pendapatan Toko</th>
                            <th class="p-4 md:px-6 text-center">Status</th>
                            <th class="p-4 md:px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-[#1d273d]">
                        @forelse($orders as $order)
                        @php
                            $tenantItems = $order->orderItems->filter(function($item) use ($store) {
                                return $item->product && $item->product->store_id == $store->id;
                            });
                            $firstItem = $tenantItems->first() ?? $order->orderItems->first();
                            $firstProduct = $firstItem ? $firstItem->product : $order->product;
                            $tenantTotal = $tenantItems->isNotEmpty() ? $tenantItems->sum(function($item) { return $item->price * $item->quantity; }) : $order->amount;
                        @endphp
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-[#151e30]/50 transition-colors">
                            
                            <!-- Invoice & Date -->
                            <td class="p-4 md:px-6">
                                <div class="font-mono font-bold text-slate-900 dark:text-white text-xs">
                                    {{ $order->invoice_number }}
                                </div>
                                <div class="text-[11px] text-slate-400 mt-1">
                                    {{ $order->created_at->format('d M Y, H:i') }} WIB
                                </div>
                            </td>

                            <!-- Products -->
                            <td class="p-4 md:px-6 whitespace-normal min-w-[280px]">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 overflow-hidden shrink-0 flex items-center justify-center">
                                        @if($firstProduct && $firstProduct->images->count() > 0)
                                            @php $img = $firstProduct->images->where('is_main', true)->first() ?? $firstProduct->images->first(); @endphp
                                            <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="material-symbols-outlined text-slate-400 text-[20px]">inventory_2</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-slate-900 dark:text-white line-clamp-1 leading-snug text-xs md:text-sm">
                                            @if($tenantItems->count() > 1)
                                                {{ $firstProduct->name ?? 'Produk Digital' }} <span class="text-xs font-semibold text-sky-600 dark:text-sky-400">(+{{ $tenantItems->count() - 1 }} lainnya)</span>
                                            @else
                                                {{ $firstProduct->name ?? 'Produk Digital' }}
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-slate-400 mt-0.5 font-mono">
                                            {{ $firstItem ? 'Qty: ' . $firstItem->quantity . 'x' : '1 item' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Customer -->
                            <td class="p-4 md:px-6">
                                <div class="font-bold text-slate-900 dark:text-white text-xs md:text-sm flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[15px] text-slate-400">person</span>
                                    {{ $order->customer_name }}
                                </div>
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    {{ $order->customer_email }}
                                </div>
                            </td>

                            <!-- Tenant Earning Amount -->
                            <td class="p-4 md:px-6 text-right">
                                <div class="font-extrabold text-slate-900 dark:text-white text-sm">
                                    Rp {{ number_format($tenantTotal, 0, ',', '.') }}
                                </div>
                                <div class="text-[10px] text-slate-400 font-semibold">
                                    Saldo Masuk
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="p-4 md:px-6 text-center">
                                @if($order->status === 'paid' || $order->status === 'downloaded')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Sukses
                                    </span>
                                @elseif($order->status === 'failed')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-800/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Gagal
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="p-4 md:px-6 text-center">
                                <div class="relative inline-block text-left" x-data="{ open: false }">
                                    <button @click="open = !open" @click.away="open = false" type="button" class="inline-flex justify-center w-8 h-8 items-center rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">more_vert</span>
                                    </button>

                                    <div x-show="open" x-transition class="origin-top-right absolute right-0 mt-2 w-48 rounded-xl shadow-lg bg-white dark:bg-slate-800 ring-1 ring-black ring-opacity-5 divide-y divide-slate-100 dark:divide-slate-700 focus:outline-none z-50 text-left" style="display: none;">
                                        <div class="py-1">
                                            <button @click="open = false; $dispatch('open-edit-order-modal', { id: {{ $order->id }}, name: '{{ addslashes($order->customer_name) }}', email: '{{ addslashes($order->customer_email) }}', phone: '{{ addslashes($order->customer_phone ?? '') }}' })" class="w-full text-left group flex items-center px-4 py-2 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-sky-600 dark:hover:text-sky-400">
                                                <span class="material-symbols-outlined mr-3 text-[16px] text-slate-400 group-hover:text-sky-500">edit</span>
                                                Edit Pesanan
                                            </button>
                                        </div>
                                        @if($order->status === 'pending')
                                        <div class="py-1">
                                            <form action="{{ route('tenant.orders.mark_paid', $order) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" onclick="return confirm('Tandai pesanan ini sebagai Lunas? Email berisi link produk akan otomatis dikirim ke pembeli.')" class="w-full text-left group flex items-center px-4 py-2 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-emerald-600 dark:hover:text-emerald-400">
                                                    <span class="material-symbols-outlined mr-3 text-[16px] text-slate-400 group-hover:text-emerald-500">check_circle</span>
                                                    Tandai Lunas
                                                </button>
                                            </form>
                                        </div>
                                        @elseif(in_array($order->status, ['paid', 'downloaded']))
                                        <div class="py-1">
                                            <form action="{{ route('tenant.orders.resend_email', $order) }}" method="POST">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Kirim ulang email berisi link produk ke {{ $order->customer_email }}?')" class="w-full text-left group flex items-center px-4 py-2 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-sky-600 dark:hover:text-sky-400">
                                                    <span class="material-symbols-outlined mr-3 text-[16px] text-slate-400 group-hover:text-sky-500">forward_to_inbox</span>
                                                    Kirim Ulang Email
                                                </button>
                                            </form>
                                        </div>
                                        @endif
                                        <div class="py-1">
                                            <form action="{{ route('tenant.orders.destroy', $order) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus pesanan ini secara permanen?')" class="w-full text-left group flex items-center px-4 py-2 text-xs text-slate-700 dark:text-slate-200 hover:bg-rose-50 dark:hover:bg-rose-900/20 hover:text-rose-600 dark:hover:text-rose-400">
                                                    <span class="material-symbols-outlined mr-3 text-[16px] text-slate-400 group-hover:text-rose-500">delete</span>
                                                    Hapus Pesanan
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-16 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                    <div class="w-16 h-16 rounded-2xl bg-sky-500/10 text-sky-500 flex items-center justify-center mb-4">
                                        <span class="material-symbols-outlined text-[32px]">receipt_long</span>
                                    </div>
                                    <h3 class="font-bold text-base text-slate-800 dark:text-white mb-1">Belum ada riwayat pesanan</h3>
                                    <p class="text-xs text-slate-400">Pesanan dari pembeli akan otomatis tercatat dan masuk ke daftar ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer Pagination -->
            @if($orders->hasPages())
            <div class="p-5 border-t border-slate-100 dark:border-[#222f49] flex justify-center">
                {{ $orders->links() }}
            </div>
            @endif

        </div>

    </div>
</div>

<!-- Edit Order Modal -->
<div x-data="{ 
        isOpen: false, 
        orderId: '', 
        customerName: '', 
        customerEmail: '', 
        customerPhone: '',
        init() {
            window.addEventListener('open-edit-order-modal', event => {
                this.orderId = event.detail.id;
                this.customerName = event.detail.name;
                this.customerEmail = event.detail.email;
                this.customerPhone = event.detail.phone;
                this.isOpen = true;
            });
        }
    }" 
    x-show="isOpen" 
    style="display: none;" 
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="isOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/75 transition-opacity" @click="isOpen = false" aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="isOpen" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             class="inline-block align-bottom bg-white dark:bg-[#111726] rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-slate-200 dark:border-[#222f49]">
            
            <form :action="`/tenant/orders/${orderId}`" method="POST">
                @csrf
                @method('PUT')
                
                <div class="px-6 py-5 border-b border-slate-100 dark:border-[#222f49] flex justify-between items-center bg-slate-50/50 dark:bg-[#0c1220]/50">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-sky-500">edit_document</span>
                        Edit Detail Pesanan
                    </h3>
                    <button type="button" @click="isOpen = false" class="text-slate-400 hover:text-slate-500 focus:outline-none">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                
                <div class="p-6 space-y-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Nama Pembeli <span class="text-rose-500">*</span></label>
                        <input type="text" name="customer_name" x-model="customerName" required class="w-full px-3.5 py-2.5 text-sm bg-white dark:bg-[#161f33] border border-slate-200 dark:border-[#222f49] rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 text-slate-900 dark:text-white transition-all">
                    </div>
                    
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Email Pembeli <span class="text-rose-500">*</span></label>
                        <input type="email" name="customer_email" x-model="customerEmail" required class="w-full px-3.5 py-2.5 text-sm bg-white dark:bg-[#161f33] border border-slate-200 dark:border-[#222f49] rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 text-slate-900 dark:text-white transition-all">
                        <p class="text-[11px] text-slate-500 mt-1">Ubah email ini jika pembeli salah mengetikkan alamat email mereka saat memesan.</p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Nomor WhatsApp (Opsional)</label>
                        <input type="text" name="customer_phone" x-model="customerPhone" class="w-full px-3.5 py-2.5 text-sm bg-white dark:bg-[#161f33] border border-slate-200 dark:border-[#222f49] rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 text-slate-900 dark:text-white transition-all">
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-slate-50/80 dark:bg-[#0c1220]/80 border-t border-slate-100 dark:border-[#222f49] flex justify-end gap-3">
                    <button type="button" @click="isOpen = false" class="px-4 py-2.5 bg-white dark:bg-[#161f33] border border-slate-200 dark:border-[#222f49] rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#1c273f] transition-all">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-sm font-semibold shadow-sm shadow-sky-600/20 transition-all flex items-center gap-2">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

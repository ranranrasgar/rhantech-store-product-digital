@extends('layouts.tenant')

@section('title', 'WhatsApp Broadcast')

@section('content')
<div class="p-4 md:p-6 max-w-5xl mx-auto w-full" x-data="broadcastApp()">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                WhatsApp Broadcast 
                <span class="bg-amber-500 text-white text-[10px] px-2 py-0.5 rounded shadow-sm font-black tracking-wider uppercase">PRO</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Kirim pesan promo massal ke semua pelanggan Anda.</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-sky-100 text-sky-600 dark:bg-sky-900/30 dark:text-sky-400 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-2xl">group</span>
            </div>
            <div>
                <div class="text-slate-500 dark:text-slate-400 text-xs font-bold mb-1">Total Pelanggan</div>
                <div class="text-2xl font-black text-slate-800 dark:text-white">{{ $customers->count() }}</div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-2xl">mark_email_read</span>
            </div>
            <div>
                <div class="text-slate-500 dark:text-slate-400 text-xs font-bold mb-1">Pesan Berhasil</div>
                <div class="text-2xl font-black text-slate-800 dark:text-white">0</div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-2xl">generating_tokens</span>
            </div>
            <div>
                <div class="text-slate-500 dark:text-slate-400 text-xs font-bold mb-1">Sisa Kuota</div>
                <div class="text-2xl font-black text-slate-800 dark:text-white">&infin;</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Editor -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 flex items-center justify-between">
                    <h2 class="font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-sky-500">edit_document</span>
                        Tulis Pesan
                    </h2>
                    <button @click="insertVariable('[NAMA]')" class="text-xs bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 px-2 py-1 rounded transition-colors font-medium">
                        + Sisipkan [NAMA]
                    </button>
                </div>
                <div class="p-5">
                    <textarea 
                        x-model="message"
                        rows="8" 
                        class="w-full bg-slate-50 dark:bg-[#0d1117] border border-slate-200 dark:border-slate-700 rounded-xl p-4 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all resize-none"
                        placeholder="Halo [NAMA], ada promo diskon 50% nih di toko kami..."></textarea>
                    
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-medium"><span x-text="message.length"></span> karakter</span>
                        
                        <button @click="sendBroadcast()" :disabled="isSending || message.trim() === '' || selectedCustomers.length === 0" class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 disabled:opacity-50 disabled:cursor-not-allowed text-white text-sm font-bold rounded-xl shadow-md transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]" x-show="!isSending">send</span>
                            <span class="material-symbols-outlined text-[18px] animate-spin" x-show="isSending">progress_activity</span>
                            <span x-text="isSending ? 'Mengirim...' : 'Kirim Broadcast'"></span>
                        </button>
                    </div>
                    
                    <!-- Alert Status -->
                    <div x-show="showStatus" x-transition class="mt-4 p-3 rounded-lg flex items-start gap-2" :class="statusType === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200'">
                        <span class="material-symbols-outlined text-[18px]" x-text="statusType === 'success' ? 'check_circle' : 'error'"></span>
                        <span class="text-sm font-medium" x-text="statusMessage"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Target -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col h-[500px]">
                <div class="p-4 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="font-bold text-slate-800 dark:text-white text-sm">Penerima (<span x-text="selectedCustomers.length"></span>/<span x-text="customers.length"></span>)</h2>
                        <button @click="selectAll()" class="text-xs text-sky-500 font-bold hover:underline" x-text="selectedCustomers.length === customers.length ? 'Batal Semua' : 'Pilih Semua'"></button>
                    </div>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
                        <input type="text" x-model="searchQuery" placeholder="Cari pelanggan..." class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-[#0d1117] border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sky-500 transition-all">
                    </div>
                </div>
                
                <div class="flex-1 overflow-y-auto p-2 space-y-1 custom-scrollbar">
                    <template x-for="(customer, index) in filteredCustomers" :key="index">
                        <label class="flex items-center gap-3 p-2 hover:bg-slate-50 dark:hover:bg-slate-700/50 rounded-lg cursor-pointer transition-colors border border-transparent hover:border-slate-200 dark:hover:border-slate-700">
                            <input type="checkbox" :value="customer.phone" x-model="selectedCustomers" class="w-4 h-4 text-emerald-500 rounded border-slate-300 focus:ring-emerald-500 focus:ring-2">
                            <div class="min-w-0">
                                <div class="text-sm font-bold text-slate-800 dark:text-white truncate" x-text="customer.name || 'Pelanggan'"></div>
                                <div class="text-xs text-slate-500 dark:text-slate-400 font-mono" x-text="customer.phone"></div>
                            </div>
                        </label>
                    </template>
                    <div x-show="filteredCustomers.length === 0" class="p-4 text-center text-slate-500 text-xs">
                        Pelanggan tidak ditemukan.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('broadcastApp', () => ({
        message: 'Halo [NAMA],\n\nTerima kasih telah berbelanja di {{ addslashes($store->name) }}.\n\nKami ada promo spesial untuk Anda! Gunakan kode voucher PROMO10 untuk diskon 10% di pembelian berikutnya.\n\nKlik link di bawah untuk belanja:\n{{ route("store.show", $store->slug) }}',
        customers: @json($customers),
        selectedCustomers: [],
        searchQuery: '',
        isSending: false,
        showStatus: false,
        statusType: 'success',
        statusMessage: '',

        init() {
            // Select all by default
            this.selectedCustomers = this.customers.map(c => c.customer_phone).filter(Boolean);
        },

        get filteredCustomers() {
            if (this.searchQuery === '') {
                return this.customers;
            }
            return this.customers.filter(c => {
                return (c.customer_name && c.customer_name.toLowerCase().includes(this.searchQuery.toLowerCase())) || 
                       (c.customer_phone && c.customer_phone.includes(this.searchQuery));
            });
        },

        selectAll() {
            if (this.selectedCustomers.length === this.customers.length) {
                this.selectedCustomers = [];
            } else {
                this.selectedCustomers = this.customers.map(c => c.customer_phone).filter(Boolean);
            }
        },

        insertVariable(variable) {
            const textarea = document.querySelector('textarea');
            const startPos = textarea.selectionStart;
            const endPos = textarea.selectionEnd;
            this.message = this.message.substring(0, startPos) + variable + this.message.substring(endPos, this.message.length);
            
            setTimeout(() => {
                textarea.focus();
                textarea.selectionStart = startPos + variable.length;
                textarea.selectionEnd = startPos + variable.length;
            }, 10);
        },

        sendBroadcast() {
            if (this.message.trim() === '' || this.selectedCustomers.length === 0) return;
            
            this.isSending = true;
            this.showStatus = false;

            fetch('{{ route("tenant.broadcast.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    message: this.message,
                    recipients: this.selectedCustomers
                })
            })
            .then(res => res.json())
            .then(data => {
                this.isSending = false;
                this.showStatus = true;
                if (data.success) {
                    this.statusType = 'success';
                    this.statusMessage = data.message;
                    // Reset selected after success
                    this.selectedCustomers = [];
                    this.message = '';
                } else {
                    this.statusType = 'error';
                    this.statusMessage = data.message || 'Terjadi kesalahan.';
                }
            })
            .catch(err => {
                this.isSending = false;
                this.showStatus = true;
                this.statusType = 'error';
                this.statusMessage = 'Gagal terhubung ke server.';
                console.error(err);
            });
        }
    }));
});
</script>
@endpush
@endsection

@extends('layouts.tenant')

@section('title', 'Pembayaran Saldo Iklan - ' . $transaction->reference_no)

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-8 bg-[#f5f6f8] dark:bg-[#000000] text-[#09090b] dark:text-[#ededed] transition-colors duration-200 flex items-center justify-center min-h-[80vh]">
    <div class="max-w-md w-full mx-auto space-y-6">
        
        <!-- Breadcrumb / Back -->
        <div class="flex items-center gap-2 text-xs text-zinc-500 dark:text-zinc-400">
            <a href="{{ route('tenant.ads.top-up') }}" class="hover:text-[#ea580c] flex items-center gap-1 font-semibold transition-colors">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                Kembali ke Pilih Nominal
            </a>
            <span>/</span>
            <span class="text-zinc-800 dark:text-zinc-200 font-semibold">Pembayaran</span>
        </div>

        <!-- Payment Card -->
        <div class="bg-white dark:bg-[#000000] rounded-2xl border border-slate-200/90 dark:border-slate-800 p-6 md:p-8 text-center space-y-6">
            
            <div class="w-16 h-16 mx-auto rounded-2xl bg-zinc-100 dark:bg-zinc-900 text-zinc-800 dark:text-zinc-200 flex items-center justify-center">
                <span class="material-symbols-outlined text-4xl">qr_code_scanner</span>
            </div>

            <div>
                <h1 class="text-xl font-extrabold text-zinc-900 dark:text-zinc-100">
                    Selesaikan Pembayaran Saldo Iklan
                </h1>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 font-mono">
                    Ref: {{ $transaction->reference_no }}
                </p>
            </div>

            <!-- Detail Tagihan -->
            <div class="bg-slate-50 dark:bg-[#0c1220] rounded-xl p-4 text-left text-xs space-y-2.5 border border-slate-100 dark:border-slate-800">
                <div class="flex justify-between items-center text-zinc-600 dark:text-zinc-400">
                    <span>Toko</span>
                    <span class="font-bold text-zinc-900 dark:text-zinc-100">{{ $transaction->store->name ?? 'Toko Saya' }}</span>
                </div>
                <div class="flex justify-between items-center text-zinc-600 dark:text-zinc-400">
                    <span>Nominal Saldo Iklan</span>
                    <span class="font-bold text-zinc-900 dark:text-zinc-100">Rp{{ number_format($transaction->amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-zinc-600 dark:text-zinc-400">
                    <span>PPN (11%)</span>
                    <span class="font-bold text-zinc-900 dark:text-zinc-100">Rp{{ number_format($transaction->tax_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center pt-2.5 border-t border-zinc-200 dark:border-zinc-800 text-sm font-extrabold">
                    <span class="text-zinc-900 dark:text-zinc-100">Total Tagihan</span>
                    <span class="text-zinc-900 dark:text-zinc-100 text-base font-black">Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <button id="pay-button" class="w-full py-3.5 px-6 rounded-xl bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white font-bold text-sm transition-all active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
                <span class="material-symbols-outlined text-[20px]">payments</span>
                Bayar Sekarang (QRIS / Transfer)
            </button>

            <p class="text-[11px] text-slate-400 flex items-center justify-center gap-1">
                <span class="material-symbols-outlined text-[14px] text-emerald-500">lock</span>
                Pembayaran Aman Terenkripsi via Midtrans
            </p>

        </div>

    </div>
</div>

@if(config('midtrans.is_production'))
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@else
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endif

<script type="text/javascript">
    document.getElementById('pay-button').onclick = function(){
        if (!'{{ $transaction->snap_token }}') {
            alert('Snap Token tidak tersedia. Silakan hubungi admin atau coba lagi.');
            return;
        }

        snap.pay('{{ $transaction->snap_token }}', {
            onSuccess: function(result){
                window.location.href = "{{ route('tenant.ads.finish-top-up', $transaction->reference_no) }}";
            },
            onPending: function(result){
                window.location.href = "{{ route('tenant.ads.finish-top-up', $transaction->reference_no) }}";
            },
            onError: function(result){
                alert("Pembayaran belum berhasil atau dibatalkan.");
                window.location.href = "{{ route('tenant.ads.top-up') }}";
            },
            onClose: function(){
                window.location.href = "{{ route('tenant.ads.finish-top-up', $transaction->reference_no) }}";
            }
        });
    };
    
    // Otomatis buka pop-up Snap saat halaman pertama kali dimuat
    window.onload = function() {
        document.getElementById('pay-button').click();
    };
</script>
@endsection

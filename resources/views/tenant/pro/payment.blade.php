@extends('layouts.tenant')

@section('title', 'Pembayaran Upgrade Toko PRO - ' . $subscription->reference_no)

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-8 bg-[#f5f6f8] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] transition-colors duration-200 flex items-center justify-center min-h-[80vh]">
    <div class="max-w-md w-full mx-auto space-y-6">
        
        <!-- Breadcrumb / Back -->
        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
            <a href="{{ route('tenant.pro.index') }}" class="hover:text-amber-500 flex items-center gap-1 font-semibold transition-colors">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                Kembali ke Layanan PRO
            </a>
            <span>/</span>
            <span class="text-slate-800 dark:text-slate-200 font-semibold">Pembayaran QRIS</span>
        </div>

        <!-- Payment Card -->
        <div class="bg-white dark:bg-[#161b22] rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-sm p-6 md:p-8 text-center space-y-6">
            
            <div class="w-16 h-16 mx-auto rounded-2xl bg-amber-50 dark:bg-amber-950/40 text-amber-500 flex items-center justify-center">
                <span class="material-symbols-outlined text-4xl">workspace_premium</span>
            </div>

            <div>
                <h1 class="text-xl font-extrabold text-slate-900 dark:text-white">
                    Selesaikan Upgrade Toko PRO
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-mono">
                    No. Invoice: <strong class="text-slate-700 dark:text-slate-300">{{ $subscription->reference_no }}</strong>
                </p>
            </div>

            <!-- Detail Tagihan -->
            <div class="bg-slate-50 dark:bg-[#0c1220] rounded-xl p-4 text-left text-xs space-y-2.5 border border-slate-100 dark:border-slate-800">
                <div class="flex justify-between items-center text-slate-600 dark:text-slate-400">
                    <span>Toko</span>
                    <span class="font-bold text-slate-900 dark:text-white">{{ $store->name ?? 'Toko Anda' }}</span>
                </div>
                <div class="flex justify-between items-center text-slate-600 dark:text-slate-400">
                    <span>Paket PRO</span>
                    <span class="font-bold text-amber-600 dark:text-amber-400 capitalize">
                        @if($subscription->plan === 'monthly')
                            Bulanan (1 Bulan)
                        @elseif($subscription->plan === 'yearly')
                            Tahunan (1 Tahun)
                        @else
                            Lifetime (Akses Selamanya)
                        @endif
                    </span>
                </div>
                <div class="flex justify-between items-center text-slate-600 dark:text-slate-400">
                    <span>Metode Pembayaran</span>
                    <span class="font-bold text-slate-900 dark:text-white">QRIS / Midtrans Instan</span>
                </div>
                <div class="flex justify-between items-center pt-2.5 border-t border-slate-200 dark:border-slate-700 text-sm font-extrabold">
                    <span class="text-slate-900 dark:text-white">Total Bayar</span>
                    <span class="text-amber-600 dark:text-amber-400 text-base">Rp {{ number_format($subscription->amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <button id="pay-button" class="w-full py-3.5 px-6 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-sm shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer">
                <span class="material-symbols-outlined text-[20px]">qr_code_2</span>
                Bayar Sekarang via QRIS / E-Wallet
            </button>

            <div class="space-y-1">
                <p class="text-[11px] text-slate-400 flex items-center justify-center gap-1">
                    <span class="material-symbols-outlined text-[14px] text-emerald-500">verified_user</span>
                    Dukungan QRIS Nasional (GoPay, OVO, DANA, ShopeePay, BCA, Mandiri, dll.)
                </p>
                <p class="text-[10px] text-slate-400">
                    Callback otomatis tersinkronisasi dengan awalan <code class="font-mono font-bold text-slate-500 dark:text-slate-300">RHN-</code>
                </p>
            </div>

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
        if (!'{{ $subscription->snap_token }}') {
            alert('Snap Token tidak tersedia. Silakan muat ulang halaman atau hubungi admin.');
            return;
        }

        snap.pay('{{ $subscription->snap_token }}', {
            onSuccess: function(result){
                window.location.href = "{{ route('tenant.pro.finish-payment', $subscription->reference_no) }}";
            },
            onPending: function(result){
                window.location.href = "{{ route('tenant.pro.finish-payment', $subscription->reference_no) }}";
            },
            onError: function(result){
                alert("Pembayaran belum berhasil atau dibatalkan.");
                window.location.href = "{{ route('tenant.pro.index') }}";
            },
            onClose: function(){
                window.location.href = "{{ route('tenant.pro.finish-payment', $subscription->reference_no) }}";
            }
        });
    };
    
    // Buka pop-up Snap otomatis saat halaman selesai dimuat
    window.onload = function() {
        document.getElementById('pay-button').click();
    };
</script>
@endsection

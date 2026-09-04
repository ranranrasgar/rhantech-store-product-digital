@extends('layouts.public')
@section('title', 'Payment - ' . $order->invoice_number)
@section('content')
<main class="pt-24 pb-2xl min-h-screen bg-surface dark:bg-surface-container-lowest flex items-center justify-center">
    <div class="max-w-md w-full px-lg text-center">
        
        <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-lg ">
            <span class="material-symbols-outlined text-5xl text-primary mb-4">payments</span>
            <h1 class="font-headline-md font-black text-on-surface mb-2">Complete Your Payment</h1>
            <p class="text-on-surface-variant font-body-sm mb-6">Order <strong>{{ $order->invoice_number }}</strong></p>
            
            <div class="text-left bg-surface-container-high rounded-lg p-4 mb-6">
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-on-surface-variant">Product</span>
                    <span class="font-bold text-on-surface line-clamp-1 max-w-[150px]">
                        @if($order->orderItems->count() > 1)
                            {{ $order->orderItems->first()->product->name ?? 'Product' }} (+{{ $order->orderItems->count() - 1 }} lainnya)
                        @else
                            {{ $order->orderItems->first()->product->name ?? 'Product' }}
                        @endif
                    </span>
                </div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-on-surface-variant">Email</span>
                    <span class="font-bold text-on-surface">{{ $order->customer_email }}</span>
                </div>
                <div class="flex justify-between text-sm pt-2 border-t border-outline-variant">
                    <span class="text-on-surface-variant">Total Amount</span>
                    <span class="font-black text-primary">Rp {{ number_format($order->amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <button id="pay-button" class="w-full bg-primary text-white font-bold font-label-lg py-4 rounded-md hover:brightness-110 transition shadow-md">
                Pay Now
            </button>
            <p class="text-xs text-on-surface-variant mt-4">Secured by Midtrans</p>
        </div>
    </div>
</main>

@if(config('midtrans.is_production'))
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@else
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endif

<script type="text/javascript">
    document.getElementById('pay-button').onclick = function(){
        snap.pay('{{ $order->snap_token }}', {
            onSuccess: function(result){
                window.location.href = "{{ route('checkout.finish', $order->invoice_number) }}";
            },
            onPending: function(result){
                window.location.href = "{{ route('checkout.finish', $order->invoice_number) }}";
            },
            onError: function(result){
                alert("Pembayaran gagal!");
                window.location.href = "{{ route('tenant.purchases.index') }}";
            },
            onClose: function(){
                window.location.href = "{{ route('checkout.finish', $order->invoice_number) }}";
            }
        });
    };
    
    // Auto click the pay button when page loads
    window.onload = function() {
        document.getElementById('pay-button').click();
    };
</script>
@endsection

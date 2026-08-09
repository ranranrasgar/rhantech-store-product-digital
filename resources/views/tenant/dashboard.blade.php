@extends('layouts.tenant')

@section('title', 'Tenant Dashboard')

@section('content')
<div class="mb-lg">
    <h2 class="font-headline-md text-headline-md font-bold text-on-background mb-xs">Welcome to your Dashboard</h2>
    <p class="font-body-md text-on-surface-variant">Here is a summary of your store's performance.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-md">
    <div class="bg-surface-container rounded-xl p-md border border-outline-variant/30 flex items-center gap-md">
        <div class="w-12 h-12 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center">
            <span class="material-symbols-outlined text-[1.5rem]">inventory_2</span>
        </div>
        <div>
            <p class="font-label-md text-on-surface-variant mb-xs">Total Products</p>
            <p class="font-headline-sm font-bold text-on-surface">{{ $totalProducts }}</p>
        </div>
    </div>
    
    <div class="bg-surface-container rounded-xl p-md border border-outline-variant/30 flex items-center gap-md">
        <div class="w-12 h-12 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center">
            <span class="material-symbols-outlined text-[1.5rem]">account_balance_wallet</span>
        </div>
        <div>
            <p class="font-label-md text-on-surface-variant mb-xs">Total Balance</p>
            <p class="font-headline-sm font-bold text-on-surface">Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
        </div>
    </div>
</div>
@endsection

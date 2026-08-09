@extends('layouts.tenant')

@section('title', 'Payout Requests')

@section('content')
<div class="mb-lg flex flex-col sm:flex-row sm:items-end justify-between gap-md">
    <div>
        <h2 class="font-headline-md text-headline-md font-bold text-on-background mb-xs">Payout Requests</h2>
        <p class="font-body-md text-on-surface-variant">Manage your earnings and request payouts.</p>
    </div>
    <div class="bg-primary-container text-on-primary-container px-md py-sm rounded-lg flex flex-col items-end">
        <span class="font-label-sm text-on-primary-container/80">Available Balance</span>
        <span class="font-headline-sm font-bold">Rp {{ number_format($store->balance, 0, ',', '.') }}</span>
    </div>
</div>

<div class="bg-surface-container rounded-xl border border-outline-variant/30 overflow-hidden mb-xl">
    <div class="p-md border-b border-outline-variant/30">
        <h3 class="font-title-lg font-bold text-on-surface">Request New Payout</h3>
    </div>
    <div class="p-md">
        @if (session('success'))
            <div class="bg-success/10 text-success p-sm rounded-lg mb-md">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-error/10 text-error p-sm rounded-lg mb-md">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('tenant.payouts.store') }}" method="POST" class="flex flex-col sm:flex-row gap-md items-end">
            @csrf
            <div class="flex-1 w-full">
                <label for="amount" class="block font-label-md text-on-surface mb-xs">Amount (Rp)</label>
                <input type="number" id="amount" name="amount" min="10000" max="{{ $store->balance }}" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors" required>
                @error('amount')
                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="w-full sm:w-auto bg-primary hover:bg-primary-dark text-on-primary font-label-lg px-lg py-sm rounded-full transition-colors flex justify-center items-center gap-2" {{ $store->balance < 10000 ? 'disabled' : '' }}>
                <span class="material-symbols-outlined text-[1.25rem]">payments</span>
                Request
            </button>
        </form>
        @if(empty($store->bank_account_info))
            <p class="text-warning-dark text-sm mt-md">You need to set up your Bank Account Info in the <a href="{{ route('tenant.store.index') }}" class="underline font-bold">Store Profile</a> before requesting a payout.</p>
        @endif
    </div>
</div>

<div class="bg-surface-container rounded-xl border border-outline-variant/30 overflow-hidden">
    <div class="p-md border-b border-outline-variant/30">
        <h3 class="font-title-lg font-bold text-on-surface">Payout History</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-variant/30 text-on-surface-variant font-label-md">
                    <th class="p-md font-medium">Date</th>
                    <th class="p-md font-medium">Amount</th>
                    <th class="p-md font-medium">Status</th>
                    <th class="p-md font-medium">Notes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/30 text-on-surface font-body-md">
                @forelse ($payouts as $payout)
                    <tr class="hover:bg-surface-variant/10 transition-colors">
                        <td class="p-md">{{ $payout->created_at->format('d M Y H:i') }}</td>
                        <td class="p-md">Rp {{ number_format($payout->amount, 0, ',', '.') }}</td>
                        <td class="p-md">
                            @if($payout->status === 'pending')
                                <span class="px-2 py-1 bg-warning/20 text-warning-dark rounded-full text-xs font-bold uppercase tracking-wider">Pending</span>
                            @elseif($payout->status === 'approved')
                                <span class="px-2 py-1 bg-success/20 text-success rounded-full text-xs font-bold uppercase tracking-wider">Approved</span>
                            @elseif($payout->status === 'rejected')
                                <span class="px-2 py-1 bg-error/20 text-error rounded-full text-xs font-bold uppercase tracking-wider">Rejected</span>
                            @endif
                        </td>
                        <td class="p-md">{{ $payout->notes ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-md text-center text-on-surface-variant">No payout requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Manage Stores')

@section('content')
<div class="p-lg">
    <div class="flex justify-between items-center mb-lg">
        <div>
            <h2 class="font-headline-sm font-bold text-on-surface">Manage Stores</h2>
            <p class="font-body-md text-on-surface-variant">List of all tenant stores in the system.</p>
        </div>
    </div>

    <div class="bg-surface-container-lowest rounded-lg border border-outline-variant  overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant/50">
                        <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase">Store Name</th>
                        <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase">Owner</th>
                        <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase">Balance</th>
                        <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase">Bank Info</th>
                        <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase">Joined Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30">
                    @forelse ($stores as $store)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="p-4 font-body-md font-bold text-on-surface">{{ $store->name }}</td>
                            <td class="p-4 font-body-md">
                                <div class="text-on-surface">{{ $store->user->name ?? 'Unknown' }}</div>
                                <div class="text-sm text-on-surface-variant">{{ $store->user->email ?? '' }}</div>
                            </td>
                            <td class="p-4 font-body-md text-on-surface font-bold text-primary">Rp {{ number_format($store->balance, 0, ',', '.') }}</td>
                            <td class="p-4 font-body-md text-sm text-on-surface-variant whitespace-pre-wrap">{{ $store->bank_account_info ?? '-' }}</td>
                            <td class="p-4 font-body-md text-on-surface">{{ $store->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-on-surface-variant">No stores found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($stores->hasPages())
            <div class="p-4 border-t border-outline-variant/30">
                {{ $stores->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

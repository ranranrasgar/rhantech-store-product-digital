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
                        <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase">PRO Settings</th>
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
                            <td class="p-4 font-body-md text-on-surface">
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" class="px-3 py-1.5 rounded-full text-xs font-bold border transition-colors {{ $store->is_pro ? 'bg-amber-100 text-amber-700 border-amber-300 hover:bg-amber-200' : 'bg-surface-container-high text-on-surface-variant border-outline-variant hover:bg-surface-container-highest' }}">
                                        {{ $store->is_pro ? 'PRO Aktif' : 'Non-PRO' }}
                                    </button>
                                    
                                    <div x-show="open" @click.away="open = false" style="display: none;" class="absolute right-0 top-full mt-2 w-64 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-xl z-20 p-4">
                                        <form action="{{ route('admin.stores.update', $store->id) }}" method="POST" class="space-y-3">
                                            @csrf
                                            @method('PATCH')
                                            
                                            <div>
                                                <label class="block text-xs font-bold text-on-surface mb-1">Status Toko</label>
                                                <select name="is_pro" class="w-full text-sm bg-surface-container border border-outline-variant rounded-lg p-2 text-on-surface">
                                                    <option value="0" {{ !$store->is_pro ? 'selected' : '' }}>Reguler (Non-PRO)</option>
                                                    <option value="1" {{ $store->is_pro ? 'selected' : '' }}>PRO Aktif</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-on-surface mb-1">Plan</label>
                                                <input type="text" name="pro_plan" value="{{ $store->pro_plan }}" placeholder="Contoh: bulanan" class="w-full text-sm bg-surface-container border border-outline-variant rounded-lg p-2 text-on-surface">
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-on-surface mb-1">Berakhir Pada</label>
                                                <input type="date" name="pro_expires_at" value="{{ $store->pro_expires_at ? \Carbon\Carbon::parse($store->pro_expires_at)->format('Y-m-d') : '' }}" class="w-full text-sm bg-surface-container border border-outline-variant rounded-lg p-2 text-on-surface">
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-on-surface mb-1">Custom Payout Fee (%)</label>
                                                <input type="number" step="0.01" name="custom_payout_fee_percentage" value="{{ $store->custom_payout_fee_percentage }}" placeholder="Biarkan kosong utk default" class="w-full text-sm bg-surface-container border border-outline-variant rounded-lg p-2 text-on-surface">
                                                <p class="text-[10px] text-on-surface-variant mt-1">Kosong = otomatis (PRO: 1%, Reg: 2.5%)</p>
                                            </div>

                                            <div class="pt-2">
                                                <button type="submit" class="w-full bg-primary text-on-primary py-2 rounded-lg text-sm font-bold hover:bg-primary/90 transition-colors">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
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

@extends('layouts.admin')

@section('title', 'Manage Payout Requests')

@section('content')
<div class="p-lg">
    <div class="flex justify-between items-center mb-lg">
        <div>
            <h2 class="font-headline-sm font-bold text-on-surface">Payout Requests</h2>
            <p class="font-body-md text-on-surface-variant">Review and manage tenant payout requests.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-secondary-container text-on-secondary-container p-4 rounded-lg mb-lg border border-secondary/20">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant/50">
                        <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase">Date</th>
                        <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase">Store & Bank Info</th>
                        <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase">Amount</th>
                        <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase">Status</th>
                        <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30">
                    @forelse ($payouts as $payout)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="p-4 font-body-md text-on-surface">{{ $payout->created_at->format('d M Y H:i') }}</td>
                            <td class="p-4 font-body-md text-on-surface">
                                <span class="font-bold">{{ $payout->store->name ?? 'Unknown' }}</span><br>
                                <span class="text-sm text-on-surface-variant whitespace-pre-wrap">{{ $payout->store->bank_account_info ?? '-' }}</span>
                            </td>
                            <td class="p-4 font-body-md font-bold text-[#06B6D4]">Rp {{ number_format($payout->amount, 0, ',', '.') }}</td>
                            <td class="p-4 font-body-md">
                                @if($payout->status === 'pending')
                                    <span class="px-2 py-1 bg-[#fff8e1] text-[#f57f17] rounded-full text-xs font-bold uppercase tracking-wider">Pending</span>
                                @elseif($payout->status === 'approved')
                                    <span class="px-2 py-1 bg-[#e6f4ea] text-[#137333] rounded-full text-xs font-bold uppercase tracking-wider">Approved</span>
                                @elseif($payout->status === 'rejected')
                                    <span class="px-2 py-1 bg-[#fce8e6] text-[#c5221f] rounded-full text-xs font-bold uppercase tracking-wider">Rejected</span>
                                @endif
                            </td>
                            <td class="p-4 font-body-md">
                                @if($payout->status === 'pending')
                                    <form action="{{ route('admin.payouts.update', $payout->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to approve this payout? Make sure you have transferred the money.');">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="bg-[#e6f4ea] text-[#137333] hover:bg-[#137333] hover:text-white px-3 py-1 rounded-md text-sm font-bold transition-colors border border-[#137333]/20">Approve</button>
                                    </form>
                                    <button onclick="document.getElementById('reject-modal-{{ $payout->id }}').classList.remove('hidden')" class="bg-[#fce8e6] text-[#c5221f] hover:bg-[#c5221f] hover:text-white px-3 py-1 rounded-md text-sm font-bold transition-colors ml-2 border border-[#c5221f]/20">Reject</button>

                                    <!-- Reject Modal -->
                                    <div id="reject-modal-{{ $payout->id }}" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
                                        <div class="bg-surface p-xl rounded-2xl w-full max-w-md">
                                            <h3 class="font-title-lg font-bold text-on-surface mb-md">Reject Payout</h3>
                                            <form action="{{ route('admin.payouts.update', $payout->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <div class="mb-md">
                                                    <label class="block font-label-md text-on-surface mb-xs">Reason / Notes</label>
                                                    <textarea name="notes" rows="3" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors" required></textarea>
                                                </div>
                                                <div class="flex justify-end gap-3">
                                                    <button type="button" onclick="document.getElementById('reject-modal-{{ $payout->id }}').classList.add('hidden')" class="text-on-surface-variant hover:bg-surface-variant/20 px-4 py-2 rounded-lg font-label-lg transition-colors">Cancel</button>
                                                    <button type="submit" class="bg-error hover:bg-error-dark text-on-error px-4 py-2 rounded-lg font-label-lg shadow-sm transition-colors">Confirm Reject</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-on-surface-variant text-sm">{{ $payout->notes ?? '-' }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-on-surface-variant">No payout requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($payouts->hasPages())
            <div class="p-4 border-t border-outline-variant/30">
                {{ $payouts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

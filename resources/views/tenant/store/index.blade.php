@extends('layouts.tenant')

@section('title', 'My Store Profile')

@section('content')
<div class="mb-lg flex justify-between items-end">
    <div>
        <h2 class="font-headline-md text-headline-md font-bold text-on-background mb-xs">My Store Profile</h2>
        <p class="font-body-md text-on-surface-variant">Update your store information and payout bank details.</p>
    </div>
</div>

<div class="bg-surface-container rounded-xl border border-outline-variant/30 overflow-hidden">
    <div class="p-md">
        @if (session('success'))
            <div class="bg-success/10 text-success p-sm rounded-lg mb-md">
                {{ session('success') }}
            </div>
        @endif
        @if (session('warning'))
            <div class="bg-warning/10 text-warning-dark p-sm rounded-lg mb-md">
                {{ session('warning') }}
            </div>
        @endif

        <form action="{{ route('tenant.store.store') }}" method="POST">
            @csrf
            
            <div class="mb-md">
                <label for="name" class="block font-label-md text-on-surface mb-xs">Store Name</label>
                <input type="text" id="name" name="name" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors" value="{{ old('name', $store->name ?? '') }}" required>
                @error('name')
                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-md">
                <label for="description" class="block font-label-md text-on-surface mb-xs">Description</label>
                <textarea id="description" name="description" rows="4" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">{{ old('description', $store->description ?? '') }}</textarea>
                @error('description')
                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-md">
                <label for="bank_account_info" class="block font-label-md text-on-surface mb-xs">Bank Account / Payout Info</label>
                <textarea id="bank_account_info" name="bank_account_info" rows="3" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors" placeholder="e.g. BCA 123456789 a.n. John Doe">{{ old('bank_account_info', $store->bank_account_info ?? '') }}</textarea>
                <p class="font-label-sm text-on-surface-variant mt-1">This will be used for your payout requests.</p>
                @error('bank_account_info')
                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end pt-md">
                <button type="submit" class="bg-primary hover:bg-primary-dark text-on-primary font-label-lg px-xl py-sm rounded-full transition-colors shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[1.25rem]">save</span>
                    Save Profile
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

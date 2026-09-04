@extends('layouts.admin')
@section('title', 'Add Gateway App')

@section('content')
<div class="p-lg max-w-3xl mx-auto">
    <div class="flex items-center gap-4 mb-lg">
        <a href="{{ route('admin.gateway_apps.index') }}" class="p-2 bg-surface-container-low text-on-surface-variant hover:bg-surface-variant rounded-full transition-colors flex items-center justify-center" wire:navigate>
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h2 class="font-headline-sm font-bold text-on-surface">Add New Gateway App</h2>
    </div>

    <form action="{{ route('admin.gateway_apps.store') }}" method="POST" class="bg-surface-container-lowest p-lg rounded-lg border border-outline-variant  flex flex-col gap-lg">
        @csrf

        <div>
            <label for="name" class="block font-label-md font-bold text-on-surface mb-2">App Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="e.g., NOC System" class="w-full bg-surface-container-low border @error('name') border-error @else border-outline-variant @enderror rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" required>
            @error('name')
                <p class="text-error text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="prefix" class="block font-label-md font-bold text-on-surface mb-2">Order Prefix</label>
            <input type="text" name="prefix" id="prefix" value="{{ old('prefix') }}" placeholder="e.g., NOC-" class="w-full bg-surface-container-low border @error('prefix') border-error @else border-outline-variant @enderror rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" required>
            <p class="text-sm text-on-surface-variant mt-1">Anda bisa memasukkan beberapa awalan dipisah koma (contoh: <code>PLT-, INV-, NOC-</code>). Gunakan tanda bintang (<code>*</code>) agar aplikasi ini menjadi <b>Default/Fallback</b> untuk semua transaksi yang tidak memiliki prefix.</p>
            @error('prefix')
                <p class="text-error text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="callback_url" class="block font-label-md font-bold text-on-surface mb-2">Callback URL</label>
            <input type="url" name="callback_url" id="callback_url" value="{{ old('callback_url') }}" placeholder="https://noc.rhantech.com/api/webhooks/midtrans/callback" class="w-full bg-surface-container-low border @error('callback_url') border-error @else border-outline-variant @enderror rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" required>
            <p class="text-sm text-on-surface-variant mt-1">The full URL to forward the webhook payload to.</p>
            @error('callback_url')
                <p class="text-error text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-5 h-5 text-primary focus:ring-primary rounded border-outline-variant cursor-pointer">
                <span class="font-label-md font-bold text-on-surface">Active</span>
            </label>
            <p class="text-sm text-on-surface-variant mt-1 ml-7">If inactive, webhooks matching this prefix will not be forwarded.</p>
            @error('is_active')
                <p class="text-error text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_local" value="1" {{ old('is_local') ? 'checked' : '' }} class="w-5 h-5 text-primary focus:ring-primary rounded border-outline-variant cursor-pointer">
                <span class="font-label-md font-bold text-on-surface">Process Locally</span>
            </label>
            <p class="text-sm text-on-surface-variant mt-1 ml-7">Jika diaktifkan, webhook akan diproses langsung di aplikasi ini (bukan diteruskan ke Callback URL). Gunakan untuk aplikasi <strong>rhantech.com</strong> sendiri.</p>
            @error('is_local')
                <p class="text-error text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end pt-4 border-t border-outline-variant/30">
            <button type="submit" class="bg-primary text-on-primary px-6 py-2 rounded-lg font-bold hover:bg-primary/90 transition-colors">Save App</button>
        </div>
    </form>
</div>
@endsection

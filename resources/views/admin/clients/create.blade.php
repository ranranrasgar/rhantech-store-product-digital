@extends('layouts.admin')
@section('title', 'Add New Client')
@section('content')
<div class="flex-1 overflow-y-auto p-lg bg-background">
    <div class="max-w-3xl mx-auto space-y-lg">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-md">
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface">Add New Client</h2>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">Create a new corporate client record.</p>
            </div>
            <a href="{{ route('admin.clients.index') }}" class="text-on-surface-variant hover:bg-surface-container-high p-2 rounded-full transition-colors flex items-center justify-center" title="Back to Clients" wire:navigate>
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0; font-size: 24px;">arrow_back</span>
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-surface rounded-md border border-outline-variant shadow-[0px_4px_6px_-1px_rgba(15,23,42,0.03),0px_2px_4px_-2px_rgba(15,23,42,0.03)] p-lg">
            <form action="{{ route('admin.clients.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-lg">
                @csrf
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Client Name <span class="text-error">*</span></label>
                    <input type="text" name="name" required value="{{ old('name') }}" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                    @error('name')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Website URL</label>
                    <input type="text" name="url" placeholder="https://example.com" value="{{ old('url') }}" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                    @error('url')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                    @error('email')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                    @error('phone')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Logo Image</label>
                    <input type="file" name="logo" accept="image/*" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                    @error('logo')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-5 h-5 rounded border-[#CBD5E1] text-primary focus:ring-primary">
                        <span class="font-body-md text-on-surface">Active Client</span>
                    </label>
                </div>
                <div class="flex justify-end gap-sm mt-lg pt-md border-t border-outline-variant">
                    <a href="{{ route('admin.clients.index') }}" class="px-6 py-2 border border-outline-variant rounded-lg font-label-md font-bold text-on-surface hover:bg-surface-variant transition" wire:navigate>Cancel</a>
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg font-label-md font-bold hover:bg-primary/90 transition shadow-[0px_4px_6px_-1px_rgba(15,23,42,0.03),0px_2px_4px_-2px_rgba(15,23,42,0.03)]">Save Client</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

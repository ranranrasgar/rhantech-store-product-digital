@extends('layouts.admin')
@section('title', 'Company Profile')
@section('content')
<div class="p-lg md:p-xl flex-1 max-w-4xl mx-auto w-full">
    <div class="bg-surface rounded-md border border-outline-variant  p-lg">
        <form action="{{ route('admin.company.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-lg">
            @csrf
            
            <!-- Basic Info -->
            <h3 class="font-headline-sm font-bold text-on-surface border-b border-outline-variant pb-2">Basic Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Company Name *</label>
                    <input type="text" name="company_name" required value="{{ old('company_name', $profile->company_name ?? '') }}" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                    @error('company_name')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Email Address *</label>
                    <input type="email" name="email" required value="{{ old('email', $profile->email ?? '') }}" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                    @error('email')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Phone Number *</label>
                    <input type="text" name="phone" required value="{{ old('phone', $profile->phone ?? '') }}" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                    @error('phone')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">WhatsApp Number (Optional)</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $profile->whatsapp ?? '') }}" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                    @error('whatsapp')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
            </div>

            <!-- Address & Description -->
            <div>
                <label class="block font-label-md text-on-surface mb-xs">Address</label>
                <textarea name="address" rows="3" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">{{ old('address', $profile->address ?? '') }}</textarea>
                @error('address')<span class="text-error text-xs">{{ $message }}</span>@enderror
            </div>
            <div>
                <label class="block font-label-md text-on-surface mb-xs">About Us (Short)</label>
                <textarea name="description" rows="2" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">{{ old('description', $profile->description ?? '') }}</textarea>
                @error('description')<span class="text-error text-xs">{{ $message }}</span>@enderror
            </div>

            <!-- Social Media -->
            <h3 class="font-headline-sm font-bold text-on-surface border-b border-outline-variant pb-2 mt-md">Social Media Links</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Facebook URL</label>
                    <input type="url" name="facebook" value="{{ old('facebook', $profile->facebook ?? '') }}" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Instagram URL</label>
                    <input type="url" name="instagram" value="{{ old('instagram', $profile->instagram ?? '') }}" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">LinkedIn URL</label>
                    <input type="url" name="linkedin" value="{{ old('linkedin', $profile->linkedin ?? '') }}" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Website URL</label>
                    <input type="url" name="website" value="{{ old('website', $profile->website ?? '') }}" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                </div>
            </div>
            <div>
                <label class="block font-label-md text-on-surface mb-xs">YouTube URL</label>
                <input type="url" name="youtube" value="{{ old('youtube', $profile->youtube ?? '') }}" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
            </div>

            <!-- Assets -->
            <h3 class="font-headline-sm font-bold text-on-surface border-b border-outline-variant pb-2 mt-md">Brand Assets</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-md items-end">
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Logo</label>
                    @if(isset($profile) && $profile->logo)
                        <div class="mb-2"><img src="{{ asset('storage/' . $profile->logo) }}" class="h-16 w-auto bg-white p-2 rounded border border-outline-variant"></div>
                    @endif
                    <input type="file" name="logo" accept="image/*" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                    @error('logo')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Favicon</label>
                    @if(isset($profile) && $profile->favicon)
                        <div class="mb-2"><img src="{{ asset('storage/' . $profile->favicon) }}" class="h-10 w-auto bg-white p-2 rounded border border-outline-variant"></div>
                    @endif
                    <input type="file" name="favicon" accept="image/*" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                    @error('favicon')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="flex justify-end mt-lg pt-md border-t border-outline-variant">
                <button type="submit" class="px-md py-2 bg-primary text-white rounded-lg font-label-md font-bold hover:brightness-110 transition shadow">Save Configuration</button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.admin')
@section('title', 'Add New Service')
@section('content')
<div class="flex-1 overflow-y-auto p-lg bg-background">
    <div class="max-w-4xl mx-auto space-y-lg">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-md">
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface">Add New Service</h2>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">Create a new service offering.</p>
            </div>
            <a href="{{ route('admin.services.index') }}" class="text-on-surface-variant hover:bg-surface-container-high p-2 rounded-full transition-colors flex items-center justify-center" title="Back to Services">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0; font-size: 24px;">arrow_back</span>
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-surface rounded-xl border border-outline-variant shadow-[0px_4px_6px_-1px_rgba(15,23,42,0.03),0px_2px_4px_-2px_rgba(15,23,42,0.03)] p-lg">
            <form action="{{ route('admin.services.store') }}" method="POST" class="flex flex-col gap-lg">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Service Title <span class="text-error">*</span></label>
                        <input type="text" name="name" required value="{{ old('name') }}" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-[#06B6D4] focus:ring-4 focus:ring-[#06B6D4]/10 transition-all">
                        @error('name')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Slug (Optional)</label>
                        <input type="text" name="slug" value="{{ old('slug') }}" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-[#06B6D4] focus:ring-4 focus:ring-[#06B6D4]/10 transition-all">
                        @error('slug')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Icon (Material Symbol name)</label>
                    <input type="text" name="icon" value="{{ old('icon') }}" placeholder="e.g., code, layers, design_services" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-[#06B6D4] focus:ring-4 focus:ring-[#06B6D4]/10 transition-all">
                    @error('icon')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Short Description</label>
                    <textarea name="short_description" rows="2" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-[#06B6D4] focus:ring-4 focus:ring-[#06B6D4]/10 transition-all">{{ old('short_description') }}</textarea>
                    @error('short_description')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Full Description</label>
                    <textarea name="description" rows="5" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-[#06B6D4] focus:ring-4 focus:ring-[#06B6D4]/10 transition-all">{{ old('description') }}</textarea>
                    @error('description')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-5 h-5 rounded border-[#CBD5E1] text-[#06B6D4] focus:ring-[#06B6D4]">
                        <span class="font-body-md text-on-surface">Active Service</span>
                    </label>
                </div>

                <div class="flex justify-end gap-sm mt-lg pt-md border-t border-outline-variant">
                    <a href="{{ route('admin.services.index') }}" class="px-6 py-2 border border-outline-variant rounded-lg font-label-md font-bold text-on-surface hover:bg-surface-variant transition">Cancel</a>
                    <button type="submit" class="px-6 py-2 bg-[#06B6D4] text-white rounded-lg font-label-md font-bold hover:bg-[#06B6D4]/90 transition shadow-[0px_4px_6px_-1px_rgba(15,23,42,0.03),0px_2px_4px_-2px_rgba(15,23,42,0.03)]">Save Service</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

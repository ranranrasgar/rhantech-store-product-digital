@extends('layouts.admin')
@section('title', 'Add New Testimonial')
@section('content')
<div class="flex-1 overflow-y-auto p-lg bg-background">
    <div class="max-w-4xl mx-auto space-y-lg">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-md">
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface">Add New Testimonial</h2>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">Record a new client review.</p>
            </div>
            <a href="{{ route('admin.testimonials.index') }}" class="text-on-surface-variant hover:bg-surface-container-high p-2 rounded-full transition-colors flex items-center justify-center" title="Back to Testimonials" wire:navigate>
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0; font-size: 24px;">arrow_back</span>
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-surface rounded-md border border-outline-variant shadow-[0px_4px_6px_-1px_rgba(15,23,42,0.03),0px_2px_4px_-2px_rgba(15,23,42,0.03)] p-lg">
            <form action="{{ route('admin.testimonials.store') }}" method="POST" class="flex flex-col gap-lg">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Client <span class="text-error">*</span></label>
                        <select name="client_id" required class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                            <option value="">Select a Client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                            @endforeach
                        </select>
                        @error('client_id')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Rating (1-5)</label>
                        <select name="rating" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ old('rating', 5) == $i ? 'selected' : '' }}>{{ $i }} Stars</option>
                            @endfor
                        </select>
                        @error('rating')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Quote / Testimonial</label>
                    <textarea name="quote" rows="4" required class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">{{ old('quote') }}</textarea>
                    @error('quote')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-5 h-5 rounded border-[#CBD5E1] text-primary focus:ring-primary">
                        <span class="font-body-md text-on-surface">Active Testimonial</span>
                    </label>
                </div>

                <div class="flex justify-end gap-sm mt-lg pt-md border-t border-outline-variant">
                    <a href="{{ route('admin.testimonials.index') }}" class="px-6 py-2 border border-outline-variant rounded-lg font-label-md font-bold text-on-surface hover:bg-surface-variant transition" wire:navigate>Cancel</a>
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg font-label-md font-bold hover:bg-primary/90 transition shadow-[0px_4px_6px_-1px_rgba(15,23,42,0.03),0px_2px_4px_-2px_rgba(15,23,42,0.03)]">Save Testimonial</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

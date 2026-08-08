@extends('layouts.admin')
@section('title', 'Add Digital Product')
@section('content')
<div class="p-lg md:p-xl flex-1 max-w-4xl mx-auto w-full">
    <div class="flex items-center gap-md mb-lg">
        <a href="{{ route('admin.products.index') }}" class="p-2 text-on-surface-variant hover:bg-surface-container-high rounded-full transition">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h2 class="font-headline-md font-bold text-on-surface">Add Digital Product</h2>
    </div>

    <div class="bg-surface rounded-xl border border-outline-variant shadow-sm p-lg">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-lg">
            @csrf

            <div>
                <label class="block font-label-md text-on-surface mb-xs">Product Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                @error('name')<span class="text-error text-xs">{{ $message }}</span>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Category</label>
                    <select name="product_category_id" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                        <option value="">-- No Category --</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('product_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('product_category_id')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Type</label>
                    <select name="product_type_id" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                        <option value="">-- No Type --</option>
                        @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ old('product_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                    @error('product_type_id')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Price (Rp) *</label>
                    <input type="number" name="price" required min="0" value="{{ old('price') }}" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                    @error('price')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Discount Price (Rp) - Optional</label>
                    <input type="number" name="discount_price" min="0" value="{{ old('discount_price') }}" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                    @error('discount_price')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
            </div>

            <div>
                <label class="block font-label-md text-on-surface mb-xs">Description *</label>
                <textarea name="description" rows="5" required class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">{{ old('description') }}</textarea>
                @error('description')<span class="text-error text-xs">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="block font-label-md text-on-surface mb-xs">Demo URL (Optional)</label>
                <input type="url" name="demo_url" value="{{ old('demo_url') }}" placeholder="https://..." class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                @error('demo_url')<span class="text-error text-xs">{{ $message }}</span>@enderror
            </div>

            <div class="p-md bg-secondary-container/20 border border-secondary-container rounded-lg">
                <label class="block font-label-md text-on-surface mb-xs">Digital File (ZIP/RAR) *</label>
                <p class="text-xs text-on-surface-variant mb-2">This is the file the customer will download after payment. Max 100MB.</p>
                <input type="file" name="file" required class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                @error('file')<span class="text-error text-xs">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="block font-label-md text-on-surface mb-xs">Product Images (Up to 5) *</label>
                <input type="file" name="images[]" multiple accept="image/*" required class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                <p class="text-xs text-on-surface-variant mt-1">Select multiple files holding CTRL/CMD.</p>
                @error('images')<span class="text-error text-xs">{{ $message }}</span>@enderror
                @error('images.*')<span class="text-error text-xs">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-outline-variant text-[#06B6D4] focus:ring-[#06B6D4]">
                    <span class="font-label-md text-on-surface">Active (Visible in Store)</span>
                </label>
            </div>

            <div class="flex justify-end pt-md border-t border-outline-variant">
                <button type="submit" class="px-md py-2 bg-[#06B6D4] text-white rounded-lg font-label-md font-bold hover:bg-[#0891B2] transition shadow">Save Product</button>
            </div>
        </form>
    </div>
</div>
@endsection

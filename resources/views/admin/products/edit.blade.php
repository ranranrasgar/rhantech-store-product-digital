@extends('layouts.admin')
@section('title', 'Edit Digital Product')
@section('content')
<div class="p-lg md:p-xl flex-1 max-w-4xl mx-auto w-full">
    <div class="flex items-center gap-md mb-lg">
        <a href="{{ route('admin.products.index') }}" class="p-2 text-on-surface-variant hover:bg-surface-container-high rounded-full transition" wire:navigate>
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h2 class="font-headline-md font-bold text-on-surface">Edit Digital Product</h2>
    </div>

    <div class="bg-surface rounded-md border border-outline-variant  p-lg">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-lg">
            @csrf @method('PUT')

            <div>
                <label class="block font-label-md text-on-surface mb-xs">Product Name *</label>
                <input type="text" name="name" required value="{{ old('name', $product->name) }}" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                @error('name')<span class="text-error text-xs">{{ $message }}</span>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Category</label>
                    <select name="product_category_id" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                        <option value="">-- No Category --</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('product_category_id', $product->product_category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('product_category_id')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Type</label>
                    <select name="product_type_id" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                        <option value="">-- No Type --</option>
                        @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ old('product_type_id', $product->product_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                    @error('product_type_id')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Price (Rp) *</label>
                    <input type="number" name="price" required min="0" value="{{ old('price', (int)$product->price) }}" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                    @error('price')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Discount Price (Rp) - Optional</label>
                    <input type="number" name="discount_price" min="0" value="{{ old('discount_price', (int)$product->discount_price) }}" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                    @error('discount_price')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
            </div>

            <div>
                <label class="block font-label-md text-on-surface mb-xs">Description *</label>
                <textarea name="description" rows="5" required class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">{{ old('description', $product->description) }}</textarea>
                @error('description')<span class="text-error text-xs">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="block font-label-md text-on-surface mb-xs">Demo URL (Optional)</label>
                <input type="url" name="demo_url" value="{{ old('demo_url', $product->demo_url) }}" placeholder="https://..." class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                @error('demo_url')<span class="text-error text-xs">{{ $message }}</span>@enderror
            </div>

            <div class="p-md bg-secondary-container/20 border border-secondary-container rounded-lg">
                <label class="block font-label-md text-on-surface mb-xs">Replace Digital File (ZIP/RAR) (Optional)</label>
                <p class="text-xs text-on-surface-variant mb-2">Leave empty to keep the existing file.</p>
                <input type="file" name="file" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                @error('file')<span class="text-error text-xs">{{ $message }}</span>@enderror
            </div>

            <div class="p-md bg-surface-container-low border border-outline-variant rounded-lg">
                <div class="flex justify-between items-center mb-xs">
                    <label class="block font-label-md text-on-surface">External Download Links (Optional)</label>
                    <button type="button" onclick="addLink()" class="text-xs bg-primary text-white px-3 py-1 rounded font-bold hover:brightness-110 transition">+ Add Link</button>
                </div>
                <p class="text-xs text-on-surface-variant mb-4">Add multiple external links (e.g., Google Drive, Mega) to be sent to the buyer's email.</p>
                
                <div id="links-container" class="flex flex-col gap-sm">
                    @php $links = old('download_links', $product->download_links ?? []); @endphp
                    @foreach($links as $index => $link)
                    <div class="flex gap-2 items-start">
                        <div class="flex-1">
                            <input type="text" name="download_links[{{ $index }}][name]" value="{{ $link['name'] ?? '' }}" placeholder="Link Name (e.g., Source Code)" required class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-sm mb-1">
                        </div>
                        <div class="flex-[2]">
                            <input type="url" name="download_links[{{ $index }}][url]" value="{{ $link['url'] ?? '' }}" placeholder="https://..." required class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-sm">
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="p-2 text-error hover:bg-error/10 rounded-lg transition" title="Remove Link">
                            <span class="material-symbols-outlined text-sm">delete</span>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block font-label-md text-on-surface mb-xs">Add More Images</label>
                <input type="file" name="images[]" multiple accept="image/*" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                <p class="text-xs text-on-surface-variant mt-1">Maximum 5 images total.</p>
                @error('images')<span class="text-error text-xs">{{ $message }}</span>@enderror
            </div>

            @include('products._custom_fields', ['productItem' => $product])

            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="rounded border-outline-variant text-primary focus:ring-primary">
                    <span class="font-label-md text-on-surface">Active (Visible in Store)</span>
                </label>
            </div>
            
            <div class="flex justify-end pt-md border-t border-outline-variant">
                <button type="submit" class="px-md py-2 bg-primary text-white rounded-lg font-label-md font-bold hover:brightness-110 transition shadow">Update Product</button>
            </div>
        </form>

        @if($product->images->count() > 0)
        <div class="mt-lg pt-lg border-t border-outline-variant">
            <h3 class="font-headline-sm font-bold text-on-surface mb-md">Current Images</h3>
            <div class="flex flex-wrap gap-md">
                @foreach($product->images as $img)
                <div class="relative group">
                    <img src="{{ asset('storage/' . $img->image_path) }}" class="w-32 h-32 object-cover rounded-lg border {{ $img->is_main ? 'border-primary border-4' : 'border-outline-variant' }}">
                    
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex flex-col items-center justify-center gap-2">
                        @if(!$img->is_main)
                        <form action="{{ route('admin.products.image.set_main', $img) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="px-3 py-1 bg-white text-black text-xs font-bold rounded shadow hover:bg-gray-200">
                                Set Main
                            </button>
                        </form>
                        @endif
                        <form action="{{ route('admin.products.image.destroy', $img) }}" method="POST" onsubmit="return confirm('Delete this image?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-error text-white text-xs font-bold rounded shadow hover:bg-red-600">
                                Delete
                            </button>
                        </form>
                    </div>
                    @if($img->is_main)
                        <div class="absolute top-0 left-0 bg-primary text-white text-[10px] font-bold px-2 py-1 rounded-tl-lg rounded-br-lg">MAIN</div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    function addLink() {
        const container = document.getElementById('links-container');
        const index = Date.now(); // use timestamp to avoid index collision on edit
        
        const row = document.createElement('div');
        row.className = 'flex gap-2 items-start';
        row.innerHTML = `
            <div class="flex-1">
                <input type="text" name="download_links[${index}][name]" placeholder="Link Name (e.g., Source Code)" required class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-sm mb-1">
            </div>
            <div class="flex-[2]">
                <input type="url" name="download_links[${index}][url]" placeholder="https://..." required class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-sm">
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="p-2 text-error hover:bg-error/10 rounded-lg transition" title="Remove Link">
                <span class="material-symbols-outlined text-sm">delete</span>
            </button>
        `;
        container.appendChild(row);
    }
</script>
@endsection

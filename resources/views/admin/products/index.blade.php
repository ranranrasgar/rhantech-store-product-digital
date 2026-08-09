@extends('layouts.admin')
@section('title', 'Digital Products')
@section('content')
<div class="p-lg md:p-xl flex-1 max-w-7xl mx-auto w-full">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-lg gap-md">
        <div>
            <h2 class="font-headline-md font-bold text-on-surface">Digital Products</h2>
            <p class="font-body-md text-on-surface-variant">Manage your digital source codes and assets.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-xs px-md py-2 bg-[#06B6D4] text-white rounded-lg font-label-md font-bold hover:bg-[#0891B2] transition shadow">
            <span class="material-symbols-outlined text-[1.25rem]">add</span>
            Add Product
        </a>
    </div>

    <div class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left font-body-md">
                <thead class="bg-surface-container-lowest border-b border-outline-variant text-on-surface-variant font-label-md">
                    <tr>
                        <th class="p-4 font-medium">Product</th>
                        <th class="p-4 font-medium">Price</th>
                        <th class="p-4 font-medium">Status</th>
                        <th class="p-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($products as $product)
                    <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                        <td class="p-4">
                            <div class="flex items-center gap-sm">
                                @if($product->images->count() > 0)
                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="w-12 h-12 rounded object-cover border border-outline-variant">
                                @else
                                    <div class="w-12 h-12 rounded bg-surface-container-high border border-outline-variant flex items-center justify-center">
                                        <span class="material-symbols-outlined text-on-surface-variant">code</span>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-bold text-on-surface">{{ $product->name }}</div>
                                    <div class="text-on-surface-variant text-xs">{{ $product->slug }}</div>
                                    @if($product->store)
                                        <div class="text-primary text-xs mt-1 font-bold">Store: {{ $product->store->name }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            @if($product->discount_price)
                                <div class="font-bold text-[#06B6D4]">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</div>
                                <div class="text-xs text-on-surface-variant line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            @else
                                <div class="font-bold text-on-surface">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            @endif
                        </td>
                        <td class="p-4">
                            @if($product->is_active)
                                <span class="px-2 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">Active</span>
                            @else
                                <span class="px-2 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">Inactive</span>
                            @endif
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <form action="{{ route('admin.products.toggle_active', $product) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="p-2 {{ $product->is_active ? 'text-green-600 hover:bg-green-50' : 'text-gray-400 hover:bg-gray-50' }} rounded-lg transition" title="Toggle Active">
                                        <span class="material-symbols-outlined text-[1.25rem]">{{ $product->is_active ? 'toggle_on' : 'toggle_off' }}</span>
                                    </button>
                                </form>
                                <a href="{{ route('admin.products.edit', $product) }}" class="p-2 text-on-surface-variant hover:text-[#06B6D4] hover:bg-surface-container-high rounded-lg transition" title="Edit">
                                    <span class="material-symbols-outlined text-[1.25rem]">edit</span>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-on-surface-variant hover:text-error hover:bg-error-container rounded-lg transition" title="Delete">
                                        <span class="material-symbols-outlined text-[1.25rem]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl mb-2 opacity-50">inventory_2</span>
                            <p>No digital products found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

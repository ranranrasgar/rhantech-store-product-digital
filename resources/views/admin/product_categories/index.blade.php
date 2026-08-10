@extends('layouts.admin')
@section('title', 'Product Categories')
@section('content')
<div class="p-lg md:p-xl flex-1 max-w-5xl mx-auto w-full">
    <div class="flex items-center justify-between mb-lg">
        <h1 class="font-headline-sm font-bold">Categories</h1>
        <form action="{{ route('admin.product_categories.store') }}" method="POST" class="flex gap-2">
            @csrf
            <input type="text" name="name" required placeholder="New Category Name" class="pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg font-bold hover:brightness-110 transition">Add</button>
        </form>
    </div>

    <div class="bg-surface-container-lowest border border-outline-variant rounded-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="p-4 font-label-md font-bold text-on-surface-variant">Name</th>
                        <th class="p-4 font-label-md font-bold text-on-surface-variant">Slug</th>
                        <th class="p-4 font-label-md font-bold text-on-surface-variant text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($categories as $category)
                    <tr class="hover:bg-surface-container-low/50 transition">
                        <td class="p-4">
                            <form action="{{ route('admin.product_categories.update', $category) }}" method="POST" class="flex gap-2">
                                @csrf @method('PUT')
                                <input type="text" name="name" value="{{ $category->name }}" required class="px-2 py-1 bg-transparent border border-transparent focus:border-outline-variant rounded">
                                <button type="submit" class="text-xs bg-surface-container-high px-2 py-1 rounded hover:bg-surface-container-highest transition">Update</button>
                            </form>
                        </td>
                        <td class="p-4 text-on-surface-variant">{{ $category->slug }}</td>
                        <td class="p-4 text-right">
                            <form action="{{ route('admin.product_categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?');" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-error hover:bg-error/10 rounded-lg transition" title="Delete">
                                    <span class="material-symbols-outlined text-[1.25rem]">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="p-8 text-center text-on-surface-variant">No categories found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

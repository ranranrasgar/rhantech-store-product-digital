<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HelpCategory;

class HelpCategoryController extends Controller
{
    public function index()
    {
        $categories = HelpCategory::orderBy('sort_order')->get();
        return view('admin.help_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.help_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:help_categories',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'integer',
        ]);

        HelpCategory::create($request->all());

        return redirect()->route('admin.help_categories.index')->with('success', 'Kategori Bantuan berhasil ditambahkan.');
    }

    public function edit(HelpCategory $helpCategory)
    {
        return view('admin.help_categories.edit', compact('helpCategory'));
    }

    public function update(Request $request, HelpCategory $helpCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:help_categories,slug,' . $helpCategory->id,
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'integer',
        ]);

        $helpCategory->update($request->all());

        return redirect()->route('admin.help_categories.index')->with('success', 'Kategori Bantuan berhasil diperbarui.');
    }

    public function destroy(HelpCategory $helpCategory)
    {
        $helpCategory->delete();
        return redirect()->route('admin.help_categories.index')->with('success', 'Kategori Bantuan berhasil dihapus.');
    }
}

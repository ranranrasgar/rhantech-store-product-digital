<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProjectCategory;
use Illuminate\Support\Str;

class ProjectCategoryController extends Controller
{
    public function index()
    {
        $categories = ProjectCategory::withCount('projects')->orderBy('name', 'asc')->get();
        return view('admin.project_categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(4);
        ProjectCategory::create($validated);
        
        return back()->with('success', 'Project Category added.');
    }

    public function update(Request $request, ProjectCategory $project_category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(4);
        $project_category->update($validated);
        
        return back()->with('success', 'Project Category updated.');
    }

    public function destroy(ProjectCategory $project_category)
    {
        // Set the project_category_id to null for related projects before deleting
        // (Handled automatically by nullOnDelete in migration, but good practice to ensure)
        $project_category->delete();
        return back()->with('success', 'Project Category deleted.');
    }
}

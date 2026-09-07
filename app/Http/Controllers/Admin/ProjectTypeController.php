<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProjectType;
use Illuminate\Support\Str;

class ProjectTypeController extends Controller
{
    public function index()
    {
        $types = ProjectType::withCount('projects')->orderBy('name', 'asc')->get();
        return view('admin.project_types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(4);
        ProjectType::create($validated);
        
        return back()->with('success', 'Project Type added.');
    }

    public function update(Request $request, ProjectType $project_type)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(4);
        $project_type->update($validated);
        
        return back()->with('success', 'Project Type updated.');
    }

    public function destroy(ProjectType $project_type)
    {
        $project_type->delete();
        return back()->with('success', 'Project Type deleted.');
    }
}

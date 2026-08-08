<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalProjects = Project::count('id');
        $publishedProjects = Project::query()->where('status', 'published')->count('id');
        $featuredProjects = Project::query()->where('is_featured', true)->count('id');

        $projects = Project::with('client')->latest()->paginate(10);
        return view('admin.projects.index', compact('projects', 'totalProjects', 'publishedProjects', 'featuredProjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::query()->orderBy('name', 'asc')->get();
        return view('admin.projects.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'client_id' => 'nullable|exists:clients,id',
            'slug' => 'nullable|string|max:255|unique:projects',
            'category' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'project_url' => 'nullable|url',
            'completed_at' => 'nullable|date',
            'status' => 'required|string|in:draft,published,archived',
            'is_featured' => 'boolean',
            'thumbnail' => 'nullable|image|max:2048'
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('projects', 'public');
        }

        Project::create($validated);

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $clients = Client::query()->orderBy('name', 'asc')->get();
        return view('admin.projects.edit', compact('project', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'client_id' => 'nullable|exists:clients,id',
            'slug' => 'nullable|string|max:255|unique:projects,slug,' . $project->id,
            'category' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'project_url' => 'nullable|url',
            'completed_at' => 'nullable|date',
            'status' => 'required|string|in:draft,published,archived',
            'is_featured' => 'boolean',
            'thumbnail' => 'nullable|image|max:2048'
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('thumbnail')) {
            if ($project->thumbnail) {
                Storage::disk('public')->delete($project->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('projects', 'public');
        }

        $project->update($validated);

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        if ($project->thumbnail) {
            Storage::disk('public')->delete($project->thumbnail);
        }
        $project->deleteOrFail();

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }
}

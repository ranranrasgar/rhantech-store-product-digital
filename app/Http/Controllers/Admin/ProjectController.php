<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Client;
use App\Models\ProjectCategory;
use App\Models\ProjectType;
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

        $projects = Project::with(['clients', 'projectCategory', 'projectType'])->latest()->paginate(10);
        return view('admin.projects.index', compact('projects', 'totalProjects', 'publishedProjects', 'featuredProjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $clients = Client::query()->orderBy('name', 'asc')->get();
        $categories = ProjectCategory::orderBy('name', 'asc')->get();
        $types = ProjectType::orderBy('name', 'asc')->get();

        $duplicateProject = null;
        if ($request->filled('duplicate_from')) {
            $duplicateProject = Project::with('clients')->whereKey($request->query('duplicate_from'))->first();
        }

        return view('admin.projects.create', compact('clients', 'categories', 'types', 'duplicateProject'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'client_id' => 'nullable|exists:clients,id',
            'client_ids' => 'nullable|array',
            'client_ids.*' => 'exists:clients,id',
            'slug' => 'nullable|string|max:255|unique:projects',
            'project_category_id' => 'nullable|exists:project_categories,id',
            'project_type_id' => 'nullable|exists:project_types,id',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'project_url' => 'nullable|url',
            'completed_at' => 'nullable|date',
            'status' => 'required|string|in:draft,published,archived',
            'is_featured' => 'boolean',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,ico|max:2048'
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('projects', 'public');
        }

        $validated['is_featured'] = $request->has('is_featured');

        // Jika client_id kosong tapi ada client_ids, ambil client pertama sebagai primary client_id
        if (empty($validated['client_id']) && !empty($validated['client_ids'])) {
            $validated['client_id'] = $validated['client_ids'][0] ?? null;
        }

        $clientIds = $validated['client_ids'] ?? [];
        if (!empty($validated['client_id']) && !in_array($validated['client_id'], $clientIds)) {
            $clientIds[] = $validated['client_id'];
        }

        unset($validated['client_ids']);

        $project = Project::create($validated);
        $project->clients()->sync($clientIds);

        return redirect()->route('admin.projects.index')->with('success', 'Portfolio created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $project->load('clients');
        $clients = Client::query()->orderBy('name', 'asc')->get();
        $categories = ProjectCategory::orderBy('name', 'asc')->get();
        $types = ProjectType::orderBy('name', 'asc')->get();
        return view('admin.projects.edit', compact('project', 'clients', 'categories', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'client_id' => 'nullable|exists:clients,id',
            'client_ids' => 'nullable|array',
            'client_ids.*' => 'exists:clients,id',
            'slug' => 'nullable|string|max:255|unique:projects,slug,' . $project->id,
            'project_category_id' => 'nullable|exists:project_categories,id',
            'project_type_id' => 'nullable|exists:project_types,id',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'project_url' => 'nullable|url',
            'completed_at' => 'nullable|date',
            'status' => 'required|string|in:draft,published,archived',
            'is_featured' => 'boolean',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,ico|max:2048'
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

        $validated['is_featured'] = $request->has('is_featured');

        // Jika client_id kosong tapi ada client_ids, ambil client pertama sebagai primary client_id
        if (empty($validated['client_id']) && !empty($validated['client_ids'])) {
            $validated['client_id'] = $validated['client_ids'][0] ?? null;
        }

        $clientIds = $validated['client_ids'] ?? [];
        if (!empty($validated['client_id']) && !in_array($validated['client_id'], $clientIds)) {
            $clientIds[] = $validated['client_id'];
        }

        unset($validated['client_ids']);

        $project->update($validated);
        $project->clients()->sync($clientIds);

        return redirect()->route('admin.projects.index')->with('success', 'Portfolio updated successfully.');
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

    /**
     * Check if a slug exists and generate an available unique slug.
     */
    public function checkSlug(Request $request)
    {
        $title = $request->query('title', '');
        $clientName = $request->query('client_name', '');
        $currentId = $request->query('exclude_id');

        // Combine title and client name for unique context
        $baseText = trim($title . ($clientName ? ' ' . $clientName : ''));
        $baseSlug = Str::slug($baseText ?: 'project');
        $slug = $baseSlug;
        $counter = 1;

        while (Project::whereSlug($slug)
            ->when($currentId, fn($q) => $q->where('id', '!=', $currentId))
            ->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return response()->json([
            'slug' => $slug,
            'is_duplicate' => $slug !== $baseSlug
        ]);
    }
}

<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TenantProjectController extends Controller
{
    private function getStore(): ?Store
    {
        return Auth::user()->store;
    }

    public function index()
    {
        $store = $this->getStore();
        if (!$store) {
            return redirect()->route('tenant.dashboard')->with('error', 'Silakan lengkapi profil toko terlebih dahulu.');
        }

        $projects = Project::where('store_id', $store->id)
            ->latest()
            ->paginate(12);

        return view('tenant.projects.index', compact('store', 'projects'));
    }

    public function create()
    {
        $store = $this->getStore();
        if (!$store) {
            return redirect()->route('tenant.dashboard');
        }

        return view('tenant.projects.create', compact('store'));
    }

    public function store(Request $request)
    {
        $store = $this->getStore();
        if (!$store) {
            return redirect()->route('tenant.dashboard');
        }

        if (!$store->isPro()) {
            return redirect()->route('tenant.pro.index')->with('error', 'Modul Portofolio & Proyek khusus untuk Toko PRO. Silakan upgrade toko Anda.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
            'project_url' => 'nullable|url|max:255',
            'technologies' => 'nullable|string',
            'completed_at' => 'nullable|date',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('projects', 'public');
        }

        $techArray = null;
        if ($request->filled('technologies')) {
            $techArray = array_map('trim', explode(',', $request->technologies));
        }

        $baseSlug = Str::slug($request->title);
        $slug = $baseSlug;
        $counter = 1;
        while (Project::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        Project::create([
            'store_id' => $store->id,
            'title' => $request->title,
            'slug' => $slug,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'thumbnail' => $thumbnailPath,
            'project_url' => $request->project_url,
            'technologies' => $techArray,
            'completed_at' => $request->completed_at,
            'status' => 'published',
            'is_featured' => false,
        ]);

        return redirect()->route('tenant.projects.index')->with('success', 'Proyek portofolio berhasil ditambahkan!');
    }

    public function edit(Project $project)
    {
        $store = $this->getStore();
        if ($project->store_id !== $store->id) {
            abort(403);
        }

        return view('tenant.projects.edit', compact('store', 'project'));
    }

    public function update(Request $request, Project $project)
    {
        $store = $this->getStore();
        if ($project->store_id !== $store->id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
            'project_url' => 'nullable|url|max:255',
            'technologies' => 'nullable|string',
            'completed_at' => 'nullable|date',
        ]);

        if ($request->hasFile('thumbnail')) {
            $project->thumbnail = $request->file('thumbnail')->store('projects', 'public');
        }

        if ($request->filled('technologies')) {
            $project->technologies = array_map('trim', explode(',', $request->technologies));
        }

        $project->title = $request->title;
        $project->short_description = $request->short_description;
        $project->description = $request->description;
        $project->project_url = $request->project_url;
        $project->completed_at = $request->completed_at;
        $project->save();

        return redirect()->route('tenant.projects.index')->with('success', 'Proyek portofolio berhasil diperbarui!');
    }

    public function destroy(Project $project)
    {
        $store = $this->getStore();
        if ($project->store_id !== $store->id) {
            abort(403);
        }

        $project->delete();
        return redirect()->route('tenant.projects.index')->with('success', 'Proyek berhasil dihapus.');
    }
}

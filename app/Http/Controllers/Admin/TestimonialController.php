<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use App\Models\Client;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function index()
    {
        $totalTestimonials = Testimonial::query()->count('id');
        $activeTestimonials = Testimonial::query()->where('is_active', true)->count('id');
        $averageRating = Testimonial::query()->avg('rating') ?? 0;
        
        $testimonials = Testimonial::query()->with('client')->latest()->paginate(10);
        return view('admin.testimonials.index', compact('testimonials', 'totalTestimonials', 'activeTestimonials', 'averageRating'));
    }

    public function create()
    {
        $clients = Client::query()->orderBy('name', 'asc')->get();
        return view('admin.testimonials.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg,ico|max:2048',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('testimonials', 'public');
        }

        Testimonial::create($validated);
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial added.');
    }

    public function edit(Testimonial $testimonial)
    {
        $clients = Client::query()->orderBy('name', 'asc')->get();
        return view('admin.testimonials.edit', compact('testimonial', 'clients'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg,ico|max:2048',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('avatar')) {
            if ($testimonial->avatar) Storage::disk('public')->delete($testimonial->avatar);
            $validated['avatar'] = $request->file('avatar')->store('testimonials', 'public');
        }

        $testimonial->update($validated);
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial)
    {
        if ($testimonial->avatar) Storage::disk('public')->delete($testimonial->avatar);
        $testimonial->deleteOrFail();
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deleted.');
    }
}

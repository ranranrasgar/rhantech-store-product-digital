<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PopupAd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PopupAdController extends Controller
{
    public function index()
    {
        $popupAds = PopupAd::latest()->paginate(10);
        return view('admin.popup_ads.index', compact('popupAds'));
    }

    public function create()
    {
        return view('admin.popup_ads.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link_url' => 'nullable|url|max:255',
            'link_text' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('popup_ads', 'public');
                $imagePaths[] = $path;
            }
        }

        $validated['images'] = !empty($imagePaths) ? $imagePaths : null;
        $validated['is_active'] = $request->has('is_active');

        PopupAd::create($validated);

        return redirect()->route('admin.popup_ads.index')->with('success', 'Iklan pop-up berhasil ditambahkan.');
    }

    public function edit(PopupAd $popupAd)
    {
        return view('admin.popup_ads.edit', compact('popupAd'));
    }

    public function update(Request $request, PopupAd $popupAd)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link_url' => 'nullable|url|max:255',
            'link_text' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePaths = $popupAd->images ?? [];

        // Handle image removals
        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $index => $shouldRemove) {
                if (isset($imagePaths[$index])) {
                    Storage::disk('public')->delete($imagePaths[$index]);
                    unset($imagePaths[$index]);
                }
            }
            $imagePaths = array_values($imagePaths); // Reindex array
        }

        // Handle new uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('popup_ads', 'public');
                $imagePaths[] = $path;
            }
        }

        $validated['images'] = !empty($imagePaths) ? $imagePaths : null;
        $validated['is_active'] = $request->has('is_active');

        $popupAd->update($validated);

        return redirect()->route('admin.popup_ads.index')->with('success', 'Iklan pop-up berhasil diperbarui.');
    }

    public function destroy(PopupAd $popupAd)
    {
        if ($popupAd->images) {
            foreach ($popupAd->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        $popupAd->delete();

        return redirect()->route('admin.popup_ads.index')->with('success', 'Iklan pop-up berhasil dihapus.');
    }
}

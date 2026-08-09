<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompanyProfile;
use Illuminate\Support\Facades\Storage;

class CompanyProfileController extends Controller
{
    public function index()
    {
        $profile = CompanyProfile::query()->first();
        return view('admin.company.index', compact('profile'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'website' => 'nullable|url',
            'youtube' => 'nullable|url',
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:1024',
        ]);

        $profile = CompanyProfile::query()->first();

        if (!$profile) {
            $profile = new CompanyProfile();
        }

        if ($request->hasFile('logo')) {
            if ($profile->logo) Storage::disk('public')->delete($profile->logo);
            $validated['logo'] = $request->file('logo')->store('company', 'public');
        }

        if ($request->hasFile('favicon')) {
            if ($profile->favicon) Storage::disk('public')->delete($profile->favicon);
            $validated['favicon'] = $request->file('favicon')->store('company', 'public');
        }

        if ($profile->exists) {
            $profile->update($validated);
        } else {
            CompanyProfile::create($validated);
        }

        return redirect()->route('admin.company.index')->with('success', 'Company profile saved successfully.');
    }
}

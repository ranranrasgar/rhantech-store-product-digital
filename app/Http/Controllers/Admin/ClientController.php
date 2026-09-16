<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ClientController extends Controller
{
    public function index()
    {
        $totalClients = Client::query()->count('id');
        $activeClients = Client::query()->where('is_active', true)->count('id');
        $newThisMonth = Client::query()->whereMonth('created_at', '=', Carbon::now()->month, 'and')->count('id');

        $clients = Client::query()->latest()->paginate(10);
        return view('admin.clients.index', compact('clients', 'totalClients', 'activeClients', 'newThisMonth'));
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,ico|max:2048',
            'url' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean'
        ], [
            'logo.max' => 'Ukuran logo klien tidak boleh melebihi 2 MB.',
            'logo.image' => 'File logo harus berupa gambar.',
        ]);

        if (array_key_exists('url', $validated)) {
            $validated['website'] = $validated['url'];
        }

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('clients', 'public');
        }

        Client::create($validated);
        return redirect()->route('admin.clients.index')->with('success', 'Client added.');
    }

    public function edit(Client $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,ico|max:2048',
            'url' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean'
        ], [
            'logo.max' => 'Ukuran logo klien tidak boleh melebihi 2 MB.',
            'logo.image' => 'File logo harus berupa gambar.',
        ]);

        if (array_key_exists('url', $validated)) {
            $validated['website'] = $validated['url'];
        }

        if ($request->hasFile('logo')) {
            if ($client->logo) Storage::disk('public')->delete($client->logo);
            $validated['logo'] = $request->file('logo')->store('clients', 'public');
        }

        $client->update($validated);
        return redirect()->route('admin.clients.index')->with('success', 'Client updated.');
    }

    public function destroy(Client $client)
    {
        if ($client->logo) Storage::disk('public')->delete($client->logo);
        $client->deleteOrFail();
        return redirect()->route('admin.clients.index')->with('success', 'Client deleted.');
    }
}

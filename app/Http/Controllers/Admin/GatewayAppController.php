<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GatewayApp;
use Illuminate\Http\Request;

class GatewayAppController extends Controller
{
    public function index()
    {
        $apps = GatewayApp::orderBy('id', 'desc')->paginate(10);
        return view('admin.gateway_apps.index', compact('apps'));
    }

    public function create()
    {
        return view('admin.gateway_apps.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'prefix' => 'required|string|max:50|unique:gateway_apps',
            'callback_url' => 'required|url|max:255',
        ]);

        GatewayApp::create([
            'name' => $validated['name'],
            'prefix' => $validated['prefix'],
            'callback_url' => $validated['callback_url'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.gateway_apps.index')->with('success', 'Gateway App created successfully.');
    }

    public function edit(GatewayApp $gatewayApp)
    {
        return view('admin.gateway_apps.edit', compact('gatewayApp'));
    }

    public function update(Request $request, GatewayApp $gatewayApp)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'prefix' => 'required|string|max:50|unique:gateway_apps,prefix,' . $gatewayApp->id,
            'callback_url' => 'required|url|max:255',
        ]);

        $gatewayApp->update([
            'name' => $validated['name'],
            'prefix' => $validated['prefix'],
            'callback_url' => $validated['callback_url'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.gateway_apps.index')->with('success', 'Gateway App updated successfully.');
    }

    public function destroy(GatewayApp $gatewayApp)
    {
        $gatewayApp->delete();
        return redirect()->route('admin.gateway_apps.index')->with('success', 'Gateway App deleted successfully.');
    }
}

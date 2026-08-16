<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompanyProfile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class CompanyProfileController extends Controller
{
    public function index(Request $request)
    {
        $profile = CompanyProfile::query()->first();
        $activeTab = $request->get('tab', 'profile');

        // Database Statistics
        $dbStats = [];
        $dbTables = [];
        try {
            $dbName = config('database.connections.' . config('database.default') . '.database');
            $tables = DB::select('SHOW TABLE STATUS');
            $totalSize = 0;
            $totalRows = 0;

            foreach ($tables as $table) {
                $size = ($table->Data_length + $table->Index_length);
                $totalSize += $size;
                $totalRows += $table->Rows;
                $dbTables[] = [
                    'name' => $table->Name,
                    'rows' => $table->Rows,
                    'size' => round($size / 1024, 2) . ' KB',
                    'engine' => $table->Engine,
                    'collation' => $table->Collation,
                ];
            }

            $dbStats = [
                'name' => $dbName,
                'connection' => config('database.default'),
                'tables_count' => count($tables),
                'total_rows' => $totalRows,
                'total_size' => round($totalSize / (1024 * 1024), 2) . ' MB',
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
            ];
        } catch (\Throwable $e) {
            $dbStats = [
                'name' => config('database.connections.' . config('database.default') . '.database'),
                'connection' => config('database.default'),
                'tables_count' => 0,
                'total_rows' => 0,
                'total_size' => '0 MB',
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
                'error' => $e->getMessage(),
            ];
        }

        return view('admin.company.index', compact('profile', 'activeTab', 'dbStats', 'dbTables'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'founded_year' => 'nullable|string|max:10',
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'website' => 'nullable|url',
            'youtube' => 'nullable|url',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg,ico|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg,ico|max:1024',
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

        return redirect()->route('admin.company.index', ['tab' => 'profile'])->with('success', 'Profil perusahaan berhasil disimpan.');
    }

    public function optimizeDatabase(Request $request)
    {
        $action = $request->input('action');

        try {
            if ($action === 'clear_cache') {
                Artisan::call('optimize:clear');
                return redirect()->route('admin.company.index', ['tab' => 'database'])->with('success', 'Cache sistem & aplikasi berhasil dibersihkan!');
            } elseif ($action === 'optimize') {
                Artisan::call('optimize');
                return redirect()->route('admin.company.index', ['tab' => 'database'])->with('success', 'Aplikasi berhasil dioptimasi (Route, Config & View cached)!');
            } elseif ($action === 'migrate') {
                Artisan::call('migrate', ['--force' => true]);
                return redirect()->route('admin.company.index', ['tab' => 'database'])->with('success', 'Migrasi database berhasil dijalankan!');
            }
        } catch (\Throwable $e) {
            return redirect()->route('admin.company.index', ['tab' => 'database'])->with('error', 'Gagal: ' . $e->getMessage());
        }

        return redirect()->route('admin.company.index', ['tab' => 'database']);
    }
}

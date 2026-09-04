<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompanyProfile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

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
                    'name'      => $table->Name,
                    'rows'      => $table->Rows,
                    'size'      => round($size / 1024, 2) . ' KB',
                    'engine'    => $table->Engine,
                    'collation' => $table->Collation,
                ];
            }

            $dbStats = [
                'name'            => $dbName,
                'connection'      => config('database.default'),
                'tables_count'    => count($tables),
                'total_rows'      => $totalRows,
                'total_size'      => round($totalSize / (1024 * 1024), 2) . ' MB',
                'php_version'     => PHP_VERSION,
                'laravel_version' => app()->version(),
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
            ];
        } catch (\Throwable $e) {
            $dbStats = [
                'name'            => config('database.connections.' . config('database.default') . '.database'),
                'connection'      => config('database.default'),
                'tables_count'    => 0,
                'total_rows'      => 0,
                'total_size'      => '0 MB',
                'php_version'     => PHP_VERSION,
                'laravel_version' => app()->version(),
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
                'error'           => $e->getMessage(),
            ];
        }

        // List existing backups
        $backups = [];
        Storage::disk('local')->makeDirectory('backups');
        $files = Storage::disk('local')->files('backups');
        foreach ($files as $file) {
            if (Str::endsWith($file, ['.sql', '.sql.gz'])) {
                $backups[] = [
                    'filename'   => basename($file),
                    'size'       => $this->formatBytes(Storage::disk('local')->size($file)),
                    'created_at' => date('d M Y H:i', Storage::disk('local')->lastModified($file)),
                ];
            }
        }
        usort($backups, fn($a, $b) => strcmp($b['filename'], $a['filename']));

        return view('admin.company.index', compact('profile', 'activeTab', 'dbStats', 'dbTables', 'backups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name'      => 'required|string|max:255',
            'tagline'           => 'nullable|string|max:255',
            'email'             => 'required|email|max:255',
            'phone'             => 'required|string|max:50',
            'whatsapp'          => 'nullable|string|max:50',
            'address'           => 'nullable|string',
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'vision'            => 'nullable|string',
            'mission'           => 'nullable|string',
            'founded_year'      => 'nullable|string|max:10',
            'facebook'          => 'nullable|url',
            'instagram'         => 'nullable|url',
            'linkedin'          => 'nullable|url',
            'website'           => 'nullable|url',
            'youtube'           => 'nullable|url',
            'logo'              => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,ico|max:2048',
            'favicon'           => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,ico|max:1024',
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

    // ===================== BACKUP =====================

    public function backupDatabase()
    {
        $dbConfig = config('database.connections.' . config('database.default'));
        $dbName   = $dbConfig['database'];
        $dbUser   = $dbConfig['username'];
        $dbPass   = $dbConfig['password'];
        $dbHost   = $dbConfig['host'];
        $dbPort   = $dbConfig['port'] ?? 3306;

        $filename = 'backup_' . $dbName . '_' . now()->format('Ymd_His') . '.sql';
        $dir      = storage_path('app/backups');
        $path     = $dir . DIRECTORY_SEPARATOR . $filename;

        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        // Coba mysqldump terlebih dahulu
        $passFlag = $dbPass ? '-p' . $dbPass : '';
        $command  = sprintf(
            'mysqldump -h %s -P %s -u %s %s %s > %s 2>&1',
            escapeshellarg($dbHost),
            (int) $dbPort,
            escapeshellarg($dbUser),
            $passFlag,
            escapeshellarg($dbName),
            escapeshellarg($path)
        );

        exec($command, $output, $returnCode);

        // Fallback ke PHP jika mysqldump gagal
        if ($returnCode !== 0 || !file_exists($path) || filesize($path) === 0) {
            try {
                $this->backupViaPHP($path, $dbName);
            } catch (\Throwable $e) {
                return redirect()->route('admin.company.index', ['tab' => 'database'])
                    ->with('error', 'Backup gagal: ' . $e->getMessage());
            }
        }

        return response()->download($path, $filename, ['Content-Type' => 'application/sql'])
            ->deleteFileAfterSend(false);
    }

    /**
     * Fallback backup menggunakan PHP murni (tanpa mysqldump).
     */
    private function backupViaPHP(string $path, string $dbName): void
    {
        $tables   = DB::select('SHOW TABLES');
        $tableKey = 'Tables_in_' . $dbName;
        $sql      = "-- Database Backup: {$dbName}\n-- Generated: " . now() . "\n-- Method: PHP\n\nSET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $tableObj) {
            $table = $tableObj->$tableKey;

            $createRow = DB::select("SHOW CREATE TABLE `{$table}`");
            $createSql = $createRow[0]->{'Create Table'};
            $sql      .= "DROP TABLE IF EXISTS `{$table}`;\n{$createSql};\n\n";

            $rows = DB::table($table)->get();
            if ($rows->count() > 0) {
                $chunks = $rows->chunk(500);
                foreach ($chunks as $chunk) {
                    $valueList = [];
                    foreach ($chunk as $row) {
                        $escaped = array_map(function ($val) {
                            return is_null($val) ? 'NULL' : "'" . addslashes((string) $val) . "'";
                        }, (array) $row);
                        $valueList[] = '(' . implode(', ', $escaped) . ')';
                    }
                    $sql .= "INSERT INTO `{$table}` VALUES\n" . implode(",\n", $valueList) . ";\n\n";
                }
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
        file_put_contents($path, $sql);
    }

    public function downloadBackup(string $filename)
    {
        // Security: hanya izinkan nama file yang aman
        if (!preg_match('/^[\w\-\.]+\.(sql|sql\.gz)$/', $filename)) {
            abort(400, 'Nama file tidak valid.');
        }

        $path = storage_path('app/backups/' . $filename);

        if (!file_exists($path)) {
            abort(404, 'File backup tidak ditemukan.');
        }

        return response()->download($path, $filename);
    }

    public function deleteBackup(string $filename)
    {
        if (!preg_match('/^[\w\-\.]+\.(sql|sql\.gz)$/', $filename)) {
            abort(400, 'Nama file tidak valid.');
        }

        Storage::disk('local')->delete('backups/' . $filename);

        return redirect()->route('admin.company.index', ['tab' => 'database'])
            ->with('success', "Backup '{$filename}' berhasil dihapus.");
    }

    public function restoreDatabase(Request $request)
    {
        $request->validate([
            'sql_file' => 'required|file|max:102400', // max 100MB
        ]);

        $file = $request->file('sql_file');
        $ext  = strtolower($file->getClientOriginalExtension());

        if (!in_array($ext, ['sql', 'txt'])) {
            return redirect()->route('admin.company.index', ['tab' => 'database'])
                ->with('error', 'Format file tidak didukung. Gunakan file .sql');
        }

        $sql = file_get_contents($file->getRealPath());

        if (empty(trim($sql))) {
            return redirect()->route('admin.company.index', ['tab' => 'database'])
                ->with('error', 'File SQL kosong atau tidak valid.');
        }

        try {
            DB::unprepared($sql);
            return redirect()->route('admin.company.index', ['tab' => 'database'])
                ->with('success', 'Database berhasil direstore dari file: ' . $file->getClientOriginalName());
        } catch (\Throwable $e) {
            return redirect()->route('admin.company.index', ['tab' => 'database'])
                ->with('error', 'Restore gagal: ' . $e->getMessage());
        }
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }
}

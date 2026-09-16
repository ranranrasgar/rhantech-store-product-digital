<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
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

        // List existing database backups
        $backups = [];
        $backupDir = storage_path('app/backups');
        if (!file_exists($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $files = glob($backupDir . DIRECTORY_SEPARATOR . '*.{sql,sql.gz}', GLOB_BRACE) ?: [];
        foreach ($files as $filePath) {
            $filename = basename($filePath);
            $backups[] = [
                'filename'   => $filename,
                'size'       => $this->formatBytes(filesize($filePath)),
                'created_at' => date('d M Y H:i', filemtime($filePath)),
            ];
        }
        usort($backups, fn($a, $b) => strcmp($b['filename'], $a['filename']));

        // List existing media backups (.zip)
        $mediaBackups = [];
        $mediaBackupFiles = glob($backupDir . DIRECTORY_SEPARATOR . '*.zip') ?: [];
        foreach ($mediaBackupFiles as $filePath) {
            $filename = basename($filePath);
            $mediaBackups[] = [
                'filename'   => $filename,
                'size'       => $this->formatBytes(filesize($filePath)),
                'created_at' => date('d M Y H:i', filemtime($filePath)),
            ];
        }
        usort($mediaBackups, fn($a, $b) => strcmp($b['filename'], $a['filename']));

        // Media Storage Stats
        $mediaStats = $this->getMediaStorageStats();

        return view('admin.company.index', compact('profile', 'activeTab', 'dbStats', 'dbTables', 'backups', 'mediaBackups', 'mediaStats'));
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
            'facebook'          => 'nullable|string',
            'instagram'         => 'nullable|string',
            'linkedin'          => 'nullable|string',
            'website'           => 'nullable|string',
            'youtube'           => 'nullable|string',
            'social_links'      => 'nullable|array',
            'logo'              => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,ico|max:2048',
            'favicon'           => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,ico|max:1024',
            'hero_mode'         => 'nullable|string|in:custom,top_stores,both',
            'hero_badge'        => 'nullable|string|max:255',
            'hero_title'        => 'nullable|string|max:255',
            'hero_subtitle'     => 'nullable|string',
            'hero_image'        => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'hero_btn_primary_text'   => 'nullable|string|max:100',
            'hero_btn_primary_url'    => 'nullable|string|max:255',
            'hero_btn_secondary_text' => 'nullable|string|max:100',
            'hero_btn_secondary_url'  => 'nullable|string|max:255',
            'hero_stats_val'    => 'nullable|string|max:50',
            'hero_stats_label'  => 'nullable|string|max:100',
        ], [
            'logo.max' => 'Ukuran logo tidak boleh melebihi 2 MB.',
            'favicon.max' => 'Ukuran favicon tidak boleh melebihi 1 MB.',
            'hero_image.max' => 'Ukuran gambar hero tidak boleh melebihi 2 MB.',
            'hero_image.image' => 'File hero harus berupa format gambar valid.',
            'logo.image' => 'File logo harus berupa format gambar valid.',
            'favicon.image' => 'File favicon harus berupa format gambar valid.',
        ]);

        // Process and normalize social_links repeater
        if ($request->has('social_links') && is_array($request->social_links)) {
            $socialLinks = [];
            $socialMap = [
                'facebook'  => null,
                'instagram' => null,
                'linkedin'  => null,
                'youtube'   => null,
                'website'   => null,
            ];

            foreach ($request->social_links as $item) {
                if (is_array($item) && !empty(trim($item['url'] ?? ''))) {
                    $platform = trim($item['platform'] ?? 'custom');
                    $name = !empty(trim($item['name'] ?? '')) ? trim($item['name']) : ucfirst($platform);
                    $url = trim($item['url']);

                    if ($platform === 'whatsapp' && !str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
                        $cleanPhone = preg_replace('/[^0-9]/', '', $url);
                        if (str_starts_with($cleanPhone, '0')) {
                            $cleanPhone = '62' . substr($cleanPhone, 1);
                        }
                        $url = "https://wa.me/{$cleanPhone}";
                    } elseif (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://') && !str_starts_with($url, 'mailto:')) {
                        $url = 'https://' . $url;
                    }

                    $socialLinks[] = [
                        'platform' => $platform,
                        'name'     => $name,
                        'url'      => $url,
                    ];

                    if (array_key_exists($platform, $socialMap) && empty($socialMap[$platform])) {
                        $socialMap[$platform] = $url;
                    }
                }
            }

            $validated['social_links'] = $socialLinks;
            foreach ($socialMap as $col => $val) {
                $validated[$col] = $val;
            }
        }

        $profile = CompanyProfile::query()->first();

        if (!$profile) {
            $profile = new CompanyProfile();
        }

        if ($request->hasFile('logo')) {
            if ($profile->logo) Storage::disk('public')->delete($profile->logo);
            $validated['logo'] = $this->optimizeAndStoreImage($request->file('logo'), 'company', 'public', 600, 600);
        }

        if ($request->hasFile('favicon')) {
            if ($profile->favicon) Storage::disk('public')->delete($profile->favicon);
            $validated['favicon'] = $this->optimizeAndStoreImage($request->file('favicon'), 'company', 'public', 128, 128);
        }

        if ($request->hasFile('hero_image')) {
            if ($profile->hero_image) Storage::disk('public')->delete($profile->hero_image);
            $validated['hero_image'] = $this->optimizeAndStoreImage($request->file('hero_image'), 'company/hero', 'public', 1600, 2000);
        }

        if ($profile->exists) {
            $profile->update($validated);
        } else {
            CompanyProfile::create($validated);
        }

        \Illuminate\Support\Facades\Cache::forget('site_company_profile');

        $activeTab = $request->input('redirect_tab', 'profile');

        return redirect()->route('admin.company.index', ['tab' => $activeTab])->with('success', 'Pengaturan berhasil disimpan.');
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
        // Gunakan --password= dan arahkan stderr ke /dev/null agar warning tidak masuk file SQL
        $passFlag  = $dbPass ? '--password=' . $dbPass : '';
        $nullDev   = PHP_OS_FAMILY === 'Windows' ? 'NUL' : '/dev/null';
        $command   = sprintf(
            'mysqldump -h %s -P %s -u %s %s %s > %s 2>%s',
            escapeshellarg($dbHost),
            (int) $dbPort,
            escapeshellarg($dbUser),
            $passFlag,
            escapeshellarg($dbName),
            escapeshellarg($path),
            $nullDev
        );

        exec($command, $output, $returnCode);

        // Fallback ke PHP jika mysqldump gagal
        if ($returnCode !== 0 || !file_exists($path) || filesize($path) === 0) {
            try {
                $this->backupViaPHP($path, $dbName);
            } catch (\Throwable $e) {
                return redirect()->route('admin.company.index', ['tab' => 'backup'])
                    ->with('error', 'Backup gagal: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.company.index', ['tab' => 'backup'])
            ->with('success', "Backup berhasil dibuat: {$filename}. Anda dapat mengunduh atau merestore file dari daftar tabel kapan saja.");
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

        $path = storage_path('app/backups/' . $filename);
        if (file_exists($path)) {
            unlink($path);
        }

        return redirect()->route('admin.company.index', ['tab' => 'backup'])
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
            return redirect()->route('admin.company.index', ['tab' => 'backup'])
                ->with('error', 'Format file tidak didukung. Gunakan file .sql');
        }

        $raw = file_get_contents($file->getRealPath());

        if (empty(trim($raw))) {
            return redirect()->route('admin.company.index', ['tab' => 'backup'])
                ->with('error', 'File SQL kosong atau tidak valid.');
        }

        // Hapus baris warning mysqldump (baris non-SQL di awal file)
        // Contoh: "mysqldump: [Warning] Using a password on the command line..."
        $lines = explode("\n", $raw);
        $cleanLines = array_filter($lines, function ($line) {
            $trimmed = ltrim($line);
            // Hapus baris yang dimulai dengan 'mysqldump:' atau warning serupa
            if (preg_match('/^mysqldump\s*:/i', $trimmed)) return false;
            if (preg_match('/^\[Warning\]/i', $trimmed)) return false;
            return true;
        });
        $sql = implode("\n", $cleanLines);

        if (empty(trim($sql))) {
            return redirect()->route('admin.company.index', ['tab' => 'backup'])
                ->with('error', 'File SQL kosong atau tidak valid setelah pembersihan.');
        }

        try {
            DB::unprepared($sql);
            // Hapus session backup_download agar script auto-download tidak terpicu setelah restore
            session()->forget('backup_download');
            return redirect()->route('admin.company.index', ['tab' => 'backup'])
                ->with('success', 'Database berhasil direstore dari file: ' . $file->getClientOriginalName());
        } catch (\Throwable $e) {
            return redirect()->route('admin.company.index', ['tab' => 'backup'])
                ->with('error', 'Restore gagal: ' . $e->getMessage());
        }
    }

    // ===================== MEDIA BACKUP & RESTORE =====================

    /**
     * Backup seluruh file media/upload di storage/app/public menjadi file .zip
     */
    public function backupMedia()
    {
        if (!class_exists(\ZipArchive::class)) {
            return redirect()->route('admin.company.index', ['tab' => 'backup'])
                ->with('error', 'Ekstensi PHP ZipArchive tidak terinstall/aktif di server PHP Anda.');
        }

        $mediaSource = storage_path('app/public');
        if (!file_exists($mediaSource)) {
            return redirect()->route('admin.company.index', ['tab' => 'backup'])
                ->with('error', 'Direktori media (storage/app/public) tidak ditemukan.');
        }

        $dir = storage_path('app/backups');
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $filename = 'backup_media_' . now()->format('Ymd_His') . '.zip';
        $zipPath  = $dir . DIRECTORY_SEPARATOR . $filename;

        @set_time_limit(300);
        @ini_set('memory_limit', '512M');

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return redirect()->route('admin.company.index', ['tab' => 'backup'])
                ->with('error', 'Gagal membuat file arsip ZIP.');
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($mediaSource, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        $fileCount = 0;
        foreach ($files as $file) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($mediaSource) + 1);

            // Normalisasi separator path untuk ZIP
            $relativePath = str_replace('\\', '/', $relativePath);

            if ($file->isDir()) {
                $zip->addEmptyDir($relativePath);
            } elseif ($file->isFile()) {
                $zip->addFile($filePath, $relativePath);
                $fileCount++;
            }
        }

        $zip->close();

        if (!file_exists($zipPath) || filesize($zipPath) === 0) {
            if (file_exists($zipPath)) unlink($zipPath);
            return redirect()->route('admin.company.index', ['tab' => 'backup'])
                ->with('error', 'Media backup gagal atau kosong.');
        }

        $fileSizeStr = $this->formatBytes(filesize($zipPath));

        return redirect()->route('admin.company.index', ['tab' => 'backup'])
            ->with('success', "Backup media berhasil dibuat: {$filename} ({$fileSizeStr}, {$fileCount} file).");
    }

    public function downloadMediaBackup(string $filename)
    {
        if (!preg_match('/^[\w\-\.]+\.zip$/', $filename)) {
            abort(400, 'Nama file media tidak valid.');
        }

        $path = storage_path('app/backups/' . $filename);

        if (!file_exists($path)) {
            abort(404, 'File backup media tidak ditemukan.');
        }

        return response()->download($path, $filename);
    }

    public function deleteMediaBackup(string $filename)
    {
        if (!preg_match('/^[\w\-\.]+\.zip$/', $filename)) {
            abort(400, 'Nama file tidak valid.');
        }

        $path = storage_path('app/backups/' . $filename);
        if (file_exists($path)) {
            unlink($path);
        }

        return redirect()->route('admin.company.index', ['tab' => 'backup'])
            ->with('success', "Backup media '{$filename}' berhasil dihapus.");
    }

    public function restoreMedia(Request $request)
    {
        $request->validate([
            'media_zip' => 'required|file|mimes:zip|max:512000', // max 500MB
        ]);

        if (!class_exists(\ZipArchive::class)) {
            return redirect()->route('admin.company.index', ['tab' => 'backup'])
                ->with('error', 'Ekstensi PHP ZipArchive tidak terinstall/aktif di server.');
        }

        $file = $request->file('media_zip');
        $zip = new \ZipArchive();

        if ($zip->open($file->getRealPath()) !== true) {
            return redirect()->route('admin.company.index', ['tab' => 'backup'])
                ->with('error', 'Gagal membuka file ZIP media.');
        }

        $destination = storage_path('app/public');
        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }

        @set_time_limit(300);
        @ini_set('memory_limit', '512M');

        // Ekstrak file zip ke storage/app/public
        $zip->extractTo($destination);
        $totalFiles = $zip->numFiles;
        $zip->close();

        // Pastikan symlink storage terhubung
        try {
            Artisan::call('storage:link');
        } catch (\Throwable $e) {
            // Abaikan jika sudah ada symlink
        }

        return redirect()->route('admin.company.index', ['tab' => 'backup'])
            ->with('success', "Media berhasil direstore ({$totalFiles} file/folder diekstrak) ke storage publik.");
    }

    /**
     * Hitung informasi statistik file media di storage/app/public
     */
    private function getMediaStorageStats(): array
    {
        $mediaPath = storage_path('app/public');
        if (!file_exists($mediaPath)) {
            return [
                'total_size' => '0 B',
                'file_count' => 0,
                'folders'    => [],
            ];
        }

        $totalBytes = 0;
        $fileCount = 0;
        $folders = [];

        try {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($mediaPath, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($iterator as $item) {
                if ($item->isFile()) {
                    $size = $item->getSize();
                    $totalBytes += $size;
                    $fileCount++;

                    $rel = substr($item->getPath(), strlen($mediaPath) + 1);
                    $firstFolder = explode(DIRECTORY_SEPARATOR, $rel)[0] ?: 'root';

                    if (!isset($folders[$firstFolder])) {
                        $folders[$firstFolder] = ['count' => 0, 'bytes' => 0];
                    }
                    $folders[$firstFolder]['count']++;
                    $folders[$firstFolder]['bytes'] += $size;
                }
            }
        } catch (\Throwable $e) {
            // handle error gracefully
        }

        $formattedFolders = [];
        foreach ($folders as $folderName => $data) {
            $formattedFolders[] = [
                'name'  => $folderName,
                'count' => $data['count'],
                'size'  => $this->formatBytes($data['bytes']),
            ];
        }

        return [
            'total_size' => $this->formatBytes($totalBytes),
            'total_bytes'=> $totalBytes,
            'file_count' => $fileCount,
            'folders'    => $formattedFolders,
        ];
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }

    /**
     * Optimize and store uploaded image as WebP.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string $disk
     * @param int $maxWidth
     * @param int $maxHeight
     * @return string
     */
    protected function optimizeAndStoreImage(UploadedFile $file, string $directory, string $disk = 'public', int $maxWidth = 1600, int $maxHeight = 2000): string
    {
        $extension = strtolower($file->getClientOriginalExtension());

        // SVG, ICO, dan GIF animasi langsung disimpan tanpa re-encode
        if (in_array($extension, ['svg', 'gif', 'ico'])) {
            return $file->store($directory, $disk);
        }

        if (function_exists('imagecreatefromstring') && function_exists('imagewebp')) {
            try {
                $imageContent = file_get_contents($file->getRealPath());
                $srcImage = @imagecreatefromstring($imageContent);

                if ($srcImage !== false) {
                    // Perbaiki rotasi orientasi kamera HP (EXIF) jika ada
                    if (function_exists('exif_read_data')) {
                        try {
                            $exif = @exif_read_data($file->getRealPath());
                            if (!empty($exif['Orientation'])) {
                                switch ($exif['Orientation']) {
                                    case 3: $srcImage = imagerotate($srcImage, 180, 0); break;
                                    case 6: $srcImage = imagerotate($srcImage, -90, 0); break;
                                    case 8: $srcImage = imagerotate($srcImage, 90, 0); break;
                                }
                            }
                        } catch (\Throwable $e) {}
                    }

                    $origWidth = imagesx($srcImage);
                    $origHeight = imagesy($srcImage);

                    $targetWidth = $origWidth;
                    $targetHeight = $origHeight;
                    if ($origWidth > $maxWidth || $origHeight > $maxHeight) {
                        $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
                        $targetWidth = (int) max(1, round($origWidth * $ratio));
                        $targetHeight = (int) max(1, round($origHeight * $ratio));
                    }

                    $dstImage = imagecreatetruecolor($targetWidth, $targetHeight);
                    imagealphablending($dstImage, false);
                    imagesavealpha($dstImage, true);
                    $transparent = imagecolorallocatealpha($dstImage, 255, 255, 255, 127);
                    imagefilledrectangle($dstImage, 0, 0, $targetWidth, $targetHeight, $transparent);

                    imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $origWidth, $origHeight);

                    ob_start();
                    imagewebp($dstImage, null, 82);
                    $webpContent = ob_get_clean();

                    imagedestroy($srcImage);
                    imagedestroy($dstImage);

                    if (!empty($webpContent)) {
                        $filename = Str::random(40) . '.webp';
                        $path = rtrim($directory, '/') . '/' . $filename;
                        Storage::disk($disk)->put($path, $webpContent, 'public');
                        return $path;
                    }
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Server image optimization error: ' . $e->getMessage());
            }
        }

        return $file->store($directory, $disk);
    }
}


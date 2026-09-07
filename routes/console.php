<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('r2:sync-local {--delete : Hapus file lokal setelah berhasil diupload ke R2}', function () {
    /** @var \Illuminate\Filesystem\FilesystemAdapter $localDisk */
    $localDisk = \Illuminate\Support\Facades\Storage::disk('public');
    /** @var \Illuminate\Filesystem\FilesystemAdapter $r2Disk */
    $r2Disk = \Illuminate\Support\Facades\Storage::disk('r2');

    $this->info("Memindai file di storage/app/public...");
    $files = $localDisk->allFiles();

    if (empty($files)) {
        $this->warn("Tidak ada file yang ditemukan di storage/app/public.");
        return;
    }

    $this->info("Ditemukan " . count($files) . " file. Memulai sinkronisasi ke Cloudflare R2...");
    $bar = $this->output->createProgressBar(count($files));
    $bar->start();

    $success = 0;
    $failed = 0;

    foreach ($files as $file) {
        try {
            $stream = $localDisk->readStream($file);
            $mime = $localDisk->mimeType($file);

            $r2Disk->put($file, $stream, [
                'visibility' => 'public',
                'mimetype' => $mime,
            ]);

            if (is_resource($stream)) {
                fclose($stream);
            }

            if ($this->option('delete')) {
                $localDisk->delete($file);
            }

            $success++;
        } catch (\Throwable $e) {
            $failed++;
            $this->error("\nGagal mengunggah {$file}: " . $e->getMessage());
        }
        $bar->advance();
    }

    $bar->finish();
    $this->newLine(2);
    $this->info("Sinkronisasi Selesai! Berhasil: {$success}, Gagal: {$failed}");
    $this->info("File kini dapat diakses melalui: " . config('filesystems.disks.r2.url') . "/<path>");
})->purpose('Sinkronisasi semua file media lokal ke Cloudflare R2');

Artisan::command('r2:check', function () {
    /** @var \Illuminate\Filesystem\FilesystemAdapter $r2 */
    $r2 = \Illuminate\Support\Facades\Storage::disk('r2');
    $files = $r2->allFiles();
    $this->info("Total file di R2: " . count($files));
    $sample = array_slice($files, 0, 5);
    foreach ($sample as $file) {
        $url = $r2->url($file);
        $this->line("- File: {$file} => URL: {$url}");

        $ctx = stream_context_create(['http' => ['ignore_errors' => true, 'timeout' => 5]]);
        $headers = @get_headers($url, true, $ctx);
        $status = $headers ? ($headers[0] ?? 'NO STATUS') : 'FAILED CONNECT';
        $this->line("  Status HTTP: {$status}");
    }
})->purpose('Cek sampel file dan status akses di Cloudflare R2');

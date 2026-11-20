<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BackupDownloadController extends Controller
{
    public function __invoke(string $filename): BinaryFileResponse
    {
        $safeName = basename($filename);
        if (
            $safeName === '' ||
            $safeName !== $filename ||
            ! str_ends_with($safeName, '.zip')
        ) {
            abort(404);
        }

        $backupName = (string) config('backup.backup.name', 'Laravel');
        $relativePath = $backupName . '/' . $safeName;
        $disk = Storage::disk('local');

        if (! $disk->exists($relativePath)) {
            abort(404);
        }

        $fullPath = $disk->path($relativePath);
        if (! is_file($fullPath) || ! is_readable($fullPath)) {
            Log::warning("BackupDownloadController unreadable file: {$fullPath}");
            abort(404);
        }

        return response()->download($fullPath, $safeName, [
            'Content-Type' => 'application/zip',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}

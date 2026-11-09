<?php
namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;

class BackupManager extends Component
{
    public $backups = [];
    public $message = '';
    public $isGenerating = false;
    
    // Statistik
    public $totalBackups = 0;
    public $totalSize = '0 MB';
    public $lastBackup = 'None yet';
    public $diskFree = '0 GB';
    
    // Filter
    public $filter = 'semua'; 

    public function mount()
    {
        $this->loadData();
    }

    public function setFilter($type)
    {
        $this->filter = $type;
        $this->loadData(); // Load ulang saat filter diubah agar tabel terupdate
    }

    public function loadData()
    {
        $disk = Storage::disk('local');
        $backupName = config('backup.backup.name', 'Laravel');
        
        $freeSpace = @disk_free_space(storage_path());
        $this->diskFree = $freeSpace ? Number::fileSize($freeSpace, precision: 2) : 'N/A';

        if (!$disk->exists($backupName)) {
            $disk->makeDirectory($backupName);
        }

        $files = $disk->files($backupName);
        $allBackups = [];
        $totalBytes = 0;
        $latestTime = 0;

        foreach ($files as $file) {
            if (!str_ends_with($file, '.zip')) continue;

            $size = $disk->size($file);
            $time = $disk->lastModified($file);
            $totalBytes += $size;
            if ($time > $latestTime) $latestTime = $time;

            $filename = basename($file);
            $type = 'full';
            if (str_contains($filename, 'db-database') || str_contains($filename, 'db')) {
                $type = 'database';
            } elseif (str_contains($filename, 'files')) {
                $type = 'files';
            }

            $allBackups[] = [
                'name' => $filename,
                'size_raw' => $size,
                'size' => Number::fileSize($size, precision: 2),
                'time_raw' => $time,
                'time' => date('d/m/Y H:i', $time),
                'type' => $type
            ];
        }

        $this->totalBackups = count($allBackups);
        $this->totalSize = Number::fileSize($totalBytes, precision: 2);
        $this->lastBackup = $latestTime > 0 ? \Carbon\Carbon::createFromTimestamp($latestTime)->diffForHumans() : 'None yet';

        if ($this->filter !== 'semua') {
            $allBackups = array_filter($allBackups, fn($b) => $b['type'] === $this->filter);
        }

        usort($allBackups, fn($a, $b) => $b['time_raw'] <=> $a['time_raw']);
        $this->backups = array_values($allBackups); // Re-index array
    }

    // --- ACTIONS ---
    
    public function downloadBackup($filename)
    {
        $backupName = config('backup.backup.name', 'Laravel');
        $path = $backupName . '/' . $filename;
        
        if (Storage::disk('local')->exists($path)) {
            return Storage::disk('local')->download($path);
        }
        
        $this->message = "File tidak ditemukan.";
    }

    public function deleteBackup($filename)
    {
        $backupName = config('backup.backup.name', 'Laravel');
        $path = $backupName . '/' . $filename;
        
        if (Storage::disk('local')->exists($path)) {
            Storage::disk('local')->delete($path);
            $this->message = "File backup {$filename} berhasil dihapus.";
            $this->loadData();
        } else {
            $this->message = "File tidak ditemukan.";
        }
    }

    public function runBackup($type)
    {
        $this->isGenerating = true;
        $this->message = '';

        try {
            $options = ['--disable-notifications' => true];
            if ($type === 'database') $options['--only-db'] = true;
            if ($type === 'files') $options['--only-files'] = true;

            Artisan::call('backup:run', $options);
            $this->message = "Backup {$type} successfully created!";
            $this->loadData();
        } catch (\Exception $e) {
            $this->message = 'Failed to create backup: ' . $e->getMessage();
        }

        $this->isGenerating = false;
    }

    public function cleanupOld()
    {
        try {
            Artisan::call('backup:clean');
            $this->message = 'Old backup cleanup successful.';
            $this->loadData();
        } catch (\Exception $e) {
            $this->message = 'Gagal membersihkan backup: ' . $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.backup-manager')->layout('layouts::app');
    }
}

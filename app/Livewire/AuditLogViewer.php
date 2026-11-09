<?php
namespace App\Livewire;
use App\Models\AuditLog;
use Livewire\Component;
use Livewire\WithPagination;

class AuditLogViewer extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.audit-log-viewer', [
            'logs' => AuditLog::latest()->paginate(15)
        ])->layout('layouts::app');
    }
}

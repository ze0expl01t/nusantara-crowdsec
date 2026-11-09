<?php
namespace App\Livewire;

use App\Services\CrowdSecLapiClient;
use App\Models\AuditLog;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class DecisionManager extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 15;
    
    // Modal State Unban
    public bool $showUnbanModal = false;
    public ?string $unbanId = null;
    public ?string $unbanIp = null;

    // Modal State Manual Ban
    public bool $showBanModal = false;
    public string $banIp = '';
    public string $banReason = 'manual_ban';
    public string $banDuration = '4h';

    public function updatedSearch()
    {
        $this->resetPage(); 
    }

    // Fungsi Refresh hanya untuk trigger render ulang
    public function refreshData()
    {
        // Cukup panggil $this->render() secara otomatis oleh Livewire
        session()->flash('message', 'Data refreshed.');
    }

    // --- MANUAL BAN ---
    public function openBanModal()
    {
        $this->reset(['banIp', 'banReason', 'banDuration']);
        $this->showBanModal = true;
    }

    public function executeBan(CrowdSecLapiClient $client)
    {
        $this->validate([
            'banIp' => 'required|ip',
            'banReason' => 'required|string|max:255',
            'banDuration' => 'required|string'
        ]);

        if ($client->createDecision($this->banIp, $this->banDuration, $this->banReason)) {
            AuditLog::create([
                'action' => 'manual_ban', 
                'target' => $this->banIp, 
                'actor' => Auth::user()->email ?? 'system',
                'meta' => ['duration' => $this->banDuration, 'reason' => $this->banReason]
            ]);
            session()->flash('message', "IP {$this->banIp} successfully banned for {$this->banDuration}.");
        } else {
            session()->flash('error', "Failed to ban IP {$this->banIp}.");
        }
        
        $this->showBanModal = false;
    }

    // --- UNBAN ---
    public function confirmUnban($id, $ip)
    {
        $this->unbanId = $id;
        $this->unbanIp = $ip;
        $this->showUnbanModal = true;
    }

    public function executeUnban(CrowdSecLapiClient $client)
    {
        if ($client->deleteDecision($this->unbanId)) {
            AuditLog::create([
                'action' => 'unban_ip', 'target' => $this->unbanIp, 'actor' => Auth::user()->email ?? 'system',
                'meta' => ['decision_id' => $this->unbanId]
            ]);
            session()->flash('message', "IP {$this->unbanIp} successfully unbanned.");
        } else {
            session()->flash('error', "Failed to unban.");
        }
        
        $this->showUnbanModal = false;
        $this->reset(['unbanId', 'unbanIp']);
    }

    public function render(CrowdSecLapiClient $client)
    {
        // 1. Ambil data di dalam render() agar TIDAK disimpan di public property
        $rawData = $client->getDecisions(1000);
        $allDecisions = array_values(array_filter($rawData, 'is_array'));

        // 2. Filter Search
        $filtered = array_filter($allDecisions, function($d) {
            if (!is_array($d)) return false;
            return empty($this->search) 
                || stripos($d['value'] ?? '', $this->search) !== false 
                || stripos($d['scenario'] ?? '', $this->search) !== false;
        });
        
        // 3. Pagination
        $currentPage = $this->getPage();
        $paginated = new LengthAwarePaginator(
            array_slice($filtered, ($currentPage - 1) * $this->perPage, $this->perPage),
            count($filtered),
            $this->perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return view('livewire.decision-manager', ['filteredDecisions' => $paginated])->layout('layouts::app');
    }
}

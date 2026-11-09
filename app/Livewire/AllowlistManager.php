<?php
namespace App\Livewire;
use App\Models\WhitelistedIp;
use App\Models\AuditLog;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class AllowlistManager extends Component
{
    use WithPagination;

    public $ip, $label, $note;
    public int $perPage = 10;

    public function add()
    {
        // Validasi: Harus berupa IPv4, IPv6, ATAU format CIDR (IP/Range)
        $this->validate([
            'ip' => [
                'required',
                'string',
                'regex:/^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(\/([0-9]|[1-2][0-9]|3[0-2]))?$/i' // Regex IPv4 / CIDR
            ],
        ], [
            'ip.regex' => 'Invalid IP or CIDR format. Contoh: 192.168.1.1 atau 10.0.0.0/24',
        ]);

        WhitelistedIp::create([
            'ip' => $this->ip, 
            'label' => $this->label, 
            'note' => $this->note
        ]);

        AuditLog::create([
            'action' => 'add_allowlist', 
            'target' => $this->ip, 
            'actor' => Auth::user()->email ?? 'system'
        ]);

        $this->reset(['ip', 'label', 'note']);
        $this->resetPage();
        session()->flash('message', 'IP successfully added to Allowlist.');
    }

    public function delete($id)
    {
        $item = WhitelistedIp::find($id);
        if($item) {
            AuditLog::create([
                'action' => 'remove_allowlist', 
                'target' => $item->ip, 
                'actor' => Auth::user()->email ?? 'system'
            ]);
            $item->delete();
            $this->resetPage();
            session()->flash('message', 'IP successfully removed from Allowlist.');
        }
    }

    public function render()
    {
        $allowlists = WhitelistedIp::latest()->paginate($this->perPage);
        return view('livewire.allowlist-manager', ['allowlists' => $allowlists])->layout('layouts::app');
    }
}

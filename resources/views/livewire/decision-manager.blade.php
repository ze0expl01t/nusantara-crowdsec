<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <!-- Header Page -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="shrink-0">
                <flux:heading size="xl">Manage Blocks (Decisions)</flux:heading>
                <flux:subheading>List of IPs currently blocked by CrowdSec.</flux:subheading>
            </div>
            <div class="flex items-center gap-2 w-full md:w-auto">
                <div class="w-full md:w-64">
                    <flux:input wire:model.live.debounce.300ms="search" placeholder="Search IP or Scenario..." icon="magnifying-glass" />
                </div>
                <flux:button wire:click="openBanModal" icon="no-symbol" variant="danger" class="cursor-pointer whitespace-nowrap">Ban IP</flux:button>
                <flux:button wire:click="refreshData" icon="arrow-path" variant="primary" class="cursor-pointer">Refresh</flux:button>
            </div>
        </div>

        @if (session()->has('message'))
            <flux:callout color="green" icon="check-circle">{{ session('message') }}</flux:callout>
        @endif
        @if (session()->has('error'))
            <flux:callout color="red" icon="exclamation-triangle">{{ session('error') }}</flux:callout>
        @endif

        <!-- Card Tabel -->
        <flux:card class="p-0 overflow-hidden">
            <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
                <flux:heading size="lg">Active Blocklist</flux:heading>
            </div>

            <div class="px-6 py-2">
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>IP Address</flux:table.column>
                        <flux:table.column>Reason (Scenario)</flux:table.column>
                        <flux:table.column>Duration</flux:table.column>
                        <flux:table.column class="text-right">Action</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @forelse ($filteredDecisions as $decision)
                            <flux:table.row :key="$decision['id'] ?? md5(json_encode($decision))">
                                <flux:table.cell class="font-mono">{{ $decision['value'] ?? 'N/A' }}</flux:table.cell>
                                <flux:table.cell>
                                    <flux:badge color="zinc" size="sm">{{ $decision['scenario'] ?? 'N/A' }}</flux:badge>
                                </flux:table.cell>
                                <flux:table.cell class="text-zinc-500 text-sm">
                                    {{ $decision['duration'] ?? '-' }}
                                </flux:table.cell>
                                <flux:table.cell class="text-right">
                                    @if(isset($decision['id']))
                                        <flux:button wire:click="confirmUnban('{{ $decision['id'] }}', '{{ $decision['value'] }}')" variant="danger" size="sm" icon="lock-open" class="cursor-pointer">Unban</flux:button>
                                    @endif
                                </flux:table.cell>
                            </flux:table.row>
                        @empty
                            <flux:table.row>
                                <flux:table.cell colspan="4" class="text-center py-6 text-zinc-500">Data not found.</flux:table.cell>
                            </flux:table.row>
                        @endforelse
                    </flux:table.rows>
                </flux:table>
            </div>
            
            <div class="p-4 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900/50">
                {{ $filteredDecisions->links('livewire.pagination.tailwind') }}
            </div>
        </flux:card>
    </div>

    <!-- MODAL: Manual Ban -->
    <flux:modal wire:model="showBanModal" class="md:w-96">
        <form wire:submit="executeBan" class="space-y-6">
            <div>
                <flux:heading size="lg">Manual Ban IP</flux:heading>
                <flux:subheading>Block an IP address immediately.</flux:subheading>
            </div>
            
            <flux:input wire:model="banIp" label="IP Address" placeholder="192.168.1.100" required icon="globe-alt" />
            
            <flux:select wire:model="banDuration" label="Duration">
                <option value="1h">1 Hour</option>
                <option value="4h">4 Hours</option>
                <option value="24h">24 Hours</option>
                <option value="168h">7 Days</option>
                <option value="8760h">1 Year</option>
            </flux:select>

            <flux:input wire:model="banReason" label="Reason" placeholder="manual_ban" required icon="document-text" />

            <div class="flex gap-2">
                <flux:spacer />
                <flux:button wire:click="$set('showBanModal', false)" variant="ghost" class="cursor-pointer">Cancel</flux:button>
                <flux:button type="submit" variant="danger" class="cursor-pointer" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="executeBan">Ban IP</span>
                    <span wire:loading wire:target="executeBan">Processing...</span>
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <!-- MODAL: Konfirmasi Unban -->
    <flux:modal wire:model="showUnbanModal" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Confirm Unban</flux:heading>
                <flux:subheading>Are you sure you want to unban IP <span class="font-bold font-mono text-white">{{ $unbanIp }}</span>?</flux:subheading>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:button wire:click="$set('showUnbanModal', false)" variant="ghost" class="cursor-pointer">Cancel</flux:button>
                <flux:button wire:click="executeUnban" variant="danger" class="cursor-pointer" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="executeUnban">Yes, Unban</span>
                    <span wire:loading wire:target="executeUnban">Processing...</span>
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>

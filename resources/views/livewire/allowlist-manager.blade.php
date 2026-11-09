<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div>
            <flux:heading size="xl">Manage Allowlist (Excluded IPs)</flux:heading>
            <flux:subheading>List of IPs that will never be blocked by the system.</flux:subheading>
        </div>

        @if (session()->has('message'))
            <flux:callout color="green" icon="check-circle">{{ session('message') }}</flux:callout>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <flux:card class="md:col-span-1 h-fit">
                <form wire:submit="add" class="space-y-4">
                    <flux:heading size="lg">Add New IP</flux:heading>
                    <flux:input wire:model="ip" label="IP / CIDR" placeholder="192.168.1.1" required />
                    <flux:input wire:model="label" label="Label" placeholder="IP Admin" />
                    <flux:input wire:model="note" label="Note" placeholder="Keterangan" />
                    <flux:button type="submit" variant="primary" class="w-full cursor-pointer" wire:loading.attr="disabled">Save to Allowlist</flux:button>
                </form>
            </flux:card>

            <flux:card class="md:col-span-2 p-0 overflow-hidden h-fit">
                <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
                    <flux:heading size="lg">Trusted IP List</flux:heading>
                </div>
                
                <!-- BUNGKUS TABEL DENGAN PADDING -->
                <div class="px-6 py-2">
                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>IP / CIDR</flux:table.column>
                            <flux:table.column>Label</flux:table.column>
                            <flux:table.column><div class="text-right w-full">Action</div></flux:table.column>
                        </flux:table.columns>
                        <flux:table.rows>
                            @forelse ($allowlists as $item)
                                <flux:table.row :key="$item->id">
                                    <flux:table.cell class="font-mono">{{ $item->ip }}</flux:table.cell>
                                    <flux:table.cell>{{ $item->label ?? '-' }}</flux:table.cell>
                                    <flux:table.cell><div class="flex justify-end w-full">
                                        <flux:button wire:click="delete({{ $item->id }})" wire:confirm="Delete?" variant="danger" size="sm" icon="trash" class="cursor-pointer">Delete</flux:button></div></flux:table.cell>
                                </flux:table.row>
                            @empty
                                <flux:table.row>
                                    <flux:table.cell colspan="3" class="text-center py-6 text-zinc-500">No IPs yet.</flux:table.cell>
                                </flux:table.row>
                            @endforelse
                        </flux:table.rows>
                    </flux:table>
                </div>

                <div class="p-4 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900/50">
                    {{ $allowlists->links('livewire.pagination.tailwind') }}
                </div>
            </flux:card>
        </div>
    </div>
</div>

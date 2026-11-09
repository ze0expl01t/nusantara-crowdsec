<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <flux:card class="flex flex-col p-6">
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 flex items-center justify-center mb-4">
                    <flux:icon.circle-stack class="w-6 h-6" />
                </div>
                <span class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $totalBackups }}</span>
                <span class="text-sm text-zinc-500 mt-1">Total Backups</span>
            </flux:card>

            <flux:card class="flex flex-col p-6">
                <div class="w-10 h-10 rounded-lg bg-teal-100 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 flex items-center justify-center mb-4">
                    <flux:icon.document class="w-6 h-6" />
                </div>
                <span class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $totalSize }}</span>
                <span class="text-sm text-zinc-500 mt-1">Total Size</span>
            </flux:card>

            <flux:card class="flex flex-col p-6">
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-4">
                    <flux:icon.clock class="w-6 h-6" />
                </div>
                <span class="text-2xl font-bold text-zinc-900 dark:text-white leading-tight">{{ $lastBackup }}</span>
                <span class="text-sm text-zinc-500 mt-1">Last Backup</span>
            </flux:card>

            <flux:card class="flex flex-col p-6">
                <div class="w-10 h-10 rounded-lg bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 flex items-center justify-center mb-4">
                    <flux:icon.bolt class="w-6 h-6" />
                </div>
                <span class="text-3xl font-bold text-orange-600">{{ $diskFree }}</span>
                <span class="text-sm text-zinc-500 mt-1">Disk Free</span>
            </flux:card>
        </div>

        @if($message)
            <flux:callout color="{{ str_contains($message, 'Gagal') ? 'red' : 'green' }}" icon="{{ str_contains($message, 'Gagal') ? 'exclamation-triangle' : 'check-circle' }}">
                {{ $message }}
            </flux:callout>
        @endif

        <!-- Panel Action -->
        <flux:card class="p-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700 shadow-sm">
            <div class="flex flex-wrap items-center gap-3">
                <span class="font-bold text-zinc-700 dark:text-zinc-300 mr-2">Run:</span>
                
                <flux:button wire:click="runBackup('database')" icon="document" variant="primary" class="cursor-pointer bg-rose-600 hover:bg-rose-500" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="runBackup('database')">Data Backupbase</span>
                    <span wire:loading wire:target="runBackup('database')">Proses...</span>
                </flux:button>

                <flux:button wire:click="runBackup('files')" icon="document-duplicate" variant="outline" class="cursor-pointer" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="runBackup('files')">Backup Files</span>
                    <span wire:loading wire:target="runBackup('files')">Proses...</span>
                </flux:button>

                <flux:button wire:click="runBackup('full')" icon="archive-box" variant="outline" class="cursor-pointer" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="runBackup('full')">Full Backup</span>
                    <span wire:loading wire:target="runBackup('full')">Proses...</span>
                </flux:button>
            </div>

            <flux:button wire:click="cleanupOld" icon="trash" variant="danger" class="cursor-pointer" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="cleanupOld">Cleanup Old</span>
                <span wire:loading wire:target="cleanupOld">Membersihkan...</span>
            </flux:button>
        </flux:card>

        <!-- Tabel Data & Filter -->
        <flux:card class="p-0 overflow-hidden mt-6">
            <div class="p-4 border-b border-zinc-200 dark:border-zinc-700 flex items-center gap-4">
                <span class="font-bold text-zinc-700 dark:text-zinc-300">Type:</span>
                <div class="flex gap-2">
                    <flux:button wire:click="setFilter('semua')" size="sm" :variant="$filter === 'semua' ? 'primary' : 'ghost'" class="cursor-pointer">All</flux:button>
                    <flux:button wire:click="setFilter('database')" size="sm" :variant="$filter === 'database' ? 'primary' : 'ghost'" class="cursor-pointer">Database</flux:button>
                    <flux:button wire:click="setFilter('files')" size="sm" :variant="$filter === 'files' ? 'primary' : 'ghost'" class="cursor-pointer">Files</flux:button>
                </div>
            </div>

            <div class="px-6 py-2">
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Filename</flux:table.column>
                        <flux:table.column>Tipe</flux:table.column>
                        <flux:table.column>Size</flux:table.column>
                        <flux:table.column>Date</flux:table.column>
                        <flux:table.column class="text-right">Action</flux:table.column>
                    </flux:table.columns>
                    <flux:table.rows>
                        @forelse ($backups as $bk)
                            <flux:table.row :key="$bk['name']">
                                <flux:table.cell class="font-mono text-sm text-blue-500">{{ $bk['name'] }}</flux:table.cell>
                                <flux:table.cell>
                                    @if($bk['type'] == 'database')
                                        <flux:badge color="rose" size="sm">Database</flux:badge>
                                    @elseif($bk['type'] == 'files')
                                        <flux:badge color="blue" size="sm">Files</flux:badge>
                                    @else
                                        <flux:badge color="zinc" size="sm">Full</flux:badge>
                                    @endif
                                </flux:table.cell>
                                <flux:table.cell>{{ $bk['size'] }}</flux:table.cell>
                                <flux:table.cell>{{ $bk['time'] }}</flux:table.cell>
                                <flux:table.cell class="text-right">
                                    <div class="flex justify-end gap-2">
                                        <!-- Tombol Download -->
                                        <flux:button wire:click="downloadBackup('{{ $bk['name'] }}')" variant="primary" size="sm" icon="arrow-down-tray" class="cursor-pointer" tooltip="Download File" />
                                        
                                        <!-- Tombol Delete -->
                                        <flux:button wire:click="deleteBackup('{{ $bk['name'] }}')" wire:confirm="Delete file backup ini secara permanen?" variant="danger" size="sm" icon="trash" class="cursor-pointer" tooltip="Delete File" />
                                    </div>
                                </flux:table.cell>
                            </flux:table.row>
                        @empty
                            <flux:table.row>
                                <flux:table.cell colspan="5" class="text-center py-8 text-zinc-500">
                                    No backup files available for this type.
                                </flux:table.cell>
                            </flux:table.row>
                        @endforelse
                    </flux:table.rows>
                </flux:table>
            </div>
        </flux:card>
    </div>
</div>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div>
            <flux:heading size="xl">System Audit Logs</flux:heading>
            <flux:subheading>History of administrative activities on the portal.</flux:subheading>
        </div>

        <flux:card class="p-0 overflow-hidden">
            <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
                <flux:heading size="lg">Activity History</flux:heading>
            </div>
            
            <!-- BUNGKUS TABEL DENGAN PADDING -->
            <div class="px-6 py-2">
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Time</flux:table.column>
                        <flux:table.column>Actor</flux:table.column>
                        <flux:table.column>Action</flux:table.column>
                        <flux:table.column>Target</flux:table.column>
                    </flux:table.columns>
                    <flux:table.rows>
                        @forelse ($logs as $log)
                            <flux:table.row :key="$log->id">
                                <flux:table.cell class="whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i') }}</flux:table.cell>
                                <flux:table.cell>{{ $log->actor }}</flux:table.cell>
                                <flux:table.cell><flux:badge color="zinc" size="sm">{{ $log->action }}</flux:badge></flux:table.cell>
                                <flux:table.cell class="font-mono text-red-600 dark:text-red-400">{{ $log->target }}</flux:table.cell>
                            </flux:table.row>
                        @empty
                            <flux:table.row>
                                <flux:table.cell colspan="4" class="text-center py-6 text-zinc-500">No activity yet.</flux:table.cell>
                            </flux:table.row>
                        @endforelse
                    </flux:table.rows>
                </flux:table>
            </div>

            <div class="p-4 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900/50">
                {{ $logs->links('livewire.pagination.tailwind') }}
            </div>
        </flux:card>
    </div>
</div>

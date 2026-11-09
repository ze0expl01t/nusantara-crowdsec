@if ($paginator->hasPages())
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <!-- Teks Informasi -->
        <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">
            Menampilkan <span class="font-medium text-zinc-900 dark:text-white">{{ $paginator->firstItem() }}</span> 
            sampai <span class="font-medium text-zinc-900 dark:text-white">{{ $paginator->lastItem() }}</span> 
            dari <span class="font-medium text-zinc-900 dark:text-white">{{ $paginator->total() }}</span> data
        </flux:text>

        <!-- Tombol Navigasi -->
        <div class="flex items-center gap-1">
            {{-- Tombol Previous --}}
            @if ($paginator->onFirstPage())
                <flux:button size="sm" icon="chevron-left" disabled class="cursor-not-allowed" />
            @else
                <flux:button size="sm" icon="chevron-left" wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" class="cursor-pointer" />
            @endif

            {{-- Nomor Halaman --}}
            @foreach ($elements as $element)
                {{-- Separator --}}
                @if (is_string($element))
                    <flux:button size="sm" disabled class="cursor-default">{{ $element }}</flux:button>
                @endif

                {{-- Array Nomor Halaman --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <flux:button size="sm" variant="primary" class="cursor-default">{{ $page }}</flux:button>
                        @else
                            <flux:button size="sm" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" class="cursor-pointer">{{ $page }}</flux:button>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Tombol Next --}}
            @if ($paginator->hasMorePages())
                <flux:button size="sm" icon="chevron-right" wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" class="cursor-pointer" />
            @else
                <flux:button size="sm" icon="chevron-right" disabled class="cursor-not-allowed" />
            @endif
        </div>
    </div>
@endif

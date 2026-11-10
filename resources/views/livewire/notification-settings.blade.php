<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div>
            <flux:heading size="xl">Notification Settings</flux:heading>
            <flux:subheading>Configure Email & Telegram alerts for specific CrowdSec scenarios.</flux:subheading>
        </div>

        @if (session()->has('message'))
            <flux:callout color="green" icon="check-circle">{{ session('message') }}</flux:callout>
        @endif

        <form wire:submit="save" class="space-y-6">
            
            <!-- EMAIL -->
            <flux:card>
                <div class="flex items-center justify-between mb-4">
                    <flux:heading size="lg">Email Notification</flux:heading>
                    <flux:switch wire:model="email_enabled" />
                </div>
                @if($email_enabled)
                    <flux:input wire:model="email_recipient" label="Recipient Email" placeholder="admin@yourdomain.com" type="email" />
                @endif
            </flux:card>

            <!-- TELEGRAM -->
            <flux:card>
                <div class="flex items-center justify-between mb-4">
                    <flux:heading size="lg">Telegram Notification</flux:heading>
                    <flux:switch wire:model="telegram_enabled" />
                </div>
                @if($telegram_enabled)
                    <div class="space-y-4">
                        <flux:input wire:model="telegram_token" label="Bot Token" placeholder="123456:ABC-DEF..." />
                        <flux:input wire:model="telegram_chat_id" label="Chat ID" placeholder="-1001234567890" />
                        <flux:text size="sm" class="text-zinc-500">Buat bot via @BotFather dan dapatkan Chat ID via @userinfobot.</flux:text>
                    </div>
                @endif
            </flux:card>

            <!-- SCENARIOS -->
            <flux:card>
                <flux:heading size="lg" class="mb-2">Trigger Scenarios</flux:heading>
                <flux:subheading class="mb-4">Select which attack scenarios will trigger a notification.</flux:subheading>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-4 max-h-64 overflow-y-auto p-2 border border-zinc-200 dark:border-zinc-700 rounded-lg">
                    @forelse($available_scenarios as $scenario)
                        <label class="flex items-center gap-2 p-2 hover:bg-zinc-50 dark:hover:bg-zinc-800 rounded cursor-pointer">
                            <input type="checkbox" wire:model="scenarios" value="{{ $scenario }}" class="rounded border-zinc-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-mono text-zinc-700 dark:text-zinc-300">{{ basename($scenario) }}</span>
                        </label>
                    @empty
                        <div class="text-sm text-zinc-500 p-2">No recent scenarios found.</div>
                    @endforelse
                </div>
            </flux:card>

            <div class="flex justify-end">
                <flux:button type="submit" variant="primary" class="cursor-pointer">Save Settings</flux:button>
            </div>
        </form>
    </div>
</div>

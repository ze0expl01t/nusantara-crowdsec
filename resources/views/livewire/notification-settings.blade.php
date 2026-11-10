<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div>
            <flux:heading size="xl">Notification Settings</flux:heading>
            <flux:subheading>Configure Email (SMTP) & Telegram alerts for specific CrowdSec scenarios.</flux:subheading>
        </div>

        @if (session()->has('message'))
            <flux:callout color="green" icon="check-circle">{{ session('message') }}</flux:callout>
        @endif
        @if (session()->has('error'))
            <flux:callout color="red" icon="exclamation-triangle">{{ session('error') }}</flux:callout>
        @endif

        <form wire:submit="save" class="space-y-6">
            
            <!-- EMAIL & SMTP -->
            <flux:card>
                <div class="flex items-center justify-between mb-4">
                    <flux:heading size="lg">Email Notification</flux:heading>
                    <flux:switch wire:model.live="email_enabled" />
                </div>
                @if($email_enabled)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <flux:input wire:model="email_recipient" label="Recipient Email" placeholder="admin@yourdomain.com" type="email" required />
                        <flux:input wire:model="smtp_from_address" label="Sender Email (From)" placeholder="noreply@yourdomain.com" type="email" required />
                        
                        <flux:input wire:model="smtp_host" label="SMTP Host" placeholder="smtp.gmail.com" required />
                        <flux:input wire:model="smtp_port" label="SMTP Port" placeholder="587" required />
                        
                        <flux:input wire:model="smtp_username" label="SMTP Username" placeholder="user@gmail.com" required />
                        <flux:input wire:model="smtp_password" label="SMTP Password" type="password" placeholder="••••••••" />
                        
                        <flux:select wire:model="smtp_encryption" label="Encryption">
                            <option value="tls">TLS (Port 587)</option>
                            <option value="ssl">SSL (Port 465)</option>
                            <option value="">None (Port 25)</option>
                        </flux:select>
                        <flux:input wire:model="smtp_from_name" label="Sender Name" placeholder="CrowdSec Portal" />
                    </div>
                    
                    <div class="mt-4 flex justify-end border-t border-zinc-200 dark:border-zinc-700 pt-4">
                        <flux:button wire:click="testSmtp" variant="filled" icon="paper-airplane" class="cursor-pointer" wire:loading.attr="disabled">
                            Send Test Email
                        </flux:button>
                    </div>
                @endif
            </flux:card>

            <!-- TELEGRAM -->
            <flux:card>
                <div class="flex items-center justify-between mb-4">
                    <flux:heading size="lg">Telegram Notification</flux:heading>
                    <flux:switch wire:model.live="telegram_enabled" />
                </div>
                @if($telegram_enabled)
                    <div class="space-y-4">
                        <flux:input wire:model="telegram_token" label="Bot Token" placeholder="123456:ABC-DEF..." required />
                        <flux:input wire:model="telegram_chat_id" label="Chat ID" placeholder="-1001234567890" required />
                        <flux:text size="sm" class="text-zinc-500">Create a bot via @BotFather and get your Chat ID via @userinfobot.</flux:text>
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

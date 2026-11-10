<div class="py-12">
            <!-- Tombol Autodetect -->
            <div class="p-4 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-xl mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-blue-800 dark:text-blue-200">CrowdSec Auto-Detect</h3>
                        <p class="text-sm text-blue-600 dark:text-blue-300">Find and save LAPI configuration automatically.</p>
                    </div>
                    <flux:button wire:click="autoDetectCrowdSec" variant="primary" icon="magnifying-glass" class="cursor-pointer" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="autoDetectCrowdSec">Detect Now</span>
                        <span wire:loading wire:target="autoDetectCrowdSec">Detecting...</span>
                    </flux:button>
                </div>
            </div>

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div>
            <flux:heading size="xl">Branding & Appearance Settings</flux:heading>
            <flux:subheading>Customize app identity, text, and landing page features.</flux:subheading>
        </div>

                @if (session()->has('error'))
            <flux:callout color="red" icon="exclamation-triangle">{{ session('error') }}</flux:callout>
        @endif
        @if (session()->has('message'))
            <flux:callout color="green" icon="check-circle">{{ session('message') }}</flux:callout>
        @endif

        <form wire:submit="save" class="space-y-8">
            
            <flux:card>
                <flux:heading size="lg" class="mb-4">Application Identity</flux:heading>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input wire:model="app_name" label="App Name" required />
                    <flux:input wire:model="institution_name" label="Institution Name" required />
                </div>
                <div class="mt-4">
                    <flux:input wire:model="logo_url" label="Logo URL (Optional)" placeholder="https://contoh.com/logo.png" />
                </div>
            </flux:card>

            <flux:card>
                <flux:heading size="lg" class="mb-4">Landing Page Text</flux:heading>
                <div class="space-y-4">
                    <flux:input wire:model="hero_title" label="Main Title" required />
                    <flux:textarea wire:model="hero_subtitle" label="Subtitle" rows="3" required />
                </div>
            </flux:card>

            <!-- REPEATER FITUR -->
            <flux:card>
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-zinc-200 dark:border-zinc-700">
                    <div>
                        <flux:heading size="lg">Landing Page Features</flux:heading>
                        <flux:subheading>Manage icon, color, title, and feature description.</flux:subheading>
                    </div>
                    <flux:button wire:click.prevent="addFeature" icon="plus" variant="primary" size="sm" class="cursor-pointer">Add Feature</flux:button>
                </div>
                
                <div class="space-y-8">
                    @foreach($features as $index => $feature)
                        <div class="relative bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6 mt-4">
                            
                            <div class="flex justify-between items-center mb-6">
                                <span class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider bg-blue-100 dark:bg-blue-900/30 px-3 py-1.5 rounded-md">
                                    Feature #{{ $index + 1 }}
                                </span>
                                <flux:button wire:click.prevent="removeFeature({{ $index }})" icon="trash" variant="danger" size="sm" class="cursor-pointer" tooltip="Delete Fitur" />
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-6 gap-x-6 gap-y-6">
                                <!-- Icon -->
                                <div class="md:col-span-2">
                                    <flux:select wire:model="features.{{ $index }}.icon" label="Icon">
                                        <option value="bolt">Petir (Bolt)</option>
                                        <option value="chart-bar">Grafik (Chart)</option>
                                        <option value="shield-exclamation">Perisai (Shield)</option>
                                        <option value="check-badge">Centang (Check)</option>
                                        <option value="document-text">Dokumen (Doc)</option>
                                        <option value="book-open">Buku (Book)</option>
                                        <option value="cog-6-tooth">Gir (Settings)</option>
                                        <option value="lock-closed">Gembok (Lock)</option>
                                        <option value="server">Server</option>
                                        <option value="star">Bintang (Star)</option>
                                        <option value="user">Orang (User)</option>
                                        <option value="users">Grup (Group)</option>
                                    </flux:select>
                                </div>
                                
                                <!-- Warna -->
                                <div class="md:col-span-2">
                                    <flux:select wire:model="features.{{ $index }}.color" label="Icon Color">
                                        <option value="blue">Biru</option>
                                        <option value="red">Merah</option>
                                        <option value="green">Hijau</option>
                                        <option value="orange">Oranye</option>
                                        <option value="zinc">Abu-abu</option>
                                    </flux:select>
                                </div>

                                <!-- Feature Title -->
                                <div class="md:col-span-2">
                                    <flux:input wire:model="features.{{ $index }}.title" label="Feature Title" required />
                                </div>

                                <!-- Deskripsi -->
                                <div class="md:col-span-6">
                                    <flux:textarea wire:model="features.{{ $index }}.desc" label="Short Description" rows="2" required />
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </flux:card>

            <flux:card>
                <flux:heading size="lg" class="mb-4">Footer</flux:heading>
                <flux:input wire:model="footer_text" label="Copyright Text" required />
            </flux:card>

            <div class="flex justify-end pt-4">
                <flux:button type="submit" variant="primary" class="px-8 py-3 cursor-pointer shadow-lg shadow-blue-500/20" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">Save All Settings</span>
                    <span wire:loading wire:target="save">Saving...</span>
                </flux:button>
            </div>
        </form>
    </div>
</div>

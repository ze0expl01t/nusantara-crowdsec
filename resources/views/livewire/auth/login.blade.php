<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

new #[Layout('components.layouts.auth.simple')] class extends Component {
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function login(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        session()->regenerate();
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
};
?>

<!-- Menambahkan class 'dark' untuk memastikan form membaca tema gelap -->
<div class="dark flex flex-col gap-5 pt-2">
    
    <!-- Icon Gembok -->
    <div class="mx-auto w-12 h-12 bg-blue-500/10 rounded-full flex items-center justify-center border border-blue-500/20 mb-2 shadow-[0_0_15px_rgba(59,130,246,0.2)]">
        <flux:icon.lock-closed class="w-6 h-6 text-blue-500" />
    </div>

    <div class="text-center">
        <h2 class="text-2xl font-bold text-white mb-1">Restricted Access</h2>
        <p class="text-sm text-zinc-400">Enter your administrator credentials</p>
    </div>

    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="login" class="flex flex-col gap-5 mt-2">
        
        <!-- Email: Memaksa Label dan Input berwarna terang/gelap -->
        <div class="flex flex-col gap-1.5">
            <label class="text-sm font-medium text-zinc-300">Email Address</label>
            <flux:input 
                wire:model="email" 
                type="email" 
                required 
                autofocus 
                autocomplete="email" 
                placeholder="admin@sekolah.sch.id" 
                icon="envelope"
                class="!bg-zinc-950 !text-white !border-zinc-700" 
            />
        </div>

        <!-- Password -->
        <div class="flex flex-col gap-1.5 relative">
            <div class="flex justify-between items-center">
                <label class="text-sm font-medium text-zinc-300">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs text-blue-500 hover:text-blue-400 font-medium transition" wire:navigate>Forgot password?</a>
                @endif
            </div>
            <flux:input 
                wire:model="password" 
                type="password" 
                required 
                autocomplete="current-password" 
                placeholder="••••••••" 
                viewable 
                icon="key"
                class="!bg-zinc-950 !text-white !border-zinc-700" 
            />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center mt-1">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" wire:model="remember" class="w-4 h-4 rounded border-zinc-600 bg-zinc-800 text-blue-500 focus:ring-blue-500 focus:ring-offset-zinc-900">
                <span class="text-sm text-zinc-400">Remember this device</span>
            </label>
        </div>

        <!-- Submit Button -->
        <flux:button type="submit" class="w-full py-3 mt-4 cursor-pointer bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 text-white shadow-[0_0_20px_rgba(59,130,246,0.3)] transition-all duration-300 border-0" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="login">Authorize Login</span>
            <span wire:loading wire:target="login">Verifying...</span>
        </flux:button>
    </form>
</div>

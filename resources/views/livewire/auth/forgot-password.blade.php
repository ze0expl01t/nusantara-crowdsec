<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Password;

new #[Layout('components.layouts.auth.simple')] class extends Component {
    public string $email = '';

    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));
            return;
        }

        $this->reset('email');
        session()->flash('status', __($status));
    }
};
?>

<div class="dark flex flex-col gap-5 pt-2">
    
    <!-- Icon Gembok -->
    <div class="mx-auto w-12 h-12 bg-blue-500/10 rounded-full flex items-center justify-center border border-blue-500/20 mb-2 shadow-[0_0_15px_rgba(59,130,246,0.2)]">
        <flux:icon.key class="w-6 h-6 text-blue-500" />
    </div>

    <div class="text-center">
        <h2 class="text-2xl font-bold text-white mb-1">Forgot Password?</h2>
        <p class="text-sm text-zinc-400">Enter your email to receive a reset link.</p>
    </div>

    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="flex flex-col gap-5 mt-2">
        
        <div class="flex flex-col gap-1.5">
            <label class="text-sm font-medium text-zinc-300">Email Address</label>
            <flux:input 
                wire:model="email" 
                type="email" 
                required 
                autofocus 
                placeholder="admin@sekolah.sch.id" 
                icon="envelope"
                class="!bg-zinc-950 !text-white !border-zinc-700" 
            />
        </div>

        <!-- Submit Button (Gradient) -->
        <flux:button type="submit" class="w-full py-3 mt-2 cursor-pointer bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 text-white shadow-[0_0_20px_rgba(59,130,246,0.3)] transition-all duration-300 border-0" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="sendPasswordResetLink">Send Reset Link</span>
            <span wire:loading wire:target="sendPasswordResetLink">Mengirim...</span>
        </flux:button>
    </form>

    <p class="text-center text-sm text-zinc-500 mt-2">
        Or, return to 
        <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-400 font-medium transition" wire:navigate>Sign In Page</a>
    </p>
</div>

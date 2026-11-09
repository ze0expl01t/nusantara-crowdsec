<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

new #[Layout('components.layouts.auth.simple')] class extends Component {
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(string $token): void
    {
        $this->token = $token;
        $this->email = request()->string('email');
    }

    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));
            return;
        }

        session()->flash('status', __($status));
        $this->redirectRoute('login', navigate: true);
    }
};
?>

<div class="dark flex flex-col gap-5 pt-2">
    <div class="mx-auto w-12 h-12 bg-blue-500/10 rounded-full flex items-center justify-center border border-blue-500/20 mb-2 shadow-[0_0_15px_rgba(59,130,246,0.2)]">
        <flux:icon.shield-check class="w-6 h-6 text-blue-500" />
    </div>

    <div class="text-center">
        <h2 class="text-2xl font-bold text-white mb-1">Create New Password</h2>
        <p class="text-sm text-zinc-400">Please create a new password for your account.</p>
    </div>

    <form wire:submit="resetPassword" class="flex flex-col gap-5 mt-2">
        <div class="flex flex-col gap-1.5">
            <label class="text-sm font-medium text-zinc-300">Email Address</label>
            <flux:input wire:model="email" type="email" required readonly class="!bg-zinc-950 !text-zinc-500 !border-zinc-700" />
        </div>

        <div class="flex flex-col gap-1.5">
            <label class="text-sm font-medium text-zinc-300">New Password</label>
            <flux:input wire:model="password" type="password" required viewable placeholder="••••••••" icon="key" class="!bg-zinc-950 !text-white !border-zinc-700" />
        </div>

        <div class="flex flex-col gap-1.5">
            <label class="text-sm font-medium text-zinc-300">Confirm Password</label>
            <flux:input wire:model="password_confirmation" type="password" required viewable placeholder="••••••••" icon="key" class="!bg-zinc-950 !text-white !border-zinc-700" />
        </div>

        <flux:button type="submit" class="w-full py-3 mt-2 cursor-pointer bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 text-white shadow-[0_0_20px_rgba(59,130,246,0.3)] transition-all duration-300 border-0" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="resetPassword">Save New Password</span>
            <span wire:loading wire:target="resetPassword">Saving...</span>
        </flux:button>
    </form>
</div>

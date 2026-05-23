<?php

namespace App\Modules\Admin\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AdminLogin extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public function login(): mixed
    {
        $this->validate();

        if (!Auth::guard('web')->attempt(['email' => $this->email, 'password' => $this->password])) {
            $this->addError('email', 'Credenciais inválidas.');
            return null;
        }

        if (request()->hasSession()) {
            request()->session()->regenerate();
        }

        return redirect()->intended(route('admin.dashboard'));
    }

    public function render()
    {
        return view('modules.admin.livewire.admin-login')
            ->layout('layouts.app', ['meta_title' => 'Admin — Imóveis da Caixa']);
    }
}

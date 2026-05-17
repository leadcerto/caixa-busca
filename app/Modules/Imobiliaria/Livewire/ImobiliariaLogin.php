<?php

namespace App\Modules\Imobiliaria\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ImobiliariaLogin extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $senha = '';

    public function login(): mixed
    {
        $this->validate();

        if (!Auth::guard('imobiliaria')->attempt(['email' => $this->email, 'senha' => $this->senha])) {
            $this->addError('email', 'Credenciais inválidas.');
            return null;
        }

        request()->session()->regenerate();

        return redirect()->intended(route('imobiliaria.painel'));
    }

    public function render()
    {
        return view('modules.imobiliaria.livewire.imobiliaria-login')
            ->layout('layouts.app', ['meta_title' => 'Área do Parceiro — Antigravity']);
    }
}

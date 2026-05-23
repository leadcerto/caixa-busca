<?php

namespace App\Modules\Imobiliaria\Livewire;

use App\Models\Imobiliaria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        $imobiliaria = Imobiliaria::where('email', $this->email)->first();

        if (!$imobiliaria || !Hash::check($this->senha, $imobiliaria->senha)) {
            $this->addError('email', 'Credenciais inválidas.');
            return null;
        }

        Auth::guard('imobiliaria')->login($imobiliaria);

        if (request()->hasSession()) {
            request()->session()->regenerate();
        }

        return redirect()->intended(route('imobiliaria.painel'));
    }

    public function render()
    {
        return view('modules.imobiliaria.livewire.imobiliaria-login')
            ->layout('layouts.app', ['meta_title' => 'Área do Parceiro — Antigravity']);
    }
}

<?php

namespace App\Modules\Imobiliaria\Livewire;

use App\Models\Atendimento;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PainelLeads extends Component
{
    use WithPagination;

    public function render()
    {
        $imobiliaria = Auth::guard('imobiliaria')->user()->load('estados');

        $atendimentos = Atendimento::with([
            'lead',
            'imovel.tipoImovel',
            'imovel.municipio',
            'imovel.estado',
            'origem',
        ])
            ->where('id_imobiliaria', $imobiliaria->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('modules.imobiliaria.livewire.painel-leads', [
            'atendimentos' => $atendimentos,
            'imobiliaria'  => $imobiliaria,
        ])->layout('layouts.app', ['meta_title' => 'Painel do Parceiro — Antigravity']);
    }
}

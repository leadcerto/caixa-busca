<?php

namespace App\Modules\Imobiliaria\Livewire;

use App\Models\Atendimento;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PainelLeads extends Component
{
    use WithPagination;

    /**
     * Renderiza o painel de leads da imobiliária.
     * Filtra obrigatoriamente pelo ID da imobiliária logada (Multitenancy).
     */
    public function render()
    {
        // Obtém a imobiliária logada via guard específico
        $imobiliaria = Auth::guard('imobiliaria')->user();

        // Busca atendimentos (leads) vinculados a esta imobiliária
        // Carrega relacionamentos para evitar N+1
        $leads = Atendimento::with(['lead', 'imovel'])
            ->where('id_imobiliaria', $imobiliaria->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('modules.imobiliaria.livewire.painel-leads', [
            'leads' => $leads,
            'imobiliaria' => $imobiliaria
        ])->layout('layouts.app'); // Assume-se que existe um layout base
    }
}

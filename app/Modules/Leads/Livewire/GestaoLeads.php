<?php

namespace App\Modules\Leads\Livewire;

use App\Models\Atendimento;
use App\Models\Estado;
use App\Models\Imobiliaria;
use App\Models\Imovel;
use App\Models\Lead;
use Livewire\Component;
use Livewire\WithPagination;

class GestaoLeads extends Component
{
    use WithPagination;

    public string $busca           = '';
    public string $imobiliariaId   = '';
    public string $estadoId        = '';
    public string $dataInicio      = '';
    public string $dataFim         = '';
    public bool   $somenteDuplicados = false;

    public ?int $leadDetalheId = null;

    public function updatedBusca(): void           { $this->resetPage(); }
    public function updatedImobiliariaId(): void   { $this->resetPage(); }
    public function updatedEstadoId(): void        { $this->resetPage(); }
    public function updatedDataInicio(): void      { $this->resetPage(); }
    public function updatedDataFim(): void         { $this->resetPage(); }
    public function updatedSomenteDuplicados(): void { $this->resetPage(); }

    // -------------------------------------------------------------------------
    // Lista
    // -------------------------------------------------------------------------

    private function queryLeads()
    {
        $query = Lead::withCount('atendimentos')
            ->with(['atendimentos.imobiliaria', 'atendimentos.imovel.estado']);

        if ($this->busca) {
            $b = $this->busca;
            $query->where(fn($q) => $q
                ->where('nome', 'like', "%{$b}%")
                ->orWhere('email', 'like', "%{$b}%")
                ->orWhere('telefone', 'like', "%{$b}%")
            );
        }

        if ($this->imobiliariaId) {
            $query->whereHas('atendimentos', fn($q) =>
                $q->where('id_imobiliaria', $this->imobiliariaId)
            );
        }

        if ($this->estadoId) {
            $query->whereHas('atendimentos.imovel', fn($q) =>
                $q->where('id_estado', $this->estadoId)
            );
        }

        if ($this->dataInicio) {
            $query->whereDate('created_at', '>=', $this->dataInicio);
        }

        if ($this->dataFim) {
            $query->whereDate('created_at', '<=', $this->dataFim);
        }

        if ($this->somenteDuplicados) {
            $emailsDup = Lead::select('email')
                ->groupBy('email')->havingRaw('COUNT(*) > 1')->pluck('email');
            $telsDup   = Lead::select('telefone')->whereNotNull('telefone')
                ->where('telefone', '!=', '')
                ->groupBy('telefone')->havingRaw('COUNT(*) > 1')->pluck('telefone');

            $query->where(fn($q) => $q
                ->whereIn('email', $emailsDup)
                ->orWhereIn('telefone', $telsDup)
            );
        }

        return $query->orderByDesc('created_at');
    }

    // -------------------------------------------------------------------------
    // Duplicados (para badge na listagem)
    // -------------------------------------------------------------------------

    private function emailsDuplicados(): array
    {
        return Lead::select('email')
            ->groupBy('email')->havingRaw('COUNT(*) > 1')
            ->pluck('email')->toArray();
    }

    private function telefonesDuplicados(): array
    {
        return Lead::select('telefone')
            ->whereNotNull('telefone')->where('telefone', '!=', '')
            ->groupBy('telefone')->havingRaw('COUNT(*) > 1')
            ->pluck('telefone')->toArray();
    }

    // -------------------------------------------------------------------------
    // Ações
    // -------------------------------------------------------------------------

    public function verDetalhe(int $id): void
    {
        $this->leadDetalheId = $id;
    }

    public function voltarLista(): void
    {
        $this->leadDetalheId = null;
    }

    public function exportarCsv()
    {
        $leads = $this->queryLeads()->get();

        $linhas = [];
        $linhas[] = implode(';', [
            'ID', 'Nome', 'E-mail', 'Telefone', 'Cadastro',
            'Total Atendimentos', 'Imóveis de Interesse',
        ]);

        foreach ($leads as $lead) {
            $linhas[] = implode(';', [
                $lead->id,
                $lead->nome,
                $lead->email,
                $lead->telefone ?? '',
                $lead->created_at->format('d/m/Y H:i'),
                $lead->atendimentos_count,
                implode(' | ', array_map(
                    fn($id) => "#{$id}",
                    $lead->imoveis_interesse ?? []
                )),
            ]);
        }

        $csv      = implode("\n", $linhas);
        $filename = 'leads_admin_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(
            fn() => print($csv),
            $filename,
            ['Content-Type' => 'text/csv; charset=UTF-8']
        );
    }

    // -------------------------------------------------------------------------
    // Render
    // -------------------------------------------------------------------------

    public function render()
    {
        // Tela de detalhe
        if ($this->leadDetalheId) {
            $lead = Lead::with([
                'atendimentos.imobiliaria',
                'atendimentos.imovel.tipoImovel',
                'atendimentos.imovel.municipio',
                'atendimentos.imovel.estado',
                'atendimentos.origem',
            ])->findOrFail($this->leadDetalheId);

            $imoveisInteresse = collect();
            if (!empty($lead->imoveis_interesse)) {
                $numeros = collect($lead->imoveis_interesse)->pluck('numero')->filter()->values();
                $imoveisInteresse = Imovel::whereIn('numero_original', $numeros)
                    ->with(['tipoImovel', 'municipio', 'estado'])
                    ->get();
            }

            return view('modules.leads.livewire.gestao-leads', [
                'modo'             => 'detalhe',
                'lead'             => $lead,
                'imoveisInteresse' => $imoveisInteresse,
                'imobiliarias'     => collect(),
                'estados'          => collect(),
                'leads'            => null,
                'emailsDup'        => [],
                'telefonesDup'     => [],
            ])->layout('layouts.admin', ['title' => 'Leads']);
        }

        // Tela de listagem
        $leads = $this->queryLeads()->paginate(20);

        return view('modules.leads.livewire.gestao-leads', [
            'modo'             => 'lista',
            'leads'            => $leads,
            'imobiliarias'     => Imobiliaria::orderBy('nome')->get(['id', 'nome']),
            'estados'          => Estado::orderBy('uf')->get(['id', 'uf', 'nome']),
            'emailsDup'        => $this->emailsDuplicados(),
            'telefonesDup'     => $this->telefonesDuplicados(),
            'lead'             => null,
            'imoveisInteresse' => collect(),
        ])->layout('layouts.admin', ['title' => 'Leads']);
    }
}

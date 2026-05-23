<?php

namespace App\Modules\Imobiliaria\Livewire;

use App\Models\Atendimento;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PainelLeads extends Component
{
    use WithPagination;

    public string $busca       = '';
    public string $dataInicio  = '';
    public string $dataFim     = '';
    public string $statusFiltro = '';

    // Reseta paginação ao mudar qualquer filtro
    public function updatedBusca(): void       { $this->resetPage(); }
    public function updatedDataInicio(): void   { $this->resetPage(); }
    public function updatedDataFim(): void      { $this->resetPage(); }
    public function updatedStatusFiltro(): void { $this->resetPage(); }

    public function atualizarStatus(int $id, string $status): void
    {
        $imobiliariaId = Auth::guard('imobiliaria')->id();

        $atendimento = Atendimento::where('id', $id)
            ->where('id_imobiliaria', $imobiliariaId)
            ->first();

        if (!$atendimento) {
            return;
        }

        $atendimento->update(['status_parceiro' => $status]);
    }

    public function exportarCsv()
    {
        $imobiliariaId = Auth::guard('imobiliaria')->id();

        $atendimentos = $this->queryBase($imobiliariaId)->get();

        $linhas = [];
        $linhas[] = implode(';', [
            'Data', 'Lead', 'Email', 'Telefone',
            'Imóvel', 'Tipo', 'Município', 'UF',
            'Status', 'Email Enviado', 'WhatsApp Enviado',
        ]);

        foreach ($atendimentos as $at) {
            $linhas[] = implode(';', [
                $at->created_at->format('d/m/Y H:i'),
                $at->lead?->nome ?? '',
                $at->lead?->email ?? '',
                $at->lead?->telefone ?? '',
                '#' . ($at->imovel?->numero_original ?? ''),
                $at->imovel?->tipoImovel?->nome ?? '',
                $at->imovel?->municipio?->nome ?? '',
                $at->imovel?->estado?->uf ?? '',
                Atendimento::STATUS_LABELS[$at->status_parceiro] ?? $at->status_parceiro,
                $at->email_enviado ? 'Sim' : 'Não',
                $at->whatsapp_enviado ? 'Sim' : 'Não',
            ]);
        }

        $csv      = implode("\n", $linhas);
        $filename = 'leads_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(
            fn() => print($csv),
            $filename,
            ['Content-Type' => 'text/csv; charset=UTF-8']
        );
    }

    private function queryBase(int $imobiliariaId)
    {
        $query = Atendimento::with([
            'lead',
            'imovel.tipoImovel',
            'imovel.municipio',
            'imovel.estado',
            'origem',
        ])->where('id_imobiliaria', $imobiliariaId);

        if ($this->busca) {
            $busca = $this->busca;
            $query->where(function ($q) use ($busca) {
                $q->whereHas('lead', fn($l) => $l
                    ->where('nome', 'like', "%{$busca}%")
                    ->orWhere('email', 'like', "%{$busca}%")
                    ->orWhere('telefone', 'like', "%{$busca}%")
                )->orWhereHas('imovel', fn($i) => $i
                    ->where('numero_original', 'like', "%{$busca}%")
                    ->orWhere('endereco', 'like', "%{$busca}%")
                );
            });
        }

        if ($this->dataInicio) {
            $query->whereDate('created_at', '>=', $this->dataInicio);
        }

        if ($this->dataFim) {
            $query->whereDate('created_at', '<=', $this->dataFim);
        }

        if ($this->statusFiltro) {
            $query->where('status_parceiro', $this->statusFiltro);
        }

        return $query->orderByDesc('created_at');
    }

    public function render()
    {
        $imobiliaria   = Auth::guard('imobiliaria')->user()->load('estados');
        $imobiliariaId = $imobiliaria->id;

        $atendimentos = $this->queryBase($imobiliariaId)->paginate(15);

        $metricas = [
            'total'     => Atendimento::where('id_imobiliaria', $imobiliariaId)->count(),
            'ultimos7d' => Atendimento::where('id_imobiliaria', $imobiliariaId)
                                ->where('created_at', '>=', now()->subDays(7))->count(),
            'pendentes' => Atendimento::where('id_imobiliaria', $imobiliariaId)
                                ->where('status_parceiro', 'pendente')->count(),
        ];

        return view('modules.imobiliaria.livewire.painel-leads', [
            'atendimentos' => $atendimentos,
            'imobiliaria'  => $imobiliaria,
            'metricas'     => $metricas,
            'statusOpcoes' => Atendimento::STATUS_LABELS,
            'statusCores'  => Atendimento::STATUS_CORES,
        ])->layout('layouts.parceiro', ['title' => 'Painel do Parceiro']);
    }
}

<?php

namespace App\Modules\Imobiliaria\Livewire;

use App\Models\Atendimento;
use App\Models\Lead;
use App\Models\LeadNote;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PainelLeads extends Component
{
    use WithPagination;

    // ── Filtros ───────────────────────────────────────────────────────────────
    public string $busca        = '';
    public string $dataInicio   = '';
    public string $dataFim      = '';
    public string $statusFiltro = '';

    public function updatedBusca(): void       { $this->resetPage(); }
    public function updatedDataInicio(): void   { $this->resetPage(); }
    public function updatedDataFim(): void      { $this->resetPage(); }
    public function updatedStatusFiltro(): void { $this->resetPage(); }

    // ── Painel de notas ───────────────────────────────────────────────────────
    public ?int   $notasLeadId = null;
    public string $novaNota    = '';
    public string $notaTipo    = 'anotacao';

    // ── Status do Atendimento (parceiro) ──────────────────────────────────────
    public function atualizarStatus(int $id, string $status): void
    {
        $imobiliariaId = Auth::guard('imobiliaria')->id();

        $atendimento = Atendimento::where('id', $id)
            ->where('id_imobiliaria', $imobiliariaId)
            ->first();

        if (! $atendimento) return;

        $atendimento->update(['status_parceiro' => $status]);
    }

    // ── Status CRM do Lead ────────────────────────────────────────────────────
    public function atualizarStatusLead(int $atendimentoId, string $status): void
    {
        $imobiliariaId = Auth::guard('imobiliaria')->id();

        $atendimento = Atendimento::where('id', $atendimentoId)
            ->where('id_imobiliaria', $imobiliariaId)
            ->with('lead')
            ->first();

        if (! $atendimento?->lead) return;

        $atendimento->lead->update(['status' => $status]);
    }

    // ── Notas ─────────────────────────────────────────────────────────────────
    public function abrirNotas(int $leadId): void
    {
        $imobiliariaId = Auth::guard('imobiliaria')->id();

        $autorizado = Atendimento::where('id_lead', $leadId)
            ->where('id_imobiliaria', $imobiliariaId)
            ->exists();

        if (! $autorizado) return;

        $this->notasLeadId = $leadId;
        $this->novaNota    = '';
        $this->notaTipo    = 'anotacao';
    }

    public function fecharNotas(): void
    {
        $this->notasLeadId = null;
        $this->novaNota    = '';
    }

    public function salvarNota(): void
    {
        $this->validate([
            'novaNota' => 'required|string|min:3|max:2000',
            'notaTipo' => 'required|in:' . implode(',', array_keys(LeadNote::TIPOS)),
        ]);

        $imobiliariaId = Auth::guard('imobiliaria')->id();

        $autorizado = Atendimento::where('id_lead', $this->notasLeadId)
            ->where('id_imobiliaria', $imobiliariaId)
            ->exists();

        if (! $autorizado) return;

        LeadNote::create([
            'lead_id'  => $this->notasLeadId,
            'user_id'  => null,
            'conteudo' => $this->novaNota,
            'tipo'     => $this->notaTipo,
        ]);

        $this->novaNota = '';
    }

    // ── Export CSV ────────────────────────────────────────────────────────────
    public function exportarCsv()
    {
        $imobiliariaId = Auth::guard('imobiliaria')->id();

        $atendimentos = $this->queryBase($imobiliariaId)->get();

        $linhas   = [];
        $linhas[] = implode(';', [
            'Data', 'Lead', 'Email', 'Telefone',
            'Imóvel', 'Tipo', 'Município', 'UF',
            'Status CRM', 'Status Parceiro', 'WhatsApp Enviado',
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
                Lead::STATUS_LABELS[$at->lead?->status] ?? ($at->lead?->status ?? ''),
                Atendimento::STATUS_LABELS[$at->status_parceiro] ?? $at->status_parceiro,
                $at->whatsapp_enviado ? 'Sim' : 'Não',
            ]);
        }

        $csv      = implode("\n", $linhas);
        $filename = 'leads_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(
            fn () => print($csv),
            $filename,
            ['Content-Type' => 'text/csv; charset=UTF-8']
        );
    }

    // ── Query base ────────────────────────────────────────────────────────────
    private function queryBase(int $imobiliariaId)
    {
        $query = Atendimento::with([
            'lead',
            'imovel.tipoImovel',
            'imovel.municipio',
            'imovel.estado',
            'imovel.ultimoHistorico',
            'origem',
        ])->where('id_imobiliaria', $imobiliariaId);

        if ($this->busca) {
            $busca = $this->busca;
            $query->where(function ($q) use ($busca) {
                $q->whereHas('lead', fn ($l) => $l
                    ->where('nome', 'like', "%{$busca}%")
                    ->orWhere('email', 'like', "%{$busca}%")
                    ->orWhere('telefone', 'like', "%{$busca}%")
                )->orWhereHas('imovel', fn ($i) => $i
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

    // ── Render ────────────────────────────────────────────────────────────────
    public function render()
    {
        $imobiliaria   = Auth::guard('imobiliaria')->user()->load('estados');
        $imobiliariaId = $imobiliaria->id;

        $atendimentos = $this->queryBase($imobiliariaId)->paginate(15);

        $notasLead = null;
        $notas     = collect();
        if ($this->notasLeadId) {
            $notasLead = Lead::find($this->notasLeadId);
            $notas     = LeadNote::where('lead_id', $this->notasLeadId)
                ->with('autor')
                ->latest()
                ->get();
        }

        $metricas = [
            'total'     => Atendimento::where('id_imobiliaria', $imobiliariaId)->count(),
            'ultimos7d' => Atendimento::where('id_imobiliaria', $imobiliariaId)
                ->where('created_at', '>=', now()->subDays(7))->count(),
            'pendentes' => Atendimento::where('id_imobiliaria', $imobiliariaId)
                ->where('status_parceiro', 'pendente')->count(),
        ];

        return view('modules.imobiliaria.livewire.painel-leads', [
            'atendimentos'     => $atendimentos,
            'imobiliaria'      => $imobiliaria,
            'metricas'         => $metricas,
            'statusOpcoes'     => Atendimento::STATUS_LABELS,
            'statusCores'      => Atendimento::STATUS_CORES,
            'leadStatusOpcoes' => Lead::STATUS_LABELS,
            'leadStatusCores'  => Lead::STATUS_CORES,
            'tiposNota'        => LeadNote::TIPOS,
            'tipoIcons'        => LeadNote::TIPO_ICONS,
            'notas'            => $notas,
            'notasLead'        => $notasLead,
        ])->layout('layouts.parceiro', ['title' => 'Painel do Parceiro']);
    }
}

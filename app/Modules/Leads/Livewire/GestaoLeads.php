<?php

namespace App\Modules\Leads\Livewire;

use App\Models\Atendimento;
use App\Models\Estado;
use App\Models\Imobiliaria;
use App\Models\Imovel;
use App\Models\Lead;
use App\Models\LeadNote;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class GestaoLeads extends Component
{
    use WithPagination;

    // -------------------------------------------------------------------------
    // Filtros existentes
    // -------------------------------------------------------------------------
    public string $busca             = '';
    public string $imobiliariaId     = '';
    public string $estadoId          = '';
    public string $dataInicio        = '';
    public string $dataFim           = '';
    public bool   $somenteDuplicados = false;

    // -------------------------------------------------------------------------
    // Filtros avançados (13.14)
    // -------------------------------------------------------------------------
    public string $filtroStatus      = '';
    public string $filtroResponsavel = '';

    // -------------------------------------------------------------------------
    // Detalhe do lead
    // -------------------------------------------------------------------------
    public ?int $leadDetalheId = null;

    // -------------------------------------------------------------------------
    // Seleção múltipla (13.10)
    // -------------------------------------------------------------------------
    public array $leadsSelecionados = [];
    public bool  $selecionarTodos   = false;

    // -------------------------------------------------------------------------
    // Ações em massa (13.11)
    // -------------------------------------------------------------------------
    public string $acaoMassaStatusValor = '';

    // -------------------------------------------------------------------------
    // Notas rápidas (13.9)
    // -------------------------------------------------------------------------
    public ?int   $notasLeadId  = null;
    public string $notaConteudo = '';
    public string $notaTipo     = 'anotacao';

    // -------------------------------------------------------------------------
    // Preview do imóvel (13.8)
    // -------------------------------------------------------------------------
    public ?int $previewImovelId = null;

    // -------------------------------------------------------------------------
    // Reset de paginação ao mudar filtros
    // -------------------------------------------------------------------------
    public function updatedBusca(): void             { $this->resetPage(); }
    public function updatedImobiliariaId(): void     { $this->resetPage(); }
    public function updatedEstadoId(): void          { $this->resetPage(); }
    public function updatedDataInicio(): void        { $this->resetPage(); }
    public function updatedDataFim(): void           { $this->resetPage(); }
    public function updatedSomenteDuplicados(): void { $this->resetPage(); }
    public function updatedFiltroStatus(): void      { $this->resetPage(); }
    public function updatedFiltroResponsavel(): void { $this->resetPage(); }

    public function updatedSelecionarTodos(): void
    {
        if ($this->selecionarTodos) {
            $this->leadsSelecionados = $this->queryLeads()->paginate(20)->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->leadsSelecionados = [];
        }
    }

    // =========================================================================
    // QUERY (com filtros avançados — 13.14)
    // =========================================================================

    private function queryLeads()
    {
        $query = Lead::withCount('atendimentos')
            ->with(['atendimentos.imobiliaria', 'atendimentos.imovel.estado', 'responsavel']);

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

        // 13.14 — Filtro por status
        if ($this->filtroStatus) {
            $query->where('status', $this->filtroStatus);
        }

        // 13.14 — Filtro por responsável
        if ($this->filtroResponsavel) {
            if ($this->filtroResponsavel === 'sem') {
                $query->whereNull('user_id');
            } else {
                $query->where('user_id', $this->filtroResponsavel);
            }
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

    // =========================================================================
    // KPIs (13.15)
    // =========================================================================

    private function computeKpis(): array
    {
        $total       = Lead::count();
        $novos7d     = Lead::where('created_at', '>=', now()->subDays(7))->count();
        $propostas   = Lead::where('status', Lead::STATUS_PROPOSTA)->count();
        $emAtend     = Lead::where('status', Lead::STATUS_EM_ATENDIMENTO)->count();
        $taxaConv    = $total > 0 ? round(($propostas / $total) * 100, 1) : 0;

        return [
            'total'       => $total,
            'novos7d'     => $novos7d,
            'emAtend'     => $emAtend,
            'propostas'   => $propostas,
            'taxaConv'    => $taxaConv,
        ];
    }

    // =========================================================================
    // LEAD SCORE (13.12)
    // =========================================================================

    public static function leadScore(Lead $lead): array
    {
        $diasCriacao = $lead->created_at->diffInDays(now());
        $atendCount  = $lead->atendimentos_count ?? $lead->atendimentos()->count();

        // 🔥 Quente
        if ($atendCount >= 3 || $diasCriacao <= 3 || $lead->status === Lead::STATUS_PROPOSTA) {
            return ['icon' => '🔥', 'label' => 'Quente', 'class' => 'bg-red-50 text-red-600'];
        }

        // ❄️ Frio
        if ($atendCount === 0 || $diasCriacao > 14 || $lead->status === Lead::STATUS_PERDA) {
            return ['icon' => '❄️', 'label' => 'Frio', 'class' => 'bg-blue-50 text-blue-500'];
        }

        // 🟡 Morno (default)
        return ['icon' => '🟡', 'label' => 'Morno', 'class' => 'bg-yellow-50 text-yellow-600'];
    }

    // =========================================================================
    // DUPLICADOS (badge na listagem)
    // =========================================================================

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

    // =========================================================================
    // 13.6 — Atualizar Status inline
    // =========================================================================

    public function atualizarStatus(int $leadId, string $status): void
    {
        if (! array_key_exists($status, Lead::STATUS_LABELS)) {
            return;
        }

        Lead::where('id', $leadId)->update(['status' => $status]);
        $this->dispatch('status-atualizado');
    }

    // =========================================================================
    // 13.7 — Atribuir Responsável inline
    // =========================================================================

    public function atribuirResponsavel(int $leadId, $userId): void
    {
        $userId = $userId === '' ? null : (int) $userId;

        if ($userId !== null && ! User::where('id', $userId)->exists()) {
            return;
        }

        Lead::where('id', $leadId)->update(['user_id' => $userId]);
    }

    // =========================================================================
    // 13.8 — Preview do Imóvel
    // =========================================================================

    public function getPreviewImovel(): ?Imovel
    {
        if (! $this->previewImovelId) {
            return null;
        }

        return Imovel::with(['tipoImovel', 'municipio', 'estado'])
            ->find($this->previewImovelId);
    }

    // =========================================================================
    // 13.9 — Notas Rápidas
    // =========================================================================

    public function abrirNotas(int $leadId): void
    {
        $this->notasLeadId  = $leadId;
        $this->notaConteudo = '';
        $this->notaTipo     = 'anotacao';
        $this->dispatch('abrir-notas');
    }

    public function fecharNotas(): void
    {
        $this->notasLeadId  = null;
        $this->notaConteudo = '';
        $this->notaTipo     = 'anotacao';
        $this->dispatch('fechar-notas');
    }

    public function salvarNota(): void
    {
        $this->validate([
            'notaConteudo' => 'required|string|min:2|max:2000',
            'notaTipo'     => 'required|in:' . implode(',', array_keys(LeadNote::TIPOS)),
        ]);

        LeadNote::create([
            'lead_id'  => $this->notasLeadId,
            'user_id'  => auth()->id(),
            'conteudo' => $this->notaConteudo,
            'tipo'     => $this->notaTipo,
        ]);

        $this->notaConteudo = '';
        $this->dispatch('nota-salva');
    }

    public function getNotasLead()
    {
        if (! $this->notasLeadId) {
            return collect();
        }

        return LeadNote::where('lead_id', $this->notasLeadId)
            ->with('autor')
            ->latest()
            ->get();
    }

    // =========================================================================
    // 13.11 — Ações em Massa
    // =========================================================================

    public function acaoMassaStatus(): void
    {
        if (empty($this->leadsSelecionados) || ! array_key_exists($this->acaoMassaStatusValor, Lead::STATUS_LABELS)) {
            return;
        }

        Lead::whereIn('id', $this->leadsSelecionados)
            ->update(['status' => $this->acaoMassaStatusValor]);

        $this->leadsSelecionados    = [];
        $this->selecionarTodos      = false;
        $this->acaoMassaStatusValor = '';
        $this->dispatch('massa-atualizada');
    }

    public function exportarSelecionados()
    {
        if (empty($this->leadsSelecionados)) {
            return;
        }

        $leads = Lead::withCount('atendimentos')
            ->with('responsavel')
            ->whereIn('id', $this->leadsSelecionados)
            ->get();

        return $this->gerarCsv($leads, 'leads_selecionados_');
    }

    // =========================================================================
    // Exportação CSV (refatorada para reuso)
    // =========================================================================

    public function exportarCsv()
    {
        $leads = $this->queryLeads()->get();
        return $this->gerarCsv($leads, 'leads_admin_');
    }

    private function gerarCsv($leads, string $prefixo)
    {
        $linhas = [];
        $linhas[] = implode(';', [
            'ID', 'Nome', 'E-mail', 'Telefone', 'Status', 'Responsável',
            'UTM Source', 'UTM Medium', 'UTM Campaign',
            'Cadastro', 'Total Atendimentos', 'Imóveis de Interesse',
        ]);

        foreach ($leads as $lead) {
            $linhas[] = implode(';', [
                $lead->id,
                $lead->nome,
                $lead->email,
                $lead->telefone ?? '',
                Lead::STATUS_LABELS[$lead->status] ?? $lead->status ?? 'Novo',
                $lead->responsavel?->name ?? '—',
                $lead->utm_source ?? '',
                $lead->utm_medium ?? '',
                $lead->utm_campaign ?? '',
                $lead->created_at->format('d/m/Y H:i'),
                $lead->atendimentos_count,
                implode(' | ', array_map(
                    fn($item) => is_array($item) ? "#{$item['numero']}" : "#{$item}",
                    $lead->imoveis_interesse ?? []
                )),
            ]);
        }

        $csv      = implode("\n", $linhas);
        $filename = $prefixo . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(
            fn() => print($csv),
            $filename,
            ['Content-Type' => 'text/csv; charset=UTF-8']
        );
    }

    // =========================================================================
    // Ações de navegação
    // =========================================================================

    public function verDetalhe(int $id): void
    {
        $this->leadDetalheId = $id;
    }

    public function voltarLista(): void
    {
        $this->leadDetalheId = null;
    }

    // =========================================================================
    // RENDER
    // =========================================================================

    public function render()
    {
        $usuarios = User::orderBy('name')->get(['id', 'name']);

        // Tela de detalhe
        if ($this->leadDetalheId) {
            $lead = Lead::with([
                'atendimentos.imobiliaria',
                'atendimentos.imovel.tipoImovel',
                'atendimentos.imovel.municipio',
                'atendimentos.imovel.estado',
                'atendimentos.origem',
                'notes.autor',
                'responsavel',
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
                'usuarios'         => $usuarios,
                'kpis'             => [],
                'notasLead'        => $this->getNotasLead(),
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
            'usuarios'         => $usuarios,
            'kpis'             => $this->computeKpis(),
            'notasLead'        => $this->getNotasLead(),
        ])->layout('layouts.admin', ['title' => 'Leads']);
    }
}

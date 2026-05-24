<?php

namespace App\Modules\Admin\Livewire;

use App\Models\Atendimento;
use App\Models\Imobiliaria;
use App\Models\Imovel;
use App\Models\Lead;
use App\Modules\ImportacaoCSV\Services\CaixaCsvParserService;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class AdminDashboard extends Component
{
    /**
     * Progresso da importação CSV em tempo real (lido do cache).
     * Retorna null se nenhuma importação ativa/recente.
     */
    public function getImportProgressProperty(): ?array
    {
        return Cache::get(CaixaCsvParserService::PROGRESS_CACHE_KEY);
    }

    /**
     * Limpa o indicador de importação concluída do dashboard.
     */
    public function dismissImportProgress(): void
    {
        Cache::forget(CaixaCsvParserService::PROGRESS_CACHE_KEY);
    }

    public function getMetricasProperty(): array
    {
        return [
            'imoveis_ativos'       => Imovel::where('status', 'ativo')->count(),
            'leads_hoje'           => Lead::whereDate('created_at', today())->count(),
            'leads_7d'             => Lead::where('created_at', '>=', now()->subDays(7))->count(),
            'leads_30d'            => Lead::where('created_at', '>=', now()->subDays(30))->count(),
            'atendimentos_hoje'    => Atendimento::whereDate('created_at', today())->count(),
            'atendimentos_7d'      => Atendimento::where('created_at', '>=', now()->subDays(7))->count(),
            'atendimentos_30d'     => Atendimento::where('created_at', '>=', now()->subDays(30))->count(),
            'imobiliarias_ativas'  => Imobiliaria::where('ativo', true)->count(),
            'total_leads'          => Lead::count(),
            'total_atendimentos'   => Atendimento::count(),
        ];
    }

    public function getUltimosAtendimentosProperty()
    {
        return Atendimento::with(['lead', 'imovel.tipoImovel', 'imovel.municipio', 'imovel.estado', 'imobiliaria'])
            ->latest()
            ->limit(10)
            ->get();
    }

    public function getTopImoveisProperty()
    {
        return Imovel::withCount('atendimentos')
            ->with(['tipoImovel', 'municipio', 'estado'])
            ->whereHas('atendimentos')
            ->orderByDesc('atendimentos_count')
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('modules.admin.livewire.admin-dashboard')
            ->layout('layouts.admin', ['title' => 'Dashboard']);
    }
}

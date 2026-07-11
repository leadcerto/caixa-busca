<?php

namespace App\Modules\Admin\Livewire;

use App\Models\CampaignPageView;
use App\Models\Vitrine;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CampanhaDashboard extends Component
{
    // Filtros
    public string $period    = '7d';
    public string $utmSource = '';
    public int    $bairroId  = 0;

    // Formulário de criação de vitrine
    public string $novaVitrineNome = '';
    public string $novaVitrineUrl  = '';
    public string $mensagem        = '';
    public string $mensagemTipo    = '';

    /**
     * Criar nova vitrine a partir da URL de busca.
     */
    public function criarVitrine(): void
    {
        $this->mensagem = '';
        $this->mensagemTipo = '';

        if (empty(trim($this->novaVitrineNome))) {
            $this->mensagem = 'Informe o nome da campanha.';
            $this->mensagemTipo = 'erro';
            return;
        }

        if (empty(trim($this->novaVitrineUrl))) {
            $this->mensagem = 'Cole a URL da busca.';
            $this->mensagemTipo = 'erro';
            return;
        }

        try {
            $filtros = Vitrine::extrairFiltrosDaUrl($this->novaVitrineUrl);

            if (empty($filtros['estado'])) {
                $this->mensagem = 'URL inválida. Use uma URL de busca do site (ex: /imoveis/rj/rio-de-janeiro?...).';
                $this->mensagemTipo = 'erro';
                return;
            }

            $vitrine = Vitrine::create([
                'nome'          => trim($this->novaVitrineNome),
                'filtros'       => $filtros,
                'url_original'  => trim($this->novaVitrineUrl),
            ]);

            $this->novaVitrineNome = '';
            $this->novaVitrineUrl  = '';
            $this->mensagem = "Vitrine \"{$vitrine->nome}\" criada com sucesso!";
            $this->mensagemTipo = 'sucesso';
        } catch (\Throwable $e) {
            $this->mensagem = 'Erro ao criar vitrine: ' . $e->getMessage();
            $this->mensagemTipo = 'erro';
        }
    }

    /**
     * Ativar/desativar uma vitrine.
     */
    public function toggleVitrine(int $id): void
    {
        $vitrine = Vitrine::find($id);
        if ($vitrine) {
            $vitrine->update(['ativa' => !$vitrine->ativa]);
        }
    }

    /**
     * Excluir uma vitrine.
     */
    public function excluirVitrine(int $id): void
    {
        Vitrine::destroy($id);
        $this->mensagem = 'Vitrine excluída.';
        $this->mensagemTipo = 'sucesso';
    }

    public function render()
    {
        $base = CampaignPageView::query()
            ->wherePeriod($this->period)
            ->whereUtmSource($this->utmSource ?: null)
            ->whereBairroId($this->bairroId ?: null);

        // Métricas dos cards
        $totalAcessos   = (clone $base)->count();
        $acessosHoje    = CampaignPageView::whereDate('created_at', today())->count();
        $fontesUnicas   = (clone $base)->whereNotNull('utm_source')->distinct('utm_source')->count('utm_source');
        $totalMobile    = (clone $base)->where('device_type', 'mobile')->count();
        $pctMobile      = $totalAcessos > 0 ? round(($totalMobile / $totalAcessos) * 100) : 0;

        // Top bairros
        $topBairros = (clone $base)
            ->select('bairro_id', DB::raw('COUNT(*) as total'))
            ->with(['bairro.municipio.estado'])
            ->groupBy('bairro_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Acessos por utm_source
        $porFonte = (clone $base)
            ->select(
                DB::raw("COALESCE(utm_source, 'direto') as fonte"),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('fonte')
            ->orderByDesc('total')
            ->get();

        // Campanhas ativas (utm_campaign)
        $campanhasAtivas = (clone $base)
            ->whereNotNull('utm_campaign')
            ->select(
                'utm_campaign',
                'utm_source',
                DB::raw('COUNT(*) as total'),
                DB::raw('MIN(created_at) as primeiro_acesso'),
                DB::raw('MAX(created_at) as ultimo_acesso')
            )
            ->groupBy('utm_campaign', 'utm_source')
            ->orderByDesc('total')
            ->limit(20)
            ->get();

        // Acessos recentes
        $acessosRecentes = (clone $base)
            ->with('bairro.municipio.estado')
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        // Fontes únicas disponíveis para o filtro
        $fontesDisponiveis = CampaignPageView::query()
            ->whereNotNull('utm_source')
            ->distinct('utm_source')
            ->pluck('utm_source')
            ->sort()
            ->values();

        // Vitrines cadastradas
        $vitrines = Vitrine::orderByDesc('created_at')->get();

        return view('modules.admin.livewire.campanha-dashboard', [
            'totalAcessos'      => $totalAcessos,
            'acessosHoje'       => $acessosHoje,
            'fontesUnicas'      => $fontesUnicas,
            'pctMobile'         => $pctMobile,
            'topBairros'        => $topBairros,
            'porFonte'          => $porFonte,
            'campanhasAtivas'   => $campanhasAtivas,
            'acessosRecentes'   => $acessosRecentes,
            'fontesDisponiveis' => $fontesDisponiveis,
            'vitrines'          => $vitrines,
        ])->layout('layouts.admin', ['title' => 'Campanhas']);
    }
}

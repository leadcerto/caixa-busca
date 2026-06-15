<?php

namespace App\Modules\Admin\Livewire;

use App\Models\Bairro;
use App\Models\Estado;
use App\Models\Municipio;
use App\Modules\BairrosDossie\Jobs\GerarConteudoBairroJob;
use Livewire\Component;
use Livewire\WithPagination;

class BairrosDossie extends Component
{
    use WithPagination;

    public string $estadoId    = '';
    public string $municipioId = '';
    public string $statusFiltro = '';

    public ?string $mensagem = null;
    public bool    $sucesso  = false;

    public function updatedEstadoId(): void   { $this->municipioId = ''; $this->resetPage(); }
    public function updatedMunicipioId(): void { $this->resetPage(); }
    public function updatedStatusFiltro(): void { $this->resetPage(); }

    public function gerarUm(int $id): void
    {
        $bairro = Bairro::findOrFail($id);
        GerarConteudoBairroJob::dispatch($bairro->id);

        $bairro->update(['ia_status' => 'pendente']);

        $this->mensagem = "Job enfileirado para: {$bairro->nome}";
        $this->sucesso  = true;
    }

    public function gerarLote(): void
    {
        $query = $this->queryBairros()->whereHas('imoveis', fn($q) => $q->where('status', 'ativo'));
        $bairros = $query->get();

        if ($bairros->isEmpty()) {
            $this->mensagem = 'Nenhum bairro encontrado com os filtros atuais.';
            $this->sucesso  = false;
            return;
        }

        foreach ($bairros as $i => $bairro) {
            // Delay escalonado: 1 job a cada 3s para não bater rate limit
            GerarConteudoBairroJob::dispatch($bairro->id)->delay(now()->addSeconds($i * 3));
            $bairro->update(['ia_status' => 'pendente']);
        }

        $this->mensagem = "{$bairros->count()} jobs enfileirados com sucesso (1 a cada 3s).";
        $this->sucesso  = true;
    }

    public function excluirSemImoveis(): void
    {
        $count = Bairro::doesntHave('imoveis')->delete();

        $this->mensagem = $count > 0
            ? "{$count} bairro(s) sem imóveis excluídos."
            : 'Nenhum bairro sem imóveis encontrado.';
        $this->sucesso = $count > 0;
    }

    public function resetarParaFaq(): void
    {
        // Marca como pendente bairros com conteúdo gerado mas sem os novos campos FAQ
        $count = Bairro::where('ia_status', 'gerado')
            ->whereNotNull('conteudo_ia')
            ->get()
            ->filter(fn($b) => empty(($b->conteudo_ia)['vizinhanca_localizacao']))
            ->each(fn($b) => $b->update(['ia_status' => 'pendente', 'conteudo_ia' => null]))
            ->count();

        $this->mensagem = $count > 0
            ? "{$count} bairro(s) com conteúdo antigo resetados — use 'Gerar em Lote' para regerá-los."
            : 'Todos os bairros já possuem o conteúdo no novo formato.';
        $this->sucesso = $count > 0;
    }

    private function queryBairros()
    {
        $query = Bairro::with(['municipio.estado'])
            ->withCount(['imoveis' => fn($q) => $q->where('status', 'ativo')]);

        if ($this->estadoId) {
            $query->whereHas('municipio', fn($q) => $q->where('id_estado', $this->estadoId));
        }

        if ($this->municipioId) {
            $query->where('id_municipio', $this->municipioId);
        }

        if ($this->statusFiltro) {
            $query->where('ia_status', $this->statusFiltro);
        }

        return $query->orderByRaw('(SELECT COUNT(*) FROM imoveis WHERE imoveis.id_bairro = bairros.id AND status = \'ativo\' AND deleted_at IS NULL) DESC')
                     ->orderBy('ia_status')
                     ->orderBy('nome');
    }

    public function render()
    {
        $bairros     = $this->queryBairros()->paginate(30);
        $estados     = Estado::orderBy('uf')->get(['id', 'uf', 'nome']);
        $municipios  = $this->estadoId
            ? Municipio::where('id_estado', $this->estadoId)->orderBy('nome')->get(['id', 'nome'])
            : collect();

        $totais = [
            'gerado'  => Bairro::where('ia_status', 'gerado')->count(),
            'pendente' => Bairro::where('ia_status', 'pendente')->count(),
            'erro'    => Bairro::where('ia_status', 'erro')->count(),
            'total'   => Bairro::count(),
        ];

        return view('modules.admin.livewire.bairros-dossie', [
            'bairros'    => $bairros,
            'estados'    => $estados,
            'municipios' => $municipios,
            'totais'     => $totais,
        ])->layout('layouts.admin', ['title' => 'Bairros Dossiê']);
    }
}

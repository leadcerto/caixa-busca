<?php

namespace App\Modules\Imoveis\Livewire;

use App\Models\Bairro;
use App\Models\Estado;
use App\Models\Imovel;
use App\Models\Municipio;
use App\Models\TipoImovel;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class ImovelSearch extends Component
{
    use WithPagination;

    // Filtros — únicos dados serializados pelo Livewire no snapshot
    public string $busca_numero = '';
    public ?int $id_estado = null;
    public ?int $id_municipio = null;
    public array $bairros_selecionados = [];
    public string $preco_min = '';
    public string $preco_max = '';
    public string $financiamento = 'todos';
    public string $ordenacao = 'recente';
    public string $tipo = '';
    public bool $show_results = false;

    protected $queryString = [
        'busca_numero'         => ['except' => ''],
        'id_estado'            => ['except' => null],
        'id_municipio'         => ['except' => null],
        'bairros_selecionados' => ['except' => []],
        'preco_min'            => ['except' => ''],
        'preco_max'            => ['except' => ''],
        'financiamento'        => ['except' => 'todos'],
        'ordenacao'            => ['except' => 'recente'],
        'tipo'                 => ['except' => ''],
        'show_results'         => ['except' => false],
    ];

    public function mount(): void
    {
        if ($this->busca_numero !== '' ||
            $this->id_estado !== null ||
            $this->id_municipio !== null ||
            !empty($this->bairros_selecionados) ||
            $this->preco_min !== '' ||
            $this->preco_max !== '' ||
            $this->tipo !== '' ||
            $this->financiamento !== 'todos') {
            $this->show_results = true;
        }
    }

    public function updatedIdEstado(): void
    {
        $this->id_municipio = null;
        $this->bairros_selecionados = [];
        $this->resetPage();
    }

    public function updatedIdMunicipio(): void
    {
        $this->bairros_selecionados = [];
        $this->resetPage();
    }

    public function updated(?string $property = null): void
    {
        if (empty($property)) {
            return;
        }

        if (in_array($property, ['id_estado', 'id_municipio', 'page']) || str_starts_with($property, 'paginators')) {
            return;
        }

        $this->resetPage();
    }

    public function selecionarTodosBairros(): void
    {
        if ($this->id_municipio) {
            $ids = Bairro::where('id_municipio', $this->id_municipio)
                ->whereHas('imoveis', fn ($q) => $q->where('status', 'ativo'))
                ->pluck('id')
                ->toArray();
            $this->bairros_selecionados = $ids;
        }
        $this->resetPage();
    }

    public function limparBairrosSelecionados(): void
    {
        $this->bairros_selecionados = [];
        $this->resetPage();
    }

    public function buscar(): mixed
    {
        if ($this->id_estado) {
            $estado = Estado::find($this->id_estado);

            if ($estado) {
                // Monta segmentos: com tipo (/imoveis/casa/rj) ou sem (/imoveis/rj)
                if ($this->tipo) {
                    $segments = ['imoveis', \Illuminate\Support\Str::slug($this->tipo), strtolower($estado->uf)];
                } else {
                    $segments = ['imoveis', strtolower($estado->uf)];
                }

                if ($this->id_municipio) {
                    $municipio = Municipio::find($this->id_municipio);
                    if ($municipio) {
                        $segments[] = \Illuminate\Support\Str::slug($municipio->nome);
                    }
                }

                $params = ['ordenar' => 'preco_asc'];

                if ($this->financiamento === 'sim') {
                    $params['financiamento'] = ['sbpe'];
                }
                if ($this->preco_min) {
                    $params['preco_min'] = $this->preco_min;
                }
                if ($this->preco_max) {
                    $params['preco_max'] = $this->preco_max;
                }
                if (! empty($this->bairros_selecionados)) {
                    $params['bairros_ids'] = $this->bairros_selecionados;
                }

                return redirect()->to('/' . implode('/', $segments) . '?' . http_build_query($params));
            }
        }

        $this->show_results = true;
        $this->resetPage();
        return null;
    }

    public function limparFiltros(): void
    {
        $this->reset([
            'busca_numero',
            'id_estado',
            'id_municipio',
            'preco_min',
            'preco_max',
            'financiamento',
            'ordenacao',
            'tipo',
            'show_results',
        ]);
        $this->bairros_selecionados = [];
        $this->resetPage();
    }

    public function render()
    {
        // Dropdowns carregados no render() — nunca serializados pelo Livewire.
        // Cache armazena arrays simples (não Eloquent) para evitar falha no unserialize do Hostinger.
        $estados = collect(Cache::remember('dropdown_estados_com_imoveis', 3600, fn () =>
            Estado::whereHas('imoveis', fn ($q) => $q->where('status', 'ativo'))
                ->orderBy('nome')
                ->select('id', 'nome', 'uf')
                ->get()
                ->toArray()
        ))->map(fn ($e) => (object) $e)->values();

        $tipos = collect(Cache::remember('dropdown_tipos_imoveis', 86400, fn () =>
            TipoImovel::where('ativo', true)->orderBy('nome')->select('id', 'nome')->get()->toArray()
        ))->map(fn ($e) => (object) $e)->values();

        $municipios = collect([]);
        if ($this->id_estado) {
            $municipios = Municipio::where('id_estado', $this->id_estado)
                ->orderBy('nome')
                ->selectRaw('id, UPPER(nome) as nome')
                ->get();
        }

        $bairros = collect([]);
        if ($this->id_municipio) {
            $bairros = Bairro::where('id_municipio', $this->id_municipio)
                ->whereHas('imoveis', fn ($q) => $q->where('status', 'ativo'))
                ->orderBy('nome')
                ->selectRaw('id, UPPER(nome) as nome')
                ->get();
        }

        $imoveis = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12, 1);

        if ($this->show_results) {
            $query = Imovel::query()
                ->select('imoveis.*')
                ->join('imoveis_historico as latest_h', function ($join) {
                    $join->on('latest_h.id_imovel', '=', 'imoveis.id')
                        ->whereRaw('latest_h.id = (
                            SELECT id FROM imoveis_historico
                            WHERE id_imovel = imoveis.id
                            ORDER BY created_at DESC, id DESC
                            LIMIT 1
                        )');
                })
                ->where('imoveis.status', 'ativo');

            if ($this->busca_numero) {
                $query->where('imoveis.numero_original', 'like', "%{$this->busca_numero}%");
            }

            if ($this->id_estado) {
                $query->where('imoveis.id_estado', $this->id_estado);
            }
            if ($this->id_municipio) {
                $query->where('imoveis.id_municipio', $this->id_municipio);
            }
            if ($this->bairros_selecionados) {
                $query->whereIn('imoveis.id_bairro', $this->bairros_selecionados);
            }

            if ($this->tipo) {
                $query->whereHas('tipoImovel', fn ($q) => $q->where('nome', $this->tipo));
            }

            if ($this->preco_min) {
                $query->where('latest_h.valor_venda', '>=', (float) str_replace(',', '.', $this->preco_min));
            }
            if ($this->preco_max) {
                $query->where('latest_h.valor_venda', '<=', (float) str_replace(',', '.', $this->preco_max));
            }

            if ($this->financiamento === 'sim') {
                $query->where('imoveis.aceita_fgts', 'sim');
            }

            switch ($this->ordenacao) {
                case 'desconto_pct_desc':
                    $query->orderBy('latest_h.desconto_percentual', 'desc');
                    break;
                case 'desconto_reais_desc':
                    $query->orderBy('latest_h.desconto_valor', 'desc');
                    break;
                case 'preco_asc':
                    $query->orderBy('latest_h.valor_venda', 'asc');
                    break;
                case 'preco_desc':
                    $query->orderBy('latest_h.valor_venda', 'desc');
                    break;
                case 'recente':
                default:
                    $query->orderBy('latest_h.valor_venda', 'asc');
                    break;
            }

            $imoveis = $query->with(['tipoImovel', 'municipio', 'estado', 'bairro', 'ultimoHistorico'])
                ->paginate(12);
        }

        return view('modules.imoveis.livewire.imovel-search', compact(
            'imoveis', 'estados', 'tipos', 'municipios', 'bairros'
        ))->layout('layouts.app', [
            'meta_title'       => 'Imóveis da Caixa Econômica Federal | Busca e Comparação',
            'meta_description' => 'Encontre imóveis da Caixa Econômica Federal com descontos de até 50%. Busque por cidade, bairro, tipo e preço. Financiamento pelo FGTS e SBPE disponível.',
            'og_image'         => asset('images/imovel-placeholder.svg'),
            'canonical'        => url('/'),
        ]);
    }
}

<?php

namespace App\Modules\Admin\Livewire;

use App\Models\Bairro;
use App\Models\Estado;
use App\Models\Imovel;
use App\Models\Municipio;
use Livewire\Component;
use Livewire\WithPagination;

class ImovelBuscaInterna extends Component
{
    use WithPagination;

    // Filters
    public string $busca_numero = '';
    public ?int $id_estado = null;
    public ?int $id_municipio = null;
    public array $bairros_selecionados = [];
    public string $preco_min = '';
    public string $preco_max = '';
    public string $financiamento = 'todos'; // 'todos' or 'sim'
    public string $ordenacao = 'recente'; // 'recente', 'desconto_pct_desc', 'desconto_reais_desc', 'preco_asc', 'preco_desc'

    // Dropdowns data
    public $estados = [];
    public $municipios = [];
    public $bairros = [];

    protected $queryString = [
        'busca_numero'  => ['except' => ''],
        'id_estado'     => ['except' => null],
        'id_municipio'  => ['except' => null],
        'bairros_selecionados' => ['except' => []],
        'preco_min'     => ['except' => ''],
        'preco_max'     => ['except' => ''],
        'financiamento' => ['except' => 'todos'],
        'ordenacao'     => ['except' => 'recente'],
    ];

    public function mount(): void
    {
        $this->estados = Estado::whereHas('imoveis', fn ($q) => $q->where('status', 'ativo'))
            ->orderBy('nome')
            ->get(['id', 'nome', 'uf']);
        $this->carregarMunicipios();
        $this->carregarBairros();
    }

    public function updatedIdEstado(): void
    {
        $this->id_municipio = null;
        $this->bairros_selecionados = [];
        $this->carregarMunicipios();
        $this->carregarBairros();
        $this->resetPage();
    }

    public function updatedIdMunicipio(): void
    {
        $this->bairros_selecionados = [];
        $this->carregarBairros();
        $this->resetPage();
    }

    public function updated(?string $property = null): void
    {
        if (empty($property)) {
            return;
        }

        // Avoid infinite loop recursion when pagination properties are updated
        if (in_array($property, ['id_estado', 'id_municipio', 'page']) || str_starts_with($property, 'paginators')) {
            return;
        }

        $this->resetPage();
    }

    public function carregarMunicipios(): void
    {
        if ($this->id_estado) {
            $this->municipios = Municipio::where('id_estado', $this->id_estado)->orderBy('nome')->get();
        } else {
            $this->municipios = [];
        }
    }

    public function carregarBairros(): void
    {
        if ($this->id_municipio) {
            $this->bairros = Bairro::where('id_municipio', $this->id_municipio)
                ->whereHas('imoveis', function ($q) {
                    $q->where('status', 'ativo');
                })
                ->orderBy('nome')
                ->get();
        } else {
            $this->bairros = [];
        }
    }

    public function selecionarTodosBairros(): void
    {
        if ($this->bairros) {
            $this->bairros_selecionados = collect($this->bairros)->pluck('id')->toArray();
        }
        $this->resetPage();
    }

    public function limparBairrosSelecionados(): void
    {
        $this->bairros_selecionados = [];
        $this->resetPage();
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
        ]);
        $this->bairros_selecionados = [];
        $this->municipios = [];
        $this->bairros = [];
        $this->resetPage();
    }

    public function render()
    {
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

        // Filter by number
        if ($this->busca_numero) {
            $query->where('imoveis.numero_original', 'like', "%{$this->busca_numero}%");
        }

        // Filter by Location
        if ($this->id_estado) {
            $query->where('imoveis.id_estado', $this->id_estado);
        }
        if ($this->id_municipio) {
            $query->where('imoveis.id_municipio', $this->id_municipio);
        }
        if ($this->bairros_selecionados) {
            $query->whereIn('imoveis.id_bairro', $this->bairros_selecionados);
        }

        // Filter by Price range
        if ($this->preco_min) {
            $query->where('latest_h.valor_venda', '>=', (float) str_replace(',', '.', $this->preco_min));
        }
        if ($this->preco_max) {
            $query->where('latest_h.valor_venda', '<=', (float) str_replace(',', '.', $this->preco_max));
        }

        // Filter by Financing (CSV 'financiamento' = SBPE; data lives in aceita_fgts)
        if ($this->financiamento === 'sim') {
            $query->where('imoveis.aceita_fgts', 'sim');
        }

        // Apply Sorting
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
                $query->orderBy('imoveis.updated_at', 'desc');
                break;
        }

        $imoveis = $query->with(['tipoImovel', 'municipio', 'estado', 'bairro', 'ultimoHistorico'])
            ->paginate(10);

        return view('modules.admin.livewire.imovel-busca-interna', compact('imoveis'))
            ->layout('layouts.admin', ['title' => 'Busca Interna de Imóveis']);
    }
}

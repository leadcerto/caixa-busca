<?php

namespace App\Modules\Imoveis\Livewire;

use App\Models\Bairro;
use App\Models\Estado;
use App\Models\Imovel;
use App\Models\Municipio;
use App\Models\TipoImovel;
use Livewire\Component;
use Livewire\WithPagination;

class ImovelSearch extends Component
{
    use WithPagination;

    // Filtros
    public string $busca_numero = '';
    public ?int $id_estado = null;
    public ?int $id_municipio = null;
    public array $bairros_selecionados = [];
    public string $preco_min = '';
    public string $preco_max = '';
    public string $financiamento = 'todos'; // 'todos' ou 'sim'
    public string $ordenacao = 'recente'; // 'recente', 'desconto_pct_desc', 'desconto_reais_desc', 'preco_asc', 'preco_desc'
    public string $tipo = '';
    public bool $show_results = false;

    // Dados de dropdowns
    public $estados = [];
    public $municipios = [];
    public $bairros = [];
    public $tipos = [];

    protected $queryString = [
        'busca_numero'  => ['except' => ''],
        'id_estado'     => ['except' => null],
        'id_municipio'  => ['except' => null],
        'bairros_selecionados' => ['except' => []],
        'preco_min'     => ['except' => ''],
        'preco_max'     => ['except' => ''],
        'financiamento' => ['except' => 'todos'],
        'ordenacao'     => ['except' => 'recente'],
        'tipo'          => ['except' => ''],
        'show_results'  => ['except' => false],
    ];

    public function mount(): void
    {
        $this->estados = Cache::remember('dropdown_estados_com_imoveis', 3600, fn () =>
            Estado::whereHas('imoveis', fn ($q) => $q->where('status', 'ativo'))
                ->orderBy('nome')
                ->get()
        );
        $this->tipos = TipoImovel::where('ativo', true)->orderBy('nome')->get();
        $this->carregarMunicipios();
        $this->carregarBairros();

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

    public function buscar(): void
    {
        $this->show_results = true;
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
            'tipo',
            'show_results',
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

        // Filtro por Número do Imóvel
        if ($this->busca_numero) {
            $query->where('imoveis.numero_original', 'like', "%{$this->busca_numero}%");
        }

        // Filtros de Localização
        if ($this->id_estado) {
            $query->where('imoveis.id_estado', $this->id_estado);
        }
        if ($this->id_municipio) {
            $query->where('imoveis.id_municipio', $this->id_municipio);
        }
        if ($this->bairros_selecionados) {
            $query->whereIn('imoveis.id_bairro', $this->bairros_selecionados);
        }

        // Filtro por Tipo de Imóvel
        if ($this->tipo) {
            $query->whereHas('tipoImovel', fn($q) => $q->where('nome', $this->tipo));
        }

        // Filtro por Faixa de Preço
        if ($this->preco_min) {
            $query->where('latest_h.valor_venda', '>=', (float) str_replace(',', '.', $this->preco_min));
        }
        if ($this->preco_max) {
            $query->where('latest_h.valor_venda', '<=', (float) str_replace(',', '.', $this->preco_max));
        }

        // Filtro por Financiamento
        if ($this->financiamento === 'sim') {
            $query->where('imoveis.aceita_fgts', 'sim');
        }

        // Ordenação Dinâmica
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
            ->paginate(12);

        return view('modules.imoveis.livewire.imovel-search', compact('imoveis'));
    }
}

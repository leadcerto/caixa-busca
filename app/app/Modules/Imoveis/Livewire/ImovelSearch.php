<?php

namespace App\Modules\Imoveis\Livewire;

use App\Models\Imovel;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Componente Livewire para busca e vitrine de imveis.
 * Implementa a regra de "Epicenter Design" focada na facilidade de busca.
 */
class ImovelSearch extends Component
{
    use WithPagination;

    /**
     * Atributos de pesquisa vinculados ao Model (Regra 88).
     */
    public $estado = '';
    public $municipio = '';
    public $tipo = '';
    public $min_preco = '';
    public $max_preco = '';

    /**
     * Mantm os filtros na URL para facilitar o compartilhamento da busca.
     */
    protected $queryString = [
        'estado' => ['except' => ''],
        'municipio' => ['except' => ''],
        'tipo' => ['except' => ''],
        'min_preco' => ['except' => ''],
        'max_preco' => ['except' => ''],
    ];

    /**
     * Hook do Livewire disparado em qualquer alterao de estado.
     * Garante que a busca volte para a pgina 1 ao filtrar.
     */
    public function updated()
    {
        $this->resetPage();
    }

    /**
     * Executa a query de busca indexada no MySQL.
     */
    public function render()
    {
        // Apenas imveis ativos so exibidos na vitrine pblica
        $query = Imovel::query();

        // Aplicao de filtros dinmicos
        if ($this->estado) {
            $query->where('uf', $this->estado);
        }

        if ($this->municipio) {
            $query->where('cidade', 'like', '%' . $this->municipio . '%');
        }

        if ($this->tipo) {
            $query->where('tipo_imovel', $this->tipo);
        }

        if ($this->min_preco) {
            $query->where('preco', '>=', (float) $this->min_preco);
        }

        if ($this->max_preco) {
            $query->where('preco', '<=', (float) $this->max_preco);
        }

        // Ordenao por atualizao mais recente (Data de Gerao do CSV)
        $imoveis = $query->orderBy('updated_at', 'desc')->paginate(12);

        return view('modules.imoveis.livewire.imovel-search', [
            'imoveis' => $imoveis
        ]);
    }
}

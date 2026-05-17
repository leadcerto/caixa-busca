<?php

namespace App\Modules\Imoveis\Livewire;

use App\Models\Estado;
use App\Models\Imovel;
use App\Models\TipoImovel;
use Livewire\Component;
use Livewire\WithPagination;

class ImovelSearch extends Component
{
    use WithPagination;

    public string $estado    = '';
    public string $municipio = '';
    public string $tipo      = '';
    public string $min_preco = '';
    public string $max_preco = '';

    protected $queryString = [
        'estado'    => ['except' => ''],
        'municipio' => ['except' => ''],
        'tipo'      => ['except' => ''],
        'min_preco' => ['except' => ''],
        'max_preco' => ['except' => ''],
    ];

    public function updated(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Imovel::query()->where('status', 'ativo');

        if ($this->estado) {
            $query->whereHas('estado', fn($q) => $q->where('uf', $this->estado));
        }

        if ($this->municipio) {
            $query->whereHas('municipio', fn($q) =>
                $q->where('nome', 'like', '%' . $this->municipio . '%')
            );
        }

        if ($this->tipo) {
            $query->whereHas('tipoImovel', fn($q) => $q->where('nome', $this->tipo));
        }

        if ($this->min_preco || $this->max_preco) {
            $query->whereHas('historico', function ($h) {
                // Garante que é o histórico mais recente do imóvel
                $h->whereNotExists(fn($newer) =>
                    $newer->from('imoveis_historico as h2')
                        ->whereColumn('h2.id_imovel', 'imoveis_historico.id_imovel')
                        ->whereColumn('h2.created_at', '>', 'imoveis_historico.created_at')
                );
                if ($this->min_preco) {
                    $h->where('valor_venda', '>=', (float) str_replace(',', '.', $this->min_preco));
                }
                if ($this->max_preco) {
                    $h->where('valor_venda', '<=', (float) str_replace(',', '.', $this->max_preco));
                }
            });
        }

        $imoveis = $query
            ->with(['estado', 'municipio', 'tipoImovel', 'bairro', 'ultimoHistorico'])
            ->orderBy('updated_at', 'desc')
            ->paginate(12);

        $estados = Estado::orderBy('nome')->get();
        $tipos   = TipoImovel::where('ativo', true)->orderBy('nome')->get();

        return view('modules.imoveis.livewire.imovel-search', compact('imoveis', 'estados', 'tipos'));
    }
}

<?php

namespace App\Modules\BairrosDossie\Livewire;

use App\Models\Bairro;
use App\Models\Estado;
use App\Models\Municipio;
use App\Models\Imovel;
use Livewire\Component;
use Livewire\WithPagination;

class PaginaBairro extends Component
{
    use WithPagination;

    public Bairro $bairro;

    public function mount(string $uf, string $municipio_slug, string $bairro_slug): void
    {
        $estado = Estado::where('uf', strtoupper($uf))->firstOrFail();

        $municipio = Municipio::where('id_estado', $estado->id)
            ->where('slug', $municipio_slug)
            ->firstOrFail();

        $this->bairro = Bairro::where('id_municipio', $municipio->id)
            ->where('slug', $bairro_slug)
            ->with(['municipio.estado'])
            ->firstOrFail();
    }

    public function render()
    {
        $imoveis = Imovel::where('id_bairro', $this->bairro->id)
            ->where('ativo', true)
            ->with(['tipoImovel', 'ultimoHistorico'])
            ->orderByDesc('created_at')
            ->paginate(12);

        $conteudo = $this->bairro->conteudo_ia ?? [];
        $titulo   = $conteudo['titulo']   ?? "Imóveis em {$this->bairro->nome}";
        $metaDesc = $conteudo['meta_description'] ?? "Imóveis da Caixa Econômica Federal em {$this->bairro->nome}, {$this->bairro->municipio?->nome}.";
        $texto    = $conteudo['texto']    ?? null;

        return view('modules.bairros-dossie.livewire.pagina-bairro', [
            'imoveis'  => $imoveis,
            'conteudo' => $conteudo,
            'titulo'   => $titulo,
            'texto'    => $texto,
        ])->layout('layouts.app', [
            'meta_title'       => $titulo . ' | Antigravity',
            'meta_description' => $metaDesc,
        ]);
    }
}

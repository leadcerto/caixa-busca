<?php

namespace App\Modules\Imoveis\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ImovelCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($imovel) {
                $ultimo = $imovel->ultimoHistorico;

                return [
                    'id' => $imovel->id,
                    'numero_original' => $imovel->numero_original,
                    'slug' => $imovel->slug,
                    'status' => $imovel->status,
                    'tipo_imovel' => $imovel->tipoImovel?->nome,
                    'localizacao' => [
                        'bairro' => $imovel->bairro?->nome,
                        'municipio' => $imovel->municipio?->nome,
                        'estado' => $imovel->estado?->uf,
                    ],
                    'detalhes' => [
                        'area_total' => $imovel->area_total ? (float) $imovel->area_total : null,
                        'quartos' => $imovel->quartos,
                        'banheiros' => $imovel->banheiros,
                        'garagens' => $imovel->garagens,
                    ],
                    'financeiro' => [
                        'valor_venda' => $ultimo ? (float) $ultimo->valor_venda : null,
                        'valor_avaliacao' => $ultimo ? (float) $ultimo->valor_avaliacao : null,
                        'desconto_percentual' => $ultimo && $ultimo->valor_avaliacao > 0
                            ? round((($ultimo->valor_avaliacao - $ultimo->valor_venda) / $ultimo->valor_avaliacao) * 100, 2)
                            : 0,
                        'modalidade_venda' => $ultimo?->modalidade?->nome,
                    ],
                    'imagens' => [
                        'foto_fachada' => $imovel->foto_fachada_url ?: asset('images/imovel-placeholder.svg'),
                    ],
                    'link_matricula' => $imovel->link_matricula,
                    'criado_em' => $imovel->created_at?->toIso8601String(),
                ];
            }),
        ];
    }
}

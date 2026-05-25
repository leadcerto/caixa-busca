<?php

namespace App\Modules\Imoveis\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImovelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $ultimo = $this->ultimoHistorico;

        // Extract historical prices list
        $historico = $this->whenLoaded('historico', function () {
            return $this->historico->map(fn($h) => [
                'valor_venda' => (float) $h->valor_venda,
                'valor_avaliacao' => (float) $h->valor_avaliacao,
                'desconto' => $h->valor_avaliacao > 0 
                    ? round((($h->valor_avaliacao - $h->valor_venda) / $h->valor_avaliacao) * 100, 2)
                    : 0,
                'modalidade' => $h->modalidade?->nome,
                'data_registro' => $h->created_at?->toIso8601String(),
            ]);
        });

        // Safe features list
        $caracteristicas = [];
        $features = ['varanda', 'area_servico', 'cozinha', 'piscina', 'churrasqueira', 'terraco'];
        foreach ($features as $f) {
            if ($this->{$f}) {
                $caracteristicas[] = str_replace('_', ' ', $f);
            }
        }

        // Bairro AI dossier content
        $dossieBairro = null;
        if ($this->relationLoaded('bairro') && $this->bairro && $this->bairro->conteudo_ia) {
            $dossieBairro = [
                'titulo' => $this->bairro->conteudo_ia['titulo'] ?? null,
                'meta_description' => $this->bairro->conteudo_ia['meta_description'] ?? null,
                'texto' => $this->bairro->conteudo_ia['texto'] ?? null,
                'gerado_em' => $this->bairro->ia_gerado_em?->toIso8601String(),
            ];
        }

        return [
            'id' => $this->id,
            'numero_original' => $this->numero_original,
            'slug' => $this->slug,
            'status' => $this->status,
            'tipo_imovel' => $this->tipoImovel?->nome,
            'endereco' => [
                'logradouro' => $this->endereco,
                'cep' => $this->cep,
                'bairro' => $this->bairro?->nome,
                'municipio' => $this->municipio?->nome,
                'estado' => $this->estado?->uf,
            ],
            'detalhes' => [
                'area_total' => $this->area_total ? (float) $this->area_total : null,
                'area_privativa' => $this->area_privativa ? (float) $this->area_privativa : null,
                'area_terreno' => $this->area_terreno ? (float) $this->area_terreno : null,
                'quartos' => $this->quartos,
                'banheiros' => $this->banheiros,
                'salas' => $this->salas,
                'garagens' => $this->garagens,
                'caracteristicas' => $caracteristicas,
            ],
            'financeiro' => [
                'valor_venda' => $ultimo ? (float) $ultimo->valor_venda : null,
                'valor_avaliacao' => $ultimo ? (float) $ultimo->valor_avaliacao : null,
                'desconto_percentual' => $ultimo && $ultimo->valor_avaliacao > 0
                    ? round((($ultimo->valor_avaliacao - $ultimo->valor_venda) / $ultimo->valor_avaliacao) * 100, 2)
                    : 0,
                'modalidade_venda' => $ultimo?->modalidade?->nome,
                'aceita_fgts' => $this->aceita_fgts === 'sim',
                'aceita_financ_sbpe' => (bool) $this->aceita_financ_sbpe,
                'aceita_financ_mcmv' => (bool) $this->aceita_financ_mcmv,
            ],
            'imagens' => [
                'foto_fachada' => $this->foto_fachada_url ?: asset('images/imovel-placeholder.svg'),
                'foto_destaque' => $this->imagem_destaque_url ?: asset('images/imovel-placeholder.svg'),
            ],
            'link_caixa' => $this->link_edital,
            'link_matricula' => $this->link_matricula,
            'historico_precos' => $historico,
            'dossie_bairro' => $dossieBairro,
            'criado_em' => $this->created_at?->toIso8601String(),
            'atualizado_em' => $this->updated_at?->toIso8601String(),
        ];
    }
}

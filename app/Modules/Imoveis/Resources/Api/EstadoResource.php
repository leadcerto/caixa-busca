<?php

namespace App\Modules\Imoveis\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EstadoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'uf' => $this->uf,
            'total_imoveis' => $this->whenCounted('imoveis'),
        ];
    }
}

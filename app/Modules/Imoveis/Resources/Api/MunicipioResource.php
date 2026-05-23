<?php

namespace App\Modules\Imoveis\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MunicipioResource extends JsonResource
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
            'id_estado' => $this->id_estado,
            'nome' => $this->nome,
            'estado' => new EstadoResource($this->whenLoaded('estado')),
            'total_imoveis' => $this->whenCounted('imoveis'),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Estado;
use App\Models\Municipio;
use App\Models\TipoImovel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImovelFactory extends Factory
{
    public function definition(): array
    {
        $estado    = Estado::factory()->create();
        $municipio = Municipio::factory()->create(['id_estado' => $estado->id]);

        return [
            'numero_original' => fake()->unique()->numerify('##########'),
            'id_tipo_imovel'  => TipoImovel::factory(),
            'id_estado'       => $estado->id,
            'id_municipio'    => $municipio->id,
            'id_bairro'       => null,
            'id_imobiliaria'  => null,
            'endereco'        => fake()->streetAddress(),
            'descricao_original' => 'Apartamento, 65,50 m², 2 quartos, 1 vaga de garagem.',
            'area_total'      => 65.50,
            'status'          => 'ativo',
            'slug'            => fake()->unique()->slug(3) . '-' . fake()->numerify('####'),
        ];
    }

    public function inativo(): static
    {
        return $this->state(['status' => 'inativo']);
    }
}

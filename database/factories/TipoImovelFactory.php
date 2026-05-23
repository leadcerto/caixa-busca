<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TipoImovelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome' => fake()->randomElement(['Apartamento', 'Casa', 'Terreno', 'Sala Comercial', 'Galpão']),
            'ativo' => true,
        ];
    }
}

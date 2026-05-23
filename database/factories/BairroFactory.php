<?php

namespace Database\Factories;

use App\Models\Municipio;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BairroFactory extends Factory
{
    public function definition(): array
    {
        $nome = fake()->streetName();
        return [
            'id_municipio' => Municipio::factory(),
            'nome'         => $nome,
            'slug'         => Str::slug($nome) . '-' . fake()->numerify('##'),
        ];
    }
}

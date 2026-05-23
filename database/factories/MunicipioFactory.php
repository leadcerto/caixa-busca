<?php

namespace Database\Factories;

use App\Models\Estado;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MunicipioFactory extends Factory
{
    public function definition(): array
    {
        $nome = fake()->city();
        return [
            'id_estado' => Estado::factory(),
            'nome'      => $nome,
            'slug'      => Str::slug($nome) . '-' . fake()->numerify('###'),
        ];
    }
}

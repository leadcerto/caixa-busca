<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class ImobiliariaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome'     => fake()->company(),
            'email'    => fake()->unique()->safeEmail(),
            'senha'    => Hash::make('password'),
            'whatsapp' => '11999' . fake()->numerify('######'),
            'creci'    => fake()->numerify('CRECI-####'),
            'ativo'    => true,
        ];
    }
}

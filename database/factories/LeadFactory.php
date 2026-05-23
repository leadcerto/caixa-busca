<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LeadFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'telefone'          => '119' . fake()->numerify('########'),
            'senha'             => Hash::make('password'),
            'email_confirmado'  => false,
            'token_confirmacao' => Str::random(32),
            'imoveis_interesse' => [],
            'ativo'             => true,
        ];
    }
}

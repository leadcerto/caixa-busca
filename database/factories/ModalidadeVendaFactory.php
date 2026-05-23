<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ModalidadeVendaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome' => fake()->randomElement([
                'Venda Direta Online',
                'Licitação Aberta',
                'Licitação Fechada',
                'Concorrência Pública',
            ]),
            'ativo' => true,
        ];
    }
}

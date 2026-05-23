<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EstadoFactory extends Factory
{
    public function definition(): array
    {
        static $ufs = ['SP', 'RJ', 'MG', 'RS', 'PR', 'BA', 'CE', 'PE', 'GO', 'SC'];
        static $used = [];

        do {
            $uf = fake()->randomElement($ufs);
        } while (in_array($uf, $used, true));

        $used[] = $uf;

        $nomes = [
            'SP' => 'São Paulo', 'RJ' => 'Rio de Janeiro', 'MG' => 'Minas Gerais',
            'RS' => 'Rio Grande do Sul', 'PR' => 'Paraná', 'BA' => 'Bahia',
            'CE' => 'Ceará', 'PE' => 'Pernambuco', 'GO' => 'Goiás', 'SC' => 'Santa Catarina',
        ];

        return [
            'uf'   => $uf,
            'nome' => $nomes[$uf] ?? 'Estado Teste',
        ];
    }
}

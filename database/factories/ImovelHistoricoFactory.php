<?php

namespace Database\Factories;

use App\Models\Imovel;
use App\Models\ModalidadeVenda;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImovelHistoricoFactory extends Factory
{
    public function definition(): array
    {
        $valorVenda = fake()->randomFloat(2, 80000, 900000);

        return [
            'id_imovel'           => Imovel::factory(),
            'id_modalidade'       => ModalidadeVenda::factory(),
            'data_referencia'     => now()->toDateString(),
            'valor_avaliacao'     => $valorVenda * 1.2,
            'valor_venda'         => $valorVenda,
            'desconto_percentual' => 16.67,
            'desconto_valor'      => $valorVenda * 0.2,
        ];
    }
}

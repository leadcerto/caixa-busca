<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImovelGrupoSeeder extends Seeder
{
    /**
     * Grupos de classificação por faixa de valor de avaliação.
     * Os percentuais e valores fixos definem as condições de financiamento
     * de cada faixa e devem ser ajustados conforme as regras de negócio.
     *
     * percentual_1 = entrada mínima (% sobre valor de venda)
     * percentual_2 = reservado para cálculos futuros
     * valor_fixo_1 = entrada mínima em reais (prevalece se maior que percentual_1)
     * valor_fixo_2 = reservado para cálculos futuros
     */
    public function run(): void
    {
        DB::table('imoveis_grupos')->insert([
            [
                'nome'         => 'Faixa 1 — Até R$ 90 mil',
                'valor_minimo' => 0.00,
                'valor_maximo' => 90_000.00,
                'percentual_1' => 5.00,
                'percentual_2' => null,
                'valor_fixo_1' => null,
                'valor_fixo_2' => null,
                'ativo'        => true,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'nome'         => 'Faixa 2 — R$ 90 mil a R$ 180 mil',
                'valor_minimo' => 90_000.01,
                'valor_maximo' => 180_000.00,
                'percentual_1' => 10.00,
                'percentual_2' => null,
                'valor_fixo_1' => null,
                'valor_fixo_2' => null,
                'ativo'        => true,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'nome'         => 'Faixa 3 — R$ 180 mil a R$ 300 mil',
                'valor_minimo' => 180_000.01,
                'valor_maximo' => 300_000.00,
                'percentual_1' => 20.00,
                'percentual_2' => null,
                'valor_fixo_1' => null,
                'valor_fixo_2' => null,
                'ativo'        => true,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'nome'         => 'Faixa 4 — R$ 300 mil a R$ 500 mil',
                'valor_minimo' => 300_000.01,
                'valor_maximo' => 500_000.00,
                'percentual_1' => 30.00,
                'percentual_2' => null,
                'valor_fixo_1' => null,
                'valor_fixo_2' => null,
                'ativo'        => true,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'nome'         => 'Faixa 5 — Acima de R$ 500 mil',
                'valor_minimo' => 500_000.01,
                'valor_maximo' => 99_999_999.99,
                'percentual_1' => 40.00,
                'percentual_2' => null,
                'valor_fixo_1' => null,
                'valor_fixo_2' => null,
                'ativo'        => true,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ]);
    }
}

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
        $grupos = [
            [
                'nome'         => 'Faixa 1 — Até R$ 90 mil',
                'valor_minimo' => 0.00,
                'valor_maximo' => 90000.00,
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
                'valor_minimo' => 90000.01,
                'valor_maximo' => 180000.00,
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
                'valor_minimo' => 180000.01,
                'valor_maximo' => 300000.00,
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
                'valor_minimo' => 300000.01,
                'valor_maximo' => 500000.00,
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
                'valor_minimo' => 500000.01,
                'valor_maximo' => 99999999.99,
                'percentual_1' => 40.00,
                'percentual_2' => null,
                'valor_fixo_1' => null,
                'valor_fixo_2' => null,
                'ativo'        => true,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ];

        foreach ($grupos as $grupo) {
            DB::table('imoveis_grupos')->updateOrInsert(
                ['nome' => $grupo['nome']],
                [
                    'valor_minimo' => $grupo['valor_minimo'],
                    'valor_maximo' => $grupo['valor_maximo'],
                    'percentual_1' => $grupo['percentual_1'],
                    'percentual_2' => $grupo['percentual_2'],
                    'valor_fixo_1' => $grupo['valor_fixo_1'],
                    'valor_fixo_2' => $grupo['valor_fixo_2'],
                    'ativo'        => $grupo['ativo'],
                    'created_at'   => $grupo['created_at'],
                    'updated_at'   => $grupo['updated_at'],
                ]
            );
        }
    }
}

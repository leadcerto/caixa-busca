<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoImovelSeeder extends Seeder
{
    /**
     * Seed dos tipos de imóvel previstos no sistema.
     */
    public function run(): void
    {
        $tipos = [
            ['nome' => 'Casa',         'ativo' => true],
            ['nome' => 'Apartamento',  'ativo' => true],
            ['nome' => 'Terreno',      'ativo' => true],
            ['nome' => 'Sobrado',      'ativo' => true],
            ['nome' => 'Prédio',       'ativo' => true],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipos_imovel')->updateOrInsert(
                ['nome' => $tipo['nome']],
                ['ativo' => $tipo['ativo']]
            );
        }
    }
}

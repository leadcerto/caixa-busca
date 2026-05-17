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

        DB::table('tipos_imovel')->insert($tipos);
    }
}

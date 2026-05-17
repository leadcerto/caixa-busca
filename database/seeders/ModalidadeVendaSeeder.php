<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModalidadeVendaSeeder extends Seeder
{
    /**
     * Seed das modalidades de venda aceitas.
     */
    public function run(): void
    {
        $modalidades = [
            ['nome' => 'Venda Direta',        'ativo' => true],
            ['nome' => 'Venda Direta Online',  'ativo' => true],
        ];

        DB::table('modalidades_venda')->insert($modalidades);
    }
}

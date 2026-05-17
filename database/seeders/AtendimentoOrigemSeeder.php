<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AtendimentoOrigemSeeder extends Seeder
{
    /**
     * Seed das 5 origens de atendimento previstas.
     */
    public function run(): void
    {
        $origens = [
            ['nome' => 'Formulário do site',     'ativo' => true],
            ['nome' => 'WhatsApp do anúncio',     'ativo' => true],
            ['nome' => 'WhatsApp do site',        'ativo' => true],
            ['nome' => 'E-mail',                  'ativo' => true],
            ['nome' => 'Blog',                    'ativo' => true],
        ];

        DB::table('atendimentos_origem')->insert($origens);
    }
}

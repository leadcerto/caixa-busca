<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImovelEtapaSeeder extends Seeder
{
    /**
     * Seed das 7 etapas de processamento de imóveis.
     */
    public function run(): void
    {
        $etapas = [
            ['nome' => 'Importação',                  'descricao' => 'Dados brutos importados do CSV',                    'ordem' => 1, 'ativo' => true],
            ['nome' => 'Processamento',               'descricao' => 'Normalização e validação dos dados',                'ordem' => 2, 'ativo' => true],
            ['nome' => 'Geração de links',             'descricao' => 'Criação de links de edital e referência',           'ordem' => 3, 'ativo' => true],
            ['nome' => 'Desmembramento da descrição',  'descricao' => 'Extração de quartos, área, etc. via PHP',           'ordem' => 4, 'ativo' => true],
            ['nome' => 'Scraping',                     'descricao' => 'Coleta de dados complementares externos',           'ordem' => 5, 'ativo' => true],
            ['nome' => 'Geração de SEO',               'descricao' => 'Criação de slug, meta_title, meta_description',     'ordem' => 6, 'ativo' => true],
            ['nome' => 'Cálculos financeiros',         'descricao' => 'Enquadramento em grupos e cálculos de percentual', 'ordem' => 7, 'ativo' => true],
        ];

        DB::table('imoveis_etapas')->insert($etapas);
    }
}

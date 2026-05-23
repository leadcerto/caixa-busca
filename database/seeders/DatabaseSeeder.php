<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Popula todas as tabelas de referência com dados iniciais
     * obrigatórios para o funcionamento do sistema.
     */
    public function run(): void
    {
        $this->call([
            EstadoSeeder::class,
            TipoImovelSeeder::class,
            ModalidadeVendaSeeder::class,
            ImovelEtapaSeeder::class,
            AtendimentoOrigemSeeder::class,
            ImovelGrupoSeeder::class,
        ]);

        // Criar administrador padrão para testes
        \App\Models\User::firstOrCreate(
            ['email' => 'icaixa.001@gmail.com'],
            [
                'name' => 'Administrador',
                'password' => \Illuminate\Support\Facades\Hash::make('lcps@1974VI'),
            ]
        );

        // Criar imobiliária parceira padrão para o Rio de Janeiro (RJ)
        $imobiliaria = \App\Models\Imobiliaria::firstOrCreate(
            ['email' => 'imobiliaria@teste.com'],
            [
                'nome' => 'Imobiliária Teste RJ',
                'senha' => \Illuminate\Support\Facades\Hash::make('senha123'),
                'whatsapp' => '5521997882950',
                'creci' => 'CRECI-12345-J',
                'ativo' => true,
            ]
        );

        // Associa a imobiliária ao estado do Rio de Janeiro (RJ)
        $rj = \App\Models\Estado::where('uf', 'RJ')->first();
        if ($rj) {
            \Illuminate\Support\Facades\DB::table('imobiliarias_estados')->updateOrInsert(
                ['id_estado' => $rj->id],
                ['id_imobiliaria' => $imobiliaria->id]
            );
        }

        // Seed mock/test cities, neighborhoods and properties to let the user test instantly
        $sp = \App\Models\Estado::where('uf', 'SP')->first();

        if ($rj && $sp) {
            // Create some test municipios (cities)
            $cidadeRj = \App\Models\Municipio::firstOrCreate(
                ['id_estado' => $rj->id, 'nome' => 'Rio de Janeiro']
            );
            $cidadeNiteroi = \App\Models\Municipio::firstOrCreate(
                ['id_estado' => $rj->id, 'nome' => 'Niterói']
            );
            $cidadeSp = \App\Models\Municipio::firstOrCreate(
                ['id_estado' => $sp->id, 'nome' => 'São Paulo']
            );
            $cidadeCampinas = \App\Models\Municipio::firstOrCreate(
                ['id_estado' => $sp->id, 'nome' => 'Campinas']
            );

            // Create some test bairros (neighborhoods)
            $bairroCopacabana = \App\Models\Bairro::firstOrCreate(
                ['id_municipio' => $cidadeRj->id, 'nome' => 'Copacabana']
            );
            $bairroIpanema = \App\Models\Bairro::firstOrCreate(
                ['id_municipio' => $cidadeRj->id, 'nome' => 'Ipanema']
            );
            $bairroIcarai = \App\Models\Bairro::firstOrCreate(
                ['id_municipio' => $cidadeNiteroi->id, 'nome' => 'Icaraí']
            );
            $bairroPinheiros = \App\Models\Bairro::firstOrCreate(
                ['id_municipio' => $cidadeSp->id, 'nome' => 'Pinheiros']
            );
            $bairroJardins = \App\Models\Bairro::firstOrCreate(
                ['id_municipio' => $cidadeSp->id, 'nome' => 'Jardins']
            );
            $bairroCambui = \App\Models\Bairro::firstOrCreate(
                ['id_municipio' => $cidadeCampinas->id, 'nome' => 'Cambuí']
            );

            // Create some test active properties
            $tipoCasa = \App\Models\TipoImovel::where('nome', 'Casa')->first()?->id ?? 1;
            $tipoApto = \App\Models\TipoImovel::where('nome', 'Apartamento')->first()?->id ?? 2;

            $etapaImportacao = \App\Models\ImovelEtapa::where('ordem', 1)->first()?->id ?? 1;
            $grupoFaixa3 = \App\Models\ImovelGrupo::where('nome', 'like', '%Faixa 3%')->first()?->id ?? 3;
            $grupoFaixa4 = \App\Models\ImovelGrupo::where('nome', 'like', '%Faixa 4%')->first()?->id ?? 4;

            // Imovel 1: RJ - Copacabana
            $imovel1 = \App\Models\Imovel::firstOrCreate(
                ['numero_original' => '8555510834062'],
                [
                    'id_imobiliaria' => $imobiliaria->id,
                    'id_tipo_imovel' => $tipoApto,
                    'id_estado' => $rj->id,
                    'id_municipio' => $cidadeRj->id,
                    'id_bairro' => $bairroCopacabana->id,
                    'id_etapa' => $etapaImportacao,
                    'id_grupo' => $grupoFaixa3,
                    'endereco' => 'Avenida Atlântica, 1702 Apt 501',
                    'cep' => '22021-001',
                    'descricao_original' => 'Apartamento incrível de 2 quartos com vista para o mar em Copacabana.',
                    'area_total' => 90.00,
                    'area_privativa' => 85.00,
                    'quartos' => 2,
                    'banheiros' => 2,
                    'salas' => 1,
                    'garagens' => 1,
                    'aceita_fgts' => 'sim',
                    'status' => 'ativo',
                    'slug' => 'apartamento-vista-mar-copacabana-rj',
                    'meta_title' => 'Apartamento em Copacabana, Rio de Janeiro',
                    'meta_description' => 'Oportunidade incrível de apartamento em Copacabana, RJ.',
                ]
            );

            // Imovel 1 History
            \App\Models\ImovelHistorico::firstOrCreate(
                ['id_imovel' => $imovel1->id, 'data_referencia' => now()->toDateString()],
                [
                    'id_modalidade' => 1,
                    'valor_avaliacao' => 350000.00,
                    'valor_venda' => 245000.00,
                    'desconto_percentual' => 30.00,
                    'desconto_valor' => 105000.00,
                ]
            );

            // Imovel 2: RJ - Niterói Icaraí
            $imovel2 = \App\Models\Imovel::firstOrCreate(
                ['numero_original' => '8555510834070'],
                [
                    'id_imobiliaria' => $imobiliaria->id,
                    'id_tipo_imovel' => $tipoCasa,
                    'id_estado' => $rj->id,
                    'id_municipio' => $cidadeNiteroi->id,
                    'id_bairro' => $bairroIcarai->id,
                    'id_etapa' => $etapaImportacao,
                    'id_grupo' => $grupoFaixa4,
                    'endereco' => 'Rua Moreira César, 450',
                    'cep' => '24230-062',
                    'descricao_original' => 'Casa moderna de 3 quartos no coração de Icaraí.',
                    'area_total' => 180.00,
                    'area_privativa' => 150.00,
                    'quartos' => 3,
                    'banheiros' => 3,
                    'salas' => 2,
                    'garagens' => 2,
                    'aceita_fgts' => 'sim',
                    'status' => 'ativo',
                    'slug' => 'casa-moderna-icarai-niteroi-rj',
                    'meta_title' => 'Casa em Icaraí, Niterói, RJ',
                    'meta_description' => 'Casa espetacular em Icaraí, Niterói.',
                ]
            );

            // Imovel 2 History
            \App\Models\ImovelHistorico::firstOrCreate(
                ['id_imovel' => $imovel2->id, 'data_referencia' => now()->toDateString()],
                [
                    'id_modalidade' => 1,
                    'valor_avaliacao' => 600000.00,
                    'valor_venda' => 390000.00,
                    'desconto_percentual' => 35.00,
                    'desconto_valor' => 210000.00,
                ]
            );

            // Imovel 3: SP - Pinheiros
            $imovel3 = \App\Models\Imovel::firstOrCreate(
                ['numero_original' => '8555510834080'],
                [
                    'id_imobiliaria' => $imobiliaria->id,
                    'id_tipo_imovel' => $tipoApto,
                    'id_estado' => $sp->id,
                    'id_municipio' => $cidadeSp->id,
                    'id_bairro' => $bairroPinheiros->id,
                    'id_etapa' => $etapaImportacao,
                    'id_grupo' => $grupoFaixa4,
                    'endereco' => 'Rua dos Pinheiros, 1200 Apt 82',
                    'cep' => '05422-002',
                    'descricao_original' => 'Apartamento compacto com 1 quarto em Pinheiros, excelente localização.',
                    'area_total' => 50.00,
                    'area_privativa' => 45.00,
                    'quartos' => 1,
                    'banheiros' => 1,
                    'salas' => 1,
                    'garagens' => 1,
                    'aceita_fgts' => 'sim',
                    'status' => 'ativo',
                    'slug' => 'apartamento-pinheiros-sao-paulo-sp',
                    'meta_title' => 'Apartamento em Pinheiros, São Paulo, SP',
                    'meta_description' => 'Apartamento em Pinheiros.',
                ]
            );

            // Imovel 3 History
            \App\Models\ImovelHistorico::firstOrCreate(
                ['id_imovel' => $imovel3->id, 'data_referencia' => now()->toDateString()],
                [
                    'id_modalidade' => 1,
                    'valor_avaliacao' => 420000.00,
                    'valor_venda' => 294000.00,
                    'desconto_percentual' => 30.00,
                    'desconto_valor' => 126000.00,
                ]
            );
        }
    }
}

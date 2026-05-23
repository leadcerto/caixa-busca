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
    }
}

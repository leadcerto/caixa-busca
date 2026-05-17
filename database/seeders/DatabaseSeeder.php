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
        ]);
    }
}

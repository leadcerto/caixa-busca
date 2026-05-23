<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Obter IDs das modalidades desautorizadas
        $disallowedIds = DB::table('modalidades_venda')
            ->whereNotIn('nome', ['Venda Online', 'Venda Direta Online'])
            ->pluck('id');

        if ($disallowedIds->isEmpty()) {
            return;
        }

        // 2. Deletar imóveis cuja última modalidade registrada no histórico seja uma das desautorizadas
        DB::table('imoveis')
            ->whereExists(function ($query) use ($disallowedIds) {
                $query->select(DB::raw(1))
                    ->from('imoveis_historico as h1')
                    ->whereColumn('h1.id_imovel', 'imoveis.id')
                    ->whereIn('h1.id_modalidade', $disallowedIds)
                    ->whereRaw('h1.id = (
                        select h2.id from imoveis_historico as h2 
                        where h2.id_imovel = h1.id_imovel 
                        order by h2.created_at desc, h2.id desc 
                        limit 1
                    )');
            })
            ->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

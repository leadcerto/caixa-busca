<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // slug usado no route model binding: WHERE slug = ?
        Schema::table('imoveis', function (Blueprint $table) {
            $table->index('slug', 'imoveis_slug_index');
        });

        // filtros por data no dashboard e nos painéis
        Schema::table('atendimentos', function (Blueprint $table) {
            $table->index('created_at', 'atendimentos_created_at_index');
        });

        // latestOfMany('created_at') usado em ultimoHistorico()
        Schema::table('imoveis_historico', function (Blueprint $table) {
            $table->index('created_at', 'imoveis_historico_created_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('imoveis', fn($t) => $t->dropIndex('imoveis_slug_index'));
        Schema::table('atendimentos', fn($t) => $t->dropIndex('atendimentos_created_at_index'));
        Schema::table('imoveis_historico', fn($t) => $t->dropIndex('imoveis_historico_created_at_index'));
    }
};

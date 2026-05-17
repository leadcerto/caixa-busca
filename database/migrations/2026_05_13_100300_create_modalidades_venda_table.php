<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabela: modalidades_venda
     * Modalidades de venda aceitas para importação no sistema.
     */
    public function up(): void
    {
        Schema::create('modalidades_venda', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->boolean('ativo')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modalidades_venda');
    }
};

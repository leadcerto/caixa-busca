<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabela: imoveis_etapas
     * Etapas de processamento pelas quais cada imóvel passa após a importação.
     */
    public function up(): void
    {
        Schema::create('imoveis_etapas', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->text('descricao')->nullable();
            $table->tinyInteger('ordem');
            $table->boolean('ativo')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imoveis_etapas');
    }
};

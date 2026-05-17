<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabela: imoveis_grupos
     * Grupos de classificação por faixa de valor de avaliação,
     * com parâmetros para cálculos financeiros.
     */
    public function up(): void
    {
        Schema::create('imoveis_grupos', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->decimal('valor_minimo', 15, 2);
            $table->decimal('valor_maximo', 15, 2);
            $table->decimal('percentual_1', 5, 2)->nullable();
            $table->decimal('percentual_2', 5, 2)->nullable();
            $table->decimal('valor_fixo_1', 15, 2)->nullable();
            $table->decimal('valor_fixo_2', 15, 2)->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imoveis_grupos');
    }
};

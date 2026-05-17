<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabela: municipios
     * Municípios vinculados aos estados, com suporte a enriquecimento por IA.
     */
    public function up(): void
    {
        Schema::create('municipios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_estado')->constrained('estados')->cascadeOnDelete();
            $table->string('nome', 150);
            $table->json('conteudo_ia')->nullable();
            $table->enum('ia_status', ['pendente', 'gerado', 'erro'])->default('pendente');
            $table->dateTime('ia_gerado_em')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipios');
    }
};

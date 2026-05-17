<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabela: bairros
     * Bairros vinculados aos municípios, com suporte a enriquecimento por IA.
     */
    public function up(): void
    {
        Schema::create('bairros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_municipio')->constrained('municipios')->cascadeOnDelete();
            $table->string('nome', 150);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->json('conteudo_ia')->nullable();
            $table->enum('ia_status', ['pendente', 'gerado', 'erro'])->default('pendente');
            $table->dateTime('ia_gerado_em')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bairros');
    }
};

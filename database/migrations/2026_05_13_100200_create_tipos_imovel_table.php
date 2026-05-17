<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabela: tipos_imovel
     * Tipos de imóvel extraídos da descrição original via PHP.
     */
    public function up(): void
    {
        Schema::create('tipos_imovel', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 80);
            $table->boolean('ativo')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_imovel');
    }
};

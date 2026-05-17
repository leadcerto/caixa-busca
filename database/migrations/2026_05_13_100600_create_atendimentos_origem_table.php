<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabela: atendimentos_origem
     * Origens possíveis de um atendimento gerado por lead.
     */
    public function up(): void
    {
        Schema::create('atendimentos_origem', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->boolean('ativo')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('atendimentos_origem');
    }
};

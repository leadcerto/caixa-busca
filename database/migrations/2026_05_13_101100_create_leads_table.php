<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabela: leads
     * Compradores/visitantes que se cadastraram na plataforma.
     */
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->string('email', 150)->unique();
            $table->string('telefone', 20)->nullable();
            $table->string('senha', 255);
            $table->boolean('email_confirmado')->default(false);
            $table->string('token_confirmacao', 255)->nullable();
            $table->json('imoveis_interesse')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};

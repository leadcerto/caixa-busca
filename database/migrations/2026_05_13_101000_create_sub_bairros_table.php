<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabela: sub_bairros
     * Sub-bairros vinculados aos bairros.
     */
    public function up(): void
    {
        Schema::create('sub_bairros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_bairro')->constrained('bairros')->cascadeOnDelete();
            $table->string('nome', 150);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_bairros');
    }
};

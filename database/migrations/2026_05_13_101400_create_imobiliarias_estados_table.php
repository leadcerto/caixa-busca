<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabela: imobiliarias_estados
     * Define qual imobiliária é responsável por cada estado. Uma imobiliária por estado.
     */
    public function up(): void
    {
        Schema::create('imobiliarias_estados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_imobiliaria')->constrained('imobiliarias')->cascadeOnDelete();
            $table->foreignId('id_estado')->constrained('estados');
            $table->timestamp('created_at')->useCurrent();

            // Garante unicidade: um estado só pode ter uma imobiliária
            $table->unique('id_estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imobiliarias_estados');
    }
};

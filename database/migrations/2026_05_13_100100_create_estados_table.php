<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabela: estados
     * Estados brasileiros (UF).
     */
    public function up(): void
    {
        Schema::create('estados', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 50);
            $table->char('uf', 2)->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estados');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vitrines', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 150);
            $table->string('slug', 180)->unique();
            $table->json('filtros');               // {estado, cidade, bairros_ids, financiamento, ordenar, ...}
            $table->text('url_original')->nullable(); // URL da busca que originou a vitrine
            $table->boolean('ativa')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vitrines');
    }
};

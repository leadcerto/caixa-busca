<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('imobiliarias', function (Blueprint $table) {
            $table->string('logo_url', 500)->nullable()->after('imagem_botao');
            $table->string('horario_atendimento', 255)->nullable()->default('Segunda a Sexta-feira: 10:00 às 16:00')->after('logo_url');
        });
    }

    public function down(): void
    {
        Schema::table('imobiliarias', function (Blueprint $table) {
            $table->dropColumn(['logo_url', 'horario_atendimento']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('atendimentos', function (Blueprint $table) {
            $table->enum('status_parceiro', [
                'pendente',
                'contatado',
                'negociando',
                'sem_interesse',
                'fechado',
            ])->default('pendente')->after('whatsapp_enviado');

            $table->text('anotacao')->nullable()->after('status_parceiro');
        });
    }

    public function down(): void
    {
        Schema::table('atendimentos', function (Blueprint $table) {
            $table->dropColumn(['status_parceiro', 'anotacao']);
        });
    }
};

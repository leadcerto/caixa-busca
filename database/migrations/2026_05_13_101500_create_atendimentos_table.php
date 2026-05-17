<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabela: atendimentos
     * Registro de cada atendimento gerado por um lead interessado em um imóvel.
     * A imobiliária responsável pelo estado do imóvel é notificada
     * automaticamente por e-mail e WhatsApp.
     */
    public function up(): void
    {
        Schema::create('atendimentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_lead')->constrained('leads');
            $table->foreignId('id_imovel')->constrained('imoveis');
            $table->foreignId('id_imobiliaria')->nullable()->constrained('imobiliarias')->nullOnDelete();
            $table->foreignId('id_origem')->nullable()->constrained('atendimentos_origem')->nullOnDelete();
            $table->text('mensagem')->nullable();
            $table->boolean('email_enviado')->default(false);
            $table->boolean('whatsapp_enviado')->default(false);
            $table->timestamp('created_at')->useCurrent();

            // Índices para relatórios
            $table->index('id_imobiliaria');
            $table->index('id_lead');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('atendimentos');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabela: imoveis_historico
     * Registra os dados variáveis de cada imóvel a cada nova importação do CSV.
     */
    public function up(): void
    {
        Schema::create('imoveis_historico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_imovel')->constrained('imoveis')->cascadeOnDelete();
            $table->foreignId('id_modalidade')->constrained('modalidades_venda');
            $table->date('data_referencia')->comment('Data de geração do CSV');
            $table->decimal('valor_avaliacao', 15, 2);
            $table->decimal('valor_venda', 15, 2);
            $table->decimal('desconto_percentual', 5, 2);
            $table->decimal('desconto_valor', 15, 2)->comment('Calculado: valor_avaliacao - valor_venda');
            $table->boolean('aceita_financ_sbpe')->nullable();
            $table->boolean('aceita_financ_mcmv')->nullable();
            $table->timestamp('created_at')->useCurrent();

            // Índice para consulta de evolução de preço
            $table->index(['id_imovel', 'data_referencia']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imoveis_historico');
    }
};

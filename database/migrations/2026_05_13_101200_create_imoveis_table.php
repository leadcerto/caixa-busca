<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabela: imoveis
     * Imóveis importados via CSV. Campos originais preservados integralmente.
     */
    public function up(): void
    {
        Schema::create('imoveis', function (Blueprint $table) {
            $table->id();
            $table->string('numero_original', 50)->unique()->comment('Número original do imóvel no CSV');

            // Foreign Keys (nullable: podem ser preenchidas em etapas posteriores)
            $table->foreignId('id_imobiliaria')->nullable()->constrained('imobiliarias')->nullOnDelete();
            $table->foreignId('id_tipo_imovel')->nullable()->constrained('tipos_imovel')->nullOnDelete();
            $table->foreignId('id_estado')->nullable()->constrained('estados')->nullOnDelete();
            $table->foreignId('id_municipio')->nullable()->constrained('municipios')->nullOnDelete();
            $table->foreignId('id_bairro')->nullable()->constrained('bairros')->nullOnDelete();
            $table->foreignId('id_sub_bairro')->nullable()->constrained('sub_bairros')->nullOnDelete();
            $table->foreignId('id_grupo')->nullable()->constrained('imoveis_grupos')->nullOnDelete();
            $table->foreignId('id_etapa')->nullable()->constrained('imoveis_etapas')->nullOnDelete();

            // Dados fixos do CSV
            $table->string('endereco', 255);
            $table->string('cep', 10)->nullable();
            $table->text('descricao_original');

            // Dados extraídos da descrição via PHP
            $table->decimal('area_total', 10, 2)->nullable();
            $table->decimal('area_privativa', 10, 2)->nullable();
            $table->decimal('area_terreno', 10, 2)->nullable();
            $table->tinyInteger('quartos')->nullable();
            $table->tinyInteger('banheiros')->nullable();
            $table->tinyInteger('salas')->nullable();
            $table->tinyInteger('garagens')->nullable();
            $table->boolean('varanda')->nullable();
            $table->boolean('area_servico')->nullable();
            $table->boolean('cozinha')->nullable();
            $table->boolean('piscina')->nullable();
            $table->boolean('churrasqueira')->nullable();
            $table->boolean('terraco')->nullable();

            // Mídias e links
            $table->string('foto_fachada_url', 500)->nullable();
            $table->string('imagem_destaque_url', 500)->nullable();
            $table->string('link_edital', 500)->nullable();

            // Financiamento
            $table->enum('aceita_fgts', ['nao_informado', 'sim', 'nao'])->default('nao_informado');
            $table->boolean('aceita_financ_sbpe')->nullable();
            $table->boolean('aceita_financ_mcmv')->nullable();

            // Status e SEO
            $table->enum('status', ['ativo', 'fora_de_venda', 'vendido', 'suspenso'])->default('ativo');
            $table->string('slug', 255)->nullable()->unique();
            $table->string('meta_title', 160)->nullable();
            $table->string('meta_description', 320)->nullable();

            $table->timestamps();

            // Índices para performance de busca
            $table->index('id_estado');
            $table->index('id_municipio');
            $table->index('id_bairro');
            $table->index('id_tipo_imovel');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imoveis');
    }
};

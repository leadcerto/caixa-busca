<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_page_views', function (Blueprint $table) {
            $table->id();

            // Qual bairro foi acessado (nullable para não quebrar caso bairro não exista)
            $table->foreignId('bairro_id')->nullable()->constrained('bairros')->nullOnDelete();

            // Identificadores anônimos
            $table->string('session_id', 64)->nullable()->index();
            $table->string('ip_hash', 64)->nullable();

            // Parâmetros UTM da campanha
            $table->string('utm_source', 100)->nullable()->index();
            $table->string('utm_medium', 100)->nullable();
            $table->string('utm_campaign', 200)->nullable()->index();
            $table->string('utm_content', 200)->nullable();
            $table->string('utm_term', 200)->nullable();

            // Origem e dispositivo
            $table->string('referrer', 500)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device_type', 20)->nullable(); // mobile | tablet | desktop

            // Timestamp de acesso (append-only, sem updated_at)
            $table->timestamp('created_at')->useCurrent()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_page_views');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_configuracoes', function (Blueprint $table) {
            $table->id();
            $table->string('webhook_url')->nullable();
            $table->string('webhook_token')->nullable();
            $table->boolean('ativo')->default(false);
            $table->timestamp('ultimo_envio_em')->nullable();
            $table->string('ultimo_status', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_configuracoes');
    }
};

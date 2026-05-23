<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['sucesso', 'falha', 'erro'])->default('erro');
            $table->unsignedSmallInteger('status_code')->nullable();
            $table->json('payload')->nullable();
            $table->text('resposta')->nullable();
            $table->boolean('is_teste')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_webhook_logs');
    }
};

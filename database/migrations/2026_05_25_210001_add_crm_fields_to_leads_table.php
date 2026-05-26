<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // UTM de origem (13.1)
            $table->string('utm_source',   100)->nullable()->after('ativo');
            $table->string('utm_medium',   100)->nullable()->after('utm_source');
            $table->string('utm_campaign', 150)->nullable()->after('utm_medium');

            // Pipeline de CRM (13.2)
            $table->enum('status', ['novo', 'em_atendimento', 'proposta', 'perda'])
                  ->default('novo')
                  ->after('utm_campaign');

            // Responsável interno (13.3)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['utm_source', 'utm_medium', 'utm_campaign', 'status', 'user_id']);
        });
    }
};

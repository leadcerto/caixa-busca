<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('imoveis', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('atendimentos', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('imoveis',     fn($t) => $t->dropSoftDeletes());
        Schema::table('leads',       fn($t) => $t->dropSoftDeletes());
        Schema::table('atendimentos', fn($t) => $t->dropSoftDeletes());
    }
};

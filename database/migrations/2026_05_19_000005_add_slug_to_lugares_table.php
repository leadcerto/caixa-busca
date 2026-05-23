<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('municipios', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('nome');
        });

        Schema::table('bairros', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('nome');
            $table->index(['id_municipio', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::table('municipios', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('bairros', function (Blueprint $table) {
            $table->dropIndex(['id_municipio', 'slug']);
            $table->dropColumn('slug');
        });
    }
};

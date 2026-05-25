<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('imoveis', function (Blueprint $table) {
            $table->unsignedBigInteger('visitas')->default(0)->after('slug');
            $table->unsignedBigInteger('whatsapp_clicks')->default(0)->after('visitas');
        });
    }

    public function down(): void
    {
        Schema::table('imoveis', function (Blueprint $table) {
            $table->dropColumn(['visitas', 'whatsapp_clicks']);
        });
    }
};

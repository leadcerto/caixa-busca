<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Registros importados antes da coluna aceita_financ_sbpe existir ficaram com NULL.
        // Historicamente, a coluna "Financiamento" do CSV da Caixa mapeava para aceita_fgts.
        // Backfill: se aceita_fgts = 'sim' e aceita_financ_sbpe é NULL → true; senão → false.
        DB::statement("
            UPDATE imoveis
            SET aceita_financ_sbpe = CASE WHEN aceita_fgts = 'sim' THEN 1 ELSE 0 END
            WHERE aceita_financ_sbpe IS NULL
        ");
    }

    public function down(): void
    {
        // Sem reversão: não sabemos quais eram NULL originalmente.
    }
};

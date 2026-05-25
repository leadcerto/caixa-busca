<?php

use App\Modules\ImportacaoCSV\Services\CaixaCsvParserService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:test-import', function (CaixaCsvParserService $service) {
    $this->info('Iniciando importacao do CSV...');
    $path = base_path('referencias/MySQL_Imoveis/Lista_imoveis_RJ.csv');
    if (!file_exists($path)) {
        $this->error('Arquivo nao encontrado: ' . $path);
        return 1;
    }

    $start = microtime(true);
    $service->process($path);
    $duration = round(microtime(true) - $start, 2);

    $this->info("Importacao concluida em {$duration} segundos!");
    $this->info("Total de imoveis cadastrados: " . \App\Models\Imovel::count());
    $this->info("Total de bairros: " . \App\Models\Bairro::count());
    $this->info("Total de municipios: " . \App\Models\Municipio::count());
})->purpose('Testa o processamento do arquivo CSV da Caixa');

// -------------------------------------------------------------------------
// Agendamentos
// -------------------------------------------------------------------------

// Heartbeat: grava timestamp a cada minuto — visível no /admin/diagnostico
Schedule::call(function () {
    Cache::put('schedule_last_run', now()->toDateTimeString(), 300);
})->everyMinute()->name('heartbeat')->withoutOverlapping();

// Recompila views e configurações diariamente (9.3 — Performance)
Schedule::command('optimize')->daily();

// Limpa caches de dropdowns expirados (estados, tipos) — redundante mas garantido
Schedule::call(function () {
    Cache::forget('dropdown_estados');
    Cache::forget('dropdown_tipos_imovel');
})->daily();

// Para rotação automática de logs (PII), configure LOG_CHANNEL=daily no .env
// O canal "daily" mantém logs por 14 dias e descarta os mais antigos automaticamente.

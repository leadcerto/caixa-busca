<?php

namespace App\Modules\ImportacaoCSV\Jobs;

use App\Modules\ImportacaoCSV\Services\CaixaCsvParserService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Job para processamento assncrono do arquivo CSV da Caixa.
 * Evita timeouts em execues web.
 */
class ProcessCaixaCsvJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Tempo mximo de execuo (10 minutos).
     */
    public $timeout = 600;

    /**
     * Caminho do arquivo a ser processado.
     */
    protected string $filePath;

    /**
     * Create a new job instance.
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(CaixaCsvParserService $service): void
    {
        Log::info("CaixaCsvJob: Iniciando processamento do arquivo: {$this->filePath}");

        try {
            $service->process($this->filePath);
            Log::info("CaixaCsvJob: Processamento finalizado com sucesso.");
        } catch (\Exception $e) {
            Log::error("CaixaCsvJob: Falha crtica: " . $e->getMessage());
            throw $e;
        }
    }
}

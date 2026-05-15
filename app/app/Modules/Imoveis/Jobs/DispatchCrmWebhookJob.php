<?php

namespace App\Modules\Imoveis\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Job para integrao resiliente com o CRM externo.
 * Regra 10 e 13 dos Requisitos: Integrao via Webhooks e Segurana.
 */
class DispatchCrmWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Nmero de tentativas (Retry Policy) para lidar com a "Matriz do Caos".
     */
    public $tries = 5;

    /**
     * Intervalo entre tentativas em segundos (Backoff exponencial simblico).
     */
    public $backoff = [30, 120, 600];

    /**
     * Dados do lead e do imvel para envio.
     */
    protected $payload;

    /**
     * Inicializa o Job com os dados da converso.
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * Executa a chamada POST para o CRM.
     */
    public function handle(): void
    {
        // L as credenciais do .env (Segurana - Regra 11)
        $url = env('CRM_WEBHOOK_URL');
        $token = env('CRM_WEBHOOK_TOKEN');

        if (empty($url)) {
            Log::error("WEBHOOK_CRM: URL de destino no configurada no .env.");
            return;
        }

        // Executa o disparo com Header de Segurana (Regra 61)
        $response = Http::withHeaders([
            'X-Webhook-Token' => $token,
            'Content-Type' => 'application/json'
        ])->post($url, $this->payload);

        // Se o CRM retornar 5xx ou 429, lanamos exceo para ativar o Retry do Laravel
        if ($response->serverError() || $response->status() === 429) {
            throw new \Exception("CRM Offline ou Ocupado: Status " . $response->status());
        }

        // Log de Sucesso ou Erro Crítico (Regra 54)
        if ($response->successful()) {
            Log::info("WEBHOOK_CRM: Lead disparado com sucesso.", ['id' => $this->payload['imovel_id'] ?? 'desconhecido']);
        } else {
            Log::error("WEBHOOK_CRM: Falha na integrao (Erro Client). Status: " . $response->status());
        }
    }
}

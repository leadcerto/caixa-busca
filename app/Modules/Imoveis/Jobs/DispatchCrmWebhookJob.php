<?php

namespace App\Modules\Imoveis\Jobs;

use App\Models\CrmConfiguracao;
use App\Models\CrmWebhookLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DispatchCrmWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries   = 5;
    public $backoff = [30, 120, 600];

    protected array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function handle(): void
    {
        [$url, $token] = $this->resolveCredentials();

        if (empty($url)) {
            Log::error('WEBHOOK_CRM: URL de destino não configurada (banco ou .env).');
            return;
        }

        try {
            $response = Http::withHeaders([
                'X-Webhook-Token' => $token,
                'Content-Type'    => 'application/json',
            ])->post($url, $this->payload);

            $statusCode = $response->status();
            $sucesso    = $response->successful();

            if ($response->serverError() || $statusCode === 429) {
                $this->registrarLog('falha', $statusCode, $response->body());
                throw new \Exception("CRM offline ou ocupado: status {$statusCode}");
            }

            $logStatus = $sucesso ? 'sucesso' : 'falha';
            $this->registrarLog($logStatus, $statusCode, $response->body());

            if ($sucesso) {
                Log::info('WEBHOOK_CRM: Lead disparado.', ['imovel_id' => $this->payload['imovel_id'] ?? null]);
            } else {
                Log::error("WEBHOOK_CRM: Falha client. Status: {$statusCode}");
            }
        } catch (\Throwable $e) {
            $this->registrarLog('erro', null, $e->getMessage());
            throw $e;
        }
    }

    private function resolveCredentials(): array
    {
        $config = CrmConfiguracao::atual();

        if ($config && $config->ativo && $config->webhook_url) {
            return [$config->webhook_url, $config->webhook_token];
        }

        return [env('CRM_WEBHOOK_URL'), env('CRM_WEBHOOK_TOKEN')];
    }

    private function registrarLog(string $status, ?int $statusCode, ?string $resposta): void
    {
        try {
            $log = CrmWebhookLog::create([
                'status'      => $status,
                'status_code' => $statusCode,
                'payload'     => $this->payload,
                'resposta'    => mb_substr((string) $resposta, 0, 2000),
                'is_teste'    => false,
            ]);

            CrmConfiguracao::query()->update([
                'ultimo_envio_em' => now(),
                'ultimo_status'   => $status,
            ]);
        } catch (\Throwable $e) {
            Log::warning('WEBHOOK_CRM: Falha ao registrar log.', ['erro' => $e->getMessage()]);
        }
    }
}

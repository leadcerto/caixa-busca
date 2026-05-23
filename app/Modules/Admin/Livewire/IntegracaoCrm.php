<?php

namespace App\Modules\Admin\Livewire;

use App\Models\CrmConfiguracao;
use App\Models\CrmWebhookLog;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class IntegracaoCrm extends Component
{
    public string $webhookUrl   = '';
    public string $webhookToken = '';
    public bool   $ativo        = false;

    public ?string $mensagemSucesso = null;
    public ?string $mensagemErro    = null;
    public ?string $resultadoTeste  = null;
    public ?string $corResultado    = null;

    public function mount(): void
    {
        $config = CrmConfiguracao::atual();
        if ($config) {
            $this->webhookUrl   = $config->webhook_url   ?? '';
            $this->webhookToken = $config->webhook_token ?? '';
            $this->ativo        = $config->ativo;
        }
    }

    public function salvar(): void
    {
        $this->validate([
            'webhookUrl' => 'nullable|url|max:500',
            'webhookToken' => 'nullable|string|max:255',
        ], [
            'webhookUrl.url' => 'A URL do webhook deve ser uma URL válida.',
        ]);

        CrmConfiguracao::updateOrCreate(
            ['id' => 1],
            [
                'webhook_url'   => $this->webhookUrl   ?: null,
                'webhook_token' => $this->webhookToken ?: null,
                'ativo'         => $this->ativo,
            ]
        );

        $this->mensagemSucesso = 'Configuração salva com sucesso.';
        $this->mensagemErro    = null;
    }

    public function testarConexao(): void
    {
        $this->mensagemSucesso = null;
        $this->mensagemErro    = null;
        $this->resultadoTeste  = null;

        $url   = $this->webhookUrl   ?: env('CRM_WEBHOOK_URL');
        $token = $this->webhookToken ?: env('CRM_WEBHOOK_TOKEN');

        if (empty($url)) {
            $this->resultadoTeste = 'Nenhuma URL configurada para testar.';
            $this->corResultado   = 'red';
            return;
        }

        $payload = [
            'imovel_id'     => 0,
            'tipo_imovel'   => 'Teste de Conexão',
            'valor'         => 0,
            'localidade'    => 'Teste — Antigravity',
            'lead'          => ['nome' => 'Teste', 'email' => 'teste@antigravity.com', 'telefone' => ''],
            'conversao_url' => url('/'),
            'timestamp'     => now()->toIso8601String(),
            'marketing'     => [],
            '_teste'        => true,
        ];

        try {
            $response   = Http::timeout(10)->withHeaders([
                'X-Webhook-Token' => $token,
                'Content-Type'    => 'application/json',
            ])->post($url, $payload);

            $statusCode = $response->status();
            $status     = $response->successful() ? 'sucesso' : 'falha';

            CrmWebhookLog::create([
                'status'      => $status,
                'status_code' => $statusCode,
                'payload'     => $payload,
                'resposta'    => mb_substr($response->body(), 0, 2000),
                'is_teste'    => true,
            ]);

            $this->resultadoTeste = "Status HTTP {$statusCode} — " . ($response->successful() ? 'Conexão bem-sucedida!' : 'CRM retornou erro client.');
            $this->corResultado   = $response->successful() ? 'green' : 'yellow';
        } catch (\Throwable $e) {
            CrmWebhookLog::create([
                'status'      => 'erro',
                'status_code' => null,
                'payload'     => $payload,
                'resposta'    => mb_substr($e->getMessage(), 0, 2000),
                'is_teste'    => true,
            ]);

            $this->resultadoTeste = 'Erro de conexão: ' . $e->getMessage();
            $this->corResultado   = 'red';
        }
    }

    public function render()
    {
        $logs = CrmWebhookLog::latest()->limit(20)->get();

        return view('modules.admin.livewire.integracao-crm', [
            'logs'   => $logs,
            'config' => CrmConfiguracao::atual(),
        ])->layout('layouts.admin', ['title' => 'Integração CRM']);
    }
}

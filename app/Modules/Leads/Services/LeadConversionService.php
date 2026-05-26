<?php

namespace App\Modules\Leads\Services;

use App\Models\Atendimento;
use App\Models\AtendimentoOrigem;
use App\Models\Imovel;
use App\Models\Lead;
use App\Models\WhatsappTemplate;
use App\Modules\Imoveis\Jobs\DispatchCrmWebhookJob;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Encapsulates the full lead-conversion pipeline so that multiple entry points
 * (HTTP API, Livewire gate, future webhooks) share identical business logic.
 *
 * Responsibilities:
 *  1. Upsert Lead record
 *  2. Track imovel interest history
 *  3. Create Atendimento (idempotent — firstOrCreate)
 *  4. Dispatch CRM webhook job (async, non-blocking)
 *  5. Build the WhatsApp redirect URL
 *
 * NOT responsible for:
 *  - HTTP request parsing / response formatting  → LeadApiController
 *  - Rate limiting                               → LeadApiController
 *  - Livewire form validation / browser events   → BuyerAnalysisGate
 */
final class LeadConversionService
{
    /**
     * Run the full conversion pipeline.
     *
     * @param  string  $nome
     * @param  string  $email
     * @param  string  $telefone      Raw or formatted phone (stored as-is)
     * @param  string  $imovelId      numero_original or slug
     * @param  string  $origemTag     Partial name to match AtendimentoOrigem
     * @param  array   $utm           UTM params (all nullable)
     * @param  string  $conversaoUrl  Full URL where the conversion happened
     *
     * @return ConversionResult
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException  When imovel is not found
     */
    public function convert(
        string $nome,
        string $email,
        string $telefone,
        string $imovelId,
        string $origemTag    = 'Formulário',
        array  $utm          = [],
        string $conversaoUrl = '',
    ): ConversionResult {
        // ── 1. Resolve imovel ─────────────────────────────────────────────
        $imovel = Imovel::with(['estado', 'municipio', 'bairro', 'tipoImovel', 'ultimoHistorico.modalidade'])
            ->where('numero_original', $imovelId)
            ->orWhere('slug', $imovelId)
            ->firstOrFail();

        // ── 2. Upsert lead (email is the unique business key) ─────────────
        $lead = Lead::firstOrCreate(
            ['email' => $email],
            [
                'nome'     => $nome,
                'telefone' => $telefone,
                'senha'    => Hash::make(Str::random(16)),
            ]
        );

        $utmUpdate = array_filter([
            'utm_source'   => $utm['utm_source']   ?? null,
            'utm_medium'   => $utm['utm_medium']   ?? null,
            'utm_campaign' => $utm['utm_campaign'] ?? null,
        ]);

        if (! $lead->wasRecentlyCreated) {
            $lead->update(array_merge(['nome' => $nome, 'telefone' => $telefone], $utmUpdate));
        } elseif ($utmUpdate) {
            $lead->update($utmUpdate);
        }

        // ── 3. Track imovel interest (append without duplicates) ──────────
        $interesse = $lead->imoveis_interesse ?? [];
        if (! collect($interesse)->contains('numero', $imovel->numero_original)) {
            $interesse[] = [
                'numero'    => $imovel->numero_original,
                'data'      => now()->toDateString(),
                'modalidade'=> $imovel->ultimoHistorico?->modalidade?->nome,
            ];
            $lead->update(['imoveis_interesse' => $interesse]);
        }

        // ── 4. Create Atendimento (idempotent) ────────────────────────────
        $origem = AtendimentoOrigem::where('nome', 'like', "%{$origemTag}%")->first();

        $atendimento = Atendimento::firstOrCreate(
            [
                'id_lead'   => $lead->id,
                'id_imovel' => $imovel->id,
            ],
            [
                'id_imobiliaria'  => $imovel->id_imobiliaria,
                'id_origem'       => $origem?->id,
                'mensagem'        => "{$nome} solicitou contato sobre o imóvel {$imovel->numero_original}.",
                'whatsapp_enviado'=> true,
            ]
        );

        // ── 5. Dispatch CRM webhook (async — failure must not block response) ─
        $localidade = implode(', ', array_filter([
            $imovel->bairro?->nome,
            $imovel->municipio?->nome,
            $imovel->estado?->uf,
        ]));

        try {
            DispatchCrmWebhookJob::dispatch([
                'imovel_id'  => $imovel->numero_original,
                'tipo_imovel'=> $imovel->tipoImovel?->nome,
                'valor'      => (float) ($imovel->ultimoHistorico?->valor_venda ?? 0),
                'localidade' => $localidade,
                'lead'       => ['nome' => $nome, 'email' => $email, 'telefone' => $telefone],
                'conversao_url' => $conversaoUrl,
                'timestamp'  => now()->toIso8601String(),
                'marketing'  => $utm,
            ]);
        } catch (\Throwable $e) {
            // CRM failure must never break the lead-save flow
            Log::error('LeadConversionService: CRM webhook dispatch failed', [
                'lead_id'   => $lead->id,
                'imovel_id' => $imovel->numero_original,
                'error'     => $e->getMessage(),
            ]);
        }

        // ── 6. Build WhatsApp redirect URL ────────────────────────────────
        $vars = [
            'nome'       => $nome,
            'tipo_imovel'=> $imovel->tipoImovel?->nome ?? 'Imóvel',
            'codigo'     => $imovel->numero_original,
            'localidade' => $localidade,
            'municipio'  => $imovel->municipio?->nome ?? '',
            'uf'         => $imovel->estado?->uf ?? '',
        ];

        $fallback    = "Olá! Meu nome é {$nome}. Tenho interesse no {$vars['tipo_imovel']} (Cód: {$vars['codigo']}) em {$localidade}. Pode me ajudar?";
        $message     = WhatsappTemplate::renderizarAtivo($vars, $fallback);
        $numero      = config('services.whatsapp.central');
        $whatsappUrl = 'https://api.whatsapp.com/send?phone=' . $numero . '&text=' . urlencode($message);

        return new ConversionResult(
            lead:                  $lead,
            atendimento:           $atendimento,
            whatsappUrl:           $whatsappUrl,
            whatsappText:          $message,
            leadWasCreated:        $lead->wasRecentlyCreated,
            atendimentoWasCreated: $atendimento->wasRecentlyCreated,
        );
    }
}

/**
 * Value object returned by LeadConversionService::convert().
 * Typed, immutable, zero-dependency — easy to assert in tests.
 */
final readonly class ConversionResult
{
    public function __construct(
        public Lead        $lead,
        public Atendimento $atendimento,
        public string      $whatsappUrl,
        public string      $whatsappText,
        public bool        $leadWasCreated,
        public bool        $atendimentoWasCreated,
    ) {}
}

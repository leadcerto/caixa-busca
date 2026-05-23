<?php

namespace App\Modules\Leads\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Atendimento;
use App\Models\AtendimentoOrigem;
use App\Models\Imovel;
use App\Models\Lead;
use App\Models\WhatsappTemplate;
use App\Modules\Imoveis\Jobs\DispatchCrmWebhookJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LeadApiController extends Controller
{
    /**
     * Capture interest and convert lead from external API/Mobile requests.
     */
    public function convert(Request $request): JsonResponse
    {
        $key = 'lead_api:' . $request->ip();

        // 5 requests per minute limit
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'message' => "Muitas tentativas. Aguarde {$seconds} segundos antes de tentar novamente.",
                'errors' => [
                    'ip' => ["Limite de requisições excedido. Tente novamente em {$seconds} segundos."]
                ]
            ], 429);
        }

        // Validate lead payload
        $validated = $request->validate([
            'nome' => 'required|string|min:3|max:100',
            'email' => 'required|email|max:150',
            'telefone' => 'required|string|min:10|max:20',
            'imovel_id' => 'required|string|max:50', // Can be number or slug
            'utm_source' => 'nullable|string|max:100',
            'utm_medium' => 'nullable|string|max:100',
            'utm_campaign' => 'nullable|string|max:100',
            'utm_term' => 'nullable|string|max:100',
            'utm_content' => 'nullable|string|max:100',
        ]);

        RateLimiter::hit($key, 60);

        // Find the property by original number or slug
        $imovel = Imovel::where('numero_original', $validated['imovel_id'])
            ->orWhere('slug', $validated['imovel_id'])
            ->first();

        if (!$imovel) {
            return response()->json([
                'message' => 'Imóvel não encontrado.',
                'errors' => [
                    'imovel_id' => ['O imóvel correspondente ao código ou slug fornecido não existe.']
                ]
            ], 404);
        }

        $imovel->load(['estado', 'municipio', 'bairro', 'tipoImovel', 'ultimoHistorico.modalidade']);

        // First or create lead by email
        $lead = Lead::firstOrCreate(
            ['email' => $validated['email']],
            [
                'nome' => $validated['nome'],
                'telefone' => $validated['telefone'],
                'senha' => Hash::make(Str::random(16)),
            ]
        );

        // Update name/phone if the lead already existed
        if (!$lead->wasRecentlyCreated) {
            $lead->update([
                'nome' => $validated['nome'],
                'telefone' => $validated['telefone']
            ]);
        }

        // Add property interest to history without duplicates
        $interesse = $lead->imoveis_interesse ?? [];
        $jaExiste = collect($interesse)->contains('numero', $imovel->numero_original);
        if (!$jaExiste) {
            $interesse[] = [
                'numero' => $imovel->numero_original,
                'data' => now()->toDateString(),
                'modalidade' => $imovel->ultimoHistorico?->modalidade?->nome,
            ];
            $lead->update(['imoveis_interesse' => $interesse]);
        }

        // Get origin for API (prefer "API/Mobile", fallback to "Formulário")
        $origem = AtendimentoOrigem::where('nome', 'like', '%API%')
            ->orWhere('nome', 'like', '%Mobile%')
            ->orWhere('nome', 'like', '%Formulário%')
            ->first();

        // Create the Atendimento (prevents duplicates for the same lead + property)
        $atendimento = Atendimento::firstOrCreate(
            [
                'id_lead' => $lead->id,
                'id_imovel' => $imovel->id,
            ],
            [
                'id_imobiliaria' => $imovel->id_imobiliaria,
                'id_origem' => $origem?->id,
                'mensagem' => "{$validated['nome']} solicitou contato sobre o imóvel {$imovel->numero_original} via API.",
                'whatsapp_enviado' => true,
            ]
        );

        // Prepare localidade string
        $localidade = implode(', ', array_filter([
            $imovel->bairro?->nome,
            $imovel->municipio?->nome,
            $imovel->estado?->uf,
        ]));

        // Gather marketing UTM tags
        $marketing = [
            'utm_source' => $validated['utm_source'] ?? null,
            'utm_medium' => $validated['utm_medium'] ?? null,
            'utm_campaign' => $validated['utm_campaign'] ?? null,
            'utm_term' => $validated['utm_term'] ?? null,
            'utm_content' => $validated['utm_content'] ?? null,
        ];

        // Dispatch CRM webhook job asynchronously
        DispatchCrmWebhookJob::dispatch([
            'imovel_id' => $imovel->numero_original,
            'tipo_imovel' => $imovel->tipoImovel?->nome,
            'valor' => (float) ($imovel->ultimoHistorico?->valor_venda ?? 0),
            'localidade' => $localidade,
            'lead' => [
                'nome' => $validated['nome'],
                'email' => $validated['email'],
                'telefone' => $validated['telefone'],
            ],
            'conversao_url' => $request->fullUrl(),
            'timestamp' => now()->toIso8601String(),
            'marketing' => $marketing,
        ]);

        // Generate WhatsApp template message
        $vars = [
            'nome' => $validated['nome'],
            'tipo_imovel' => $imovel->tipoImovel?->nome ?? 'Imóvel',
            'codigo' => $imovel->numero_original,
            'localidade' => $localidade,
            'municipio' => $imovel->municipio?->nome ?? '',
            'uf' => $imovel->estado?->uf ?? '',
        ];

        $fallback = "Olá! Meu nome é {$validated['nome']}. Tenho interesse no {$vars['tipo_imovel']} (Cód: {$vars['codigo']}) em {$localidade}. Pode me ajudar?";
        $message = WhatsappTemplate::renderizarAtivo($vars, $fallback);

        $numeroCentral = config('services.whatsapp.central', env('WHATSAPP_CENTRAL', '5511999999999'));
        $whatsappUrl = 'https://api.whatsapp.com/send?phone=' . $numeroCentral . '&text=' . urlencode($message);

        return response()->json([
            'success' => true,
            'message' => 'Lead e atendimento convertidos com sucesso!',
            'data' => [
                'lead_id' => $lead->id,
                'atendimento_id' => $atendimento->id,
                'lead_was_created' => $lead->wasRecentlyCreated,
                'atendimento_was_created' => $atendimento->wasRecentlyCreated,
                'whatsapp_text' => $message,
                'whatsapp_url' => $whatsappUrl,
            ]
        ], 201);
    }
}

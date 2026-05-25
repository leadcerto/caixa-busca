<?php

namespace App\Modules\Imoveis\Livewire;

use App\Models\Atendimento;
use App\Models\AtendimentoOrigem;
use App\Models\Imovel;
use App\Models\Lead;
use App\Models\WhatsappTemplate;
use App\Modules\Imoveis\Jobs\DispatchCrmWebhookJob;
use App\Modules\Imoveis\Services\UtmTrackerService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ImovelShow extends Component
{
    public Imovel $imovel;

    #[Validate('required|string|min:3|max:100')]
    public string $nome = '';

    #[Validate('required|email|max:150')]
    public string $email = '';

    #[Validate('required|string|min:10|max:20')]
    public string $telefone = '';

    public function mount(Imovel $imovel, UtmTrackerService $utmTracker): void
    {
        $this->imovel = $imovel->load([
            'estado',
            'municipio',
            'bairro',
            'tipoImovel',
            'ultimoHistorico.modalidade',
            'imobiliaria',
        ]);

        $utmTracker->captureFromRequest();
    }

    public function converterLead(UtmTrackerService $utmTracker): mixed
    {
        $key = 'lead_form:' . request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $segundos = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'nome' => ["Muitas tentativas. Aguarde {$segundos} segundos antes de tentar novamente."],
            ]);
        }

        $this->validate();

        RateLimiter::hit($key, 60);

        // Cria ou recupera o lead pelo e-mail
        $lead = Lead::firstOrCreate(
            ['email' => $this->email],
            [
                'nome'     => $this->nome,
                'telefone' => $this->telefone,
                'senha'    => Hash::make(Str::random(16)),
            ]
        );

        // Atualiza nome/telefone se o lead já existia
        if (!$lead->wasRecentlyCreated) {
            $lead->update(['nome' => $this->nome, 'telefone' => $this->telefone]);
        }

        // Adiciona imóvel ao histórico de interesse sem duplicar
        $interesse = $lead->imoveis_interesse ?? [];
        $jaExiste  = collect($interesse)->contains('numero', $this->imovel->numero_original);
        if (!$jaExiste) {
            $interesse[] = [
                'numero'     => $this->imovel->numero_original,
                'data'       => now()->toDateString(),
                'modalidade' => $this->imovel->ultimoHistorico?->modalidade?->nome,
            ];
            $lead->update(['imoveis_interesse' => $interesse]);
        }

        // Cria o atendimento (evita duplicata lead+imóvel)
        $origem = AtendimentoOrigem::where('nome', 'like', '%Formulário%')->first();

        Atendimento::firstOrCreate(
            [
                'id_lead'   => $lead->id,
                'id_imovel' => $this->imovel->id,
            ],
            [
                'id_imobiliaria'   => $this->imovel->resolved_imobiliaria?->id ?? $this->imovel->id_imobiliaria,
                'id_origem'        => $origem?->id,
                'mensagem'         => "{$this->nome} solicitou contato sobre o imóvel {$this->imovel->numero_original}.",
                'whatsapp_enviado' => true,
            ]
        );

        // Monta localidade para o webhook e mensagem
        $localidade = implode(', ', array_filter([
            $this->imovel->bairro?->nome,
            $this->imovel->municipio?->nome,
            $this->imovel->estado?->uf,
        ]));

        // Dispara webhook CRM de forma assíncrona
        DispatchCrmWebhookJob::dispatch([
            'imovel_id'     => $this->imovel->numero_original,
            'tipo_imovel'   => $this->imovel->tipoImovel?->nome,
            'valor'         => (float) ($this->imovel->ultimoHistorico?->valor_venda ?? 0),
            'localidade'    => $localidade,
            'lead'          => [
                'nome'     => $this->nome,
                'email'    => $this->email,
                'telefone' => $this->telefone,
            ],
            'conversao_url' => url()->current(),
            'timestamp'     => now()->toIso8601String(),
            'marketing'     => $utmTracker->getTrackedUtms(),
        ]);

        // Gera link do WhatsApp com dados do lead e imóvel
        $vars = [
            'nome'       => $this->nome,
            'tipo_imovel' => $this->imovel->tipoImovel?->nome ?? 'Imóvel',
            'codigo'     => $this->imovel->numero_original,
            'localidade' => $localidade,
            'municipio'  => $this->imovel->municipio?->nome ?? '',
            'uf'         => $this->imovel->estado?->uf ?? '',
        ];

        $fallback = "Olá! Meu nome é {$this->nome}. Tenho interesse no {$vars['tipo_imovel']} "
            . "(Cód: {$vars['codigo']}) em {$localidade}. Pode me ajudar?";

        $message = WhatsappTemplate::renderizarAtivo($vars, $fallback);

        $resolvedImob = $this->imovel->resolved_imobiliaria;
        $numero = $resolvedImob ? preg_replace('/\D/', '', $resolvedImob->whatsapp) : config('services.whatsapp.central', env('WHATSAPP_CENTRAL', '5521997882950'));

        // Certifica de adicionar o DDI do Brasil (55) se estiver ausente
        if (strlen($numero) > 0 && !str_starts_with($numero, '55')) {
            if (strlen($numero) === 10 || strlen($numero) === 11) {
                $numero = '55' . $numero;
            }
        }

        $whatsappUrl = 'https://api.whatsapp.com/send/?phone=' . $numero . '&text=' . urlencode($message);

        return redirect()->away($whatsappUrl);
    }

    public function render()
    {
        $tipo      = $this->imovel->tipoImovel?->nome ?? 'Imóvel';
        $municipio = $this->imovel->municipio?->nome ?? '';

        return view('modules.imoveis.livewire.imovel-show')
            ->layout('layouts.app', [
                'meta_title'       => "{$tipo} em {$municipio} | Antigravity Imóveis",
                'meta_description' => $this->imovel->meta_description
                    ?? "Oportunidade de investimento em {$this->imovel->bairro?->nome}. Veja detalhes.",
                'og_image'         => $this->imovel->foto_fachada_url ?? asset('images/imovel-placeholder.svg'),
            ]);
    }
}

<?php

namespace App\Modules\Imoveis\Livewire;

use App\Models\Atendimento;
use App\Models\AtendimentoOrigem;
use App\Models\Imovel;
use App\Models\Lead;
use App\Modules\Imoveis\Jobs\DispatchCrmWebhookJob;
use App\Modules\Imoveis\Services\UtmTrackerService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
        ]);

        $utmTracker->captureFromRequest();
    }

    public function converterLead(UtmTrackerService $utmTracker): mixed
    {
        $this->validate();

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
                'id_imobiliaria'   => $this->imovel->id_imobiliaria,
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
        $message = "Olá! Meu nome é {$this->nome}. Tenho interesse no imóvel "
            . "{$this->imovel->tipoImovel?->nome} (Cód: {$this->imovel->numero_original}) "
            . "em {$localidade}. Pode me ajudar?";

        $numero      = config('services.whatsapp.central', env('WHATSAPP_CENTRAL', '5511999999999'));
        $whatsappUrl = 'https://api.whatsapp.com/send?phone=' . $numero . '&text=' . urlencode($message);

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
                'og_image'         => asset("images/og/{$this->imovel->slug}.jpg"),
            ]);
    }
}

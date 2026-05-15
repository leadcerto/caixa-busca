<?php

namespace App\Modules\Imoveis\Livewire;

use App\Models\Imovel;
use App\Modules\Imoveis\Jobs\DispatchCrmWebhookJob;
use App\Modules\Imoveis\Services\UtmTrackerService;
use Livewire\Component;

/**
 * Componente Livewire para a Pgina de Detalhes do Imvel.
 * Responsvel pela converso final do Lead e gerao de links dinmicos.
 */
class ImovelShow extends Component
{
    /**
     * Instncia do imvel carregada via Route Model Binding.
     */
    public Imovel $imovel;

    /**
     * Inicializa o componente e captura UTMs (Regra 8).
     */
    public function mount(Imovel $imovel, UtmTrackerService $utmTracker)
    {
        $this->imovel = $imovel;
        
        // Persiste UTMs se o usurio caiu direto nesta pgina
        $utmTracker->captureFromRequest();
    }

    /**
     * Mtodo de Converso: Dispara o Webhook e gera o link do WhatsApp.
     * Segue a regra de "Integrao Resiliente" e "Mensagens Dinmicas".
     */
    public function converterLead(UtmTrackerService $utmTracker)
    {
        // 1. Recupera as UTMs persistidas para enriquecer o Lead
        $utms = $utmTracker->getTrackedUtms();

        // 2. Prepara o Payload para o CRM (Regra 50)
        $payload = [
            'imovel_id' => $this->imovel->numero_original,
            'tipo_imovel' => $this->imovel->tipo_imovel,
            'valor' => (float) $this->imovel->preco,
            'localidade' => "{$this->imovel->bairro}, {$this->imovel->cidade} - {$this->imovel->uf}",
            'conversao_url' => url()->current(),
            'timestamp' => now()->toIso8601String(),
            'marketing' => $utms
        ];

        // 3. Dispara o Job Assncrono (Resilincia - Matriz do Caos)
        DispatchCrmWebhookJob::dispatch($payload);

        // 4. Gera a Mensagem Dinmica do WhatsApp (Regra 44)
        // Template flexvel para o SDR saber exatamente de onde o lead veio
        $messageTemplate = "Ol! Tenho interesse no imvel {{tipo}} (ID: {{id}}) localizado em {{bairro}}. Gostaria de mais informaes.";
        
        $message = str_replace(
            ['{{tipo}}', '{{id}}', '{{bairro}}'],
            [$this->imovel->tipo_imovel, $this->imovel->numero_original, $this->imovel->bairro],
            $messageTemplate
        );

        $centralNumber = env('WHATSAPP_CENTRAL', '5511999999999');
        $whatsappUrl = "https://api.whatsapp.com/send?phone={$centralNumber}&text=" . urlencode($message);

        // 5. Redirecionamento Final para o WhatsApp
        return redirect()->away($whatsappUrl);
    }

    /**
     * Renderiza a view com metatags dinmicas para SEO (Regra 15).
     */
    public function render()
    {
        return view('modules.imoveis.livewire.imovel-show')
            ->layout('layouts.app', [
                'meta_title' => "{$this->imovel->tipo_imovel} em {$this->imovel->cidade} | Antigravity Imveis",
                'meta_description' => "Oportunidade de investimento em {$this->imovel->bairro}. Veja fotos e detalhes tcnicos.",
                'og_image' => asset("images/og/{$this->imovel->slug}.jpg") // Regra 31
            ]);
    }
}

<?php

namespace App\Modules\Imoveis\Livewire;

use App\Models\Atendimento;
use App\Models\AtendimentoOrigem;
use App\Models\Imovel;
use App\Models\Lead;
use App\Models\WhatsappTemplate;
use App\Modules\BairrosDossie\Services\ConteudoIaService;
use App\Modules\Imoveis\Jobs\DispatchCrmWebhookJob;
use App\Modules\Imoveis\Services\UtmTrackerService;
use Illuminate\Support\Facades\DB;
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

        // Incrementa contador de visitas de forma atômica (sem risco de race condition)
        DB::table('imoveis')->where('id', $this->imovel->id)->increment('visitas');

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

        // Recarrega visitas do banco (o increment não atualiza o objeto em memória)
        $visitas         = DB::table('imoveis')->where('id', $this->imovel->id)->value('visitas') ?? 0;
        $whatsappClicks  = DB::table('imoveis')->where('id', $this->imovel->id)->value('whatsapp_clicks') ?? 0;

        // Métricas de atendimento (formulários preenchidos)
        $totalFormularios   = Atendimento::where('id_imovel', $this->imovel->id)->count();
        $formUltimos7Dias   = Atendimento::where('id_imovel', $this->imovel->id)->where('created_at', '>=', now()->subDays(7))->count();
        $formUltimos30Dias  = Atendimento::where('id_imovel', $this->imovel->id)->where('created_at', '>=', now()->subDays(30))->count();
        $whatsappEnviados   = Atendimento::where('id_imovel', $this->imovel->id)->where('whatsapp_enviado', true)->count();

        // Taxa de conversão (formulários / visitas)
        $taxaConversao = $visitas > 0 ? round(($totalFormularios / $visitas) * 100, 1) : 0;

        // Histórico de preços
        $historicos         = $this->imovel->historico;
        $totalAtualizacoes  = $historicos->count();
        $primeiroHistorico  = $historicos->last(); // ordenado desc, logo last() é o mais antigo
        $ultimoHistorico    = $historicos->first();
        $precoInicial       = $primeiroHistorico?->valor_venda ?? 0;
        $precoAtual         = $ultimoHistorico?->valor_venda ?? 0;
        $variacaoPreco      = ($precoInicial > 0 && $precoAtual > 0)
            ? round((($precoAtual - $precoInicial) / $precoInicial) * 100, 1)
            : 0;

        // Dias na plataforma
        $diasNaPlataforma = $this->imovel->created_at->diffInDays(now());

        $stats = compact(
            'visitas', 'whatsappClicks',
            'totalFormularios', 'formUltimos7Dias', 'formUltimos30Dias',
            'whatsappEnviados', 'taxaConversao',
            'totalAtualizacoes', 'precoInicial', 'precoAtual', 'variacaoPreco',
            'diasNaPlataforma'
        );

        $bairro        = strtoupper($this->imovel->bairro?->nome ?? '');
        $uf            = strtoupper($this->imovel->estado?->uf ?? '');
        $descontoValor = $this->imovel->ultimoHistorico?->desconto_valor ?? 0;
        $descontoFmt   = 'R$ ' . number_format($descontoValor, 2, ',', '.');

        $metaTitle = $descontoValor > 0
            ? "Lucro imediato de {$descontoFmt} | Saiba Mais"
            : "{$tipo} em {$municipio} | Imóveis da Caixa";

        $metaDesc = "{$tipo} à venda em {$bairro}, {$municipio} - {$uf}"
            . ($descontoValor > 0 ? " com desconto de {$descontoFmt}" : '')
            . "; Clique no link para mais informações.";

        // Conteúdo rico do bairro (gerado pela IA — Fase 14)
        $conteudoIaBairro = $this->imovel->bairro?->conteudo_ia ?? [];
        $temFaqBairro = is_array($conteudoIaBairro)
            && !empty(array_intersect_key($conteudoIaBairro, array_flip(ConteudoIaService::FAQ_CAMPOS)));

        return view('modules.imoveis.livewire.imovel-show', compact('stats', 'conteudoIaBairro', 'temFaqBairro'))
            ->layout('layouts.app', [
                'meta_title'       => $metaTitle,
                'meta_description' => $metaDesc,
                'og_image'         => $this->imovel->foto_fachada_url ?? asset('images/imovel-placeholder.svg'),
                'canonical'        => url('/' . $this->imovel->slug),
            ]);
    }
}

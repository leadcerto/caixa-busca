<?php

namespace App\Modules\BairrosDossie\Services;

use App\Models\Bairro;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ConteudoIaService
{
    // Campos FAQ obrigatórios na resposta da IA
    public const FAQ_CAMPOS = [
        'vizinhanca_localizacao',
        'beneficios',
        'acessos_transporte',
        'comercio_conveniencia',
        'educacao',
        'saude',
        'lazer_cultura',
        'dados_infraestrutura',
    ];

    public function gerarParaBairro(Bairro $bairro): array
    {
        $bairro->load(['municipio.estado', 'imoveis' => fn($q) => $q->where('status', 'ativo')->limit(100)]);

        $municipio = $bairro->municipio?->nome ?? 'cidade';
        $uf        = $bairro->municipio?->estado?->uf ?? '';
        $nome      = $bairro->nome;

        $totalImoveis = $bairro->imoveis->count();

        $precos = $bairro->imoveis
            ->map(fn($i) => $i->ultimoHistorico?->valor_venda ?? null)
            ->filter()
            ->values();

        $faixaPreco = $precos->isEmpty()
            ? 'valores não disponíveis'
            : 'de R$ ' . number_format($precos->min(), 0, ',', '.') . ' a R$ ' . number_format($precos->max(), 0, ',', '.');

        $tipos = $bairro->imoveis
            ->groupBy(fn($i) => $i->tipoImovel?->nome ?? 'Outros')
            ->map->count()
            ->sortDesc()
            ->take(4)
            ->map(fn($qtd, $tipo) => "{$qtd} {$tipo}(s)")
            ->implode(', ');

        $prompt = <<<PROMPT
Você é um especialista imobiliário e urbanista profundo conhecedor do Brasil.
Sua tarefa é fornecer informações detalhadas, realistas e atrativas sobre o bairro para quem deseja comprar ou investir em imóveis.

Dados disponíveis:
- Bairro: {$nome}
- Município: {$municipio} — {$uf}
- Imóveis disponíveis (Caixa Econômica Federal): {$totalImoveis}
- Tipos de imóvel: {$tipos}
- Faixa de preço: {$faixaPreco}

Retorne SOMENTE um objeto JSON válido, sem markdown, sem texto antes ou depois do JSON.
Tom: profissional, voltado para comprador/investidor.
Se o bairro for de cidade menor, adapte a realidade para a escala da cidade.

{
  "titulo": "título H1 otimizado para SEO (máx 70 caracteres)",
  "meta_description": "meta description para Google (máx 155 caracteres)",
  "texto": "texto de 2 a 3 parágrafos sobre o bairro e as oportunidades imobiliárias",
  "vizinhanca_localizacao": "2 parágrafos sobre perfil da vizinhança, história breve e posição na cidade",
  "beneficios": "principais benefícios de morar ou investir neste bairro",
  "acessos_transporte": "principais vias, rodovias próximas e oferta de transporte público",
  "comercio_conveniencia": "supermercados, shoppings, farmácias, padarias e comércio local",
  "educacao": "escolas públicas e particulares, creches e universidades na região",
  "saude": "hospitais, clínicas, UPAs e postos de saúde",
  "lazer_cultura": "praças, parques, praias, cinemas, vida noturna e restaurantes",
  "dados_infraestrutura": "saneamento, segurança geral, pavimentação e iluminação"
}
PROMPT;

        $apiKey = config('services.openrouter.key', env('OPENROUTER_API_KEY'));
        $model  = config('services.openrouter.model', 'meta-llama/llama-3-8b-instruct:free');

        if (empty($apiKey)) {
            throw new \RuntimeException('OPENROUTER_API_KEY não configurada.');
        }

        $response = Http::timeout(60)->withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type'  => 'application/json',
            'HTTP-Referer'  => config('app.url', 'https://venda.imoveisdacaixa.com.br'),
            'X-Title'       => 'Imóveis da Caixa',
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model'    => $model,
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
            'max_tokens'  => 2048,
            'temperature' => 0.7,
        ]);

        if (!$response->successful()) {
            throw new \RuntimeException("OpenRouter API erro {$response->status()}: {$response->body()}");
        }

        $texto = $response->json('choices.0.message.content', '');

        // Remove possível markdown ```json ... ``` que alguns modelos inserem
        $texto = preg_replace('/^```(?:json)?\s*/i', '', trim($texto));
        $texto = preg_replace('/\s*```$/', '', $texto);

        $dados = json_decode(trim($texto), true);

        if (!is_array($dados) || empty($dados['titulo'])) {
            throw new \RuntimeException("Resposta da IA inválida ou fora do formato JSON esperado: {$texto}");
        }

        $dados['_meta'] = [
            'modelo'    => $model,
            'data'      => now()->format('d/m/Y'),
            'hora'      => now()->format('H:i:s'),
            'gerado_em' => now()->toDateTimeString(),
        ];

        return $dados;
    }
}

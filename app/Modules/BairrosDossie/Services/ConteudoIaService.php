<?php

namespace App\Modules\BairrosDossie\Services;

use App\Models\Bairro;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ConteudoIaService
{
    private string $modelo = 'claude-haiku-4-5-20251001';

    public function gerarParaBairro(Bairro $bairro): array
    {
        $bairro->load(['municipio.estado', 'imoveis' => fn($q) => $q->where('ativo', true)->limit(100)]);

        $totalImoveis = $bairro->imoveis->count();
        $municipio    = $bairro->municipio?->nome ?? 'cidade';
        $uf           = $bairro->municipio?->estado?->uf ?? '';
        $nome         = $bairro->nome;

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
Você é um especialista em mercado imobiliário brasileiro. Crie um conteúdo informativo e otimizado para SEO sobre imóveis disponíveis no bairro.

Dados do bairro:
- Bairro: {$nome}
- Município: {$municipio} — {$uf}
- Total de imóveis disponíveis (Caixa Econômica Federal): {$totalImoveis}
- Tipos: {$tipos}
- Faixa de preço: {$faixaPreco}

Retorne SOMENTE um JSON válido com esta estrutura (sem markdown, sem texto antes ou depois):
{
  "titulo": "título H1 otimizado para SEO (máx 70 caracteres)",
  "meta_description": "meta description para Google (máx 155 caracteres)",
  "texto": "texto informativo de 3 a 4 parágrafos sobre o bairro, oportunidades imobiliárias e público-alvo, em português do Brasil, tom informativo e profissional"
}
PROMPT;

        $apiKey = config('services.anthropic.key', env('ANTHROPIC_API_KEY'));

        if (empty($apiKey)) {
            throw new \RuntimeException('ANTHROPIC_API_KEY não configurada.');
        }

        $response = Http::timeout(30)->withHeaders([
            'x-api-key'         => $apiKey,
            'anthropic-version' => '2023-06-01',
            'Content-Type'      => 'application/json',
        ])->post('https://api.anthropic.com/v1/messages', [
            'model'      => $this->modelo,
            'max_tokens' => 1024,
            'messages'   => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        if (!$response->successful()) {
            throw new \RuntimeException("Anthropic API erro {$response->status()}: {$response->body()}");
        }

        $texto = $response->json('content.0.text', '');
        $dados = json_decode($texto, true);

        if (!is_array($dados) || empty($dados['titulo']) || empty($dados['texto'])) {
            throw new \RuntimeException("Resposta da IA inválida: {$texto}");
        }

        return $dados;
    }
}

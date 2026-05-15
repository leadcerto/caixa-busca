<?php

namespace App\Modules\ImportacaoCSV\Services;

use App\Models\Imovel;
use App\Models\ImovelHistorico;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Serviço de Parsing e Importação de CSV da Caixa Econômica Federal.
 * Segue estritamente a especificação spec-importacao-csv.md
 */
class CaixaCsvParserService
{
    private ?string $dataGeracao = null;

    /**
     * Processa o arquivo CSV e persiste no banco de dados.
     * 
     * @param string $filePath Caminho absoluto do arquivo CSV.
     * @return void
     */
    public function process(string $filePath): void
    {
        if (!file_exists($filePath)) {
            Log::error("CaixaCsvParser: Arquivo no encontrado em {$filePath}");
            return;
        }

        // Abre o arquivo para leitura (modo r)
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            Log::error("CaixaCsvParser: Falha ao abrir o arquivo.");
            return;
        }

        $lineCount = 0;
        $headers = [];

        try {
            while (($row = fgetcsv($handle, 0, ';')) !== false) {
                $lineCount++;

                // Converte encoding de ISO-8859-1 para UTF-8
                $row = array_map(function($cell) {
                    return mb_convert_encoding($cell, 'UTF-8', 'ISO-8859-1');
                }, $row);

                // BLOCO 1: Captura de Metadados (Linha 1)
                if ($lineCount === 1) {
                    $this->extractDataGeracao($row[0]);
                    continue;
                }

                // BLOCO 2: Definio de Headers Sanitizados (Linha 2)
                if ($lineCount === 2) {
                    $headers = $this->sanitizeHeaders($row);
                    continue;
                }

                // BLOCO 3: Salto de Segurana (Linha 3 - separadores vazios)
                if ($lineCount === 3) {
                    continue;
                }

                // Validao de conformidade da linha
                if (count($headers) !== count($row)) {
                    Log::warning("CaixaCsvParser: Linha {$lineCount} ignorada. Colunas incondizentes com o header.");
                    continue;
                }

                $data = array_combine($headers, $row);

                // BLOCO 4: Processamento e Blindagem (Try/Catch)
                try {
                    $this->processRow($data);
                } catch (\Exception $e) {
                    Log::warning("CaixaCsvParser: Falha na linha {$lineCount}: " . $e->getMessage());
                    continue;
                }
            }
        } catch (\Exception $e) {
            Log::error("CaixaCsvParser: Erro crtico no processamento: " . $e->getMessage());
        } finally {
            fclose($handle);
        }
    }

    /**
     * Extrai a data de gerao do arquivo da primeira linha.
     */
    private function extractDataGeracao(string $firstCell): void
    {
        // Exemplo: "Arquivo gerado em: 15/05/2024"
        if (preg_match('/(\d{2}\/\d{2}\/\d{4})/', $firstCell, $matches)) {
            try {
                $this->dataGeracao = Carbon::createFromFormat('d/m/Y', $matches[1])->startOfDay()->toDateTimeString();
            } catch (\Exception $e) {
                $this->dataGeracao = now()->toDateTimeString();
            }
        } else {
            $this->dataGeracao = now()->toDateTimeString();
        }
    }

    /**
     * Sanitiza os headers conforme as regras de negcio.
     */
    private function sanitizeHeaders(array $row): array
    {
        return array_map(function ($header) {
            $header = mb_strtolower($header, 'UTF-8');
            $header = str_replace([' ', 'n'], ['_', 'numero'], $header);
            
            // Remove acentos e caracteres especiais
            $header = preg_replace('/[]/u', 'a', $header);
            $header = preg_replace('/[]/u', 'e', $header);
            $header = preg_replace('/[]/u', 'i', $header);
            $header = preg_replace('/[]/u', 'o', $header);
            $header = preg_replace('/[]/u', 'u', $header);
            $header = preg_replace('//u', 'c', $header);
            
            // Remove qualquer outro caractere no alfanumrico (exceto _)
            $header = preg_replace('/[^a-z0-9_]/', '', $header);

            return trim($header, '_');
        }, $row);
    }

    /**
     * Processa a linha e persiste os dados com deduplicao.
     */
    private function processRow(array $data): void
    {
        // Matriz do Caos: Campos vitais no podem ser nulos
        $idCaixa = $data['numero_do_imovel'] ?? null;
        $precoStr = $data['preco'] ?? null;

        if (!$idCaixa || !$precoStr) {
            throw new \Exception("Campos essenciais ausentes.");
        }

        // Limpeza de valores monetrios
        $preco = $this->cleanMoney($precoStr);
        $valorAvaliacao = $this->cleanMoney($data['valor_de_avaliacao'] ?? '0');
        $desconto = (float) str_replace(['.', ','], ['', '.'], $data['desconto'] ?? '0');

        // Parse de Atributos da Descrio via Regex
        $attrs = $this->parseDescription($data['descricao'] ?? '');

        // Tratamento de Bairro/Sub-bairro (Parenteses)
        $location = $this->parseBairro($data['bairro'] ?? '');

        // Persistncia Atmica
        DB::transaction(function () use ($idCaixa, $preco, $valorAvaliacao, $desconto, $data, $attrs, $location) {
            
            // 1. Upsert do Imvel (Deduplicao por numero_original)
            $imovel = Imovel::updateOrCreate(
                ['numero_original' => $idCaixa],
                [
                    'endereco' => $data['endereco'] ?? '',
                    'descricao_original' => $data['descricao'] ?? '',
                    'area_total' => $attrs['area_total'],
                    'quartos' => $attrs['quartos'],
                    'garagens' => $attrs['vagas'],
                    'updated_at' => $this->dataGeracao,
                    'status' => 'ativo',
                    // Note: id_estado, id_municipio etc devem ser vinculados via Service de Localidade (prxima fase)
                ]
            );

            // 2. Verificao de mudana de preo para histrico
            $ultimoHistorico = ImovelHistorico::where('id_imovel', $imovel->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$ultimoHistorico || (float)$ultimoHistorico->valor_venda !== $preco) {
                ImovelHistorico::create([
                    'id_imovel' => $imovel->id,
                    'valor_venda' => $preco,
                    'valor_avaliacao' => $valorAvaliacao,
                    'desconto_percentual' => $desconto,
                    'desconto_valor' => $valorAvaliacao - $preco,
                    'data_referencia' => $this->dataGeracao,
                ]);
            }
        });
    }

    /**
     * Converte string de preo CSV para float.
     */
    private function cleanMoney(string $value): float
    {
        return (float) str_replace(['.', ','], ['', '.'], $value);
    }

    /**
     * Extrai atributos da descrio.
     */
    private function parseDescription(string $desc): array
    {
        $attrs = ['quartos' => 0, 'vagas' => 0, 'area_total' => 0.0];

        // rea
        if (preg_match('/(\d+[.,]\d+)\s*m[2]/u', $desc, $matches)) {
            $attrs['area_total'] = (float) str_replace(',', '.', $matches[1]);
        }

        // Quartos
        if (preg_match('/(\d+)\s*quartos?/u', $desc, $matches)) {
            $attrs['quartos'] = (int) $matches[1];
        }

        // Vagas
        if (preg_match('/(\d+)\s*vagas?\s*de\s*garagem/u', $desc, $matches)) {
            $attrs['vagas'] = (int) $matches[1];
        }

        return $attrs;
    }

    /**
     * Separa Bairro de Sub-bairro.
     */
    private function parseBairro(string $bairroStr): array
    {
        $bairro = $bairroStr;
        $subBairro = null;

        if (preg_match('/^(.*?)\s*\((.*?)\)$/u', $bairroStr, $matches)) {
            $bairro = trim($matches[1]);
            $subBairro = trim($matches[2]);
        }

        return ['bairro' => $bairro, 'sub_bairro' => $subBairro];
    }
}

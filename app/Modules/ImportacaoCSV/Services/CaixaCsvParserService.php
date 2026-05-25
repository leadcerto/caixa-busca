<?php

namespace App\Modules\ImportacaoCSV\Services;

use App\Models\Bairro;
use App\Models\Estado;
use App\Models\ImobiliariaEstado;
use App\Models\Imovel;
use App\Models\ImovelEtapa;
use App\Models\ImovelGrupo;
use App\Models\ImovelHistorico;
use App\Models\ModalidadeVenda;
use App\Models\Municipio;
use App\Models\SubBairro;
use App\Models\TipoImovel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CaixaCsvParserService
{
    private ?string $dataGeracao = null;
    private ?int $etapaImportacaoId = null;

    // Caches em memória para evitar N queries por linha do CSV
    private array $estadoCache = [];
    private array $imobiliariaCache = [];
    private array $municipioCache = [];
    private array $bairroCache = [];
    private array $subBairroCache = [];
    private array $tipoImovelCache = [];
    private array $modalidadeCache = [];

    // Grupos carregados uma única vez no início do processo
    private array $grupos = [];

    /**
     * Chave de cache usada para expor o progresso em tempo real ao dashboard.
     */
    public const PROGRESS_CACHE_KEY = 'csv_import_progress';

    public function process(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new \RuntimeException("CaixaCsvParser: Arquivo não encontrado em {$filePath}");
        }

        // Conta o total de linhas para calcular percentual de progresso
        $totalLines = $this->countLines($filePath);

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            Log::error("CaixaCsvParser: Falha ao abrir o arquivo.");
            return;
        }

        $this->etapaImportacaoId = ImovelEtapa::where('ordem', 1)->value('id');
        $this->grupos = ImovelGrupo::where('ativo', true)->orderBy('valor_minimo')->get()->all();

        // Publica o estado inicial de progresso (TTL de 30 minutos)
        Cache::put(self::PROGRESS_CACHE_KEY, [
            'status'    => 'processing',
            'file'      => basename($filePath),
            'total'     => $totalLines,
            'processed' => 0,
            'inserted'  => 0,
            'skipped'   => 0,
            'started_at' => now()->toDateTimeString(),
        ], 1800);

        $lineCount = 0;
        $insertedCount = 0;
        $skippedCount = 0;
        $headers = null;

        try {
            while (($row = fgetcsv($handle, 0, ';')) !== false) {
                $lineCount++;

                $row = array_map(
                    fn($cell) => $cell !== null ? mb_convert_encoding($cell, 'UTF-8', 'ISO-8859-1') : '',
                    $row
                );

                // Ignora linhas completamente vazias
                $nonEmpty = array_filter($row, fn($c) => trim($c) !== '');
                if (empty($nonEmpty)) {
                    continue;
                }

                // Se ainda não detectou a data de geração, procura na linha atual
                if ($this->dataGeracao === null) {
                    $isMetadata = false;
                    foreach ($row as $cell) {
                        if (stripos($cell, 'Lista de Imóveis') !== false || stripos($cell, 'Data de geração') !== false) {
                            $isMetadata = true;
                            break;
                        }
                    }
                    if ($isMetadata) {
                        $this->extractDataGeracao($row);
                        continue;
                    }
                }

                // Se ainda não detectou o cabeçalho, procura a linha contendo "N° do imóvel" ou variantes
                if (empty($headers)) {
                    $isHeader = false;
                    foreach ($row as $cell) {
                        $cleanCell = trim(mb_strtolower($cell));
                        if ($cleanCell === 'n° do imóvel' || $cleanCell === 'nº do imóvel' || $cleanCell === 'n. do imovel' || $cleanCell === 'numero_do_imovel') {
                            $isHeader = true;
                            break;
                        }
                    }
                    if ($isHeader) {
                        $headers = $this->sanitizeHeaders($row);
                        if ($this->dataGeracao === null) {
                            $this->dataGeracao = now()->toDateTimeString();
                        }
                        continue;
                    }
                    continue; // Pula linhas de metadados ou vazias antes do cabeçalho
                }

                if (count($headers) !== count($row)) {
                    Log::warning("CaixaCsvParser: Linha {$lineCount} ignorada — colunas incompatíveis. Esperadas " . count($headers) . ", recebidas " . count($row));
                    continue;
                }

                $data = array_combine($headers, $row);

                try {
                    $this->processRow($data);
                    $insertedCount++;
                } catch (\Exception $e) {
                    $skippedCount++;
                    $msg = "CaixaCsvParser: Falha na linha {$lineCount}: " . $e->getMessage();
                    Log::warning($msg);
                    if (app()->runningInConsole()) {
                        echo $msg . "\n";
                    }
                }

                // Atualiza progresso no cache a cada 50 linhas processadas
                if ($lineCount % 50 === 0) {
                    Cache::put(self::PROGRESS_CACHE_KEY, [
                        'status'    => 'processing',
                        'file'      => basename($filePath),
                        'total'     => $totalLines,
                        'processed' => $lineCount,
                        'inserted'  => $insertedCount,
                        'skipped'   => $skippedCount,
                        'started_at' => Cache::get(self::PROGRESS_CACHE_KEY)['started_at'] ?? now()->toDateTimeString(),
                    ], 1800);
                }
            }
        } catch (\Exception $e) {
            Log::error("CaixaCsvParser: Erro crítico no processamento: " . $e->getMessage());
            Cache::put(self::PROGRESS_CACHE_KEY, [
                'status'    => 'error',
                'error'     => $e->getMessage(),
                'processed' => $lineCount,
                'inserted'  => $insertedCount,
                'skipped'   => $skippedCount,
            ], 1800);
        } finally {
            fclose($handle);
        }

        // Estado final: concluído
        Cache::put(self::PROGRESS_CACHE_KEY, [
            'status'      => 'completed',
            'file'        => basename($filePath),
            'total'       => $totalLines,
            'processed'   => $lineCount,
            'inserted'    => $insertedCount,
            'skipped'     => $skippedCount,
            'finished_at' => now()->toDateTimeString(),
            'started_at'  => Cache::get(self::PROGRESS_CACHE_KEY)['started_at'] ?? now()->toDateTimeString(),
        ], 300); // Mantém por 5 minutos após concluir
    }

    /**
     * Conta linhas do arquivo CSV para cálculo de progresso.
     */
    private function countLines(string $filePath): int
    {
        $count = 0;
        $handle = fopen($filePath, 'r');
        if ($handle) {
            while (fgets($handle) !== false) {
                $count++;
            }
            fclose($handle);
        }
        return $count;
    }

    private function processRow(array $data): void
    {
        // Bug fix: N° do imóvel chega em notação científica (1,44442E+12) quando
        // o Excel gera o CSV. O número real está no parâmetro hdnimovel da URL.
        $link    = trim($data['link_de_acesso'] ?? '');
        $idCaixa = $this->extractHdnimovel($link);

        if (!$idCaixa) {
            $rawNum = trim($data['numero_do_imovel'] ?? '');
            if (stripos($rawNum, 'E+') !== false) {
                // Se estiver em notação científica, converte para inteiro string normalizado
                $floatVal = (float) str_replace(',', '.', $rawNum);
                $idCaixa = sprintf('%.0f', $floatVal);
            } else {
                $idCaixa = preg_replace('/\D/', '', $rawNum);
            }
        }

        $precoStr = $data['preco'] ?? '';

        if (!$idCaixa || !$precoStr) {
            throw new \Exception("Campos essenciais ausentes (numero_do_imovel / preco).");
        }

        $preco          = $this->cleanMoney($precoStr);
        $valorAvaliacao = $this->cleanMoney($data['valor_de_avaliacao'] ?? '0');
        $desconto       = $this->cleanDecimal($data['desconto'] ?? '0');

        $attrs    = $this->parseDescription($data['descricao'] ?? '');
        $location = $this->parseBairro($data['bairro'] ?? '');

        // Resolução das FKs com cache em memória
        $uf = strtoupper(trim($data['uf'] ?? ''));
        if (!$uf) {
            throw new \Exception("UF ausente.");
        }

        $idEstado = $this->resolveEstado($uf);
        if (!$idEstado) {
            throw new \Exception("UF não reconhecida: \"{$uf}\".");
        }

        $nomeCidade = trim($data['cidade'] ?? '');
        if (!$nomeCidade) {
            throw new \Exception("Cidade ausente.");
        }

        $idMunicipio = $this->resolveMunicipio($nomeCidade, $idEstado);

        $idBairro    = null;
        $idSubBairro = null;
        if ($location['bairro'] && $idMunicipio) {
            $idBairro = $this->resolveBairro($location['bairro'], $idMunicipio);
            if ($location['sub_bairro'] && $idBairro) {
                $idSubBairro = $this->resolveSubBairro($location['sub_bairro'], $idBairro);
            }
        }

        $nomeTipo     = trim($data['tipo_de_imovel'] ?? $attrs['tipo_imovel'] ?? '');
        $idTipoImovel = $nomeTipo ? $this->resolveTipoImovel($nomeTipo) : null;

        $nomeModalidade = trim($data['modalidade_de_venda'] ?? '');
        if (!in_array($nomeModalidade, ['Venda Online', 'Venda Direta Online'], true)) {
            throw new \Exception("Modalidade de venda não permitida: \"{$nomeModalidade}\".");
        }
        $idModalidade   = $nomeModalidade ? $this->resolveModalidade($nomeModalidade) : null;

        if (!$idModalidade) {
            throw new \Exception("Modalidade de venda não reconhecida: \"{$nomeModalidade}\".");
        }

        $idImobiliaria = $idEstado ? $this->resolveImobiliaria($idEstado) : null;

        $idGrupo = $valorAvaliacao > 0 ? $this->resolveGrupo($valorAvaliacao) : null;

        // Bug fix: CSV não tem coluna "aceita_fgts" — a coluna real é "Financiamento"
        $aceitaFgts = $this->parseAceitaFgts($data['financiamento'] ?? '');

        // Dados textuais necessários para gerar SEO dentro da transação
        $nomeBairro = $location['bairro'];

        DB::transaction(function () use (
            $idCaixa, $preco, $valorAvaliacao, $desconto, $data, $attrs,
            $idEstado, $idMunicipio, $idBairro, $idSubBairro,
            $idTipoImovel, $idModalidade, $idImobiliaria, $idGrupo, $aceitaFgts,
            $nomeTipo, $nomeBairro, $nomeCidade, $uf, $link
        ) {
            // Campos fixos sempre atualizados
            $campos = [
                'endereco'           => $data['endereco'] ?? '',
                'cep'                => $data['cep'] ?? null,
                'descricao_original' => $data['descricao'] ?? '',
                'area_total'         => $attrs['area_total'] ?: null,
                'quartos'            => $attrs['quartos'] ?: null,
                'garagens'           => $attrs['vagas'] ?: null,
                // Bug fix: coluna CSV é "link_de_acesso", não "link_edital"
                'link_edital'        => $link ?: null,
                'foto_fachada_url'   => "https://venda-imoveis.caixa.gov.br/fotos/F" . str_pad($idCaixa, 13, '0', STR_PAD_LEFT) . "21.jpg",
                'aceita_fgts'        => $aceitaFgts,
                'status'             => 'ativo',
                // updated_at só avança — importações de CSVs antigos não regridem a data
                'updated_at'         => DB::raw("GREATEST(COALESCE(updated_at, '1970-01-01 00:00:00'), '{$this->dataGeracao}')"),
            ];

            // FKs opcionais: só atualiza se foram resolvidas (não sobrescreve valores existentes com null)
            $fks = array_filter([
                'id_imobiliaria' => $idImobiliaria,
                'id_tipo_imovel' => $idTipoImovel,
                'id_estado'      => $idEstado,
                'id_municipio'   => $idMunicipio,
                'id_bairro'      => $idBairro,
                'id_sub_bairro'  => $idSubBairro,
                'id_grupo'       => $idGrupo,
                'id_etapa'       => $this->etapaImportacaoId,
            ], fn($v) => $v !== null);

            $imovel = Imovel::updateOrCreate(
                ['numero_original' => $idCaixa],
                array_merge($campos, $fks)
            );

            // Gera slug e SEO apenas se ainda não existem (preserva URL em reimportações)
            if (!$imovel->slug) {
                $imovel->update($this->gerarDadosSeo(
                    $nomeTipo, $nomeBairro, $nomeCidade, $uf, $idCaixa, $preco, $desconto
                ));
            }

            // Registra no histórico: uma entrada por data de referência, somente se o preço difere
            $historicoNestaData = ImovelHistorico::where('id_imovel', $imovel->id)
                ->where('data_referencia', $this->dataGeracao)
                ->first();

            if (!$historicoNestaData) {
                $ultimoHistorico = ImovelHistorico::where('id_imovel', $imovel->id)
                    ->orderBy('data_referencia', 'desc')
                    ->first();

                if (!$ultimoHistorico || (float) $ultimoHistorico->valor_venda !== $preco) {
                    ImovelHistorico::create([
                        'id_imovel'           => $imovel->id,
                        'id_modalidade'       => $idModalidade,
                        'data_referencia'     => $this->dataGeracao,
                        'valor_avaliacao'     => $valorAvaliacao,
                        'valor_venda'         => $preco,
                        'desconto_percentual' => $desconto,
                        'desconto_valor'      => $valorAvaliacao - $preco,
                    ]);
                }
            }
        });
    }

    // -------------------------------------------------------------------------
    // Resolvers com cache em memória
    // -------------------------------------------------------------------------

    private function resolveEstado(string $uf): ?int
    {
        if (!array_key_exists($uf, $this->estadoCache)) {
            $nome = self::UF_NOMES[$uf] ?? null;

            if (!$nome) {
                Log::warning("CaixaCsvParser: UF desconhecida \"{$uf}\" — linha ignorada.");
                $this->estadoCache[$uf] = null;
            } else {
                $estado = Estado::firstOrCreate(['uf' => $uf], ['nome' => $nome]);
                $this->estadoCache[$uf] = $estado->id;
            }
        }
        return $this->estadoCache[$uf];
    }

    private const UF_NOMES = [
        'AC' => 'Acre',
        'AL' => 'Alagoas',
        'AM' => 'Amazonas',
        'AP' => 'Amapá',
        'BA' => 'Bahia',
        'CE' => 'Ceará',
        'DF' => 'Distrito Federal',
        'ES' => 'Espírito Santo',
        'GO' => 'Goiás',
        'MA' => 'Maranhão',
        'MG' => 'Minas Gerais',
        'MS' => 'Mato Grosso do Sul',
        'MT' => 'Mato Grosso',
        'PA' => 'Pará',
        'PB' => 'Paraíba',
        'PE' => 'Pernambuco',
        'PI' => 'Piauí',
        'PR' => 'Paraná',
        'RJ' => 'Rio de Janeiro',
        'RN' => 'Rio Grande do Norte',
        'RO' => 'Rondônia',
        'RR' => 'Roraima',
        'RS' => 'Rio Grande do Sul',
        'SC' => 'Santa Catarina',
        'SE' => 'Sergipe',
        'SP' => 'São Paulo',
        'TO' => 'Tocantins',
    ];

    private function resolveImobiliaria(int $idEstado): ?int
    {
        if (!array_key_exists($idEstado, $this->imobiliariaCache)) {
            $this->imobiliariaCache[$idEstado] = ImobiliariaEstado::where('id_estado', $idEstado)
                ->value('id_imobiliaria');
        }
        return $this->imobiliariaCache[$idEstado];
    }

    private function resolveMunicipio(string $nome, int $idEstado): int
    {
        $key = "{$idEstado}|{$nome}";
        if (!array_key_exists($key, $this->municipioCache)) {
            $municipio = Municipio::firstOrCreate(
                ['id_estado' => $idEstado, 'nome' => $nome]
            );
            $this->municipioCache[$key] = $municipio->id;
        }
        return $this->municipioCache[$key];
    }

    private function resolveBairro(string $nome, int $idMunicipio): int
    {
        $key = "{$idMunicipio}|{$nome}";
        if (!array_key_exists($key, $this->bairroCache)) {
            $bairro = Bairro::firstOrCreate(
                ['id_municipio' => $idMunicipio, 'nome' => $nome]
            );
            $this->bairroCache[$key] = $bairro->id;
        }
        return $this->bairroCache[$key];
    }

    private function resolveSubBairro(string $nome, int $idBairro): int
    {
        $key = "{$idBairro}|{$nome}";
        if (!array_key_exists($key, $this->subBairroCache)) {
            $sub = SubBairro::firstOrCreate(
                ['id_bairro' => $idBairro, 'nome' => $nome]
            );
            $this->subBairroCache[$key] = $sub->id;
        }
        return $this->subBairroCache[$key];
    }

    private function resolveTipoImovel(string $nome): int
    {
        if (!array_key_exists($nome, $this->tipoImovelCache)) {
            $tipo = TipoImovel::firstOrCreate(['nome' => $nome]);
            $this->tipoImovelCache[$nome] = $tipo->id;
        }
        return $this->tipoImovelCache[$nome];
    }

    private function resolveGrupo(float $valorAvaliacao): ?int
    {
        foreach ($this->grupos as $grupo) {
            if ($valorAvaliacao >= $grupo->valor_minimo && $valorAvaliacao <= $grupo->valor_maximo) {
                return $grupo->id;
            }
        }
        return null;
    }

    private function resolveModalidade(string $nome): ?int
    {
        if (!array_key_exists($nome, $this->modalidadeCache)) {
            $modalidade = ModalidadeVenda::firstOrCreate(
                ['nome' => $nome],
                ['ativo' => true]
            );
            $this->modalidadeCache[$nome] = $modalidade->id;
        }
        return $this->modalidadeCache[$nome];
    }

    // -------------------------------------------------------------------------
    // SEO
    // -------------------------------------------------------------------------

    private function gerarDadosSeo(
        string $tipo,
        string $bairro,
        string $cidade,
        string $uf,
        string $numero,
        float $valor,
        float $desconto
    ): array {
        $localSlug   = $this->slugify($bairro ?: $cidade);
        $cidadeSlug  = $this->slugify($cidade);
        $ufSlug      = strtolower($uf);
        $tipoSlug    = $this->slugify($tipo ?: 'imovel');
        $numeroSlug  = $this->slugify($numero);

        $slug = "{$tipoSlug}-{$localSlug}-{$cidadeSlug}-{$ufSlug}-{$numeroSlug}";

        $localLabel = implode(', ', array_filter([
            $bairro ? ucwords(mb_strtolower($bairro)) : null,
            ucwords(mb_strtolower($cidade)),
        ]));
        $tipoLabel = ucwords(mb_strtolower($tipo ?: 'Imóvel'));
        $ufLabel   = strtoupper($uf);

        $metaTitle = mb_substr(
            "{$tipoLabel} em {$localLabel} - {$ufLabel} | Antigravity Imóveis",
            0, 160
        );

        $valorFmt  = 'R$ ' . number_format($valor, 2, ',', '.');
        $descontoFmt = $desconto > 0 ? " com {$desconto}% de desconto" : '';
        $metaDesc  = mb_substr(
            "{$tipoLabel} disponível na Caixa Econômica Federal{$descontoFmt}. "
            . "{$valorFmt}. Localizado em {$localLabel}/{$ufLabel}.",
            0, 320
        );

        return [
            'slug'             => $slug,
            'meta_title'       => $metaTitle,
            'meta_description' => $metaDesc,
        ];
    }

    private function slugify(string $text): string
    {
        $text = mb_strtolower(trim($text), 'UTF-8');
        $text = preg_replace('/[àáâãä]/u', 'a', $text);
        $text = preg_replace('/[èéêë]/u', 'e', $text);
        $text = preg_replace('/[ìíîï]/u', 'i', $text);
        $text = preg_replace('/[òóôõö]/u', 'o', $text);
        $text = preg_replace('/[ùúûü]/u', 'u', $text);
        $text = preg_replace('/[ç]/u', 'c', $text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim($text, '-');
    }

    // -------------------------------------------------------------------------
    // Parsers
    // -------------------------------------------------------------------------

    private function extractDataGeracao(array|string $row): void
    {
        $cells = is_array($row) ? $row : [$row];
        foreach ($cells as $cell) {
            if (preg_match('/(\d{2}\/\d{2}\/\d{4})/', $cell, $matches)) {
                try {
                    $this->dataGeracao = Carbon::createFromFormat('d/m/Y', $matches[1])
                        ->startOfDay()
                        ->toDateTimeString();
                    return;
                } catch (\Exception) {
                }
            }
        }
        $this->dataGeracao = now()->toDateTimeString();
    }

    private function sanitizeHeaders(array $row): array
    {
        return array_map(function ($header) {
            $header = trim($header);
            $header = mb_strtolower($header, 'UTF-8');
            $header = str_replace(['n°', 'nº'], 'numero', $header);
            $header = str_replace(' ', '_', $header);
            $header = preg_replace('/[àáâãä]/u', 'a', $header);
            $header = preg_replace('/[èéêë]/u', 'e', $header);
            $header = preg_replace('/[ìíîï]/u', 'i', $header);
            $header = preg_replace('/[òóôõö]/u', 'o', $header);
            $header = preg_replace('/[ùúûü]/u', 'u', $header);
            $header = preg_replace('/[ç]/u', 'c', $header);
            $header = preg_replace('/[^a-z0-9_]/', '', $header);
            return trim($header, '_');
        }, $row);
    }

    private function parseDescription(string $desc): array
    {
        $attrs = ['quartos' => 0, 'vagas' => 0, 'area_total' => 0.0, 'tipo_imovel' => ''];

        if (preg_match('/^([^,]+)/u', $desc, $matches)) {
            $attrs['tipo_imovel'] = trim($matches[1]);
        }

        if (preg_match('/(\d+[.,]\d+)\s*m[²2]/u', $desc, $matches)) {
            $attrs['area_total'] = (float) str_replace(',', '.', $matches[1]);
        }

        if (preg_match('/(\d+)\s*quartos?/u', $desc, $matches)) {
            $attrs['quartos'] = (int) $matches[1];
        }

        if (preg_match('/(\d+)\s*vagas?\s*de\s*garagem/u', $desc, $matches)) {
            $attrs['vagas'] = (int) $matches[1];
        }

        return $attrs;
    }

    private function parseBairro(string $bairroStr): array
    {
        $bairro    = trim($bairroStr);
        $subBairro = null;

        if (preg_match('/^(.*?)\s*\((.*?)\)$/u', $bairroStr, $matches)) {
            $bairro    = trim($matches[1]);
            $subBairro = trim($matches[2]);
        }

        return ['bairro' => $bairro, 'sub_bairro' => $subBairro];
    }

    private function cleanMoney(string $value): float
    {
        return $this->cleanDecimal($value);
    }

    private function cleanDecimal(string $value): float
    {
        $value = trim($value);
        if ($value === '') {
            return 0.0;
        }

        // If there's a comma and a dot, e.g. 51.111,93
        if (strpos($value, ',') !== false && strpos($value, '.') !== false) {
            // Find which one is last
            if (strrpos($value, ',') > strrpos($value, '.')) {
                // Comma is decimal, dot is thousand separator
                return (float) str_replace(['.', ','], ['', '.'], $value);
            } else {
                // Dot is decimal, comma is thousand separator
                return (float) str_replace([',', '.'], ['', '.'], $value);
            }
        }

        // If there's only a comma, e.g. 70,46
        if (strpos($value, ',') !== false) {
            return (float) str_replace(',', '.', $value);
        }

        // If there's only a dot, e.g. 70.46
        return (float) $value;
    }

    private function parseAceitaFgts(string $valor): string
    {
        $valor = strtolower(trim($valor));
        if (in_array($valor, ['sim', 's', 'yes', '1'], true)) return 'sim';
        if (in_array($valor, ['não', 'nao', 'n', 'no', '0'], true)) return 'nao';
        return 'nao_informado';
    }

    private function extractHdnimovel(string $link): ?string
    {
        if (!$link) return null;
        preg_match('/hdnimovel=(\d+)/i', $link, $matches);
        return $matches[1] ?? null;
    }
}

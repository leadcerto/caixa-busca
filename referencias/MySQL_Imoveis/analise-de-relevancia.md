# 🚀 Documentação Completa e Diretrizes: Importação de Dados Imóveis Caixa — Laravel

> 💡 Este documento serve como instrução total para a equipe e a IA automatizarem a inclusão de imóveis extraídos do CSV da Caixa no banco relacional. O objetivo é realizar a importação de forma segura, mantendo relacionamentos, integridade estrutural e histórico de variações de valores (preços e descontos).

---

## ⚠️ 1. O QUE NÃO DEVE SER FEITO (Regras Críticas)

1. **NUNCA sobrescrever dados físicos do imóvel:** Se o imóvel já existir (`numero_original`), os dados como área, quartos e endereço não devem ser alterados pelo CSV (que costuma ter formatações inconsistentes). Use o CSV apenas para criar o imóvel pela primeira vez ou atualizar o **histórico de venda**.
2. **NUNCA inserir sem validar relacionamentos:** Nomes de Estados, Cidades, Bairros e Tipos de Imóveis devem ser buscados ou criados antes (usando `firstOrCreate`).
3. **NUNCA importar grandes volumes de forma síncrona:** O CSV da Caixa pode ter dezenas de milhares de linhas. O processamento deve ser feito em **Chunks (lotes)** rodando em **Background Jobs (Filas)**.
4. **NUNCA salvar valores monetários como string:** Campos como `Preço` e `Valor de avaliação` vêm no formato `R$ 158.053,95`. Eles devem obrigatoriamente ser convertidos para float/decimal no banco de dados.

---

## ✅ 2. Melhor Estrutura e Pré-Processamento

### Preparação do CSV
- Pular as linhas iniciais de cabeçalho inútil (geralmente as primeiras 2 a 4 linhas).
- Converter encoding de `ISO-8859-1` ou `Windows-1252` para `UTF-8` para evitar quebra de caracteres (acentos e cedilha).
- Fazer `trim()` em todas as strings para remover espaços inúteis gerados pelo sistema da Caixa.

### Ordem de Inserção (Integridade Referencial)
1. **Domínios (Tabelas de Apoio):** Estado ➔ Município ➔ Bairro ➔ Tipo de Imóvel ➔ Modalidade de Venda.
2. **Imóvel:** Inserir apenas se não existir.
3. **Histórico de Venda:** Sempre inserir um novo registro para guardar o preço do dia da extração.

---

## 🧩 3. Extração Inteligente (Regex para o campo "Descrição")

O campo "Descrição" do CSV da Caixa vem como um texto livre (ex: *"Casa, 2 quartos, 1 banheiro, sala, cozinha, área de serviço, 50m2 de área privativa, 100m2 de terreno"*). 

Devemos usar Expressões Regulares (Regex) para extrair os dados estruturados na hora de criar o imóvel:

```php
/**
 * Extrai dados estruturados a partir da descrição do imóvel da Caixa.
 */
function extrairDadosDescricao(string $descricao): array
{
    $dados = [
        'quartos' => 0,
        'banheiros' => 0,
        'vagas' => 0,
        'area_privativa' => null,
        'area_terreno' => null,
    ];

    // Extrair Quartos (ex: "2 quartos" ou "2 quarto")
    if (preg_match('/(\d+)\s*quarto(s)?/i', $descricao, $matches)) {
        $dados['quartos'] = (int) $matches[1];
    }

    // Extrair Banheiros (ex: "1 banheiro" ou "2 banheiros")
    if (preg_match('/(\d+)\s*banheiro(s)?/i', $descricao, $matches)) {
        $dados['banheiros'] = (int) $matches[1];
    }

    // Extrair Vagas de Garagem (ex: "1 vaga" ou "2 vagas")
    if (preg_match('/(\d+)\s*vaga(s)?/i', $descricao, $matches)) {
        $dados['vagas'] = (int) $matches[1];
    }

    // Extrair Área Privativa (ex: "50,45m2 de área privativa" ou "50.45 m2 area privativa")
    if (preg_match('/([\d\.,]+)\s*m2[^\d]*privativa/i', $descricao, $matches)) {
        $dados['area_privativa'] = moedaParaFloat($matches[1]);
    }

    // Extrair Área do Terreno (ex: "100m2 de área do terreno")
    if (preg_match('/([\d\.,]+)\s*m2[^\d]*terreno/i', $descricao, $matches)) {
        $dados['area_terreno'] = moedaParaFloat($matches[1]);
    }

    return $dados;
}



🛠️ 4. Código Completo: Job de Importação com Laravel Excel (Maatwebsite)
Abaixo está o esqueleto de como a classe de importação deve ser estruturada usando o pacote maatwebsite/excel.

Helper de Conversão de Moeda
Crie este helper globalmente ou dentro da classe:

php


function moedaParaFloat($valorStr) {
    if (empty($valorStr)) return 0.00;
    // Remove R$, espaços e converte pontuação do padrão BR para Float
    $limpo = preg_replace('/[^0-9,-]/', '', $valorStr);
    $limpo = str_replace(',', '.', $limpo);
    return (float) $limpo;
}
Classe de Importação (app/Imports/ImoveisCaixaImport.php)
php


namespace App\Imports;

use App\Models\{Estado, Municipio, Bairro, TipoImovel, ModalidadeVenda, Imovel, ImovelHistorico};
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImoveisCaixaImport implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue
{
    private $dataReferencia;

    public function __construct($dataReferencia)
    {
        $this->dataReferencia = $dataReferencia; // Data do arquivo original
    }

    public function model(array $row)
    {
        // Pula linhas vazias
        if (!isset($row['n_do_imovel']) || empty(trim($row['n_do_imovel']))) {
            return null;
        }

        return DB::transaction(function () use ($row) {
            // 1. Relacionamentos de Localidade
            $estado = Estado::firstOrCreate(['uf' => trim($row['uf'])]);
            
            $municipio = Municipio::firstOrCreate([
                'nome' => trim($row['cidade']), 
                'id_estado' => $estado->id
            ]);
            
            $bairro = Bairro::firstOrCreate([
                'nome' => trim($row['bairro']), 
                'id_municipio' => $municipio->id
            ]);

            // 2. Extração de Tipo de Imóvel e Modalidade
            $tipoImovelNome = explode(',', $row['descricao'])[0];
            $tipo = TipoImovel::firstOrCreate(['nome' => trim($tipoImovelNome)]);
            
            $modalidade = ModalidadeVenda::firstOrCreate(['nome' => trim($row['modalidade_de_venda'])]);

            // 3. Imóvel (Cria apenas se não existir)
            $imovel = Imovel::firstOrCreate(
                ['numero_original' => trim($row['n_do_imovel'])],
                [
                    'id_imobiliaria' => 1,
                    'id_tipo_imovel' => $tipo->id,
                    'id_estado' => $estado->id,
                    'id_municipio' => $municipio->id,
                    'id_bairro' => $bairro->id,
                    'id_etapa' => 1,
                    'endereco' => trim($row['endereco']),
                    'descricao_original' => trim($row['descricao']),
                    'status' => 'ativo',
                    // Populando com a função Regex
                    'quartos' => extrairDadosDescricao($row['descricao'])['quartos'],
                    'banheiros' => extrairDadosDescricao($row['descricao'])['banheiros'],
                    'area_privativa' => extrairDadosDescricao($row['descricao'])['area_privativa'],
                ]
            );

            // 4. Histórico Financeiro e de Venda (Sempre Insere)
            ImovelHistorico::create([
                'id_imovel' => $imovel->id,
                'id_modalidade' => $modalidade->id,
                'data_referencia' => $this->dataReferencia,
                'valor_avaliacao' => moedaParaFloat($row['valor_de_avaliacao']),
                'valor_venda' => moedaParaFloat($row['preco']),
                'desconto_percentual' => moedaParaFloat($row['desconto']),
                'desconto_valor' => moedaParaFloat($row['valor_de_avaliacao']) - moedaParaFloat($row['preco']),
                'aceita_financ_sbpe' => (trim($row['financiamento']) === 'Sim'),
            ]);

            return $imovel;
        });
    }

    // Processa de 500 em 500 linhas para não sobrecarregar a memória
    public function chunkSize(): int
    {
        return 500;
    }
}
📌 5. Checklist Final de Boas Práticas
 Filas (Queues): Garanta que o worker do Laravel (php artisan queue:work) esteja rodando no servidor, pois o ShouldQueue manda o processo para background.
 Cabeçalhos do CSV: O WithHeadingRow do pacote Laravel Excel transforma automaticamente cabeçalhos como "Nº do imóvel" em snake_case limpo: n_do_imovel. Verifique o dump de uma linha para mapear as chaves corretamente.
 Data de Referência: É vital passar a data do arquivo para o construtor da classe de importação, assim você consegue filtrar posteriormente no banco os imóveis que entraram num edital específico da Caixa.
 Log de Erros: Considere implementar a interface WithValidation e SkipsOnError do pacote Excel para que, se uma linha específica estiver corrompida, o arquivo inteiro não falhe, gerando apenas um log daquela linha.
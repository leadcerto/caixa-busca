# Especificação Funcional: Importação de CSV (Caixa Econômica Federal)

## 1. Contexto, Objetivo e Dor
**Dor:** O arquivo CSV disponibilizado pela Caixa Econômica Federal é fornecido em um formato bruto, com encoding legado (ISO-8859-1), metadados misturados às linhas de dados e uma coluna de "Descrição" que aglutina informações críticas (tipo, quartos, área) em um texto único. Essa estrutura impede a filtragem eficiente e a busca granular por parte dos investidores.

**Objetivo:** Automatizar a ingestão, limpeza e transformação desses dados brutos em registros relacionais padronizados. O foco é garantir que cada atributo técnico seja extraído e indexado, permitindo que a plataforma ofereça uma experiência de busca de alta performance.

## 2. Atores
- **Administrador do Sistema:** Responsável por carregar o arquivo CSV no painel administrativo para disparar a rotina de atualização.

## 3. Pré-condições
- O arquivo deve estar no formato CSV original da Caixa.
- O sistema deve processar o arquivo utilizando o encoding `ISO-8859-1`.
- O ambiente deve ter as tabelas de localidade (estados, municipios, bairros, sub_bairros) devidamente estruturadas no banco de dados MySQL.

## 4. O Caminho Feliz (Fluxo de Parsing)
O processo de importação deve seguir rigorosamente os passos abaixo:

1. **Captura de Metadados (Linha 1):** O sistema deve ler a primeira linha do arquivo para extrair a "Data de Geração" via Regex. 
   - **Regra de Negócio:** Este valor deve ser utilizado como a **Data de Atualização** (`updated_at`) para todos os imóveis processados (novos e existentes).
   - **Formatação:** Converter a data do formato PT-BR (ex: `12/05/2024`) para o formato ISO (`YYYY-MM-DD HH:MM:SS`) antes da persistência.
2. **Definição de Headers (Linha 2):** A segunda linha deve ser tratada como o cabeçalho. O sistema deve sanitizar os nomes das colunas:
   - Converter para minúsculas (lowercase).
   - Remover acentos e caracteres especiais.
   - Substituir espaços por underscores (`_`).
   - Converter o símbolo `Nº` para a palavra `numero`.
3. **Salto de Segurança (Linha 3):** O parser deve obrigatoriamente pular (skip) a terceira linha, que contém apenas separadores vazios.
4. **Processamento de Dados (A partir da Linha 4):**
   - **Descarte:** A coluna original "Link de acesso" deve ser ignorada.
   - **Extração Cirúrgica (Parse de Descrição):** O campo "Descrição" deve ser processado via Regex/PHP para extrair os seguintes campos indexáveis:
     - Tipo de Imóvel (ex: Casa, Apartamento, Terreno).
     - Quantidade de Quartos.
     - Vagas de Garagem.
     - Área Útil/Privativa.
   - **Preservação:** A descrição original deve ser mantida em uma coluna específica para exibição completa.
5. **Tratamento de Localidade (Bairro/Sub-bairro):** O sistema deve processar o campo `bairro` do CSV para identificar sub-bairros embutidos:
   - **Padrão com Parênteses:** Se o campo contiver parênteses (ex: `Bairro Exemplo (Sub-bairro Exemplo)`), o conteúdo externo deve ser salvo como `bairro` e o conteúdo interno como `sub_bairro`.
   - **Padrão Simples:** Se não houver parênteses, o campo é salvo integralmente como `bairro`.
   - **Sanitização:** Aplicar `trim()` em ambos os campos para evitar espaços residuais.
6. **Deduplicação e Vínculo:** O sistema deve utilizar o "ID do Imóvel" (Número original da Caixa) como chave única para evitar duplicidade.
   - **Atualização:** Se o imóvel já existir, os campos devem ser atualizados e o `updated_at` deve obrigatoriamente ser a "Data de Geração" capturada no Passo 1.

## 5. A Matriz do Caos (Caminhos Tristes)

| Cenário de Falha | Ação do Sistema | Resultado Esperado |
| :--- | :--- | :--- |
| Campos Essenciais Nulos (ID ou Preço) | Ignorar o registro individual. | Log de erro detalhado em `error.log` com a linha do CSV. |
| Imóvel já existente na base | Comparar dados e atualizar o registro. | Registro único mantido, sem duplicação de IDs. |
| Mudança de Header no CSV | Validar a existência de colunas obrigatórias. | Interromper a importação e alertar o administrador. |
| Encoding Divergente | Validar a integridade dos caracteres na leitura inicial. | Logar erro de leitura e não corromper os dados no banco. |

## 6. Pós-condições
- **Banco de Dados:** A tabela `imoveis` deve conter os novos registros com os campos extraídos da descrição devidamente preenchidos e indexados.
- **Histórico:** Caso o preço de um imóvel existente tenha mudado, um novo registro deve ser criado na tabela de histórico de preços.
- **Localidade:** Novos municípios, bairros ou sub-bairros encontrados no CSV devem ser criados e vinculados respeitando a integridade referencial.
- **Finalização:** O sistema deve gerar um resumo da operação (Total processado, sucessos e falhas).

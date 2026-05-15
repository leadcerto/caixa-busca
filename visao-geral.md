# 📋 VISÃO GERAL DO SISTEMA

## 🎯 Objetivo do Sistema

Plataforma web para **captação, organização e distribuição de leads** interessados em
imóveis com desconto acima de 30%, disponíveis para venda direta.

O sistema conecta **compradores em potencial** (leads) às **imobiliárias parceiras**
responsáveis pelo atendimento, sem que as imobiliárias precisem operar ativamente
dentro da plataforma.

---

## 👥 PERFIS DE USUÁRIO

### 🔧 Administrador
- Usuários internos (2 a 3 pessoas)
- Acesso total ao sistema
- Gerencia imóveis, leads, imobiliárias e configurações gerais

### 🏢 Imobiliária
- Uma imobiliária parceira por estado (até 27 no total)
- Se cadastra na plataforma com login e senha
- Acesso a um **painel simples de visualização** (somente leitura)
- Visualiza os atendimentos recebidos: data, nome, e-mail, imóvel de interesse
- Pode copiar as informações para dar continuidade no atendimento
- **Recebe automaticamente** cada lead por **e-mail e WhatsApp**
- Não insere, não edita, não realiza nenhuma ação no sistema

### 👤 Lead / Visitante (Comprador)
- Se cadastra ao preencher o formulário de interesse
- Recebe **e-mail de confirmação** para validar o cadastro
- Após confirmado, tem acesso a **áreas restritas do site**
- Não tem painel, não edita nada, acesso somente leitura ao conteúdo

---

## 🏠 IMÓVEIS

- Os imóveis são importados via **arquivo CSV** inserido manualmente pelo administrador
- A frequência de importação não é fixa — pode ocorrer mais de uma vez por semana
  ou nenhuma vez, conforme a disponibilidade do arquivo
- O sistema processa o arquivo e atualiza a base de dados com os imóveis disponíveis
- Cada imóvel contém informações como: endereço, cidade, estado, valor, modalidade
  de venda, tipo de imóvel, link do edital, entre outros
- Os imóveis são **vinculados a uma imobiliária parceira** responsável pelo atendimento
  do estado correspondente

### 🔒 Regras de Importação
- Só são importados imóveis com **desconto acima de 30%**
- Só são importados imóveis com as seguintes modalidades de venda:
  - **Venda Direta Online**
  - **Venda Direta**
- Imóveis com qualquer outra modalidade são descartados na importação

### 🔄 Status do Imóvel
- Um imóvel pode sair da lista sem ter sido vendido e retornar futuramente
- Os status possíveis são:
  - **Ativo** — consta na lista atual
  - **Fora de venda** — saiu da lista, situação não confirmada
  - **Vendido** — confirmado como vendido
  - **Suspenso** — venda suspensa ou cancelada

### 🔢 Dados Fixos vs. Dados Variáveis

**Dados fixos** (não mudam após a importação inicial):
- Número original do imóvel no CSV (chave principal — único)
- Endereço completo (logradouro, bairro, cidade, estado)
- Descrição original do arquivo
- Tipo do imóvel

**Dados variáveis** (podem mudar a cada nova importação — geram histórico):
- Preço de venda
- Valor de avaliação
- Desconto percentual
- Desconto em reais (calculado pelo sistema)
- Modalidade de venda
- Aceite de financiamento SBPE
- Aceite de financiamento MCMV (campo reservado para uso futuro)
- Aceite de FGTS (obtido via scraping — valor inicial: "não informado")
- Data de referência (data de geração do CSV)

---

## 🔁 ETAPAS DE PROCESSAMENTO DO IMÓVEL

Cada imóvel passa por etapas sequenciais de processamento após a importação:

1. **Importação** — dados brutos inseridos no banco a partir do CSV
2. **Processamento** — sistema organiza e interpreta os dados
3. **Geração de links** — criação do link da imagem e do link do edital
4. **Desmembramento da descrição** — extração via PHP dos campos individuais
   contidos no texto original (tipo, área, quartos, características)
5. **Scraping** — coleta de informações complementares no site de origem
   (FGTS, dados adicionais)
6. **Geração de SEO** — criação de títulos, slugs e meta descriptions
7. **Cálculos financeiros** — desconto em reais, enquadramento em grupo, simulações

---

## 📐 DESMEMBRAMENTO DA DESCRIÇÃO

O campo de descrição original contém múltiplas informações em texto livre. O sistema
extrai automaticamente via PHP os seguintes campos:

| Campo extraído      | Exemplo                                   |
|---------------------|-------------------------------------------|
| Tipo do imóvel      | Casa, Apartamento, Terreno, Sobrado, Prédio |
| Área total          | 69,03 m²                                  |
| Área privativa      | 69,03 m²                                  |
| Área do terreno     | 99,79 m²                                  |
| Quartos             | 3                                         |
| Banheiros / WC      | 1                                         |
| Salas               | 1                                         |
| Vagas de garagem    | 0                                         |
| Varanda             | Sim / Não                                 |
| Área de serviço     | Sim / Não                                 |
| Cozinha             | Sim / Não                                 |
| Piscina             | Sim / Não                                 |
| Churrasqueira       | Sim / Não                                 |
| Terraço             | Sim / Não                                 |

---

## 📊 GRUPOS DE IMÓVEIS

- Os imóveis são classificados em grupos com base no **valor de avaliação**
- Cada grupo possui percentuais e valores fixos utilizados em cálculos financeiros
- Os grupos são preenchidos manualmente pelo administrador
- As alterações são raras — ocorrem principalmente na fase inicial do sistema

---

## 📍 HIERARQUIA DE LOCALIZAÇÃO

A localização é estruturada em níveis relacionais para evitar duplicação e permitir
enriquecimento de conteúdo:

- **Estado (UF)**
- **Município**
- **Bairro**
- **Sub-bairro (quando aplicável)**
- **CEP**

Ao importar um imóvel, o sistema verifica se o estado, município, bairro e sub-bairro (se houver) já existem na base antes de criar um novo registro.

### 🌍 Enriquecimento por Município e Bairro

Cada município e bairro pode ter seu conteúdo enriquecido por Inteligência Artificial,
armazenado em um campo JSON. O objetivo é fornecer informações relevantes para
compradores que não conhecem a região.

**Conteúdo gerado para municípios:**
- Apresentação geral
- Data de fundação, população, área
- Principais atividades econômicas
- Turismo e pontos turísticos
- Infraestrutura (aeroportos, hospitais de referência)

**Conteúdo gerado para bairros:**
- Educação (escolas, universidades próximas)
- Transporte (linhas de ônibus, metrô, trem, estações próximas)
- Saúde (hospitais, UBS, clínicas)
- Comércio (shoppings, supermercados, bancos)
- Pontos de referência (distância a aeroportos, rodoviárias, pontos turísticos)
- Principais avenidas e vias de acesso

---

## 🔍 BUSCA DE IMÓVEIS

- O site é aberto — qualquer visitante pode realizar buscas sem login
- Filtros disponíveis:
  - Estado (UF)
  - Município
  - Bairro
  - Tipo de imóvel
  - Faixa de valor
  - Modalidade de venda
- Os resultados podem ser **compartilhados via link**
- O link compartilhado gera uma **pré-visualização** no WhatsApp com a imagem
  de destaque configurada

---

## 📬 GERAÇÃO DE LEAD

- Ao demonstrar interesse em um imóvel, o visitante preenche um formulário
- Esse formulário gera um **atendimento vinculado ao imóvel e à imobiliária
  responsável pelo estado**
- A imobiliária é **notificada automaticamente** por e-mail e WhatsApp
- Se o mesmo lead se interessar por múltiplos imóveis, a imobiliária recebe uma
  notificação separada para cada imóvel
- O histórico de imóveis de interesse do lead fica **gravado em JSON** dentro
  do registro do lead:

```json
{
  "imoveis_interesse": [
    { "numero": "1042", "data": "2026-04-10", "modalidade": "Venda Direta Online" },
    { "numero": "3891", "data": "2026-04-15", "modalidade": "Venda Direta" }
  ]
}
```

---

## 🖼️ IMAGENS DO SISTEMA

O sistema trabalha com um volume reduzido de imagens:

- **Foto da fachada** — imagem real do imóvel, tirada da rua. Hospedada em servidores
  externos. O sistema armazena apenas a URL que aponta para ela.
- **Imagem de destaque (Open Graph)** — imagem institucional genérica, hospedada no
  servidor próprio da plataforma. Exibida automaticamente quando qualquer link do site
  é compartilhado no WhatsApp ou redes sociais.

---

*Documento gerado em: maio de 2026 — Versão: 2.0*

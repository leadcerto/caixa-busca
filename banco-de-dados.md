# 🗄️ BANCO DE DADOS — Estrutura Técnica Completa

## 📁 Arquivo: `docs/banco-de-dados.md`

---

## 📐 VISÃO GERAL DAS TABELAS

| Tabela                  | Descrição                                                    |
|-------------------------|--------------------------------------------------------------|
| `usuarios`              | Administradores do sistema                                   |
| `imobiliarias`          | Imobiliárias parceiras cadastradas                           |
| `leads`                 | Compradores/visitantes cadastrados                           |
| `imoveis`               | Imóveis importados via CSV                                   |
| `imoveis_historico`     | Histórico de dados variáveis a cada importação               |
| `imoveis_grupos`        | Grupos de classificação por faixa de valor de avaliação      |
| `imoveis_etapas`        | Etapas de processamento de cada imóvel                       |
| `tipos_imovel`          | Tipos de imóvel (casa, apartamento, terreno, etc.)           |
| `modalidades_venda`     | Modalidades de venda aceitas pelo sistema                    |
| `estados`               | Estados brasileiros (UF)                                     |
| `municipios`            | Municípios vinculados aos estados                            |
| `bairros`               | Bairros vinculados aos municípios                            |
| `sub_bairros`           | Sub-bairros vinculados aos bairros                           |
| `imobiliarias_estados`  | Vínculo entre imobiliária e estado atendido                  |
| `atendimentos`          | Registro de atendimentos gerados por leads                   |
| `atendimentos_origem`   | Origens possíveis de um atendimento                          |

---

## 🔧 TABELA: `usuarios`

> Administradores com acesso total ao sistema.

| Campo        | Tipo         | Obrigatório | Descrição                   |
|--------------|--------------|-------------|-----------------------------|
| `id`         | INT (PK, AI) | ✅          | Identificador único         |
| `nome`       | VARCHAR(100) | ✅          | Nome completo               |
| `email`      | VARCHAR(150) | ✅          | E-mail de acesso (único)    |
| `senha`      | VARCHAR(255) | ✅          | Senha criptografada (hash)  |
| `ativo`      | BOOLEAN      | ✅          | Se o usuário está ativo     |
| `created_at` | TIMESTAMP    | ✅          | Data de criação do registro |
| `updated_at` | TIMESTAMP    | ✅          | Data da última atualização  |

---

## 🏢 TABELA: `imobiliarias`

> Imobiliárias parceiras que recebem os leads e têm painel de visualização.

| Campo        | Tipo         | Obrigatório | Descrição                              |
|--------------|--------------|-------------|----------------------------------------|
| `id`         | INT (PK, AI) | ✅          | Identificador único                    |
| `nome`       | VARCHAR(150) | ✅          | Nome da imobiliária                    |
| `email`      | VARCHAR(150) | ✅          | E-mail de acesso e recebimento de lead |
| `senha`      | VARCHAR(255) | ✅          | Senha criptografada (hash)             |
| `whatsapp`   | VARCHAR(20)  | ✅          | Número para recebimento via WhatsApp   |
| `creci`      | VARCHAR(30)  | ⬜          | Número do CRECI                        |
| `ativo`      | BOOLEAN      | ✅          | Se a imobiliária está ativa            |
| `created_at` | TIMESTAMP    | ✅          | Data de cadastro                       |
| `updated_at` | TIMESTAMP    | ✅          | Data da última atualização             |

---

## 👤 TABELA: `leads`

> Compradores/visitantes que se cadastraram na plataforma.

| Campo               | Tipo         | Obrigatório | Descrição                                 |
|---------------------|--------------|-------------|-------------------------------------------|
| `id`                | INT (PK, AI) | ✅          | Identificador único                       |
| `nome`              | VARCHAR(100) | ✅          | Nome completo                             |
| `email`             | VARCHAR(150) | ✅          | E-mail (único)                            |
| `telefone`          | VARCHAR(20)  | ⬜          | Telefone/WhatsApp                         |
| `senha`             | VARCHAR(255) | ✅          | Senha criptografada (hash)                |
| `email_confirmado`  | BOOLEAN      | ✅          | Se confirmou o e-mail (default: false)    |
| `token_confirmacao` | VARCHAR(255) | ⬜          | Token enviado por e-mail para validação   |
| `imoveis_interesse` | JSON         | ⬜          | Histórico de imóveis de interesse do lead |
| `ativo`             | BOOLEAN      | ✅          | Se o lead está ativo                      |
| `created_at`        | TIMESTAMP    | ✅          | Data de cadastro                          |
| `updated_at`        | TIMESTAMP    | ✅          | Data da última atualização                |

### 📌 Exemplo do campo `imoveis_interesse` (JSON)

```json
{
  "imoveis_interesse": [
    { "numero": "1042", "data": "2026-04-10", "modalidade": "Venda Direta Online" },
    { "numero": "3891", "data": "2026-04-15", "modalidade": "Venda Direta" }
  ]
}
```

---

## 🏠 TABELA: `imoveis`

> Imóveis importados via CSV. Campos originais preservados integralmente e nunca alterados.

| Campo                 | Tipo          | Obrigatório | Descrição                                                         |
|-----------------------|---------------|-------------|-------------------------------------------------------------------|
| `id`                  | INT (PK, AI)  | ✅          | Identificador único interno                                       |
| `numero_original`     | VARCHAR(50)   | ✅          | Número original do imóvel no CSV (único, chave principal)         |
| `id_imobiliaria`      | INT (FK)      | ✅          | Imobiliária responsável pelo estado do imóvel                     |
| `id_tipo_imovel`      | INT (FK)      | ✅          | Tipo do imóvel → `tipos_imovel.id`                                |
| `id_estado`           | INT (FK)      | ✅          | Estado do imóvel → `estados.id`                                   |
| `id_municipio`        | INT (FK)      | ✅          | Município do imóvel → `municipios.id`                             |
| `id_bairro`           | INT (FK)      | ⬜          | Bairro do imóvel → `bairros.id`                                   |
| `id_sub_bairro`       | INT (FK)      | ⬜          | Sub-bairro do imóvel → `sub_bairros.id` (opcional)                |
| `id_grupo`            | INT (FK)      | ⬜          | Grupo de classificação → `imoveis_grupos.id`                      |
| `id_etapa`            | INT (FK)      | ✅          | Etapa de processamento atual → `imoveis_etapas.id`                |
| `endereco`            | VARCHAR(255)  | ✅          | Logradouro original do CSV (dado fixo)                            |
| `cep`                 | VARCHAR(10)   | ⬜          | CEP (dado fixo)                                                   |
| `descricao_original`  | TEXT          | ✅          | Descrição original do CSV (preservada, nunca alterada)            |
| `area_total`          | DECIMAL(10,2) | ⬜          | Área total em m² (extraída da descrição via PHP)                  |
| `area_privativa`      | DECIMAL(10,2) | ⬜          | Área privativa em m² (extraída da descrição via PHP)              |
| `area_terreno`        | DECIMAL(10,2) | ⬜          | Área do terreno em m² (extraída da descrição via PHP)             |
| `quartos`             | TINYINT       | ⬜          | Número de quartos (extraído da descrição via PHP)                 |
| `banheiros`           | TINYINT       | ⬜          | Número de banheiros (extraído da descrição via PHP)               |
| `salas`               | TINYINT       | ⬜          | Número de salas (extraído da descrição via PHP)                   |
| `garagens`            | TINYINT       | ⬜          | Número de vagas de garagem (extraído da descrição via PHP)        |
| `varanda`             | BOOLEAN       | ⬜          | Possui varanda (extraído da descrição via PHP)                    |
| `area_servico`        | BOOLEAN       | ⬜          | Possui área de serviço (extraído da descrição via PHP)            |
| `cozinha`             | BOOLEAN       | ⬜          | Possui cozinha (extraído da descrição via PHP)                    |
| `piscina`             | BOOLEAN       | ⬜          | Possui piscina (extraído da descrição via PHP)                    |
| `churrasqueira`       | BOOLEAN       | ⬜          | Possui churrasqueira (extraído da descrição via PHP)              |
| `terraco`             | BOOLEAN       | ⬜          | Possui terraço (extraído da descrição via PHP)                    |
| `foto_fachada_url`    | VARCHAR(500)  | ⬜          | URL da foto da fachada (servidor externo)                         |
| `imagem_destaque_url` | VARCHAR(500)  | ⬜          | URL da imagem Open Graph para compartilhamento (servidor próprio) |
| `link_edital`         | VARCHAR(500)  | ⬜          | Link do edital oficial                                            |
| `aceita_fgts`         | ENUM          | ✅          | `nao_informado` / `sim` / `nao` — default: `nao_informado`        |
| `aceita_financ_sbpe`  | BOOLEAN       | ⬜          | Aceita financiamento SBPE                                         |
| `aceita_financ_mcmv`  | BOOLEAN       | ⬜          | Aceita financiamento MCMV (reservado para uso futuro)             |
| `status`              | ENUM          | ✅          | `ativo` / `fora_de_venda` / `vendido` / `suspenso`                |
| `slug`                | VARCHAR(255)  | ⬜          | Slug para URL amigável (gerado na etapa de SEO)                   |
| `meta_title`          | VARCHAR(160)  | ⬜          | Título SEO (gerado na etapa de SEO)                               |
| `meta_description`    | VARCHAR(320)  | ⬜          | Meta description SEO (gerado na etapa de SEO)                     |
| `created_at`          | TIMESTAMP     | ✅          | Data de inserção no sistema                                       |
| `updated_at`          | TIMESTAMP     | ✅          | Data da última atualização                                        |

---

## 🔄 TABELA: `imoveis_historico`

> Registra os dados variáveis de cada imóvel a cada nova importação do CSV, permitindo análise de evolução de preço e tempo de mercado.

| Campo                 | Tipo          | Obrigatório | Descrição                                                      |
|-----------------------|---------------|-------------|----------------------------------------------------------------|
| `id`                  | INT (PK, AI)  | ✅          | Identificador único                                            |
| `id_imovel`           | INT (FK)      | ✅          | Imóvel referenciado → `imoveis.id`                             |
| `id_modalidade`       | INT (FK)      | ✅          | Modalidade de venda vigente → `modalidades_venda.id`           |
| `data_referencia`     | DATE          | ✅          | Data de geração do CSV (não a data de importação)              |
| `valor_avaliacao`     | DECIMAL(15,2) | ✅          | Valor de avaliação do imóvel                                   |
| `valor_venda`         | DECIMAL(15,2) | ✅          | Valor de venda                                                 |
| `desconto_percentual` | DECIMAL(5,2)  | ✅          | Percentual de desconto (vem do CSV)                            |
| `desconto_valor`      | DECIMAL(15,2) | ✅          | Desconto em reais — calculado: `valor_avaliacao - valor_venda` |
| `aceita_financ_sbpe`  | BOOLEAN       | ⬜          | Aceita SBPE nesta atualização                                  |
| `aceita_financ_mcmv`  | BOOLEAN       | ⬜          | Aceita MCMV nesta atualização (uso futuro)                     |
| `created_at`          | TIMESTAMP     | ✅          | Data de registro desta entrada                                 |

---

## 📊 TABELA: `imoveis_grupos`

> Grupos de classificação por faixa de valor de avaliação, com parâmetros para cálculos financeiros. Preenchidos manualmente pelo administrador.

| Campo          | Tipo          | Obrigatório | Descrição                                         |
|----------------|---------------|-------------|---------------------------------------------------|
| `id`           | INT (PK, AI)  | ✅          | Identificador único                               |
| `nome`         | VARCHAR(100)  | ✅          | Nome do grupo                                     |
| `valor_minimo` | DECIMAL(15,2) | ✅          | Valor mínimo de avaliação para enquadramento      |
| `valor_maximo` | DECIMAL(15,2) | ✅          | Valor máximo de avaliação para enquadramento      |
| `percentual_1` | DECIMAL(5,2)  | ⬜          | Percentual configurável para cálculos financeiros |
| `percentual_2` | DECIMAL(5,2)  | ⬜          | Percentual configurável para cálculos financeiros |
| `valor_fixo_1` | DECIMAL(15,2) | ⬜          | Valor fixo configurável para cálculos financeiros |
| `valor_fixo_2` | DECIMAL(15,2) | ⬜          | Valor fixo configurável para cálculos financeiros |
| `ativo`        | BOOLEAN       | ✅          | Se o grupo está ativo                             |
| `created_at`   | TIMESTAMP     | ✅          | Data de criação                                   |
| `updated_at`   | TIMESTAMP     | ✅          | Data da última atualização                        |

---

## 🔁 TABELA: `imoveis_etapas`

> Etapas de processamento pelas quais cada imóvel passa após a importação.

| Campo       | Tipo         | Obrigatório | Descrição                        |
|-------------|--------------|-------------|----------------------------------|
| `id`        | INT (PK, AI) | ✅          | Identificador único              |
| `nome`      | VARCHAR(100) | ✅          | Nome da etapa                    |
| `descricao` | TEXT         | ⬜          | Descrição do que ocorre na etapa |
| `ordem`     | TINYINT      | ✅          | Ordem sequencial de execução     |
| `ativo`     | BOOLEAN      | ✅          | Se a etapa está ativa            |

### 📌 Etapas previstas (em ordem)

| Ordem | Nome                        |
|-------|-----------------------------|
| 1     | Importação                  |
| 2     | Processamento               |
| 3     | Geração de links            |
| 4     | Desmembramento da descrição |
| 5     | Scraping                    |
| 6     | Geração de SEO              |
| 7     | Cálculos financeiros        |

---

## 🏷️ TABELA: `tipos_imovel`

> Tipos de imóvel extraídos da descrição original via PHP.

| Campo   | Tipo         | Obrigatório | Descrição           |
|---------|--------------|-------------|---------------------|
| `id`    | INT (PK, AI) | ✅          | Identificador único |
| `nome`  | VARCHAR(80)  | ✅          | Nome do tipo        |
| `ativo` | BOOLEAN      | ✅          | Se está ativo       |

### 📌 Tipos previstos

- Casa
- Apartamento
- Terreno
- Sobrado
- Prédio

---

## 💰 TABELA: `modalidades_venda`

> Modalidades de venda aceitas para importação no sistema.

| Campo   | Tipo         | Obrigatório | Descrição           |
|---------|--------------|-------------|---------------------|
| `id`    | INT (PK, AI) | ✅          | Identificador único |
| `nome`  | VARCHAR(100) | ✅          | Nome da modalidade  |
| `ativo` | BOOLEAN      | ✅          | Se está ativa       |

### 📌 Modalidades aceitas

- Venda Direta
- Venda Direta Online

---

## 🗺️ TABELA: `estados`

> Estados brasileiros.

| Campo   | Tipo         | Obrigatório | Descrição           |
|---------|--------------|-------------|---------------------|
| `id`    | INT (PK, AI) | ✅          | Identificador único |
| `nome`  | VARCHAR(50)  | ✅          | Nome do estado      |
| `uf`    | CHAR(2)      | ✅          | Sigla do estado     |

---

## 🏙️ TABELA: `municipios`

> Municípios vinculados aos estados, com suporte a enriquecimento por IA.

| Campo          | Tipo         | Obrigatório | Descrição                                                        |
|----------------|--------------|-------------|------------------------------------------------------------------|
| `id`           | INT (PK, AI) | ✅          | Identificador único                                              |
| `id_estado`    | INT (FK)     | ✅          | Estado ao qual pertence → `estados.id`                           |
| `nome`         | VARCHAR(150) | ✅          | Nome do município                                                |
| `conteudo_ia`  | JSON         | ⬜          | Conteúdo enriquecido pela IA (economia, turismo, infraestrutura) |
| `ia_status`    | ENUM         | ✅          | `pendente` / `gerado` / `erro` — default: `pendente`             |
| `ia_gerado_em` | DATETIME     | ⬜          | Data em que o conteúdo IA foi gerado                             |
| `created_at`   | TIMESTAMP    | ✅          | Data de criação                                                  |
| `updated_at`   | TIMESTAMP    | ✅          | Data da última atualização                                       |

### 📌 Exemplo do campo `conteudo_ia` — Municípios (JSON)

```json
{
  "apresentacao": "Município localizado no interior de...",
  "dados_gerais": {
    "data_fundacao": "15 de novembro de 1890",
    "populacao": "1.200.000 habitantes",
    "area_km2": "4.557 km²"
  },
  "economia": {
    "principais_atividades": ["Indústria", "Turismo", "Agronegócio"]
  },
  "turismo": {
    "pontos_turisticos": ["Ponto A", "Ponto B"]
  },
  "infraestrutura": {
    "aeroportos": ["Aeroporto Regional X"],
    "hospitais_referencia": ["Hospital Regional Y"]
  }
}
```

---

## 🏘️ TABELA: `bairros`

> Bairros vinculados aos municípios, com suporte a enriquecimento por IA.

| Campo          | Tipo          | Obrigatório | Descrição                                                            |
|----------------|---------------|-------------|----------------------------------------------------------------------|
| `id`           | INT (PK, AI)  | ✅          | Identificador único                                                  |
| `id_municipio` | INT (FK)      | ✅          | Município ao qual pertence → `municipios.id`                         |
| `nome`         | VARCHAR(150)  | ✅          | Nome do bairro                                                       |
| `latitude`     | DECIMAL(10,7) | ⬜          | Latitude geográfica                                                  |
| `longitude`    | DECIMAL(10,7) | ⬜          | Longitude geográfica                                                 |
| `conteudo_ia`  | JSON          | ⬜          | Conteúdo enriquecido pela IA (educação, transporte, saúde, comércio) |
| `ia_status`    | ENUM          | ✅          | `pendente` / `gerado` / `erro` — default: `pendente`                 |
| `ia_gerado_em` | DATETIME      | ⬜          | Data em que o conteúdo IA foi gerado                                 |
| `created_at`   | TIMESTAMP     | ✅          | Data de criação                                                      |
| `updated_at`   | TIMESTAMP     | ✅          | Data da última atualização                                           |
|
---

## 🏘️ TABELA: `sub_bairros`

> Sub-bairros vinculados aos bairros.

| Campo        | Tipo         | Obrigatório | Descrição                   |
|--------------|--------------|-------------|-----------------------------|
| `id`         | INT (PK, AI) | ✅          | Identificador único         |
| `id_bairro`  | INT (FK)     | ✅          | Bairro ao qual pertence → `bairros.id` |
| `nome`       | VARCHAR(150) | ✅          | Nome do sub-bairro          |
| `created_at` | TIMESTAMP    | ✅          | Data de criação             |
| `updated_at` | TIMESTAMP    | ✅          | Data da última atualização  |

### 📌 Exemplo do campo `conteudo_ia` — Bairros (JSON)

```json
{
  "educacao": {
    "escolas": ["Escola Municipal X"],
    "universidades_proximas": ["UERJ - 3,2km"]
  },
  "transporte": {
    "onibus": ["474", "SV-01"],
    "metro": false,
    "trem": true,
    "estacao_proxima": "Estação Vista Alegre - 400m"
  },
  "saude": {
    "hospitais": ["UPA Vista Alegre"],
    "ubs": ["Clínica da Família Y"]
  },
  "comercio": {
    "shoppings": ["Shopping Nova América - 2,1km"],
    "supermercados": ["Extra", "Guanabara"]
  },
  "pontos_referencia": {
    "aeroporto": "Aeroporto do Galeão - 8,5km",
    "rodoviaria": "Rodoviária Novo Rio - 12km"
  },
  "avenidas_principais": ["Avenida Brasil", "Estrada Intendente Magalhães"]
}
```

---

## 🔗 TABELA: `imobiliarias_estados`

> Define qual imobiliária é responsável por cada estado. Uma imobiliária por estado.

| Campo            | Tipo         | Obrigatório | Descrição               |
|------------------|--------------|-------------|-------------------------|
| `id`             | INT (PK, AI) | ✅          | Identificador único     |
| `id_imobiliaria` | INT (FK)     | ✅          | Imobiliária responsável |
| `id_estado`      | INT (FK)     | ✅          | Estado atendido         |
| `created_at`     | TIMESTAMP    | ✅          | Data do vínculo         |

---

## 📣 TABELA: `atendimentos_origem`

> Origens possíveis de um atendimento gerado por lead.

| Campo   | Tipo         | Obrigatório | Descrição                     |
|---------|--------------|-------------|-------------------------------|
| `id`    | INT (PK, AI) | ✅          | Identificador único           |
| `nome`  | VARCHAR(100) | ✅          | Nome da origem do atendimento |
| `ativo` | BOOLEAN      | ✅          | Se está ativa                 |

### 📌 Origens previstas

- Formulário do site
- WhatsApp do anúncio
- WhatsApp do site
- E-mail
- Blog

---

## 📋 TABELA: `atendimentos`

> Registro de cada atendimento gerado por um lead interessado em um imóvel. A imobiliária responsável pelo estado do imóvel é notificada automaticamente por e-mail e WhatsApp.

| Campo              | Tipo         | Obrigatório | Descrição                                        |
|--------------------|--------------|-------------|--------------------------------------------------|
| `id`               | INT (PK, AI) | ✅          | Identificador único                              |
| `id_lead`          | INT (FK)     | ✅          | Lead que gerou o atendimento → `leads.id`        |
| `id_imovel`        | INT (FK)     | ✅          | Imóvel de interesse → `imoveis.id`               |
| `id_imobiliaria`   | INT (FK)     | ✅          | Imobiliária notificada → `imobiliarias.id`       |
| `id_origem`        | INT (FK)     | ✅          | Origem do atendimento → `atendimentos_origem.id` |
| `mensagem`         | TEXT         | ⬜          | Mensagem enviada pelo lead no formulário         |
| `email_enviado`    | BOOLEAN      | ✅          | Se a notificação por e-mail foi enviada          |
| `whatsapp_enviado` | BOOLEAN      | ✅          | Se a notificação por WhatsApp foi enviada        |
| `created_at`       | TIMESTAMP    | ✅          | Data e hora do atendimento                       |

---

## 🔑 RELACIONAMENTOS (Chaves Estrangeiras)

```
imoveis.id_imobiliaria          → imobiliarias.id
imoveis.id_tipo_imovel          → tipos_imovel.id
imoveis.id_estado               → estados.id
imoveis.id_municipio            → municipios.id
imoveis.id_bairro               → bairros.id
imoveis.id_sub_bairro           → sub_bairros.id
imoveis.id_grupo                → imoveis_grupos.id
imoveis.id_etapa                → imoveis_etapas.id
imoveis_historico.id_imovel     → imoveis.id
imoveis_historico.id_modalidade → modalidades_venda.id
municipios.id_estado            → estados.id
bairros.id_municipio            → municipios.id
sub_bairros.id_bairro           → bairros.id
imobiliarias_estados.id_imobiliaria → imobiliarias.id
imobiliarias_estados.id_estado      → estados.id
atendimentos.id_lead            → leads.id
atendimentos.id_imovel          → imoveis.id
atendimentos.id_imobiliaria     → imobiliarias.id
atendimentos.id_origem          → atendimentos_origem.id
```

---

## 📊 DIAGRAMA SIMPLIFICADO

```
[estados] ──< [municipios] ──< [bairros] ──< [sub_bairros]
                                   │
[estados] ──< [imobiliarias_estados] >── [imobiliarias]
                                   │
                              [imoveis] >── [tipos_imovel]
                              [imoveis] >── [modalidades_venda]
                              [imoveis] >── [imoveis_grupos]
                              [imoveis] ──< [imoveis_historico]
                              [imoveis] ──< [imoveis_etapas]
                                   │
                         [atendimentos] >── [leads]
                         [atendimentos] >── [atendimentos_origem]
```

---

## 📁 Localização deste arquivo

```
/docs
├── visao-geral.md       ← visão geral do sistema
└── banco-de-dados.md    ← este arquivo
```

---

*Documento gerado em: maio de 2026 — Versão: 2.0*



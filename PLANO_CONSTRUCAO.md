# Plano de Construção — Antigravity Caixa-Busca

> Guia sequencial de desenvolvimento. Cada fase deve ser concluída antes de iniciar a próxima.
> Atualizado em: 2026-05-25 (adicionadas FASE 12 — SEO e FASE 13 — Mini-CRM)

---

## Estado Atual do Projeto

**Stack:** Laravel 13.7 + Livewire 4.3 + Tailwind CSS + MySQL  
**Arquitetura:** Modular (`app/Modules/`) com 16 models, 18 migrations  
**MVP Funcional:** Vitrine pública, importação CSV, painel admin, painel imobiliária, formulário lead → email + WhatsApp + webhook CRM

---

## Fases de Construção

---

### FASE 1 — Estabilização do Core
**Objetivo:** Fechar o que está em andamento e limpar pendências antes de avançar.

- [x] **1.1** Revisar e finalizar `CaixaCsvParserService.php` — 3 bugs corrigidos, parser sólido, sem pendências
- [x] **1.2** Revisar `DatabaseSeeder.php` — credenciais de admin e imobiliária movidas para env vars (`ADMIN_EMAIL`, `ADMIN_PASSWORD`, `IMOBILIARIA_DEMO_*`)
- [x] **1.3** Revisar `routes/console.php` — agendamentos corretos (`optimize::daily`, limpeza de cache), sem pendências
- [x] **1.4** `print_mismatches.php` — arquivo já inexistente (excluído anteriormente)
- [x] **1.5** `/test-log` já redireciona para `admin.diagnostico` (rota protegida por auth). Rota `/verificar-erro-sistema`: token movido de hardcoded para env var `DIAGNOSTICO_TOKEN` (deixar vazio desativa a rota em produção)
- [x] **1.6** Configuração de fila documentada no `.env.example` com exemplo de Supervisor (`queue:work --tries=3 --max-time=3600`)
- [x] **1.7** `.env.example` atualizado com todas as variáveis críticas: `DB_*`, `QUEUE_CONNECTION`, `CRM_*`, `WHATSAPP_CENTRAL`, `MAIL_*`, `ANTHROPIC_API_KEY`, `DIAGNOSTICO_TOKEN`, `ADMIN_*`, `IMOBILIARIA_DEMO_*`. Config `services.whatsapp.central` adicionada em `config/services.php`

**Arquivos-chave:**
- `app/Modules/ImportacaoCSV/Services/CaixaCsvParserService.php`
- `database/seeders/DatabaseSeeder.php`
- `routes/console.php`
- `routes/web.php` (rota `/test-log`)

---

### FASE 2 — Imagens dos Imóveis
**Objetivo:** Exibir imagens reais dos imóveis. Os campos `foto_fachada_url` e `imagem_destaque_url` já existem na tabela, falta popular e exibir.

- [x] **2.1** CSV da Caixa **não tem colunas de imagem** — 12 colunas apenas. Estratégia: URL manual via admin.
- [x] **2.2** CSV verificado: colunas são `N° do imóvel | UF | Cidade | Bairro | Endereço | Preço | Valor de avaliação | Desconto | Financiamento | Descrição | Modalidade de venda | Link de acesso`
- [x] **2.3** (N/A — CSV sem imagens) — corrigidos 3 bugs críticos no parser durante a análise:
  - Bug 1: `numero_original` em notação científica → extrair `hdnimovel` da URL (`extractHdnimovel()`)
  - Bug 2: `link_edital` usava campo errado → agora usa `$data['link_de_acesso']`
  - Bug 3: `aceita_fgts` usava campo errado → agora usa `$data['financiamento']`
- [x] **2.4** Painel admin criado: `GET /admin/imoveis/imagens` — busca imóvel + define URL da imagem manualmente
  - Componente: `Admin/Livewire/ImovelImagem.php`
  - View: `resources/views/modules/admin/livewire/imovel-imagem.blade.php`
- [x] **2.5** (Thumbnail não necessário — views já usam `object-cover` com aspect ratio fixo)
- [x] **2.6** `imovel-search.blade.php` — já usa `foto_fachada_url` com fallback para placeholder SVG
- [x] **2.7** `imovel-show.blade.php` — já usa `foto_fachada_url` + adicionado botão "Ver no site da Caixa"
- [x] **2.8** Placeholder criado: `public/images/imovel-placeholder.svg` (SVG brandado Antigravity/Caixa)
- [x] **2.9** Padrão de URL confirmado: `https://venda-imoveis.caixa.gov.br/fotos/F{numero_13digitos}21.jpg` — sufixo `21` é fixo para a foto de fachada. Já implementado e gravado no banco via `CaixaCsvParserService.php` (linha 299) desde a importação do CSV.

**Arquivos-chave:**
- `resources/views/modules/imoveis/livewire/imovel-search.blade.php`
- `resources/views/modules/imoveis/livewire/imovel-show.blade.php`
- `app/Modules/ImportacaoCSV/Services/CaixaCsvParserService.php`

---

### FASE 3 — Dashboard Admin com Métricas
**Objetivo:** Dar ao admin visibilidade sobre o negócio: imóveis importados, leads gerados, conversão.

- [x] **3.1** Rota `GET /admin/dashboard` criada dentro do grupo `middleware(['auth'])`
- [x] **3.2** Componente `Admin/Livewire/AdminDashboard.php` criado com computed properties
- [x] **3.3** Cards de métricas: imóveis ativos, leads e atendimentos (hoje/7d/30d), imobiliárias
- [x] **3.4** Tabela dos últimos 10 atendimentos (lead + imóvel + imobiliária)
- [x] **3.5** Tabela top 10 imóveis com mais atendimentos (`withCount`)
- [x] **3.6** View `admin-dashboard.blade.php` criada com design consistente
- [x] **3.7** Layout admin criado (`layouts/admin.blade.php`) com nav bar completa: Dashboard, Importar CSV, Imagens, Diagnóstico, Sair. Todos os componentes admin atualizados para usar este layout. Login redireciona para dashboard.

**Arquivos-chave:**
- `app/Modules/Admin/Livewire/` (novo: `AdminDashboard.php`)
- `resources/views/modules/admin/livewire/` (novo: `admin-dashboard.blade.php`)
- `routes/web.php`

---

### FASE 4 — Melhorias no Painel da Imobiliária
**Objetivo:** Tornar o painel do parceiro mais útil com filtros, períodos e exportação.

- [x] **4.1** Filtros em `PainelLeads`: busca por nome/email/telefone/nº imóvel, período (data início/fim), status do parceiro
- [x] **4.2** Métricas no topo: Total de Leads · Últimos 7 dias · Aguardando Contato (pendentes)
- [x] **4.3** Exportação CSV com botão "Exportar CSV" — exporta todos os atendimentos filtrados (sem limite de paginação)
- [x] **4.4** Campo `status_parceiro` (pendente/contatado/negociando/sem_interesse/fechado) + `anotacao` adicionados via migration. Dropdown inline na tabela com cores por status. `Atendimento::STATUS_LABELS` e `STATUS_CORES` como constantes no model.

**Arquivos-chave:**
- `app/Modules/Imobiliaria/Livewire/PainelLeads.php`
- `resources/views/modules/imobiliaria/livewire/painel-leads.blade.php`
- `app/Models/Atendimento.php`

---

### FASE 5 — Módulo Leads (Gestão Centralizada Admin)
**Objetivo:** O admin precisa visualizar e gerir todos os leads da plataforma, independente de imobiliária.

- [x] **5.1** Criar componente Livewire `Leads/Livewire/GestaoLeads.php`
- [x] **5.2** Listagem com filtros: por imobiliária, por estado, por período, somente duplicados
- [x] **5.3** Tela de detalhe do lead: todos os imóveis de interesse, todos os atendimentos
- [x] **5.4** Exportação global de leads em CSV
- [x] **5.5** Detectar leads duplicados (mesmo email ou telefone) e marcar com badge
- [x] **5.6** Rota: `GET /admin/leads` + link "Leads" adicionado ao nav bar admin

**Arquivos-chave:**
- `app/Modules/Leads/Livewire/GestaoLeads.php`
- `resources/views/modules/leads/livewire/gestao-leads.blade.php`
- `app/Models/Lead.php`
- `app/Models/Atendimento.php`

---

### FASE 6 — Módulo Integração CRM (Interface de Configuração)
**Objetivo:** Permitir configurar e monitorar a integração com CRM externo via interface admin, sem precisar editar `.env`.

- [x] **6.1** Criar tabela `crm_configuracoes` (migration): `webhook_url`, `webhook_token`, `ativo`, `ultimo_envio_em`, `ultimo_status`
- [x] **6.2** Criar Model `CrmConfiguracao` com `atual()` singleton helper
- [x] **6.3** Criar tabela `crm_webhook_logs` (migration) + Model `CrmWebhookLog`
- [x] **6.4** Componente `Admin/Livewire/IntegracaoCrm.php`: formulário de config, botão "Testar", log últimos 20 envios
- [x] **6.5** `DispatchCrmWebhookJob` atualizado: lê do banco (fallback `.env`), registra log a cada envio, atualiza `ultimo_envio_em` e `ultimo_status`
- [x] **6.6** Rota `GET /admin/integracao-crm` (nome: `admin.crm`) + link "CRM" no nav bar admin

**Arquivos-chave:**
- `app/Modules/Admin/Livewire/IntegracaoCrm.php`
- `resources/views/modules/admin/livewire/integracao-crm.blade.php`
- `app/Modules/Imoveis/Jobs/DispatchCrmWebhookJob.php`
- `app/Models/CrmConfiguracao.php`, `app/Models/CrmWebhookLog.php`

---

### FASE 7 — Módulo WhatsApp Templates
**Objetivo:** Permitir que o admin configure as mensagens de WhatsApp enviadas ao lead no momento da conversão.

- [x] **7.1** Criar tabela `whatsapp_templates` (migration): `nome`, `mensagem`, `ativo`
- [x] **7.2** Criar Model `WhatsappTemplate` com `ativo()`, `renderizar()` e `renderizarAtivo()` (fallback para mensagem padrão)
- [x] **7.3** Criar CRUD Livewire `Admin/Livewire/WhatsappTemplates.php`: lista, criar, editar, ativar (um ativo por vez), excluir
- [x] **7.4** Atualizar `ImovelShow::converterLead()` para usar `WhatsappTemplate::renderizarAtivo()` com fallback hardcoded
- [x] **7.5** Preview em tempo real na tela de edição — balão estilo WhatsApp com dados configuráveis
- [x] **7.6** Rota `GET /admin/whatsapp-templates` (nome: `admin.whatsapp`) + link "WhatsApp" no nav bar admin

**Variáveis suportadas:** `{nome}`, `{tipo_imovel}`, `{codigo}`, `{localidade}`, `{municipio}`, `{uf}`

**Arquivos-chave:**
- `app/Modules/Admin/Livewire/WhatsappTemplates.php`
- `resources/views/modules/admin/livewire/whatsapp-templates.blade.php`
- `app/Modules/Imoveis/Livewire/ImovelShow.php` (método `converterLead`)

---

### FASE 8 — Bairros Dossiê (Conteúdo IA para SEO)
**Objetivo:** Gerar conteúdo textual automático sobre cada bairro usando IA, melhorando SEO local.

- [x] **8.1** Campos `conteudo_ia`, `ia_status`, `ia_gerado_em` confirmados nas tabelas `bairros` e `municipios`
- [x] **8.2** Migration `2026_05_19_000005`: adiciona `slug` a `bairros` e `municipios` (necessário para URL SEO)
- [x] **8.3** `ConteudoIaService` — chama Anthropic Claude Haiku via `Http::`, monta prompt com dados reais do bairro (imóveis, tipos, faixa de preço), retorna `{titulo, meta_description, texto}`
- [x] **8.4** `GerarConteudoBairroJob` — job com 3 tentativas + backoff, atualiza `ia_status` (pendente → gerado | erro)
- [x] **8.5** Artisan command `app:gerar-conteudo-bairro`: processa bairro único ou lote (filtro `--estado`, opção `--now`, opção `--force`)
- [x] **8.6** Interface admin `GET /admin/bairros-dossie` (`admin.bairros`): cards de status, filtros por estado/município, tabela com botão "Gerar" por bairro + botão "Gerar em lote"
- [x] **8.7** Rota pública `GET /bairros/{uf}/{municipio_slug}/{bairro_slug}` (`bairro.show`) + componente `PaginaBairro`
- [x] **8.8** `ANTHROPIC_API_KEY` documentada no `.env.example`

**Pré-requisito para funcionar:** configurar `ANTHROPIC_API_KEY` no `.env` e rodar `php artisan migrate` + `php artisan queue:work`.

**Arquivos-chave:**
- `app/Modules/BairrosDossie/Services/ConteudoIaService.php`
- `app/Modules/BairrosDossie/Jobs/GerarConteudoBairroJob.php`
- `app/Console/Commands/GerarConteudoBairro.php`
- `app/Modules/Admin/Livewire/BairrosDossie.php`
- `app/Modules/BairrosDossie/Livewire/PaginaBairro.php`

---

### FASE 9 — Performance e Qualidade
**Objetivo:** Preparar a plataforma para volume real (~150k imóveis).

- [x] **9.1** Migration `2026_05_19_000006`: índices em `imoveis.slug`, `atendimentos.created_at`, `imoveis_historico.created_at` (demais índices críticos já existiam)
- [x] **9.2** Cache `Cache::remember()` nos dropdowns do `ImovelSearch::render()`: `dropdown_estados` e `dropdown_tipos_imovel` — TTL 1h. Cache store usa o padrão do `.env` (`database` por padrão, trocável por `redis`)
- [x] **9.3** `Schedule::command('optimize')->daily()` adicionado em `routes/console.php`. Para ativar: configurar cron `* * * * * php artisan schedule:run` no servidor
- [x] **9.4** Rate limiting no `ImovelShow::converterLead()`: máx 5 tentativas/min por IP usando `RateLimiter`. Excesso lança `ValidationException` com mensagem de espera
- [x] **9.5** Migration `2026_05_19_000007`: `deleted_at` adicionado a `imoveis`, `leads`, `atendimentos`. Trait `SoftDeletes` adicionada nos três models
- [x] **9.6** Logs da plataforma não registram PII (email/telefone). `DispatchCrmWebhookJob` loga apenas `imovel_id`. Recomendado: `LOG_CHANNEL=daily` no `.env` para rotação automática com retenção de 14 dias

**Arquivos-chave:**
- `database/migrations/2026_05_19_000006_add_performance_indexes.php`
- `database/migrations/2026_05_19_000007_add_soft_deletes_to_tables.php`
- `app/Models/Imovel.php`, `Lead.php`, `Atendimento.php` (SoftDeletes)
- `app/Modules/Imoveis/Livewire/ImovelSearch.php` (cache dropdowns)
- `app/Modules/Imoveis/Livewire/ImovelShow.php` (rate limiting)
- `routes/console.php` (Schedule::optimize)

---

### FASE 10 — Testes Automatizados
**Objetivo:** Garantir regressão zero ao evoluir o sistema.

- [x] **10.1** `tests/Feature/LeadFormTest.php` — 6 testes: exibição do form, criação de lead + atendimento, atualização de lead existente, validação de campos, sem duplicata de atendimento, rate limiting (bloqueia após 5 tentativas)
- [x] **10.2** `tests/Feature/CsvImportacaoTest.php` — 6 testes: importação de imóvel, criação de histórico, criação automática de estado/município, sem duplicata em reimportação, slug gerado, extração de número por `hdnimovel`
- [x] **10.3** `tests/Unit/CaixaCsvParserServiceTest.php` — 16 testes via reflection sobre métodos privados: `cleanDecimal` (6 casos), `extractHdnimovel` (4), `parseBairro` (2), `parseDescription` (4), `slugify` (3)
- [x] **10.4** `tests/Feature/AuthTest.php` — 7 testes: login admin (sucesso/falha/validação), acesso protegido, login imobiliária (sucesso/falha), painel sem autenticação
- [x] **10.5** `tests/Feature/PainelLeadsTest.php` — 6 testes: visualização de atendimentos próprios, filtro por status, busca por nome, atualização de status (próprio e de outra imobiliária), exportação CSV, métricas
- [x] **10.6** Job `laravel-tests` adicionado ao `.github/workflows/ci.yml` — PHP 8.3, SQLite in-memory, `composer install`, `php artisan test --stop-on-failure`
- [x] **Factories criadas:** `EstadoFactory`, `MunicipioFactory`, `BairroFactory`, `TipoImovelFactory`, `ImobiliariaFactory`, `ImovelFactory`, `ImovelHistoricoFactory`, `LeadFactory`, `AtendimentoFactory`

**Arquivos-chave:**
- `tests/Feature/` — 4 arquivos de feature test
- `tests/Unit/CaixaCsvParserServiceTest.php`
- `database/factories/` — 9 factories criadas
- `.github/workflows/ci.yml` (job `laravel-tests` adicionado)

---

### FASE 12 — SEO e Performance Web
**Objetivo:** Garantir que os imóveis da plataforma sejam indexados corretamente e ranqueiem bem nos buscadores.

- [x] **12.1** `public/robots.txt` completo — bloqueia `/admin/`, `/parceiro/` e rotas sensíveis; aponta `Sitemap:` para a URL de produção
- [x] **12.2** Rota `/sitemap.xml` dinâmica — XML com homepage (priority 1.0) + todos os imóveis ativos (priority 0.8, `changefreq weekly`, slug + `updated_at`)
- [x] **12.3** `<link rel="canonical">` no layout base — usa `$canonical` ou `url()->current()` como fallback
- [x] **12.4** `ImovelShow` passa `canonical`, `meta_title`, `meta_description` e `og_image` (foto real ou placeholder) ao layout
- [x] **12.5** `PaginaBairro` passa `meta_title` e `meta_description` (conteúdo IA ou fallback genérico)
- [x] **12.6** Open Graph + Twitter Card no layout base — `og:title`, `og:description`, `og:image`, `og:url`
- [x] **12.7** JSON-LD Schema.org `RealEstateListing` + `BreadcrumbList` na página do imóvel (via `json_encode()` sem HTML entities)
- [x] **12.8** Security headers no `.htaccess` — HSTS, X-Frame-Options, X-Content-Type-Options, Referrer-Policy, COOP, CSP básica
- [x] **12.9** Performance LCP — `fetchpriority="high"` + `<link rel="preload">` na imagem hero da vitrine
- [x] **12.10** Acessibilidade — `<main>` landmark, `title` em iframe Google Maps, hierarquia de headings corrigida (h6→`<p>`, h3→h2)
- [x] **12.11** Gated content "Análise do Comprador" desbloqueada após captura de lead (`BuyerAnalysisGate` Livewire + Alpine.js localStorage)
- [x] **12.12** `ImovelShow` — fallback automático de `meta_description` quando o campo está nulo no banco (`"Oportunidade de investimento em {bairro}. Veja detalhes."`)
- [x] **12.13** HTTP 500 em URLs de dois segmentos `/{slug}/{hdnimovel}` — adicionado redirect 301 para `/{slug}` quando o primeiro segmento é um slug válido (antes da rota wildcard `/{imovel:slug}`)
- [x] **12.14** `ImovelSearch` — `meta_title`, `meta_description`, `og_image` e `canonical` (`url('/')`) adicionados ao `render()` via `.layout('layouts.app', [...])`
- [x] **12.15** `PaginaBairro` — `og_image` e `canonical` explícito adicionados via `route('bairro.show', [uf, municipioSlug, bairroSlug])`
- [x] **12.16** JSON-LD `SearchResultsPage` + `ItemList` na vitrine — `@push('schema')` na view `imovel-search.blade.php` com `numberOfItems` total e `ListItem` por imóvel da página atual
- [x] **12.17** Enviar sitemap ao Google Search Console e monitorar indexação dos imóveis
- [x] **12.18** Core Web Vitals — CLS: coberto pelo container `aspect-[4/3]` (espaço reservado antes do load). LCP: `fetchpriority="high"` + `loading="eager"` no primeiro card da vitrine + `<link rel="preload">` no `<head>` via `@push('preload')`. Demais cards: `loading="lazy"`.

**Arquivos-chave:**
- `public/robots.txt`, `public/.htaccess`
- `routes/web.php` (rota `/sitemap.xml`)
- `resources/views/layouts/app.blade.php`
- `app/Modules/Imoveis/Livewire/ImovelShow.php` (meta tags + JSON-LD)
- `app/Modules/BairrosDossie/Livewire/PaginaBairro.php` (meta tags)

---

### FASE 13 — Melhorias na Gestão de Leads (Mini-CRM)
**Objetivo:** Evoluir a página de gestão de leads do admin para um Mini-CRM funcional com rastreamento de UTMs, status de atendimento, atribuição de responsável e histórico de interações.

**Passo 1: Atualizações de Banco de Dados (Migrations e Models)**
- [x] **13.1** Migration `2026_05_25_210001`: UTM fields (`utm_source`, `utm_medium`, `utm_campaign`) na tabela `leads`.
- [x] **13.2** Migration `2026_05_25_210001`: campo `status` enum (novo/em_atendimento/proposta/perda), default `novo`.
- [x] **13.3** Migration `2026_05_25_210001`: `user_id` nullable FK → `users` (nullOnDelete).
- [x] **13.4** Migration `2026_05_25_210002` + Model `LeadNote` — tabela `lead_notes` com `lead_id`, `user_id`, `conteudo`, `tipo` (anotacao/ligacao/email/whatsapp/visita).
- [x] **13.5** Model `Lead` atualizado: constantes `STATUS_*`, fillables, `notes()`, `responsavel()`. `LeadConversionService` persiste UTMs ao converter.

**Passo 2: Componentes de Interface e Lógica Livewire (Ações Individuais)**
- [x] **13.6** Dropdown de Status (Inline Editable) via Livewire — `atualizarStatus()` com cores por status
- [x] **13.7** Dropdown de Responsável (Atribuição) via Livewire — `atribuirResponsavel()` com lista de usuários
- [x] **13.8** Preview do Imóvel — mini-card com foto, tipo, localização e preço na tela de detalhe
- [x] **13.9** Painel lateral (Offcanvas) para Notas Rápidas — `abrirNotas()`, `salvarNota()`, `fecharNotas()` com tipos (anotação/ligação/email/whatsapp/visita)

**Passo 3: Ações em Massa e Inteligência da Listagem**
- [x] **13.10** Checkboxes na tabela para seleção múltipla — `$leadsSelecionados` + "Selecionar todos da página"
- [x] **13.11** Ações em Massa — `acaoMassaStatus()` (status em lote) + `exportarSelecionados()` (CSV filtrado)
- [x] **13.12** Lead Score visual — 🔥 Quente (≥3 atend. ou <3 dias ou proposta) · 🟡 Morno · ❄️ Frio (0 atend. ou >14 dias ou perda)
- [x] **13.13** Origem (UTMs) exibida visualmente — badges `utm_source` e `utm_campaign` na tabela + detalhe

**Passo 4: Filtros e Dashboards**
- [x] **13.14** Filtros Avançados — dropdowns de Status e Responsável (incluindo "Sem responsável") na barra de filtros
- [x] **13.15** Cards KPI no topo — Total Leads · Novos 7d · Taxa Conversão (%) · Em Atendimento

**Arquivos-chave:**
- `app/Modules/Leads/Livewire/GestaoLeads.php`
- `resources/views/modules/leads/livewire/gestao-leads.blade.php`
- `app/Models/Lead.php`
- `app/Models/LeadNote.php`
- `database/migrations/` (migrations de UTM, status, user_id, lead_notes)

---

### FASE 14 — Conteúdo Rico por Bairro via OpenRouter
**Objetivo:** Expandir o conteúdo IA dos bairros de 3 campos genéricos (titulo/meta/texto) para 8 tópicos estruturados em FAQ/accordion (Vizinhança, Benefícios, Acessos, Comércio, Educação, Saúde, Lazer, Infraestrutura), usando modelos gratuitos via OpenRouter em vez da API Anthropic.

**Premissa:** A coluna `conteudo_ia` (JSON) já existe na tabela `bairros`. Nenhuma migration necessária — só se expandem os campos do JSON gravado. O sistema de geração em lote (Job + Artisan command + admin UI) já existe da Fase 8 e será reaproveitado.

**Passo 1 — Infraestrutura OpenRouter**
- [x] **14.1** Adicionar `OPENROUTER_API_KEY` e `OPENROUTER_MODEL` ao `.env.example` e `config/services.php`
- [x] **14.2** Atualizar `ConteudoIaService` para usar OpenRouter (API compatível com OpenAI: `POST https://openrouter.ai/api/v1/chat/completions`, header `Authorization: Bearer {key}`)
- [x] **14.3** Atualizar o prompt para retornar os 11 campos: `titulo`, `meta_description`, `texto` (retrocompatibilidade) + 8 novos campos do FAQ (`vizinhanca_localizacao`, `beneficios`, `acessos_transporte`, `comercio_conveniencia`, `educacao`, `saude`, `lazer_cultura`, `dados_infraestrutura`)
- [x] **14.4** Validar resposta JSON — se campos do FAQ ausentes, continuar sem erro (retrocompat com conteúdo antigo)

**Passo 2 — Front-end: Accordion na Página do Imóvel**
- [x] **14.5** `ImovelShow::render()` carrega `$bairro->conteudo_ia` via eager load do relacionamento bairro
- [x] **14.6** `imovel-show.blade.php` — seção "FAQ 2: Perguntas Frequentes sobre o Bairro" com accordion Alpine.js: 8 tópicos colapsáveis com fallback genérico quando conteúdo IA ausente

**Passo 3 — Front-end: Accordion na Página Pública do Bairro**
- [x] **14.7** `PaginaBairro::render()` passa os 8 campos para a view
- [x] **14.8** `pagina-bairro.blade.php` — accordion dos 8 tópicos acima da listagem de imóveis (exibido apenas quando `$temFaq` é `true`)

**Passo 4 — Limpeza**
- [x] **14.9** Botão "Resetar conteúdo antigo" na admin UI (`/admin/bairros-dossie`) — `resetarParaFaq()` marca bairros sem campo `vizinhanca_localizacao` como `ia_status = 'pendente'` e limpa `conteudo_ia`
- [x] **14.10** Modelo configurado via `OPENROUTER_MODEL` no `.env` — padrão: `meta-llama/llama-3-8b-instruct:free`; troca para `google/gemma-2-9b-it:free` basta alterar a variável

**Campos do JSON gerado:**
```json
{
  "titulo": "...",
  "meta_description": "...",
  "texto": "...",
  "vizinhanca_localizacao": "...",
  "beneficios": "...",
  "acessos_transporte": "...",
  "comercio_conveniencia": "...",
  "educacao": "...",
  "saude": "...",
  "lazer_cultura": "...",
  "dados_infraestrutura": "..."
}
```

**Arquivos-chave:**
- `app/Modules/BairrosDossie/Services/ConteudoIaService.php` (troca API + expande prompt)
- `app/Modules/BairrosDossie/Livewire/PaginaBairro.php` (passa novos campos)
- `app/Modules/Imoveis/Livewire/ImovelShow.php` (carrega conteudo_ia do bairro)
- `resources/views/modules/imoveis/livewire/imovel-show.blade.php` (accordion)
- `resources/views/modules/bairros-dossie/livewire/pagina-bairro.blade.php` (accordion)
- `.env.example`, `config/services.php`

---

### FASE 11 — API REST (Opcional / Fase Futura)
**Objetivo:** Expor dados para consumo por app mobile ou parceiros externos.

- [x] **11.1** Laravel Sanctum `^4.0` já instalado via composer
- [x] **11.2** Rotas em `routes/api.php`: `GET /api/imoveis`, `GET /api/imoveis/{slug}`, `GET /api/estados`, `GET /api/municipios`, `POST /api/leads` — todas com `throttle:60,1`
- [x] **11.3** `ImovelResource`, `ImovelCollection`, `EstadoResource`, `MunicipioResource` criados em `app/Modules/Imoveis/Resources/Api/`. Bug corrigido: `aceita_fgts === 'sim'` em vez de `(bool)` cast
- [x] **11.4** Rate limiting: 60 req/min (público via `throttle:60,1`) + 5 req/min por IP no `POST /api/leads` via `RateLimiter`. Origin "API/Mobile" adicionada ao seeder
- [x] **11.5** Documentação completa em `API.md` — todos os 5 endpoints com exemplos de request/response, parâmetros, códigos de erro e notas de integração

---

## Prioridade Resumida

| Fase | Nome | Status | Estimativa |
|------|------|--------|------------|
| 1 | Estabilização do Core | ✅ Concluída | — |
| 2 | Imagens dos Imóveis | ✅ Concluída | — |
| 3 | Dashboard Admin | ✅ Concluída | — |
| 4 | Melhorias Painel Imobiliária | ✅ Concluída | — |
| 5 | Módulo Leads (Admin) | ✅ Concluída | — |
| 6 | Módulo Integração CRM | ✅ Concluída | — |
| 7 | WhatsApp Templates | ✅ Concluída | — |
| 8 | Bairros Dossiê (IA) | ✅ Concluída | — |
| 9 | Performance e Qualidade | ✅ Concluída | — |
| 10 | Testes Automatizados | ✅ Concluída | — |
| 11 | API REST | ✅ Concluída | — |
| 12 | SEO e Performance Web | ✅ Concluída | — |
| 13 | Melhorias Gestão de Leads (Mini-CRM) | ✅ Concluída | — |
| 14 | Conteúdo Rico por Bairro via OpenRouter | ✅ Concluída | — |

---

## Como Usar Este Guia

1. A cada nova sessão, **informe qual fase ou tarefa** quer trabalhar
2. Iremos implementar **uma tarefa de cada vez**, testando antes de avançar
3. Ao concluir um item, **marcamos com `[x]`** e atualizamos este arquivo
4. Se surgir uma necessidade nova fora do plano, **adicionamos uma nova fase** antes de implementar

---

## Estrutura de Módulos (Referência Rápida)

```
app/
├── Models/                          # 16 modelos Eloquent
├── Modules/
│   ├── Admin/                       # ✅ Login + Dashboard + Importação CSV + CRM + WhatsApp
│   ├── Imoveis/                     # ✅ Vitrine + Show + UTM + Webhook + API Resources
│   ├── Imobiliaria/                 # ✅ Login + Painel de Leads filtrado + Exportação CSV
│   ├── ImportacaoCSV/               # ✅ Parser + Job + Upload + Progress Banner
│   ├── BairrosDossie/               # ✅ Geração IA + Página pública + Interface admin (Fase 8)
│   ├── Leads/                       # ✅ Gestão centralizada admin + Exportação (Fase 5)
│   └── WhatsAppTemplates/           # ✅ CRUD + Preview + Renderização ativa (Fase 7)
└── Observers/
    └── AtendimentoObserver.php      # ✅ Email automático

database/
├── migrations/                      # ✅ 18 migrations completas
└── seeders/                         # ✅ 7 seeders (estados, tipos, grupos, etc.)

resources/views/modules/
├── admin/                           # ✅ Login
├── imoveis/                         # ✅ Search + Show
├── imobiliaria/                     # ✅ Login + Painel
└── importacao-csv/                  # ✅ Upload

routes/
├── web.php                          # ✅ Públicas + Admin + Parceiro
└── console.php                      # ✅ Comandos artisan
```

---

## Variáveis de Ambiente Necessárias

```env
# Banco de dados
DB_CONNECTION=mysql
DB_HOST=
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

# Filas
QUEUE_CONNECTION=database

# CRM
CRM_WEBHOOK_URL=
CRM_WEBHOOK_TOKEN=

# WhatsApp
WHATSAPP_CENTRAL=

# E-mail
MAIL_MAILER=
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME=

# Cache (Fase 9)
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

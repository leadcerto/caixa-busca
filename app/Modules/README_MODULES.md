# 🏗️ ORQUESTRADOR DE ARQUITETURA MODULAR (DDD)

Este documento define as regras rígidas de Arquitetura Modular (Domain-Driven Design) para este projeto. O objetivo é manter o sistema escalável, isolado e tolerante a falhas. 

Nenhum código deve ser criado no padrão MVC monolítico clássico (tudo misturado em uma única pasta `app/` ou `src/`). Toda a lógica de negócio deve ser construída **dentro desta pasta `Modules/`**.

## 📂 1. ESTRUTURA OBRIGATÓRIA DE CADA MÓDULO

Cada módulo dentro desta pasta deve ser 100% autossuficiente (encapsulado). Se um módulo for deletado, o resto do sistema não deve quebrar catastroficamente (deve haver tratamento de exceções).

A estrutura interna de QUALQUER módulo criado deve seguir este padrão:

/Modules
  /NomeDoModulo
    /Controllers     # Controladores de requisição específicos do módulo
    /Models          # Entidades de banco de dados
    /Services        # Regras de negócio pesadas (CSV, Scraping, etc.)
    /Routes          # Rotas de API e Web exclusivas deste módulo
    /Views           # (Se aplicável) Componentes visuais do módulo
    docs.md          # OBRIGATÓRIO: Autodocumentação da API e do funcionamento do módulo

## 🧩 2. MÓDULOS A SEREM DESENVOLVIDOS (ESCOPO)

A IA (Antigravity) e os desenvolvedores devem provisionar a criação dos seguintes módulos isolados:

### Módulo: `ImportacaoCSV`
*   **Responsabilidade:** Ler o arquivo CSV da Caixa e inserir/atualizar o banco de dados.
*   **Regras:** Executar Regex e *split* por vírgula na coluna "Descrição" para isolar o tipo do imóvel (casa, apartamento) e suas características (quartos, vagas) em colunas indexadas no banco.
*   **Segurança:** Deve conter `Try/Catch` e gravar logs em `error.log` em caso de falha de leitura.

### Módulo: `BairrosDossie`
*   **Responsabilidade:** Fazer o scraping de dados demográficos, infraestrutura, escolas e IDH dos bairros onde os imóveis estão localizados.
*   **Regras:** Estes dados NÃO devem ser fatiados em várias tabelas. O resultado do scraping deve ser convertido e salvo em um único campo do tipo `JSON` no banco de dados para leitura em altíssima velocidade.

### Módulo: `ImoveisBusca`
*   **Responsabilidade:** Exibir os imóveis para o usuário final, com SEO dinâmico (Open Graph dinâmico via slug do imóvel) e aplicar filtros de busca ultrarrápidos baseados nas colunas tratadas pelo módulo de CSV.
*   **Interface:** Gerenciar as Views (Sanfonas/Accordions) para exibir os dados do imóvel e o JSON do Dossiê do Bairro.

### Módulo: `WhatsAppTemplates`
*   **Responsabilidade:** Gerenciar o painel administrativo onde os textos de WhatsApp são configurados.
*   **Regras:** Substituir variáveis (`{{tipo_imovel}}`, `{{id_imovel}}`, etc.) para gerar o link do `api.whatsapp.com`. Não pode haver *hardcode* de mensagens no código.

### Módulo: `IntegracaoCRM` (Webhooks)
*   **Responsabilidade:** Ouvir (Listen) eventos de conversão gerados pelo sistema (ex: clique no WhatsApp).
*   **Regras:** Fazer um *POST* assíncrono (Webhook) enviando um JSON com UTMs, Origem, e dados do imóvel para a URL do CRM externo cadastrada no painel e via `.env`. Não deve gerenciar funil de vendas, apenas notificar.

## 🚦 3. REGRAS DE COMUNICAÇÃO ENTRE MÓDULOS

1. **Injeção de Dependência:** Um módulo não deve acessar o banco de dados do outro diretamente. Se o módulo `ImoveisBusca` precisar de dados de `BairrosDossie`, ele deve chamar uma classe de `Service` do módulo de bairros.
2. **Eventos (Event-Driven):** Quando um lead clica no WhatsApp (Módulo `ImoveisBusca`), este módulo apenas "emite um evento". O módulo `IntegracaoCRM` "ouve" esse evento e dispara o Webhook. Isso garante que a plataforma não trave esperando respostas lentas.
3. **Variáveis de Ambiente:** NENHUM módulo pode ter URLs, tokens, senhas ou credenciais de banco escritos diretamente. O uso de `env()` ou `process.env` é imperativo em todo o ecossistema.

DOCUMENTO MESTRE DE REQUISITOS (D.R.S.)
Projeto: Plataforma de Busca de Imóveis (Caixa Econômica Federal) Escopo Principal: Máquina de busca, exibição de imóveis, enriquecimento de dados e captação de intenção de compra (Lead). Arquitetura: Microsserviços / Sistema Desacoplado (O sistema não fará papel de CRM, envio de e-mail ou gestão de equipe).

🏗️ ARQUITETURA E ESTRUTURA DE DESENVOLVIMENTO
1. Arquitetura Modular (Domain-Driven Design)
Abordagem: O sistema deve abandonar o padrão MVC monolítico clássico. A estrutura de pastas deve seguir uma abordagem modular, isolando cada contexto do negócio.
Estrutura de Diretórios: Deve existir uma pasta principal de orquestração (ex: Modules/). Dentro dela, uma subpasta para cada módulo (ex: Modules/Imoveis/, Modules/ImportacaoCSV/, Modules/WhatsApp/).
Encapsulamento: Cada pasta de módulo deve conter todos os seus respectivos Controllers, Models, Rotas, Serviços e Views.
2. Autodocumentação
Após a criação de cada módulo, deve ser gerado automaticamente um arquivo de documentação (ex: docs.md ou api_swagger.yaml) dentro da pasta do respectivo módulo.
O arquivo deve explicar como o módulo foi construído, endpoints de API (rotas), parâmetros e lógica de operação.
3. Código 100% Comentado
É obrigatório que todo o código gerado possua comentários claros e descritivos.
Variáveis, campos de banco de dados, funções e lógicas complexas devem ser comentadas para que qualquer programador no futuro compreenda exatamente o que foi feito.
💾 BANCO DE DADOS E PROCESSAMENTO DE DADOS
4. Importação e Tratamento do CSV (Caixa)
Rotina Robusta: O script de importação deve ler o arquivo CSV fornecido pela Caixa usando encoding `ISO-8859-1`.
Extração de Metadados: Antes do parsing das colunas, o serviço deve ler a Linha 1 para extrair a "Data de Geração" via Regex e injetá-la em cada registro.
Pulo de Linhas: O parser deve considerar a Linha 2 como Header e obrigatoriamente pular (skip) a Linha 3 (separadores vazios).
Quebra Cirúrgica da Descrição (Parse/Regex): A coluna de "Descrição" do CSV NÃO deve ser salva como um texto único. O backend fará um explode/split por vírgulas para extrair tipo de imóvel, quartos, vagas, etc.
Exclusão de Coluna: A coluna original "Link de acesso" deve ser descartada; o sistema usará rotas internas para os imóveis.

5. Dossiê de Bairros (Scraping) e Colunas JSON
Abordagem Híbrida (SQL + NoSQL): O banco de dados usará colunas JSON nativas estritamente para dados de leitura pesada que NÃO farão parte de filtros de pesquisa.
Scraping: Os dados de infraestrutura, IDH, segurança e escolas de cada bairro serão capturados por scraping e salvos em um arquivo único JSON.
Armazenamento: Esses dados serão armazenados em uma única coluna dados_bairro (tipo JSON) para garantir um carregamento ultrarrápido na página, sem necessidade de consultas complexas (JOINs).
🎨 FRONTEND, SEO E IMAGENS
6. Gestão de Imagens
Foto do Imóvel: Não haverá tabela de galeria. Será utilizada a imagem única (fachada) hospedada diretamente no servidor da Caixa. O sistema apenas carregará essa URL externa.
Imagem de Destaque (Open Graph/SEO): Para compartilhamento de links (WhatsApp, Redes Sociais), o sistema usará uma imagem única, no formato .jpeg, hospedada localmente.
Regra de SEO: Essa imagem de destaque será obrigatoriamente renomeada de forma dinâmica com o título (slug) correspondente à URL da página do imóvel.
7. Interface de Usuário (Página do Imóvel)
A página do imóvel será focada em retenção e engajamento.
As informações detalhadas e o Dossiê do Bairro devem ser organizadas em seções retráteis (Sanfonas / Accordions) para manter o layout limpo.
📊 RASTREAMENTO, ANALYTICS E CONVERSÃO
8. Inteligência de Rastreamento
UTMs e Cookies: Captura inteligente e persistente de todas as UTMs de campanhas de tráfego (Source, Medium, Campaign, Term, Content).
Google Tag Manager (GTM): Monitoramento profundo do comportamento do usuário:
Tempo de tela.
Cliques para expandir as "Sanfonas" de conteúdo.
Cliques no botão de WhatsApp.
9. Mensagens Dinâmicas de WhatsApp (Templates)
O Painel Administrativo terá um módulo para criar Templates da primeira mensagem que o lead enviará ao corretor.
Funcionamento: Quando o usuário clica em "Falar com Corretor", o sistema gera um link do WhatsApp (api.whatsapp.com/send) substituindo variáveis dinâmicas (ex: {{tipo_imovel}}, {{id_imovel}}, {{bairro}}) para que o cliente envie uma mensagem já formatada e detalhada com um único clique.
Nenhuma mensagem deve ser fixada no código (hardcoded).
🔌 INTEGRAÇÃO E SEGURANÇA
10. Integração com o CRM (Webhooks)
Arquitetura Orientada a Eventos: Como o sistema é focado apenas em busca, qualquer evento de conversão (ex: clique no WhatsApp) deve disparar um evento interno.
Webhook de Saída: O sistema enviará um POST em formato JSON para uma URL externa (seu CRM de vendas em outro subdomínio).
Payload do Webhook: O pacote de dados enviado conterá as UTMs rastreadas, os dados do imóvel acessado e as informações do clique/conversão, deixando o tratamento do lead exclusivamente para o CRM.
11. Segurança, Credenciais e Logs
Variáveis de Ambiente (.env): Nenhuma senha, token ou chave de API deve constar no código-fonte. Tudo será gerenciado pelo arquivo de ambiente.
Tolerância a Falhas (Try/Catch): Processos como importação de CSV e disparos de Webhooks devem possuir tratamento de erros rigoroso.
Logs: Qualquer falha em rotinas de background não deve "quebrar" o sistema silenciosamente, mas sim gerar um registro claro em um arquivo error.log com data, linha e motivo da falha.
12. Conformidade com a LGPD (Privacidade)
Gestão de Consentimento: O sistema deve implementar um banner de cookies para que o usuário aceite o rastreamento antes da ativação dos scripts de analytics.
Transparência na Captura: Todo formulário ou botão que capture intenção de compra (Lead) deve conter uma breve nota informando que os dados serão processados conforme a LGPD.
Finalidade Específica: Os dados capturados (UTMs + Dados do Imóvel) devem ser utilizados exclusivamente para o encaminhamento ao CRM externo.

13. Segurança de Integração (Webhooks)
Autenticação por Token: Todas as requisições de Webhook enviadas para o CRM devem incluir um token único (X-Webhook-Token) no cabeçalho, configurado via .env.
Retry Policy: O sistema deve registrar o status HTTP de retorno do CRM. Caso o CRM esteja offline (5xx) ou ocupado (429), o evento deve ser enfileirado para nova tentativa.

14. Estratégia de Cache e Performance
Cache de Bairros: Os dados do "Dossiê de Bairros" (JSON) devem ser cacheados após a primeira leitura para evitar processamento repetitivo do banco de dados.
Resultados de Busca: Implementar cache para as queries de busca mais comuns (ex: "Casa em Copacabana"), com tempo de expiração (TTL) de 1 hora ou limpeza automática após nova importação de CSV.
Carregamento Imediato: As fotos dos imóveis (URLs externas da Caixa) NÃO devem utilizar lazy loading, garantindo que estejam visíveis assim que a página for aberta. O carregamento tardio fica restrito a componentes de rodapé ou imagens secundárias de baixa prioridade.

15. SEO Técnico e Indexação
Sitemap Dinâmico: O sistema deve gerar e atualizar automaticamente um arquivo sitemap.xml que liste todas as URLs ativas de imóveis e páginas de bairros.
Robots.txt: Configuração de um arquivo robots.txt otimizado para permitir a varredura completa dos imóveis, mas bloquear áreas administrativas ou URLs de filtros que geram conteúdo duplicado.
Canonical Tags: Implementação automática de tags canônicas para evitar que páginas com filtros aplicados sejam interpretadas como conteúdo duplicado pelos buscadores.

16. Integridade e Validação de Importação
Sanitização de Cabeçalhos: O script deve ignorar diferenças de maiúsculas/minúsculas ou espaços extras nos nomes das colunas do CSV.
Tratamento de Nulos: Caso campos essenciais (ex: Preço ou ID) venham vazios no CSV, o sistema deve ignorar o registro e logar o erro, em vez de interromper toda a importação.
Deduplicação: Implementar uma trava lógica para evitar que o mesmo imóvel seja duplicado no banco caso o CSV seja reimportado acidentalmente (usar o ID único do imóvel da Caixa como chave).

### 🏗️ REGRAS DE NEGÓCIO E LIMITAÇÕES
17. **Uma Imobiliária por Estado**: O sistema é configurado estritamente para ter apenas uma imobiliária parceira responsável por cada estado (UF). 
18. **Vínculo Automático**: Leads gerados para um imóvel devem ser encaminhados exclusivamente para a imobiliária vinculada ao estado daquele imóvel.

🤖 DIRETRIZES DE COMPORTAMENTO PARA OS AGENTES (ANTIGRAVITY)
(Entregar esta lista como instrução base de sistema para a IA que irá programar)

Código Comentado: Documentem e comentem exaustivamente a criação de variáveis, tabelas, consultas e lógicas de negócios.
Arquitetura Modular: Usem estruturação por módulos/domínios. Cada módulo deve conter sua própria lógica, rotas e autodocumentação em um arquivo (ex: docs.md).
Engenharia de Banco de Dados: Atributos de pesquisa (Tipo de imóvel, Vagas, Quartos) extraídos do CSV DEVEM ficar em colunas relacionais e indexadas.
Casting JSON: Usem colunas tipo JSON (ex: dados_bairro) e casting automático nas Entidades do backend estritamente para dados de leitura e enriquecimento que não necessitam de filtros na query (WHERE).
Parse de CSV e Regex (Protocolo Caixa):
1. Ler Linha 1: Extrair "Data de Geração".
2. Definir Header: Linha 2.
3. Skip: Linha 3.
4. Encoding: `ISO-8859-1`.
5. Sanitizar Headers: Lowercase, remover acentos, trocar espaços por `_` e converter `Nº` para `numero`.
6. Descartar: Coluna "Link de acesso".
7. Split Descrição: Extrair comodidades do campo "Descrição" para colunas indexadas.
Validação de Dados: O parser de CSV deve ser resiliente a mudanças sutis de formato e garantir a deduplicação de registros baseada no ID único da Caixa.





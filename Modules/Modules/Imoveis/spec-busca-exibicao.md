# Especificao Funcional: Busca Pblica e Gerao de Leads (SDD)

## 1. Contexto, Objetivo e Dor
**Contexto:** O projeto Antigravity visa conectar investidores a imveis da Caixa Econmica Federal de forma gil e eficiente.
**Objetivo:** Implementar uma interface de busca intuitiva e uma pgina de detalhes de alta converso, integrando rastreamento de marketing (UTMs) e automao de contatos (WhatsApp + Webhook CRM).
**Dor:** Investidores perdem tempo com filtros complexos e imobilirias perdem leads por falta de rastreamento ou demora no recebimento dos dados. Esta especificao elimina a frico entre o interesse do comprador e o atendimento da imobiliria parceira.

## 2. Atores
- **Visitante (Investidor/Lead):** Pessoa interessada em adquirir imveis da Caixa.
- **Imobiliria Parceira:** Responsvel por receber e processar os leads de seu estado correspondente (Uma por estado).

## 3. Pr-condies
- O banco de dados deve estar populado com imveis com status "Ativo".
- As tabelas de localidade (Estados, Municpios, Bairros) devem estar devidamente relacionadas.
- O sistema de enfileiramento (Jobs/Redis) deve estar configurado para disparos de webhooks resilientes.

## 4. O Caminho Feliz (Fluxo de Usurio)

1. **Filtro de Busca (Epicentro):**
   - O usurio acessa a home e utiliza os filtros: **Estado**, **Municpio**, **Tipo de Imvel** e **Faixa de Valor**.
   - O sistema realiza a busca indexada no MySQL para garantir carregamento sub-segundo.

2. **Exibio de Resultados:**
   - O sistema exibe os cards dos imveis com a "foto da fachada" carregada diretamente da URL externa da Caixa.
   - **Regra de Performance:** NO utilizar lazy loading na primeira imagem de destaque (fachada) para garantir LCP (Largest Contentful Paint) timo.

3. **Pgina de Detalhes:**
   - Ao clicar em um imvel, o sistema abre a pgina de detalhes.
   - **SEO/OG Dinmico:** O sistema gera a tag Open Graph utilizando uma imagem local (.jpeg) renomeada obrigatoriamente com o slug do imvel.
   - **Interface Clean:** Detalhes tcnicos e o "Dossi do Bairro" (dados JSON) so exibidos em seções retrteis (**Accordions/Sanfonas**).

4. **Converso (CTA de Alta Converso):**
   - O usurio clica no boto "Falar com Corretor" (WhatsApp).
   - **Rastreamento Silencioso:** O sistema captura todas as UTMs (`source`, `medium`, `campaign`, etc) persistidas em cookies ou sesso.
   - **Link Dinmico:** Gera o link do WhatsApp utilizando o template administrativo, substituindo variveis: `{{tipo_imovel}}`, `{{id_imovel}}`, `{{bairro}}`.
   - **Disparo de Webhook:** Simultaneamente ao clique, o sistema dispara um evento assncrono (Webhook POST JSON) para o CRM da imobiliria.
     - **Segurana:** Header `X-Webhook-Token` configurado via `.env`.
     - **Payload:** Dados do imvel + UTMs + Timestamp da converso.

## 5. A Matriz do Caos (Caminhos Tristes)

| Cenrio de Falha | Comportamento do Sistema | Resultado Esperado |
| :--- | :--- | :--- |
| Busca sem resultados | Exibir "Estado Vazio" (Empty State) amigável. | Sugerir limpeza de filtros ou novos critrios de busca. |
| Foto da Caixa quebrada (404) | Detectar erro de carregamento via `onerror` no Blade/JS. | Exibir imagem de fallback padro do projeto. |
| Falha no Webhook CRM (5xx/Timeout) | Aplicar **Retry Policy** com backoff exponencial. | Lead enfileirado para reprocessamento sem perda de dados. |
| UTMs Ausentes | Prosseguir com a converso omitindo os campos de marketing. | Lead gerado normalmente, mas registrado como "Trfego Direto". |

## 6. Ps-condies
- **Conexo Humana:** O visitante  direcionado para o WhatsApp da imobiliria com a mensagem personalizada pronta para envio.
- **Gesto de Leads:** O CRM da imobiliria recebe o Lead estruturado via Webhook para atendimento imediato.
- **Analytics:** O clique  registrado no Google Tag Manager (GTM) para clculo de ROI.

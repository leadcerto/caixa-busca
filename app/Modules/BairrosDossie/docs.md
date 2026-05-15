# 📖 Módulo: Dossiê de Bairros (Scraping & Dados)

## Responsabilidade
Este módulo é responsável por buscar dados demográficos, infraestrutura, escolas, IDH e segurança dos bairros onde os imóveis estão localizados, criando um "Dossiê" completo para enriquecer a página de vendas.

## Regras de Negócio Críticas (Para a IA)
1. **Performance Absoluta (Coluna JSON):** O resultado do scraping NÃO deve ser desmembrado em múltiplas tabelas relacionais (ex: tabela de escolas, tabela de hospitais). Toda a inteligência coletada deve ser formatada e salva em um **único campo do tipo JSON** no banco de dados.
2. **Resiliência de Scraping:** O scraping pode falhar (mudança de layout da fonte, timeout). Use blocos `Try/Catch`. Se o scraping falhar para um bairro, salve um JSON com valores padrão ou vazios (ex: `{"erro": "dados indisponíveis"}`) e registre no `error.log`. NUNCA trave a aplicação.
3. **Cache / Cron Job:** Este módulo não deve fazer scraping em tempo real quando o usuário acessa o site. O scraping deve rodar em background (Jobs/Queues) logo após a importação do CSV ou via Cron.

## Endpoints Internos
- `POST /api/bairros/gerar-dossie` - Enfileira a busca de dados para bairros novos.
- `GET /api/bairros/:cidade/:nome` - Retorna o JSON do bairro solicitado (usado pelo módulo ImoveisBusca).

# Módulo do Parceiro (Portal das Imobiliárias Estaduais)

## 1. Escopo e Regras de Acesso
Este módulo é a área de trabalho diária dos 23 parceiros estaduais. O sistema atua como um portal de **recepção de leads, inteligência de mercado regional e medição de SLA (Acordo de Nível de Serviço)**.
- **Isolamento de Dados:** Filtro global e absoluto. O parceiro logado só visualiza leads, métricas, visitas e produtos referentes ao seu próprio estado.
- **Parceria Estratégica:** O portal não substitui o CRM da imobiliária, mas atua como um "radar de oportunidades", entregando contexto mercadológico e gerando urgência no atendimento.

---

## 2. Dashboard Gerencial (A Bússola da Imobiliária)
A interface deve gerar senso de urgência imediato e fornecer inteligência para a tomada de decisões da imobiliária.

### A. Senso de Urgência (Fator 5 Minutos)
- **Alerta de Topo:** Contador em destaque (ex: "Você tem X leads novos não visualizados").
- **Tags de Tempo na Listagem:** Cada lead deve exibir um cronômetro visual ou tag de tempo desde a sua geração (ex: "Recebido há 4 minutos").

### B. Inteligência de Mercado Regional (Gráficos)
- **Bússola de Captação (Perfil de Busca):** Gráfico mostrando a faixa de preço, tipologia e bairros mais buscados no estado no mês vigente. (Ajuda o parceiro a direcionar sua equipe de captação de imóveis).
- **Ranking de Produtos (Estrela x Mico):** Lista comparativa dos imóveis mais visitados na vitrine cruzados com o volume de leads gerados. Permite ao parceiro identificar imóveis com alto tráfego, mas baixa conversão (indicativo de problema no preço ou fotos).
- **Relatório de SLA (Tempo de Primeira Resposta):** Gráfico e métricas mostrando o tempo médio que a equipe leva para iniciar o primeiro contato com o lead. 
  - *Gatilho de Urgência:* Deve incluir uma mensagem de impacto baseada na demora (Ex: "Sua média de primeiro contato é de 14 horas. A demora acima de 15 minutos reduz sua chance de venda em XX%").

### C. Painel de Omissão (Cobrança Automática)
- **Alerta de Feedback Pendente:** Um card de atenção informando quantos leads estão há mais de 48h sem o preenchimento do histórico/retorno. O objetivo é fazer o dono da imobiliária cobrar sua própria equipe de corretores.

---

## 3. Gestão de Leads e Gatilhos de Ação

### A. Recepção com Contexto ("A Pegada")
Quando o lead cai no painel, ele deve apresentar não apenas os dados de contato, mas o **contexto da navegação**:
- De qual imóvel ele veio (código, bairro, valor).
- Parâmetros da busca que ele estava realizando antes de converter.

### B. O Quebra-Gelo (Botão Inteligente de WhatsApp)
Ação principal da tabela de leads. Um botão "Chamar no WhatsApp" com funcionalidades avançadas:
- **Rastreio Automático:** Ao clicar, o sistema registra automaticamente no banco de dados a data e hora do primeiro contato. Isso gera a métrica do relatório de SLA (Tempo de Primeira Resposta) de forma passiva, sem depender do corretor digitar.
- **Mensagem Pré-configurada:** O botão aciona a API do WhatsApp (`wa.me`) já carregando um texto contextual. 
  - *Exemplo:* "Olá [Nome do Lead], sou corretor da [Imobiliária]. Vi que você se interessou pelo [Tipo de Imóvel] no bairro [Bairro] (Ref: [Código]). Tenho todos os detalhes aqui. Qual o melhor horário para falarmos?"

### C. Sistema de Feedback e Histórico Contínuo (Retroalimentação)
Área obrigatória para qualificar a jornada do lead e fornecer dados para otimização das campanhas do Gestor.
- **Qualificação:** Dropdown para status atual do lead (Lead Quente, Frio, Contato Inválido, Negociação, Venda Concluída).
- **Diário de Bordo (Textarea Editável):** Um campo de texto onde a imobiliária registrará o histórico dos contatos. 
  - *Regra de Negócio:* Esse campo não é travado após o primeiro envio. A imobiliária pode editar/adicionar informações à medida que a negociação avança.
  - *Objetivo Futuro:* Quando o atendimento for finalizado, toda essa massa de texto servirá de insumo para uma Inteligência Artificial ler e extrair padrões de comportamento, objeções e melhorias para as campanhas de marketing geradoras.

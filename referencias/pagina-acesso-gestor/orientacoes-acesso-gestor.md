# Módulo do Gestor: Visão Geral e Requisitos do Sistema

## 1. Escopo e Conceito do Negócio
O sistema atua estritamente como um **Hub Centralizado de Controle de Estoque e Roteamento de Leads**, e NÃO como uma imobiliária tradicional. O foco principal é a gestão ágil de um grande inventário de produtos (imóveis) a nível nacional, controlando entradas, saídas, itens inativos e projeções financeiras. Os leads gerados são distribuídos estrategicamente para 23 parceiros estaduais (que atuam apenas como receptores de contatos, sem acesso administrativo ao sistema).

---

## 2. Dashboard Principal do Gestor
O painel de controle deve fornecer uma visão macro e imediata da saúde do estoque e do negócio, utilizando gráficos e indicadores visuais de fácil leitura.

### A. Stack Tecnológica para Gráficos
- **Backend (Laravel):** Responsável por processar, agrupar e calcular os dados via banco de dados, entregando a informação em formato leve (JSON).
- **Frontend:** Utilização de bibliotecas JavaScript responsivas e interativas (como **ApexCharts** ou **Chart.js**).
- **Recursos Visuais Exigidos:**
  - **Tooltips:** Exibição de números exatos de dados ao passar o mouse ou tocar na tela.
  - **Responsividade:** Recálculo automático de tamanho para adaptação perfeita em monitores grandes e smartphones.
  - **Tipos de Gráficos Recomendados:** Rosca/Pizza (para proporções de status do estoque), Linhas/Áreas (para evolução temporal) e Barras (para comparativos entre os 23 estados).

### B. Atalhos Rápidos
- A área de navegação deve incluir um menu de acesso rápido para áreas críticas, destacando o link direto para a página de **Gerenciamento de Selos de Oportunidade**.

---

## 3. Gestão de Estoque em Massa (Upload de CSV)
O sistema deve suportar a importação de grandes volumes de dados (arquivos com aproximadamente 15.000 linhas). 

- **Processamento Assíncrono:** O processamento deve ocorrer sem travar a tela do usuário.
- **Feedback Visual (Barra de Progresso):** A interface deve exibir, obrigatoriamente, um **percentual (X%) em tempo real** do que já foi processado. Isso garantirá que o gestor possa acompanhar a evolução do upload pesado e tenha certeza de que o sistema continua operando a leitura do arquivo.

---

## 4. Módulo "Selo de Oportunidade" (CRUD)
Ferramenta para aplicar regras de negócios e destaques visuais aos itens do estoque.

### A. Estrutura de Dados
- **Nome do Selo:** Identificação em texto curto (ex: "Liquidação", "Oportunidade").
- **Regras de Percentual:** Campos numéricos para definir gatilhos atrelados ao selo (ex: % de desconto ou rentabilidade). Validação estrita para aceitar apenas valores numéricos.
- **Textos Descritivos:** Área para incluir regras e termos que acompanham o selo.

### B. Tratamento de Imagens e UI
- **Formatos Permitidos:** Restrito a formatos de imagem com fundo transparente (PNG ou SVG).
- **Otimização:** O backend (Storage do Laravel) deve redimensionar automaticamente a imagem para um tamanho padronizado (ex: máx 200x200px) e otimizar seu peso no momento do upload.
- **Preview em Tempo Real:** A tela de edição deve possuir um "Card de Preview" para que o gestor visualize como o selo ficará sobreposto em um produto fictício antes de salvar.

### C. Integridade de Dados
- **Soft Deletes:** O sistema **não deve** realizar exclusão física (drop) de um selo do banco de dados para não quebrar a exibição dos itens do estoque já associados a ele. Deve-se utilizar exclusão lógica (*Soft Deletes* no Laravel) ou status Inativo.

---

## 5. Módulo de Relatórios e Inteligência de Estoque
Área dedicada à volumetria, movimentação e previsão financeira. Projetada para visualização em tela cheia (nova guia) e exportação em alta qualidade para **PDF** (via `dompdf`, `laravel-snappy` ou similares nativos).

### A. Filtros Globais Dinâmicos
Todos os relatórios devem permitir cruzamento de dados filtrando por:
- **Período:** (Ex: últimos 30, 60, 90 dias, ou seleção de datas personalizadas).
- **Estado/Região.**
- **Status do Produto** (Ativos, Inativos, Vendidos).

### B. Relatórios Essenciais Requeridos
1. **Fluxo de Inventário (Entradas e Saídas):** Quantidade de itens novos cadastrados versus itens que saíram/foram inativados no período.
2. **Distribuição Geográfica do Estoque:** Visão do volume total do estoque ativo atual dividido pelos 23 estados (onde está o maior peso do inventário).
3. **Métricas de Novas Entradas:** Comparativo mostrando a quantidade de itens novos que entraram em estados específicos versus o volume total de entradas a nível Brasil no período.
4. **Previsão de Vendas (VGV do Estoque):** Cálculo da soma do valor financeiro de todos os produtos ativos. O relatório deve mostrar o potencial total financeiro a nível nacional e quebrado por estado.
5. **Ticket Médio:** Valor médio financeiro dos produtos em estoque, categorizado por estado.
6. **Volume de Distribuição de Leads:** Quantidade total de leads captados e efetivamente encaminhados para cada um dos 23 parceiros estaduais no período, mensurando o volume de demanda gerada para cada região.

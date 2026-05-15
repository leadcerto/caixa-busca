# Módulo: Página Inicial (Busca Inteligente)

## 1. Visão Geral e Regra de Negócio Principal
A página inicial é a principal porta de entrada do sistema. Ela deve ser extremamente leve, minimalista e com foco absoluto na conversão rápida (escola "Google" de design).
- **Público-Alvo:** Investidores imobiliários (compradores focados em lucro/revenda, não em moradia tradicional).
- **Regra de Ouro (Anti-Imobiliária):** Não existem filtros de características convencionais (quartos, vagas, ou tipo de imóvel - casa/apartamento). O sistema foca em **Localização**, **Capacidade de Investimento** (Valor de Venda) e **Alavancagem** (Financiamento). O produto vendido não é "tijolo", é "margem de lucro".

---

## 2. Estrutura do Formulário e UX (Experiência do Usuário)

O formulário opera em um fluxo contínuo e sem recarregamento de página (utilizando Livewire ou Alpine.js no ecossistema Laravel).

### Passo 1: Localização Macro (Estado e Cidade)
- **Estado (UF):** Dropdown padrão. Ao selecionar o estado, o campo de Cidade é desbloqueado e populado dinamicamente via requisição assíncrona.
- **Cidade:** Dropdown padrão com filtro de digitação rápido.

### Passo 2: Localização Micro (O Painel de Bairros)
- **Gatilho:** Ao selecionar a Cidade, a interface **não** usa selects múltiplos com barra de rolagem (que prejudicam a usabilidade, especialmente no mobile).
- **Ação Visual:** Um painel espaçoso (container) se expande suavemente logo abaixo do campo de cidade.
- **Layout:** Os bairros disponíveis daquela cidade são exibidos em formato de grade (Grid de botões ou *Pills*). 
- **Interação:** O usuário clica nos bairros de interesse, que ficam destacados visualmente. Ele tem uma visão panorâmica de todas as opções de uma só vez, facilitando a seleção múltipla com um simples "tocar/clicar".

### Passo 3: Capacidade de Investimento e Alavancagem
- **Preço Mínimo e Máximo:** Campos de input numérico. 
  - *Lógica de Banco de Dados:* Esses valores filtram **exclusivamente a coluna `valor_venda`** (o que sai do bolso do investidor), ignorando o valor de avaliação do imóvel neste momento da busca.
- **Toggle "Somente com Financiamento":** Chave seletora (Switch) visual.
  - *Comportamento:* Se ativado, adiciona a cláusula `where('aceita_financiamento', true)` na query. Como o status de financiamento é dinâmico e atualizado via CSV do fornecedor, essa busca reflete o estoque em tempo real.

### Passo 4: Submissão e Gatilhos de Prova Social
- **Botão de Busca:** Botão em destaque com feedback visual de carregamento.
- **Contador Dinâmico (Prova Social):** Abaixo do botão, manter a mensagem de autoridade baseada no banco de dados em tempo real: *"Estamos monitorando [XX.XXX] imóveis hoje."*

---

## 3. Requisitos Técnicos (Laravel)
- O formulário fará um `GET request` para a rota da página de resultados (Vitrine), passando os parâmetros na URL de forma limpa. 
- *Exemplo de URL gerada:* `/imoveis?estado=RJ&cidade=Rio+de+Janeiro&bairros[]=Copacabana&bairros[]=Tijuca&preco_min=100000&preco_max=500000&financiamento=1`
- Isso permite que o investidor copie o link da busca dele e salve nos favoritos ou compartilhe, mantendo a pesquisa intacta.

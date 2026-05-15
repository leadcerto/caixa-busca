# ESPECIFICAÇÃO TÉCNICA: PÁGINA DE ADMINISTRAÇÃO DE GRUPOS (PARÂMETROS)

**Contexto para a IA Desenvolvedora:**
Esta página é o painel de administração onde o usuário configura as taxas, honorários e parâmetros financeiros dos "Grupos" de imóveis. Estes parâmetros são vitais, pois alimentam os cálculos de lucro do resto do sistema. O objetivo principal é ter uma interface limpa para uso esporádico, com foco em uma edição segura, à prova de erros e sem perda de dados.

---

## 1. ARQUITETURA DA INTERFACE (MODO DUPLO)

A página deve possuir duas formas de visualização e edição para resolver o problema de excesso de colunas:

### 1.1. Visão Padrão (Tabela Resumida)
*   **[INSTRUÇÃO PARA A IA]:** A tabela principal NÃO deve mostrar todas as colunas do banco de dados para não quebrar o layout.
*   **Colunas visíveis na tabela padrão:** 
    1. Nome do Grupo
    2. Valor Mínimo (R$)
    3. Valor Máximo (R$)
    4. ROI Comum / ROI Caixa
    5. Ações (Botões de "Editar" e "Excluir")
*   **Ação Principal:** Um botão no topo chamado "Adicionar Novo Grupo".

### 1.2. Edição Individual (Drawer / Modal Lateral)
*   **[INSTRUÇÃO PARA A IA]:** Ao clicar em "Editar" em uma linha, o sistema deve abrir um painel lateral (Drawer) ou um Modal largo contendo o formulário completo do grupo selecionado.
*   **Organização Visual (Crucial):** O formulário dentro do painel deve ser dividido em seções (cards ou fieldsets) para facilitar a leitura:
    *   **Seção 1: Identificação e Valores** (Nome, V. Mínimo, V. Máximo)
    *   **Seção 2: Entradas e Crédito (%)** (Registro, Entrada Caixa, Entrada Normal, Prestação)
    *   **Seção 3: Custos e Despesas** (Despesas R$, Desocupação R$, Reforma %, Impostos %)
    *   **Seção 4: Prazos e Metas** (Tempo em meses, Aceleração %, ROI Comum, ROI Caixa)
    *   **Seção 5: Honorários (%)** (Leiloeiro, Corretor, Caixa)

### 1.3. Edição em Massa (Modo Planilha)
*   **[INSTRUÇÃO PARA A IA]:** Adicionar um botão no topo chamado "Modo Edição em Massa". Ao ativar, a tabela resumida se transforma em uma tabela completa com todos os campos editáveis diretamente nas células (estilo Excel), com rolagem horizontal e um botão mestre "Salvar Tudo".

---

## 2. MAPEAMENTO DE DADOS (DATA BINDING)

*   **[INSTRUÇÃO CRÍTICA]:** O formulário deve mapear OBRIGATORIAMENTE os seguintes campos com o banco de dados. Nenhum pode ser omitido:
    1. `nome` (String)
    2. `valor_minimo` (Moeda - R$)
    3. `valor_maximo` (Moeda - R$)
    4. `taxa_registro` (%)
    5. `entrada_caixa` (%)
    6. `entrada_normal` (%)
    7. `taxa_prestacao` (%)
    8. `despesa_fixa` (Moeda - R$)
    9. `despesa_desocupacao` (Moeda - R$)
    10. `taxa_reforma` (%)
    11. `taxa_imposto` (%)
    12. `tempo_meses` (Inteiro)
    13. `taxa_aceleracao` (%)
    14. `roi_comum` (%)
    15. `roi_caixa` (%)
    16. `honorario_leiloeiro` (%)
    17. `honorario_corretor` (%)
    18. `honorario_caixa` (%)

---

## 3. 🛑 RESTRIÇÕES ABSOLUTAS (O QUE É EXTREMAMENTE PROIBIDO FAZER)

Para garantir a estabilidade do sistema, a fidelidade ao design e a segurança dos dados, você está **ESTRITAMENTE PROIBIDA** de realizar as seguintes ações:

### 3.1. Proibições de Dados e Lógica (Backend/Frontend)
*   **PROIBIDO OMITIR CAMPOS:** NÃO exclua, ignore ou deixe de fora NENHUM dos 18 campos mapeados acima no formulário ou no payload da requisição (API). Todos devem ser lidos e enviados.
*   **PROIBIDO CRIAR LÓGICA DE CÁLCULO:** NÃO crie funções matemáticas no frontend para deduzir lucros, alterar honorários automaticamente ou cruzar taxas. O frontend deve ser apenas um "espelho" do banco de dados: lê o que está lá e grava o que o usuário digitar.
*   **PROIBIDO SALVAMENTO AUTOMÁTICO (AUTO-SAVE):** NÃO implemente lógicas onde o sistema salva os dados ao perder o foco do input (`onBlur`). O salvamento DEVE ser feito exclusivamente pelo clique explícito do usuário no botão "Salvar".
*   **PROIBIDO MOCKAR DADOS (HARDCODE):** NÃO insira dados falsos no código final. A tabela deve renderizar vazia (ou com um state de loading) até que a resposta real da API chegue.

### 3.2. Proibições Visuais e de Interface (UI/UX)
*   **PROIBIDO RENDERIZAR A TABELA GIGANTE NA VISÃO PADRÃO:** É proibido colocar todas as 18 colunas apertadas na mesma tela na visão principal, pois isso quebra a responsividade. Use ESTRITAMENTE a Tabela Resumida e esconda os detalhes no Drawer/Modal.
*   **PROIBIDO DEIXAR INPUTS LIVRES:** NÃO crie inputs do tipo texto simples (`<input type="text">`) para campos numéricos. É proibido permitir que o usuário digite letras em campos de taxa (%) ou moeda (R$). Use máscaras e validações estritas no formulário.
*   **PROIBIDO USAR CSS INLINE:** NÃO utilize a propriedade `style={{ ... }}` no código. Como o projeto usa Tailwind CSS, resolva todo o layout usando as classes utilitárias do Tailwind.
*   **PROIBIDO AÇÕES SILENCIOSAS:** NÃO envie requisições para o banco de dados de forma invisível. É proibido salvar ou deletar um grupo sem desabilitar o botão (estado de `loading`) e sem exibir uma notificação visual (Toast/Alerta) de sucesso ou erro logo em seguida.

### 3.3. Proibições de Dependências
*   **PROIBIDO INSTALAR BIBLIOTECAS DE TABELA COMPLEXAS:** NÃO instale pacotes pesados como `ag-grid` ou `mui-datatables` a menos que explicitamente solicitado. Construa a tabela e o Drawer utilizando os componentes nativos do HTML/React e classes do Tailwind.

---

## 4. ESQUELETO ESPERADO (PSEUDO-CÓDIGO UI)

```html
<Page>
  <Header>
    <Title>Administração de Grupos</Title>
    <Actions>
      <Button toggle>Modo Planilha (Massa)</Button>
      <Button primary>Adicionar Grupo</Button>
    </Actions>
  </Header>

  <!-- Visão Padrão: Tabela Limpa -->
  <Table>
     <Columns>Nome | V. Mínimo | V. Máximo | ROIs | Ações</Columns>
     <Row>
       <Cell>Grupo A</Cell>
       <Cell>R$ 100.000</Cell>
       <Cell>R$ 500.000</Cell>
       <Cell>15% / 12%</Cell>
       <Cell><Button onClick="openDrawer(grupo.id)">Editar</Button></Cell>
     </Row>
  </Table>

  <!-- Visão de Edição (Só abre ao clicar em Editar) -->
  <SideDrawer isOpen={isDrawerOpen}>
     <Title>Editando: Grupo A</Title>
     <Form onSubmit="updateGroup(dados)">
        <Section title="Valores">
           <Input name="valor_minimo" mask="currency" />
           <Input name="valor_maximo" mask="currency" />
        </Section>
        <Section title="Taxas e Entradas">
           <Input name="taxa_registro" mask="percentage" />
           <Input name="entrada_caixa" mask="percentage" />
           <!-- ... demais campos seguindo as seções -->
        </Section>
        
        <FormActions>
           <Button type="button" onClick="closeDrawer()">Cancelar</Button>
           <Button type="submit" isLoading={isSaving}>Salvar Alterações</Button>
        </FormActions>
     </Form>
  </SideDrawer>
</Page>

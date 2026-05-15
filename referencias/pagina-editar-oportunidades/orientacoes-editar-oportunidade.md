# ESPECIFICAÇÃO TÉCNICA: PÁGINA DE EDITOR DE OPORTUNIDADES (ETIQUETAS)

**Contexto para a IA Desenvolvedora:**
Esta página é um CRUD simples para gerenciar as "Regras de Oportunidade" do sistema. O objetivo é criar categorias baseadas em faixas de desconto (ex: 70% a 80%). Estas categorias recebem um nome e uma identidade visual (ícone/etiqueta). Posteriormente, o sistema usará essas regras para classificar os imóveis automaticamente na vitrine. Como são poucos dados, a interface deve ser minimalista, direta e focada em uma excelente usabilidade.

---

## 1. ARQUITETURA DA INTERFACE

A página deve ser composta por uma Tabela de Listagem e um Modal para edição/criação.

### 1.1. Visão Principal (Tabela de Regras)
*   **Colunas da Tabela:**
    1. Etiqueta Visual (Preview do ícone/imagem)
    2. Nome da Oportunidade (ex: "Oportunidade de Ouro")
    3. Faixa de Desconto (ex: 70% a 80%)
    4. Ações (Editar, Excluir)
*   **Ação Principal:** Botão "Nova Oportunidade" no topo da página.

### 1.2. Formulário de Edição (Modal)
*   Ao clicar em "Nova Oportunidade" ou "Editar", deve abrir um Modal centralizado na tela.
*   **Campos do Modal:**
    *   **Nome:** Input de texto simples.
    *   **Faixa de Desconto:** Dois inputs numéricos lado a lado (Mínimo % e Máximo %).
    *   **Ícone/Imagem:** Um campo para upload de imagem ou seletor de ícone (permitir que o usuário defina a representação visual, deixando um placeholder genérico caso ainda não tenha a imagem).

---

## 2. MAPEAMENTO DE DADOS (DATA BINDING)

O formulário deve mapear os seguintes campos para envio à API:
1. `nome` (String - ex: "Excelente Oportunidade")
2. `desconto_minimo` (Número/Percentual - ex: 70)
3. `desconto_maximo` (Número/Percentual - ex: 80)
4. `icone_imagem` (String URL, File Upload ou String de referência do ícone)

---

## 3. 🛑 RESTRIÇÕES ABSOLUTAS (O QUE É EXTREMAMENTE PROIBIDO FAZER)

Para garantir a arquitetura correta do sistema e evitar invenções por parte da IA, você está **ESTRITAMENTE PROIBIDA** de realizar as seguintes ações:

### 3.1. Proibições de Lógica e Dados
*   **PROIBIDO APLICAR AS REGRAS AOS IMÓVEIS NESTA TELA:** Esta tela serve APENAS para gerenciar as *regras* (faixas de desconto e nomes). É PROIBIDO criar lógicas no frontend que tentem buscar a lista de imóveis e colar as etiquetas neles. O cruzamento de dados acontecerá no backend ou na tela da vitrine.
*   **PROIBIDO OMITIR A IDENTIDADE VISUAL:** NÃO crie o formulário sem o campo de `icone_imagem`. Mesmo que o usuário ainda não tenha as artes finais, o campo de upload/seleção deve existir e estar funcional no payload da API.
*   **PROIBIDO ENVIAR DADOS SEM VALIDAÇÃO BÁSICA:** NÃO permita salvar uma regra onde o `desconto_minimo` seja maior que o `desconto_maximo`. O frontend deve bloquear essa submissão.

### 3.2. Proibições Visuais e de Interface (UI/UX)
*   **PROIBIDO INTERFACES COMPLEXAS (EX: DRAWERS GIGANTES):** Como são apenas 4 campos, NÃO utilize Drawers laterais enormes ou páginas separadas para o formulário. Use estritamente um Modal simples e centralizado.
*   **PROIBIDO USAR CSS INLINE OU BIBLIOTECAS EXTERNAS DE ESTILO:** NÃO utilize `style={{...}}`. É estritamente obrigatório usar apenas classes do Tailwind CSS.
*   **PROIBIDO AÇÕES SILENCIOSAS:** NÃO salve ou exclua dados sem mostrar feedback visual. É obrigatório exibir o estado de "Carregando..." (Loading) nos botões e um alerta (Toast) de "Salvo/Excluído com sucesso" ou "Erro".

---

## 4. ESQUELETO ESPERADO (PSEUDO-CÓDIGO UI)

```html
<Page>
  <Header>
    <Title>Editor de Oportunidades (Etiquetas)</Title>
    <Button primary onClick="openModal()">+ Nova Oportunidade</Button>
  </Header>

  <Table>
     <Columns>Visual | Nome | Desconto Mín | Desconto Máx | Ações</Columns>
     <Row>
       <Cell><BadgeIcon src="/trofeu-ouro.png" /></Cell>
       <Cell>Oportunidade de Ouro</Cell>
       <Cell>70%</Cell>
       <Cell>80%</Cell>
       <Cell>
          <Button onClick="edit(rule.id)">Editar</Button>
          <Button danger onClick="delete(rule.id)">Excluir</Button>
       </Cell>
     </Row>
  </Table>

  <!-- Modal de Edição/Criação -->
  <Modal isOpen={isModalOpen}>
     <Title>Configurar Oportunidade</Title>
     <Form onSubmit="saveOpportunity(dados)">
        <Input name="nome" label="Nome da Etiqueta (ex: Ouro)" />
        
        <FlexRow>
           <Input name="desconto_minimo" label="Desconto Mínimo (%)" type="number" />
           <Input name="desconto_maximo" label="Desconto Máximo (%)" type="number" />
        </FlexRow>

        <ImageUpload name="icone_imagem" label="Ícone / Imagem da Etiqueta" />
        
        <FormActions>
           <Button type="button" onClick="closeModal()">Cancelar</Button>
           <Button type="submit" isLoading={isSaving}>Salvar Regra</Button>
        </FormActions>
     </Form>
  </Modal>
</Page>

# ESPECIFICAÇÃO TÉCNICA: PÁGINA DE VITRINE E CARDS DE IMÓVEIS

**Contexto para a IA Desenvolvedora:**
Este documento contém as diretrizes estritas para a construção da página de listagem de imóveis e o layout do card de cada imóvel. O foco absoluto desta página é a conversão pela oportunidade financeira (Lucro). 

---

## 1. REGRA DE OURO: DADOS ESTÃO 100% PRONTOS (FETCH & RENDER)

*   **[INSTRUÇÃO CRÍTICA PARA A IA]:** Absolutamente **TODA** a informação, incluindo resultados de cálculos (lucro, margem de desconto, etc.), já virá processada do banco de dados / API.
*   O seu único trabalho como IA é criar o código para **buscar a informação no banco de dados e imprimir no local indicado** do layout. Só isso. Você não deve criar nenhuma lógica matemática.

---

## 2. REGRAS DE EXIBIÇÃO E PAGINAÇÃO

### 2.1. Ordenação (Sorting)
*   **[INSTRUÇÃO PARA A IA]:** A lista de imóveis renderizada na tela deve vir **OBRIGATORIAMENTE** ordenada de forma decrescente pelo campo de Lucro. O imóvel com o maior lucro deve ser o primeiro item do topo esquerdo.

### 2.2. Paginação e Layout da Grade (Grid)
*   **Quantidade:** Exibir exatamente 9 imóveis por página (Paginação Server-Side).
*   **Controles:** Incluir botões de navegação no fim da lista (Ex: "Anterior", "1, 2, 3...", "Próxima").
*   **Responsividade (Grid):**
    *   **Desktop:** Utilizar grid com 3 colunas e 3 linhas.
    *   **Mobile:** Utilizar coluna única para rolagem vertical.

---

## 3. LAYOUT DO CARD DO IMÓVEL (O COMPONENTE)

### 3.1. Cabeçalho do Card (Área da Imagem)
*   **Imagem Externa:** 
    *   O link da imagem (`image_url`) vem do banco de dados e aponta para o servidor do fornecedor/proprietário (Não está na Hostinger local).
    *   **Fallback Obrigatório:** Implementar tratamento de erro (ex: `onError`). Se o link quebrar, exibir uma imagem padrão do sistema no lugar.
*   **Tags Sobrepostas:** 
    *   Posicionar sobre a imagem o Tipo do imóvel (Ex: Apartamento, Casa, Terreno).

### 3.2. Corpo do Card (Informações Básicas)
*   **Localização:** Exibir no formato "Bairro, Cidade - Estado" (truncar com reticências se for muito longo).
*   **Condições:** Exibir selos/badges caso o imóvel aceite FGTS ou Financiamento (estes booleanos vêm do BD).

### 3.3. Área Financeira (Coração do Card)
*   **Valor de Venda:** Exibir em formatação de moeda (BRL).
*   **Lucro Previsto:** Exibir com destaque máximo (fonte maior, em negrito e OBRIGATORIAMENTE na **cor verde**). Formato: `Lucro: R$ XX.XXX,XX`.
*   **Margem de Desconto:** Exibir a porcentagem (`discount_percentage`) que já virá calculada do banco.

### 3.4. Call to Action (Ação)
*   **Botão:** Ocupando 100% da largura útil do card com o texto "Mais Detalhes". Ao clicar, redireciona para a página interna do imóvel usando seu ID.

---

## 4. 🛑 RESTRIÇÕES ABSOLUTAS (O QUE VOCÊ NÃO DEVE FAZER)

Para garantir a performance e a fidelidade ao projeto, você está **PROIBIDA** de:

1. **CRIAR LÓGICA DE CÁLCULO:** NÃO crie funções matemáticas no código para calcular lucro, subtração ou porcentagens. Você só vai pegar a variável que vem do banco e imprimir na tela. Não crie nada além disso.
2. **EXIBIR O VALOR DE AVALIAÇÃO:** O valor original de mercado (avaliação) **NÃO DEVE** aparecer na tela para o usuário, nem mesmo riscado (Ex: ~~R$ 300.000~~). Exiba APENAS o preço de venda e o Lucro.
3. **FAZER PAGINAÇÃO LOCAL:** NÃO puxe todos os imóveis do banco de uma vez para paginar no Front-end. A query ao banco deve pedir estritamente 9 itens por vez.
4. **INVENTAR CAMPOS OU DESIGN:** NÃO adicione informações que não foram pedidas (como corretores, metragem, botões de favoritar). Mantenha o card 100% focado no lucro, sem poluição visual.
5. **USAR BIBLIOTECAS DESNECESSÁRIAS:** NÃO instale bibliotecas de terceiros para fazer animações, formatar textos ou criar carrosséis. Use recursos nativos.

---

## 5. ESQUELETO ESPERADO DO COMPONENTE (PSEUDO-CÓDIGO)

```html
<Card>
  <ImageContainer relative>
    <!-- IA: Imagem vem de URL externa. O Fallback é OBRIGATÓRIO -->
    <ExternalImage src="{dados_do_banco.imagem_url}" onError="{mostrarImagemPadrao}" />
    <Badge absolute> {dados_do_banco.tipo_imovel} </Badge>
  </ImageContainer>
  
  <InfoContainer>
    <Location> {dados_do_banco.bairro}, {dados_do_banco.cidade} </Location>
    
    <FinanceSection>
      <SalePrice> {dados_do_banco.valor_venda} </SalePrice>
      <DiscountBadge> {dados_do_banco.margem_desconto} OFF </DiscountBadge>
      <!-- IA: O valor do lucro já vem pronto do banco! Apenas imprima em verde. -->
      <Profit text-color="green" font-weight="bold">
        Lucro: {dados_do_banco.lucro_absoluto}
      </Profit>
    </FinanceSection>
    
    <ActionButton onClick="{redirecionar(dados_do_banco.id)}">Mais Detalhes</ActionButton>
  </InfoContainer>
</Card>

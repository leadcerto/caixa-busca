# Briefing de Layout — Página de Detalhe de Imóvel

## Objetivo

Criar uma página de detalhe de imóvel usando como referência visual o arquivo:

`references/pagina-imoveis/imovel.html`

Esse arquivo foi extraído de uma página criada no WordPress com Elementor e deve ser usado apenas como **referência visual e estrutural**, não como código final obrigatório.

A nova página deve ser recriada com código limpo, organizado, responsivo e fácil de manter.

---

## Página de Referência

URL original usada como inspiração:

`https://imoveisdacaixa.com.br/casa-a-venda-em-vista-alegre-sao-goncalo-rj-1444402960230/`

Arquivo HTML salvo no projeto:

`references/mpagina-imoveis/imovel.html`

---

## Instrução Principal para o Antigravity

Analise o arquivo `references/modpagina-imoveis/imovel.html` para entender a estrutura visual da página original.

O código original vem do WordPress/Elementor, então provavelmente contém muitas classes, estilos inline, scripts e elementos desnecessários.

Não copie a estrutura do Elementor literalmente.

Use o arquivo apenas para entender:

- Hierarquia visual da página;
- Distribuição dos blocos;
- Ordem das informações;
- Posição das imagens;
- Destaques de preço e contato;
- Organização da descrição do imóvel;
- Layout em desktop e mobile.

A implementação final deve ser feita com HTML, CSS e JavaScript limpos, sem dependência do Elementor.

---

## Estrutura Esperada da Página

A página final deve conter as seguintes seções:

### 1. Cabeçalho

Criar uma área superior simples contendo:

- Logo ou nome do site;
- Menu de navegação, se necessário;
- Botão de contato ou WhatsApp;
- Layout responsivo para mobile.

O cabeçalho não precisa ser idêntico ao original, mas deve manter uma aparência profissional.

---

### 2. Galeria de Imagens do Imóvel

Criar uma área de destaque para as fotos do imóvel.

A galeria deve conter:

- Imagem principal em destaque;
- Miniaturas ou grid de imagens secundárias;
- Botão ou indicação para visualizar mais fotos;
- Comportamento responsivo em telas menores.

Caso ainda não existam imagens reais disponíveis, usar imagens temporárias/placeholders.

---

### 3. Informações Principais do Imóvel

Criar um bloco com as principais informações do imóvel:

- Título do imóvel;
- Tipo do imóvel;
- Cidade;
- Bairro;
- Estado;
- Endereço aproximado, se existir;
- Código ou referência do imóvel;
- Valor do imóvel;
- Valor de avaliação, se existir;
- Valor mínimo de venda, se existir.

Exemplo de campos:

- Casa à venda;
- Vista Alegre;
- São Gonçalo — RJ;
- Código do imóvel;
- Preço de venda.

---

### 4. Bloco de Preço e Chamada para Ação

Criar um bloco visualmente destacado com:

- Preço principal do imóvel;
- Informações comerciais importantes;
- Botão principal de contato;
- Botão para WhatsApp;
- Possível botão para simulação ou proposta.

Esse bloco pode ficar na lateral em telas grandes e abaixo das informações principais no mobile.

O CTA principal deve ter destaque visual.

Exemplo de botões:

- `Tenho interesse`
- `Falar no WhatsApp`
- `Solicitar mais informações`
- `Simular financiamento`

---

### 5. Características do Imóvel

Criar uma seção com os principais atributos do imóvel, como:

- Área privativa;
- Área total;
- Quartos;
- Banheiros;
- Vagas de garagem;
- Tipo do imóvel;
- Situação do imóvel;
- Modalidade de venda;
- Matrícula ou registro, se aplicável.

Apresentar essas informações em cards, ícones ou lista organizada.

---

### 6. Descrição do Imóvel

Criar uma seção textual para apresentar a descrição completa do imóvel.

A descrição deve ser clara, legível e bem espaçada.

Caso o texto seja longo, manter boa hierarquia visual com parágrafos.

---

### 7. Dados Complementares

Criar uma seção para informações adicionais, como:

- Número do imóvel;
- Número da matrícula;
- Comarca;
- Ofício;
- Inscrição imobiliária;
- Situação de ocupação;
- Aceita financiamento;
- Aceita FGTS;
- Origem do imóvel;
- Observações importantes.

Essa seção pode ser apresentada em formato de tabela, lista ou cards.

---

### 8. Localização

Criar uma seção de localização contendo:

- Bairro;
- Cidade;
- Estado;
- Endereço aproximado, caso disponível;
- Mapa incorporado ou placeholder para mapa.

Se não houver mapa disponível, criar uma área visual reservada para futura integração.

---

### 9. Avisos e Informações Legais

Criar uma área para avisos importantes, por exemplo:

- As informações podem sofrer alteração;
- O imóvel pode estar ocupado;
- A venda pode seguir regras específicas;
- O interessado deve consultar os detalhes oficiais antes de tomar decisão.

Essa área deve ter menor destaque que o preço e o CTA, mas deve ser visível.

---

### 10. Rodapé

Criar um rodapé simples contendo:

- Nome do site;
- Links úteis;
- Informações de contato;
- Direitos autorais;
- Possível aviso institucional.

---

## Requisitos Visuais

A nova página deve seguir uma aparência:

- Limpa;
- Moderna;
- Profissional;
- Responsiva;
- De fácil leitura;
- Otimizada para conversão.

Priorizar uma experiência parecida com portais imobiliários modernos.

---

## Cores e Estilo

Usar um estilo visual próximo ao mercado imobiliário.

Sugestão inicial:

- Fundo geral claro;
- Cards brancos;
- Bordas suaves;
- Sombras leves;
- Botões com cor de destaque;
- Preço em destaque;
- Tipografia legível.

A paleta de cores pode ser ajustada conforme o padrão visual do projeto.

---

## Responsividade

A página deve funcionar bem em:

- Desktop;
- Notebook;
- Tablet;
- Celular.

No desktop, a página pode usar layout em duas colunas:

- Coluna principal com imagens, descrição e dados;
- Coluna lateral com preço e contato.

No mobile, todos os blocos devem ficar empilhados em uma única coluna.

---

## Requisitos Técnicos

A implementação deve seguir estas orientações:

- Criar HTML semântico;
- Separar CSS em arquivo próprio;
- Evitar estilos inline desnecessários;
- Evitar dependência do Elementor;
- Evitar dependência do WordPress;
- Criar classes claras e reutilizáveis;
- Manter estrutura fácil de editar;
- Utilizar nomes de classes compreensíveis;
- Garantir boa performance;
- Evitar excesso de JavaScript.

---

## Arquivos Esperados

Se for um projeto HTML simples, criar ou organizar os arquivos assim:

```text
src/
├── index.html
├── styles.css
└── script.js

# 🚀 Documentação Oficial e Arquitetura: Página de Imóveis (Plataforma Anti-Gravity)

> 💡 **EXPLICAÇÃO GERAL PARA LEIGOS:** 
> Este documento é o "manual de instruções" para construir a página onde os investidores vão ver os imóveis. O nosso objetivo principal não é apenas mostrar casas bonitas, mas sim capturar o contato desse investidor de forma inteligente, sem assustá-lo, e garantir que a nossa equipe (e não a imobiliária) fale com ele primeiro para ver se ele é um comprador real ou apenas um curioso.

---

## 🚫 0. O QUE **NÃO** DEVE SER FEITO (Regras Críticas)
*Atenção, Agente de IA: Sob nenhuma circunstância as ações abaixo devem ser implementadas.*

1. **NÃO envie o lead direto para a imobiliária ou corretor:** O sistema nunca deve disparar um e-mail automático com os dados do cliente para o corretor final. Tudo para na nossa Central (SDR) primeiro.
2. **NÃO recarregue a página ao enviar o formulário:** Esqueça o padrão antigo do HTML (`<form action="...">` que dá "refresh" na tela). A página deve ser estática, rápida e tudo deve acontecer nos bastidores (via AJAX).
3. **NÃO use validação fraca de telefone:** Não aceite campos abertos onde o usuário digita letras ou números incompletos. Se não tiver 11 dígitos numéricos e um DDD brasileiro real, o formulário deve travar.
4. **NÃO deixe o Botão de WhatsApp estático/morto:** Ele não pode ser apenas uma imagem parada. Se não tiver a animação de "pulsar" (respirar), está errado.
5. **NÃO mande o cliente para uma tela de "Obrigado" (Redirect):** O usuário deve ficar na mesma página do imóvel para continuar lendo os dados que ele acabou de destravar.

---

## 📌 1. Visão Geral e Modelo de Negócio (Central SDR)

> 💡 **EXPLICAÇÃO PARA LEIGOS:** 
> Imagine que a nossa página é uma peneira. Muita gente entra na internet só para olhar o preço das coisas sem intenção de comprar. Se mandarmos todo mundo para os corretores parceiros, eles vão perder tempo e achar que nosso sistema é ruim. Por isso, criamos a "Central SDR" (uma equipe nossa de pré-vendas). Todo contato cai para nós primeiro. Nós ligamos, confirmamos se o cara tem dinheiro e real interesse. Só repassamos o "filé mignon" (cliente pronto) para a imobiliária.

**Regra Técnica:** 
- O status inicial no banco de dados para todo novo contato gerado pela página deve ser **OBRIGATORIAMENTE** `pending_sdr_validation` (Aguardando Validação da Central). O sistema de repasse será construído em outra etapa.

---

## 🔐 2. O "Cofre do Investidor" (Formulário Híbrido AJAX)

> 💡 **EXPLICAÇÃO PARA LEIGOS:** 
> Para o investidor ver informações valiosas (como a rentabilidade do imóvel ou documentos), ele precisa "pagar" deixando o Nome, E-mail e Telefone. Mas tem que ser rápido! Se a página piscar ou recarregar, ele desiste. Usamos uma tecnologia chamada AJAX que envia os dados por baixo dos panos. E, para evitar que ele digite telefone falso (ex: 00 99999-9999), colocamos travas de segurança invisíveis que bloqueiam o envio se o número não fizer sentido.

### Requisitos Técnicos de Execução (Frontend e Backend):
- **O Comportamento (UX):** O envio deve ser feito via requisição assíncrona (Fetch API, Axios ou Alpine.js). A página não deve sofrer reload.
- **O Sucesso:** Se o Laravel retornar HTTP 200 (Tudo certo), oculte a `div` do formulário usando uma transição suave de CSS (Fade Out) e revele a `div` com os dados sensíveis do imóvel (Fade In).
- **O Erro:** Exibir as mensagens de erro (ex: "E-mail inválido") em tempo real, exatamente embaixo do campo que o usuário errou, com texto em vermelho claro.
- **FormRequest (Laravel Backend):**
  - **E-mail:** Validação estrita (exigir presença do `@` e terminação `.com` ou `.com.br`).
  - **Telefone:** Criar e aplicar uma *Custom Rule* (Regra Customizada no Laravel). Essa regra DEVE checar se a string tem exatos 11 caracteres numéricos. Além disso, DEVE extrair os dois primeiros dígitos (DDD) e validar contra um array de DDDs reais do Brasil (rejeitando sumariamente 00, 10, 99, etc.).

---

## 📱 3. Botão de WhatsApp (A Máquina de Contato)

> 💡 **EXPLICAÇÃO PARA LEIGOS:** 
> O botão do WhatsApp é a estrela da página. Quando o cliente clica, o celular dele já abre o WhatsApp com um texto pronto dizendo: *"Oi, gostei do imóvel X, no bairro Y, que custa Z. Aqui está o link que eu estava olhando"*. Isso é mágico porque a nossa equipe de atendimento já recebe a mensagem sabendo exatamente o que o cliente quer, sem ele precisar digitar nada. Além disso, o botão fica "pulsando" na tela, como um coração, para atrair o olho do cliente e forçar o clique.

### Requisitos Técnicos de Execução:
- **A URL Estratégica:** Utilize estritamente a API base `https://api.whatsapp.com/send`. Isso previne bugs que acontecem em links `wa.me` quando acessados via computadores Desktop.
- **Parâmetros Obrigatórios:** 
  - `phone=NUMERO_DA_CENTRAL`
  - `type=phone_number`
  - `app_absent=0`
  - `text=MENSAGEM_CODIFICADA`
- **Montagem do Texto Dinâmico (Blade):** O Laravel deve injetar na string (com `urlencode`) as variáveis do imóvel. Formato exato do template a ser montado:
  *"Olá! Tenho interesse no {TIPO_IMOVEL} código {ID_IMOVEL}, localizado em {BAIRRO} - {CIDADE}/{UF}, valor R$ {VALOR}. Link: {URL_ATUAL_DA_PAGINA}."*
- **Animação Visual (CSS):** Criar uma animação de `pulse`. Pode ser via TailwindCSS (classes customizadas no `tailwind.config.js`) ou CSS puro usando `@keyframes` que alterne o `transform: scale` e o `box-shadow` do botão de forma contínua e infinita.

---

## 📊 4. Estrutura de Dados e Score Visual

> 💡 **EXPLICAÇÃO PARA LEIGOS:** 
> Nós queremos que o investidor bata o olho e pense: "Esse imóvel é uma oportunidade nota 10!". Por isso, os dados do imóvel não são apenas textos soltos. O sistema vai receber um pacote de dados (JSON) que inclui um "Score" (uma nota). Nós vamos transformar essa nota em um desenho bonito na tela, como 5 estrelas douradas ou um termômetro de oportunidade, para ajudar na decisão psicológica de compra.

### Requisitos Técnicos de Execução:
- **Estrutura Base:** Os dados serão consumidos na View primariamente através de um objeto JSON contendo todas as propriedades do imóvel.
- **Lógica de View (Blade/Componente):** Criar um componente isolado para renderizar o Score do Imóvel. Ele deve ler o valor numérico (ex: 9.5) e converter isso em uma representação visual clara e elegante (estrelas preenchidas/vazadas, barra de progresso ou badge colorida).

---

## 🤖 5. Instruções Finais para a Geração de Código
Ao processar este documento, atue como um Desenvolvedor Full-Stack Sênior em Laravel:

1. **Backend Primeiro:** Gere a estrutura de Rotas (web/api), o Controller e o `FormRequest` com a regra customizada de DDD solicitada.
2. **Frontend Interativo:** Gere a View Blade com o layout do formulário, embutindo o script JS (Fetch/Axios) para submissão sem reload, tratamento de resposta e manipulação do DOM (esconder form, mostrar dados).
3. **Botão e Componentes:** Inclua o código HTML/Blade do botão do WhatsApp com os dados dinâmicos e o CSS para a animação.
4. **Comente o Código:** Coloque comentários explicativos no próprio código PHP e JS gerado (DocBlocks, explicações de lógica) para facilitar futuras manutenções da equipe humana. Siga fielmente todas as regras da seção "O QUE NÃO DEVE SER FEITO".

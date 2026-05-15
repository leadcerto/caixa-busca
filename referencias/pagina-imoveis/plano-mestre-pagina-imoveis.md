🏗️ Plano Mestre: Arquitetura e Estratégia da Página de Imóveis
Projeto: Plataforma de Captação e Venda de Imóveis (Foco em Investidores/Caixa) Proprietário: Leonardo Objetivo: Criar uma página de apresentação de imóveis que seja rápida, elegante e, acima de tudo, uma máquina de conversão e qualificação de leads.

1. 🧠 O Modelo de Negócio: A Central SDR (Pré-Qualificação)
A maior decisão estratégica do projeto. O sistema não enviará os contatos de forma automática e "cega" para as imobiliárias.

Como vai funcionar: Todos os leads (contatos) gerados na página cairão primeiro na sua Central de Atendimento.
O Papel da sua Central: Atuar como um SDR (Sales Development Representative). Sua equipe vai pegar os dados, confirmar nome, telefone, e-mail e entender o real interesse.
O Repasse: Só após essa triagem o lead será "aberto" e enviado para a imobiliária parceira.
Por que isso é genial?
A imobiliária recebe um "filé mignon" (lead validado e pronto para comprar), aumentando drasticamente o valor do seu serviço.
Blinda os corretores de perderem tempo com curiosos, números falsos ou e-mails inválidos.
Você mantém o controle absoluto sobre o funil de vendas e sabe exatamente o que está gerando resultado.
2. 🔐 O "Cofre do Investidor" e a Validação Híbrida (Caminho 3)
Para acessar dados sensíveis ou financeiros do imóvel, o usuário precisará deixar o contato. Queremos que isso seja rápido e sem dor.

A Tecnologia (AJAX): O formulário vai destravar os dados na própria tela, na mesma hora, sem precisar recarregar a página. Isso gera uma experiência de usuário (UX) maravilhosa e moderna.
As Travas de Segurança (Validação Híbrida): Para evitar que o usuário digite "qualquer coisa" só para ver os dados, o formulário terá inteligência embutida:
E-mail: Precisará ter um formato real (com @ e .com ou .com.br).
Telefone: Terá que ter um DDD válido (nada de 00 ou 99) e conter exatamente 11 dígitos.
O Benefício: Filtramos a maior parte do "lixo" logo na entrada. Se um usuário muito insistente colocar um número falso que passe na regra (ex: 21999999999), a sua Central SDR (citada no passo 1) vai barrar esse cara depois. Temos o equilíbrio perfeito entre velocidade para o usuário e segurança para a imobiliária.
3. 📱 O Botão de WhatsApp (A Máquina de Contato Direto)
O botão de WhatsApp será o principal canal de ação rápida da página. Ele foi desenhado para ser à prova de falhas.

Atenção ao Usuário (Desktop e Mobile): Usaremos a API oficial do WhatsApp (https://api.whatsapp.com/send). Isso garante que, se o usuário estiver no celular, abre o app. Se estiver no computador (Desktop), abre o WhatsApp Web sem erros. Funciona 100% perfeito.
Rastreio Automático (Inteligência de Origem): Quando o usuário clicar, a mensagem já vai pronta para o seu atendimento, contendo um "raio-x" do que ele quer:
Tipo do Imóvel e Número de Identificação (ID).
Localização (Bairro, Cidade/Estado).
Valor de venda.
O Link (URL) exato da página onde ele estava.
Micro-interação (Pulsar): O botão não será estático. Ele terá uma animação sutil de "pulsar" (como se estivesse respirando) para chamar a atenção dos olhos do usuário e aumentar a taxa de cliques.
4. 📊 Estrutura de Dados e Score (O Cérebro da Página)
JSON e Score: A página será alimentada por um arquivo de dados (JSON) que trará todas as informações do imóvel de forma rápida. O sistema de Score (pontuação de atratividade do imóvel) será um dos grandes diferenciais visuais para ajudar o investidor a tomar a decisão.
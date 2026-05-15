# SISTEMA DE CAPTAÇÃO DE LEADS — IMÓVEIS COM DESCONTO

## O QUE É O SISTEMA

Este sistema é uma plataforma web desenvolvida para importar uma lista de imóveis que
estão à venda com mais de 30% de desconto, incrementar as informações, adicionar
informações relevantes, estar 100% acessível para as ferramentas de busca e entregar
aos interessados o maior número de informações após a realização de um filtro de busca
relevante para os interessados.

O sistema é um intermediário inteligente, fazendo a publicação dos imóveis para
captação de leads qualificados e distribuição de leads para a imobiliária responsável
pelo atendimento da região onde o imóvel está localizado (uma imobiliária para cada
estado).

O visitante acessa o site, pesquisa imóveis disponíveis, demonstra interesse e o
sistema cuida de todo o restante: registra o lead, identifica qual imobiliária é
responsável pelo imóvel em questão e envia automaticamente as informações de contato
desse comprador para a imobiliária via e-mail e WhatsApp. Tudo isso sem que a
imobiliária precise operar ativamente dentro da plataforma.


## COMO O SISTEMA FUNCIONA NA PRÁTICA

Os imóveis disponíveis na plataforma são importados automaticamente a partir de um
arquivo CSV fornecido periodicamente pelo administrador do sistema. Esse arquivo contém
todos os dados dos imóveis disponíveis para venda e o sistema processa essas
informações, atualiza a base de dados e mantém o catálogo sempre atualizado.

A cada nova importação do CSV, o sistema preserva os dados originais do imóvel
integralmente na tabela de imóveis e registra os dados variáveis — como valores e
modalidade de venda — na tabela de histórico, permitindo acompanhar a evolução de
preço e o tempo de mercado de cada imóvel ao longo do tempo.

Cada imóvel possui informações como endereço, cidade, estado, tipo do imóvel, valor
de avaliação, valor mínimo de venda, modalidade de venda, além de links para o
edital oficial. O sistema trabalha com apenas dois tipos de imagem: a foto da fachada,
que é uma imagem tirada da rua e hospedada em servidores externos — onde o sistema
armazena apenas a URL que aponta para ela —, e uma imagem de destaque institucional,
pequena e genérica, que aparece quando um link do site é compartilhado no WhatsApp
ou em outras redes sociais, funcionando como um convite visual para que a pessoa
clique e acesse mais informações.

O visitante pode navegar pelo site e utilizar filtros de busca como estado, município,
bairro, tipo de imóvel, faixa de valor e modalidade de venda para encontrar os imóveis
de seu interesse. Os resultados dessa busca podem ser compartilhados via link,
gerando a pré-visualização com a imagem de destaque.

Quando o visitante encontra um imóvel de seu interesse e deseja saber mais, ele
preenche um formulário de contato. Esse preenchimento gera um lead no sistema e
também realiza o cadastro do visitante na plataforma. A partir desse momento, o
sistema identifica automaticamente qual imobiliária é responsável pelo estado do
imóvel, registra o atendimento vinculado a ela e dispara as notificações por e-mail e
WhatsApp com todas as informações do comprador e do imóvel de interesse.


## OS PERFIS DE USUÁRIO

O sistema possui três perfis distintos de usuário, cada um com um nível de acesso
e uma função bem definida.

O primeiro perfil é o do administrador. São os usuários internos do sistema, em
número reduzido, entre dois e três pessoas. Eles têm acesso total à plataforma e
são responsáveis por gerenciar os imóveis, as imobiliárias parceiras, os leads
captados e todas as configurações gerais do sistema.

O segundo perfil é o da imobiliária. Cada imobiliária parceira possui um cadastro
na plataforma com e-mail e senha próprios. Ao acessar o sistema, ela visualiza um
painel simples e objetivo onde pode consultar todos os atendimentos que recebeu, com
informações como data de geração do lead, nome do comprador, e-mail, telefone e
o imóvel de interesse. Esse painel é exclusivamente visual, ou seja, a imobiliária
não

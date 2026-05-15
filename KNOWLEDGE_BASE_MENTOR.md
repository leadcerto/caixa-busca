# KNOWLEDGE BASE - MENTOR (ANTIGRAVITY)

Este arquivo Ã© a fonte Ãºnica de verdade para o Mentor. Ele consolida toda a documentaÃ§Ã£o tÃ©cnica e de negÃ³cio.

---
# FILE: AI_GUIDELINES.md
---

# Diretrizes de Uso de IA

Este documento define como a IA deve ser utilizada neste projeto.

O objetivo Ã© usar IA como ferramenta de apoio para acelerar trabalho, melhorar clareza e reduzir esforÃ§o repetitivo, sem abrir mÃ£o de revisÃ£o humana, responsabilidade tÃ©cnica e controle sobre as decisÃµes do projeto.

---

## 1. IA Ã© ferramenta, nÃ£o piloto automÃ¡tico

A IA deve apoiar o desenvolvimento, mas nÃ£o deve conduzir o projeto sem direÃ§Ã£o humana.

Ela pode ajudar em:

- AnÃ¡lise de problemas
- OrganizaÃ§Ã£o de ideias
- Escrita de documentaÃ§Ã£o
- GeraÃ§Ã£o inicial de cÃ³digo
- RefatoraÃ§Ã£o assistida
- CriaÃ§Ã£o de testes
- RevisÃ£o de cÃ³digo
- ExplicaÃ§Ã£o de trechos complexos
- SugestÃ£o de melhorias

Mas a decisÃ£o final deve ser sempre humana.

A IA nÃ£o deve substituir:

- Entendimento do problema
- Julgamento tÃ©cnico
- ValidaÃ§Ã£o de seguranÃ§a
- RevisÃ£o de impacto
- Testes
- Responsabilidade pelo cÃ³digo entregue

---

## 2. Trabalhar com contexto claro

Antes de pedir algo para a IA, forneÃ§a contexto suficiente.

Sempre que possÃ­vel, inclua:

- Objetivo da tarefa
- Arquivos relevantes
- Regras de negÃ³cio
- RestriÃ§Ãµes tÃ©cnicas
- PadrÃµes jÃ¡ usados no projeto
- O que deve ser alterado
- O que nÃ£o deve ser alterado
- CritÃ©rios de aceitaÃ§Ã£o

Pedidos vagos tendem a gerar respostas genÃ©ricas, incompletas ou incompatÃ­veis com o projeto.

---

## 3. Preferir tarefas pequenas

A IA deve ser usada preferencialmente em tarefas pequenas e bem definidas.

Evitar pedir para a IA fazer mudanÃ§as muito amplas de uma sÃ³ vez.

Preferir pedidos como:

- â€œCrie testes para esta funÃ§Ã£oâ€
- â€œRefatore este trecho sem mudar comportamentoâ€
- â€œExplique este erroâ€
- â€œSugira uma estrutura para este documentoâ€
- â€œImplemente apenas esta validaÃ§Ã£oâ€
- â€œRevise este arquivo procurando inconsistÃªnciasâ€

Evitar pedidos como:

- â€œReescreva todo o projetoâ€
- â€œMelhore tudoâ€
- â€œRefatore toda a arquiteturaâ€
- â€œImplemente esse mÃ³dulo inteiro sem especificaÃ§Ã£oâ€
- â€œFaÃ§a do jeito que achar melhorâ€

MudanÃ§as pequenas sÃ£o mais fÃ¡ceis de revisar, testar e reverter.

---

## 4. Especificar antes de implementar

Antes de pedir cÃ³digo para a IA, defina minimamente:

- O problema a ser resolvido
- O comportamento esperado
- Entradas e saÃ­das
- Casos principais
- Casos de erro
- LimitaÃ§Ãµes conhecidas
- CritÃ©rios de aceitaÃ§Ã£o

A IA funciona melhor quando existe uma especificaÃ§Ã£o clara.

A especificaÃ§Ã£o nÃ£o precisa ser longa, mas precisa reduzir ambiguidade.

---

## 5. Revisar tudo que a IA gerar

Todo conteÃºdo gerado por IA deve ser revisado antes de ser aceito.

Isso inclui:

- CÃ³digo
- Testes
- DocumentaÃ§Ã£o
- ConfiguraÃ§Ãµes
- Scripts
- MigraÃ§Ãµes
- Mensagens de erro
- Regras de negÃ³cio

Nunca assumir que uma resposta da IA estÃ¡ correta apenas porque parece bem escrita.

Verificar especialmente:

- Se resolve o problema certo
- Se nÃ£o remove comportamento necessÃ¡rio
- Se nÃ£o adiciona complexidade desnecessÃ¡ria
- Se segue os padrÃµes do projeto
- Se nÃ£o introduz falhas de seguranÃ§a
- Se nÃ£o quebra fluxos existentes
- Se Ã© compreensÃ­vel para outra pessoa

---

## 6. NÃ£o aceitar cÃ³digo sem entender

NÃ£o deve ser aceito cÃ³digo que ninguÃ©m entende.

Antes de incorporar uma soluÃ§Ã£o sugerida por IA, a pessoa responsÃ¡vel deve conseguir explicar:

- O que o cÃ³digo faz
- Por que ele foi escrito dessa forma
- Quais alternativas existiam
- Quais riscos existem
- Como testar o comportamento
- Como reverter se necessÃ¡rio

Se a soluÃ§Ã£o parece â€œmÃ¡gicaâ€, complexa demais ou difÃ­cil de explicar, ela deve ser simplificada ou recusada.

---

## 7. Manter simplicidade

A IA pode sugerir soluÃ§Ãµes mais complexas do que o necessÃ¡rio.

Sempre avaliar se a resposta segue a filosofia do projeto:

- Resolve o problema atual?
- Ã‰ simples o suficiente?
- Adiciona abstraÃ§Ãµes desnecessÃ¡rias?
- Cria dependÃªncias sem necessidade?
- Introduz padrÃµes complexos cedo demais?
- Aumenta o custo de manutenÃ§Ã£o?

Preferir soluÃ§Ãµes diretas, claras e fÃ¡ceis de manter.

---

## 8. Evitar mudanÃ§as fora do escopo

Ao usar IA, deve-se tomar cuidado com alteraÃ§Ãµes nÃ£o solicitadas.

A IA nÃ£o deve:

- Reformatar arquivos inteiros sem necessidade
- Alterar nomes pÃºblicos sem justificativa
- Remover cÃ³digo funcional sem explicaÃ§Ã£o
- Trocar bibliotecas sem aprovaÃ§Ã£o
- Mudar arquitetura sem discussÃ£o
- Criar abstraÃ§Ãµes fora do escopo
- Alterar comportamento existente sem avisar

Toda mudanÃ§a deve ter motivo claro.

---

## 9. SeguranÃ§a em primeiro lugar

A IA pode sugerir cÃ³digo inseguro ou incompleto.

Revisar com atenÃ§Ã£o pontos relacionados a:

- AutenticaÃ§Ã£o
- AutorizaÃ§Ã£o
- Dados sensÃ­veis
- ValidaÃ§Ã£o de entrada
- Controle de acesso
- InjeÃ§Ã£o de cÃ³digo
- SQL Injection
- XSS
- CSRF
- ExposiÃ§Ã£o de variÃ¡veis de ambiente
- Logs com informaÃ§Ãµes sensÃ­veis
- ManipulaÃ§Ã£o de arquivos
- DependÃªncias externas

Nunca inserir em prompts:

- Senhas
- Tokens
- Chaves privadas
- Credenciais
- Dados pessoais sensÃ­veis
- Segredos de produÃ§Ã£o

Se for necessÃ¡rio discutir um exemplo, usar valores fictÃ­cios.

---

## 10. NÃ£o compartilhar segredos com IA

InformaÃ§Ãµes sensÃ­veis nÃ£o devem ser enviadas para ferramentas de IA.

NÃ£o enviar:

- Arquivos `.env` reais
- Tokens de API
- Credenciais de banco de dados
- Certificados privados
- Chaves SSH
- Dados de clientes
- InformaÃ§Ãµes financeiras privadas
- Dados pessoais sensÃ­veis
- Logs contendo segredos

Quando necessÃ¡rio, mascarar os dados:

```txt
DATABASE_URL=mysql://user:password@host:3306/db
API_KEY=example_api_key
TOKEN=example_token
```

---

## 11. Usar IA para documentaÃ§Ã£o

A IA pode ser usada para melhorar a documentaÃ§Ã£o do projeto.

Usos recomendados:

- Criar rascunhos de documentos
- Melhorar clareza de textos
- Padronizar linguagem
- Resumir decisÃµes tÃ©cnicas
- Criar checklists
- Explicar fluxos
- Gerar exemplos de uso
- Revisar inconsistÃªncias

Mesmo assim, a documentaÃ§Ã£o deve ser revisada para garantir que corresponde ao funcionamento real do projeto.

DocumentaÃ§Ã£o errada pode causar mais problemas do que ausÃªncia de documentaÃ§Ã£o.

---

## 12. Usar IA para testes

A IA pode ajudar a criar testes, mas os testes precisam ser avaliados criticamente.

Ao pedir testes para a IA, informar:

- O comportamento esperado
- Casos principais
- Casos de erro
- Limites importantes
- DependÃªncias externas
- Dados de exemplo

Verificar se os testes:

- Testam comportamento, nÃ£o implementaÃ§Ã£o interna desnecessÃ¡ria
- Cobrem cenÃ¡rios relevantes
- NÃ£o sÃ£o frÃ¡geis demais
- NÃ£o apenas repetem a lÃ³gica do cÃ³digo
- SÃ£o fÃ¡ceis de entender
- Falham quando o comportamento estÃ¡ incorreto

Testes gerados por IA nÃ£o devem ser aceitos sem execuÃ§Ã£o e revisÃ£o.

---

## 13. Usar IA para refatoraÃ§Ã£o

A IA pode apoiar refatoraÃ§Ãµes, desde que o escopo seja controlado.

Antes de refatorar com IA:

- Definir o objetivo da refatoraÃ§Ã£o
- Garantir que existem testes ou validaÃ§Ã£o mÃ­nima
- Pedir para preservar comportamento
- Evitar mudanÃ§as funcionais misturadas
- Revisar o diff cuidadosamente

RefatoraÃ§Ã£o deve melhorar:

- Clareza
- OrganizaÃ§Ã£o
- RemoÃ§Ã£o de duplicaÃ§Ã£o
- Legibilidade
- ManutenÃ§Ã£o

NÃ£o deve adicionar complexidade apenas por estÃ©tica tÃ©cnica.

---

## 14. Usar IA para revisÃ£o de cÃ³digo

A IA pode ser usada como uma camada adicional de revisÃ£o.

Ela pode ajudar a identificar:

- Bugs provÃ¡veis
- Trechos confusos
- DuplicaÃ§Ãµes
- Falhas de validaÃ§Ã£o
- Problemas de seguranÃ§a
- Casos de erro ausentes
- Nomes pouco claros
- Complexidade desnecessÃ¡ria

Mas a revisÃ£o da IA nÃ£o substitui revisÃ£o humana.

A IA pode nÃ£o entender corretamente o contexto do projeto ou pode apontar problemas irrelevantes.

---

## 15. Solicitar explicaÃ§Ãµes quando necessÃ¡rio

Sempre que a IA sugerir uma soluÃ§Ã£o, Ã© vÃ¡lido pedir explicaÃ§Ã£o.

Perguntas Ãºteis:

- Por que essa abordagem?
- Existe uma soluÃ§Ã£o mais simples?
- Quais sÃ£o os riscos?
- Que comportamento pode quebrar?
- Como testar isso?
- Quais alternativas existem?
- Essa abstraÃ§Ã£o Ã© realmente necessÃ¡ria?
- O que foi alterado exatamente?

A IA deve ajudar a aumentar entendimento, nÃ£o apenas gerar cÃ³digo.

---

## 16. Validar comandos antes de executar

Comandos sugeridos por IA devem ser lidos antes de serem executados.

Ter atenÃ§Ã£o especial com comandos que:

- Apagam arquivos
- Alteram permissÃµes
- Instalam pacotes
- Modificam banco de dados
- Fazem deploy
- Alteram configuraÃ§Ã£o global
- Usam `sudo`
- Usam `rm -rf`
- Executam scripts externos
- Baixam conteÃºdo da internet

Nunca executar comandos destrutivos sem entender completamente o impacto.

---

## 17. DependÃªncias sugeridas por IA

A IA pode sugerir bibliotecas ou ferramentas externas, mas nenhuma dependÃªncia deve ser adicionada automaticamente.

Antes de adicionar uma dependÃªncia, avaliar:

- Ã‰ realmente necessÃ¡ria?
- O projeto jÃ¡ possui algo equivalente?
- A biblioteca Ã© mantida?
- Tem boa documentaÃ§Ã£o?
- Tem licenÃ§a compatÃ­vel?
- Aumenta muito o tamanho ou complexidade?
- Pode criar risco de seguranÃ§a?
- O benefÃ­cio compensa o custo de manutenÃ§Ã£o?

Preferir usar recursos jÃ¡ existentes no projeto quando possÃ­vel.

---

## 18. Commits e pull requests com apoio de IA

A IA pode ajudar a escrever mensagens de commit e descriÃ§Ãµes de pull request.

Uma boa descriÃ§Ã£o deve incluir:

- O que foi alterado
- Por que foi alterado
- Como foi testado
- Riscos conhecidos
- PendÃªncias, se houver

Evitar descriÃ§Ãµes genÃ©ricas como:

- â€œajustesâ€
- â€œmelhoriasâ€
- â€œcorreÃ§Ãµesâ€
- â€œupdateâ€
- â€œfixâ€

MudanÃ§as feitas com apoio de IA continuam sendo responsabilidade da pessoa que as submeteu.

---

## 19. Prompts recomendados

Exemplos de prompts Ãºteis para este projeto:

```txt
Analise este cÃ³digo e aponte problemas de clareza, duplicaÃ§Ã£o e manutenÃ§Ã£o. NÃ£o altere comportamento.
```

```txt
Refatore este trecho para melhorar legibilidade, mantendo exatamente o mesmo comportamento.
```

```txt
Crie testes para os seguintes cenÃ¡rios. Priorize comportamento observÃ¡vel e casos de erro.
```

```txt
Explique este erro e sugira uma correÃ§Ã£o simples, sem mudar a arquitetura.
```

```txt
Revise esta documentaÃ§Ã£o e melhore clareza, objetividade e consistÃªncia.
```

```txt
Sugira uma implementaÃ§Ã£o mÃ­nima para este requisito, evitando abstraÃ§Ãµes prematuras.
```

```txt
Compare duas abordagens para este problema considerando simplicidade, manutenÃ§Ã£o e risco.
```

```txt
Liste possÃ­veis impactos desta mudanÃ§a antes de implementar.
```

---

## 20. Prompts a evitar

Evitar prompts vagos ou amplos demais:

```txt
Melhore esse cÃ³digo.
```

```txt
FaÃ§a tudo.
```

```txt
Refatore o projeto inteiro.
```

```txt
Implemente da melhor forma possÃ­vel.
```

```txt
Crie uma arquitetura completa.
```

```txt
Corrija todos os problemas.
```

```txt
Adicione qualquer coisa que achar necessÃ¡rio.
```

Esses pedidos aumentam o risco de mudanÃ§as fora do escopo, complexidade desnecessÃ¡ria e retrabalho.

---

## 21. Checklist antes de aceitar uma resposta da IA

Antes de aceitar uma sugestÃ£o da IA, verificar:

- [ ] A soluÃ§Ã£o resolve o problema pedido?
- [ ] EstÃ¡ dentro do escopo?
- [ ] Eu entendo o que foi gerado?
- [ ] O cÃ³digo segue os padrÃµes do projeto?
- [ ] NÃ£o hÃ¡ segredos ou dados sensÃ­veis expostos?
- [ ] NÃ£o foram adicionadas dependÃªncias desnecessÃ¡rias?
- [ ] NÃ£o houve mudanÃ§a de comportamento nÃ£o solicitada?
- [ ] A soluÃ§Ã£o Ã© simples o suficiente?
- [ ] Existem testes ou validaÃ§Ã£o mÃ­nima?
- [ ] A documentaÃ§Ã£o foi atualizada, se necessÃ¡rio?
- [ ] O impacto foi revisado?
- [ ] Ã‰ possÃ­vel reverter a mudanÃ§a se der problema?

---

## 22. Checklist para prompts melhores

Antes de pedir ajuda para a IA, verificar:

- [ ] Expliquei o objetivo?
- [ ] Informei o contexto necessÃ¡rio?
- [ ] Defini o escopo?
- [ ] Disse o que nÃ£o deve ser alterado?
- [ ] IncluÃ­ exemplos, se necessÃ¡rio?
- [ ] Informei critÃ©rios de aceitaÃ§Ã£o?
- [ ] Pedi uma soluÃ§Ã£o simples?
- [ ] Pedi explicaÃ§Ã£o quando havia risco?
- [ ] Dividi a tarefa em partes pequenas?

---

## 23. Quando nÃ£o usar IA

Evitar usar IA quando:

- A tarefa envolve segredos ou dados sensÃ­veis
- NÃ£o hÃ¡ contexto suficiente para uma boa resposta
- NinguÃ©m poderÃ¡ revisar o resultado
- O impacto da mudanÃ§a Ã© alto e pouco compreendido
- A soluÃ§Ã£o exige decisÃ£o estratÃ©gica sem discussÃ£o humana
- A resposta serÃ¡ aplicada automaticamente sem validaÃ§Ã£o
- O problema ainda nÃ£o foi entendido

Nesses casos, primeiro esclarecer o problema, reduzir o escopo ou discutir com uma pessoa responsÃ¡vel.

---

## 24. Responsabilidade

Todo cÃ³digo, documento ou decisÃ£o criada com apoio de IA pertence Ã  responsabilidade da pessoa que aceitou e aplicou a sugestÃ£o.

A frase â€œfoi a IA que fezâ€ nÃ£o justifica:

- CÃ³digo quebrado
- Falhas de seguranÃ§a
- Comportamento incorreto
- DocumentaÃ§Ã£o falsa
- MudanÃ§as fora do escopo
- Falta de testes
- Falta de revisÃ£o

A IA pode ajudar, mas a responsabilidade continua sendo humana.

---

## 25. PrincÃ­pio central

O princÃ­pio central para uso de IA neste projeto Ã©:

> Usar IA para acelerar trabalho claro, pequeno e revisÃ¡vel, mantendo controle humano sobre entendimento, decisÃ£o, qualidade e responsabilidade.

A IA deve tornar o projeto mais simples, mais claro e mais sustentÃ¡vel.

Se o uso da IA estiver aumentando confusÃ£o, complexidade ou risco, o processo deve ser revisto.


---
# FILE: REQUISITOS.md
---

DOCUMENTO MESTRE DE REQUISITOS (D.R.S.)
Projeto: Plataforma de Busca de ImÃ³veis (Caixa EconÃ´mica Federal) Escopo Principal: MÃ¡quina de busca, exibiÃ§Ã£o de imÃ³veis, enriquecimento de dados e captaÃ§Ã£o de intenÃ§Ã£o de compra (Lead). Arquitetura: MicrosserviÃ§os / Sistema Desacoplado (O sistema nÃ£o farÃ¡ papel de CRM, envio de e-mail ou gestÃ£o de equipe).

ðŸ—ï¸ ARQUITETURA E ESTRUTURA DE DESENVOLVIMENTO
1. Arquitetura Modular (Domain-Driven Design)
Abordagem: O sistema deve abandonar o padrÃ£o MVC monolÃ­tico clÃ¡ssico. A estrutura de pastas deve seguir uma abordagem modular, isolando cada contexto do negÃ³cio.
Estrutura de DiretÃ³rios: Deve existir uma pasta principal de orquestraÃ§Ã£o (ex: Modules/). Dentro dela, uma subpasta para cada mÃ³dulo (ex: Modules/Imoveis/, Modules/ImportacaoCSV/, Modules/WhatsApp/).
Encapsulamento: Cada pasta de mÃ³dulo deve conter todos os seus respectivos Controllers, Models, Rotas, ServiÃ§os e Views.
2. AutodocumentaÃ§Ã£o
ApÃ³s a criaÃ§Ã£o de cada mÃ³dulo, deve ser gerado automaticamente um arquivo de documentaÃ§Ã£o (ex: docs.md ou api_swagger.yaml) dentro da pasta do respectivo mÃ³dulo.
O arquivo deve explicar como o mÃ³dulo foi construÃ­do, endpoints de API (rotas), parÃ¢metros e lÃ³gica de operaÃ§Ã£o.
3. CÃ³digo 100% Comentado
Ã‰ obrigatÃ³rio que todo o cÃ³digo gerado possua comentÃ¡rios claros e descritivos.
VariÃ¡veis, campos de banco de dados, funÃ§Ãµes e lÃ³gicas complexas devem ser comentadas para que qualquer programador no futuro compreenda exatamente o que foi feito.
ðŸ’¾ BANCO DE DADOS E PROCESSAMENTO DE DADOS
4. ImportaÃ§Ã£o e Tratamento do CSV (Caixa)
Rotina Robusta: O script de importaÃ§Ã£o deve ler o arquivo CSV fornecido pela Caixa usando encoding `ISO-8859-1`.
ExtraÃ§Ã£o de Metadados: Antes do parsing das colunas, o serviÃ§o deve ler a Linha 1 para extrair a "Data de GeraÃ§Ã£o" via Regex e injetÃ¡-la em cada registro.
Pulo de Linhas: O parser deve considerar a Linha 2 como Header e obrigatoriamente pular (skip) a Linha 3 (separadores vazios).
Quebra CirÃºrgica da DescriÃ§Ã£o (Parse/Regex): A coluna de "DescriÃ§Ã£o" do CSV NÃƒO deve ser salva como um texto Ãºnico. O backend farÃ¡ um explode/split por vÃ­rgulas para extrair tipo de imÃ³vel, quartos, vagas, etc.
ExclusÃ£o de Coluna: A coluna original "Link de acesso" deve ser descartada; o sistema usarÃ¡ rotas internas para os imÃ³veis.

5. DossiÃª de Bairros (Scraping) e Colunas JSON
Abordagem HÃ­brida (SQL + NoSQL): O banco de dados usarÃ¡ colunas JSON nativas estritamente para dados de leitura pesada que NÃƒO farÃ£o parte de filtros de pesquisa.
Scraping: Os dados de infraestrutura, IDH, seguranÃ§a e escolas de cada bairro serÃ£o capturados por scraping e salvos em um arquivo Ãºnico JSON.
Armazenamento: Esses dados serÃ£o armazenados em uma Ãºnica coluna dados_bairro (tipo JSON) para garantir um carregamento ultrarrÃ¡pido na pÃ¡gina, sem necessidade de consultas complexas (JOINs).
ðŸŽ¨ FRONTEND, SEO E IMAGENS
6. GestÃ£o de Imagens
Foto do ImÃ³vel: NÃ£o haverÃ¡ tabela de galeria. SerÃ¡ utilizada a imagem Ãºnica (fachada) hospedada diretamente no servidor da Caixa. O sistema apenas carregarÃ¡ essa URL externa.
Imagem de Destaque (Open Graph/SEO): Para compartilhamento de links (WhatsApp, Redes Sociais), o sistema usarÃ¡ uma imagem Ãºnica, no formato .jpeg, hospedada localmente.
Regra de SEO: Essa imagem de destaque serÃ¡ obrigatoriamente renomeada de forma dinÃ¢mica com o tÃ­tulo (slug) correspondente Ã  URL da pÃ¡gina do imÃ³vel.
7. Interface de UsuÃ¡rio (PÃ¡gina do ImÃ³vel)
A pÃ¡gina do imÃ³vel serÃ¡ focada em retenÃ§Ã£o e engajamento.
As informaÃ§Ãµes detalhadas e o DossiÃª do Bairro devem ser organizadas em seÃ§Ãµes retrÃ¡teis (Sanfonas / Accordions) para manter o layout limpo.
ðŸ“Š RASTREAMENTO, ANALYTICS E CONVERSÃƒO
8. InteligÃªncia de Rastreamento
UTMs e Cookies: Captura inteligente e persistente de todas as UTMs de campanhas de trÃ¡fego (Source, Medium, Campaign, Term, Content).
Google Tag Manager (GTM): Monitoramento profundo do comportamento do usuÃ¡rio:
Tempo de tela.
Cliques para expandir as "Sanfonas" de conteÃºdo.
Cliques no botÃ£o de WhatsApp.
9. Mensagens DinÃ¢micas de WhatsApp (Templates)
O Painel Administrativo terÃ¡ um mÃ³dulo para criar Templates da primeira mensagem que o lead enviarÃ¡ ao corretor.
Funcionamento: Quando o usuÃ¡rio clica em "Falar com Corretor", o sistema gera um link do WhatsApp (api.whatsapp.com/send) substituindo variÃ¡veis dinÃ¢micas (ex: {{tipo_imovel}}, {{id_imovel}}, {{bairro}}) para que o cliente envie uma mensagem jÃ¡ formatada e detalhada com um Ãºnico clique.
Nenhuma mensagem deve ser fixada no cÃ³digo (hardcoded).
ðŸ”Œ INTEGRAÃ‡ÃƒO E SEGURANÃ‡A
10. IntegraÃ§Ã£o com o CRM (Webhooks)
Arquitetura Orientada a Eventos: Como o sistema Ã© focado apenas em busca, qualquer evento de conversÃ£o (ex: clique no WhatsApp) deve disparar um evento interno.
Webhook de SaÃ­da: O sistema enviarÃ¡ um POST em formato JSON para uma URL externa (seu CRM de vendas em outro subdomÃ­nio).
Payload do Webhook: O pacote de dados enviado conterÃ¡ as UTMs rastreadas, os dados do imÃ³vel acessado e as informaÃ§Ãµes do clique/conversÃ£o, deixando o tratamento do lead exclusivamente para o CRM.
11. SeguranÃ§a, Credenciais e Logs
VariÃ¡veis de Ambiente (.env): Nenhuma senha, token ou chave de API deve constar no cÃ³digo-fonte. Tudo serÃ¡ gerenciado pelo arquivo de ambiente.
TolerÃ¢ncia a Falhas (Try/Catch): Processos como importaÃ§Ã£o de CSV e disparos de Webhooks devem possuir tratamento de erros rigoroso.
Logs: Qualquer falha em rotinas de background nÃ£o deve "quebrar" o sistema silenciosamente, mas sim gerar um registro claro em um arquivo error.log com data, linha e motivo da falha.
12. Conformidade com a LGPD (Privacidade)
GestÃ£o de Consentimento: O sistema deve implementar um banner de cookies para que o usuÃ¡rio aceite o rastreamento antes da ativaÃ§Ã£o dos scripts de analytics.
TransparÃªncia na Captura: Todo formulÃ¡rio ou botÃ£o que capture intenÃ§Ã£o de compra (Lead) deve conter uma breve nota informando que os dados serÃ£o processados conforme a LGPD.
Finalidade EspecÃ­fica: Os dados capturados (UTMs + Dados do ImÃ³vel) devem ser utilizados exclusivamente para o encaminhamento ao CRM externo.

13. SeguranÃ§a de IntegraÃ§Ã£o (Webhooks)
AutenticaÃ§Ã£o por Token: Todas as requisiÃ§Ãµes de Webhook enviadas para o CRM devem incluir um token Ãºnico (X-Webhook-Token) no cabeÃ§alho, configurado via .env.
Retry Policy: O sistema deve registrar o status HTTP de retorno do CRM. Caso o CRM esteja offline (5xx) ou ocupado (429), o evento deve ser enfileirado para nova tentativa.

14. EstratÃ©gia de Cache e Performance
Cache de Bairros: Os dados do "DossiÃª de Bairros" (JSON) devem ser cacheados apÃ³s a primeira leitura para evitar processamento repetitivo do banco de dados.
Resultados de Busca: Implementar cache para as queries de busca mais comuns (ex: "Casa em Copacabana"), com tempo de expiraÃ§Ã£o (TTL) de 1 hora ou limpeza automÃ¡tica apÃ³s nova importaÃ§Ã£o de CSV.
Carregamento Imediato: As fotos dos imÃ³veis (URLs externas da Caixa) NÃƒO devem utilizar lazy loading, garantindo que estejam visÃ­veis assim que a pÃ¡gina for aberta. O carregamento tardio fica restrito a componentes de rodapÃ© ou imagens secundÃ¡rias de baixa prioridade.

15. SEO TÃ©cnico e IndexaÃ§Ã£o
Sitemap DinÃ¢mico: O sistema deve gerar e atualizar automaticamente um arquivo sitemap.xml que liste todas as URLs ativas de imÃ³veis e pÃ¡ginas de bairros.
Robots.txt: ConfiguraÃ§Ã£o de um arquivo robots.txt otimizado para permitir a varredura completa dos imÃ³veis, mas bloquear Ã¡reas administrativas ou URLs de filtros que geram conteÃºdo duplicado.
Canonical Tags: ImplementaÃ§Ã£o automÃ¡tica de tags canÃ´nicas para evitar que pÃ¡ginas com filtros aplicados sejam interpretadas como conteÃºdo duplicado pelos buscadores.

16. Integridade e ValidaÃ§Ã£o de ImportaÃ§Ã£o
SanitizaÃ§Ã£o de CabeÃ§alhos: O script deve ignorar diferenÃ§as de maiÃºsculas/minÃºsculas ou espaÃ§os extras nos nomes das colunas do CSV.
Tratamento de Nulos: Caso campos essenciais (ex: PreÃ§o ou ID) venham vazios no CSV, o sistema deve ignorar o registro e logar o erro, em vez de interromper toda a importaÃ§Ã£o.
DeduplicaÃ§Ã£o: Implementar uma trava lÃ³gica para evitar que o mesmo imÃ³vel seja duplicado no banco caso o CSV seja reimportado acidentalmente (usar o ID Ãºnico do imÃ³vel da Caixa como chave).

### ðŸ—ï¸ REGRAS DE NEGÃ“CIO E LIMITAÃ‡Ã•ES
17. **Uma ImobiliÃ¡ria por Estado**: O sistema Ã© configurado estritamente para ter apenas uma imobiliÃ¡ria parceira responsÃ¡vel por cada estado (UF). 
18. **VÃ­nculo AutomÃ¡tico**: Leads gerados para um imÃ³vel devem ser encaminhados exclusivamente para a imobiliÃ¡ria vinculada ao estado daquele imÃ³vel.

ðŸ¤– DIRETRIZES DE COMPORTAMENTO PARA OS AGENTES (ANTIGRAVITY)
(Entregar esta lista como instruÃ§Ã£o base de sistema para a IA que irÃ¡ programar)

CÃ³digo Comentado: Documentem e comentem exaustivamente a criaÃ§Ã£o de variÃ¡veis, tabelas, consultas e lÃ³gicas de negÃ³cios.
Arquitetura Modular: Usem estruturaÃ§Ã£o por mÃ³dulos/domÃ­nios. Cada mÃ³dulo deve conter sua prÃ³pria lÃ³gica, rotas e autodocumentaÃ§Ã£o em um arquivo (ex: docs.md).
Engenharia de Banco de Dados: Atributos de pesquisa (Tipo de imÃ³vel, Vagas, Quartos) extraÃ­dos do CSV DEVEM ficar em colunas relacionais e indexadas.
Casting JSON: Usem colunas tipo JSON (ex: dados_bairro) e casting automÃ¡tico nas Entidades do backend estritamente para dados de leitura e enriquecimento que nÃ£o necessitam de filtros na query (WHERE).
Parse de CSV e Regex (Protocolo Caixa):
1. Ler Linha 1: Extrair "Data de GeraÃ§Ã£o".
2. Definir Header: Linha 2.
3. Skip: Linha 3.
4. Encoding: `ISO-8859-1`.
5. Sanitizar Headers: Lowercase, remover acentos, trocar espaÃ§os por `_` e converter `NÂº` para `numero`.
6. Descartar: Coluna "Link de acesso".
7. Split DescriÃ§Ã£o: Extrair comodidades do campo "DescriÃ§Ã£o" para colunas indexadas.
ValidaÃ§Ã£o de Dados: O parser de CSV deve ser resiliente a mudanÃ§as sutis de formato e garantir a deduplicaÃ§Ã£o de registros baseada no ID Ãºnico da Caixa.






---
# FILE: arquitetura.md
---

# Arquitetura de Hospedagem e ImplantaÃ§Ã£o

Este documento detalha a arquitetura de hospedagem, a stack tecnolÃ³gica e o fluxo de trabalho de implantaÃ§Ã£o (CI/CD) definidos para este projeto.

## 1. VisÃ£o Geral da Arquitetura

O projeto foi desenhado com foco em simplicidade, baixo custo operacional e facilidade de manutenÃ§Ã£o, aproveitando uma stack de tecnologia robusta e um fluxo de trabalho de implantaÃ§Ã£o automatizado.

- **Estrutura:** Monorepo
- **Hospedagem:** Hospedagem padrÃ£o na Hostinger (nÃ£o-VPS)
- **Tecnologia:** PHP
- **ImplantaÃ§Ã£o:** Automatizada com GitHub Actions

## 2. Ambiente de Hospedagem

A decisÃ£o foi por **nÃ£o utilizar um servidor VPS**. Em vez disso, a aplicaÃ§Ã£o serÃ¡ hospedada em um plano de hospedagem padrÃ£o (Cloud/Shared) da Hostinger.

- **Provedor:** Hostinger
- **Plano:** Hospedagem PadrÃ£o (nÃ£o-VPS)
- **Justificativa:**
  - **ReduÃ§Ã£o de Custo:** Aproveita um plano jÃ¡ existente, eliminando a necessidade de contratar e manter um novo servidor.
  - **Simplicidade de GestÃ£o:** A manutenÃ§Ã£o da infraestrutura do servidor (seguranÃ§a, atualizaÃ§Ãµes de sistema operacional) Ã© gerenciada pela prÃ³pria Hostinger.
  - **Alinhamento com a Stack:** Os planos de hospedagem padrÃ£o sÃ£o altamente otimizados para aplicaÃ§Ãµes PHP.

## 3. Stack TecnolÃ³gica

- **Backend:** **PHP**. A aplicaÃ§Ã£o serÃ¡ construÃ­da como um monolito PHP padrÃ£o.
- **Banco de Dados:** **MySQL / MariaDB**. O banco de dados serÃ¡ o serviÃ§o jÃ¡ incluÃ­do no plano da Hostinger, gerenciado atravÃ©s do painel hPanel.
- **Estrutura do Projeto:** **Monorepo**. O cÃ³digo-fonte do backend e do frontend residirÃ¡ no mesmo repositÃ³rio para simplificar o versionamento e a gestÃ£o.

## 4. Fluxo de ImplantaÃ§Ã£o (Deployment Workflow)

O processo de publicaÃ§Ã£o de novas versÃµes do cÃ³digo no servidor de produÃ§Ã£o Ã© **totalmente automatizado** utilizando **GitHub Actions**.

- **Gatilho (Trigger):** O workflow de implantaÃ§Ã£o Ã© acionado automaticamente a cada `git push` na branch `main` do repositÃ³rio.
- **Ferramenta:** GitHub Actions, o sistema de automaÃ§Ã£o nativo do GitHub.
- **Mecanismo:** TransferÃªncia de arquivos via **SFTP (Secure File Transfer Protocol)**.

### Processo Detalhado:

1.  O desenvolvedor finaliza uma alteraÃ§Ã£o em sua mÃ¡quina local.
2.  O cÃ³digo Ã© enviado para o repositÃ³rio no GitHub com o comando `git push origin main`.
3.  O push aciona o workflow definido no arquivo `.github/workflows/deploy.yml` dentro do repositÃ³rio.
4.  O GitHub Action inicia um processo que se conecta de forma segura ao servidor da Hostinger usando credenciais de SFTP.
    -   *Nota: As credenciais (host, usuÃ¡rio, senha) devem ser armazenadas como **Secrets** nas configuraÃ§Ãµes do repositÃ³rio no GitHub para mÃ¡xima seguranÃ§a.*
5.  O Action sincroniza os arquivos do repositÃ³rio com o diretÃ³rio de produÃ§Ã£o no servidor (geralmente `public_html`).

### Diagrama do Fluxo:



---
# FILE: TECH_STACK.md
---

# TECH_STACK.md

## VisÃ£o Geral TÃ©cnica

Este documento descreve a estrutura tÃ©cnica, as tecnologias, os padrÃµes arquiteturais e as estratÃ©gias de desenvolvimento adotadas neste projeto.

O objetivo deste arquivo Ã© servir como referÃªncia para desenvolvedores humanos e agentes de IA que venham a dar continuidade ao projeto, garantindo consistÃªncia tÃ©cnica, organizaÃ§Ã£o estrutural e clareza sobre as decisÃµes arquiteturais jÃ¡ planejadas.

---

## Objetivo da Arquitetura

A arquitetura deste projeto foi pensada para ser:

- **Modular**
- **EscalÃ¡vel**
- **Organizada por responsabilidade**
- **FÃ¡cil de manter**
- **Preparada para evoluÃ§Ã£o futura**
- **CompatÃ­vel com desenvolvimento incremental**
- **AmigÃ¡vel para colaboraÃ§Ã£o entre humanos e IA**

Mesmo que algumas partes do projeto ainda estejam vazias, incompletas ou funcionando como placeholders, a estrutura atual representa uma base planejada para futuras implementaÃ§Ãµes.

---

## Estado Atual do Projeto

Atualmente, o projeto funciona como um **esqueleto arquitetural planejado**.

Isso significa que a estrutura de pastas, arquivos e separaÃ§Ã£o de responsabilidades foi criada previamente para orientar o desenvolvimento futuro.

Nem todos os arquivos precisam conter implementaÃ§Ã£o neste momento. Alguns arquivos podem existir apenas para indicar:

- Onde determinada responsabilidade deve ser implementada
- Como o projeto deve crescer futuramente
- Qual padrÃ£o arquitetural deve ser respeitado
- Como separar corretamente cÃ³digo, configuraÃ§Ã£o, documentaÃ§Ã£o e regras de negÃ³cio

Essa abordagem Ã© intencional.

A presenÃ§a de arquivos vazios, placeholders ou comentÃ¡rios explicativos nÃ£o deve ser interpretada como erro, mas sim como parte da estratÃ©gia de organizaÃ§Ã£o inicial do projeto.

---

## EstratÃ©gia de Esqueleto Arquitetural

O projeto foi estruturado para permitir que futuras funcionalidades sejam adicionadas sem necessidade de reorganizar a base principal.

Essa estratÃ©gia ajuda a evitar crescimento desordenado do cÃ³digo e facilita a manutenÃ§Ã£o por diferentes colaboradores.

### Diretrizes dessa estratÃ©gia

- Manter a estrutura de pastas mesmo antes da implementaÃ§Ã£o completa
- Usar arquivos placeholder quando necessÃ¡rio para preservar a organizaÃ§Ã£o
- Documentar a intenÃ§Ã£o de diretÃ³rios e mÃ³dulos importantes
- Evitar misturar responsabilidades diferentes em um mesmo arquivo
- Priorizar clareza e previsibilidade na organizaÃ§Ã£o do projeto
- Permitir que humanos e agentes de IA entendam rapidamente onde cada coisa deve ser implementada

---

## Stack TÃ©cnica

> Esta seÃ§Ã£o deve refletir as tecnologias adotadas no projeto.  
> Caso alguma tecnologia ainda esteja em definiÃ§Ã£o, ela deve ser marcada claramente como pendente ou planejada.

### Linguagem Principal

- **PHP 8.2+**
  - Utilizado como linguagem base para todo o desenvolvimento do sistema, aproveitando a maturidade e performance das versÃµes recentes.
  - Framework: **Laravel 11**.

### Frontend

- **TALL Stack (Tailwind, Alpine.js, Laravel, Livewire)**
  - A abordagem escolhida Ã© a de um "MonÃ³lito Moderno", mantendo a lÃ³gica de programaÃ§Ã£o dentro do ecossistema PHP.
  - **Blade:** Motor de templates nativo do Laravel.
  - **Tailwind CSS:** Framework utility-first para design responsivo e moderno.
  - **Livewire:** Reatividade e dinamismo com lÃ³gica em PHP.
  - **Alpine.js:** Micro-interatividades no lado do cliente.

### Backend

- Estrutura preparada para regras de negÃ³cio, APIs, integraÃ§Ãµes e processamento de dados.
- A camada de backend deve manter regras de negÃ³cio separadas da interface e da infraestrutura.

PossÃ­veis responsabilidades dessa camada:

- ServiÃ§os de aplicaÃ§Ã£o
- Rotas de API
- ValidaÃ§Ãµes
- IntegraÃ§Ãµes externas
- Regras de negÃ³cio
- PersistÃªncia de dados

### Banco de Dados

- **MySQL / MariaDB**
  - **Hospedagem:** Hostinger (Ambiente nÃ£o-VPS / hPanel).
  - **Justificativa:** Aproveitamento do ambiente de hospedagem Hostinger, reduÃ§Ã£o de custos, simplicidade de gestÃ£o e alinhamento total com a infraestrutura do Laravel.
  - O projeto utiliza Migrations e Eloquent ORM para garantir a portabilidade e consistÃªncia dos dados dentro do ecossistema Laravel.

### EstilizaÃ§Ã£o

A estilizaÃ§Ã£o deve seguir um padrÃ£o consistente e reutilizÃ¡vel.

PossÃ­veis abordagens:

- CSS Modules
- Tailwind CSS
- Styled Components
- Sass
- CSS global organizado
- Design system prÃ³prio

A tecnologia final adotada deve ser documentada conforme o projeto evoluir.

### Testes

O projeto deve ser preparado para receber testes automatizados.

Tipos de testes recomendados:

- Testes unitÃ¡rios
- Testes de integraÃ§Ã£o
- Testes de componentes
- Testes end-to-end, quando aplicÃ¡vel

PossÃ­veis ferramentas:

- Jest
- Vitest
- Testing Library
- Playwright
- Cypress

### Qualidade de CÃ³digo

Recomenda-se o uso de ferramentas de padronizaÃ§Ã£o e anÃ¡lise estÃ¡tica para manter consistÃªncia no projeto.

Ferramentas recomendadas:

- ESLint
- Prettier
- EditorConfig
- TypeScript
- Husky
- lint-staged

Essas ferramentas ajudam a manter o cÃ³digo limpo, previsÃ­vel e padronizado.

---

## OrganizaÃ§Ã£o Geral do Projeto

A estrutura do projeto deve seguir separaÃ§Ã£o clara de responsabilidades.

Exemplo conceitual de organizaÃ§Ã£o:

    project-root/
    â”œâ”€â”€ docs/
    â”‚   â””â”€â”€ TECH_STACK.md
    â”œâ”€â”€ src/
    â”‚   â”œâ”€â”€ components/
    â”‚   â”œâ”€â”€ pages/
    â”‚   â”œâ”€â”€ routes/
    â”‚   â”œâ”€â”€ services/
    â”‚   â”œâ”€â”€ utils/
    â”‚   â”œâ”€â”€ hooks/
    â”‚   â”œâ”€â”€ styles/
    â”‚   â””â”€â”€ config/
    â”œâ”€â”€ public/
    â”œâ”€â”€ tests/
    â”œâ”€â”€ README.md
    â”œâ”€â”€ CONTRIBUTING.md
    â””â”€â”€ package.json

> A estrutura real pode variar de acordo com o framework, biblioteca ou arquitetura adotada.  
> O importante Ã© manter a separaÃ§Ã£o de responsabilidades e a coerÃªncia entre os diretÃ³rios.

---

## PadrÃµes Arquiteturais

O projeto deve seguir padrÃµes que favoreÃ§am clareza, manutenÃ§Ã£o e evoluÃ§Ã£o.

### SeparaÃ§Ã£o de Responsabilidades

Cada arquivo ou mÃ³dulo deve ter uma responsabilidade clara.

Evitar arquivos que misturam:

- Interface
- Regra de negÃ³cio
- ComunicaÃ§Ã£o com API
- ValidaÃ§Ã£o
- ConfiguraÃ§Ã£o
- ManipulaÃ§Ã£o direta de dados

Sempre que possÃ­vel, separar essas responsabilidades em mÃ³dulos especÃ­ficos.

---

### Modularidade

Funcionalidades devem ser organizadas de forma modular.

Isso facilita:

- ReutilizaÃ§Ã£o
- Testes
- RefatoraÃ§Ã£o
- Escalabilidade
- Trabalho em equipe
- ImplementaÃ§Ãµes futuras por IA

---

### Baixo Acoplamento

Os mÃ³dulos devem depender o mÃ­nimo possÃ­vel uns dos outros.

Quando houver comunicaÃ§Ã£o entre partes do sistema, essa comunicaÃ§Ã£o deve ser feita por interfaces claras, serviÃ§os ou camadas intermediÃ¡rias.

---

### Alta CoesÃ£o

Arquivos e mÃ³dulos devem agrupar apenas responsabilidades relacionadas.

Um mÃ³dulo deve conter elementos que fazem sentido juntos e que pertencem ao mesmo contexto funcional.

---

## ConvenÃ§Ãµes de CÃ³digo

### NomeaÃ§Ã£o

Usar nomes claros, descritivos e consistentes.

Exemplos de boas prÃ¡ticas:

- Componentes com nomes em PascalCase
- FunÃ§Ãµes e variÃ¡veis com nomes em camelCase
- Arquivos utilitÃ¡rios com nomes descritivos
- DiretÃ³rios nomeados de acordo com sua responsabilidade

Exemplos conceituais:

- `UserCard`
- `useAuth`
- `formatCurrency`
- `apiClient`
- `userService`

---

### ComentÃ¡rios

ComentÃ¡rios devem ser usados para explicar intenÃ§Ã£o, contexto ou decisÃµes importantes.

Evitar comentÃ¡rios Ã³bvios que apenas repetem o que o cÃ³digo jÃ¡ mostra.

ComentÃ¡rios sÃ£o especialmente Ãºteis em:

- Arquivos placeholder
- Pontos de integraÃ§Ã£o futura
- DecisÃµes arquiteturais
- Regras de negÃ³cio complexas
- Trechos temporÃ¡rios que serÃ£o implementados depois

---

### Arquivos Placeholder

Arquivos placeholder sÃ£o permitidos quando fazem parte da estrutura planejada.

Eles podem conter comentÃ¡rios explicativos indicando a intenÃ§Ã£o futura do arquivo.

Exemplo conceitual:

    // Este arquivo estÃ¡ reservado para futuras funÃ§Ãµes utilitÃ¡rias relacionadas Ã  formataÃ§Ã£o de dados.
    // ImplementaÃ§Ãµes devem ser adicionadas conforme necessidade do projeto.

Arquivos placeholder devem ser mantidos apenas quando ajudarem a preservar ou explicar a arquitetura.

---

## EstratÃ©gia para Desenvolvimento Futuro

Ao continuar o desenvolvimento do projeto, deve-se priorizar:

1. Preservar a estrutura arquitetural existente
2. Implementar funcionalidades no local correto
3. Evitar duplicaÃ§Ã£o de responsabilidades
4. Documentar decisÃµes tÃ©cnicas relevantes
5. Manter consistÃªncia entre arquivos semelhantes
6. Atualizar este documento quando novas tecnologias forem adicionadas
7. Evitar remover arquivos estruturais sem entender sua finalidade

---

## OrientaÃ§Ãµes para Agentes de IA

Este projeto pode ser desenvolvido ou continuado com auxÃ­lio de agentes de IA.

Ao trabalhar neste repositÃ³rio, agentes de IA devem:

- Respeitar a estrutura existente
- NÃ£o remover arquivos vazios sem justificativa
- NÃ£o reorganizar diretÃ³rios sem necessidade clara
- NÃ£o misturar responsabilidades em um Ãºnico arquivo
- Adicionar comentÃ¡rios quando criar placeholders
- Seguir os padrÃµes descritos neste documento
- Atualizar documentaÃ§Ã£o quando alterar decisÃµes tÃ©cnicas
- Preferir mudanÃ§as incrementais e bem localizadas

Caso uma IA encontre arquivos vazios, ela deve verificar se eles fazem parte do esqueleto arquitetural antes de sugerir remoÃ§Ã£o.

---

## OrientaÃ§Ãµes para Desenvolvedores Humanos

Desenvolvedores humanos devem tratar este projeto como uma base planejada para evoluÃ§Ã£o contÃ­nua.

Antes de alterar a estrutura principal, recomenda-se:

- Entender a funÃ§Ã£o de cada diretÃ³rio
- Verificar se hÃ¡ documentaÃ§Ã£o relacionada
- Avaliar se a mudanÃ§a preserva a organizaÃ§Ã£o do projeto
- Evitar apagar arquivos estruturais sem motivo tÃ©cnico claro
- Atualizar a documentaÃ§Ã£o quando necessÃ¡rio

---

## AtualizaÃ§Ã£o deste Documento

Este documento deve ser atualizado sempre que houver mudanÃ§as relevantes na stack ou arquitetura.

Exemplos de mudanÃ§as que exigem atualizaÃ§Ã£o:

- InclusÃ£o de um novo framework
- Troca de biblioteca principal
- DefiniÃ§Ã£o de banco de dados
- AlteraÃ§Ã£o na estratÃ©gia de testes
- MudanÃ§a na estrutura de pastas
- InclusÃ£o de ferramentas de build, deploy ou CI/CD
- DefiniÃ§Ã£o de padrÃµes de autenticaÃ§Ã£o, autorizaÃ§Ã£o ou seguranÃ§a

---

## Documentos Relacionados

Este documento faz parte da documentaÃ§Ã£o principal do projeto.

Documentos complementares previstos:

- `README.md` â€” visÃ£o geral do projeto, instalaÃ§Ã£o, uso e estrutura principal
- `CONTRIBUTING.md` â€” regras de contribuiÃ§Ã£o, padrÃµes e orientaÃ§Ãµes para evoluÃ§Ã£o do projeto

---

## Resumo

Este projeto foi preparado com uma estrutura tÃ©cnica inicial para permitir crescimento organizado.

A existÃªncia de arquivos vazios, comentÃ¡rios explicativos e placeholders faz parte de uma estratÃ©gia consciente de planejamento arquitetural.

O desenvolvimento futuro deve respeitar essa base, preenchendo os mÃ³dulos conforme a necessidade, sem comprometer a organizaÃ§Ã£o geral do projeto.


---
# FILE: visao-geral.md
---

# ðŸ“‹ VISÃƒO GERAL DO SISTEMA

## ðŸŽ¯ Objetivo do Sistema

Plataforma web para **captaÃ§Ã£o, organizaÃ§Ã£o e distribuiÃ§Ã£o de leads** interessados em
imÃ³veis com desconto acima de 30%, disponÃ­veis para venda direta.

O sistema conecta **compradores em potencial** (leads) Ã s **imobiliÃ¡rias parceiras**
responsÃ¡veis pelo atendimento, sem que as imobiliÃ¡rias precisem operar ativamente
dentro da plataforma.

---

## ðŸ‘¥ PERFIS DE USUÃRIO

### ðŸ”§ Administrador
- UsuÃ¡rios internos (2 a 3 pessoas)
- Acesso total ao sistema
- Gerencia imÃ³veis, leads, imobiliÃ¡rias e configuraÃ§Ãµes gerais

### ðŸ¢ ImobiliÃ¡ria
- Uma imobiliÃ¡ria parceira por estado (atÃ© 27 no total)
- Se cadastra na plataforma com login e senha
- Acesso a um **painel simples de visualizaÃ§Ã£o** (somente leitura)
- Visualiza os atendimentos recebidos: data, nome, e-mail, imÃ³vel de interesse
- Pode copiar as informaÃ§Ãµes para dar continuidade no atendimento
- **Recebe automaticamente** cada lead por **e-mail e WhatsApp**
- NÃ£o insere, nÃ£o edita, nÃ£o realiza nenhuma aÃ§Ã£o no sistema

### ðŸ‘¤ Lead / Visitante (Comprador)
- Se cadastra ao preencher o formulÃ¡rio de interesse
- Recebe **e-mail de confirmaÃ§Ã£o** para validar o cadastro
- ApÃ³s confirmado, tem acesso a **Ã¡reas restritas do site**
- NÃ£o tem painel, nÃ£o edita nada, acesso somente leitura ao conteÃºdo

---

## ðŸ  IMÃ“VEIS

- Os imÃ³veis sÃ£o importados via **arquivo CSV** inserido manualmente pelo administrador
- A frequÃªncia de importaÃ§Ã£o nÃ£o Ã© fixa â€” pode ocorrer mais de uma vez por semana
  ou nenhuma vez, conforme a disponibilidade do arquivo
- O sistema processa o arquivo e atualiza a base de dados com os imÃ³veis disponÃ­veis
- Cada imÃ³vel contÃ©m informaÃ§Ãµes como: endereÃ§o, cidade, estado, valor, modalidade
  de venda, tipo de imÃ³vel, link do edital, entre outros
- Os imÃ³veis sÃ£o **vinculados a uma imobiliÃ¡ria parceira** responsÃ¡vel pelo atendimento
  do estado correspondente

### ðŸ”’ Regras de ImportaÃ§Ã£o
- SÃ³ sÃ£o importados imÃ³veis com **desconto acima de 30%**
- SÃ³ sÃ£o importados imÃ³veis com as seguintes modalidades de venda:
  - **Venda Direta Online**
  - **Venda Direta**
- ImÃ³veis com qualquer outra modalidade sÃ£o descartados na importaÃ§Ã£o

### ðŸ”„ Status do ImÃ³vel
- Um imÃ³vel pode sair da lista sem ter sido vendido e retornar futuramente
- Os status possÃ­veis sÃ£o:
  - **Ativo** â€” consta na lista atual
  - **Fora de venda** â€” saiu da lista, situaÃ§Ã£o nÃ£o confirmada
  - **Vendido** â€” confirmado como vendido
  - **Suspenso** â€” venda suspensa ou cancelada

### ðŸ”¢ Dados Fixos vs. Dados VariÃ¡veis

**Dados fixos** (nÃ£o mudam apÃ³s a importaÃ§Ã£o inicial):
- NÃºmero original do imÃ³vel no CSV (chave principal â€” Ãºnico)
- EndereÃ§o completo (logradouro, bairro, cidade, estado)
- DescriÃ§Ã£o original do arquivo
- Tipo do imÃ³vel

**Dados variÃ¡veis** (podem mudar a cada nova importaÃ§Ã£o â€” geram histÃ³rico):
- PreÃ§o de venda
- Valor de avaliaÃ§Ã£o
- Desconto percentual
- Desconto em reais (calculado pelo sistema)
- Modalidade de venda
- Aceite de financiamento SBPE
- Aceite de financiamento MCMV (campo reservado para uso futuro)
- Aceite de FGTS (obtido via scraping â€” valor inicial: "nÃ£o informado")
- Data de referÃªncia (data de geraÃ§Ã£o do CSV)

---

## ðŸ” ETAPAS DE PROCESSAMENTO DO IMÃ“VEL

Cada imÃ³vel passa por etapas sequenciais de processamento apÃ³s a importaÃ§Ã£o:

1. **ImportaÃ§Ã£o** â€” dados brutos inseridos no banco a partir do CSV
2. **Processamento** â€” sistema organiza e interpreta os dados
3. **GeraÃ§Ã£o de links** â€” criaÃ§Ã£o do link da imagem e do link do edital
4. **Desmembramento da descriÃ§Ã£o** â€” extraÃ§Ã£o via PHP dos campos individuais
   contidos no texto original (tipo, Ã¡rea, quartos, caracterÃ­sticas)
5. **Scraping** â€” coleta de informaÃ§Ãµes complementares no site de origem
   (FGTS, dados adicionais)
6. **GeraÃ§Ã£o de SEO** â€” criaÃ§Ã£o de tÃ­tulos, slugs e meta descriptions
7. **CÃ¡lculos financeiros** â€” desconto em reais, enquadramento em grupo, simulaÃ§Ãµes

---

## ðŸ“ DESMEMBRAMENTO DA DESCRIÃ‡ÃƒO

O campo de descriÃ§Ã£o original contÃ©m mÃºltiplas informaÃ§Ãµes em texto livre. O sistema
extrai automaticamente via PHP os seguintes campos:

| Campo extraÃ­do      | Exemplo                                   |
|---------------------|-------------------------------------------|
| Tipo do imÃ³vel      | Casa, Apartamento, Terreno, Sobrado, PrÃ©dio |
| Ãrea total          | 69,03 mÂ²                                  |
| Ãrea privativa      | 69,03 mÂ²                                  |
| Ãrea do terreno     | 99,79 mÂ²                                  |
| Quartos             | 3                                         |
| Banheiros / WC      | 1                                         |
| Salas               | 1                                         |
| Vagas de garagem    | 0                                         |
| Varanda             | Sim / NÃ£o                                 |
| Ãrea de serviÃ§o     | Sim / NÃ£o                                 |
| Cozinha             | Sim / NÃ£o                                 |
| Piscina             | Sim / NÃ£o                                 |
| Churrasqueira       | Sim / NÃ£o                                 |
| TerraÃ§o             | Sim / NÃ£o                                 |

---

## ðŸ“Š GRUPOS DE IMÃ“VEIS

- Os imÃ³veis sÃ£o classificados em grupos com base no **valor de avaliaÃ§Ã£o**
- Cada grupo possui percentuais e valores fixos utilizados em cÃ¡lculos financeiros
- Os grupos sÃ£o preenchidos manualmente pelo administrador
- As alteraÃ§Ãµes sÃ£o raras â€” ocorrem principalmente na fase inicial do sistema

---

## ðŸ“ HIERARQUIA DE LOCALIZAÃ‡ÃƒO

A localizaÃ§Ã£o Ã© estruturada em nÃ­veis relacionais para evitar duplicaÃ§Ã£o e permitir
enriquecimento de conteÃºdo:

- **Estado (UF)**
- **MunicÃ­pio**
- **Bairro**
- **CEP**

Ao importar um imÃ³vel, o sistema verifica se o estado, municÃ­pio e bairro jÃ¡ existem
na base antes de criar um novo registro.

### ðŸŒ Enriquecimento por MunicÃ­pio e Bairro

Cada municÃ­pio e bairro pode ter seu conteÃºdo enriquecido por InteligÃªncia Artificial,
armazenado em um campo JSON. O objetivo Ã© fornecer informaÃ§Ãµes relevantes para
compradores que nÃ£o conhecem a regiÃ£o.

**ConteÃºdo gerado para municÃ­pios:**
- ApresentaÃ§Ã£o geral
- Data de fundaÃ§Ã£o, populaÃ§Ã£o, Ã¡rea
- Principais atividades econÃ´micas
- Turismo e pontos turÃ­sticos
- Infraestrutura (aeroportos, hospitais de referÃªncia)

**ConteÃºdo gerado para bairros:**
- EducaÃ§Ã£o (escolas, universidades prÃ³ximas)
- Transporte (linhas de Ã´nibus, metrÃ´, trem, estaÃ§Ãµes prÃ³ximas)
- SaÃºde (hospitais, UBS, clÃ­nicas)
- ComÃ©rcio (shoppings, supermercados, bancos)
- Pontos de referÃªncia (distÃ¢ncia a aeroportos, rodoviÃ¡rias, pontos turÃ­sticos)
- Principais avenidas e vias de acesso

---

## ðŸ” BUSCA DE IMÃ“VEIS

- O site Ã© aberto â€” qualquer visitante pode realizar buscas sem login
- Filtros disponÃ­veis:
  - Estado (UF)
  - MunicÃ­pio
  - Bairro
  - Tipo de imÃ³vel
  - Faixa de valor
  - Modalidade de venda
- Os resultados podem ser **compartilhados via link**
- O link compartilhado gera uma **prÃ©-visualizaÃ§Ã£o** no WhatsApp com a imagem
  de destaque configurada

---

## ðŸ“¬ GERAÃ‡ÃƒO DE LEAD

- Ao demonstrar interesse em um imÃ³vel, o visitante preenche um formulÃ¡rio
- Esse formulÃ¡rio gera um **atendimento vinculado ao imÃ³vel e Ã  imobiliÃ¡ria
  responsÃ¡vel pelo estado**
- A imobiliÃ¡ria Ã© **notificada automaticamente** por e-mail e WhatsApp
- Se o mesmo lead se interessar por mÃºltiplos imÃ³veis, a imobiliÃ¡ria recebe uma
  notificaÃ§Ã£o separada para cada imÃ³vel
- O histÃ³rico de imÃ³veis de interesse do lead fica **gravado em JSON** dentro
  do registro do lead:

```json
{
  "imoveis_interesse": [
    { "numero": "1042", "data": "2026-04-10", "modalidade": "Venda Direta Online" },
    { "numero": "3891", "data": "2026-04-15", "modalidade": "Venda Direta" }
  ]
}
```

---

## ðŸ–¼ï¸ IMAGENS DO SISTEMA

O sistema trabalha com um volume reduzido de imagens:

- **Foto da fachada** â€” imagem real do imÃ³vel, tirada da rua. Hospedada em servidores
  externos. O sistema armazena apenas a URL que aponta para ela.
- **Imagem de destaque (Open Graph)** â€” imagem institucional genÃ©rica, hospedada no
  servidor prÃ³prio da plataforma. Exibida automaticamente quando qualquer link do site
  Ã© compartilhado no WhatsApp ou redes sociais.

---

*Documento gerado em: maio de 2026 â€” VersÃ£o: 2.0*


---
# FILE: analise de contexto.md
---

# ðŸ“‹ VISÃƒO GERAL DO SISTEMA â€” Gerenciador de Estoque de ImÃ³veis

## ðŸŽ¯ Objetivo do Sistema

Plataforma web para **captaÃ§Ã£o, organizaÃ§Ã£o e distribuiÃ§Ã£o de leads** interessados em imÃ³veis Ã  venda, acessÃ­vel pelo endereÃ§o **venda.imoveisdacaixa.com.br**.

O sistema conecta **compradores em potencial** (leads) Ã s **imobiliÃ¡rias parceiras** responsÃ¡veis pelo atendimento, sem que as imobiliÃ¡rias precisem operar ativamente dentro da plataforma.

---

## ðŸ‘¥ PERFIS DE USUÃRIO

### ðŸ”§ Administrador
- UsuÃ¡rios internos (2 a 3 pessoas)
- Acesso total ao sistema
- Gerencia imÃ³veis, leads, imobiliÃ¡rias e configuraÃ§Ãµes gerais

### ðŸ¢ ImobiliÃ¡ria
- Uma imobiliÃ¡ria parceira por estado (atÃ© 27 no total)
- Se cadastra na plataforma com login e senha
- Acesso a um **painel simples de visualizaÃ§Ã£o** (somente leitura)
- Visualiza os leads recebidos: data, nome, e-mail, imÃ³vel de interesse
- Pode copiar as informaÃ§Ãµes para dar continuidade no atendimento
- **Recebe automaticamente** cada lead por **e-mail e WhatsApp**
- NÃ£o insere, nÃ£o edita, nÃ£o realiza nenhuma aÃ§Ã£o no sistema

### ðŸ‘¤ Lead / Visitante (Comprador)
- Se cadastra ao preencher o formulÃ¡rio de interesse
- Recebe **e-mail de confirmaÃ§Ã£o** para validar o cadastro
- ApÃ³s confirmado, tem acesso a **Ã¡reas restritas do site**
- NÃ£o tem painel, nÃ£o edita nada, acesso somente leitura ao conteÃºdo

---

## ðŸ  IMÃ“VEIS

- Os imÃ³veis sÃ£o importados via **arquivo CSV**, inserido manualmente pelo administrador
- A frequÃªncia de importaÃ§Ã£o nÃ£o Ã© fixa â€” pode ocorrer mais de uma vez por semana ou nenhuma, conforme disponibilidade do arquivo
- O sistema processa o arquivo e atualiza a base de dados com os imÃ³veis disponÃ­veis
- Cada imÃ³vel contÃ©m informaÃ§Ãµes como: endereÃ§o, cidade, estado, valor, modalidade de venda, tipo de imÃ³vel, link do edital, entre outros
- Os imÃ³veis sÃ£o **vinculados a uma imobiliÃ¡ria parceira** responsÃ¡vel pelo atendimento do estado correspondente

### ðŸ”’ Regras de ImportaÃ§Ã£o
- SÃ³ sÃ£o importados imÃ³veis com **desconto acima de 30%**
- SÃ³ sÃ£o importados imÃ³veis com as seguintes modalidades de venda:
  - **Venda Direta**
  - **Venda Direta Online**
- ImÃ³veis com qualquer outra modalidade sÃ£o descartados na importaÃ§Ã£o

### ðŸ”„ Status do ImÃ³vel
- Um imÃ³vel pode sair da lista sem ter sido vendido e retornar futuramente
- Os status possÃ­veis sÃ£o:
  - **Ativo** â€” consta na lista atual
  - **Fora de venda** â€” saiu da lista, situaÃ§Ã£o nÃ£o confirmada
  - **Vendido** â€” confirmado como vendido
  - **Suspenso** â€” venda suspensa ou cancelada

### ðŸ”¢ Dados Fixos vs. Dados VariÃ¡veis

**Dados fixos** (nÃ£o mudam apÃ³s a importaÃ§Ã£o inicial):
- NÃºmero do imÃ³vel (chave principal â€” Ãºnico)
- EndereÃ§o completo (logradouro, bairro, cidade, estado)
- DescriÃ§Ã£o original
- Tipo do imÃ³vel

**Dados variÃ¡veis** (podem mudar a cada nova importaÃ§Ã£o â€” geram histÃ³rico):
- PreÃ§o de venda
- Valor de avaliaÃ§Ã£o
- Desconto percentual
- Desconto em reais (calculado pelo sistema)
- Modalidade de venda
- Aceite de financiamento SBPE
- Aceite de financiamento MCMV (campo reservado para uso futuro)
- Aceite de FGTS (obtido via scraping â€” valor inicial: "nÃ£o informado")
- Data de referÃªncia (data de geraÃ§Ã£o do CSV)

---

## ðŸ” ETAPAS DE PROCESSAMENTO DO IMÃ“VEL

Cada imÃ³vel passa por etapas sequenciais de processamento apÃ³s a importaÃ§Ã£o:

1. **ImportaÃ§Ã£o** â€” dados brutos inseridos no banco a partir do CSV
2. **Processamento** â€” sistema organiza e interpreta os dados
3. **GeraÃ§Ã£o de links** â€” criaÃ§Ã£o do link da imagem e do link do edital
4. **Desmembramento da descriÃ§Ã£o** â€” extraÃ§Ã£o via PHP dos campos individuais contidos no texto original (tipo, Ã¡rea, quartos, caracterÃ­sticas)
5. **Scraping** â€” coleta de informaÃ§Ãµes complementares (FGTS, dados adicionais)
6. **GeraÃ§Ã£o de SEO** â€” criaÃ§Ã£o de tÃ­tulos, slugs e meta descriptions
7. **CÃ¡lculos financeiros** â€” desconto em reais, enquadramento em grupo, simulaÃ§Ãµes

---

## ðŸ“ DESMEMBRAMENTO DA DESCRIÃ‡ÃƒO

O campo de descriÃ§Ã£o original contÃ©m mÃºltiplas informaÃ§Ãµes em texto livre. O sistema extrai automaticamente via PHP os seguintes campos:

| Campo extraÃ­do | Exemplo |
|---|---|
| Tipo do imÃ³vel | Casa, Apartamento, Terreno, Sobrado, PrÃ©dio |
| Ãrea total | 69,03 mÂ² |
| Ãrea privativa | 69,03 mÂ² |
| Ãrea do terreno | 99,79 mÂ² |
| Quartos | 3 |
| Banheiros / WC | 1 |
| Salas | 1 |
| Vagas de garagem | 0 |
| Varanda | Sim / NÃ£o |
| Ãrea de serviÃ§o | Sim / NÃ£o |
| Cozinha | Sim / NÃ£o |
| Piscina | Sim / NÃ£o |
| Churrasqueira | Sim / NÃ£o |
| TerraÃ§o | Sim / NÃ£o |

---

## ðŸ“Š GRUPOS DE IMÃ“VEIS

- Os imÃ³veis sÃ£o classificados em grupos com base no **valor de avaliaÃ§Ã£o**
- Cada grupo possui percentuais e valores fixos utilizados em cÃ¡lculos financeiros
- Os grupos sÃ£o preenchidos manualmente pelo administrador
- As alteraÃ§Ãµes sÃ£o raras â€” ocorrem principalmente na fase inicial do sistema

---

## ðŸ“ HIERARQUIA DE LOCALIZAÃ‡ÃƒO

A localizaÃ§Ã£o Ã© estruturada em nÃ­veis relacionais para evitar duplicaÃ§Ã£o e permitir enriquecimento de conteÃºdo:

- **Estado (UF)**
- **MunicÃ­pio**
- **Bairro**
- **CEP**

Ao importar um imÃ³vel, o sistema verifica se o estado, municÃ­pio e bairro jÃ¡ existem na base antes de criar um novo registro.

### ðŸŒ Enriquecimento por MunicÃ­pio e Bairro

Cada municÃ­pio e bairro pode ter seu conteÃºdo enriquecido por InteligÃªncia Artificial, armazenado em um campo JSON. O objetivo Ã© fornecer informaÃ§Ãµes relevantes para compradores que nÃ£o conhecem a regiÃ£o.

**ConteÃºdo gerado para municÃ­pios:**
- ApresentaÃ§Ã£o geral
- Data de fundaÃ§Ã£o, populaÃ§Ã£o, Ã¡rea
- Principais atividades econÃ´micas
- Turismo e pontos turÃ­sticos
- Infraestrutura (aeroportos, hospitais de referÃªncia)

**ConteÃºdo gerado para bairros:**
- EducaÃ§Ã£o (escolas, universidades prÃ³ximas)
- Transporte (linhas de Ã´nibus, metrÃ´, trem, estaÃ§Ãµes prÃ³ximas)
- SaÃºde (hospitais, UBS, clÃ­nicas)
- ComÃ©rcio (shoppings, supermercados, bancos)
- Pontos de referÃªncia (distÃ¢ncia a aeroportos, rodoviÃ¡rias, pontos turÃ­sticos)
- Principais avenidas e vias de acesso

---

## ðŸ” BUSCA DE IMÃ“VEIS

- O site Ã© aberto â€” qualquer visitante pode realizar buscas sem login
- Filtros disponÃ­veis:
  - Estado (UF)
  - Cidade
  - Tipo de imÃ³vel
  - Faixa de valor
  - Modalidade de venda
- Os resultados podem ser **compartilhados via link**
- O link compartilhado gera uma **prÃ©-visualizaÃ§Ã£o** no WhatsApp com a imagem de destaque configurada

---

## ðŸ“¬ GERAÃ‡ÃƒO DE LEAD

- Ao demonstrar interesse em um imÃ³vel, o visitante preenche um formulÃ¡rio
- Esse formulÃ¡rio gera um **lead vinculado ao imÃ³vel e Ã  imobiliÃ¡ria responsÃ¡vel pelo estado**
- A imobiliÃ¡ria Ã© **notificada automaticamente** por e-mail e WhatsApp
- Se o mesmo lead se interessar por mÃºltiplos imÃ³veis, a imobiliÃ¡ria recebe uma notificaÃ§Ã£o separada para cada imÃ³vel
- O histÃ³rico de imÃ³veis de interesse do lead fica **gravado em JSON** dentro do registro do lead:

```json
{
  "imoveis_interesse": [
    { "numero": "1042", "data": "2026-04-10", "modalidade": "Venda Direta Online" },
    { "numero": "3891", "data": "2026-04-15", "modalidade": "Venda Direta" }
  ]
}


ðŸ–¼ï¸ IMAGENS
ðŸ“¸ Foto da Fachada
Ãšnica foto real do imÃ³vel (tirada da rua, da fachada)
Hospedada em servidor externo
O sistema armazena apenas a URL que aponta para essa imagem
ðŸ”— Imagem de Destaque (Open Graph)
Imagem genÃ©rica / institucional do site
Aparece quando um link Ã© compartilhado no WhatsApp ou redes sociais
Serve como convite visual para o usuÃ¡rio clicar no link
Hospedada no servidor prÃ³prio
âš™ï¸ REGRAS DE NEGÃ“CIO PRINCIPAIS
Cada imÃ³vel estÃ¡ vinculado a uma imobiliÃ¡ria por estado
O lead Ã© enviado somente para a imobiliÃ¡ria responsÃ¡vel pelo estado do imÃ³vel
A imobiliÃ¡ria nÃ£o acessa o sistema operacionalmente â€” apenas visualiza
O lead precisa confirmar o e-mail para ser considerado vÃ¡lido
Ãreas restritas do site sÃ³ sÃ£o acessÃ­veis para leads confirmados e logados
Os campos originais do CSV sÃ£o preservados integralmente e nunca alterados
O valor do desconto em reais Ã© calculado pelo sistema: desconto_valor = valor_avaliacao - valor_venda
O histÃ³rico de atualizaÃ§Ãµes de cada imÃ³vel Ã© mantido para anÃ¡lise de evoluÃ§Ã£o de preÃ§o e tempo de mercado
O campo FGTS tem valor inicial "nÃ£o informado" atÃ© que o scraping seja realizado
O campo MCMV estÃ¡ reservado para uso futuro
A data de referÃªncia corresponde Ã  data de geraÃ§Ã£o do CSV, nÃ£o Ã  data de importaÃ§Ã£o

---
# FILE: banco-de-dados.md
---

# ðŸ—„ï¸ BANCO DE DADOS â€” Estrutura TÃ©cnica Completa

## ðŸ“ Arquivo: `docs/banco-de-dados.md`

---

## ðŸ“ VISÃƒO GERAL DAS TABELAS

| Tabela                  | DescriÃ§Ã£o                                                    |
|-------------------------|--------------------------------------------------------------|
| `usuarios`              | Administradores do sistema                                   |
| `imobiliarias`          | ImobiliÃ¡rias parceiras cadastradas                           |
| `leads`                 | Compradores/visitantes cadastrados                           |
| `imoveis`               | ImÃ³veis importados via CSV                                   |
| `imoveis_historico`     | HistÃ³rico de dados variÃ¡veis a cada importaÃ§Ã£o               |
| `imoveis_grupos`        | Grupos de classificaÃ§Ã£o por faixa de valor de avaliaÃ§Ã£o      |
| `imoveis_etapas`        | Etapas de processamento de cada imÃ³vel                       |
| `tipos_imovel`          | Tipos de imÃ³vel (casa, apartamento, terreno, etc.)           |
| `modalidades_venda`     | Modalidades de venda aceitas pelo sistema                    |
| `estados`               | Estados brasileiros (UF)                                     |
| `municipios`            | MunicÃ­pios vinculados aos estados                            |
| `bairros`               | Bairros vinculados aos municÃ­pios                            |
| `imobiliarias_estados`  | VÃ­nculo entre imobiliÃ¡ria e estado atendido                  |
| `atendimentos`          | Registro de atendimentos gerados por leads                   |
| `atendimentos_origem`   | Origens possÃ­veis de um atendimento                          |

---

## ðŸ”§ TABELA: `usuarios`

> Administradores com acesso total ao sistema.

| Campo        | Tipo         | ObrigatÃ³rio | DescriÃ§Ã£o                   |
|--------------|--------------|-------------|-----------------------------|
| `id`         | INT (PK, AI) | âœ…          | Identificador Ãºnico         |
| `nome`       | VARCHAR(100) | âœ…          | Nome completo               |
| `email`      | VARCHAR(150) | âœ…          | E-mail de acesso (Ãºnico)    |
| `senha`      | VARCHAR(255) | âœ…          | Senha criptografada (hash)  |
| `ativo`      | BOOLEAN      | âœ…          | Se o usuÃ¡rio estÃ¡ ativo     |
| `created_at` | TIMESTAMP    | âœ…          | Data de criaÃ§Ã£o do registro |
| `updated_at` | TIMESTAMP    | âœ…          | Data da Ãºltima atualizaÃ§Ã£o  |

---

## ðŸ¢ TABELA: `imobiliarias`

> ImobiliÃ¡rias parceiras que recebem os leads e tÃªm painel de visualizaÃ§Ã£o.

| Campo        | Tipo         | ObrigatÃ³rio | DescriÃ§Ã£o                              |
|--------------|--------------|-------------|----------------------------------------|
| `id`         | INT (PK, AI) | âœ…          | Identificador Ãºnico                    |
| `nome`       | VARCHAR(150) | âœ…          | Nome da imobiliÃ¡ria                    |
| `email`      | VARCHAR(150) | âœ…          | E-mail de acesso e recebimento de lead |
| `senha`      | VARCHAR(255) | âœ…          | Senha criptografada (hash)             |
| `whatsapp`   | VARCHAR(20)  | âœ…          | NÃºmero para recebimento via WhatsApp   |
| `creci`      | VARCHAR(30)  | â¬œ          | NÃºmero do CRECI                        |
| `ativo`      | BOOLEAN      | âœ…          | Se a imobiliÃ¡ria estÃ¡ ativa            |
| `created_at` | TIMESTAMP    | âœ…          | Data de cadastro                       |
| `updated_at` | TIMESTAMP    | âœ…          | Data da Ãºltima atualizaÃ§Ã£o             |

---

## ðŸ‘¤ TABELA: `leads`

> Compradores/visitantes que se cadastraram na plataforma.

| Campo               | Tipo         | ObrigatÃ³rio | DescriÃ§Ã£o                                 |
|---------------------|--------------|-------------|-------------------------------------------|
| `id`                | INT (PK, AI) | âœ…          | Identificador Ãºnico                       |
| `nome`              | VARCHAR(100) | âœ…          | Nome completo                             |
| `email`             | VARCHAR(150) | âœ…          | E-mail (Ãºnico)                            |
| `telefone`          | VARCHAR(20)  | â¬œ          | Telefone/WhatsApp                         |
| `senha`             | VARCHAR(255) | âœ…          | Senha criptografada (hash)                |
| `email_confirmado`  | BOOLEAN      | âœ…          | Se confirmou o e-mail (default: false)    |
| `token_confirmacao` | VARCHAR(255) | â¬œ          | Token enviado por e-mail para validaÃ§Ã£o   |
| `imoveis_interesse` | JSON         | â¬œ          | HistÃ³rico de imÃ³veis de interesse do lead |
| `ativo`             | BOOLEAN      | âœ…          | Se o lead estÃ¡ ativo                      |
| `created_at`        | TIMESTAMP    | âœ…          | Data de cadastro                          |
| `updated_at`        | TIMESTAMP    | âœ…          | Data da Ãºltima atualizaÃ§Ã£o                |

### ðŸ“Œ Exemplo do campo `imoveis_interesse` (JSON)

```json
{
  "imoveis_interesse": [
    { "numero": "1042", "data": "2026-04-10", "modalidade": "Venda Direta Online" },
    { "numero": "3891", "data": "2026-04-15", "modalidade": "Venda Direta" }
  ]
}
```

---

## ðŸ  TABELA: `imoveis`

> ImÃ³veis importados via CSV. Campos originais preservados integralmente e nunca alterados.

| Campo                 | Tipo          | ObrigatÃ³rio | DescriÃ§Ã£o                                                         |
|-----------------------|---------------|-------------|-------------------------------------------------------------------|
| `id`                  | INT (PK, AI)  | âœ…          | Identificador Ãºnico interno                                       |
| `numero_original`     | VARCHAR(50)   | âœ…          | NÃºmero original do imÃ³vel no CSV (Ãºnico, chave principal)         |
| `id_imobiliaria`      | INT (FK)      | âœ…          | ImobiliÃ¡ria responsÃ¡vel pelo estado do imÃ³vel                     |
| `id_tipo_imovel`      | INT (FK)      | âœ…          | Tipo do imÃ³vel â†’ `tipos_imovel.id`                                |
| `id_estado`           | INT (FK)      | âœ…          | Estado do imÃ³vel â†’ `estados.id`                                   |
| `id_municipio`        | INT (FK)      | âœ…          | MunicÃ­pio do imÃ³vel â†’ `municipios.id`                             |
| `id_bairro`           | INT (FK)      | â¬œ          | Bairro do imÃ³vel â†’ `bairros.id`                                   |
| `id_grupo`            | INT (FK)      | â¬œ          | Grupo de classificaÃ§Ã£o â†’ `imoveis_grupos.id`                      |
| `id_etapa`            | INT (FK)      | âœ…          | Etapa de processamento atual â†’ `imoveis_etapas.id`                |
| `endereco`            | VARCHAR(255)  | âœ…          | Logradouro original do CSV (dado fixo)                            |
| `cep`                 | VARCHAR(10)   | â¬œ          | CEP (dado fixo)                                                   |
| `descricao_original`  | TEXT          | âœ…          | DescriÃ§Ã£o original do CSV (preservada, nunca alterada)            |
| `area_total`          | DECIMAL(10,2) | â¬œ          | Ãrea total em mÂ² (extraÃ­da da descriÃ§Ã£o via PHP)                  |
| `area_privativa`      | DECIMAL(10,2) | â¬œ          | Ãrea privativa em mÂ² (extraÃ­da da descriÃ§Ã£o via PHP)              |
| `area_terreno`        | DECIMAL(10,2) | â¬œ          | Ãrea do terreno em mÂ² (extraÃ­da da descriÃ§Ã£o via PHP)             |
| `quartos`             | TINYINT       | â¬œ          | NÃºmero de quartos (extraÃ­do da descriÃ§Ã£o via PHP)                 |
| `banheiros`           | TINYINT       | â¬œ          | NÃºmero de banheiros (extraÃ­do da descriÃ§Ã£o via PHP)               |
| `salas`               | TINYINT       | â¬œ          | NÃºmero de salas (extraÃ­do da descriÃ§Ã£o via PHP)                   |
| `garagens`            | TINYINT       | â¬œ          | NÃºmero de vagas de garagem (extraÃ­do da descriÃ§Ã£o via PHP)        |
| `varanda`             | BOOLEAN       | â¬œ          | Possui varanda (extraÃ­do da descriÃ§Ã£o via PHP)                    |
| `area_servico`        | BOOLEAN       | â¬œ          | Possui Ã¡rea de serviÃ§o (extraÃ­do da descriÃ§Ã£o via PHP)            |
| `cozinha`             | BOOLEAN       | â¬œ          | Possui cozinha (extraÃ­do da descriÃ§Ã£o via PHP)                    |
| `piscina`             | BOOLEAN       | â¬œ          | Possui piscina (extraÃ­do da descriÃ§Ã£o via PHP)                    |
| `churrasqueira`       | BOOLEAN       | â¬œ          | Possui churrasqueira (extraÃ­do da descriÃ§Ã£o via PHP)              |
| `terraco`             | BOOLEAN       | â¬œ          | Possui terraÃ§o (extraÃ­do da descriÃ§Ã£o via PHP)                    |
| `foto_fachada_url`    | VARCHAR(500)  | â¬œ          | URL da foto da fachada (servidor externo)                         |
| `imagem_destaque_url` | VARCHAR(500)  | â¬œ          | URL da imagem Open Graph para compartilhamento (servidor prÃ³prio) |
| `link_edital`         | VARCHAR(500)  | â¬œ          | Link do edital oficial                                            |
| `aceita_fgts`         | ENUM          | âœ…          | `nao_informado` / `sim` / `nao` â€” default: `nao_informado`        |
| `aceita_financ_sbpe`  | BOOLEAN       | â¬œ          | Aceita financiamento SBPE                                         |
| `aceita_financ_mcmv`  | BOOLEAN       | â¬œ          | Aceita financiamento MCMV (reservado para uso futuro)             |
| `status`              | ENUM          | âœ…          | `ativo` / `fora_de_venda` / `vendido` / `suspenso`                |
| `slug`                | VARCHAR(255)  | â¬œ          | Slug para URL amigÃ¡vel (gerado na etapa de SEO)                   |
| `meta_title`          | VARCHAR(160)  | â¬œ          | TÃ­tulo SEO (gerado na etapa de SEO)                               |
| `meta_description`    | VARCHAR(320)  | â¬œ          | Meta description SEO (gerado na etapa de SEO)                     |
| `created_at`          | TIMESTAMP     | âœ…          | Data de inserÃ§Ã£o no sistema                                       |
| `updated_at`          | TIMESTAMP     | âœ…          | Data da Ãºltima atualizaÃ§Ã£o                                        |

---

## ðŸ”„ TABELA: `imoveis_historico`

> Registra os dados variÃ¡veis de cada imÃ³vel a cada nova importaÃ§Ã£o do CSV, permitindo anÃ¡lise de evoluÃ§Ã£o de preÃ§o e tempo de mercado.

| Campo                 | Tipo          | ObrigatÃ³rio | DescriÃ§Ã£o                                                      |
|-----------------------|---------------|-------------|----------------------------------------------------------------|
| `id`                  | INT (PK, AI)  | âœ…          | Identificador Ãºnico                                            |
| `id_imovel`           | INT (FK)      | âœ…          | ImÃ³vel referenciado â†’ `imoveis.id`                             |
| `id_modalidade`       | INT (FK)      | âœ…          | Modalidade de venda vigente â†’ `modalidades_venda.id`           |
| `data_referencia`     | DATE          | âœ…          | Data de geraÃ§Ã£o do CSV (nÃ£o a data de importaÃ§Ã£o)              |
| `valor_avaliacao`     | DECIMAL(15,2) | âœ…          | Valor de avaliaÃ§Ã£o do imÃ³vel                                   |
| `valor_venda`         | DECIMAL(15,2) | âœ…          | Valor de venda                                                 |
| `desconto_percentual` | DECIMAL(5,2)  | âœ…          | Percentual de desconto (vem do CSV)                            |
| `desconto_valor`      | DECIMAL(15,2) | âœ…          | Desconto em reais â€” calculado: `valor_avaliacao - valor_venda` |
| `aceita_financ_sbpe`  | BOOLEAN       | â¬œ          | Aceita SBPE nesta atualizaÃ§Ã£o                                  |
| `aceita_financ_mcmv`  | BOOLEAN       | â¬œ          | Aceita MCMV nesta atualizaÃ§Ã£o (uso futuro)                     |
| `created_at`          | TIMESTAMP     | âœ…          | Data de registro desta entrada                                 |

---

## ðŸ“Š TABELA: `imoveis_grupos`

> Grupos de classificaÃ§Ã£o por faixa de valor de avaliaÃ§Ã£o, com parÃ¢metros para cÃ¡lculos financeiros. Preenchidos manualmente pelo administrador.

| Campo          | Tipo          | ObrigatÃ³rio | DescriÃ§Ã£o                                         |
|----------------|---------------|-------------|---------------------------------------------------|
| `id`           | INT (PK, AI)  | âœ…          | Identificador Ãºnico                               |
| `nome`         | VARCHAR(100)  | âœ…          | Nome do grupo                                     |
| `valor_minimo` | DECIMAL(15,2) | âœ…          | Valor mÃ­nimo de avaliaÃ§Ã£o para enquadramento      |
| `valor_maximo` | DECIMAL(15,2) | âœ…          | Valor mÃ¡ximo de avaliaÃ§Ã£o para enquadramento      |
| `percentual_1` | DECIMAL(5,2)  | â¬œ          | Percentual configurÃ¡vel para cÃ¡lculos financeiros |
| `percentual_2` | DECIMAL(5,2)  | â¬œ          | Percentual configurÃ¡vel para cÃ¡lculos financeiros |
| `valor_fixo_1` | DECIMAL(15,2) | â¬œ          | Valor fixo configurÃ¡vel para cÃ¡lculos financeiros |
| `valor_fixo_2` | DECIMAL(15,2) | â¬œ          | Valor fixo configurÃ¡vel para cÃ¡lculos financeiros |
| `ativo`        | BOOLEAN       | âœ…          | Se o grupo estÃ¡ ativo                             |
| `created_at`   | TIMESTAMP     | âœ…          | Data de criaÃ§Ã£o                                   |
| `updated_at`   | TIMESTAMP     | âœ…          | Data da Ãºltima atualizaÃ§Ã£o                        |

---

## ðŸ” TABELA: `imoveis_etapas`

> Etapas de processamento pelas quais cada imÃ³vel passa apÃ³s a importaÃ§Ã£o.

| Campo       | Tipo         | ObrigatÃ³rio | DescriÃ§Ã£o                        |
|-------------|--------------|-------------|----------------------------------|
| `id`        | INT (PK, AI) | âœ…          | Identificador Ãºnico              |
| `nome`      | VARCHAR(100) | âœ…          | Nome da etapa                    |
| `descricao` | TEXT         | â¬œ          | DescriÃ§Ã£o do que ocorre na etapa |
| `ordem`     | TINYINT      | âœ…          | Ordem sequencial de execuÃ§Ã£o     |
| `ativo`     | BOOLEAN      | âœ…          | Se a etapa estÃ¡ ativa            |

### ðŸ“Œ Etapas previstas (em ordem)

| Ordem | Nome                        |
|-------|-----------------------------|
| 1     | ImportaÃ§Ã£o                  |
| 2     | Processamento               |
| 3     | GeraÃ§Ã£o de links            |
| 4     | Desmembramento da descriÃ§Ã£o |
| 5     | Scraping                    |
| 6     | GeraÃ§Ã£o de SEO              |
| 7     | CÃ¡lculos financeiros        |

---

## ðŸ·ï¸ TABELA: `tipos_imovel`

> Tipos de imÃ³vel extraÃ­dos da descriÃ§Ã£o original via PHP.

| Campo   | Tipo         | ObrigatÃ³rio | DescriÃ§Ã£o           |
|---------|--------------|-------------|---------------------|
| `id`    | INT (PK, AI) | âœ…          | Identificador Ãºnico |
| `nome`  | VARCHAR(80)  | âœ…          | Nome do tipo        |
| `ativo` | BOOLEAN      | âœ…          | Se estÃ¡ ativo       |

### ðŸ“Œ Tipos previstos

- Casa
- Apartamento
- Terreno
- Sobrado
- PrÃ©dio

---

## ðŸ’° TABELA: `modalidades_venda`

> Modalidades de venda aceitas para importaÃ§Ã£o no sistema.

| Campo   | Tipo         | ObrigatÃ³rio | DescriÃ§Ã£o           |
|---------|--------------|-------------|---------------------|
| `id`    | INT (PK, AI) | âœ…          | Identificador Ãºnico |
| `nome`  | VARCHAR(100) | âœ…          | Nome da modalidade  |
| `ativo` | BOOLEAN      | âœ…          | Se estÃ¡ ativa       |

### ðŸ“Œ Modalidades aceitas

- Venda Direta
- Venda Direta Online

---

## ðŸ—ºï¸ TABELA: `estados`

> Estados brasileiros.

| Campo   | Tipo         | ObrigatÃ³rio | DescriÃ§Ã£o           |
|---------|--------------|-------------|---------------------|
| `id`    | INT (PK, AI) | âœ…          | Identificador Ãºnico |
| `nome`  | VARCHAR(50)  | âœ…          | Nome do estado      |
| `uf`    | CHAR(2)      | âœ…          | Sigla do estado     |

---

## ðŸ™ï¸ TABELA: `municipios`

> MunicÃ­pios vinculados aos estados, com suporte a enriquecimento por IA.

| Campo          | Tipo         | ObrigatÃ³rio | DescriÃ§Ã£o                                                        |
|----------------|--------------|-------------|------------------------------------------------------------------|
| `id`           | INT (PK, AI) | âœ…          | Identificador Ãºnico                                              |
| `id_estado`    | INT (FK)     | âœ…          | Estado ao qual pertence â†’ `estados.id`                           |
| `nome`         | VARCHAR(150) | âœ…          | Nome do municÃ­pio                                                |
| `conteudo_ia`  | JSON         | â¬œ          | ConteÃºdo enriquecido pela IA (economia, turismo, infraestrutura) |
| `ia_status`    | ENUM         | âœ…          | `pendente` / `gerado` / `erro` â€” default: `pendente`             |
| `ia_gerado_em` | DATETIME     | â¬œ          | Data em que o conteÃºdo IA foi gerado                             |
| `created_at`   | TIMESTAMP    | âœ…          | Data de criaÃ§Ã£o                                                  |
| `updated_at`   | TIMESTAMP    | âœ…          | Data da Ãºltima atualizaÃ§Ã£o                                       |

### ðŸ“Œ Exemplo do campo `conteudo_ia` â€” MunicÃ­pios (JSON)

```json
{
  "apresentacao": "MunicÃ­pio localizado no interior de...",
  "dados_gerais": {
    "data_fundacao": "15 de novembro de 1890",
    "populacao": "1.200.000 habitantes",
    "area_km2": "4.557 kmÂ²"
  },
  "economia": {
    "principais_atividades": ["IndÃºstria", "Turismo", "AgronegÃ³cio"]
  },
  "turismo": {
    "pontos_turisticos": ["Ponto A", "Ponto B"]
  },
  "infraestrutura": {
    "aeroportos": ["Aeroporto Regional X"],
    "hospitais_referencia": ["Hospital Regional Y"]
  }
}
```

---

## ðŸ˜ï¸ TABELA: `bairros`

> Bairros vinculados aos municÃ­pios, com suporte a enriquecimento por IA.

| Campo          | Tipo          | ObrigatÃ³rio | DescriÃ§Ã£o                                                            |
|----------------|---------------|-------------|----------------------------------------------------------------------|
| `id`           | INT (PK, AI)  | âœ…          | Identificador Ãºnico                                                  |
| `id_municipio` | INT (FK)      | âœ…          | MunicÃ­pio ao qual pertence â†’ `municipios.id`                         |
| `nome`         | VARCHAR(150)  | âœ…          | Nome do bairro                                                       |
| `latitude`     | DECIMAL(10,7) | â¬œ          | Latitude geogrÃ¡fica                                                  |
| `longitude`    | DECIMAL(10,7) | â¬œ          | Longitude geogrÃ¡fica                                                 |
| `conteudo_ia`  | JSON          | â¬œ          | ConteÃºdo enriquecido pela IA (educaÃ§Ã£o, transporte, saÃºde, comÃ©rcio) |
| `ia_status`    | ENUM          | âœ…          | `pendente` / `gerado` / `erro` â€” default: `pendente`                 |
| `ia_gerado_em` | DATETIME      | â¬œ          | Data em que o conteÃºdo IA foi gerado                                 |
| `created_at`   | TIMESTAMP     | âœ…          | Data de criaÃ§Ã£o                                                      |
| `updated_at`   | TIMESTAMP     | âœ…          | Data da Ãºltima atualizaÃ§Ã£o                                           |

### ðŸ“Œ Exemplo do campo `conteudo_ia` â€” Bairros (JSON)

```json
{
  "educacao": {
    "escolas": ["Escola Municipal X"],
    "universidades_proximas": ["UERJ - 3,2km"]
  },
  "transporte": {
    "onibus": ["474", "SV-01"],
    "metro": false,
    "trem": true,
    "estacao_proxima": "EstaÃ§Ã£o Vista Alegre - 400m"
  },
  "saude": {
    "hospitais": ["UPA Vista Alegre"],
    "ubs": ["ClÃ­nica da FamÃ­lia Y"]
  },
  "comercio": {
    "shoppings": ["Shopping Nova AmÃ©rica - 2,1km"],
    "supermercados": ["Extra", "Guanabara"]
  },
  "pontos_referencia": {
    "aeroporto": "Aeroporto do GaleÃ£o - 8,5km",
    "rodoviaria": "RodoviÃ¡ria Novo Rio - 12km"
  },
  "avenidas_principais": ["Avenida Brasil", "Estrada Intendente MagalhÃ£es"]
}
```

---

## ðŸ”— TABELA: `imobiliarias_estados`

> Define qual imobiliÃ¡ria Ã© responsÃ¡vel por cada estado. Uma imobiliÃ¡ria por estado.

| Campo            | Tipo         | ObrigatÃ³rio | DescriÃ§Ã£o               |
|------------------|--------------|-------------|-------------------------|
| `id`             | INT (PK, AI) | âœ…          | Identificador Ãºnico     |
| `id_imobiliaria` | INT (FK)     | âœ…          | ImobiliÃ¡ria responsÃ¡vel |
| `id_estado`      | INT (FK)     | âœ…          | Estado atendido         |
| `created_at`     | TIMESTAMP    | âœ…          | Data do vÃ­nculo         |

---

## ðŸ“£ TABELA: `atendimentos_origem`

> Origens possÃ­veis de um atendimento gerado por lead.

| Campo   | Tipo         | ObrigatÃ³rio | DescriÃ§Ã£o                     |
|---------|--------------|-------------|-------------------------------|
| `id`    | INT (PK, AI) | âœ…          | Identificador Ãºnico           |
| `nome`  | VARCHAR(100) | âœ…          | Nome da origem do atendimento |
| `ativo` | BOOLEAN      | âœ…          | Se estÃ¡ ativa                 |

### ðŸ“Œ Origens previstas

- FormulÃ¡rio do site
- WhatsApp do anÃºncio
- WhatsApp do site
- E-mail
- Blog

---

## ðŸ“‹ TABELA: `atendimentos`

> Registro de cada atendimento gerado por um lead interessado em um imÃ³vel. A imobiliÃ¡ria responsÃ¡vel pelo estado do imÃ³vel Ã© notificada automaticamente por e-mail e WhatsApp.

| Campo              | Tipo         | ObrigatÃ³rio | DescriÃ§Ã£o                                        |
|--------------------|--------------|-------------|--------------------------------------------------|
| `id`               | INT (PK, AI) | âœ…          | Identificador Ãºnico                              |
| `id_lead`          | INT (FK)     | âœ…          | Lead que gerou o atendimento â†’ `leads.id`        |
| `id_imovel`        | INT (FK)     | âœ…          | ImÃ³vel de interesse â†’ `imoveis.id`               |
| `id_imobiliaria`   | INT (FK)     | âœ…          | ImobiliÃ¡ria notificada â†’ `imobiliarias.id`       |
| `id_origem`        | INT (FK)     | âœ…          | Origem do atendimento â†’ `atendimentos_origem.id` |
| `mensagem`         | TEXT         | â¬œ          | Mensagem enviada pelo lead no formulÃ¡rio         |
| `email_enviado`    | BOOLEAN      | âœ…          | Se a notificaÃ§Ã£o por e-mail foi enviada          |
| `whatsapp_enviado` | BOOLEAN      | âœ…          | Se a notificaÃ§Ã£o por WhatsApp foi enviada        |
| `created_at`       | TIMESTAMP    | âœ…          | Data e hora do atendimento                       |

---

## ðŸ”‘ RELACIONAMENTOS (Chaves Estrangeiras)

```
imoveis.id_imobiliaria          â†’ imobiliarias.id
imoveis.id_tipo_imovel          â†’ tipos_imovel.id
imoveis.id_estado               â†’ estados.id
imoveis.id_municipio            â†’ municipios.id
imoveis.id_bairro               â†’ bairros.id
imoveis.id_grupo                â†’ imoveis_grupos.id
imoveis.id_etapa                â†’ imoveis_etapas.id
imoveis_historico.id_imovel     â†’ imoveis.id
imoveis_historico.id_modalidade â†’ modalidades_venda.id
municipios.id_estado            â†’ estados.id
bairros.id_municipio            â†’ municipios.id
imobiliarias_estados.id_imobiliaria â†’ imobiliarias.id
imobiliarias_estados.id_estado      â†’ estados.id
atendimentos.id_lead            â†’ leads.id
atendimentos.id_imovel          â†’ imoveis.id
atendimentos.id_imobiliaria     â†’ imobiliarias.id
atendimentos.id_origem          â†’ atendimentos_origem.id
```

---

## ðŸ“Š DIAGRAMA SIMPLIFICADO

```
[estados] â”€â”€< [municipios] â”€â”€< [bairros]
                                   â”‚
[estados] â”€â”€< [imobiliarias_estados] >â”€â”€ [imobiliarias]
                                   â”‚
                              [imoveis] >â”€â”€ [tipos_imovel]
                              [imoveis] >â”€â”€ [modalidades_venda]
                              [imoveis] >â”€â”€ [imoveis_grupos]
                              [imoveis] â”€â”€< [imoveis_historico]
                              [imoveis] â”€â”€< [imoveis_etapas]
                                   â”‚
                         [atendimentos] >â”€â”€ [leads]
                         [atendimentos] >â”€â”€ [atendimentos_origem]
```

---

## ðŸ“ LocalizaÃ§Ã£o deste arquivo

```
/docs
â”œâ”€â”€ visao-geral.md       â† visÃ£o geral do sistema
â””â”€â”€ banco-de-dados.md    â† este arquivo
```

---

*Documento gerado em: maio de 2026 â€” VersÃ£o: 2.0*




---
# FILE: SECURITY.md
---

# PolÃ­tica de SeguranÃ§a

Este documento define as prÃ¡ticas, responsabilidades e procedimentos de seguranÃ§a deste projeto.

O objetivo Ã© reduzir riscos, evitar exposiÃ§Ã£o de dados sensÃ­veis, orientar contribuiÃ§Ãµes seguras e estabelecer um processo claro para reportar e corrigir vulnerabilidades.

---

## 1. PrincÃ­pio geral

SeguranÃ§a deve ser tratada como parte essencial da qualidade do projeto.

Toda alteraÃ§Ã£o deve considerar possÃ­veis impactos em:

- Dados dos usuÃ¡rios
- AutenticaÃ§Ã£o
- AutorizaÃ§Ã£o
- PermissÃµes
- Banco de dados
- APIs
- Logs
- ConfiguraÃ§Ãµes
- DependÃªncias
- Infraestrutura
- IntegraÃ§Ãµes externas

SeguranÃ§a nÃ£o deve ser deixada apenas para o final do desenvolvimento.

---

## 2. Responsabilidade

Toda pessoa que contribui com o projeto Ã© responsÃ¡vel por evitar a introduÃ§Ã£o de falhas de seguranÃ§a.

Isso inclui:

- Revisar o prÃ³prio cÃ³digo
- Validar entradas de usuÃ¡rio
- Evitar exposiÃ§Ã£o de dados sensÃ­veis
- NÃ£o versionar segredos
- Usar dependÃªncias com cuidado
- Reportar vulnerabilidades encontradas
- Corrigir problemas de seguranÃ§a com prioridade adequada

A responsabilidade por seguranÃ§a Ã© compartilhada.

---

## 3. Dados sensÃ­veis

Nunca devem ser versionados, enviados em Pull Requests ou compartilhados publicamente:

- Senhas
- Tokens
- Chaves privadas
- Chaves SSH
- Certificados privados
- Credenciais de banco de dados
- Arquivos `.env` reais
- Segredos de produÃ§Ã£o
- Dados pessoais sensÃ­veis
- Dados financeiros
- Cookies de sessÃ£o
- Tokens JWT reais
- Logs contendo informaÃ§Ãµes privadas

Se for necessÃ¡rio usar exemplos, utilize valores fictÃ­cios.

Exemplo seguro:

```env
DATABASE_URL=mysql://user:password@localhost:3306/database
API_KEY=example_api_key
JWT_SECRET=example_secret
```

---

## 4. Arquivos de ambiente

Arquivos de ambiente reais nÃ£o devem ser enviados para o repositÃ³rio.

NÃ£o versionar:

```txt
.env
.env.local
.env.production
.env.development.local
.env.test.local
```

Quando necessÃ¡rio, criar arquivos de exemplo sem valores reais:

```txt
.env.example
```

Exemplo de `.env.example`:

```env
DATABASE_URL=mysql://user:password@localhost:3306/database
API_BASE_URL=https://example.com
JWT_SECRET=example_secret
NODE_ENV=development
```

O arquivo de exemplo deve documentar as variÃ¡veis necessÃ¡rias, mas nunca conter credenciais reais.

---

## 5. AutenticaÃ§Ã£o

Funcionalidades de autenticaÃ§Ã£o devem ser implementadas com cuidado.

Verifique sempre:

- Se credenciais sÃ£o validadas corretamente
- Se senhas nunca sÃ£o salvas em texto puro
- Se sessÃµes expiram corretamente
- Se tokens possuem tempo de expiraÃ§Ã£o adequado
- Se logout invalida a sessÃ£o quando necessÃ¡rio
- Se erros de login nÃ£o revelam informaÃ§Ãµes sensÃ­veis
- Se hÃ¡ proteÃ§Ã£o contra forÃ§a bruta quando aplicÃ¡vel

Mensagens de erro devem evitar revelar se um email, usuÃ¡rio ou conta existe.

Exemplo recomendado:

```txt
Credenciais invÃ¡lidas.
```

Evite mensagens como:

```txt
Este email nÃ£o estÃ¡ cadastrado.
```

ou:

```txt
A senha estÃ¡ incorreta.
```

---

## 6. Senhas

Senhas devem ser tratadas com alto nÃ­vel de proteÃ§Ã£o.

Regras recomendadas:

- Nunca armazenar senhas em texto puro
- Utilizar algoritmo de hash seguro
- Utilizar salt quando aplicÃ¡vel
- NÃ£o registrar senhas em logs
- NÃ£o retornar senhas em APIs
- NÃ£o enviar senhas em mensagens de erro
- NÃ£o expor senhas em telas administrativas

Quando houver redefiniÃ§Ã£o de senha:

- O token deve expirar
- O token deve ser de uso Ãºnico
- O token nÃ£o deve ser previsÃ­vel
- O fluxo deve evitar enumeraÃ§Ã£o de usuÃ¡rios

---

## 7. AutorizaÃ§Ã£o

Autenticar um usuÃ¡rio nÃ£o significa autorizar todas as aÃ§Ãµes.

Sempre validar permissÃµes no backend ou no servidor responsÃ¡vel pela regra de negÃ³cio.

Verifique:

- Se o usuÃ¡rio pode acessar o recurso solicitado
- Se o usuÃ¡rio pode alterar o recurso solicitado
- Se o usuÃ¡rio pertence Ã  organizaÃ§Ã£o, conta ou contexto correto
- Se papÃ©is e permissÃµes estÃ£o sendo respeitados
- Se usuÃ¡rios comuns nÃ£o acessam rotas administrativas
- Se IDs enviados pelo cliente nÃ£o permitem acesso indevido

Nunca confiar apenas na interface para proteger aÃ§Ãµes.

---

## 8. Controle de acesso

Todo recurso sensÃ­vel deve ter controle de acesso explÃ­cito.

AtenÃ§Ã£o especial para:

- PainÃ©is administrativos
- Dados de usuÃ¡rios
- Dados financeiros
- Arquivos privados
- RelatÃ³rios
- ConfiguraÃ§Ãµes
- IntegraÃ§Ãµes
- Rotas internas
- OperaÃ§Ãµes destrutivas

Antes de permitir uma aÃ§Ã£o, valide:

- Quem estÃ¡ solicitando
- Qual recurso estÃ¡ sendo acessado
- Qual permissÃ£o Ã© necessÃ¡ria
- Se a aÃ§Ã£o Ã© permitida naquele contexto

---

## 9. ValidaÃ§Ã£o de entrada

Toda entrada externa deve ser validada.

Entradas externas incluem:

- Corpo de requisiÃ§Ãµes
- ParÃ¢metros de URL
- Query strings
- Headers
- Cookies
- Uploads
- Dados vindos de integraÃ§Ãµes
- Dados de formulÃ¡rios
- Dados importados

Validar:

- Tipo
- Formato
- Tamanho
- Obrigatoriedade
- Valores permitidos
- Limites
- Regras de negÃ³cio

Nunca assumir que dados vindos do cliente sÃ£o confiÃ¡veis.

---

## 10. SanitizaÃ§Ã£o de dados

AlÃ©m de validar, pode ser necessÃ¡rio sanitizar dados.

SanitizaÃ§Ã£o pode incluir:

- Remover espaÃ§os extras
- Normalizar email
- Remover caracteres invÃ¡lidos
- Escapar conteÃºdo perigoso
- Limitar tamanho de textos
- Bloquear HTML quando nÃ£o permitido

A sanitizaÃ§Ã£o deve ser feita com cuidado para nÃ£o modificar dados de forma inesperada.

---

## 11. SQL Injection

Consultas ao banco de dados devem evitar concatenaÃ§Ã£o direta de entrada do usuÃ¡rio.

Evite:

```sql
SELECT * FROM users WHERE email = '${email}';
```

Prefira queries parametrizadas, query builders ou ORM com proteÃ§Ã£o adequada.

Exemplo conceitual:

```sql
SELECT * FROM users WHERE email = ?;
```

Toda entrada usada em consultas deve ser tratada como nÃ£o confiÃ¡vel.

---

## 12. XSS

Ao renderizar conteÃºdo fornecido por usuÃ¡rios, considere riscos de XSS.

AtenÃ§Ã£o especial para:

- ComentÃ¡rios
- DescriÃ§Ãµes
- Campos de perfil
- ConteÃºdo HTML
- Mensagens
- ParÃ¢metros refletidos na tela
- Dados vindos de integraÃ§Ãµes externas

Boas prÃ¡ticas:

- Escapar conteÃºdo por padrÃ£o
- Evitar renderizar HTML bruto
- Sanitizar HTML quando for realmente necessÃ¡rio permitir HTML
- Usar bibliotecas confiÃ¡veis para sanitizaÃ§Ã£o
- NÃ£o inserir dados nÃ£o confiÃ¡veis diretamente em scripts

Evite renderizaÃ§Ã£o insegura de HTML.

---

## 13. CSRF

Quando o projeto utilizar cookies de sessÃ£o ou autenticaÃ§Ã£o baseada em cookies, avalie proteÃ§Ã£o contra CSRF.

Medidas possÃ­veis:

- Tokens CSRF
- Cookies `SameSite`
- ValidaÃ§Ã£o de origem
- ValidaÃ§Ã£o de referer quando aplicÃ¡vel
- Uso correto de mÃ©todos HTTP
- ConfirmaÃ§Ã£o para aÃ§Ãµes sensÃ­veis

AÃ§Ãµes como exclusÃ£o, alteraÃ§Ã£o de senha ou mudanÃ§a de permissÃµes exigem cuidado extra.

---

## 14. Upload de arquivos

Uploads devem ser tratados como operaÃ§Ã£o de risco.

Verifique:

- Tipo de arquivo permitido
- Tamanho mÃ¡ximo
- Nome do arquivo
- ExtensÃ£o
- MIME type
- Local de armazenamento
- PermissÃµes de acesso
- Possibilidade de execuÃ§Ã£o do arquivo
- Varredura ou validaÃ§Ã£o adicional quando aplicÃ¡vel

NÃ£o confie apenas na extensÃ£o do arquivo.

Evite permitir upload de arquivos executÃ¡veis.

Exemplos de extensÃµes perigosas:

```txt
.exe
.sh
.bat
.cmd
.php
.jsp
```

---

## 15. Logs

Logs sÃ£o importantes para investigaÃ§Ã£o, mas nÃ£o devem expor informaÃ§Ãµes sensÃ­veis.

NÃ£o registrar:

- Senhas
- Tokens
- Chaves privadas
- Dados de cartÃ£o
- Documentos pessoais
- Cookies de sessÃ£o
- Credenciais
- Segredos
- Dados pessoais sensÃ­veis

Bons logs devem conter:

- Evento ocorrido
- Data e hora
- Contexto tÃ©cnico
- Identificador nÃ£o sensÃ­vel
- Erro resumido
- Local onde ocorreu

Exemplo aceitÃ¡vel:

```txt
Falha ao criar pedido para user_id=123. Motivo: estoque insuficiente.
```

Exemplo inadequado:

```txt
Falha no login. Email=usuario@example.com Senha=123456 Token=abc123
```

---

## 16. Mensagens de erro

Mensagens de erro nÃ£o devem revelar detalhes internos sensÃ­veis.

Evite expor:

- Stack traces em produÃ§Ã£o
- Nomes internos de tabelas
- Queries SQL
- Caminhos absolutos do servidor
- Chaves
- Tokens
- ConfiguraÃ§Ãµes internas
- Detalhes de infraestrutura

Para usuÃ¡rios finais, prefira mensagens claras e seguras.

Exemplo:

```txt
NÃ£o foi possÃ­vel concluir a operaÃ§Ã£o. Tente novamente.
```

Para logs internos, registre detalhes suficientes sem expor segredos.

---

## 17. APIs

APIs devem ser protegidas contra uso indevido.

Verifique:

- AutenticaÃ§Ã£o
- AutorizaÃ§Ã£o
- ValidaÃ§Ã£o de entrada
- Rate limiting quando aplicÃ¡vel
- PaginaÃ§Ã£o em listagens
- Limites de tamanho
- Tratamento de erro
- Versionamento quando necessÃ¡rio
- NÃ£o exposiÃ§Ã£o de campos sensÃ­veis
- Status codes adequados

Nunca retornar dados sensÃ­veis desnecessÃ¡rios.

Exemplo de campos que geralmente nÃ£o devem ser retornados:

```txt
password
password_hash
reset_token
access_token
refresh_token
secret_key
private_key
```

---

## 18. Rate limiting

Considere limitar requisiÃ§Ãµes em rotas sensÃ­veis.

Rotas que podem precisar de rate limiting:

- Login
- Cadastro
- RecuperaÃ§Ã£o de senha
- Envio de email
- APIs pÃºblicas
- Busca intensiva
- Upload de arquivos
- GeraÃ§Ã£o de tokens
- Webhooks

O objetivo Ã© reduzir abuso, forÃ§a bruta e sobrecarga.

---

## 19. Webhooks

Webhooks devem ser validados antes de processar dados.

Verifique:

- Assinatura do provedor
- Origem da requisiÃ§Ã£o
- Timestamp quando aplicÃ¡vel
- Replay attacks
- IdempotÃªncia
- Estrutura do payload
- Tratamento de erros
- Logs seguros

NÃ£o confie em webhooks sem validaÃ§Ã£o.

---

## 20. DependÃªncias

DependÃªncias externas podem introduzir vulnerabilidades.

Antes de adicionar uma dependÃªncia, avalie:

- Necessidade real
- Popularidade e manutenÃ§Ã£o
- HistÃ³rico de seguranÃ§a
- LicenÃ§a
- Tamanho
- FrequÃªncia de atualizaÃ§Ãµes
- Alternativas jÃ¡ existentes no projeto

ApÃ³s adicionar dependÃªncias:

- Mantenha versÃµes atualizadas
- Revise alertas de seguranÃ§a
- Remova dependÃªncias nÃ£o utilizadas
- Evite pacotes desconhecidos sem justificativa

---

## 21. AtualizaÃ§Ãµes de seguranÃ§a

CorreÃ§Ãµes de seguranÃ§a devem ter prioridade adequada.

Ao identificar dependÃªncia vulnerÃ¡vel:

- Avalie o impacto real no projeto
- Atualize para versÃ£o segura quando possÃ­vel
- Execute testes apÃ³s atualizaÃ§Ã£o
- Registre riscos conhecidos
- Evite ignorar alertas sem anÃ¡lise

Se uma atualizaÃ§Ã£o quebrar compatibilidade, planeje a correÃ§Ã£o com cuidado.

---

## 22. ConfiguraÃ§Ã£o segura

ConfiguraÃ§Ãµes devem ser revisadas antes de uso em produÃ§Ã£o.

Verifique:

- Ambiente correto
- VariÃ¡veis obrigatÃ³rias
- Logs de debug desativados
- Erros detalhados desativados em produÃ§Ã£o
- CORS configurado corretamente
- HTTPS habilitado quando aplicÃ¡vel
- PermissÃµes mÃ­nimas necessÃ¡rias
- Segredos fora do cÃ³digo
- ServiÃ§os externos configurados com seguranÃ§a

ConfiguraÃ§Ãµes de desenvolvimento nÃ£o devem ser copiadas diretamente para produÃ§Ã£o.

---

## 23. CORS

ConfiguraÃ§Ãµes de CORS devem ser restritivas.

Evite liberar origens amplas sem necessidade.

Evite:

```txt
Access-Control-Allow-Origin: *
```

Especialmente quando houver credenciais, cookies ou dados privados.

Prefira liberar apenas domÃ­nios necessÃ¡rios.

Exemplo conceitual:

```txt
Access-Control-Allow-Origin: https://example.com
```

---

## 24. PermissÃµes mÃ­nimas

Use o princÃ­pio do menor privilÃ©gio.

Cada usuÃ¡rio, serviÃ§o, token ou integraÃ§Ã£o deve ter apenas as permissÃµes necessÃ¡rias para executar sua funÃ§Ã£o.

Evite:

- Tokens com acesso total sem necessidade
- UsuÃ¡rios administrativos para tarefas simples
- Chaves compartilhadas entre ambientes
- PermissÃµes amplas em banco de dados
- Acesso de escrita quando apenas leitura Ã© necessÃ¡ria

PermissÃµes amplas aumentam o impacto de falhas.

---

## 25. Ambientes

Separar ambientes reduz riscos.

Ambientes comuns:

- Desenvolvimento
- Teste
- HomologaÃ§Ã£o
- ProduÃ§Ã£o

Boas prÃ¡ticas:

- Usar credenciais diferentes por ambiente
- NÃ£o usar dados reais em desenvolvimento sem necessidade
- NÃ£o compartilhar segredos entre ambientes
- Evitar apontar ambiente local para produÃ§Ã£o
- Identificar claramente cada ambiente
- Restringir acesso ao ambiente de produÃ§Ã£o

---

## 26. Dados de produÃ§Ã£o

Dados de produÃ§Ã£o devem ser tratados com extremo cuidado.

Evite copiar dados reais para ambientes locais.

Se for necessÃ¡rio usar dados reais:

- Obter autorizaÃ§Ã£o adequada
- Remover ou mascarar dados sensÃ­veis
- Limitar acesso
- Armazenar com seguranÃ§a
- Apagar apÃ³s o uso
- Registrar a finalidade

Preferir dados fictÃ­cios ou anonimizados.

---

## 27. Backups

Quando houver banco de dados ou arquivos importantes, backups devem ser considerados.

Boas prÃ¡ticas:

- Realizar backups regulares
- Proteger backups com controle de acesso
- Testar restauraÃ§Ã£o periodicamente
- Evitar armazenar backups em locais pÃºblicos
- Criptografar backups quando necessÃ¡rio
- Definir polÃ­tica de retenÃ§Ã£o

Backup que nÃ£o pode ser restaurado nÃ£o Ã© confiÃ¡vel.

---

## 28. OperaÃ§Ãµes destrutivas

OperaÃ§Ãµes destrutivas devem ter proteÃ§Ã£o extra.

Exemplos:

- Excluir conta
- Excluir dados
- Remover arquivos
- Alterar permissÃµes
- Resetar banco
- Cancelar assinatura
- Revogar acesso
- Apagar registros em lote

Boas prÃ¡ticas:

- Confirmar intenÃ§Ã£o do usuÃ¡rio
- Validar autorizaÃ§Ã£o
- Registrar auditoria quando aplicÃ¡vel
- Permitir reversÃ£o quando possÃ­vel
- Evitar exclusÃ£o fÃ­sica quando soft delete for mais adequado
- Testar cuidadosamente

---

## 29. Auditoria

Para aÃ§Ãµes sensÃ­veis, considere manter registros de auditoria.

Eventos que podem exigir auditoria:

- Login administrativo
- AlteraÃ§Ã£o de permissÃµes
- ExclusÃ£o de dados
- AlteraÃ§Ã£o de configuraÃ§Ãµes
- ExportaÃ§Ã£o de dados
- Acesso a informaÃ§Ãµes sensÃ­veis
- MudanÃ§as financeiras
- Falhas repetidas de autenticaÃ§Ã£o

Registros de auditoria devem evitar dados sensÃ­veis desnecessÃ¡rios.

---

## 30. Uso de IA com seguranÃ§a

Ferramentas de IA podem apoiar o desenvolvimento, mas nÃ£o devem receber informaÃ§Ãµes sensÃ­veis.

Nunca envie para ferramentas de IA:

- Senhas
- Tokens
- Chaves privadas
- Credenciais
- Arquivos `.env` reais
- Dados reais de usuÃ¡rios
- Logs com segredos
- InformaÃ§Ãµes financeiras privadas
- Dados pessoais sensÃ­veis

Ao usar IA para seguranÃ§a:

- Revise todas as sugestÃµes
- NÃ£o aplique cÃ³digo sem entender
- Teste a soluÃ§Ã£o
- Verifique se nÃ£o houve mudanÃ§a fora do escopo
- NÃ£o confie cegamente em anÃ¡lises automÃ¡ticas

Consulte tambÃ©m:

```txt
AI_GUIDELINES.md
```

---

## 31. Reportando vulnerabilidades

Se encontrar uma vulnerabilidade, nÃ£o abra uma issue pÃºblica com detalhes explorÃ¡veis.

Em vez disso, reporte de forma privada para a pessoa ou equipe responsÃ¡vel pelo projeto.

Inclua, quando possÃ­vel:

- DescriÃ§Ã£o do problema
- Impacto potencial
- Passos para reproduzir
- Arquivos ou rotas afetadas
- EvidÃªncias
- SugestÃ£o de correÃ§Ã£o, se houver

Evite divulgar publicamente detalhes antes da correÃ§Ã£o.

---

## 32. Como descrever uma vulnerabilidade

Use este modelo ao reportar um problema de seguranÃ§a:

```md
## Resumo

Descreva brevemente a vulnerabilidade.

## Impacto

Explique o que pode acontecer se o problema for explorado.

## Passos para reproduzir

1. Passo 1
2. Passo 2
3. Passo 3

## Resultado observado

Descreva o comportamento atual.

## Resultado esperado

Descreva o comportamento seguro esperado.

## EvidÃªncias

Inclua prints, logs mascarados ou exemplos sem dados sensÃ­veis.

## SugestÃ£o de correÃ§Ã£o

Descreva uma possÃ­vel correÃ§Ã£o, se souber.

## Severidade sugerida

Baixa, mÃ©dia, alta ou crÃ­tica.
```

---

## 33. ClassificaÃ§Ã£o de severidade

A severidade deve considerar impacto e facilidade de exploraÃ§Ã£o.

### Baixa

Problemas com impacto limitado ou difÃ­cil exploraÃ§Ã£o.

Exemplos:

- Mensagem pouco clara
- Pequena exposiÃ§Ã£o de informaÃ§Ã£o nÃ£o sensÃ­vel
- ConfiguraÃ§Ã£o melhorÃ¡vel sem impacto direto

### MÃ©dia

Problemas que podem afetar usuÃ¡rios ou dados em condiÃ§Ãµes especÃ­ficas.

Exemplos:

- ValidaÃ§Ã£o incompleta
- Falha de autorizaÃ§Ã£o em caso limitado
- ExposiÃ§Ã£o parcial de dados internos
- Rate limiting ausente em rota sensÃ­vel

### Alta

Problemas com impacto significativo.

Exemplos:

- Acesso indevido a dados de outros usuÃ¡rios
- Bypass de autorizaÃ§Ã£o
- ExposiÃ§Ã£o de dados sensÃ­veis
- Upload inseguro com risco relevante
- ExecuÃ§Ã£o de aÃ§Ã£o administrativa indevida

### CrÃ­tica

Problemas com impacto grave ou exploraÃ§Ã£o ampla.

Exemplos:

- ExecuÃ§Ã£o remota de cÃ³digo
- Vazamento de credenciais de produÃ§Ã£o
- Acesso administrativo nÃ£o autorizado
- Comprometimento total do sistema
- ExposiÃ§Ã£o massiva de dados sensÃ­veis

---

## 34. PriorizaÃ§Ã£o de correÃ§Ãµes

Problemas de seguranÃ§a devem ser priorizados conforme severidade.

ReferÃªncia geral:

- CrÃ­tica: corrigir imediatamente
- Alta: corrigir com prioridade mÃ¡xima
- MÃ©dia: planejar correÃ§Ã£o em curto prazo
- Baixa: corrigir conforme planejamento do projeto

A prioridade final deve considerar contexto, exposiÃ§Ã£o e impacto real.

---

## 35. Processo de correÃ§Ã£o

Ao corrigir uma vulnerabilidade:

- Reproduza o problema de forma segura
- Entenda a causa raiz
- Implemente a menor correÃ§Ã£o segura possÃ­vel
- Adicione teste quando aplicÃ¡vel
- Revise impactos laterais
- Evite expor detalhes sensÃ­veis no Pull Request
- Atualize documentaÃ§Ã£o se necessÃ¡rio
- Valide a correÃ§Ã£o antes do merge

CorreÃ§Ãµes de seguranÃ§a devem ser claras e revisÃ¡veis.

---

## 36. ComunicaÃ§Ã£o

A comunicaÃ§Ã£o sobre vulnerabilidades deve ser cuidadosa.

Evite divulgar:

- CÃ³digo de exploraÃ§Ã£o
- Credenciais
- Dados reais
- Detalhes sensÃ­veis antes da correÃ§Ã£o
- Caminhos internos desnecessÃ¡rios
- InformaÃ§Ãµes que facilitem abuso

ApÃ³s a correÃ§Ã£o, pode ser adequado documentar o problema em termos gerais.

---

## 37. Checklist de seguranÃ§a para Pull Requests

Antes de abrir ou aprovar um Pull Request, verifique:

- [ ] NÃ£o hÃ¡ segredos ou credenciais no cÃ³digo
- [ ] Entradas externas sÃ£o validadas
- [ ] PermissÃµes sÃ£o verificadas no backend ou serviÃ§o responsÃ¡vel
- [ ] Dados sensÃ­veis nÃ£o sÃ£o retornados por APIs
- [ ] Logs nÃ£o expÃµem informaÃ§Ãµes privadas
- [ ] Mensagens de erro nÃ£o revelam detalhes internos
- [ ] DependÃªncias adicionadas sÃ£o necessÃ¡rias
- [ ] Uploads sÃ£o restritos, se aplicÃ¡vel
- [ ] AlteraÃ§Ãµes de autenticaÃ§Ã£o foram revisadas com cuidado
- [ ] AlteraÃ§Ãµes de autorizaÃ§Ã£o foram revisadas com cuidado
- [ ] VariÃ¡veis de ambiente foram documentadas sem valores reais
- [ ] A mudanÃ§a foi testada ou validada

---

## 38. Checklist para novas funcionalidades

Ao criar uma nova funcionalidade, avalie:

- [ ] Quem pode acessar?
- [ ] Quem pode alterar?
- [ ] Quais dados sÃ£o manipulados?
- [ ] Existe dado sensÃ­vel?
- [ ] A entrada do usuÃ¡rio Ã© validada?
- [ ] Existe risco de abuso?
- [ ] Existe risco de vazamento?
- [ ] A funcionalidade precisa de logs?
- [ ] A funcionalidade precisa de auditoria?
- [ ] Existe impacto em permissÃµes?
- [ ] Existe impacto em integraÃ§Ãµes?
- [ ] Existe impacto em dados existentes?

---

## 39. Checklist para produÃ§Ã£o

Antes de publicar em produÃ§Ã£o, verifique:

- [ ] VariÃ¡veis de ambiente estÃ£o corretas
- [ ] NÃ£o hÃ¡ modo debug ativo
- [ ] Erros detalhados nÃ£o sÃ£o exibidos ao usuÃ¡rio
- [ ] Segredos estÃ£o fora do cÃ³digo
- [ ] CORS estÃ¡ restrito
- [ ] HTTPS estÃ¡ configurado quando aplicÃ¡vel
- [ ] Logs nÃ£o expÃµem dados sensÃ­veis
- [ ] DependÃªncias crÃ­ticas estÃ£o atualizadas
- [ ] PermissÃµes estÃ£o adequadas
- [ ] Backups foram considerados quando necessÃ¡rio
- [ ] Rotas sensÃ­veis foram revisadas
- [ ] AutenticaÃ§Ã£o e autorizaÃ§Ã£o foram validadas

---

## 40. O que fazer em caso de vazamento

Se um segredo for exposto, nÃ£o basta remover o arquivo do repositÃ³rio.

AÃ§Ãµes recomendadas:

- Revogar imediatamente o segredo exposto
- Gerar novo segredo
- Atualizar ambientes afetados
- Verificar logs e acessos suspeitos
- Remover o segredo do histÃ³rico quando necessÃ¡rio
- Investigar impacto
- Documentar a ocorrÃªncia internamente
- Revisar processo para evitar repetiÃ§Ã£o

Segredos expostos devem ser considerados comprometidos.

---

## 41. Exemplos de problemas comuns

Problemas comuns que devem ser evitados:

- API retornando `password_hash`
- Rota administrativa protegida apenas no frontend
- Upload aceitando qualquer arquivo
- Query SQL montada por concatenaÃ§Ã£o
- Token salvo em log
- Arquivo `.env` enviado ao repositÃ³rio
- Erro exibindo stack trace em produÃ§Ã£o
- PermissÃ£o de usuÃ¡rio validada apenas pela interface
- CORS liberado para qualquer origem sem necessidade
- DependÃªncia adicionada sem revisÃ£o

---

## 42. Ferramentas de apoio

Ferramentas podem ajudar, mas nÃ£o substituem revisÃ£o humana.

Podem ser usadas ferramentas para:

- AnÃ¡lise de dependÃªncias
- Lint
- Testes automatizados
- AnÃ¡lise estÃ¡tica
- DetecÃ§Ã£o de segredos
- Varredura de vulnerabilidades
- Monitoramento de logs
- Auditoria de permissÃµes

Resultados automÃ¡ticos devem ser avaliados com contexto.

---

## 43. SeguranÃ§a por padrÃ£o

Sempre que houver dÃºvida, prefira o comportamento mais seguro.

Exemplos:

- Negar acesso por padrÃ£o
- Exigir autenticaÃ§Ã£o em rotas privadas
- Retornar apenas campos necessÃ¡rios
- Validar dados antes de processar
- Usar permissÃµes mÃ­nimas
- NÃ£o expor detalhes internos
- NÃ£o aceitar arquivos perigosos
- NÃ£o confiar em dados do cliente

SeguranÃ§a por padrÃ£o reduz riscos acidentais.

---

## 44. RevisÃ£o periÃ³dica

Este documento deve ser revisado periodicamente.

Revisar especialmente quando houver:

- MudanÃ§a de arquitetura
- Nova integraÃ§Ã£o externa
- Nova forma de autenticaÃ§Ã£o
- Nova Ã¡rea administrativa
- MudanÃ§a em permissÃµes
- Incidente de seguranÃ§a
- AdiÃ§Ã£o de dependÃªncias importantes
- AlteraÃ§Ã£o no processo de deploy

A polÃ­tica de seguranÃ§a deve acompanhar a evoluÃ§Ã£o do projeto.

---

## 45. PrincÃ­pio final

SeguranÃ§a nÃ£o Ã© apenas evitar ataques.

SeguranÃ§a significa proteger usuÃ¡rios, dados, infraestrutura e confianÃ§a no projeto.

Toda contribuiÃ§Ã£o deve buscar manter o projeto:

- Seguro
- Simples
- RevisÃ¡vel
- AuditÃ¡vel
- ConfiÃ¡vel
- SustentÃ¡vel

Se uma mudanÃ§a aumenta risco sem necessidade, ela deve ser revista antes de ser aceita.


---
# FILE: CONTRIBUTING.md
---

# Guia de ContribuiÃ§Ã£o

Este documento define as regras e boas prÃ¡ticas para contribuir com este projeto.

O objetivo Ã© manter o desenvolvimento organizado, previsÃ­vel e fÃ¡cil de revisar, garantindo qualidade, clareza e consistÃªncia nas entregas.

---

## 1. PrincÃ­pios gerais

Toda contribuiÃ§Ã£o deve seguir estes princÃ­pios:

- Ser clara
- Ser pequena quando possÃ­vel
- Ter objetivo definido
- Ser fÃ¡cil de revisar
- Evitar complexidade desnecessÃ¡ria
- Preservar comportamento existente quando nÃ£o houver intenÃ§Ã£o de mudanÃ§a
- Incluir testes ou validaÃ§Ã£o quando necessÃ¡rio
- Manter documentaÃ§Ã£o atualizada quando houver impacto

ContribuiÃ§Ãµes grandes devem ser divididas em partes menores sempre que possÃ­vel.

---

## 2. Antes de comeÃ§ar

Antes de iniciar uma alteraÃ§Ã£o, verifique:

- Qual problema serÃ¡ resolvido
- Se jÃ¡ existe uma issue, tarefa ou discussÃ£o relacionada
- Se a mudanÃ§a estÃ¡ dentro do escopo do projeto
- Quais arquivos podem ser impactados
- Se hÃ¡ regras de negÃ³cio envolvidas
- Se serÃ¡ necessÃ¡rio atualizar documentaÃ§Ã£o
- Se serÃ¡ necessÃ¡rio criar ou ajustar testes

Evite comeÃ§ar mudanÃ§as grandes sem entendimento claro do objetivo.

---

## 3. Criando uma branch

Crie uma branch especÃ­fica para cada alteraÃ§Ã£o.

Use nomes claros e objetivos.

Exemplos:

```txt
feature/adicionar-login
feature/criar-dashboard
fix/corrigir-validacao-email
fix/ajustar-permissao-usuario
docs/atualizar-readme
refactor/simplificar-servico-pedidos
test/adicionar-testes-auth
chore/atualizar-dependencias
```

Evite nomes genÃ©ricos como:

```txt
ajustes
teste
nova-branch
alteracoes
fix
update
```

---

## 4. Tipos de mudanÃ§a

Use uma classificaÃ§Ã£o clara para identificar o tipo da contribuiÃ§Ã£o:

- `feature`: nova funcionalidade
- `fix`: correÃ§Ã£o de bug
- `docs`: alteraÃ§Ã£o de documentaÃ§Ã£o
- `refactor`: melhoria interna sem alterar comportamento
- `test`: criaÃ§Ã£o ou ajuste de testes
- `chore`: tarefas de manutenÃ§Ã£o
- `style`: ajustes de formataÃ§Ã£o sem impacto funcional
- `build`: mudanÃ§as relacionadas a build ou dependÃªncias
- `ci`: alteraÃ§Ãµes em integraÃ§Ã£o contÃ­nua

Essa organizaÃ§Ã£o ajuda a entender rapidamente o objetivo da mudanÃ§a.

---

## 5. Commits

FaÃ§a commits pequenos, com mensagens claras.

Uma boa mensagem de commit deve explicar o que mudou.

Exemplos bons:

```txt
fix: corrige validaÃ§Ã£o de email vazio
docs: adiciona guia de contribuiÃ§Ã£o
feature: adiciona filtro por status no painel
refactor: simplifica cÃ¡lculo de total do pedido
test: adiciona testes para autenticaÃ§Ã£o
chore: atualiza dependÃªncias de desenvolvimento
```

Evite mensagens vagas:

```txt
ajustes
update
fix
correÃ§Ãµes
mudanÃ§as
wip
final
```

---

## 6. PadrÃ£o recomendado de commit

Sempre que possÃ­vel, use o formato:

```txt
tipo: descriÃ§Ã£o curta da mudanÃ§a
```

Exemplos:

```txt
feature: adiciona cadastro de usuÃ¡rios
fix: corrige erro ao salvar formulÃ¡rio
docs: atualiza instruÃ§Ãµes de instalaÃ§Ã£o
refactor: remove duplicaÃ§Ã£o no serviÃ§o de pagamentos
test: cobre cenÃ¡rio de senha invÃ¡lida
chore: remove arquivos nÃ£o utilizados
```

A descriÃ§Ã£o deve ser curta, objetiva e escrita no presente.

---

## 7. Pull Requests

Toda alteraÃ§Ã£o relevante deve ser enviada por Pull Request.

O Pull Request deve conter:

- Resumo do que foi alterado
- Motivo da alteraÃ§Ã£o
- Como foi testado
- Prints ou evidÃªncias, quando aplicÃ¡vel
- Riscos conhecidos
- PendÃªncias, se houver

Evite abrir Pull Requests muito grandes sem necessidade.

Pull Requests menores sÃ£o mais fÃ¡ceis de revisar e aprovar.

---

## 8. Modelo de Pull Request

Use este modelo como referÃªncia:

```md
## Resumo

Descreva de forma objetiva o que foi alterado.

## MotivaÃ§Ã£o

Explique por que essa mudanÃ§a Ã© necessÃ¡ria.

## AlteraÃ§Ãµes realizadas

- AlteraÃ§Ã£o 1
- AlteraÃ§Ã£o 2
- AlteraÃ§Ã£o 3

## Como testar

Descreva os passos para validar a mudanÃ§a.

1. Passo 1
2. Passo 2
3. Resultado esperado

## EvidÃªncias

Adicione prints, logs ou exemplos, se necessÃ¡rio.

## Riscos

Liste possÃ­veis impactos ou riscos conhecidos.

## Checklist

- [ ] A mudanÃ§a estÃ¡ dentro do escopo
- [ ] O cÃ³digo foi revisado
- [ ] Testes foram criados ou ajustados, se necessÃ¡rio
- [ ] A documentaÃ§Ã£o foi atualizada, se necessÃ¡rio
- [ ] NÃ£o hÃ¡ dados sensÃ­veis expostos
- [ ] A alteraÃ§Ã£o foi validada localmente
```

---

## 9. RevisÃ£o de cÃ³digo

Toda revisÃ£o deve buscar melhorar a qualidade do projeto, nÃ£o apenas encontrar erros.

Ao revisar, observe:

- Clareza da soluÃ§Ã£o
- Simplicidade
- PossÃ­veis bugs
- Impacto em funcionalidades existentes
- SeguranÃ§a
- Performance, quando relevante
- Legibilidade
- Testes
- DocumentaÃ§Ã£o
- ConsistÃªncia com padrÃµes do projeto

ComentÃ¡rios de revisÃ£o devem ser objetivos, respeitosos e Ãºteis.

---

## 10. Boas prÃ¡ticas na revisÃ£o

Ao comentar em um Pull Request:

- Explique o motivo da sugestÃ£o
- Diferencie problema real de preferÃªncia pessoal
- Sugira alternativas quando possÃ­vel
- Evite comentÃ¡rios vagos
- Seja direto, mas respeitoso
- Foque no cÃ³digo, nÃ£o na pessoa

Exemplo ruim:

```txt
Isso estÃ¡ errado.
```

Exemplo melhor:

```txt
Esse trecho pode falhar quando o valor vier nulo. Podemos adicionar uma validaÃ§Ã£o antes de acessar a propriedade?
```

---

## 11. Tamanho das mudanÃ§as

Prefira mudanÃ§as pequenas e focadas.

Uma contribuiÃ§Ã£o ideal deve resolver um problema especÃ­fico.

Evite misturar no mesmo Pull Request:

- Nova funcionalidade
- RefatoraÃ§Ã£o grande
- CorreÃ§Ã£o de bug nÃ£o relacionada
- AtualizaÃ§Ã£o de dependÃªncias
- MudanÃ§a visual
- AlteraÃ§Ã£o de documentaÃ§Ã£o sem relaÃ§Ã£o

Se precisar fazer vÃ¡rias coisas, divida em Pull Requests separados.

---

## 12. CÃ³digo

O cÃ³digo deve ser:

- Claro
- Simples
- LegÃ­vel
- Consistente com o projeto
- FÃ¡cil de testar
- FÃ¡cil de manter

Evite:

- AbstraÃ§Ãµes desnecessÃ¡rias
- FunÃ§Ãµes muito longas
- CÃ³digo duplicado sem justificativa
- Nomes genÃ©ricos
- ComentÃ¡rios que apenas repetem o cÃ³digo
- SoluÃ§Ãµes complexas para problemas simples

---

## 13. Nomes

Use nomes descritivos para variÃ¡veis, funÃ§Ãµes, classes, arquivos e commits.

Prefira nomes que expliquem intenÃ§Ã£o.

Exemplos bons:

```txt
calculateOrderTotal
validateUserPermission
createPaymentSession
isEmailAvailable
userRepository
```

Exemplos ruins:

```txt
data
info
obj
temp
x
handleThing
doStuff
```

Nomes claros reduzem a necessidade de comentÃ¡rios.

---

## 14. ComentÃ¡rios no cÃ³digo

ComentÃ¡rios devem explicar o motivo de algo existir, nÃ£o apenas repetir o que o cÃ³digo faz.

Use comentÃ¡rios quando:

- Houver uma regra de negÃ³cio importante
- Existir uma decisÃ£o tÃ©cnica nÃ£o Ã³bvia
- Um comportamento parecer estranho, mas for intencional
- Houver limitaÃ§Ã£o conhecida
- Uma integraÃ§Ã£o externa exigir cuidado especÃ­fico

Evite comentÃ¡rios como:

```txt
incrementa contador
verifica se usuÃ¡rio existe
retorna resultado
```

Prefira comentÃ¡rios Ãºteis:

```txt
Esta validaÃ§Ã£o Ã© mantida por compatibilidade com usuÃ¡rios antigos que nÃ£o possuem documento cadastrado.
```

---

## 15. Testes

Sempre que uma alteraÃ§Ã£o impactar comportamento, avalie a necessidade de testes.

Crie ou atualize testes quando:

- Corrigir um bug
- Criar uma funcionalidade
- Alterar regra de negÃ³cio
- Modificar validaÃ§Ã£o
- Refatorar lÃ³gica sensÃ­vel
- Corrigir caso de erro
- Alterar permissÃµes ou autenticaÃ§Ã£o

Os testes devem validar comportamento observÃ¡vel, nÃ£o detalhes internos desnecessÃ¡rios.

---

## 16. Tipos de teste

Dependendo do projeto, podem existir diferentes tipos de teste:

- Testes unitÃ¡rios
- Testes de integraÃ§Ã£o
- Testes end-to-end
- Testes manuais
- Testes de regressÃ£o

Use o tipo de teste adequado ao risco da mudanÃ§a.

Nem toda alteraÃ§Ã£o precisa de teste automatizado, mas toda alteraÃ§Ã£o deve ser validada de alguma forma.

---

## 17. ValidaÃ§Ã£o manual

Quando a validaÃ§Ã£o for manual, descreva claramente no Pull Request o que foi testado.

Exemplo:

```txt
ValidaÃ§Ã£o manual realizada:

1. Acessei a tela de login.
2. Informei email invÃ¡lido.
3. Confirmei que a mensagem de erro foi exibida.
4. Informei credenciais vÃ¡lidas.
5. Confirmei que o usuÃ¡rio foi redirecionado para o painel.
```

Isso ajuda a revisÃ£o e reduz dÃºvidas sobre o impacto da mudanÃ§a.

---

## 18. DocumentaÃ§Ã£o

Atualize a documentaÃ§Ã£o sempre que a mudanÃ§a impactar:

- InstalaÃ§Ã£o
- ConfiguraÃ§Ã£o
- VariÃ¡veis de ambiente
- Fluxo de uso
- API
- Regras de negÃ³cio
- Comandos
- Deploy
- Estrutura do projeto
- Processo de contribuiÃ§Ã£o

DocumentaÃ§Ã£o desatualizada pode causar erros e retrabalho.

---

## 19. SeguranÃ§a

Toda contribuiÃ§Ã£o deve considerar seguranÃ§a.

AtenÃ§Ã£o especial para:

- ValidaÃ§Ã£o de entrada
- AutenticaÃ§Ã£o
- AutorizaÃ§Ã£o
- PermissÃµes
- ExposiÃ§Ã£o de dados sensÃ­veis
- Logs com informaÃ§Ãµes privadas
- Upload de arquivos
- DependÃªncias externas
- Consultas ao banco de dados
- ManipulaÃ§Ã£o de tokens
- ConfiguraÃ§Ãµes de ambiente

Nunca inclua no repositÃ³rio:

- Senhas
- Tokens
- Chaves privadas
- Arquivos `.env` reais
- Credenciais de banco de dados
- Certificados privados
- Dados reais de usuÃ¡rios
- Segredos de produÃ§Ã£o

---

## 20. DependÃªncias

NÃ£o adicione dependÃªncias sem necessidade.

Antes de instalar uma nova biblioteca, avalie:

- O projeto jÃ¡ possui algo que resolve o problema?
- A dependÃªncia Ã© mantida?
- A licenÃ§a Ã© compatÃ­vel?
- Ela adiciona complexidade?
- Ela aumenta muito o tamanho do projeto?
- Existe risco de seguranÃ§a?
- O benefÃ­cio compensa o custo de manutenÃ§Ã£o?

Toda nova dependÃªncia deve ter uma justificativa clara.

---

## 21. VariÃ¡veis de ambiente

Se uma mudanÃ§a exigir novas variÃ¡veis de ambiente:

- Documente o nome da variÃ¡vel
- Explique sua finalidade
- Informe se Ã© obrigatÃ³ria ou opcional
- Adicione exemplo sem valor real
- Atualize arquivos de exemplo, se existirem

Exemplo:

```env
API_BASE_URL=https://example.com
AUTH_SECRET=example_secret
DATABASE_URL=mysql://user:password@localhost:3306/database
```

Nunca coloque valores reais em arquivos versionados.

---

## 22. MigraÃ§Ãµes e banco de dados

MudanÃ§as em banco de dados devem ser feitas com cuidado.

Antes de alterar estrutura de dados, verifique:

- Impacto em dados existentes
- Compatibilidade com versÃµes anteriores
- Necessidade de migraÃ§Ã£o
- Possibilidade de rollback
- Impacto em performance
- Campos obrigatÃ³rios
- Valores padrÃ£o
- Ãndices necessÃ¡rios

Evite mudanÃ§as destrutivas sem planejamento.

---

## 23. AlteraÃ§Ãµes de API

Ao alterar APIs, considere:

- Compatibilidade com clientes existentes
- Contratos de entrada e saÃ­da
- Mensagens de erro
- Status codes
- AutenticaÃ§Ã£o
- AutorizaÃ§Ã£o
- Versionamento, se aplicÃ¡vel
- DocumentaÃ§Ã£o

Evite quebrar contratos existentes sem aviso ou justificativa.

---

## 24. Interface e experiÃªncia do usuÃ¡rio

Em mudanÃ§as visuais ou de experiÃªncia, considere:

- Clareza para o usuÃ¡rio
- ConsistÃªncia visual
- Acessibilidade
- Responsividade
- Estados de carregamento
- Estados de erro
- Mensagens vazias
- Feedback de sucesso
- Comportamento em dispositivos mÃ³veis

Sempre que possÃ­vel, inclua evidÃªncias visuais no Pull Request.

---

## 25. Acessibilidade

Ao alterar interfaces, considere acessibilidade.

Verifique:

- Textos alternativos em imagens relevantes
- Contraste adequado
- NavegaÃ§Ã£o por teclado
- Labels em campos de formulÃ¡rio
- Estados de foco visÃ­veis
- Mensagens de erro compreensÃ­veis
- Uso correto de elementos semÃ¢nticos

Acessibilidade deve ser tratada como parte da qualidade do produto.

---

## 26. Performance

Avalie performance quando a mudanÃ§a envolver:

- Consultas ao banco de dados
- Listagens grandes
- Loops pesados
- RenderizaÃ§Ã£o de muitos elementos
- Upload ou download de arquivos
- IntegraÃ§Ãµes externas
- Processamento em lote
- Carregamento inicial da aplicaÃ§Ã£o

Evite otimizaÃ§Ãµes prematuras, mas nÃ£o ignore gargalos evidentes.

---

## 27. Logs e monitoramento

Logs devem ajudar a diagnosticar problemas sem expor dados sensÃ­veis.

Bons logs indicam:

- O que aconteceu
- Onde aconteceu
- Identificadores nÃ£o sensÃ­veis
- Contexto suficiente para investigaÃ§Ã£o

Evite logar:

- Senhas
- Tokens
- Dados pessoais sensÃ­veis
- CartÃµes
- Documentos
- Segredos
- Cookies de sessÃ£o

---

## 28. Uso de IA nas contribuiÃ§Ãµes

Ã‰ permitido usar IA como apoio, desde que as regras do projeto sejam respeitadas.

Ao usar IA:

- Revise tudo que foi gerado
- Entenda o cÃ³digo antes de aceitar
- NÃ£o envie segredos para ferramentas de IA
- NÃ£o aceite mudanÃ§as grandes sem revisÃ£o
- Valide o comportamento
- Mantenha o escopo controlado
- Documente decisÃµes importantes quando necessÃ¡rio

Consulte tambÃ©m o arquivo:

```txt
AI_GUIDELINES.md
```

A responsabilidade pela contribuiÃ§Ã£o continua sendo da pessoa que abriu o Pull Request.

---

## 29. Checklist antes de abrir um Pull Request

Antes de abrir um Pull Request, verifique:

- [ ] A mudanÃ§a tem objetivo claro
- [ ] A branch possui nome descritivo
- [ ] Os commits sÃ£o claros
- [ ] O escopo estÃ¡ controlado
- [ ] NÃ£o hÃ¡ arquivos desnecessÃ¡rios
- [ ] NÃ£o hÃ¡ segredos ou dados sensÃ­veis
- [ ] O cÃ³digo foi revisado localmente
- [ ] A alteraÃ§Ã£o foi testada
- [ ] A documentaÃ§Ã£o foi atualizada, se necessÃ¡rio
- [ ] O Pull Request descreve como validar a mudanÃ§a
- [ ] Riscos conhecidos foram informados

---

## 30. Checklist para revisÃ£o

Ao revisar um Pull Request, verifique:

- [ ] A mudanÃ§a resolve o problema proposto
- [ ] O escopo estÃ¡ adequado
- [ ] O cÃ³digo estÃ¡ claro
- [ ] NÃ£o hÃ¡ complexidade desnecessÃ¡ria
- [ ] NÃ£o hÃ¡ impacto inesperado
- [ ] Existem testes ou validaÃ§Ã£o suficiente
- [ ] NÃ£o hÃ¡ dados sensÃ­veis expostos
- [ ] A documentaÃ§Ã£o foi atualizada, se necessÃ¡rio
- [ ] A soluÃ§Ã£o estÃ¡ consistente com o projeto
- [ ] O Pull Request pode ser revertido se necessÃ¡rio

---

## 31. Quando solicitar mudanÃ§as

Solicite mudanÃ§as quando encontrar:

- Bug evidente
- Falha de seguranÃ§a
- CÃ³digo difÃ­cil de entender
- MudanÃ§a fora do escopo
- Falta de validaÃ§Ã£o importante
- Quebra de comportamento existente
- DependÃªncia adicionada sem justificativa
- DocumentaÃ§Ã£o incorreta
- ExposiÃ§Ã£o de dados sensÃ­veis
- AusÃªncia de tratamento de erro relevante

ComentÃ¡rios devem explicar o motivo da solicitaÃ§Ã£o.

---

## 32. Quando aprovar

Aprovar um Pull Request significa que a mudanÃ§a estÃ¡ aceitÃ¡vel para entrar no projeto.

Antes de aprovar, verifique se:

- O objetivo foi atendido
- O risco Ã© aceitÃ¡vel
- O cÃ³digo estÃ¡ compreensÃ­vel
- O escopo estÃ¡ adequado
- A validaÃ§Ã£o foi suficiente
- NÃ£o hÃ¡ pendÃªncias crÃ­ticas
- A documentaÃ§Ã£o estÃ¡ adequada, se aplicÃ¡vel

A aprovaÃ§Ã£o deve ser feita com responsabilidade.

---

## 33. Conflitos

Se houver conflitos com a branch principal, resolva com cuidado.

ApÃ³s resolver conflitos:

- Revise os arquivos afetados
- Execute testes ou validaÃ§Ã£o
- Confirme que nenhuma alteraÃ§Ã£o foi perdida
- Atualize o Pull Request, se necessÃ¡rio

Conflitos mal resolvidos podem introduzir bugs difÃ­ceis de identificar.

---

## 34. OrganizaÃ§Ã£o dos arquivos

Ao criar novos arquivos, siga a organizaÃ§Ã£o existente do projeto.

Antes de criar uma nova pasta ou padrÃ£o, verifique:

- Se jÃ¡ existe local apropriado
- Se o nome estÃ¡ consistente
- Se o arquivo realmente precisa existir
- Se a estrutura facilita manutenÃ§Ã£o
- Se a mudanÃ§a estÃ¡ documentada quando necessÃ¡rio

Evite criar estruturas paralelas sem justificativa.

---

## 35. Compatibilidade

Ao alterar cÃ³digo existente, considere compatibilidade com:

- VersÃµes anteriores
- Dados existentes
- APIs pÃºblicas
- IntegraÃ§Ãµes externas
- ConfiguraÃ§Ãµes atuais
- Ambientes de desenvolvimento
- Ambientes de produÃ§Ã£o

MudanÃ§as incompatÃ­veis devem ser planejadas e comunicadas.

---

## 36. Erros e mensagens

Mensagens de erro devem ser claras e Ãºteis.

Uma boa mensagem deve:

- Explicar o que aconteceu
- Indicar como corrigir, quando possÃ­vel
- NÃ£o expor detalhes sensÃ­veis
- Ser compreensÃ­vel para o pÃºblico correto

Evite mensagens genÃ©ricas como:

```txt
Erro inesperado.
```

Prefira algo mais Ãºtil quando possÃ­vel:

```txt
NÃ£o foi possÃ­vel salvar o cadastro porque o email informado jÃ¡ estÃ¡ em uso.
```

---

## 37. Responsabilidade

Quem contribui Ã© responsÃ¡vel por entender e validar a prÃ³pria mudanÃ§a.

Isso inclui:

- CÃ³digo submetido
- Testes criados
- DocumentaÃ§Ã£o alterada
- DependÃªncias adicionadas
- ConfiguraÃ§Ãµes modificadas
- DecisÃµes tÃ©cnicas tomadas

O objetivo Ã© manter o projeto confiÃ¡vel e sustentÃ¡vel.

---

## 38. Conduta

Todas as interaÃ§Ãµes no projeto devem ser respeitosas.

Espera-se que as pessoas contribuam com:

- Respeito
- Clareza
- ColaboraÃ§Ã£o
- Boa-fÃ©
- Abertura para feedback
- Foco na soluÃ§Ã£o

NÃ£o serÃ£o aceitos comportamentos ofensivos, discriminatÃ³rios, abusivos ou desrespeitosos.

---

## 39. DÃºvidas

Em caso de dÃºvida:

- Pergunte antes de alterar
- Abra uma discussÃ£o
- Explique o contexto
- Mostre alternativas
- PeÃ§a revisÃ£o antecipada
- Divida a mudanÃ§a em partes menores

Ã‰ melhor esclarecer antes do que corrigir depois.

---

## 40. PrincÃ­pio final

Contribuir bem nÃ£o Ã© apenas fazer o cÃ³digo funcionar.

Uma boa contribuiÃ§Ã£o deve ser:

- Correta
- Clara
- Segura
- RevisÃ¡vel
- TestÃ¡vel
- SustentÃ¡vel
- Alinhada ao objetivo do projeto

O projeto deve ficar melhor depois de cada contribuiÃ§Ã£o.




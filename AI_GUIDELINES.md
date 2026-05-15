# Diretrizes de Uso de IA

Este documento define como a IA deve ser utilizada neste projeto.

O objetivo é usar IA como ferramenta de apoio para acelerar trabalho, melhorar clareza e reduzir esforço repetitivo, sem abrir mão de revisão humana, responsabilidade técnica e controle sobre as decisões do projeto.

---

## 1. IA é ferramenta, não piloto automático

A IA deve apoiar o desenvolvimento, mas não deve conduzir o projeto sem direção humana.

Ela pode ajudar em:

- Análise de problemas
- Organização de ideias
- Escrita de documentação
- Geração inicial de código
- Refatoração assistida
- Criação de testes
- Revisão de código
- Explicação de trechos complexos
- Sugestão de melhorias

Mas a decisão final deve ser sempre humana.

A IA não deve substituir:

- Entendimento do problema
- Julgamento técnico
- Validação de segurança
- Revisão de impacto
- Testes
- Responsabilidade pelo código entregue

---

## 2. Trabalhar com contexto claro

Antes de pedir algo para a IA, forneça contexto suficiente.

Sempre que possível, inclua:

- Objetivo da tarefa
- Arquivos relevantes
- Regras de negócio
- Restrições técnicas
- Padrões já usados no projeto
- O que deve ser alterado
- O que não deve ser alterado
- Critérios de aceitação

Pedidos vagos tendem a gerar respostas genéricas, incompletas ou incompatíveis com o projeto.

---

## 3. Preferir tarefas pequenas

A IA deve ser usada preferencialmente em tarefas pequenas e bem definidas.

Evitar pedir para a IA fazer mudanças muito amplas de uma só vez.

Preferir pedidos como:

- “Crie testes para esta função”
- “Refatore este trecho sem mudar comportamento”
- “Explique este erro”
- “Sugira uma estrutura para este documento”
- “Implemente apenas esta validação”
- “Revise este arquivo procurando inconsistências”

Evitar pedidos como:

- “Reescreva todo o projeto”
- “Melhore tudo”
- “Refatore toda a arquitetura”
- “Implemente esse módulo inteiro sem especificação”
- “Faça do jeito que achar melhor”

Mudanças pequenas são mais fáceis de revisar, testar e reverter.

---

## 4. Especificar antes de implementar

Antes de pedir código para a IA, defina minimamente:

- O problema a ser resolvido
- O comportamento esperado
- Entradas e saídas
- Casos principais
- Casos de erro
- Limitações conhecidas
- Critérios de aceitação

A IA funciona melhor quando existe uma especificação clara.

A especificação não precisa ser longa, mas precisa reduzir ambiguidade.

---

## 5. Revisar tudo que a IA gerar

Todo conteúdo gerado por IA deve ser revisado antes de ser aceito.

Isso inclui:

- Código
- Testes
- Documentação
- Configurações
- Scripts
- Migrações
- Mensagens de erro
- Regras de negócio

Nunca assumir que uma resposta da IA está correta apenas porque parece bem escrita.

Verificar especialmente:

- Se resolve o problema certo
- Se não remove comportamento necessário
- Se não adiciona complexidade desnecessária
- Se segue os padrões do projeto
- Se não introduz falhas de segurança
- Se não quebra fluxos existentes
- Se é compreensível para outra pessoa

---

## 6. Não aceitar código sem entender

Não deve ser aceito código que ninguém entende.

Antes de incorporar uma solução sugerida por IA, a pessoa responsável deve conseguir explicar:

- O que o código faz
- Por que ele foi escrito dessa forma
- Quais alternativas existiam
- Quais riscos existem
- Como testar o comportamento
- Como reverter se necessário

Se a solução parece “mágica”, complexa demais ou difícil de explicar, ela deve ser simplificada ou recusada.

---

## 7. Manter simplicidade

A IA pode sugerir soluções mais complexas do que o necessário.

Sempre avaliar se a resposta segue a filosofia do projeto:

- Resolve o problema atual?
- É simples o suficiente?
- Adiciona abstrações desnecessárias?
- Cria dependências sem necessidade?
- Introduz padrões complexos cedo demais?
- Aumenta o custo de manutenção?

Preferir soluções diretas, claras e fáceis de manter.

---

## 8. Evitar mudanças fora do escopo

Ao usar IA, deve-se tomar cuidado com alterações não solicitadas.

A IA não deve:

- Reformatar arquivos inteiros sem necessidade
- Alterar nomes públicos sem justificativa
- Remover código funcional sem explicação
- Trocar bibliotecas sem aprovação
- Mudar arquitetura sem discussão
- Criar abstrações fora do escopo
- Alterar comportamento existente sem avisar

Toda mudança deve ter motivo claro.

---

## 9. Segurança em primeiro lugar

A IA pode sugerir código inseguro ou incompleto.

Revisar com atenção pontos relacionados a:

- Autenticação
- Autorização
- Dados sensíveis
- Validação de entrada
- Controle de acesso
- Injeção de código
- SQL Injection
- XSS
- CSRF
- Exposição de variáveis de ambiente
- Logs com informações sensíveis
- Manipulação de arquivos
- Dependências externas

Nunca inserir em prompts:

- Senhas
- Tokens
- Chaves privadas
- Credenciais
- Dados pessoais sensíveis
- Segredos de produção

Se for necessário discutir um exemplo, usar valores fictícios.

---

## 10. Não compartilhar segredos com IA

Informações sensíveis não devem ser enviadas para ferramentas de IA.

Não enviar:

- Arquivos `.env` reais
- Tokens de API
- Credenciais de banco de dados
- Certificados privados
- Chaves SSH
- Dados de clientes
- Informações financeiras privadas
- Dados pessoais sensíveis
- Logs contendo segredos

Quando necessário, mascarar os dados:

```txt
DATABASE_URL=mysql://user:password@host:3306/db
API_KEY=example_api_key
TOKEN=example_token
```

---

## 11. Usar IA para documentação

A IA pode ser usada para melhorar a documentação do projeto.

Usos recomendados:

- Criar rascunhos de documentos
- Melhorar clareza de textos
- Padronizar linguagem
- Resumir decisões técnicas
- Criar checklists
- Explicar fluxos
- Gerar exemplos de uso
- Revisar inconsistências

Mesmo assim, a documentação deve ser revisada para garantir que corresponde ao funcionamento real do projeto.

Documentação errada pode causar mais problemas do que ausência de documentação.

---

## 12. Usar IA para testes

A IA pode ajudar a criar testes, mas os testes precisam ser avaliados criticamente.

Ao pedir testes para a IA, informar:

- O comportamento esperado
- Casos principais
- Casos de erro
- Limites importantes
- Dependências externas
- Dados de exemplo

Verificar se os testes:

- Testam comportamento, não implementação interna desnecessária
- Cobrem cenários relevantes
- Não são frágeis demais
- Não apenas repetem a lógica do código
- São fáceis de entender
- Falham quando o comportamento está incorreto

Testes gerados por IA não devem ser aceitos sem execução e revisão.

---

## 13. Usar IA para refatoração

A IA pode apoiar refatorações, desde que o escopo seja controlado.

Antes de refatorar com IA:

- Definir o objetivo da refatoração
- Garantir que existem testes ou validação mínima
- Pedir para preservar comportamento
- Evitar mudanças funcionais misturadas
- Revisar o diff cuidadosamente

Refatoração deve melhorar:

- Clareza
- Organização
- Remoção de duplicação
- Legibilidade
- Manutenção

Não deve adicionar complexidade apenas por estética técnica.

---

## 14. Usar IA para revisão de código

A IA pode ser usada como uma camada adicional de revisão.

Ela pode ajudar a identificar:

- Bugs prováveis
- Trechos confusos
- Duplicações
- Falhas de validação
- Problemas de segurança
- Casos de erro ausentes
- Nomes pouco claros
- Complexidade desnecessária

Mas a revisão da IA não substitui revisão humana.

A IA pode não entender corretamente o contexto do projeto ou pode apontar problemas irrelevantes.

---

## 15. Solicitar explicações quando necessário

Sempre que a IA sugerir uma solução, é válido pedir explicação.

Perguntas úteis:

- Por que essa abordagem?
- Existe uma solução mais simples?
- Quais são os riscos?
- Que comportamento pode quebrar?
- Como testar isso?
- Quais alternativas existem?
- Essa abstração é realmente necessária?
- O que foi alterado exatamente?

A IA deve ajudar a aumentar entendimento, não apenas gerar código.

---

## 16. Validar comandos antes de executar

Comandos sugeridos por IA devem ser lidos antes de serem executados.

Ter atenção especial com comandos que:

- Apagam arquivos
- Alteram permissões
- Instalam pacotes
- Modificam banco de dados
- Fazem deploy
- Alteram configuração global
- Usam `sudo`
- Usam `rm -rf`
- Executam scripts externos
- Baixam conteúdo da internet

Nunca executar comandos destrutivos sem entender completamente o impacto.

---

## 17. Dependências sugeridas por IA

A IA pode sugerir bibliotecas ou ferramentas externas, mas nenhuma dependência deve ser adicionada automaticamente.

Antes de adicionar uma dependência, avaliar:

- É realmente necessária?
- O projeto já possui algo equivalente?
- A biblioteca é mantida?
- Tem boa documentação?
- Tem licença compatível?
- Aumenta muito o tamanho ou complexidade?
- Pode criar risco de segurança?
- O benefício compensa o custo de manutenção?

Preferir usar recursos já existentes no projeto quando possível.

---

## 18. Commits e pull requests com apoio de IA

A IA pode ajudar a escrever mensagens de commit e descrições de pull request.

Uma boa descrição deve incluir:

- O que foi alterado
- Por que foi alterado
- Como foi testado
- Riscos conhecidos
- Pendências, se houver

Evitar descrições genéricas como:

- “ajustes”
- “melhorias”
- “correções”
- “update”
- “fix”

Mudanças feitas com apoio de IA continuam sendo responsabilidade da pessoa que as submeteu.

---

## 19. Prompts recomendados

Exemplos de prompts úteis para este projeto:

```txt
Analise este código e aponte problemas de clareza, duplicação e manutenção. Não altere comportamento.
```

```txt
Refatore este trecho para melhorar legibilidade, mantendo exatamente o mesmo comportamento.
```

```txt
Crie testes para os seguintes cenários. Priorize comportamento observável e casos de erro.
```

```txt
Explique este erro e sugira uma correção simples, sem mudar a arquitetura.
```

```txt
Revise esta documentação e melhore clareza, objetividade e consistência.
```

```txt
Sugira uma implementação mínima para este requisito, evitando abstrações prematuras.
```

```txt
Compare duas abordagens para este problema considerando simplicidade, manutenção e risco.
```

```txt
Liste possíveis impactos desta mudança antes de implementar.
```

---

## 20. Prompts a evitar

Evitar prompts vagos ou amplos demais:

```txt
Melhore esse código.
```

```txt
Faça tudo.
```

```txt
Refatore o projeto inteiro.
```

```txt
Implemente da melhor forma possível.
```

```txt
Crie uma arquitetura completa.
```

```txt
Corrija todos os problemas.
```

```txt
Adicione qualquer coisa que achar necessário.
```

Esses pedidos aumentam o risco de mudanças fora do escopo, complexidade desnecessária e retrabalho.

---

## 21. Checklist antes de aceitar uma resposta da IA

Antes de aceitar uma sugestão da IA, verificar:

- [ ] A solução resolve o problema pedido?
- [ ] Está dentro do escopo?
- [ ] Eu entendo o que foi gerado?
- [ ] O código segue os padrões do projeto?
- [ ] Não há segredos ou dados sensíveis expostos?
- [ ] Não foram adicionadas dependências desnecessárias?
- [ ] Não houve mudança de comportamento não solicitada?
- [ ] A solução é simples o suficiente?
- [ ] Existem testes ou validação mínima?
- [ ] A documentação foi atualizada, se necessário?
- [ ] O impacto foi revisado?
- [ ] É possível reverter a mudança se der problema?

---

## 22. Checklist para prompts melhores

Antes de pedir ajuda para a IA, verificar:

- [ ] Expliquei o objetivo?
- [ ] Informei o contexto necessário?
- [ ] Defini o escopo?
- [ ] Disse o que não deve ser alterado?
- [ ] Incluí exemplos, se necessário?
- [ ] Informei critérios de aceitação?
- [ ] Pedi uma solução simples?
- [ ] Pedi explicação quando havia risco?
- [ ] Dividi a tarefa em partes pequenas?

---

## 23. Quando não usar IA

Evitar usar IA quando:

- A tarefa envolve segredos ou dados sensíveis
- Não há contexto suficiente para uma boa resposta
- Ninguém poderá revisar o resultado
- O impacto da mudança é alto e pouco compreendido
- A solução exige decisão estratégica sem discussão humana
- A resposta será aplicada automaticamente sem validação
- O problema ainda não foi entendido

Nesses casos, primeiro esclarecer o problema, reduzir o escopo ou discutir com uma pessoa responsável.

---

## 24. Responsabilidade

Todo código, documento ou decisão criada com apoio de IA pertence à responsabilidade da pessoa que aceitou e aplicou a sugestão.

A frase “foi a IA que fez” não justifica:

- Código quebrado
- Falhas de segurança
- Comportamento incorreto
- Documentação falsa
- Mudanças fora do escopo
- Falta de testes
- Falta de revisão

A IA pode ajudar, mas a responsabilidade continua sendo humana.

---

## 25. Princípio central

O princípio central para uso de IA neste projeto é:

> Usar IA para acelerar trabalho claro, pequeno e revisável, mantendo controle humano sobre entendimento, decisão, qualidade e responsabilidade.

A IA deve tornar o projeto mais simples, mais claro e mais sustentável.

Se o uso da IA estiver aumentando confusão, complexidade ou risco, o processo deve ser revisto.

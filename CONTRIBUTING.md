# Guia de Contribuição

Este documento define as regras e boas práticas para contribuir com este projeto.

O objetivo é manter o desenvolvimento organizado, previsível e fácil de revisar, garantindo qualidade, clareza e consistência nas entregas.

---

## 1. Princípios gerais

Toda contribuição deve seguir estes princípios:

- Ser clara
- Ser pequena quando possível
- Ter objetivo definido
- Ser fácil de revisar
- Evitar complexidade desnecessária
- Preservar comportamento existente quando não houver intenção de mudança
- Incluir testes ou validação quando necessário
- Manter documentação atualizada quando houver impacto

Contribuições grandes devem ser divididas em partes menores sempre que possível.

---

## 2. Antes de começar

Antes de iniciar uma alteração, verifique:

- Qual problema será resolvido
- Se já existe uma issue, tarefa ou discussão relacionada
- Se a mudança está dentro do escopo do projeto
- Quais arquivos podem ser impactados
- Se há regras de negócio envolvidas
- Se será necessário atualizar documentação
- Se será necessário criar ou ajustar testes

Evite começar mudanças grandes sem entendimento claro do objetivo.

---

## 3. Criando uma branch

Crie uma branch específica para cada alteração.

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

Evite nomes genéricos como:

```txt
ajustes
teste
nova-branch
alteracoes
fix
update
```

---

## 4. Tipos de mudança

Use uma classificação clara para identificar o tipo da contribuição:

- `feature`: nova funcionalidade
- `fix`: correção de bug
- `docs`: alteração de documentação
- `refactor`: melhoria interna sem alterar comportamento
- `test`: criação ou ajuste de testes
- `chore`: tarefas de manutenção
- `style`: ajustes de formatação sem impacto funcional
- `build`: mudanças relacionadas a build ou dependências
- `ci`: alterações em integração contínua

Essa organização ajuda a entender rapidamente o objetivo da mudança.

---

## 5. Commits

Faça commits pequenos, com mensagens claras.

Uma boa mensagem de commit deve explicar o que mudou.

Exemplos bons:

```txt
fix: corrige validação de email vazio
docs: adiciona guia de contribuição
feature: adiciona filtro por status no painel
refactor: simplifica cálculo de total do pedido
test: adiciona testes para autenticação
chore: atualiza dependências de desenvolvimento
```

Evite mensagens vagas:

```txt
ajustes
update
fix
correções
mudanças
wip
final
```

---

## 6. Padrão recomendado de commit

Sempre que possível, use o formato:

```txt
tipo: descrição curta da mudança
```

Exemplos:

```txt
feature: adiciona cadastro de usuários
fix: corrige erro ao salvar formulário
docs: atualiza instruções de instalação
refactor: remove duplicação no serviço de pagamentos
test: cobre cenário de senha inválida
chore: remove arquivos não utilizados
```

A descrição deve ser curta, objetiva e escrita no presente.

---

## 7. Pull Requests

Toda alteração relevante deve ser enviada por Pull Request.

O Pull Request deve conter:

- Resumo do que foi alterado
- Motivo da alteração
- Como foi testado
- Prints ou evidências, quando aplicável
- Riscos conhecidos
- Pendências, se houver

Evite abrir Pull Requests muito grandes sem necessidade.

Pull Requests menores são mais fáceis de revisar e aprovar.

---

## 8. Modelo de Pull Request

Use este modelo como referência:

```md
## Resumo

Descreva de forma objetiva o que foi alterado.

## Motivação

Explique por que essa mudança é necessária.

## Alterações realizadas

- Alteração 1
- Alteração 2
- Alteração 3

## Como testar

Descreva os passos para validar a mudança.

1. Passo 1
2. Passo 2
3. Resultado esperado

## Evidências

Adicione prints, logs ou exemplos, se necessário.

## Riscos

Liste possíveis impactos ou riscos conhecidos.

## Checklist

- [ ] A mudança está dentro do escopo
- [ ] O código foi revisado
- [ ] Testes foram criados ou ajustados, se necessário
- [ ] A documentação foi atualizada, se necessário
- [ ] Não há dados sensíveis expostos
- [ ] A alteração foi validada localmente
```

---

## 9. Revisão de código

Toda revisão deve buscar melhorar a qualidade do projeto, não apenas encontrar erros.

Ao revisar, observe:

- Clareza da solução
- Simplicidade
- Possíveis bugs
- Impacto em funcionalidades existentes
- Segurança
- Performance, quando relevante
- Legibilidade
- Testes
- Documentação
- Consistência com padrões do projeto

Comentários de revisão devem ser objetivos, respeitosos e úteis.

---

## 10. Boas práticas na revisão

Ao comentar em um Pull Request:

- Explique o motivo da sugestão
- Diferencie problema real de preferência pessoal
- Sugira alternativas quando possível
- Evite comentários vagos
- Seja direto, mas respeitoso
- Foque no código, não na pessoa

Exemplo ruim:

```txt
Isso está errado.
```

Exemplo melhor:

```txt
Esse trecho pode falhar quando o valor vier nulo. Podemos adicionar uma validação antes de acessar a propriedade?
```

---

## 11. Tamanho das mudanças

Prefira mudanças pequenas e focadas.

Uma contribuição ideal deve resolver um problema específico.

Evite misturar no mesmo Pull Request:

- Nova funcionalidade
- Refatoração grande
- Correção de bug não relacionada
- Atualização de dependências
- Mudança visual
- Alteração de documentação sem relação

Se precisar fazer várias coisas, divida em Pull Requests separados.

---

## 12. Código

O código deve ser:

- Claro
- Simples
- Legível
- Consistente com o projeto
- Fácil de testar
- Fácil de manter

Evite:

- Abstrações desnecessárias
- Funções muito longas
- Código duplicado sem justificativa
- Nomes genéricos
- Comentários que apenas repetem o código
- Soluções complexas para problemas simples

---

## 13. Nomes

Use nomes descritivos para variáveis, funções, classes, arquivos e commits.

Prefira nomes que expliquem intenção.

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

Nomes claros reduzem a necessidade de comentários.

---

## 14. Comentários no código

Comentários devem explicar o motivo de algo existir, não apenas repetir o que o código faz.

Use comentários quando:

- Houver uma regra de negócio importante
- Existir uma decisão técnica não óbvia
- Um comportamento parecer estranho, mas for intencional
- Houver limitação conhecida
- Uma integração externa exigir cuidado específico

Evite comentários como:

```txt
incrementa contador
verifica se usuário existe
retorna resultado
```

Prefira comentários úteis:

```txt
Esta validação é mantida por compatibilidade com usuários antigos que não possuem documento cadastrado.
```

---

## 15. Testes

Sempre que uma alteração impactar comportamento, avalie a necessidade de testes.

Crie ou atualize testes quando:

- Corrigir um bug
- Criar uma funcionalidade
- Alterar regra de negócio
- Modificar validação
- Refatorar lógica sensível
- Corrigir caso de erro
- Alterar permissões ou autenticação

Os testes devem validar comportamento observável, não detalhes internos desnecessários.

---

## 16. Tipos de teste

Dependendo do projeto, podem existir diferentes tipos de teste:

- Testes unitários
- Testes de integração
- Testes end-to-end
- Testes manuais
- Testes de regressão

Use o tipo de teste adequado ao risco da mudança.

Nem toda alteração precisa de teste automatizado, mas toda alteração deve ser validada de alguma forma.

---

## 17. Validação manual

Quando a validação for manual, descreva claramente no Pull Request o que foi testado.

Exemplo:

```txt
Validação manual realizada:

1. Acessei a tela de login.
2. Informei email inválido.
3. Confirmei que a mensagem de erro foi exibida.
4. Informei credenciais válidas.
5. Confirmei que o usuário foi redirecionado para o painel.
```

Isso ajuda a revisão e reduz dúvidas sobre o impacto da mudança.

---

## 18. Documentação

Atualize a documentação sempre que a mudança impactar:

- Instalação
- Configuração
- Variáveis de ambiente
- Fluxo de uso
- API
- Regras de negócio
- Comandos
- Deploy
- Estrutura do projeto
- Processo de contribuição

Documentação desatualizada pode causar erros e retrabalho.

---

## 19. Segurança

Toda contribuição deve considerar segurança.

Atenção especial para:

- Validação de entrada
- Autenticação
- Autorização
- Permissões
- Exposição de dados sensíveis
- Logs com informações privadas
- Upload de arquivos
- Dependências externas
- Consultas ao banco de dados
- Manipulação de tokens
- Configurações de ambiente

Nunca inclua no repositório:

- Senhas
- Tokens
- Chaves privadas
- Arquivos `.env` reais
- Credenciais de banco de dados
- Certificados privados
- Dados reais de usuários
- Segredos de produção

---

## 20. Dependências

Não adicione dependências sem necessidade.

Antes de instalar uma nova biblioteca, avalie:

- O projeto já possui algo que resolve o problema?
- A dependência é mantida?
- A licença é compatível?
- Ela adiciona complexidade?
- Ela aumenta muito o tamanho do projeto?
- Existe risco de segurança?
- O benefício compensa o custo de manutenção?

Toda nova dependência deve ter uma justificativa clara.

---

## 21. Variáveis de ambiente

Se uma mudança exigir novas variáveis de ambiente:

- Documente o nome da variável
- Explique sua finalidade
- Informe se é obrigatória ou opcional
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

## 22. Migrações e banco de dados

Mudanças em banco de dados devem ser feitas com cuidado.

Antes de alterar estrutura de dados, verifique:

- Impacto em dados existentes
- Compatibilidade com versões anteriores
- Necessidade de migração
- Possibilidade de rollback
- Impacto em performance
- Campos obrigatórios
- Valores padrão
- Índices necessários

Evite mudanças destrutivas sem planejamento.

---

## 23. Alterações de API

Ao alterar APIs, considere:

- Compatibilidade com clientes existentes
- Contratos de entrada e saída
- Mensagens de erro
- Status codes
- Autenticação
- Autorização
- Versionamento, se aplicável
- Documentação

Evite quebrar contratos existentes sem aviso ou justificativa.

---

## 24. Interface e experiência do usuário

Em mudanças visuais ou de experiência, considere:

- Clareza para o usuário
- Consistência visual
- Acessibilidade
- Responsividade
- Estados de carregamento
- Estados de erro
- Mensagens vazias
- Feedback de sucesso
- Comportamento em dispositivos móveis

Sempre que possível, inclua evidências visuais no Pull Request.

---

## 25. Acessibilidade

Ao alterar interfaces, considere acessibilidade.

Verifique:

- Textos alternativos em imagens relevantes
- Contraste adequado
- Navegação por teclado
- Labels em campos de formulário
- Estados de foco visíveis
- Mensagens de erro compreensíveis
- Uso correto de elementos semânticos

Acessibilidade deve ser tratada como parte da qualidade do produto.

---

## 26. Performance

Avalie performance quando a mudança envolver:

- Consultas ao banco de dados
- Listagens grandes
- Loops pesados
- Renderização de muitos elementos
- Upload ou download de arquivos
- Integrações externas
- Processamento em lote
- Carregamento inicial da aplicação

Evite otimizações prematuras, mas não ignore gargalos evidentes.

---

## 27. Logs e monitoramento

Logs devem ajudar a diagnosticar problemas sem expor dados sensíveis.

Bons logs indicam:

- O que aconteceu
- Onde aconteceu
- Identificadores não sensíveis
- Contexto suficiente para investigação

Evite logar:

- Senhas
- Tokens
- Dados pessoais sensíveis
- Cartões
- Documentos
- Segredos
- Cookies de sessão

---

## 28. Uso de IA nas contribuições

É permitido usar IA como apoio, desde que as regras do projeto sejam respeitadas.

Ao usar IA:

- Revise tudo que foi gerado
- Entenda o código antes de aceitar
- Não envie segredos para ferramentas de IA
- Não aceite mudanças grandes sem revisão
- Valide o comportamento
- Mantenha o escopo controlado
- Documente decisões importantes quando necessário

Consulte também o arquivo:

```txt
AI_GUIDELINES.md
```

A responsabilidade pela contribuição continua sendo da pessoa que abriu o Pull Request.

---

## 29. Checklist antes de abrir um Pull Request

Antes de abrir um Pull Request, verifique:

- [ ] A mudança tem objetivo claro
- [ ] A branch possui nome descritivo
- [ ] Os commits são claros
- [ ] O escopo está controlado
- [ ] Não há arquivos desnecessários
- [ ] Não há segredos ou dados sensíveis
- [ ] O código foi revisado localmente
- [ ] A alteração foi testada
- [ ] A documentação foi atualizada, se necessário
- [ ] O Pull Request descreve como validar a mudança
- [ ] Riscos conhecidos foram informados

---

## 30. Checklist para revisão

Ao revisar um Pull Request, verifique:

- [ ] A mudança resolve o problema proposto
- [ ] O escopo está adequado
- [ ] O código está claro
- [ ] Não há complexidade desnecessária
- [ ] Não há impacto inesperado
- [ ] Existem testes ou validação suficiente
- [ ] Não há dados sensíveis expostos
- [ ] A documentação foi atualizada, se necessário
- [ ] A solução está consistente com o projeto
- [ ] O Pull Request pode ser revertido se necessário

---

## 31. Quando solicitar mudanças

Solicite mudanças quando encontrar:

- Bug evidente
- Falha de segurança
- Código difícil de entender
- Mudança fora do escopo
- Falta de validação importante
- Quebra de comportamento existente
- Dependência adicionada sem justificativa
- Documentação incorreta
- Exposição de dados sensíveis
- Ausência de tratamento de erro relevante

Comentários devem explicar o motivo da solicitação.

---

## 32. Quando aprovar

Aprovar um Pull Request significa que a mudança está aceitável para entrar no projeto.

Antes de aprovar, verifique se:

- O objetivo foi atendido
- O risco é aceitável
- O código está compreensível
- O escopo está adequado
- A validação foi suficiente
- Não há pendências críticas
- A documentação está adequada, se aplicável

A aprovação deve ser feita com responsabilidade.

---

## 33. Conflitos

Se houver conflitos com a branch principal, resolva com cuidado.

Após resolver conflitos:

- Revise os arquivos afetados
- Execute testes ou validação
- Confirme que nenhuma alteração foi perdida
- Atualize o Pull Request, se necessário

Conflitos mal resolvidos podem introduzir bugs difíceis de identificar.

---

## 34. Organização dos arquivos

Ao criar novos arquivos, siga a organização existente do projeto.

Antes de criar uma nova pasta ou padrão, verifique:

- Se já existe local apropriado
- Se o nome está consistente
- Se o arquivo realmente precisa existir
- Se a estrutura facilita manutenção
- Se a mudança está documentada quando necessário

Evite criar estruturas paralelas sem justificativa.

---

## 35. Compatibilidade

Ao alterar código existente, considere compatibilidade com:

- Versões anteriores
- Dados existentes
- APIs públicas
- Integrações externas
- Configurações atuais
- Ambientes de desenvolvimento
- Ambientes de produção

Mudanças incompatíveis devem ser planejadas e comunicadas.

---

## 36. Erros e mensagens

Mensagens de erro devem ser claras e úteis.

Uma boa mensagem deve:

- Explicar o que aconteceu
- Indicar como corrigir, quando possível
- Não expor detalhes sensíveis
- Ser compreensível para o público correto

Evite mensagens genéricas como:

```txt
Erro inesperado.
```

Prefira algo mais útil quando possível:

```txt
Não foi possível salvar o cadastro porque o email informado já está em uso.
```

---

## 37. Responsabilidade

Quem contribui é responsável por entender e validar a própria mudança.

Isso inclui:

- Código submetido
- Testes criados
- Documentação alterada
- Dependências adicionadas
- Configurações modificadas
- Decisões técnicas tomadas

O objetivo é manter o projeto confiável e sustentável.

---

## 38. Conduta

Todas as interações no projeto devem ser respeitosas.

Espera-se que as pessoas contribuam com:

- Respeito
- Clareza
- Colaboração
- Boa-fé
- Abertura para feedback
- Foco na solução

Não serão aceitos comportamentos ofensivos, discriminatórios, abusivos ou desrespeitosos.

---

## 39. Dúvidas

Em caso de dúvida:

- Pergunte antes de alterar
- Abra uma discussão
- Explique o contexto
- Mostre alternativas
- Peça revisão antecipada
- Divida a mudança em partes menores

É melhor esclarecer antes do que corrigir depois.

---

## 40. Princípio final

Contribuir bem não é apenas fazer o código funcionar.

Uma boa contribuição deve ser:

- Correta
- Clara
- Segura
- Revisável
- Testável
- Sustentável
- Alinhada ao objetivo do projeto

O projeto deve ficar melhor depois de cada contribuição.

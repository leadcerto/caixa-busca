# Pull Request

## Resumo

Descreva de forma objetiva o que foi alterado neste Pull Request.

Exemplo:

```txt
Este Pull Request adiciona validação de email no formulário de cadastro e ajusta a mensagem de erro exibida ao usuário.
```

---

## Motivação

Explique por que esta mudança é necessária.

- Qual problema está sendo resolvido?
- Qual melhoria está sendo entregue?
- Existe alguma issue, tarefa ou contexto relacionado?

Issue relacionada, se houver:

```txt
Closes #
```

---

## Tipo de mudança

Marque todas as opções aplicáveis:

- [ ] `feature`: nova funcionalidade
- [ ] `fix`: correção de bug
- [ ] `docs`: alteração de documentação
- [ ] `refactor`: refatoração sem mudança de comportamento
- [ ] `test`: criação ou ajuste de testes
- [ ] `chore`: manutenção do projeto
- [ ] `style`: ajuste visual ou de formatação
- [ ] `build`: alteração em build ou dependências
- [ ] `ci`: alteração em integração contínua
- [ ] `security`: correção ou melhoria de segurança
- [ ] Outro:

---

## Alterações realizadas

Liste as principais mudanças feitas.

- 
- 
- 

---

## O que ficou fora do escopo

Informe o que este Pull Request **não** pretende resolver.

Isso ajuda a evitar dúvidas durante a revisão.

- 
- 
- 

---

## Como testar

Descreva os passos para validar a mudança.

1. 
2. 
3. 

Resultado esperado:

```txt
Descreva aqui o comportamento esperado após executar os passos acima.
```

---

## Validação realizada

Informe como a mudança foi validada.

- [ ] Testes automatizados foram executados
- [ ] Testes manuais foram executados
- [ ] Validação visual foi realizada
- [ ] Fluxo principal foi testado
- [ ] Casos de erro foram testados
- [ ] Não foi possível testar completamente

Detalhes da validação:

```txt
Descreva aqui o que foi testado.
```

---

## Evidências

Adicione prints, vídeos, logs ou exemplos, se necessário.

Antes:

```txt
Adicione evidência do comportamento anterior, se aplicável.
```

Depois:

```txt
Adicione evidência do novo comportamento, se aplicável.
```

---

## Impactos conhecidos

Informe possíveis impactos desta mudança.

- [ ] Pode impactar usuários finais
- [ ] Pode impactar APIs
- [ ] Pode impactar banco de dados
- [ ] Pode impactar autenticação ou autorização
- [ ] Pode impactar performance
- [ ] Pode impactar documentação
- [ ] Pode impactar deploy
- [ ] Não há impactos conhecidos

Detalhes:

```txt
Descreva riscos, impactos ou pontos de atenção.
```

---

## Banco de dados

Este Pull Request altera banco de dados?

- [ ] Não
- [ ] Sim, adiciona migração
- [ ] Sim, altera estrutura existente
- [ ] Sim, remove campo, tabela ou índice
- [ ] Sim, altera dados existentes

Se sim, descreva:

```txt
Explique a alteração no banco, impacto em dados existentes e possibilidade de rollback.
```

---

## Variáveis de ambiente

Este Pull Request adiciona ou altera variáveis de ambiente?

- [ ] Não
- [ ] Sim

Se sim, liste as variáveis:

```env
NOME_DA_VARIAVEL=valor_de_exemplo
```

Observações:

- Não incluir valores reais
- Não incluir segredos
- Atualizar `.env.example`, se existir
- Atualizar documentação, se necessário

---

## Dependências

Este Pull Request adiciona, remove ou atualiza dependências?

- [ ] Não
- [ ] Sim, adiciona dependência
- [ ] Sim, remove dependência
- [ ] Sim, atualiza dependência

Se sim, justifique:

```txt
Explique por que a dependência é necessária e quais alternativas foram consideradas.
```

---

## Segurança

Marque os itens revisados:

- [ ] Não há segredos, tokens ou credenciais no código
- [ ] Entradas externas são validadas
- [ ] Permissões foram revisadas, se aplicável
- [ ] Dados sensíveis não são expostos em APIs
- [ ] Logs não expõem informações sensíveis
- [ ] Mensagens de erro não revelam detalhes internos
- [ ] Uploads foram validados, se aplicável
- [ ] Alterações de autenticação foram revisadas, se aplicável
- [ ] Alterações de autorização foram revisadas, se aplicável
- [ ] Não se aplica

Observações de segurança:

```txt
Descreva qualquer ponto de atenção relacionado à segurança.
```

---

## Documentação

A documentação foi atualizada?

- [ ] Não era necessário
- [ ] Sim, documentação atualizada
- [ ] Ainda precisa ser atualizada

Arquivos de documentação impactados:

- [ ] `README.md`
- [ ] `CONTRIBUTING.md`
- [ ] `SECURITY.md`
- [ ] `AI_GUIDELINES.md`
- [ ] Outro:

Observações:

```txt
Descreva o que foi documentado ou o que ainda precisa ser documentado.
```

---

## Uso de IA

Foi usada alguma ferramenta de IA para apoiar esta mudança?

- [ ] Não
- [ ] Sim

Se sim, confirme:

- [ ] O conteúdo gerado foi revisado
- [ ] O código foi entendido antes de ser aceito
- [ ] Nenhum segredo foi enviado para ferramentas de IA
- [ ] A solução foi testada ou validada
- [ ] O escopo da mudança foi mantido

Observações:

```txt
Descreva brevemente como a IA foi usada, se necessário.
```

---

## Checklist do autor

Antes de solicitar revisão, confirme:

- [ ] O Pull Request tem objetivo claro
- [ ] A mudança está dentro do escopo
- [ ] O código foi revisado localmente
- [ ] Não há arquivos desnecessários
- [ ] Não há código comentado sem justificativa
- [ ] Não há logs temporários ou mensagens de debug
- [ ] Não há segredos ou dados sensíveis
- [ ] O comportamento esperado foi validado
- [ ] Testes foram criados ou ajustados, se necessário
- [ ] A documentação foi atualizada, se necessário
- [ ] O Pull Request descreve como testar a mudança
- [ ] Riscos conhecidos foram informados
- [ ] A mudança pode ser revertida se necessário

---

## Checklist para revisão

Quem revisar deve verificar:

- [ ] A mudança resolve o problema proposto
- [ ] O escopo está adequado
- [ ] O código está claro e legível
- [ ] Não há complexidade desnecessária
- [ ] Não há mudança de comportamento inesperada
- [ ] Testes ou validação são suficientes
- [ ] Não há exposição de dados sensíveis
- [ ] Não há dependências desnecessárias
- [ ] A documentação está adequada, se aplicável
- [ ] Questões de segurança foram consideradas
- [ ] O Pull Request está pronto para merge

---

## Observações adicionais

Adicione qualquer informação extra que ajude na revisão.

```txt
Use este espaço para contexto adicional, decisões tomadas, limitações conhecidas ou próximos passos.
```

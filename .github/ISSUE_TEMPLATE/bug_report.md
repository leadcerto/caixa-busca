---
name: Relato de bug
about: Use este modelo para reportar um erro, falha ou comportamento inesperado
title: "fix: "
labels: bug
assignees: ""
---

# Relato de bug

## Resumo

Descreva de forma curta e objetiva o problema encontrado.

Exemplo:

```txt
Ao tentar salvar o formulário de cadastro com um email inválido, a aplicação exibe erro genérico em vez de mostrar uma mensagem de validação.
```

---

## Comportamento atual

Explique o que está acontecendo hoje.

```txt
Descreva aqui o comportamento observado.
```

---

## Comportamento esperado

Explique o que deveria acontecer.

```txt
Descreva aqui o comportamento correto esperado.
```

---

## Passos para reproduzir

Liste os passos necessários para reproduzir o problema.

1. 
2. 
3. 
4. 

Resultado observado:

```txt
Descreva o resultado que aparece após seguir os passos.
```

---

## O problema acontece sempre?

Marque uma opção:

- [ ] Sim, acontece sempre
- [ ] Não, acontece às vezes
- [ ] Aconteceu apenas uma vez
- [ ] Não sei confirmar

Se for intermitente, explique quando parece acontecer:

```txt
Descreva condições, horários, dados ou ações que parecem influenciar o problema.
```

---

## Severidade

Marque a opção mais adequada:

- [ ] Baixa: problema pequeno, com pouco impacto
- [ ] Média: problema afeta uso normal, mas existe contorno
- [ ] Alta: problema impede funcionalidade importante
- [ ] Crítica: problema causa indisponibilidade, perda de dados ou risco de segurança

Explique o impacto:

```txt
Descreva como o bug afeta usuários, operação, dados ou negócio.
```

---

## Área afetada

Marque todas as opções aplicáveis:

- [ ] Interface
- [ ] API
- [ ] Autenticação
- [ ] Autorização
- [ ] Cadastro
- [ ] Login
- [ ] Banco de dados
- [ ] Integração externa
- [ ] Upload de arquivos
- [ ] Relatórios
- [ ] Pagamentos
- [ ] Notificações
- [ ] Deploy
- [ ] Performance
- [ ] Segurança
- [ ] Documentação
- [ ] Testes
- [ ] Outro:

---

## Ambiente

Informe onde o problema aconteceu.

- [ ] Desenvolvimento local
- [ ] Homologação
- [ ] Produção
- [ ] Ambiente de teste
- [ ] Outro:

Detalhes do ambiente:

```txt
Sistema operacional:
Navegador:
Versão do navegador:
Dispositivo:
URL ou rota:
Versão do projeto/commit:
```

---

## Dados utilizados

Informe os dados usados para reproduzir o problema, sem incluir informações sensíveis.

```txt
Exemplo:
Tipo de usuário:
Permissão:
Parâmetros utilizados:
Arquivo de exemplo:
Payload sem dados sensíveis:
```

> Não inclua senhas, tokens, documentos, dados reais de clientes, chaves privadas ou qualquer informação sensível.

---

## Evidências

Adicione prints, vídeos, logs ou mensagens de erro que ajudem a entender o problema.

```txt
Cole aqui evidências relevantes, removendo qualquer dado sensível.
```

---

## Logs

Se houver logs úteis, cole abaixo.

Antes de colar, remova:

- Senhas
- Tokens
- Cookies
- Chaves privadas
- Dados pessoais
- Dados financeiros
- Credenciais
- Informações sensíveis

```txt
Cole logs sanitizados aqui.
```

---

## Mensagem de erro

Se alguma mensagem de erro foi exibida, informe abaixo.

```txt
Cole a mensagem de erro aqui.
```

---

## Stack trace

Se existir stack trace e for seguro compartilhar, cole abaixo.

Remova caminhos, segredos ou dados sensíveis quando necessário.

```txt
Cole o stack trace sanitizado aqui.
```

---

## Requisição relacionada

Se o bug envolver uma API, informe os dados da requisição sem informações sensíveis.

```txt
Método:
Rota:
Status code:
Query params:
Body sem dados sensíveis:
Resposta sem dados sensíveis:
```

---

## Impacto em usuários

Quem é afetado por este bug?

- [ ] Todos os usuários
- [ ] Apenas usuários autenticados
- [ ] Apenas administradores
- [ ] Apenas usuários com permissão específica
- [ ] Apenas um cliente ou organização
- [ ] Apenas ambiente interno
- [ ] Não sei

Detalhes:

```txt
Explique quem é impactado e em quais situações.
```

---

## Existe solução temporária?

Existe algum contorno conhecido para evitar ou reduzir o impacto?

- [ ] Sim
- [ ] Não
- [ ] Não sei

Se sim, descreva:

```txt
Descreva o workaround ou procedimento temporário.
```

---

## Quando começou?

Informe quando o problema foi percebido.

```txt
Data/hora aproximada:
Após alguma alteração específica?
Após deploy?
Após atualização de dependência?
Após mudança de configuração?
```

---

## Regressão

Este comportamento funcionava antes?

- [ ] Sim
- [ ] Não
- [ ] Não sei

Se sim, informe quando funcionava:

```txt
Versão, data, commit ou contexto em que o comportamento funcionava.
```

---

## Possível causa

Se você tiver uma suspeita da causa, descreva abaixo.

```txt
Descreva qualquer hipótese, arquivo relacionado, alteração recente ou contexto técnico relevante.
```

---

## Arquivos possivelmente relacionados

Liste arquivos, módulos ou áreas do código que podem estar envolvidos.

```txt
Exemplo:
src/modules/auth/
src/pages/login/
src/services/user-service.ts
```

---

## Critérios de aceite para correção

A correção pode ser considerada concluída quando:

- [ ] O problema descrito não acontece mais
- [ ] O comportamento esperado foi implementado
- [ ] Casos de erro foram tratados adequadamente
- [ ] Não houve regressão no fluxo existente
- [ ] Testes foram criados ou atualizados, se necessário
- [ ] A documentação foi atualizada, se necessário

Critérios adicionais:

```txt
Adicione outros critérios específicos, se necessário.
```

---

## Testes sugeridos

Informe cenários que devem ser testados após a correção.

```txt
1. 
2. 
3. 
```

---

## Segurança

Este bug envolve ou pode envolver risco de segurança?

- [ ] Não
- [ ] Sim
- [ ] Não sei

Se sim, **não publique detalhes exploráveis nesta issue**.

Para vulnerabilidades, siga o processo descrito em:

```txt
SECURITY.md
```

Observações seguras:

```txt
Descreva apenas informações gerais, sem instruções de exploração ou dados sensíveis.
```

---

## Checklist antes de enviar

Confirme antes de abrir a issue:

- [ ] Verifiquei se já existe uma issue parecida
- [ ] Descrevi o comportamento atual
- [ ] Descrevi o comportamento esperado
- [ ] Incluí passos para reproduzir
- [ ] Informei o ambiente onde aconteceu
- [ ] Removi dados sensíveis das evidências
- [ ] Adicionei logs ou prints, se aplicável
- [ ] Classifiquei a severidade
- [ ] Informei se existe workaround conhecido
- [ ] Verifiquei se pode ser problema de segurança

---

## Informações adicionais

Use este espaço para qualquer contexto extra que ajude na investigação.

```txt
Adicione observações, links, referências, comentários ou detalhes adicionais.
```

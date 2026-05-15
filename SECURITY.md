# Política de Segurança

Este documento define as práticas, responsabilidades e procedimentos de segurança deste projeto.

O objetivo é reduzir riscos, evitar exposição de dados sensíveis, orientar contribuições seguras e estabelecer um processo claro para reportar e corrigir vulnerabilidades.

---

## 1. Princípio geral

Segurança deve ser tratada como parte essencial da qualidade do projeto.

Toda alteração deve considerar possíveis impactos em:

- Dados dos usuários
- Autenticação
- Autorização
- Permissões
- Banco de dados
- APIs
- Logs
- Configurações
- Dependências
- Infraestrutura
- Integrações externas

Segurança não deve ser deixada apenas para o final do desenvolvimento.

---

## 2. Responsabilidade

Toda pessoa que contribui com o projeto é responsável por evitar a introdução de falhas de segurança.

Isso inclui:

- Revisar o próprio código
- Validar entradas de usuário
- Evitar exposição de dados sensíveis
- Não versionar segredos
- Usar dependências com cuidado
- Reportar vulnerabilidades encontradas
- Corrigir problemas de segurança com prioridade adequada

A responsabilidade por segurança é compartilhada.

---

## 3. Dados sensíveis

Nunca devem ser versionados, enviados em Pull Requests ou compartilhados publicamente:

- Senhas
- Tokens
- Chaves privadas
- Chaves SSH
- Certificados privados
- Credenciais de banco de dados
- Arquivos `.env` reais
- Segredos de produção
- Dados pessoais sensíveis
- Dados financeiros
- Cookies de sessão
- Tokens JWT reais
- Logs contendo informações privadas

Se for necessário usar exemplos, utilize valores fictícios.

Exemplo seguro:

```env
DATABASE_URL=mysql://user:password@localhost:3306/database
API_KEY=example_api_key
JWT_SECRET=example_secret
```

---

## 4. Arquivos de ambiente

Arquivos de ambiente reais não devem ser enviados para o repositório.

Não versionar:

```txt
.env
.env.local
.env.production
.env.development.local
.env.test.local
```

Quando necessário, criar arquivos de exemplo sem valores reais:

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

O arquivo de exemplo deve documentar as variáveis necessárias, mas nunca conter credenciais reais.

---

## 5. Autenticação

Funcionalidades de autenticação devem ser implementadas com cuidado.

Verifique sempre:

- Se credenciais são validadas corretamente
- Se senhas nunca são salvas em texto puro
- Se sessões expiram corretamente
- Se tokens possuem tempo de expiração adequado
- Se logout invalida a sessão quando necessário
- Se erros de login não revelam informações sensíveis
- Se há proteção contra força bruta quando aplicável

Mensagens de erro devem evitar revelar se um email, usuário ou conta existe.

Exemplo recomendado:

```txt
Credenciais inválidas.
```

Evite mensagens como:

```txt
Este email não está cadastrado.
```

ou:

```txt
A senha está incorreta.
```

---

## 6. Senhas

Senhas devem ser tratadas com alto nível de proteção.

Regras recomendadas:

- Nunca armazenar senhas em texto puro
- Utilizar algoritmo de hash seguro
- Utilizar salt quando aplicável
- Não registrar senhas em logs
- Não retornar senhas em APIs
- Não enviar senhas em mensagens de erro
- Não expor senhas em telas administrativas

Quando houver redefinição de senha:

- O token deve expirar
- O token deve ser de uso único
- O token não deve ser previsível
- O fluxo deve evitar enumeração de usuários

---

## 7. Autorização

Autenticar um usuário não significa autorizar todas as ações.

Sempre validar permissões no backend ou no servidor responsável pela regra de negócio.

Verifique:

- Se o usuário pode acessar o recurso solicitado
- Se o usuário pode alterar o recurso solicitado
- Se o usuário pertence à organização, conta ou contexto correto
- Se papéis e permissões estão sendo respeitados
- Se usuários comuns não acessam rotas administrativas
- Se IDs enviados pelo cliente não permitem acesso indevido

Nunca confiar apenas na interface para proteger ações.

---

## 8. Controle de acesso

Todo recurso sensível deve ter controle de acesso explícito.

Atenção especial para:

- Painéis administrativos
- Dados de usuários
- Dados financeiros
- Arquivos privados
- Relatórios
- Configurações
- Integrações
- Rotas internas
- Operações destrutivas

Antes de permitir uma ação, valide:

- Quem está solicitando
- Qual recurso está sendo acessado
- Qual permissão é necessária
- Se a ação é permitida naquele contexto

---

## 9. Validação de entrada

Toda entrada externa deve ser validada.

Entradas externas incluem:

- Corpo de requisições
- Parâmetros de URL
- Query strings
- Headers
- Cookies
- Uploads
- Dados vindos de integrações
- Dados de formulários
- Dados importados

Validar:

- Tipo
- Formato
- Tamanho
- Obrigatoriedade
- Valores permitidos
- Limites
- Regras de negócio

Nunca assumir que dados vindos do cliente são confiáveis.

---

## 10. Sanitização de dados

Além de validar, pode ser necessário sanitizar dados.

Sanitização pode incluir:

- Remover espaços extras
- Normalizar email
- Remover caracteres inválidos
- Escapar conteúdo perigoso
- Limitar tamanho de textos
- Bloquear HTML quando não permitido

A sanitização deve ser feita com cuidado para não modificar dados de forma inesperada.

---

## 11. SQL Injection

Consultas ao banco de dados devem evitar concatenação direta de entrada do usuário.

Evite:

```sql
SELECT * FROM users WHERE email = '${email}';
```

Prefira queries parametrizadas, query builders ou ORM com proteção adequada.

Exemplo conceitual:

```sql
SELECT * FROM users WHERE email = ?;
```

Toda entrada usada em consultas deve ser tratada como não confiável.

---

## 12. XSS

Ao renderizar conteúdo fornecido por usuários, considere riscos de XSS.

Atenção especial para:

- Comentários
- Descrições
- Campos de perfil
- Conteúdo HTML
- Mensagens
- Parâmetros refletidos na tela
- Dados vindos de integrações externas

Boas práticas:

- Escapar conteúdo por padrão
- Evitar renderizar HTML bruto
- Sanitizar HTML quando for realmente necessário permitir HTML
- Usar bibliotecas confiáveis para sanitização
- Não inserir dados não confiáveis diretamente em scripts

Evite renderização insegura de HTML.

---

## 13. CSRF

Quando o projeto utilizar cookies de sessão ou autenticação baseada em cookies, avalie proteção contra CSRF.

Medidas possíveis:

- Tokens CSRF
- Cookies `SameSite`
- Validação de origem
- Validação de referer quando aplicável
- Uso correto de métodos HTTP
- Confirmação para ações sensíveis

Ações como exclusão, alteração de senha ou mudança de permissões exigem cuidado extra.

---

## 14. Upload de arquivos

Uploads devem ser tratados como operação de risco.

Verifique:

- Tipo de arquivo permitido
- Tamanho máximo
- Nome do arquivo
- Extensão
- MIME type
- Local de armazenamento
- Permissões de acesso
- Possibilidade de execução do arquivo
- Varredura ou validação adicional quando aplicável

Não confie apenas na extensão do arquivo.

Evite permitir upload de arquivos executáveis.

Exemplos de extensões perigosas:

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

Logs são importantes para investigação, mas não devem expor informações sensíveis.

Não registrar:

- Senhas
- Tokens
- Chaves privadas
- Dados de cartão
- Documentos pessoais
- Cookies de sessão
- Credenciais
- Segredos
- Dados pessoais sensíveis

Bons logs devem conter:

- Evento ocorrido
- Data e hora
- Contexto técnico
- Identificador não sensível
- Erro resumido
- Local onde ocorreu

Exemplo aceitável:

```txt
Falha ao criar pedido para user_id=123. Motivo: estoque insuficiente.
```

Exemplo inadequado:

```txt
Falha no login. Email=usuario@example.com Senha=123456 Token=abc123
```

---

## 16. Mensagens de erro

Mensagens de erro não devem revelar detalhes internos sensíveis.

Evite expor:

- Stack traces em produção
- Nomes internos de tabelas
- Queries SQL
- Caminhos absolutos do servidor
- Chaves
- Tokens
- Configurações internas
- Detalhes de infraestrutura

Para usuários finais, prefira mensagens claras e seguras.

Exemplo:

```txt
Não foi possível concluir a operação. Tente novamente.
```

Para logs internos, registre detalhes suficientes sem expor segredos.

---

## 17. APIs

APIs devem ser protegidas contra uso indevido.

Verifique:

- Autenticação
- Autorização
- Validação de entrada
- Rate limiting quando aplicável
- Paginação em listagens
- Limites de tamanho
- Tratamento de erro
- Versionamento quando necessário
- Não exposição de campos sensíveis
- Status codes adequados

Nunca retornar dados sensíveis desnecessários.

Exemplo de campos que geralmente não devem ser retornados:

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

Considere limitar requisições em rotas sensíveis.

Rotas que podem precisar de rate limiting:

- Login
- Cadastro
- Recuperação de senha
- Envio de email
- APIs públicas
- Busca intensiva
- Upload de arquivos
- Geração de tokens
- Webhooks

O objetivo é reduzir abuso, força bruta e sobrecarga.

---

## 19. Webhooks

Webhooks devem ser validados antes de processar dados.

Verifique:

- Assinatura do provedor
- Origem da requisição
- Timestamp quando aplicável
- Replay attacks
- Idempotência
- Estrutura do payload
- Tratamento de erros
- Logs seguros

Não confie em webhooks sem validação.

---

## 20. Dependências

Dependências externas podem introduzir vulnerabilidades.

Antes de adicionar uma dependência, avalie:

- Necessidade real
- Popularidade e manutenção
- Histórico de segurança
- Licença
- Tamanho
- Frequência de atualizações
- Alternativas já existentes no projeto

Após adicionar dependências:

- Mantenha versões atualizadas
- Revise alertas de segurança
- Remova dependências não utilizadas
- Evite pacotes desconhecidos sem justificativa

---

## 21. Atualizações de segurança

Correções de segurança devem ter prioridade adequada.

Ao identificar dependência vulnerável:

- Avalie o impacto real no projeto
- Atualize para versão segura quando possível
- Execute testes após atualização
- Registre riscos conhecidos
- Evite ignorar alertas sem análise

Se uma atualização quebrar compatibilidade, planeje a correção com cuidado.

---

## 22. Configuração segura

Configurações devem ser revisadas antes de uso em produção.

Verifique:

- Ambiente correto
- Variáveis obrigatórias
- Logs de debug desativados
- Erros detalhados desativados em produção
- CORS configurado corretamente
- HTTPS habilitado quando aplicável
- Permissões mínimas necessárias
- Segredos fora do código
- Serviços externos configurados com segurança

Configurações de desenvolvimento não devem ser copiadas diretamente para produção.

---

## 23. CORS

Configurações de CORS devem ser restritivas.

Evite liberar origens amplas sem necessidade.

Evite:

```txt
Access-Control-Allow-Origin: *
```

Especialmente quando houver credenciais, cookies ou dados privados.

Prefira liberar apenas domínios necessários.

Exemplo conceitual:

```txt
Access-Control-Allow-Origin: https://example.com
```

---

## 24. Permissões mínimas

Use o princípio do menor privilégio.

Cada usuário, serviço, token ou integração deve ter apenas as permissões necessárias para executar sua função.

Evite:

- Tokens com acesso total sem necessidade
- Usuários administrativos para tarefas simples
- Chaves compartilhadas entre ambientes
- Permissões amplas em banco de dados
- Acesso de escrita quando apenas leitura é necessária

Permissões amplas aumentam o impacto de falhas.

---

## 25. Ambientes

Separar ambientes reduz riscos.

Ambientes comuns:

- Desenvolvimento
- Teste
- Homologação
- Produção

Boas práticas:

- Usar credenciais diferentes por ambiente
- Não usar dados reais em desenvolvimento sem necessidade
- Não compartilhar segredos entre ambientes
- Evitar apontar ambiente local para produção
- Identificar claramente cada ambiente
- Restringir acesso ao ambiente de produção

---

## 26. Dados de produção

Dados de produção devem ser tratados com extremo cuidado.

Evite copiar dados reais para ambientes locais.

Se for necessário usar dados reais:

- Obter autorização adequada
- Remover ou mascarar dados sensíveis
- Limitar acesso
- Armazenar com segurança
- Apagar após o uso
- Registrar a finalidade

Preferir dados fictícios ou anonimizados.

---

## 27. Backups

Quando houver banco de dados ou arquivos importantes, backups devem ser considerados.

Boas práticas:

- Realizar backups regulares
- Proteger backups com controle de acesso
- Testar restauração periodicamente
- Evitar armazenar backups em locais públicos
- Criptografar backups quando necessário
- Definir política de retenção

Backup que não pode ser restaurado não é confiável.

---

## 28. Operações destrutivas

Operações destrutivas devem ter proteção extra.

Exemplos:

- Excluir conta
- Excluir dados
- Remover arquivos
- Alterar permissões
- Resetar banco
- Cancelar assinatura
- Revogar acesso
- Apagar registros em lote

Boas práticas:

- Confirmar intenção do usuário
- Validar autorização
- Registrar auditoria quando aplicável
- Permitir reversão quando possível
- Evitar exclusão física quando soft delete for mais adequado
- Testar cuidadosamente

---

## 29. Auditoria

Para ações sensíveis, considere manter registros de auditoria.

Eventos que podem exigir auditoria:

- Login administrativo
- Alteração de permissões
- Exclusão de dados
- Alteração de configurações
- Exportação de dados
- Acesso a informações sensíveis
- Mudanças financeiras
- Falhas repetidas de autenticação

Registros de auditoria devem evitar dados sensíveis desnecessários.

---

## 30. Uso de IA com segurança

Ferramentas de IA podem apoiar o desenvolvimento, mas não devem receber informações sensíveis.

Nunca envie para ferramentas de IA:

- Senhas
- Tokens
- Chaves privadas
- Credenciais
- Arquivos `.env` reais
- Dados reais de usuários
- Logs com segredos
- Informações financeiras privadas
- Dados pessoais sensíveis

Ao usar IA para segurança:

- Revise todas as sugestões
- Não aplique código sem entender
- Teste a solução
- Verifique se não houve mudança fora do escopo
- Não confie cegamente em análises automáticas

Consulte também:

```txt
AI_GUIDELINES.md
```

---

## 31. Reportando vulnerabilidades

Se encontrar uma vulnerabilidade, não abra uma issue pública com detalhes exploráveis.

Em vez disso, reporte de forma privada para a pessoa ou equipe responsável pelo projeto.

Inclua, quando possível:

- Descrição do problema
- Impacto potencial
- Passos para reproduzir
- Arquivos ou rotas afetadas
- Evidências
- Sugestão de correção, se houver

Evite divulgar publicamente detalhes antes da correção.

---

## 32. Como descrever uma vulnerabilidade

Use este modelo ao reportar um problema de segurança:

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

## Evidências

Inclua prints, logs mascarados ou exemplos sem dados sensíveis.

## Sugestão de correção

Descreva uma possível correção, se souber.

## Severidade sugerida

Baixa, média, alta ou crítica.
```

---

## 33. Classificação de severidade

A severidade deve considerar impacto e facilidade de exploração.

### Baixa

Problemas com impacto limitado ou difícil exploração.

Exemplos:

- Mensagem pouco clara
- Pequena exposição de informação não sensível
- Configuração melhorável sem impacto direto

### Média

Problemas que podem afetar usuários ou dados em condições específicas.

Exemplos:

- Validação incompleta
- Falha de autorização em caso limitado
- Exposição parcial de dados internos
- Rate limiting ausente em rota sensível

### Alta

Problemas com impacto significativo.

Exemplos:

- Acesso indevido a dados de outros usuários
- Bypass de autorização
- Exposição de dados sensíveis
- Upload inseguro com risco relevante
- Execução de ação administrativa indevida

### Crítica

Problemas com impacto grave ou exploração ampla.

Exemplos:

- Execução remota de código
- Vazamento de credenciais de produção
- Acesso administrativo não autorizado
- Comprometimento total do sistema
- Exposição massiva de dados sensíveis

---

## 34. Priorização de correções

Problemas de segurança devem ser priorizados conforme severidade.

Referência geral:

- Crítica: corrigir imediatamente
- Alta: corrigir com prioridade máxima
- Média: planejar correção em curto prazo
- Baixa: corrigir conforme planejamento do projeto

A prioridade final deve considerar contexto, exposição e impacto real.

---

## 35. Processo de correção

Ao corrigir uma vulnerabilidade:

- Reproduza o problema de forma segura
- Entenda a causa raiz
- Implemente a menor correção segura possível
- Adicione teste quando aplicável
- Revise impactos laterais
- Evite expor detalhes sensíveis no Pull Request
- Atualize documentação se necessário
- Valide a correção antes do merge

Correções de segurança devem ser claras e revisáveis.

---

## 36. Comunicação

A comunicação sobre vulnerabilidades deve ser cuidadosa.

Evite divulgar:

- Código de exploração
- Credenciais
- Dados reais
- Detalhes sensíveis antes da correção
- Caminhos internos desnecessários
- Informações que facilitem abuso

Após a correção, pode ser adequado documentar o problema em termos gerais.

---

## 37. Checklist de segurança para Pull Requests

Antes de abrir ou aprovar um Pull Request, verifique:

- [ ] Não há segredos ou credenciais no código
- [ ] Entradas externas são validadas
- [ ] Permissões são verificadas no backend ou serviço responsável
- [ ] Dados sensíveis não são retornados por APIs
- [ ] Logs não expõem informações privadas
- [ ] Mensagens de erro não revelam detalhes internos
- [ ] Dependências adicionadas são necessárias
- [ ] Uploads são restritos, se aplicável
- [ ] Alterações de autenticação foram revisadas com cuidado
- [ ] Alterações de autorização foram revisadas com cuidado
- [ ] Variáveis de ambiente foram documentadas sem valores reais
- [ ] A mudança foi testada ou validada

---

## 38. Checklist para novas funcionalidades

Ao criar uma nova funcionalidade, avalie:

- [ ] Quem pode acessar?
- [ ] Quem pode alterar?
- [ ] Quais dados são manipulados?
- [ ] Existe dado sensível?
- [ ] A entrada do usuário é validada?
- [ ] Existe risco de abuso?
- [ ] Existe risco de vazamento?
- [ ] A funcionalidade precisa de logs?
- [ ] A funcionalidade precisa de auditoria?
- [ ] Existe impacto em permissões?
- [ ] Existe impacto em integrações?
- [ ] Existe impacto em dados existentes?

---

## 39. Checklist para produção

Antes de publicar em produção, verifique:

- [ ] Variáveis de ambiente estão corretas
- [ ] Não há modo debug ativo
- [ ] Erros detalhados não são exibidos ao usuário
- [ ] Segredos estão fora do código
- [ ] CORS está restrito
- [ ] HTTPS está configurado quando aplicável
- [ ] Logs não expõem dados sensíveis
- [ ] Dependências críticas estão atualizadas
- [ ] Permissões estão adequadas
- [ ] Backups foram considerados quando necessário
- [ ] Rotas sensíveis foram revisadas
- [ ] Autenticação e autorização foram validadas

---

## 40. O que fazer em caso de vazamento

Se um segredo for exposto, não basta remover o arquivo do repositório.

Ações recomendadas:

- Revogar imediatamente o segredo exposto
- Gerar novo segredo
- Atualizar ambientes afetados
- Verificar logs e acessos suspeitos
- Remover o segredo do histórico quando necessário
- Investigar impacto
- Documentar a ocorrência internamente
- Revisar processo para evitar repetição

Segredos expostos devem ser considerados comprometidos.

---

## 41. Exemplos de problemas comuns

Problemas comuns que devem ser evitados:

- API retornando `password_hash`
- Rota administrativa protegida apenas no frontend
- Upload aceitando qualquer arquivo
- Query SQL montada por concatenação
- Token salvo em log
- Arquivo `.env` enviado ao repositório
- Erro exibindo stack trace em produção
- Permissão de usuário validada apenas pela interface
- CORS liberado para qualquer origem sem necessidade
- Dependência adicionada sem revisão

---

## 42. Ferramentas de apoio

Ferramentas podem ajudar, mas não substituem revisão humana.

Podem ser usadas ferramentas para:

- Análise de dependências
- Lint
- Testes automatizados
- Análise estática
- Detecção de segredos
- Varredura de vulnerabilidades
- Monitoramento de logs
- Auditoria de permissões

Resultados automáticos devem ser avaliados com contexto.

---

## 43. Segurança por padrão

Sempre que houver dúvida, prefira o comportamento mais seguro.

Exemplos:

- Negar acesso por padrão
- Exigir autenticação em rotas privadas
- Retornar apenas campos necessários
- Validar dados antes de processar
- Usar permissões mínimas
- Não expor detalhes internos
- Não aceitar arquivos perigosos
- Não confiar em dados do cliente

Segurança por padrão reduz riscos acidentais.

---

## 44. Revisão periódica

Este documento deve ser revisado periodicamente.

Revisar especialmente quando houver:

- Mudança de arquitetura
- Nova integração externa
- Nova forma de autenticação
- Nova área administrativa
- Mudança em permissões
- Incidente de segurança
- Adição de dependências importantes
- Alteração no processo de deploy

A política de segurança deve acompanhar a evolução do projeto.

---

## 45. Princípio final

Segurança não é apenas evitar ataques.

Segurança significa proteger usuários, dados, infraestrutura e confiança no projeto.

Toda contribuição deve buscar manter o projeto:

- Seguro
- Simples
- Revisável
- Auditável
- Confiável
- Sustentável

Se uma mudança aumenta risco sem necessidade, ela deve ser revista antes de ser aceita.

# Diretrizes de Arquitetura e Regras de Negócio: Página de Login

## Contexto Geral
- **Tecnologias Base:** Laravel (PHP) e MySQL.
- **Público-alvo:** Dois perfis distintos de usuários (Gestores e Imobiliárias Parceiras).
- **Objetivo:** Criar uma página de login única que autentique o usuário e o direcione para o painel correspondente ao seu nível de acesso.
- **Atenção:** Este documento define regras de arquitetura, segurança, performance e UX. A implementação deve seguir estritamente estas orientações.

---

## 1. Arquitetura e Fluxo de Autenticação

- **Página Única de Acesso:** O sistema deve ter apenas uma interface de login. Ambos os perfis usarão o mesmo formulário (e-mail e senha).
- **Identificação de Papéis (Roles):** A consulta ao banco de dados MySQL deve verificar o nível de acesso atrelado ao usuário (ex: admin/gestor vs. parceiro/imobiliária).
- **Redirecionamento Inteligente (Condicional):** Logo após a validação bem-sucedida das credenciais, o controlador deve avaliar o perfil do usuário. Gestores devem ser enviados para a rota restrita de gestão; Imobiliárias devem ser enviadas para a rota do painel de parceiros.
- **Proteção Pós-Login (Middlewares):** A segurança deve continuar nas rotas. É obrigatório configurar middlewares no Laravel para garantir que um perfil não consiga acessar a área do outro manipulando a URL.

---

## 2. Requisitos de Segurança (Obrigatórios)

- **Criptografia (Hashing):** O sistema deve utilizar os recursos nativos do Laravel para comparação de senhas em formato Hash. Em hipótese alguma o banco deve trafegar ou comparar senhas em texto puro.
- **Rate Limiting (Proteção contra Força Bruta):** Implementar bloqueio temporário por IP ou e-mail após um limite curto de tentativas falhas (ex: 5 erros seguidos).
- **Token CSRF:** O formulário obrigatoriamente deve conter a proteção nativa do Laravel contra falsificação de requisições.
- **Consultas Seguras:** O processo de busca do usuário no MySQL deve ser feito exclusivamente pelas ferramentas do framework (Eloquent ou Query Builder) para prevenir injeções de SQL.
- **Mensagens de Erro Ocultas:** Falhas de autenticação devem retornar apenas avisos genéricos como "Credenciais inválidas". O sistema não deve informar se o erro foi no e-mail ou na senha.
- **HTTPS:** A página deve estar preparada para operar obrigatoriamente sob certificado SSL.

---

## 3. Requisitos de Performance e Velocidade

- **Indexação no Banco:** A coluna de e-mail (ou a utilizada para identificação) na tabela de usuários do MySQL deve estar indexada para garantir buscas em milissegundos.
- **Frontend Enxuto:** O carregamento da página deve ser ultrarrápido. Carregar exclusivamente o CSS e os scripts necessários para o formulário. Bibliotecas pesadas do sistema interno não devem ser carregadas nesta tela.
- **Consultas Leves:** A verificação de login deve trazer do banco de dados apenas informações primárias (ID, hash da senha e regra de acesso). O carregamento de relacionamentos complexos deve ser adiado para o momento pós-login.

---

## 4. Experiência do Usuário (UX)

- **Prevenção de Duplo Clique:** Ao acionar o botão de "Entrar", o elemento deve mudar visualmente para um estado de "carregando" e ser desabilitado, evitando múltiplas requisições ao servidor.
- **Validação Prévia:** O formato do e-mail inserido deve ser validado no frontend antes de disparar a requisição ao backend.
- **Acessibilidade de Recursos:** O formulário deve dispor de forma clara das opções "Esqueci minha senha" (para o fluxo de recuperação) e "Lembrar de mim" (gerenciamento de sessão longa).

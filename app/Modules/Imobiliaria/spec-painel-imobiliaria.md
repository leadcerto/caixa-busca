# Especificação Funcional: Painel da Imobiliária Parceira

## 1. Contexto, Objetivo e Dor
**Contexto:** O sistema VLPHP atua como um hub de captação de leads. Cada estado brasileiro possui uma imobiliária parceira exclusiva responsável pelo atendimento dos imóveis daquela região.
**Objetivo:** Prover um ambiente centralizado, seguro e de **somente leitura** onde o parceiro possa auditar e recuperar leads vinculados ao seu estado.
**Dor:** Falhas pontuais em serviços de e-mail ou instabilidades no WhatsApp podem causar a perda de leads valiosos. O painel serve como a "Contingência Final", garantindo que 100% dos leads gerados estejam disponíveis para consulta manual.

---

## 2. Atores
*   **Imobiliária Parceira:** Usuário autenticado, vinculado a um ou mais Estados (UFs), com permissão exclusiva de visualização.

---

## 3. Pré-condições
*   A imobiliária deve estar previamente cadastrada no sistema pelo Administrador.
*   O registro da imobiliária deve conter o vínculo obrigatório com o(s) Estado(s) de sua responsabilidade.
*   Devem existir registros de leads na tabela `leads` que correspondam ao estado da imobiliária.

---

## 4. O Caminho Feliz (Linear)
1.  A imobiliária acessa a tela de login da plataforma.
2.  Após autenticação bem-sucedida, o sistema identifica o perfil `Imobiliária` e redireciona para o `Dashboard do Parceiro`.
3.  O Dashboard exibe um resumo simples (ex: Total de leads nas últimas 24h).
4.  O parceiro visualiza uma tabela listando os leads com as colunas:
    *   **Data/Hora:** Momento da captura.
    *   **Nome:** Nome completo do interessado.
    *   **E-mail:** Endereço eletrônico validado.
    *   **Telefone:** WhatsApp para contato.
    *   **Imóvel:** ID/Número do imóvel e breve descrição/link para visualização.
    *   **UTM Source:** Origem do lead (para transparência de marketing).
5.  O parceiro clica em um botão de "Copiar" ou simplesmente seleciona o texto para inserir no seu CRM interno.

---

## 5. A Matriz do Caos (Caminhos Tristes)
*   **Acesso Indevido:** Caso a imobiliária tente acessar via URL a lista de leads de um estado que não é de sua responsabilidade ou tente acessar rotas de `/admin`.
    *   **Solução:** Middleware de Autorização bloqueia a requisição e retorna `403 Forbidden`.
*   **Nenhum Lead Encontrado:** O estado não teve interessados no período.
    *   **Solução:** Exibição de *Blank Slate* informando: "Nenhum lead capturado para sua região até o momento".
*   **Esquecimento de Senha:** O parceiro perde o acesso.
    *   **Solução:** Fluxo padrão de `Forgot Password` do Laravel (Breeze/Fortify) enviando link de reset por e-mail.
*   **Imobiliária Desativada:** O administrador suspende a parceria.
    *   **Solução:** O login é bloqueado imediatamente, impedindo o acesso aos dados históricos.

---

## 6. Pós-condições
*   O parceiro obteve as informações necessárias para o atendimento comercial.
*   **Integridade do Sistema:** Nenhuma alteração foi feita no banco de dados (sem `create`, `update` ou `delete` nas tabelas de leads ou imóveis).
*   **Auditoria:** O acesso pode ser registrado em logs de sistema para controle de segurança.

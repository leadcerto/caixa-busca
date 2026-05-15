# Decisões de Arquitetura: Integrações de Terceiros

Este documento detalha os serviços e abordagens escolhidos para as integrações de terceiros do sistema, com foco em simplicidade, eficiência e controle de custos para a fase inicial do projeto.

---

## 1. Disparo de E-mails Transacionais

*   **Serviço Escolhido:** **Resend**
*   **Justificativa:** Adoção do Resend como provedor de e-mails transacionais. A escolha se baseia em sua API moderna, que facilita a integração, e em sua excelente reputação para garantir a entrega de e-mails críticos na caixa de entrada dos usuários (notificações de leads para imobiliárias, confirmações de cadastro para usuários e alertas para administradores). O plano gratuito inicial é suficiente para a fase de lançamento do projeto.

---

## 2. Comunicação via WhatsApp

*   **Serviço Escolhido:** **Nenhum (Uso Passivo)**
*   **Justificativa:** O sistema não realizará disparos ativos de mensagens via WhatsApp. A comunicação será estritamente passiva, onde o usuário inicia o contato através de um link (`wa.me`) que direciona para o aplicativo padrão do WhatsApp Business da empresa. Portanto, nenhuma integração de API de terceiros é necessária para esta funcionalidade.

---

## 3. Hospedagem de Imagens

*   **Serviço Escolhido:** **Servidor Local da Aplicação**
*   **Justificativa:** As imagens principais dos imóveis são hospedadas externamente e carregadas via link, não sendo armazenadas no sistema. As únicas imagens sob a gestão da aplicação são as de "destaque" (Open Graph), que são pequenas, leves e em número gerenciável. Por uma questão de simplicidade e otimização de custos, essas imagens serão armazenadas em uma pasta pública no próprio servidor da aplicação, eliminando a necessidade e a complexidade de um serviço de armazenamento de objetos externo.


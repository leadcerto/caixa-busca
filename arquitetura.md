# Arquitetura de Hospedagem e Implantação

Este documento detalha a arquitetura de hospedagem, a stack tecnológica e o fluxo de trabalho de implantação (CI/CD) definidos para este projeto.

## 1. Visão Geral da Arquitetura

O projeto foi desenhado com foco em simplicidade, baixo custo operacional e facilidade de manutenção, aproveitando uma stack de tecnologia robusta e um fluxo de trabalho de implantação automatizado.

- **Estrutura:** Monorepo
- **Hospedagem:** Hospedagem padrão na Hostinger (não-VPS)
- **Tecnologia:** PHP
- **Implantação:** Automatizada com GitHub Actions

## 2. Ambiente de Hospedagem

A decisão foi por **não utilizar um servidor VPS**. Em vez disso, a aplicação será hospedada em um plano de hospedagem padrão (Cloud/Shared) da Hostinger.

- **Provedor:** Hostinger
- **Plano:** Hospedagem Padrão (não-VPS)
- **Justificativa:**
  - **Redução de Custo:** Aproveita um plano já existente, eliminando a necessidade de contratar e manter um novo servidor.
  - **Simplicidade de Gestão:** A manutenção da infraestrutura do servidor (segurança, atualizações de sistema operacional) é gerenciada pela própria Hostinger.
  - **Alinhamento com a Stack:** Os planos de hospedagem padrão são altamente otimizados para aplicações PHP.

## 3. Stack Tecnológica

- **Backend:** **PHP**. A aplicação será construída como um monolito PHP padrão.
- **Banco de Dados:** **MySQL / MariaDB**. O banco de dados será o serviço já incluído no plano da Hostinger, gerenciado através do painel hPanel.
- **Estrutura do Projeto:** **Monorepo**. O código-fonte do backend e do frontend residirá no mesmo repositório para simplificar o versionamento e a gestão.

## 4. Fluxo de Implantação (Deployment Workflow)

O processo de publicação de novas versões do código no servidor de produção é **totalmente automatizado** utilizando **GitHub Actions**.

- **Gatilho (Trigger):** O workflow de implantação é acionado automaticamente a cada `git push` na branch `main` do repositório.
- **Ferramenta:** GitHub Actions, o sistema de automação nativo do GitHub.
- **Mecanismo:** Transferência de arquivos via **SFTP (Secure File Transfer Protocol)**.

### Processo Detalhado:

1.  O desenvolvedor finaliza uma alteração em sua máquina local.
2.  O código é enviado para o repositório no GitHub com o comando `git push origin main`.
3.  O push aciona o workflow definido no arquivo `.github/workflows/deploy.yml` dentro do repositório.
4.  O GitHub Action inicia um processo que se conecta de forma segura ao servidor da Hostinger usando credenciais de SFTP.
    -   *Nota: As credenciais (host, usuário, senha) devem ser armazenadas como **Secrets** nas configurações do repositório no GitHub para máxima segurança.*
5.  O Action sincroniza os arquivos do repositório com o diretório de produção no servidor (geralmente `public_html`).

### Diagrama do Fluxo:


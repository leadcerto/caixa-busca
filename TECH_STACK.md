# TECH_STACK.md

## Visão Geral Técnica

Este documento descreve a estrutura técnica, as tecnologias, os padrões arquiteturais e as estratégias de desenvolvimento adotadas neste projeto.

O objetivo deste arquivo é servir como referência para desenvolvedores humanos e agentes de IA que venham a dar continuidade ao projeto, garantindo consistência técnica, organização estrutural e clareza sobre as decisões arquiteturais já planejadas.

---

## Objetivo da Arquitetura

A arquitetura deste projeto foi pensada para ser:

- **Modular**
- **Escalável**
- **Organizada por responsabilidade**
- **Fácil de manter**
- **Preparada para evolução futura**
- **Compatível com desenvolvimento incremental**
- **Amigável para colaboração entre humanos e IA**

Mesmo que algumas partes do projeto ainda estejam vazias, incompletas ou funcionando como placeholders, a estrutura atual representa uma base planejada para futuras implementações.

---

## Estado Atual do Projeto

Atualmente, o projeto funciona como um **esqueleto arquitetural planejado**.

Isso significa que a estrutura de pastas, arquivos e separação de responsabilidades foi criada previamente para orientar o desenvolvimento futuro.

Nem todos os arquivos precisam conter implementação neste momento. Alguns arquivos podem existir apenas para indicar:

- Onde determinada responsabilidade deve ser implementada
- Como o projeto deve crescer futuramente
- Qual padrão arquitetural deve ser respeitado
- Como separar corretamente código, configuração, documentação e regras de negócio

Essa abordagem é intencional.

A presença de arquivos vazios, placeholders ou comentários explicativos não deve ser interpretada como erro, mas sim como parte da estratégia de organização inicial do projeto.

---

## Estratégia de Esqueleto Arquitetural

O projeto foi estruturado para permitir que futuras funcionalidades sejam adicionadas sem necessidade de reorganizar a base principal.

Essa estratégia ajuda a evitar crescimento desordenado do código e facilita a manutenção por diferentes colaboradores.

### Diretrizes dessa estratégia

- Manter a estrutura de pastas mesmo antes da implementação completa
- Usar arquivos placeholder quando necessário para preservar a organização
- Documentar a intenção de diretórios e módulos importantes
- Evitar misturar responsabilidades diferentes em um mesmo arquivo
- Priorizar clareza e previsibilidade na organização do projeto
- Permitir que humanos e agentes de IA entendam rapidamente onde cada coisa deve ser implementada

---

## Stack Técnica

> Esta seção deve refletir as tecnologias adotadas no projeto.  
> Caso alguma tecnologia ainda esteja em definição, ela deve ser marcada claramente como pendente ou planejada.

### Linguagem Principal

- **PHP 8.2+**
  - Utilizado como linguagem base para todo o desenvolvimento do sistema, aproveitando a maturidade e performance das versões recentes.
  - Framework: **Laravel 11**.

### Frontend

- **TALL Stack (Tailwind, Alpine.js, Laravel, Livewire)**
  - A abordagem escolhida é a de um "Monólito Moderno", mantendo a lógica de programação dentro do ecossistema PHP.
  - **Blade:** Motor de templates nativo do Laravel.
  - **Tailwind CSS:** Framework utility-first para design responsivo e moderno.
  - **Livewire:** Reatividade e dinamismo com lógica em PHP.
  - **Alpine.js:** Micro-interatividades no lado do cliente.

### Backend

- Estrutura preparada para regras de negócio, APIs, integrações e processamento de dados.
- A camada de backend deve manter regras de negócio separadas da interface e da infraestrutura.

Possíveis responsabilidades dessa camada:

- Serviços de aplicação
- Rotas de API
- Validações
- Integrações externas
- Regras de negócio
- Persistência de dados

### Banco de Dados

- **MySQL / MariaDB**
  - **Hospedagem:** Hostinger (Ambiente não-VPS / hPanel).
  - **Justificativa:** Aproveitamento do ambiente de hospedagem Hostinger, redução de custos, simplicidade de gestão e alinhamento total com a infraestrutura do Laravel.
  - O projeto utiliza Migrations e Eloquent ORM para garantir a portabilidade e consistência dos dados dentro do ecossistema Laravel.

### Estilização

A estilização deve seguir um padrão consistente e reutilizável.

Possíveis abordagens:

- CSS Modules
- Tailwind CSS
- Styled Components
- Sass
- CSS global organizado
- Design system próprio

A tecnologia final adotada deve ser documentada conforme o projeto evoluir.

### Testes

O projeto deve ser preparado para receber testes automatizados.

Tipos de testes recomendados:

- Testes unitários
- Testes de integração
- Testes de componentes
- Testes end-to-end, quando aplicável

Possíveis ferramentas:

- Jest
- Vitest
- Testing Library
- Playwright
- Cypress

### Qualidade de Código

Recomenda-se o uso de ferramentas de padronização e análise estática para manter consistência no projeto.

Ferramentas recomendadas:

- ESLint
- Prettier
- EditorConfig
- TypeScript
- Husky
- lint-staged

Essas ferramentas ajudam a manter o código limpo, previsível e padronizado.

---

## Organização Geral do Projeto

A estrutura do projeto deve seguir separação clara de responsabilidades.

Exemplo conceitual de organização:

    project-root/
    ├── docs/
    │   └── TECH_STACK.md
    ├── src/
    │   ├── components/
    │   ├── pages/
    │   ├── routes/
    │   ├── services/
    │   ├── utils/
    │   ├── hooks/
    │   ├── styles/
    │   └── config/
    ├── public/
    ├── tests/
    ├── README.md
    ├── CONTRIBUTING.md
    └── package.json

> A estrutura real pode variar de acordo com o framework, biblioteca ou arquitetura adotada.  
> O importante é manter a separação de responsabilidades e a coerência entre os diretórios.

---

## Padrões Arquiteturais

O projeto deve seguir padrões que favoreçam clareza, manutenção e evolução.

### Separação de Responsabilidades

Cada arquivo ou módulo deve ter uma responsabilidade clara.

Evitar arquivos que misturam:

- Interface
- Regra de negócio
- Comunicação com API
- Validação
- Configuração
- Manipulação direta de dados

Sempre que possível, separar essas responsabilidades em módulos específicos.

---

### Modularidade

Funcionalidades devem ser organizadas de forma modular.

Isso facilita:

- Reutilização
- Testes
- Refatoração
- Escalabilidade
- Trabalho em equipe
- Implementações futuras por IA

---

### Baixo Acoplamento

Os módulos devem depender o mínimo possível uns dos outros.

Quando houver comunicação entre partes do sistema, essa comunicação deve ser feita por interfaces claras, serviços ou camadas intermediárias.

---

### Alta Coesão

Arquivos e módulos devem agrupar apenas responsabilidades relacionadas.

Um módulo deve conter elementos que fazem sentido juntos e que pertencem ao mesmo contexto funcional.

---

## Convenções de Código

### Nomeação

Usar nomes claros, descritivos e consistentes.

Exemplos de boas práticas:

- Componentes com nomes em PascalCase
- Funções e variáveis com nomes em camelCase
- Arquivos utilitários com nomes descritivos
- Diretórios nomeados de acordo com sua responsabilidade

Exemplos conceituais:

- `UserCard`
- `useAuth`
- `formatCurrency`
- `apiClient`
- `userService`

---

### Comentários

Comentários devem ser usados para explicar intenção, contexto ou decisões importantes.

Evitar comentários óbvios que apenas repetem o que o código já mostra.

Comentários são especialmente úteis em:

- Arquivos placeholder
- Pontos de integração futura
- Decisões arquiteturais
- Regras de negócio complexas
- Trechos temporários que serão implementados depois

---

### Arquivos Placeholder

Arquivos placeholder são permitidos quando fazem parte da estrutura planejada.

Eles podem conter comentários explicativos indicando a intenção futura do arquivo.

Exemplo conceitual:

    // Este arquivo está reservado para futuras funções utilitárias relacionadas à formatação de dados.
    // Implementações devem ser adicionadas conforme necessidade do projeto.

Arquivos placeholder devem ser mantidos apenas quando ajudarem a preservar ou explicar a arquitetura.

---

## Estratégia para Desenvolvimento Futuro

Ao continuar o desenvolvimento do projeto, deve-se priorizar:

1. Preservar a estrutura arquitetural existente
2. Implementar funcionalidades no local correto
3. Evitar duplicação de responsabilidades
4. Documentar decisões técnicas relevantes
5. Manter consistência entre arquivos semelhantes
6. Atualizar este documento quando novas tecnologias forem adicionadas
7. Evitar remover arquivos estruturais sem entender sua finalidade

---

## Orientações para Agentes de IA

Este projeto pode ser desenvolvido ou continuado com auxílio de agentes de IA.

Ao trabalhar neste repositório, agentes de IA devem:

- Respeitar a estrutura existente
- Não remover arquivos vazios sem justificativa
- Não reorganizar diretórios sem necessidade clara
- Não misturar responsabilidades em um único arquivo
- Adicionar comentários quando criar placeholders
- Seguir os padrões descritos neste documento
- Atualizar documentação quando alterar decisões técnicas
- Preferir mudanças incrementais e bem localizadas

Caso uma IA encontre arquivos vazios, ela deve verificar se eles fazem parte do esqueleto arquitetural antes de sugerir remoção.

---

## Orientações para Desenvolvedores Humanos

Desenvolvedores humanos devem tratar este projeto como uma base planejada para evolução contínua.

Antes de alterar a estrutura principal, recomenda-se:

- Entender a função de cada diretório
- Verificar se há documentação relacionada
- Avaliar se a mudança preserva a organização do projeto
- Evitar apagar arquivos estruturais sem motivo técnico claro
- Atualizar a documentação quando necessário

---

## Atualização deste Documento

Este documento deve ser atualizado sempre que houver mudanças relevantes na stack ou arquitetura.

Exemplos de mudanças que exigem atualização:

- Inclusão de um novo framework
- Troca de biblioteca principal
- Definição de banco de dados
- Alteração na estratégia de testes
- Mudança na estrutura de pastas
- Inclusão de ferramentas de build, deploy ou CI/CD
- Definição de padrões de autenticação, autorização ou segurança

---

## Documentos Relacionados

Este documento faz parte da documentação principal do projeto.

Documentos complementares previstos:

- `README.md` — visão geral do projeto, instalação, uso e estrutura principal
- `CONTRIBUTING.md` — regras de contribuição, padrões e orientações para evolução do projeto

---

## Resumo

Este projeto foi preparado com uma estrutura técnica inicial para permitir crescimento organizado.

A existência de arquivos vazios, comentários explicativos e placeholders faz parte de uma estratégia consciente de planejamento arquitetural.

O desenvolvimento futuro deve respeitar essa base, preenchendo os módulos conforme a necessidade, sem comprometer a organização geral do projeto.

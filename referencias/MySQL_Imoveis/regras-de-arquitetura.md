Para garantir que a gestão, a integridade e a performance do banco de dados sejam os pilares inegociáveis do seu projeto (especialmente ao lidar com grandes volumes de dados como o CSV da Caixa), precisamos estabelecer regras rigorosas de arquitetura e infraestrutura.

Aqui está um manual focado exclusivamente em proteger o banco de dados, blindando-o contra más práticas de desenvolvimento e garantindo que o foco na gestão dos dados nunca seja perdido.

🚫 1. O Que o Desenvolvedor NUNCA Deve Fazer (Anti-patterns Críticos)
Para manter o foco na gestão do banco, as seguintes ações devem ser terminantemente proibidas:

NUNCA alterar o schema diretamente no SGBD (DataGrip, DBeaver, phpMyAdmin):
Regra: Toda e qualquer alteração estrutural (criar tabelas, adicionar colunas, mudar tipos) deve ser feita exclusivamente via Laravel Migrations. O banco de dados não pode ter modificações "fantasmas" que não estejam versionadas no código.
NUNCA desabilitar validações de Chave Estrangeira (Foreign Keys):
Regra: É proibido usar comandos como SET FOREIGN_KEY_CHECKS=0 para "facilitar" a importação de dados sujos. Se um dado não tem a referência correta (ex: um bairro sem município associado), ele deve ser rejeitado ou corrigido na origem, nunca forçado no banco.
NUNCA realizar consultas dentro de laços de repetição (Problema N+1):
Regra: Fazer um SELECT ou um firstOrCreate isolado para cada linha de um CSV de milhares de registros vai sobrecarregar o banco. Deve-se fazer cache em memória de domínios pequenos (ex: carregar todos os Estados e Municípios de uma vez) ou usar inserções/buscas em lote (upsert).
NUNCA inserir dados financeiros como VARCHAR ou FLOAT genérico:
Regra: Valores monetários (preços, avaliações, descontos) devem ser armazenados como DECIMAL(15,2) ou DECIMAL(12,2). O uso de strings impede cálculos no banco, e o uso de floats comuns causa erros de arredondamento.
NUNCA realizar operações em massa sem Transações (DB::transaction):
Regra: Se um lote de importação falhar no meio do processo, o banco não pode ficar com dados órfãos ou incompletos. Se der erro, o lote inteiro deve sofrer Rollback.
NUNCA apagar dados históricos fisicamente (DELETE):
Regra: O banco de dados imobiliário é histórico. O desenvolvedor deve usar exclusão lógica (Soft Deletes do Laravel) para imóveis que saíram do catálogo. Nunca apagar o registro físico, pois isso quebra o histórico de métricas e BI (Business Intelligence).
🛡️ 2. Diretrizes de Gestão e Saúde do Banco de Dados
Para garantir que a equipe mantenha o foco na gestão eficiente, implemente as seguintes diretrizes na cultura de desenvolvimento:

A. Estratégia de Indexação (Indexes)
O desenvolvedor deve criar índices em colunas que são frequentemente usadas em cláusulas WHERE, JOIN ou ordenação.
Exemplo obrigatório: Colunas como numero_original (usado para checar duplicatas na importação), id_municipio, id_estado e status precisam estar indexadas para evitar Full Table Scans (varreduras completas que matam a CPU do servidor de banco).
B. Isolamento de Responsabilidade (O Banco não é lixeira)
O banco de dados não deve corrigir dados formatados incorretamente. A camada de aplicação (os Jobs do Laravel) deve sanitizar tudo (remover acentos, padronizar maiúsculas/minúsculas, converter strings em números) antes da query chegar ao banco de dados.
O banco só deve receber o dado pronto, mastigado e no tipo primitivo correto.
C. Proteção contra Deadlocks (Travamento de Tabelas)
Como a importação pode rodar ao mesmo tempo que clientes navegam no site, o desenvolvedor deve configurar os Chunks (lotes) para tamanhos seguros (ex: 200 a 500 registros por vez).
Transações que demoram muitos minutos para fechar bloqueiam a tabela (Table Lock) e derrubam a aplicação em produção. Lotes rápidos e contínuos são a regra.
D. Separação de Dados Voláteis vs. Estruturais
Dados Estruturais (Tabela imoveis): Endereço, área, quartos. Mudam raramente.
Dados Voláteis (Tabela imovel_historicos): Preço, desconto, modalidade. Mudam a cada novo edital/CSV da Caixa.
O desenvolvedor deve manter essa normalização estrita. Juntar tudo em uma tabela só resulta em anomalias de atualização e perda de foco na arquitetura relacional.
E. Regra do Ambiente de Testes (Staging)
Nenhuma importação massiva de CSV pode ser criada ou testada conectada ao banco de produção. O desenvolvedor é obrigado a ter um dump parcial do banco para rodar localmente ou em um servidor de Staging.

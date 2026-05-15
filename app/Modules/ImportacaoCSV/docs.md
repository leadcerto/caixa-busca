# 📖 Módulo: Importação CSV (Caixa)

## Responsabilidade
Este módulo é responsável por ler, processar e higienizar o arquivo CSV de imóveis fornecido pela Caixa Econômica Federal, inserindo ou atualizando os registros no banco de dados.

## Regras de Negócio Críticas (Para a IA)
1. **Quebra da Descrição:** A coluna original de "Descrição" do CSV não pode ser salva crua. O sistema deve fazer um `split/explode` usando vírgulas.
2. **Extração do Tipo:** O primeiro item da descrição separada deve ser salvo na coluna `tipo_imovel` (ex: Casa, Apartamento).
3. **Extração de Atributos:** Usar Regex para identificar "quartos", "vagas", "suites" e mapear para colunas inteiras no banco (para otimizar os filtros de busca).
4. **Tolerância a Falhas:** Todo o processo deve estar encapsulado em `Try/Catch`. Falhas em linhas individuais não devem parar o script inteiro.
5. **Logs:** Qualquer erro deve ser registrado em um arquivo `error.log` na raiz do módulo ou do sistema.

## Endpoints
- `POST /api/importacao/processar` - Inicia o processamento do CSV.

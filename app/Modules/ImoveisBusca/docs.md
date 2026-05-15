# 📖 Módulo: Busca de Imóveis (Catálogo e Filtros)

## Responsabilidade
Este módulo gerencia a vitrine de imóveis. Ele recebe os parâmetros de busca do usuário, consulta a tabela de imóveis otimizada, anexa os dados do Dossiê do Bairro e retorna o pacote completo para a interface (Front-end).

## Regras de Negócio Críticas (Para a IA)
1. **Busca Dinâmica:** O sistema deve aceitar múltiplos parâmetros via Query String (ex: `?tipo=Casa&quartos_min=2&vagas=1&cidade=Rio de Janeiro`).
2. **Paginação Obrigatória:** NENHUMA query deve retornar todos os imóveis de uma vez. Implementar paginação severa (ex: 20 itens por página).
3. **Merge de Dados (Imóvel + Bairro):** Ao retornar o detalhe de um imóvel, o sistema deve buscar o JSON do módulo `BairrosDossie` e injetar na resposta para que a página de vendas seja rica em informações.
4. **Log de Pesquisas:** Cada busca realizada deve ser registrada de forma anônima ou vinculada ao usuário para gerar inteligência de mercado (quais bairros e tipos de imóveis são mais buscados).

## Endpoints
- `GET /api/imoveis/buscar` - Lista imóveis com filtros dinâmicos e paginação.
- `GET /api/imoveis/:id` - Retorna a página de vendas completa (Imóvel + Dossiê do Bairro).

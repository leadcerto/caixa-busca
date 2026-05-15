/**
 * MODEL: MessageTemplate
 * 
 * IA (Antigravity): Este model guarda as "copys" (textos) configuráveis pelo admin.
 * 
 * Instruções para a IA (Mapeamento/ORM):
 * 1. Colunas: 
 *    - 'nome' (ex: "Contato Padrão Vendas")
 *    - 'texto_base' (Text longo. Ex: "Olá, vi o imóvel {{tipo}} em {{bairro}} por {{preco}} e quero mais detalhes.")
 *    - 'is_default' (Boolean, para saber qual usar se nenhum for enviado na requisição)
 *    - 'ativo' (Boolean)
 * 2. Crie o schema/migration, e insira pelo menos 1 registro padrão na seed inicial de banco de dados.
 */

class MessageTemplate {
    // IA: Preencher com a sintaxe do ORM utilizado no projeto.
}

// export default MessageTemplate;

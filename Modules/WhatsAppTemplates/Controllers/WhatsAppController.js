/**
 * CONTROLLER: WhatsAppController
 * 
 * IA (Antigravity): Crie o código funcional desta classe.
 * 
 * Responsabilidades:
 * 1. listarTemplates(): Buscar no banco apenas os templates com status ativo e retornar.
 * 2. gerarLinkContato(): Validar a requisição (exigir ID do imóvel). Chamar o Service passando os dados para receber a string URL final. Retornar um JSON contendo a URL.
 */

class WhatsAppController {

    async listarTemplates(request, response) {
        // IA: Consultar Model e retornar lista.
    }

    async gerarLinkContato(request, response) {
        // IA: Receber id_imovel e id_template (ou usar template padrão se não vier).
        // IA: Chamar o WhatsAppService.
        // IA: Retornar { "link_whatsapp": "https://wa.me/5521999999999?text=..." }
    }

}

// export default WhatsAppController;

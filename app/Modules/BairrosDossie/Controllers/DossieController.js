/**
 * CONTROLLER: DossieController
 * 
 * IA (Antigravity): Crie o código funcional desta classe.
 * 
 * Responsabilidades:
 * 1. obterDossie(): Receber requisição com cidade e bairro, buscar no banco (Model BairroDossie) e retornar o campo JSON puro. Implementar cache (Redis ou similar) se possível.
 * 2. processarNovosBairros(): Iniciar o processo assíncrono que varre o banco buscando bairros sem dossiê e chama o ScrapingService para preenchê-los.
 */

class DossieController {

    async obterDossie(request, response) {
        // IA: Buscar no banco de dados e retornar HTTP 200 com o JSON do dossiê.
        // Se não encontrar, retornar HTTP 404 de forma amigável.
    }

    async processarNovosBairros(request, response) {
        // IA: Acionar o serviço de scraping (idealmente enviando para uma fila/queue).
        // Retornar HTTP 202 (Accepted) imediatamente.
    }

}

// export default DossieController;

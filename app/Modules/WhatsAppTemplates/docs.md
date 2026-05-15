\# 📖 Módulo: Templates de WhatsApp (Geração de Links Dinâmicos)

## Responsabilidade
Este módulo gerencia os textos persuasivos (templates) que serão enviados via WhatsApp. Ele mescla variáveis como `{{preco}}`, `{{bairro}}` e `{{quartos}}` com os dados reais do imóvel, gerando o link encurtado ou a URL oficial da API do WhatsApp (`wa.me`).

## Regras de Negócio Críticas (Para a IA)
1. **Sistema de Variáveis (Tags):** Os textos no banco de dados terão marcações (ex: `Olá, vi o imóvel no bairro {{bairro}} por {{preco}}`). O serviço deve ser capaz de fazer um *parse* (substituição) dessas tags pelos dados reais do imóvel.
2. **Encoding Obrigatório:** O texto final resultante da mescla DEVE passar por um processo de URL Encoding (`encodeURIComponent` ou equivalente na linguagem) para garantir que espaços, quebras de linha e caracteres especiais não quebrem o link do WhatsApp.
3. **Múltiplos Templates:** O sistema deve suportar diferentes "intenções". Exemplo: Template de Dúvida, Template de Agendamento de Visita, Template de Oferta Direta.
4. **Resiliência:** Se uma variável não existir no dado do imóvel (ex: não tem informação de `vagas`), a palavra/variável deve ser removida silenciosamente ou substituída por um espaço vazio, sem quebrar a mensagem.

## Endpoints
- `GET /api/whatsapp/templates` - Lista os modelos de mensagens ativos disponíveis.
- `POST /api/whatsapp/gerar-link` - Recebe o ID do Imóvel e o ID do Template, e retorna o link pronto para redirecionar o usuário.

# 📖 Módulo: Integração CRM (Captura e Envio de Leads)

## Responsabilidade
Este módulo é responsável por receber os contatos (leads) gerados através da página de vendas dos imóveis, salvar uma cópia de segurança no banco de dados local e enviar esses dados para a API do CRM externo utilizado pela imobiliária/corretor.

## Regras de Negócio Críticas (Para a IA)
1. **Resiliência (Garantia de Entrega):** O fluxo OBRIGATÓRIO é: Salvar no banco local -> Tentar enviar para o CRM. Se a API do CRM falhar ou der timeout, o status local do lead deve ficar como `pending_sync`.
2. **Processamento Assíncrono:** O envio para o CRM não deve travar a resposta do usuário no site. O usuário recebe a mensagem de "Sucesso" assim que o lead é salvo no banco local.
3. **Mapeamento de Campos:** O payload recebido do front-end deve ser sanitizado e mapeado para o padrão esperado pelo CRM (ex: transformar o `id_imovel` em uma "Oportunidade" ou "Tag" no CRM).
4. **Retry Pattern:** Deve existir um método/job que busca leads com status `pending_sync` e tenta enviá-los novamente.

## Endpoints
- `POST /api/crm/capturar-lead` - Recebe os dados do formulário do site.
- `POST /api/crm/forcar-sincronizacao` - Endpoint interno/admin para forçar o reenvio de leads travados.

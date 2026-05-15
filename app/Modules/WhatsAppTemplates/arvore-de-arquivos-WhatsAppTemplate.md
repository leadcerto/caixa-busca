📁 Modules/
   └── 📁 WhatsAppTemplates/
       │
       ├── 📄 docs.md                             # Documentação com regras de negócio de parser e encoding
       │
       ├── 📁 Routes/
       │   └── 📄 api_routes.js                   # Endpoints para listar templates e gerar o link do WhatsApp
       │
       ├── 📁 Controllers/
       │   └── 📄 WhatsAppController.js           # Recebe a requisição, chama o serviço e retorna a URL final
       │
       ├── 📁 Services/
       │   └── 📄 WhatsAppService.js              # Motor que substitui as variáveis ({{preco}}, {{bairro}}) e aplica URL Encode
       │
       └── 📁 Models/
           └── 📄 MessageTemplate.js              # Tabela do banco de dados que guarda os textos/copys dinâmicos

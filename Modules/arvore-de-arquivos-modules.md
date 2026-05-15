📁 Modules/
   │
   ├── 📁 ImportacaoCSV/                  # 1. MOTOR DE DADOS (Lê a planilha da Caixa, limpa e salva)
   │   ├── 📄 docs.md
   │   ├── 📁 Routes/
   │   │   └── 📄 api_routes.js
   │   ├── 📁 Controllers/
   │   │   └── 📄 ImportacaoController.js
   │   ├── 📁 Services/
   │   │   └── 📄 CsvParserService.js
   │   └── 📁 Models/
   │       └── 📄 Imovel.js
   │
   ├── 📁 BairrosDossie/                  # 2. ENRIQUECIMENTO (Gera inteligência e dados sobre a região)
   │   ├── 📄 docs.md
   │   ├── 📁 Routes/
   │   │   └── 📄 api_routes.js
   │   ├── 📁 Controllers/
   │   │   └── 📄 DossieController.js
   │   ├── 📁 Services/
   │   │   └── 📄 DossieBuilderService.js
   │   └── 📁 Models/
   │       └── 📄 BairroInfo.js
   │
   ├── 📁 ImoveisBusca/                   # 3. VITRINE E RASTREAMENTO (Filtros rápidos e log do que o cliente quer)
   │   ├── 📄 docs.md
   │   ├── 📁 Routes/
   │   │   └── 📄 api_routes.js
   │   ├── 📁 Controllers/
   │   │   └── 📄 BuscaController.js
   │   ├── 📁 Services/
   │   │   ├── 📄 FiltroService.js
   │   │   └── 📄 LogBuscaService.js
   │   └── 📁 Models/
   │       └── 📄 SearchLog.js
   │
   ├── 📁 IntegracaoCRM/                  # 4. CAPTURA BLINDADA (Salva o Lead localmente e manda pro CRM externo)
   │   ├── 📄 docs.md
   │   ├── 📁 Routes/
   │   │   └── 📄 api_routes.js
   │   ├── 📁 Controllers/
   │   │   └── 📄 CrmController.js
   │   ├── 📁 Services/
   │   │   └── 📄 CrmIntegrationService.js
   │   └── 📁 Models/
   │       └── 📄 LocalLead.js
   │
   └── 📁 WhatsAppTemplates/              # 5. CONVERSÃO E COPY (Gera links do WhatsApp com textos persuasivos dinâmicos)
       ├── 📄 docs.md
       ├── 📁 Routes/
       │   └── 📄 api_routes.js
       ├── 📁 Controllers/
       │   └── 📄 WhatsAppController.js
       ├── 📁 Services/
       │   └── 📄 WhatsAppService.js
       └── 📁 Models/
           └── 📄 MessageTemplate.js

# API REST — Imóveis da Caixa

Base URL: `https://venda.imoveisdacaixa.com.br/api`

**Rate limiting:** 60 requisições/minuto por IP em todos os endpoints públicos.  
**Formato:** JSON. Todas as respostas retornam `Content-Type: application/json`.

---

## Endpoints Públicos

### `GET /api/imoveis`

Lista imóveis ativos com paginação e filtros.

**Parâmetros de query (todos opcionais):**

| Parâmetro    | Tipo    | Exemplo       | Descrição                                   |
|--------------|---------|---------------|---------------------------------------------|
| `estado`     | string  | `RJ`          | Sigla do estado (UF)                        |
| `municipio`  | string  | `rio-de-janeiro` | Slug ou nome parcial do município         |
| `tipo`       | string  | `Apartamento` | Nome ou ID do tipo de imóvel               |
| `preco_min`  | number  | `100000`      | Preço mínimo de venda (R$)                 |
| `preco_max`  | number  | `500000`      | Preço máximo de venda (R$)                 |
| `quartos`    | integer | `2`           | Mínimo de quartos                          |
| `banheiros`  | integer | `1`           | Mínimo de banheiros                        |
| `garagens`   | integer | `1`           | Mínimo de vagas de garagem                 |
| `area_min`   | number  | `50`          | Área total mínima (m²)                     |
| `area_max`   | number  | `200`         | Área total máxima (m²)                     |
| `per_page`   | integer | `15`          | Itens por página (padrão: 15, máx: 100)   |
| `page`       | integer | `2`           | Página                                     |

**Resposta `200`:**
```json
{
  "data": [
    {
      "id": 1,
      "numero_original": "1444402960230",
      "slug": "apartamento-copacabana-rio-de-janeiro-rj-1444402960230",
      "status": "ativo",
      "tipo_imovel": "Apartamento",
      "localizacao": {
        "bairro": "Copacabana",
        "municipio": "Rio de Janeiro",
        "estado": "RJ"
      },
      "detalhes": {
        "area_total": 90.0,
        "quartos": 2,
        "banheiros": 2,
        "garagens": 1
      },
      "financeiro": {
        "valor_venda": 245000.0,
        "valor_avaliacao": 350000.0,
        "desconto_percentual": 30.0,
        "modalidade_venda": "Venda Online"
      },
      "imagens": {
        "foto_fachada": "https://venda-imoveis.caixa.gov.br/fotos/F144440296023021.jpg"
      },
      "link_matricula": "https://venda-imoveis.caixa.gov.br/editais/matricula/RJ/1444402960230.pdf",
      "criado_em": "2026-05-01T00:00:00+00:00"
    }
  ],
  "links": {
    "first": "https://venda.imoveisdacaixa.com.br/api/imoveis?page=1",
    "last": "https://venda.imoveisdacaixa.com.br/api/imoveis?page=10",
    "prev": null,
    "next": "https://venda.imoveisdacaixa.com.br/api/imoveis?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 10,
    "per_page": 15,
    "to": 15,
    "total": 150
  }
}
```

---

### `GET /api/imoveis/{slug}`

Retorna os detalhes completos de um imóvel pelo slug.

**Parâmetro de rota:**  
`slug` — string — slug do imóvel (ex: `apartamento-copacabana-rio-de-janeiro-rj-1444402960230`)

**Resposta `200`:**
```json
{
  "data": {
    "id": 1,
    "numero_original": "1444402960230",
    "slug": "apartamento-copacabana-rio-de-janeiro-rj-1444402960230",
    "status": "ativo",
    "tipo_imovel": "Apartamento",
    "endereco": {
      "logradouro": "Avenida Atlântica, 1702 Apt 501",
      "cep": "22021-001",
      "bairro": "Copacabana",
      "municipio": "Rio de Janeiro",
      "estado": "RJ"
    },
    "detalhes": {
      "area_total": 90.0,
      "area_privativa": 85.0,
      "area_terreno": null,
      "quartos": 2,
      "banheiros": 2,
      "salas": 1,
      "garagens": 1,
      "caracteristicas": ["varanda", "churrasqueira"]
    },
    "financeiro": {
      "valor_venda": 245000.0,
      "valor_avaliacao": 350000.0,
      "desconto_percentual": 30.0,
      "modalidade_venda": "Venda Online",
      "aceita_fgts": true,
      "aceita_financ_sbpe": false,
      "aceita_financ_mcmv": false
    },
    "imagens": {
      "foto_fachada": "https://venda-imoveis.caixa.gov.br/fotos/F144440296023021.jpg",
      "foto_destaque": "https://venda.imoveisdacaixa.com.br/images/imoveis/apartamento-copacabana-rj.jpg"
    },
    "link_caixa": "https://venda-imoveis.caixa.gov.br/sistema/detalhe-imovel.asp?hdnimovel=1444402960230",
    "link_matricula": "https://venda-imoveis.caixa.gov.br/editais/matricula/RJ/1444402960230.pdf",
    "historico_precos": [
      {
        "valor_venda": 245000.0,
        "valor_avaliacao": 350000.0,
        "desconto": 30.0,
        "modalidade": "Venda Online",
        "data_registro": "2026-05-01T00:00:00+00:00"
      }
    ],
    "dossie_bairro": {
      "titulo": "Copacabana — Um dos bairros mais valorizados do Rio de Janeiro",
      "meta_description": "Imóveis em Copacabana com excelente infraestrutura...",
      "texto": "Copacabana é um bairro...",
      "gerado_em": "2026-05-10T12:00:00+00:00"
    },
    "criado_em": "2026-05-01T00:00:00+00:00",
    "atualizado_em": "2026-05-20T08:00:00+00:00"
  }
}
```

**Resposta `404`:** imóvel não encontrado ou inativo.

---

### `GET /api/estados`

Lista todos os estados com contagem de imóveis ativos.

**Resposta `200`:**
```json
{
  "data": [
    { "id": 1, "nome": "Rio de Janeiro", "uf": "RJ", "total_imoveis": 1240 },
    { "id": 2, "nome": "São Paulo",      "uf": "SP", "total_imoveis": 3580 }
  ]
}
```

---

### `GET /api/municipios`

Lista municípios com contagem de imóveis ativos.

**Parâmetros de query (opcionais):**

| Parâmetro | Tipo   | Exemplo | Descrição              |
|-----------|--------|---------|------------------------|
| `estado`  | string | `SP`    | Filtra por UF do estado |

**Resposta `200`:**
```json
{
  "data": [
    {
      "id": 10,
      "id_estado": 2,
      "nome": "São Paulo",
      "estado": { "id": 2, "nome": "São Paulo", "uf": "SP" },
      "total_imoveis": 2100
    }
  ]
}
```

---

### `POST /api/leads`

Registra o interesse de um lead em um imóvel. Cria ou atualiza o lead, cria o atendimento, dispara webhook CRM e retorna URL do WhatsApp pré-preenchida.

**Rate limiting:** 5 requisições/minuto por IP (independente do limite global de 60/min).

**Body (JSON):**
```json
{
  "nome":        "João Silva",
  "email":       "joao@email.com",
  "telefone":    "5521999990000",
  "imovel_id":   "1444402960230",
  "utm_source":  "google",
  "utm_medium":  "cpc",
  "utm_campaign": "imoveis-rj",
  "utm_term":    "apartamento copacabana",
  "utm_content": "banner-topo"
}
```

**Campos obrigatórios:** `nome`, `email`, `telefone`, `imovel_id`  
**`imovel_id`:** aceita `numero_original` (ex: `"1444402960230"`) ou `slug` do imóvel.  
**UTMs:** todos opcionais, usados para rastreamento de conversão no CRM.

**Resposta `201`:**
```json
{
  "success": true,
  "message": "Lead e atendimento convertidos com sucesso!",
  "data": {
    "lead_id": 42,
    "atendimento_id": 87,
    "lead_was_created": true,
    "atendimento_was_created": true,
    "whatsapp_text": "Olá! Meu nome é João Silva. Tenho interesse no Apartamento (Cód: 1444402960230) em Copacabana, Rio de Janeiro, RJ. Pode me ajudar?",
    "whatsapp_url": "https://api.whatsapp.com/send?phone=5521999999999&text=..."
  }
}
```

**Erros:**

| Código | Motivo                                    |
|--------|-------------------------------------------|
| `422`  | Validação falhou (campos obrigatórios)    |
| `404`  | `imovel_id` não corresponde a nenhum imóvel |
| `429`  | Muitas tentativas — aguarde X segundos   |

---

## Códigos de Erro Genéricos

| Código | Significado                                  |
|--------|----------------------------------------------|
| `200`  | OK                                           |
| `201`  | Criado com sucesso                           |
| `404`  | Recurso não encontrado                       |
| `422`  | Erro de validação — `errors` com detalhes   |
| `429`  | Rate limit atingido                          |
| `500`  | Erro interno — reportar ao suporte          |

---

## Notas de Integração

- **Paginação:** todos os endpoints de listagem retornam `links` e `meta` com navegação automática.
- **Imagens:** a URL `foto_fachada` aponta diretamente para o servidor da Caixa Econômica Federal no padrão `https://venda-imoveis.caixa.gov.br/fotos/F{numero_13_digitos}21.jpg`.
- **Atualização de dados:** o CSV da Caixa é importado mensalmente. O campo `atualizado_em` reflete a data da última importação.
- **Autenticação:** todos os endpoints listados acima são públicos. Não é necessário token.

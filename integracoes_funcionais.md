# Arquitetura: Fontes de Dados e Enriquecimento

Este documento descreve as fontes de dados externas e os serviços de enriquecimento utilizados para popular e qualificar as informações no banco de dados do sistema.

---

## 1. Dados de Imóveis (Scraping)

*   **Fonte:** API fornecida pela [Nome da Empresa de Scraping Contratada].
*   **Justificativa:** O núcleo de dados dos imóveis será obtido através de um serviço de scraping contratado, que monitora o site de origem e entrega os dados de forma estruturada via API. Isso desacopla a responsabilidade de coleta e manutenção do robô de scraping do nosso sistema principal.

---

## 2. Consulta de Endereço (API de CEP)

*   **Fonte:** API Pública de CEP (Ex: ViaCEP, BrasilAPI ou Busca CEP).
*   **Justificativa:** Para garantir a padronização e a qualidade dos dados de endereço, o sistema utilizará uma API externa para autocompletar e validar informações de endereço a partir do CEP informado no cadastro do imóvel.

---

## 3. Enriquecimento de Dados Geográficos com IA

*   **Fonte:** Modelo de Linguagem Grande (LLM) via API.
*   **Justificativa:** Para agregar valor aos anúncios, o sistema fará requisições a uma API de IA para gerar descrições ricas e informações relevantes sobre os bairros, cidades e estados. Esses dados serão armazenados no banco de dados para serem exibidos nas páginas dos imóveis, enriquecendo a experiência do usuário.

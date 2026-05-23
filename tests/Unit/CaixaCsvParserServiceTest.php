<?php

namespace Tests\Unit;

use App\Modules\ImportacaoCSV\Services\CaixaCsvParserService;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class CaixaCsvParserServiceTest extends TestCase
{
    private CaixaCsvParserService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CaixaCsvParserService();
    }

    private function invokePrivate(string $method, mixed ...$args): mixed
    {
        $m = new ReflectionMethod(CaixaCsvParserService::class, $method);
        $m->setAccessible(true);
        return $m->invoke($this->service, ...$args);
    }

    // -------------------------------------------------------------------------
    // cleanDecimal
    // -------------------------------------------------------------------------

    public function test_clean_decimal_formato_br_ponto_milhar_virgula_decimal(): void
    {
        $this->assertEquals(1500.00, $this->invokePrivate('cleanDecimal', '1.500,00'));
    }

    public function test_clean_decimal_formato_br_sem_milhar(): void
    {
        $this->assertEquals(70.46, $this->invokePrivate('cleanDecimal', '70,46'));
    }

    public function test_clean_decimal_formato_en_virgula_milhar_ponto_decimal(): void
    {
        $this->assertEquals(51111.93, $this->invokePrivate('cleanDecimal', '51,111.93'));
    }

    public function test_clean_decimal_inteiro(): void
    {
        $this->assertEquals(150000.0, $this->invokePrivate('cleanDecimal', '150000'));
    }

    public function test_clean_decimal_vazio_retorna_zero(): void
    {
        $this->assertEquals(0.0, $this->invokePrivate('cleanDecimal', ''));
    }

    public function test_clean_decimal_grande_valor_com_milhar(): void
    {
        $this->assertEquals(1234567.89, $this->invokePrivate('cleanDecimal', '1.234.567,89'));
    }

    // -------------------------------------------------------------------------
    // extractHdnimovel
    // -------------------------------------------------------------------------

    public function test_extrai_hdnimovel_da_url(): void
    {
        $url = 'https://venda-imoveis.caixa.gov.br/imoveis/detalhe-imovel?hdnimovel=123456789';
        $this->assertEquals('123456789', $this->invokePrivate('extractHdnimovel', $url));
    }

    public function test_extrai_hdnimovel_case_insensitive(): void
    {
        $url = 'https://venda-imoveis.caixa.gov.br/imoveis/detalhe-imovel?HDNIMOVEL=987654321';
        $this->assertEquals('987654321', $this->invokePrivate('extractHdnimovel', $url));
    }

    public function test_retorna_null_se_hdnimovel_ausente(): void
    {
        $this->assertNull($this->invokePrivate('extractHdnimovel', 'https://exemplo.com/sem-parametro'));
    }

    public function test_retorna_null_para_link_vazio(): void
    {
        $this->assertNull($this->invokePrivate('extractHdnimovel', ''));
    }

    // -------------------------------------------------------------------------
    // parseBairro
    // -------------------------------------------------------------------------

    public function test_parse_bairro_simples(): void
    {
        $result = $this->invokePrivate('parseBairro', 'Centro');
        $this->assertEquals('Centro', $result['bairro']);
        $this->assertNull($result['sub_bairro']);
    }

    public function test_parse_bairro_com_sub_bairro_entre_parenteses(): void
    {
        $result = $this->invokePrivate('parseBairro', 'Barra da Tijuca (Recreio dos Bandeirantes)');
        $this->assertEquals('Barra da Tijuca', $result['bairro']);
        $this->assertEquals('Recreio dos Bandeirantes', $result['sub_bairro']);
    }

    // -------------------------------------------------------------------------
    // parseDescription
    // -------------------------------------------------------------------------

    public function test_parse_description_extrai_quartos(): void
    {
        $result = $this->invokePrivate('parseDescription', 'Apartamento, 65,50 m², 2 quartos, 1 vaga de garagem.');
        $this->assertEquals(2, $result['quartos']);
    }

    public function test_parse_description_extrai_area(): void
    {
        $result = $this->invokePrivate('parseDescription', 'Casa, 120,00 m², 3 quartos.');
        $this->assertEquals(120.00, $result['area_total']);
    }

    public function test_parse_description_extrai_vagas(): void
    {
        $result = $this->invokePrivate('parseDescription', 'Apartamento, 80 m², 3 quartos, 2 vagas de garagem.');
        $this->assertEquals(2, $result['vagas']);
    }

    public function test_parse_description_extrai_tipo(): void
    {
        $result = $this->invokePrivate('parseDescription', 'Terreno, 300 m².');
        $this->assertEquals('Terreno', $result['tipo_imovel']);
    }

    // -------------------------------------------------------------------------
    // slugify
    // -------------------------------------------------------------------------

    public function test_slugify_remove_acentos(): void
    {
        $result = $this->invokePrivate('slugify', 'São Paulo');
        $this->assertEquals('sao-paulo', $result);
    }

    public function test_slugify_remove_cedilha(): void
    {
        $result = $this->invokePrivate('slugify', 'Praça da Sé');
        $this->assertEquals('praca-da-se', $result);
    }

    public function test_slugify_substitui_espacos_por_hifens(): void
    {
        $result = $this->invokePrivate('slugify', 'Rio de Janeiro');
        $this->assertEquals('rio-de-janeiro', $result);
    }
}

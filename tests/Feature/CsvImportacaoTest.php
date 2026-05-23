<?php

namespace Tests\Feature;

use App\Models\ImovelGrupo;
use App\Models\ModalidadeVenda;
use App\Modules\ImportacaoCSV\Services\CaixaCsvParserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CsvImportacaoTest extends TestCase
{
    use RefreshDatabase;

    private function csvFixture(string $numero = '12345678', string $preco = '150000,00'): string
    {
        return implode("\n", [
            '"Data de Geração: 15/05/2026"',
            'N° do imóvel;UF;Cidade;Bairro;Endereço;Preço;Valor de avaliação;Desconto;Financiamento;Descrição;Modalidade de venda;Link de acesso',
            '', // linha 3 ignorada
            "{$numero};SP;São Paulo;Centro;Rua das Flores, 100;{$preco};180000,00;16,67;Sim;Apartamento, 65,50 m², 2 quartos, 1 vaga de garagem;Venda Direta Online;https://venda-imoveis.caixa.gov.br/imoveis/detalhe-imovel?hdnimovel={$numero}",
        ]);
    }

    private function seedPrerequisitos(): void
    {
        ModalidadeVenda::create(['nome' => 'Venda Direta Online']);
        ImovelGrupo::create([
            'nome'          => 'Grupo 1',
            'valor_minimo'  => 0,
            'valor_maximo'  => 500000,
            'ativo'         => true,
        ]);
    }

    private function processarCsv(string $conteudo): void
    {
        $path = tempnam(sys_get_temp_dir(), 'caixa_test_') . '.csv';
        file_put_contents($path, mb_convert_encoding($conteudo, 'ISO-8859-1', 'UTF-8'));

        try {
            app(CaixaCsvParserService::class)->process($path);
        } finally {
            @unlink($path);
        }
    }

    public function test_importa_imovel_do_csv(): void
    {
        $this->seedPrerequisitos();

        $this->processarCsv($this->csvFixture('99887766'));

        $this->assertDatabaseHas('imoveis', [
            'numero_original' => '99887766',
            'foto_fachada_url' => 'https://venda-imoveis.caixa.gov.br/fotos/F000009988776621.jpg',
        ]);
    }

    public function test_cria_historico_de_preco(): void
    {
        $this->seedPrerequisitos();

        $this->processarCsv($this->csvFixture('77665544', '250000,00'));

        $imovel = \App\Models\Imovel::where('numero_original', '77665544')->firstOrFail();
        $this->assertDatabaseHas('imoveis_historico', [
            'id_imovel'   => $imovel->id,
            'valor_venda' => 250000.00,
        ]);
    }

    public function test_cria_estado_e_municipio_automaticamente(): void
    {
        $this->seedPrerequisitos();

        $this->processarCsv($this->csvFixture('55443322'));

        $this->assertDatabaseHas('estados', ['uf' => 'SP']);
        $this->assertDatabaseHas('municipios', ['nome' => 'São Paulo']);
    }

    public function test_nao_duplica_imovel_em_reimportacao(): void
    {
        $this->seedPrerequisitos();
        $csv = $this->csvFixture('33221100');

        $this->processarCsv($csv);
        $this->processarCsv($csv);

        $this->assertEquals(1, \App\Models\Imovel::where('numero_original', '33221100')->count());
    }

    public function test_gera_slug_unico_para_o_imovel(): void
    {
        $this->seedPrerequisitos();

        $this->processarCsv($this->csvFixture('11223344'));

        $imovel = \App\Models\Imovel::where('numero_original', '11223344')->firstOrFail();
        $this->assertNotNull($imovel->slug);
        $this->assertMatchesRegularExpression('/^[a-z0-9-]+$/', $imovel->slug);
    }

    public function test_extrai_numero_do_url_hdnimovel(): void
    {
        $this->seedPrerequisitos();

        // Simula CSV com notação científica no número mas URL correta
        $csv = implode("\n", [
            '"Data de Geração: 15/05/2026"',
            'N° do imóvel;UF;Cidade;Bairro;Endereço;Preço;Valor de avaliação;Desconto;Financiamento;Descrição;Modalidade de venda;Link de acesso',
            '',
            '1.44442E+12;SP;São Paulo;Centro;Rua A, 1;200000,00;240000,00;16,67;Sim;Apartamento, 50 m², 1 quarto;Venda Direta Online;https://venda-imoveis.caixa.gov.br/imoveis/detalhe-imovel?hdnimovel=1444420000000',
        ]);

        $this->processarCsv($csv);

        $this->assertDatabaseHas('imoveis', ['numero_original' => '1444420000000']);
    }

    public function test_gera_link_matricula_correto(): void
    {
        $this->seedPrerequisitos();

        $this->processarCsv($this->csvFixture('8555510834062'));

        $imovel = \App\Models\Imovel::where('numero_original', '8555510834062')->firstOrFail();
        $this->assertEquals(
            'https://venda-imoveis.caixa.gov.br/editais/matricula/SP/8555510834062.pdf',
            $imovel->link_matricula
        );
    }

    public function test_permite_apenas_venda_online_e_venda_direta_online(): void
    {
        $this->seedPrerequisitos();
        ModalidadeVenda::create(['nome' => 'Venda Online']);
        ModalidadeVenda::create(['nome' => 'Leilão SFI - Edital Único']);

        // 1. Test allowed modality: Venda Online
        $csvVendaOnline = implode("\n", [
            '"Data de Geração: 15/05/2026"',
            'N° do imóvel;UF;Cidade;Bairro;Endereço;Preço;Valor de avaliação;Desconto;Financiamento;Descrição;Modalidade de venda;Link de acesso',
            '',
            "11111111;SP;São Paulo;Centro;Rua das Flores, 100;150000,00;180000,00;16,67;Sim;Apartamento, 65,50 m²;Venda Online;https://venda-imoveis.caixa.gov.br/imoveis/detalhe-imovel?hdnimovel=11111111",
        ]);
        $this->processarCsv($csvVendaOnline);
        $this->assertDatabaseHas('imoveis', ['numero_original' => '11111111']);

        // 2. Test disallowed modality: Leilão SFI - Edital Único
        $csvLeilao = implode("\n", [
            '"Data de Geração: 15/05/2026"',
            'N° do imóvel;UF;Cidade;Bairro;Endereço;Preço;Valor de avaliação;Desconto;Financiamento;Descrição;Modalidade de venda;Link de acesso',
            '',
            "22222222;SP;São Paulo;Centro;Rua das Flores, 100;150000,00;180000,00;16,67;Sim;Apartamento, 65,50 m²;Leilão SFI - Edital Único;https://venda-imoveis.caixa.gov.br/imoveis/detalhe-imovel?hdnimovel=22222222",
        ]);
        $this->processarCsv($csvLeilao);
        $this->assertDatabaseMissing('imoveis', ['numero_original' => '22222222']);
    }
}

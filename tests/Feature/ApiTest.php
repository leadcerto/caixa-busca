<?php

namespace Tests\Feature;

use App\Models\Atendimento;
use App\Models\Bairro;
use App\Models\Estado;
use App\Models\Imovel;
use App\Models\ImovelHistorico;
use App\Models\Lead;
use App\Models\Municipio;
use App\Models\TipoImovel;
use App\Modules\Imoveis\Jobs\DispatchCrmWebhookJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    private function createImovel(array $attributes = []): Imovel
    {
        $valorVenda = $attributes['valor_venda'] ?? 150000.00;
        $valorAvaliacao = $attributes['valor_avaliacao'] ?? 200000.00;
        unset($attributes['valor_venda'], $attributes['valor_avaliacao']);

        $imovel = Imovel::factory()->create(array_merge([
            'status' => 'ativo',
        ], $attributes));

        ImovelHistorico::factory()->create([
            'id_imovel' => $imovel->id,
            'valor_venda' => $valorVenda,
            'valor_avaliacao' => $valorAvaliacao,
        ]);

        return $imovel->load(['estado', 'municipio', 'bairro', 'tipoImovel', 'ultimoHistorico']);
    }

    public function test_api_imoveis_returns_paginated_list(): void
    {
        $this->createImovel();
        $this->createImovel();

        $response = $this->getJson(route('api.imoveis.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'numero_original',
                        'slug',
                        'status',
                        'tipo_imovel',
                        'localizacao' => ['bairro', 'municipio', 'estado'],
                        'detalhes' => ['area_total', 'quartos', 'banheiros', 'garagens'],
                        'financeiro' => ['valor_venda', 'valor_avaliacao', 'desconto_percentual', 'modalidade_venda'],
                        'imagens' => ['foto_fachada'],
                        'link_matricula',
                        'criado_em',
                    ]
                ],
                'links',
                'meta'
            ]);
    }

    public function test_api_imoveis_filters_by_state_and_municipality(): void
    {
        $estadoSP = Estado::factory()->create(['uf' => 'SP']);
        $estadoRJ = Estado::factory()->create(['uf' => 'RJ']);

        $muniSP = Municipio::factory()->create(['id_estado' => $estadoSP->id, 'nome' => 'São Paulo', 'slug' => 'sao-paulo']);
        $muniRJ = Municipio::factory()->create(['id_estado' => $estadoRJ->id, 'nome' => 'Rio de Janeiro', 'slug' => 'rio-de-janeiro']);

        $this->createImovel(['id_estado' => $estadoSP->id, 'id_municipio' => $muniSP->id]);
        $this->createImovel(['id_estado' => $estadoRJ->id, 'id_municipio' => $muniRJ->id]);

        // Filter SP
        $response = $this->getJson(route('api.imoveis.index', ['estado' => 'SP']));
        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));

        // Filter Municipality
        $response = $this->getJson(route('api.imoveis.index', ['municipio' => 'rio-de-janeiro']));
        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
    }

    public function test_api_imoveis_filters_by_price_range(): void
    {
        $this->createImovel(['valor_venda' => 100000.00]); // cheap
        $this->createImovel(['valor_venda' => 500000.00]); // expensive

        // Cheap only
        $response = $this->getJson(route('api.imoveis.index', ['preco_max' => 200000.00]));
        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals(100000.00, $response->json('data.0.financeiro.valor_venda'));

        // Expensive only
        $response = $this->getJson(route('api.imoveis.index', ['preco_min' => 400000.00]));
        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals(500000.00, $response->json('data.0.financeiro.valor_venda'));
    }

    public function test_api_imovel_detail_returns_rich_payload_with_dossie(): void
    {
        $bairro = Bairro::factory()->create([
            'nome' => 'Centro',
            'conteudo_ia' => [
                'titulo' => 'Dossiê do Centro',
                'meta_description' => 'Tudo sobre o Centro',
                'texto' => 'O Centro é um excelente bairro histórico para se viver.'
            ],
            'ia_gerado_em' => now(),
        ]);

        $imovel = $this->createImovel([
            'id_bairro' => $bairro->id,
            'quartos' => 3,
            'banheiros' => 2,
            'piscina' => true,
        ]);

        $response = $this->getJson(route('api.imoveis.show', ['slug' => $imovel->slug]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'numero_original',
                    'slug',
                    'status',
                    'tipo_imovel',
                    'endereco' => ['logradouro', 'cep', 'bairro', 'municipio', 'estado'],
                    'detalhes' => ['area_total', 'area_privativa', 'area_terreno', 'quartos', 'banheiros', 'salas', 'garagens', 'caracteristicas'],
                    'financeiro' => ['valor_venda', 'valor_avaliacao', 'desconto_percentual', 'modalidade_venda', 'aceita_fgts', 'aceita_financ_sbpe', 'aceita_financ_mcmv'],
                    'imagens' => ['foto_fachada', 'foto_destaque'],
                    'link_caixa',
                    'link_matricula',
                    'historico_precos',
                    'dossie_bairro' => ['titulo', 'meta_description', 'texto', 'gerado_em'],
                    'criado_em',
                    'atualizado_em'
                ]
            ]);

        $this->assertEquals('Dossiê do Centro', $response->json('data.dossie_bairro.titulo'));
        $this->assertContains('piscina', $response->json('data.detalhes.caracteristicas'));
    }

    public function test_api_estados_and_municipios_return_lists(): void
    {
        $estado = Estado::factory()->create(['uf' => 'SP', 'nome' => 'São Paulo']);
        Municipio::factory()->create(['id_estado' => $estado->id, 'nome' => 'Campinas', 'slug' => 'campinas']);

        // States
        $response = $this->getJson(route('api.estados'));
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'nome', 'uf', 'total_imoveis']
                ]
            ]);

        // Municipalities
        $response = $this->getJson(route('api.municipios', ['estado' => 'SP']));
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'id_estado', 'nome', 'estado', 'total_imoveis']
                ]
            ]);
    }

    public function test_api_leads_convert_captures_interest_and_triggers_jobs(): void
    {
        Queue::fake();

        $imovel = $this->createImovel();

        $payload = [
            'nome' => 'Maria Silva',
            'email' => 'maria@exemplo.com',
            'telefone' => '11988888888',
            'imovel_id' => $imovel->numero_original,
            'utm_source' => 'google',
            'utm_medium' => 'cpc',
        ];

        $response = $this->postJson(route('api.leads.convert'), $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'lead_id',
                    'atendimento_id',
                    'lead_was_created',
                    'atendimento_was_created',
                    'whatsapp_text',
                    'whatsapp_url'
                ]
            ]);

        $this->assertDatabaseHas('leads', ['email' => 'maria@exemplo.com', 'nome' => 'Maria Silva']);
        $this->assertDatabaseHas('atendimentos', ['id_imovel' => $imovel->id]);

        Queue::assertPushed(DispatchCrmWebhookJob::class);
    }

    public function test_api_leads_convert_validation_errors(): void
    {
        $payload = [
            'nome' => '',
            'email' => 'nao-e-email',
            'telefone' => 'curto',
            'imovel_id' => ''
        ];

        $response = $this->postJson(route('api.leads.convert'), $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nome', 'email', 'telefone', 'imovel_id']);
    }

    public function test_api_leads_convert_imovel_not_found(): void
    {
        $payload = [
            'nome' => 'Maria Silva',
            'email' => 'maria@exemplo.com',
            'telefone' => '11988888888',
            'imovel_id' => 'nao-existe-12345'
        ];

        $response = $this->postJson(route('api.leads.convert'), $payload);

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Imóvel não encontrado.']);
    }
}

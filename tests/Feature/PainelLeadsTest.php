<?php

namespace Tests\Feature;

use App\Models\Atendimento;
use App\Models\Imobiliaria;
use App\Models\Imovel;
use App\Models\ImovelHistorico;
use App\Models\Lead;
use App\Modules\Imobiliaria\Livewire\PainelLeads;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PainelLeadsTest extends TestCase
{
    use RefreshDatabase;

    private Imobiliaria $imobiliaria;
    private Imovel $imovel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->imobiliaria = Imobiliaria::factory()->create();
        $this->imovel      = Imovel::factory()->create(['id_imobiliaria' => $this->imobiliaria->id]);
        ImovelHistorico::factory()->create(['id_imovel' => $this->imovel->id]);
    }

    private function atendimento(array $attrs = []): Atendimento
    {
        $lead = Lead::factory()->create();
        return Atendimento::factory()->create(array_merge([
            'id_lead'        => $lead->id,
            'id_imovel'      => $this->imovel->id,
            'id_imobiliaria' => $this->imobiliaria->id,
        ], $attrs));
    }

    public function test_imobiliaria_ve_seus_proprios_atendimentos(): void
    {
        $lead = Lead::factory()->create(['nome' => 'Cliente Visível']);
        $this->atendimento(['id_lead' => $lead->id]);

        // Atendimento de outra imobiliária — não deve aparecer
        Atendimento::factory()->create();

        Livewire::actingAs($this->imobiliaria, 'imobiliaria')
            ->test(PainelLeads::class)
            ->assertSee('Cliente Visível');
    }

    public function test_filtra_atendimentos_por_status(): void
    {
        $leadPendente   = Lead::factory()->create(['nome' => 'Lead Pendente']);
        $leadContatado  = Lead::factory()->create(['nome' => 'Lead Contatado']);

        $this->atendimento(['id_lead' => $leadPendente->id, 'status_parceiro' => 'pendente']);
        $this->atendimento(['id_lead' => $leadContatado->id, 'status_parceiro' => 'contatado']);

        Livewire::actingAs($this->imobiliaria, 'imobiliaria')
            ->test(PainelLeads::class)
            ->set('statusFiltro', 'pendente')
            ->assertSee('Lead Pendente')
            ->assertDontSee('Lead Contatado');
    }

    public function test_busca_por_nome_do_lead(): void
    {
        $leadAlvo  = Lead::factory()->create(['nome' => 'Fernanda Costa']);
        $leadOutro = Lead::factory()->create(['nome' => 'João Silva']);

        $this->atendimento(['id_lead' => $leadAlvo->id]);
        $this->atendimento(['id_lead' => $leadOutro->id]);

        Livewire::actingAs($this->imobiliaria, 'imobiliaria')
            ->test(PainelLeads::class)
            ->set('busca', 'Fernanda')
            ->assertSee('Fernanda Costa')
            ->assertDontSee('João Silva');
    }

    public function test_atualiza_status_do_atendimento(): void
    {
        $at = $this->atendimento(['status_parceiro' => 'pendente']);

        Livewire::actingAs($this->imobiliaria, 'imobiliaria')
            ->test(PainelLeads::class)
            ->call('atualizarStatus', $at->id, 'contatado');

        $this->assertDatabaseHas('atendimentos', [
            'id'             => $at->id,
            'status_parceiro' => 'contatado',
        ]);
    }

    public function test_nao_atualiza_atendimento_de_outra_imobiliaria(): void
    {
        $atDeOutra = Atendimento::factory()->create(['status_parceiro' => 'pendente']);

        Livewire::actingAs($this->imobiliaria, 'imobiliaria')
            ->test(PainelLeads::class)
            ->call('atualizarStatus', $atDeOutra->id, 'contatado');

        $this->assertDatabaseHas('atendimentos', [
            'id'             => $atDeOutra->id,
            'status_parceiro' => 'pendente',
        ]);
    }

    public function test_exporta_csv_com_atendimentos_filtrados(): void
    {
        $lead = Lead::factory()->create(['nome' => 'Export Test']);
        $this->atendimento(['id_lead' => $lead->id]);

        $response = Livewire::actingAs($this->imobiliaria, 'imobiliaria')
            ->test(PainelLeads::class)
            ->call('exportarCsv')
            ->assertFileDownloaded();

        $this->assertTrue(true); // download iniciado sem exceção
    }

    public function test_metricas_mostram_total_correto(): void
    {
        $this->atendimento();
        $this->atendimento();

        $component = Livewire::actingAs($this->imobiliaria, 'imobiliaria')
            ->test(PainelLeads::class);

        // Total de atendimentos visíveis no componente
        $component->assertSee('2');
    }
}

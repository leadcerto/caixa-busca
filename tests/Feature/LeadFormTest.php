<?php

namespace Tests\Feature;

use App\Models\Atendimento;
use App\Models\ImovelHistorico;
use App\Models\Lead;
use App\Modules\Imoveis\Jobs\DispatchCrmWebhookJob;
use App\Modules\Imoveis\Livewire\ImovelShow;
use Database\Factories\ImovelFactory;
use Database\Factories\ImovelHistoricoFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class LeadFormTest extends TestCase
{
    use RefreshDatabase;

    private function imovel(): \App\Models\Imovel
    {
        $imovel = \App\Models\Imovel::factory()->create();
        ImovelHistorico::factory()->create(['id_imovel' => $imovel->id]);
        return $imovel->load(['estado', 'municipio', 'bairro', 'tipoImovel', 'ultimoHistorico']);
    }

    public function test_exibe_formulario_na_pagina_do_imovel(): void
    {
        $imovel = $this->imovel();

        Livewire::test(ImovelShow::class, ['imovel' => $imovel])
            ->assertOk()
            ->assertSee('FALAR COM CORRETOR');
    }

    public function test_cria_lead_e_atendimento_ao_submeter_formulario(): void
    {
        Queue::fake();

        $imovel = $this->imovel();

        Livewire::test(ImovelShow::class, ['imovel' => $imovel])
            ->set('nome', 'João Silva')
            ->set('email', 'joao@teste.com')
            ->set('telefone', '11999999999')
            ->call('converterLead');

        $this->assertDatabaseHas('leads', ['email' => 'joao@teste.com', 'nome' => 'João Silva']);
        $this->assertDatabaseHas('atendimentos', ['id_imovel' => $imovel->id]);
        Queue::assertPushed(DispatchCrmWebhookJob::class);
    }

    public function test_atualiza_lead_existente_em_vez_de_duplicar(): void
    {
        Queue::fake();

        $imovel = $this->imovel();
        Lead::factory()->create(['email' => 'existente@teste.com', 'nome' => 'Nome Antigo']);

        Livewire::test(ImovelShow::class, ['imovel' => $imovel])
            ->set('nome', 'Nome Atualizado')
            ->set('email', 'existente@teste.com')
            ->set('telefone', '11988888888')
            ->call('converterLead');

        $this->assertEquals(1, Lead::where('email', 'existente@teste.com')->count());
        $this->assertDatabaseHas('leads', ['email' => 'existente@teste.com', 'nome' => 'Nome Atualizado']);
    }

    public function test_valida_campos_obrigatorios(): void
    {
        $imovel = $this->imovel();

        Livewire::test(ImovelShow::class, ['imovel' => $imovel])
            ->set('nome', '')
            ->set('email', '')
            ->set('telefone', '')
            ->call('converterLead')
            ->assertHasErrors(['nome', 'email', 'telefone']);

        $this->assertDatabaseCount('leads', 0);
    }

    public function test_valida_formato_de_email(): void
    {
        $imovel = $this->imovel();

        Livewire::test(ImovelShow::class, ['imovel' => $imovel])
            ->set('nome', 'João')
            ->set('email', 'nao-e-email')
            ->set('telefone', '11999999999')
            ->call('converterLead')
            ->assertHasErrors(['email']);
    }

    public function test_nao_cria_atendimento_duplicado_para_mesmo_imovel(): void
    {
        Queue::fake();

        $imovel = $this->imovel();

        $component = Livewire::test(ImovelShow::class, ['imovel' => $imovel])
            ->set('nome', 'Maria')
            ->set('email', 'maria@teste.com')
            ->set('telefone', '11977777777');

        $component->call('converterLead');
        $component->call('converterLead');

        $this->assertEquals(1, Atendimento::where('id_imovel', $imovel->id)->count());
    }

    public function test_rate_limiting_bloqueia_apos_5_tentativas(): void
    {
        Queue::fake();
        $imovel = $this->imovel();

        $component = Livewire::test(ImovelShow::class, ['imovel' => $imovel])
            ->set('nome', 'Teste Rate')
            ->set('email', 'rate@teste.com')
            ->set('telefone', '11966666666');

        // 5 tentativas válidas
        for ($i = 0; $i < 5; $i++) {
            $component->set('email', "rate{$i}@teste.com")->call('converterLead');
        }

        // 6ª tentativa deve ser bloqueada
        $component->set('email', 'rate6@teste.com')
            ->call('converterLead')
            ->assertHasErrors(['nome']);
    }
}

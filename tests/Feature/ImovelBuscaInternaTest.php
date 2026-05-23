<?php

namespace Tests\Feature;

use App\Models\Bairro;
use App\Models\Estado;
use App\Models\Imovel;
use App\Models\ImovelHistorico;
use App\Models\Municipio;
use App\Models\User;
use App\Modules\Admin\Livewire\ImovelBuscaInterna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ImovelBuscaInternaTest extends TestCase
{
    use RefreshDatabase;

    public function test_busca_interna_bloqueia_usuarios_nao_autenticados(): void
    {
        $this->get(route('admin.imoveis.busca-interna'))
            ->assertRedirect(route('login'));
    }

    public function test_busca_interna_permite_usuarios_autenticados(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.imoveis.busca-interna'))
            ->assertOk()
            ->assertSee('Busca Interna de Imóveis');
    }

    public function test_busca_interna_carrega_dropdown_de_estados(): void
    {
        $user = User::factory()->create();
        $estado1 = Estado::factory()->create(['nome' => 'Rio de Janeiro', 'uf' => 'RJ']);
        $estado2 = Estado::factory()->create(['nome' => 'São Paulo', 'uf' => 'SP']);

        $this->actingAs($user);

        Livewire::test(ImovelBuscaInterna::class)
            ->assertSet('estados', function ($estados) use ($estado1, $estado2) {
                return $estados->contains($estado1) && $estados->contains($estado2);
            });
    }

    public function test_busca_interna_filtra_por_numero_do_imovel(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $imovel1 = Imovel::factory()->create(['numero_original' => '8555510834062']);
        $imovel2 = Imovel::factory()->create(['numero_original' => '1234567890123']);

        // Create historicos to satisfy the latest history join requirement
        ImovelHistorico::factory()->create(['id_imovel' => $imovel1->id]);
        ImovelHistorico::factory()->create(['id_imovel' => $imovel2->id]);

        Livewire::test(ImovelBuscaInterna::class)
            ->set('busca_numero', '8555510834062')
            ->assertViewHas('imoveis', function ($imoveis) use ($imovel1, $imovel2) {
                return $imoveis->contains($imovel1) && !$imoveis->contains($imovel2);
            });
    }

    public function test_busca_interna_filtra_por_localizacao_e_atualiza_dropdowns(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $estado = Estado::factory()->create(['nome' => 'Rio de Janeiro', 'uf' => 'RJ']);
        $municipio = Municipio::factory()->create(['id_estado' => $estado->id, 'nome' => 'Niterói']);
        $bairro = Bairro::factory()->create(['id_municipio' => $municipio->id, 'nome' => 'Icaraí']);

        $imovel = Imovel::factory()->create([
            'id_estado' => $estado->id,
            'id_municipio' => $municipio->id,
            'id_bairro' => $bairro->id,
        ]);
        ImovelHistorico::factory()->create(['id_imovel' => $imovel->id]);

        Livewire::test(ImovelBuscaInterna::class)
            ->set('id_estado', $estado->id)
            ->call('carregarMunicipios')
            ->assertSet('id_municipio', null)
            ->set('id_municipio', $municipio->id)
            ->call('carregarBairros')
            ->set('bairros_selecionados', [$bairro->id])
            ->assertViewHas('imoveis', function ($imoveis) use ($imovel) {
                return $imoveis->contains($imovel);
            });
    }

    public function test_busca_interna_filtra_por_faixa_de_preco_de_venda(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $imovel1 = Imovel::factory()->create();
        $imovel2 = Imovel::factory()->create();

        ImovelHistorico::factory()->create(['id_imovel' => $imovel1->id, 'valor_venda' => 150000.00]);
        ImovelHistorico::factory()->create(['id_imovel' => $imovel2->id, 'valor_venda' => 350000.00]);

        Livewire::test(ImovelBuscaInterna::class)
            ->set('preco_min', '100000')
            ->set('preco_max', '200000')
            ->assertViewHas('imoveis', function ($imoveis) use ($imovel1, $imovel2) {
                return $imoveis->contains($imovel1) && !$imoveis->contains($imovel2);
            });
    }

    public function test_busca_interna_filtra_por_aceita_financiamento(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $imovel1 = Imovel::factory()->create(['aceita_fgts' => 'sim']);
        $imovel2 = Imovel::factory()->create(['aceita_fgts' => 'nao']);

        ImovelHistorico::factory()->create(['id_imovel' => $imovel1->id]);
        ImovelHistorico::factory()->create(['id_imovel' => $imovel2->id]);

        Livewire::test(ImovelBuscaInterna::class)
            ->set('financiamento', 'sim')
            ->assertViewHas('imoveis', function ($imoveis) use ($imovel1, $imovel2) {
                return $imoveis->contains($imovel1) && !$imoveis->contains($imovel2);
            });
    }

    public function test_busca_interna_ordena_por_maior_desconto_reais_e_percentual(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $imovel1 = Imovel::factory()->create();
        $imovel2 = Imovel::factory()->create();

        ImovelHistorico::factory()->create([
            'id_imovel' => $imovel1->id,
            'desconto_percentual' => 10.00,
            'desconto_valor' => 20000.00
        ]);

        ImovelHistorico::factory()->create([
            'id_imovel' => $imovel2->id,
            'desconto_percentual' => 30.00,
            'desconto_valor' => 90000.00
        ]);

        // When ordering by discount_pct_desc, imovel2 must be first
        Livewire::test(ImovelBuscaInterna::class)
            ->set('ordenacao', 'desconto_pct_desc')
            ->assertViewHas('imoveis', function ($imoveis) use ($imovel2) {
                return $imoveis->first()->id === $imovel2->id;
            });

        // When ordering by discount_reais_desc, imovel2 must be first
        Livewire::test(ImovelBuscaInterna::class)
            ->set('ordenacao', 'desconto_reais_desc')
            ->assertViewHas('imoveis', function ($imoveis) use ($imovel2) {
                return $imoveis->first()->id === $imovel2->id;
            });
    }

    public function test_busca_interna_carrega_apenas_bairros_com_imoveis_ativos(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $estado = Estado::factory()->create();
        $municipio = Municipio::factory()->create(['id_estado' => $estado->id]);
        
        // Bairro 1: tem imóvel ativo
        $bairroAtivo = Bairro::factory()->create(['id_municipio' => $municipio->id, 'nome' => 'Bairro Ativo']);
        $imovelAtivo = Imovel::factory()->create([
            'id_estado' => $estado->id,
            'id_municipio' => $municipio->id,
            'id_bairro' => $bairroAtivo->id,
            'status' => 'ativo',
        ]);
        ImovelHistorico::factory()->create(['id_imovel' => $imovelAtivo->id]);

        // Bairro 2: tem imóvel inativo
        $bairroInativo = Bairro::factory()->create(['id_municipio' => $municipio->id, 'nome' => 'Bairro Inativo']);
        $imovelInativo = Imovel::factory()->create([
            'id_estado' => $estado->id,
            'id_municipio' => $municipio->id,
            'id_bairro' => $bairroInativo->id,
            'status' => 'fora_de_venda',
        ]);
        ImovelHistorico::factory()->create(['id_imovel' => $imovelInativo->id]);

        // Bairro 3: não tem imóvel cadastrado
        $bairroSemImovel = Bairro::factory()->create(['id_municipio' => $municipio->id, 'nome' => 'Bairro Vazio']);

        Livewire::test(ImovelBuscaInterna::class)
            ->set('id_estado', $estado->id)
            ->set('id_municipio', $municipio->id)
            ->call('carregarBairros')
            ->assertSet('bairros', function ($bairros) use ($bairroAtivo, $bairroInativo, $bairroSemImovel) {
                return $bairros->contains($bairroAtivo) 
                    && !$bairros->contains($bairroInativo) 
                    && !$bairros->contains($bairroSemImovel);
            });
    }

    public function test_busca_interna_filtra_por_multiplos_bairros(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $estado = Estado::factory()->create();
        $municipio = Municipio::factory()->create(['id_estado' => $estado->id]);
        
        $bairro1 = Bairro::factory()->create(['id_municipio' => $municipio->id]);
        $bairro2 = Bairro::factory()->create(['id_municipio' => $municipio->id]);
        $bairro3 = Bairro::factory()->create(['id_municipio' => $municipio->id]);

        $imovel1 = Imovel::factory()->create(['id_estado' => $estado->id, 'id_municipio' => $municipio->id, 'id_bairro' => $bairro1->id, 'status' => 'ativo']);
        $imovel2 = Imovel::factory()->create(['id_estado' => $estado->id, 'id_municipio' => $municipio->id, 'id_bairro' => $bairro2->id, 'status' => 'ativo']);
        $imovel3 = Imovel::factory()->create(['id_estado' => $estado->id, 'id_municipio' => $municipio->id, 'id_bairro' => $bairro3->id, 'status' => 'ativo']);

        ImovelHistorico::factory()->create(['id_imovel' => $imovel1->id]);
        ImovelHistorico::factory()->create(['id_imovel' => $imovel2->id]);
        ImovelHistorico::factory()->create(['id_imovel' => $imovel3->id]);

        Livewire::test(ImovelBuscaInterna::class)
            ->set('id_estado', $estado->id)
            ->set('id_municipio', $municipio->id)
            ->set('bairros_selecionados', [$bairro1->id, $bairro2->id])
            ->assertViewHas('imoveis', function ($imoveis) use ($imovel1, $imovel2, $imovel3) {
                return $imoveis->contains($imovel1) 
                    && $imoveis->contains($imovel2) 
                    && !$imoveis->contains($imovel3);
            });
    }
}

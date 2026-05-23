<?php

namespace Tests\Feature;

use App\Models\Imobiliaria;
use App\Models\User;
use App\Modules\Admin\Livewire\AdminLogin;
use App\Modules\Imobiliaria\Livewire\ImobiliariaLogin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // Admin (guard: web / User)
    // -------------------------------------------------------------------------

    public function test_admin_faz_login_com_credenciais_validas(): void
    {
        $user = User::factory()->create([
            'email'    => 'admin@teste.com',
            'password' => Hash::make('senhaCorreta'),
        ]);

        Livewire::test(AdminLogin::class)
            ->set('email', 'admin@teste.com')
            ->set('password', 'senhaCorreta')
            ->call('login')
            ->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_nao_faz_login_com_senha_errada(): void
    {
        User::factory()->create([
            'email'    => 'admin@teste.com',
            'password' => Hash::make('senhaCorreta'),
        ]);

        Livewire::test(AdminLogin::class)
            ->set('email', 'admin@teste.com')
            ->set('password', 'senhaErrada')
            ->call('login')
            ->assertHasErrors(['email']);

        $this->assertGuest();
    }

    public function test_admin_valida_campos_obrigatorios(): void
    {
        Livewire::test(AdminLogin::class)
            ->set('email', '')
            ->set('password', '')
            ->call('login')
            ->assertHasErrors(['email', 'password']);
    }

    public function test_admin_nao_acessa_dashboard_sem_autenticacao(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_admin_autenticado_acessa_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    // -------------------------------------------------------------------------
    // Imobiliária (guard: imobiliaria)
    // -------------------------------------------------------------------------

    public function test_imobiliaria_faz_login_com_credenciais_validas(): void
    {
        $imobiliaria = Imobiliaria::factory()->create([
            'email' => 'parceiro@teste.com',
            'senha' => Hash::make('senhaCorreta'),
        ]);

        Livewire::test(ImobiliariaLogin::class)
            ->set('email', 'parceiro@teste.com')
            ->set('senha', 'senhaCorreta')
            ->call('login')
            ->assertRedirect(route('imobiliaria.painel'));

        $this->assertAuthenticatedAs($imobiliaria, 'imobiliaria');
    }

    public function test_imobiliaria_nao_faz_login_com_senha_errada(): void
    {
        Imobiliaria::factory()->create([
            'email' => 'parceiro@teste.com',
            'senha' => Hash::make('senhaCorreta'),
        ]);

        Livewire::test(ImobiliariaLogin::class)
            ->set('email', 'parceiro@teste.com')
            ->set('senha', 'senhaErrada')
            ->call('login')
            ->assertHasErrors(['email']);

        $this->assertGuest('imobiliaria');
    }

    public function test_imobiliaria_nao_acessa_painel_sem_autenticacao(): void
    {
        $this->get(route('imobiliaria.painel'))->assertRedirect();
    }
}

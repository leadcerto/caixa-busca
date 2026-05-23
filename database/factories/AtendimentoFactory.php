<?php

namespace Database\Factories;

use App\Models\Imobiliaria;
use App\Models\Imovel;
use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

class AtendimentoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_lead'        => Lead::factory(),
            'id_imovel'      => Imovel::factory(),
            'id_imobiliaria' => Imobiliaria::factory(),
            'id_origem'      => null,
            'mensagem'       => fake()->sentence(),
            'email_enviado'  => false,
            'whatsapp_enviado' => true,
            'status_parceiro' => 'pendente',
            'anotacao'       => null,
        ];
    }

    public function pendente(): static
    {
        return $this->state(['status_parceiro' => 'pendente']);
    }

    public function contatado(): static
    {
        return $this->state(['status_parceiro' => 'contatado']);
    }
}

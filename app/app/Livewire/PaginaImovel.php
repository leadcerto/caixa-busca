<?php

namespace App\Livewire;

use Livewire\Component;

class PaginaImovel extends Component
{
    /**
     * Estado da página: 'loading', 'regular', 'error'
     * Parte da regra "Solução de Três Estados".
     */
    public string $state = 'regular';

    /**
     * Dados do imóvel (Mock para Epicenter Design)
     */
    public array $imovel = [
        'id' => '123456',
        'tipo' => 'Apartamento',
        'cidade' => 'São Paulo',
        'bairro' => 'Moema',
        'endereco' => 'Al. dos Jurupis, 452 - Unidade 121',
        'quartos' => 3,
        'vagas' => 2,
        'area' => 115,
        'preco' => 850000.00,
        'descricao_caixa' => 'Excelente apartamento em região valorizada. 3 Quartos, 2 Vagas de Garagem, Área de Serviço, Suíte, Cozinha. Ocupado. Imóvel aceita utilização de FGTS e Financiamento Habitacional.',
    ];

    /**
     * Inicialização do componente
     */
    public function mount()
    {
        // No futuro, aqui será feito o carregamento real via Model do módulo Imoveis
        // $this->state = 'loading';
        // $this->imovel = Imovel::find($id);
        // if (!$this->imovel) $this->state = 'error';
    }

    /**
     * Função de retry para o estado de erro
     */
    public function retry()
    {
        $this->state = 'regular';
    }

    public function render()
    {
        return view('livewire.pagina-imovel');
    }
}

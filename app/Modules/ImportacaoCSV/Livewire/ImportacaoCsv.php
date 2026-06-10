<?php

namespace App\Modules\ImportacaoCSV\Livewire;

use Livewire\Component;

class ImportacaoCsv extends Component
{
    public function render()
    {
        return view('modules.importacao-csv.livewire.importacao-csv')
            ->layout('layouts.admin', ['title' => 'Importar CSV']);
    }
}

<?php

use Illuminate\Support\Facades\Route;
use App\Modules\ImportacaoCSV\Livewire\ImportacaoCsv;
use App\Modules\Imoveis\Livewire\ImovelSearch;
use App\Modules\Imoveis\Livewire\ImovelShow as ModularImovelShow;
use App\Modules\Imobiliaria\Livewire\PainelLeads;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/buscar', ImovelSearch::class)->name('imoveis.index');
Route::get('/imovel/{imovel:slug}', ModularImovelShow::class)->name('imovel.show');

// Painel do Parceiro (Imobiliária)
Route::middleware(['auth:imobiliaria', 'imobiliaria'])->group(function () {
    Route::get('/parceiro/painel', PainelLeads::class)->name('imobiliaria.painel');
});

Route::get('/admin/importar', ImportacaoCsv::class)->name('admin.importar');

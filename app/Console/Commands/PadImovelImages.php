<?php

namespace App\Console\Commands;

use App\Models\Imovel;
use Illuminate\Console\Command;

class PadImovelImages extends Command
{
    protected $signature = 'imoveis:pad-images';
    protected $description = 'Pad and format all existing imoveis facade image URLs to 13-digit Caixa standards';

    public function handle()
    {
        $this->info("Iniciando a padronização das URLs das fotos de fachada...");

        $total = Imovel::count();
        $updated = 0;

        // Processa todos os registros de forma eficiente em lotes usando lazyById
        $this->withProgressBar(Imovel::lazyById(100), function ($imovel) use (&$updated) {
            $idCaixa = $imovel->numero_original;
            if (!$idCaixa) {
                return;
            }
            
            // Padronização com preenchimento com zeros à esquerda até 13 dígitos
            $paddedUrl = "https://venda-imoveis.caixa.gov.br/fotos/F" . str_pad($idCaixa, 13, '0', STR_PAD_LEFT) . "21.jpg";

            if ($imovel->foto_fachada_url !== $paddedUrl) {
                $imovel->foto_fachada_url = $paddedUrl;
                $imovel->save();
                $updated++;
            }
        });

        $this->newLine();
        $this->info("Concluído! Total de imóveis: {$total}. Atualizados para formato correto: {$updated}.");
    }
}

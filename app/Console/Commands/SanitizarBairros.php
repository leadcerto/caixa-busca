<?php

namespace App\Console\Commands;

use App\Models\Bairro;
use App\Models\Imovel;
use App\Modules\ImportacaoCSV\Services\CaixaCsvParserService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SanitizarBairros extends Command
{
    protected $signature   = 'app:sanitizar-bairros {--dry-run : Mostra o que seria feito sem alterar o banco}';
    protected $description = 'Unifica bairros duplicados/truncados usando o dicionário BAIRRO_ALIASES do parser CSV';

    public function handle(): int
    {
        $dryRun  = $this->option('dry-run');
        $aliases = CaixaCsvParserService::BAIRRO_ALIASES;
        $total   = 0;

        if ($dryRun) {
            $this->warn('🔍 MODO DRY-RUN — nenhuma alteração será feita.');
        }

        foreach ($aliases as $errado => $canonico) {
            // Busca todos os bairros com o nome errado (case-insensitive)
            $bairrosErrados = Bairro::whereRaw('UPPER(nome) = ?', [$errado])->get();

            foreach ($bairrosErrados as $bairroErrado) {
                $idMunicipio = $bairroErrado->id_municipio;

                // Garante que o bairro canônico existe (ou cria)
                $bairroCanonicoExiste = Bairro::where('id_municipio', $idMunicipio)
                    ->where('nome', $canonico)
                    ->first();

                $quantidadeImoveis = Imovel::where('id_bairro', $bairroErrado->id)->count();

                $this->line(sprintf(
                    '  Município %d | "%s" (id=%d, %d imóveis) → "%s"%s',
                    $idMunicipio,
                    $bairroErrado->nome,
                    $bairroErrado->id,
                    $quantidadeImoveis,
                    $canonico,
                    $bairroCanonicoExiste ? " (id={$bairroCanonicoExiste->id})" : ' [novo]'
                ));

                if ($dryRun) {
                    continue;
                }

                DB::transaction(function () use ($bairroErrado, $bairroCanonicoExiste, $canonico, $idMunicipio) {
                    // Se o canônico ainda não existe, renomeia o errado
                    if (! $bairroCanonicoExiste) {
                        $bairroErrado->update(['nome' => $canonico]);
                        return;
                    }

                    // Reatribui os imóveis do bairro errado para o canônico
                    Imovel::where('id_bairro', $bairroErrado->id)
                        ->update(['id_bairro' => $bairroCanonicoExiste->id]);

                    // Remove o bairro duplicado (agora sem imóveis)
                    $bairroErrado->delete();
                });

                $total++;
            }
        }

        if ($dryRun) {
            $this->info('Dry-run concluído. Rode sem --dry-run para aplicar as mudanças.');
        } else {
            $this->info("Sanitização concluída. {$total} bairro(s) unificado(s).");
        }

        return self::SUCCESS;
    }
}

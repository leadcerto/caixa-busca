<?php

namespace App\Console\Commands;

use App\Models\Bairro;
use App\Modules\BairrosDossie\Jobs\GerarConteudoBairroJob;
use App\Modules\BairrosDossie\Services\ConteudoIaService;
use Illuminate\Console\Command;

class GerarConteudoBairro extends Command
{
    protected $signature = 'app:gerar-conteudo-bairro
                            {bairro_id? : ID do bairro (omitir para processar todos os pendentes)}
                            {--estado= : Filtrar por UF (ex: SP)}
                            {--now : Executar imediatamente sem fila}
                            {--force : Re-gerar mesmo que já tenha conteúdo}';

    protected $description = 'Gera conteúdo IA para páginas de bairros (SEO)';

    public function handle(ConteudoIaService $service): int
    {
        $bairroId = $this->argument('bairro_id');

        if ($bairroId) {
            return $this->processarUnico((int) $bairroId, $service);
        }

        return $this->processarLote($service);
    }

    private function processarUnico(int $id, ConteudoIaService $service): int
    {
        $bairro = Bairro::find($id);
        if (!$bairro) {
            $this->error("Bairro #{$id} não encontrado.");
            return self::FAILURE;
        }

        if ($this->option('now')) {
            $this->info("Gerando conteúdo para: {$bairro->nome}...");
            try {
                $conteudo = $service->gerarParaBairro($bairro);
                $bairro->update([
                    'conteudo_ia'  => $conteudo,
                    'ia_status'    => 'gerado',
                    'ia_gerado_em' => now(),
                ]);
                $this->info("Concluído: {$bairro->nome}");
            } catch (\Throwable $e) {
                $bairro->update(['ia_status' => 'erro']);
                $this->error("Erro: {$e->getMessage()}");
                return self::FAILURE;
            }
        } else {
            GerarConteudoBairroJob::dispatch($bairro->id);
            $this->info("Job enfileirado para: {$bairro->nome}");
        }

        return self::SUCCESS;
    }

    private function processarLote(ConteudoIaService $service): int
    {
        $query = Bairro::query();

        if (!$this->option('force')) {
            $query->where('ia_status', '!=', 'gerado');
        }

        if ($uf = $this->option('estado')) {
            $query->whereHas('municipio.estado', fn($q) => $q->where('uf', strtoupper($uf)));
        }

        // Só bairros com ao menos 1 imóvel ativo, ordenados do maior número de imóveis para o menor
        $query->withCount(['imoveis' => fn($q) => $q->where('status', 'ativo')])
              ->whereHas('imoveis', fn($q) => $q->where('status', 'ativo'))
              ->orderByDesc('imoveis_count');

        $bairros = $query->with('municipio.estado')->get();

        if ($bairros->isEmpty()) {
            $this->info('Nenhum bairro para processar.');
            return self::SUCCESS;
        }

        $this->info("Enfileirando {$bairros->count()} bairros...");
        $bar = $this->output->createProgressBar($bairros->count());
        $bar->start();

        foreach ($bairros as $bairro) {
            GerarConteudoBairroJob::dispatch($bairro->id);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Jobs enfileirados com sucesso.');

        return self::SUCCESS;
    }
}

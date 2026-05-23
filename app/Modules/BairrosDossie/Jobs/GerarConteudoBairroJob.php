<?php

namespace App\Modules\BairrosDossie\Jobs;

use App\Models\Bairro;
use App\Modules\BairrosDossie\Services\ConteudoIaService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GerarConteudoBairroJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries   = 3;
    public $backoff = [60, 300];
    public $timeout = 60;

    public function __construct(public readonly int $bairroId) {}

    public function handle(ConteudoIaService $service): void
    {
        $bairro = Bairro::findOrFail($this->bairroId);

        $bairro->update(['ia_status' => 'pendente']);

        try {
            $conteudo = $service->gerarParaBairro($bairro);

            $bairro->update([
                'conteudo_ia'  => $conteudo,
                'ia_status'    => 'gerado',
                'ia_gerado_em' => now(),
            ]);

            Log::info("BairrosDossie: conteúdo gerado para bairro #{$bairro->id} ({$bairro->nome}).");
        } catch (\Throwable $e) {
            $bairro->update(['ia_status' => 'erro']);
            Log::error("BairrosDossie: falha no bairro #{$bairro->id}.", ['erro' => $e->getMessage()]);
            throw $e;
        }
    }
}

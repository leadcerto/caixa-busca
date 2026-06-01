<?php

namespace App\Modules\BairrosDossie\Jobs;

use App\Models\Bairro;
use App\Modules\BairrosDossie\Services\ConteudoIaService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\GooglePingService;
use Illuminate\Support\Facades\Log;

class GerarConteudoBairroJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries   = 2;
    public $backoff = [30, 120];
    public $timeout = 120;

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
            GooglePingService::pingSitemap();
        } catch (\Throwable $e) {
            $bairro->update([
                'ia_status'   => 'erro',
                'conteudo_ia' => ['_erro' => mb_substr($e->getMessage(), 0, 500)],
            ]);
            Log::error("BairrosDossie: falha no bairro #{$bairro->id}.", ['erro' => $e->getMessage()]);
            throw $e;
        }
    }
}

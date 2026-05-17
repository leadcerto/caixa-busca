<?php

namespace App\Observers;

use App\Mail\NovoAtendimentoMail;
use App\Models\Atendimento;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AtendimentoObserver
{
    public function created(Atendimento $atendimento): void
    {
        $atendimento->loadMissing([
            'lead',
            'imovel.tipoImovel',
            'imovel.municipio',
            'imovel.estado',
            'imobiliaria',
        ]);

        $emailDestino = $atendimento->imobiliaria?->email;

        if (!$emailDestino) {
            Log::warning("AtendimentoObserver: imobiliária sem e-mail para atendimento #{$atendimento->id}.");
            return;
        }

        try {
            Mail::to($emailDestino)->queue(new NovoAtendimentoMail($atendimento));

            $atendimento->updateQuietly(['email_enviado' => true]);
        } catch (\Throwable $e) {
            Log::error("AtendimentoObserver: falha ao enfileirar e-mail para atendimento #{$atendimento->id}: {$e->getMessage()}");
        }
    }
}

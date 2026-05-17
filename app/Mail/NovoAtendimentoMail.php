<?php

namespace App\Mail;

use App\Models\Atendimento;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NovoAtendimentoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Atendimento $atendimento) {}

    public function envelope(): Envelope
    {
        $numero = $this->atendimento->imovel?->numero_original ?? '—';

        return new Envelope(
            subject: "Novo lead — Imóvel #{$numero}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.novo-atendimento',
        );
    }
}

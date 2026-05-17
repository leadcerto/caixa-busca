<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; background: #f9fafb; color: #1f2937; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 16px; overflow: hidden; border: 1px solid #e5e7eb; }
        .header { background: #005CA9; padding: 32px 40px; }
        .header h1 { color: #fff; margin: 0; font-size: 22px; font-weight: 900; }
        .header p { color: #93c5fd; margin: 6px 0 0; font-size: 13px; }
        .body { padding: 40px; }
        .card { background: #f0f7ff; border-radius: 12px; padding: 24px; margin-bottom: 24px; }
        .label { font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: .08em; color: #6b7280; margin-bottom: 4px; }
        .value { font-size: 16px; font-weight: 700; color: #111827; }
        .btn { display: inline-block; background: #F39200; color: #fff; font-weight: 900; text-decoration: none; padding: 14px 28px; border-radius: 12px; font-size: 14px; margin-top: 24px; }
        .footer { padding: 24px 40px; border-top: 1px solid #f3f4f6; font-size: 11px; color: #9ca3af; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Novo Lead Recebido</h1>
        <p>Um visitante demonstrou interesse em um imóvel da sua região.</p>
    </div>
    <div class="body">

        <div class="card">
            <div class="label">Interessado</div>
            <div class="value">{{ $atendimento->lead?->nome }}</div>
            <div style="color:#6b7280; font-size:13px; margin-top:4px;">{{ $atendimento->lead?->email }}</div>
            @if($atendimento->lead?->telefone)
                <div style="color:#6b7280; font-size:13px;">{{ $atendimento->lead->telefone }}</div>
            @endif
        </div>

        <div class="card">
            <div class="label">Imóvel de Interesse</div>
            <div class="value">
                {{ $atendimento->imovel?->tipoImovel?->nome ?? 'Imóvel' }}
                — #{{ $atendimento->imovel?->numero_original }}
            </div>
            <div style="color:#6b7280; font-size:13px; margin-top:4px;">
                {{ $atendimento->imovel?->municipio?->nome }}/{{ $atendimento->imovel?->estado?->uf }}
            </div>
            @if($atendimento->imovel?->slug)
                <a href="{{ route('imovel.show', $atendimento->imovel->slug) }}"
                   style="color:#005CA9; font-size:13px; margin-top:6px; display:inline-block;">
                    Ver página do imóvel →
                </a>
            @endif
        </div>

        @if($atendimento->mensagem)
            <div class="card">
                <div class="label">Mensagem</div>
                <div style="font-size:14px; color:#374151;">{{ $atendimento->mensagem }}</div>
            </div>
        @endif

        @if($atendimento->lead?->telefone)
            <a href="https://wa.me/{{ preg_replace('/\D/', '', $atendimento->lead->telefone) }}" class="btn">
                Responder no WhatsApp
            </a>
        @endif
    </div>
    <div class="footer">
        Antigravity Imóveis · Lead recebido em {{ now()->format('d/m/Y \à\s H:i') }}
    </div>
</div>
</body>
</html>

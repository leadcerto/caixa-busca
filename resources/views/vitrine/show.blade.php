<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDesc }}">
    <meta name="robots" content="noindex, nofollow">

    <meta property="og:type"        content="website">
    <meta property="og:title"       content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDesc }}">
    <meta property="og:url"         content="{{ url()->current() }}">

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { background: #f8fafc; }
        .vitrine-header {
            background: linear-gradient(135deg, #005CA9 0%, #003d73 100%);
        }
        .vitrine-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .vitrine-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(0, 92, 169, 0.15);
        }
        .vitrine-cta {
            background: linear-gradient(135deg, #F39200 0%, #d67e00 100%);
            transition: all 0.3s ease;
        }
        .vitrine-cta:hover {
            background: linear-gradient(135deg, #d67e00 0%, #b56a00 100%);
            transform: scale(1.03);
        }
        .nav-arrow {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 900;
            transition: all 0.25s ease;
            border: 2px solid #005CA9;
            color: #005CA9;
            background: white;
        }
        .nav-arrow:hover {
            background: #005CA9;
            color: white;
            transform: scale(1.1);
        }
        .nav-arrow-disabled {
            opacity: 0.3;
            cursor: not-allowed;
            pointer-events: none;
        }
    </style>
</head>
<body class="min-h-screen font-sans">

{{-- ── HEADER MÍNIMO ───────────────────────────────────────────────────────── --}}
<header class="vitrine-header sticky top-0 z-30 shadow-lg">
    <div class="max-w-5xl mx-auto px-4 py-4 flex items-center justify-between">
        <a href="{{ route('imoveis.index') }}" class="flex items-center gap-2 group">
            <span class="text-white/90 text-xl">🏠</span>
            <span class="text-white font-black text-lg tracking-tight group-hover:text-white/80 transition">Imóveis da Caixa</span>
        </a>
        <div class="text-right">
            <p class="text-white font-bold text-sm truncate max-w-[200px] sm:max-w-none">{{ $vitrine->nome }}</p>
            <p class="text-blue-200/70 text-xs">{{ $localidade }}</p>
        </div>
    </div>
</header>

{{-- ── CONTEÚDO ────────────────────────────────────────────────────────────── --}}
<div class="max-w-5xl mx-auto px-4 py-8">

    @if($imoveis->isEmpty())
        <div class="bg-white rounded-3xl p-16 text-center shadow-sm border border-gray-100">
            <p class="text-4xl mb-4">🔍</p>
            <h2 class="text-xl font-black text-gray-800">Nenhum imóvel disponível nesta vitrine</h2>
            <p class="text-gray-400 text-sm mt-2">Os imóveis podem ter sido vendidos ou atualizados.</p>
        </div>
    @else
        {{-- Info discreta --}}
        <div class="text-center mb-6">
            <p class="text-sm text-slate-400">
                <span class="font-bold text-[#005CA9]">{{ number_format($imoveis->total()) }}</span>
                {{ $imoveis->total() === 1 ? 'imóvel disponível' : 'imóveis disponíveis' }}
                @if($imoveis->hasPages())
                    — página {{ $imoveis->currentPage() }} de {{ $imoveis->lastPage() }}
                @endif
            </p>
        </div>

        {{-- Grid de cards — 3 por página --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($imoveis as $imovel)
                @php
                    $hist    = $imovel->ultimoHistorico;
                    $preco   = $hist?->valor_venda          ?? 0;
                    $aval    = $hist?->valor_avaliacao       ?? 0;
                    $desc    = $hist?->desconto_percentual   ?? 0;
                    $lucro   = $hist?->desconto_valor        ?? 0;
                    $fotoUrl = $imovel->foto_fachada_url     ?? asset('images/imovel-placeholder.svg');

                    if ($desc >= 40) {
                        $badgeStyle = 'background:linear-gradient(135deg,#FEF08A 0%,#FBBF24 50%,#CA8A04 100%);border:1px solid #F59E0B;color:#78350F;';
                        $badgeText  = 'Ouro'; $badgeIcon = '⭐';
                    } elseif ($desc >= 30) {
                        $badgeStyle = 'background:linear-gradient(135deg,#CBD5E1 0%,#64748B 100%);border:1px solid #94A3B8;color:#fff;';
                        $badgeText  = 'Prata'; $badgeIcon = '🥈';
                    } elseif ($desc >= 20) {
                        $badgeStyle = 'background:linear-gradient(135deg,#FCA5A5 0%,#EF4444 50%,#991B1B 100%);border:1px solid #EF4444;color:#fff;';
                        $badgeText  = 'Bronze'; $badgeIcon = '🥉';
                    } else {
                        $badgeStyle = 'background:linear-gradient(135deg,#10B981 0%,#047857 100%);border:1px solid #10B981;color:#fff;';
                        $badgeText  = 'Selecionado'; $badgeIcon = '🏷️';
                    }
                @endphp

                <a href="{{ route('imovel.show', $imovel->slug) }}"
                   target="_blank" rel="noopener"
                   class="vitrine-card bg-white rounded-[1.75rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col">

                    {{-- Imagem --}}
                    <div class="relative aspect-[4/3] bg-gray-100 overflow-hidden">
                        <img src="{{ $fotoUrl }}"
                             alt="{{ $imovel->tipoImovel?->nome }} {{ $imovel->numero_original }}"
                             loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                             onerror="this.onerror=null;this.src='{{ asset('images/imovel-placeholder.svg') }}'"
                             class="w-full h-full object-cover">

                        @if($desc > 0)
                            <span class="absolute top-3 left-3 bg-[#F39200] text-white text-xs font-black px-2.5 py-1 rounded-full shadow">
                                -{{ number_format($desc, 0, ',', '.') }}%
                            </span>
                        @endif

                        <div class="absolute top-3 right-3 flex flex-col gap-1">
                            @if($imovel->aceita_fgts === 'sim')
                                <span class="bg-emerald-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full">FGTS</span>
                            @endif
                            @if($imovel->aceita_financ_sbpe)
                                <span class="bg-[#005CA9] text-white text-[9px] font-black px-2 py-0.5 rounded-full">SBPE</span>
                            @endif
                            @if($imovel->aceita_financ_mcmv)
                                <span class="bg-violet-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full">MCMV</span>
                            @endif
                        </div>
                    </div>

                    {{-- Badges 2×2 --}}
                    <div class="grid grid-cols-2 gap-2 px-4 mt-4">
                        @if($imovel->aceita_fgts === 'sim')
                            <div class="h-10 rounded-xl flex items-center justify-between px-3 text-xs font-extrabold"
                                 style="background:#ECFDF5;border:1px solid #A7F3D0;color:#047857;">
                                <span>FGTS</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        @else
                            <div class="h-10 rounded-xl flex items-center justify-between px-3 text-xs font-bold"
                                 style="background:#FFF1F2;border:1px solid #FECDD3;color:#E11D48;">
                                <span>FGTS</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </div>
                        @endif

                        @if($imovel->aceita_financ_sbpe || $imovel->aceita_financ_mcmv)
                            <div class="h-10 rounded-xl flex items-center justify-between px-3 text-xs font-extrabold"
                                 style="background:#ECFDF5;border:1px solid #A7F3D0;color:#047857;">
                                <span>Financiamento</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        @else
                            <div class="h-10 rounded-xl flex items-center justify-between px-3 text-xs font-bold"
                                 style="background:#FFF1F2;border:1px solid #FECDD3;color:#E11D48;">
                                <span>Financiamento</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </div>
                        @endif

                        <div class="h-10 rounded-xl flex items-center justify-center px-3 text-xs font-black"
                             style="background:#005CA9;color:#fff;">
                            {{ $imovel->tipoImovel?->nome }}
                        </div>

                        <div class="h-10 rounded-xl flex items-center justify-center gap-1 px-2 text-[11px] font-black"
                             style="{{ $badgeStyle }}">
                            <span class="text-sm leading-none">{{ $badgeIcon }}</span>
                            <span>{{ $badgeText }}</span>
                        </div>
                    </div>

                    {{-- Textos --}}
                    <div class="px-4 mt-5 text-center">
                        <h2 class="text-[#005CA9] font-black text-xl leading-tight">
                            {{ $imovel->municipio?->nome }} — {{ $imovel->estado?->uf }}
                        </h2>
                        <p class="text-[#005CA9] font-black text-base mt-0.5">
                            {{ $imovel->bairro?->nome ?? '' }}
                        </p>
                        @if($imovel->endereco)
                            <p class="text-slate-600 font-semibold text-xs uppercase tracking-wide mt-3 line-clamp-2">
                                {{ $imovel->endereco }}
                            </p>
                        @endif
                        <p class="text-gray-400 text-xs mt-2">Imóvel: {{ $imovel->numero_original }}</p>
                    </div>

                    {{-- Preços --}}
                    <div class="px-4 mt-4 text-center">
                        @if($aval > 0)
                            <p class="text-[#005CA9] text-sm font-semibold">
                                De: R$ {{ number_format($aval, 2, ',', '.') }}
                            </p>
                        @endif
                        @if($preco > 0)
                            <p class="text-[#005CA9] text-xs font-extrabold uppercase tracking-wider mt-2">Por:</p>
                            <p class="text-[#005CA9] text-2xl font-black mt-0.5">
                                R$ {{ number_format($preco, 2, ',', '.') }}
                            </p>
                        @endif
                        @if($lucro > 0)
                            <p class="text-[#E50000] text-xs font-extrabold uppercase tracking-wider mt-3">Lucro:</p>
                            <p class="text-[#E50000] text-2xl font-black mt-0.5">
                                R$ {{ number_format($lucro, 2, ',', '.') }}
                            </p>
                        @endif
                    </div>

                    {{-- CTA --}}
                    <div class="px-4 pb-5 mt-5 mt-auto">
                        <div class="vitrine-cta w-full text-white font-black text-base py-3.5 rounded-2xl text-center uppercase tracking-wide">
                            SAIBA MAIS
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Paginação — Setas simples --}}
        @if($imoveis->hasPages())
            <div class="flex items-center justify-center gap-6 mt-10">
                @if($imoveis->onFirstPage())
                    <span class="nav-arrow nav-arrow-disabled">←</span>
                @else
                    <a href="{{ $imoveis->previousPageUrl() }}" class="nav-arrow">←</a>
                @endif

                <span class="text-sm font-bold text-slate-500">
                    {{ $imoveis->currentPage() }} / {{ $imoveis->lastPage() }}
                </span>

                @if($imoveis->hasMorePages())
                    <a href="{{ $imoveis->nextPageUrl() }}" class="nav-arrow">→</a>
                @else
                    <span class="nav-arrow nav-arrow-disabled">→</span>
                @endif
            </div>
        @endif
    @endif

</div>

{{-- ── FOOTER MÍNIMO ──────────────────────────────────────────────────────── --}}
<footer class="border-t border-slate-100 py-8 mt-4">
    <div class="max-w-5xl mx-auto px-4 text-center">
        <a href="{{ route('imoveis.index') }}"
           class="text-sm text-[#005CA9] hover:text-[#004a8a] font-semibold transition">
            🏠 Buscar mais imóveis em venda.imoveisdacaixa.com.br
        </a>
        <p class="text-xs text-slate-300 mt-3">
            © {{ date('Y') }} Imóveis da Caixa — Todos os direitos reservados
        </p>
    </div>
</footer>

</body>
</html>

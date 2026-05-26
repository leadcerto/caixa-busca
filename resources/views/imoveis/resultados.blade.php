<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDesc }}">

    <meta property="og:type"        content="website">
    <meta property="og:title"       content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDesc }}">
    <meta property="og:url"         content="{{ url()->current() }}">

    <link rel="canonical" href="{{ route('imoveis.busca', array_filter([$tipo, $estado, $cidade, $bairro])) }}">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 min-h-screen font-sans">

{{-- ── HEADER ──────────────────────────────────────────────────────────────── --}}
<header class="bg-white border-b border-slate-100 sticky top-0 z-30 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
        <a href="{{ route('imoveis.index') }}" class="flex items-center gap-2">
            <span class="text-[#005CA9] font-black text-xl tracking-tight">Imóveis da Caixa</span>
        </a>
        <a href="{{ route('imoveis.index') }}"
           class="inline-flex items-center gap-2 text-sm text-white bg-[#005CA9] hover:bg-[#004a8a] font-bold px-5 py-2.5 rounded-2xl transition">
            ← Nova busca
        </a>
    </div>
</header>

{{-- ── BREADCRUMB + TÍTULO ─────────────────────────────────────────────────── --}}
<div class="bg-white border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 py-5">
        <nav class="text-xs text-gray-400 mb-2 flex flex-wrap gap-1 items-center">
            <a href="{{ route('imoveis.index') }}" class="hover:text-[#005CA9]">Início</a>
            <span>/</span>
            <span class="capitalize">{{ $tipoObj->nome }}</span>
            <span>/</span>
            <span class="uppercase">{{ $estadoObj->uf }}</span>
            @if($municipioObj)
                <span>/</span>
                <span>{{ $municipioObj->nome }}</span>
            @endif
            @if($bairroObj)
                <span>/</span>
                <span>{{ $bairroObj->nome }}</span>
            @endif
        </nav>
        <h1 class="text-2xl font-black text-gray-900">
            {{ ucfirst($tipoObj->nome) }} à venda em {{ $localidade }}
        </h1>
        <p class="text-sm text-gray-400 mt-1">
            <strong class="text-[#005CA9]">{{ number_format($imoveis->total()) }}</strong>
            {{ $imoveis->total() === 1 ? 'imóvel encontrado' : 'imóveis encontrados' }}
        </p>
    </div>
</div>

{{-- ── RESULTADOS ───────────────────────────────────────────────────────────── --}}
<div class="max-w-7xl mx-auto px-4 py-8">

    @if($imoveis->isEmpty())
        <div class="bg-white rounded-[2rem] p-20 text-center shadow-sm border border-gray-100">
            <p class="text-4xl mb-4">🔍</p>
            <h2 class="text-xl font-black text-gray-800">Nenhum imóvel encontrado</h2>
            <p class="text-gray-400 text-sm mt-2">Tente uma busca diferente.</p>
            <a href="{{ route('imoveis.index') }}"
               class="inline-block mt-6 bg-[#005CA9] text-white font-black px-8 py-3 rounded-2xl hover:bg-[#004a8a] transition">
                ← Nova busca
            </a>
        </div>
    @else
        {{-- Grid de cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
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
                   target="_blank"
                   class="group bg-white rounded-[1.75rem] border border-gray-100 shadow-sm
                          hover:shadow-xl hover:shadow-blue-100/50 hover:-translate-y-1
                          transition-all duration-300 overflow-hidden flex flex-col">

                    {{-- Imagem --}}
                    <div class="relative aspect-[4/3] bg-gray-100 overflow-hidden rounded-t-[1.75rem]">
                        <img src="{{ $fotoUrl }}"
                             alt="{{ $imovel->tipoImovel?->nome }} {{ $imovel->numero_original }}"
                             loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                             onerror="this.onerror=null;this.src='{{ asset('images/imovel-placeholder.svg') }}'"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                        @if($desc > 0)
                            <span class="absolute top-3 left-3 bg-[#F39200] text-white text-xs font-black px-2.5 py-1 rounded-full shadow">
                                -{{ number_format($desc, 0, ',', '.') }}%
                            </span>
                        @endif

                        <div class="absolute top-3 right-3 flex flex-col gap-1">
                            @if($imovel->aceita_fgts === 'sim')
                                <span class="bg-emerald-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full">FGTS</span>
                            @endif
                            @if($hist?->aceita_financ_sbpe)
                                <span class="bg-[#005CA9] text-white text-[9px] font-black px-2 py-0.5 rounded-full">SBPE</span>
                            @endif
                            @if($hist?->aceita_financ_mcmv)
                                <span class="bg-violet-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full">MCMV</span>
                            @endif
                        </div>
                    </div>

                    {{-- Badges 2×2 --}}
                    <div class="grid grid-cols-2 gap-2 px-4 mt-4">
                        {{-- FGTS --}}
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

                        {{-- Financiamento --}}
                        @if($imovel->aceita_fgts === 'sim')
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

                        {{-- Tipo --}}
                        <div class="h-10 rounded-xl flex items-center justify-center px-3 text-xs font-black"
                             style="background:#005CA9;color:#fff;">
                            {{ $imovel->tipoImovel?->nome }}
                        </div>

                        {{-- Badge ouro/prata/bronze --}}
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
                    <div class="px-4 pb-5 mt-5">
                        <div class="w-full bg-[#F39200] group-hover:bg-[#d67e00] text-white font-black text-base py-3.5 rounded-2xl text-center uppercase tracking-wide transition-all duration-300 group-hover:scale-[1.02]">
                            SAIBA MAIS
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Paginação --}}
        @if($imoveis->hasPages())
            <div class="mt-10 flex justify-center">
                {{ $imoveis->links() }}
            </div>
        @endif
    @endif
</div>

{{-- ── FOOTER CTA ───────────────────────────────────────────────────────────── --}}
<div class="bg-white border-t border-slate-100 py-10 mt-4">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <a href="{{ route('imoveis.index') }}"
           class="inline-flex items-center gap-2 text-sm text-white bg-[#005CA9] hover:bg-[#004a8a] font-bold px-8 py-4 rounded-2xl transition shadow-lg shadow-blue-200/50">
            ← Fazer nova busca em venda.imoveisdacaixa.com.br
        </a>
    </div>
</div>

{{-- ── JSON-LD ──────────────────────────────────────────────────────────────── --}}
@php
$listItems = [];
foreach ($imoveis as $i => $item) {
    $listItems[] = [
        '@type'    => 'ListItem',
        'position' => $imoveis->firstItem() + $i,
        'url'      => route('imovel.show', $item->slug),
        'name'     => trim(($item->tipoImovel?->nome ?? 'Imóvel') . ' em '
                     . ($item->municipio?->nome ?? '') . ', ' . ($item->estado?->uf ?? '')),
    ];
}
echo '<script type="application/ld+json">'
    . json_encode([
        '@context'   => 'https://schema.org',
        '@type'      => 'SearchResultsPage',
        'name'       => $metaTitle,
        'url'        => url()->current(),
        'mainEntity' => [
            '@type'           => 'ItemList',
            'numberOfItems'   => $imoveis->total(),
            'itemListElement' => $listItems,
        ],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
    . '</script>';
@endphp

</body>
</html>

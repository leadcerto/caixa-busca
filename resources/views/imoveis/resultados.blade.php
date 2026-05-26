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
           class="text-xs text-[#005CA9] font-bold hover:underline">← Nova busca</a>
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

{{-- ── LAYOUT PRINCIPAL ────────────────────────────────────────────────────── --}}
<div class="max-w-7xl mx-auto px-4 py-8 flex flex-col lg:flex-row gap-8">

    {{-- ── SIDEBAR DE FILTROS ──────────────────────────────────────────────── --}}
    <aside class="w-full lg:w-72 flex-shrink-0">
        <form
            id="filtros-form"
            data-tipo="{{ $tipo }}"
            data-estado="{{ $estado }}"
            data-cidade="{{ $cidade }}"
            data-bairro="{{ $bairro }}"
            class="bg-white rounded-[1.75rem] border border-gray-100 shadow-sm p-6 space-y-6"
        >
            {{-- Ordenação --}}
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">
                    Ordenar por
                </label>
                <select name="ordenar"
                        class="w-full border border-gray-200 rounded-2xl h-11 px-4 text-sm text-gray-800
                               focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition appearance-none">
                    <option value="desconto_desc" {{ $ordenar === 'desconto_desc' ? 'selected' : '' }}>Maior desconto</option>
                    <option value="preco_asc"     {{ $ordenar === 'preco_asc'     ? 'selected' : '' }}>Menor preço</option>
                    <option value="preco_desc"    {{ $ordenar === 'preco_desc'    ? 'selected' : '' }}>Maior preço</option>
                    <option value="desconto_asc"  {{ $ordenar === 'desconto_asc'  ? 'selected' : '' }}>Menor desconto</option>
                </select>
            </div>

            {{-- Financiamento --}}
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-3">
                    Financiamento
                </p>
                <div class="space-y-2">
                    @foreach([
                        'fgts' => 'FGTS',
                        'sbpe' => 'SBPE (Banco)',
                        'mcmv' => 'Minha Casa Minha Vida',
                    ] as $val => $label)
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox"
                                   name="financiamento[]"
                                   value="{{ $val }}"
                                   {{ in_array($val, $financiamentos) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded accent-[#005CA9] cursor-pointer">
                            <span class="text-sm text-gray-700 group-hover:text-[#005CA9] transition">
                                {{ $label }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Quartos --}}
            <div>
                <label for="quartos"
                       class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">
                    Mínimo de quartos
                </label>
                <div class="flex gap-2">
                    @foreach([1, 2, 3, 4] as $q)
                        <button type="button"
                                data-quartos="{{ $q }}"
                                class="quartos-btn flex-1 h-10 rounded-xl border text-sm font-bold transition
                                       {{ (int) request('quartos') === $q
                                            ? 'bg-[#005CA9] text-white border-[#005CA9]'
                                            : 'border-gray-200 text-gray-500 hover:border-[#005CA9] hover:text-[#005CA9]' }}">
                            {{ $q }}+
                        </button>
                    @endforeach
                </div>
                <input type="hidden" name="quartos" id="quartos-input"
                       value="{{ request('quartos', '') }}">
            </div>

            {{-- Preço máximo --}}
            <div>
                <label for="preco_max"
                       class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">
                    Preço máximo (R$)
                </label>
                <input type="text"
                       id="preco_max"
                       name="preco_max"
                       value="{{ request('preco_max', '') }}"
                       placeholder="Ex: 500000"
                       class="w-full border border-gray-200 rounded-2xl h-11 px-4 text-sm text-gray-800
                              focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition">
            </div>

            {{-- Desconto mínimo --}}
            <div>
                <label for="desconto_min"
                       class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">
                    Desconto mínimo (%)
                </label>
                <select name="desconto_min" id="desconto_min"
                        class="w-full border border-gray-200 rounded-2xl h-11 px-4 text-sm text-gray-800
                               focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition appearance-none">
                    <option value="">Qualquer desconto</option>
                    @foreach([10, 20, 30, 40, 50] as $pct)
                        <option value="{{ $pct }}"
                                {{ (int) request('desconto_min') === $pct ? 'selected' : '' }}>
                            Acima de {{ $pct }}%
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Botão aplicar --}}
            <button type="submit"
                    class="w-full bg-[#005CA9] hover:bg-[#004a8a] text-white font-black text-sm
                           py-3 rounded-2xl transition-all shadow-lg shadow-blue-200/50">
                Aplicar Filtros
            </button>

            {{-- Limpar filtros --}}
            <a href="{{ route('imoveis.busca', array_filter([$tipo, $estado, $cidade, $bairro])) }}"
               class="block text-center text-xs text-gray-400 hover:text-gray-600 font-bold transition">
                Limpar filtros
            </a>
        </form>
    </aside>

    {{-- ── RESULTADOS ──────────────────────────────────────────────────────── --}}
    <main class="flex-1 min-w-0">

        @if($imoveis->isEmpty())
            <div class="bg-white rounded-[2rem] p-20 text-center shadow-sm border border-gray-100">
                <p class="text-4xl mb-4">🔍</p>
                <h2 class="text-xl font-black text-gray-800">Nenhum imóvel encontrado</h2>
                <p class="text-gray-400 text-sm mt-2">Tente remover alguns filtros para ampliar a busca.</p>
            </div>
        @else
            {{-- Grid de cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                @foreach($imoveis as $imovel)
                    @php
                        $hist    = $imovel->ultimoHistorico;
                        $preco   = $hist?->valor_venda   ?? 0;
                        $desc    = $hist?->desconto_percentual ?? 0;
                        $lucro   = $hist?->desconto_valor ?? 0;
                        $fotoUrl = $imovel->foto_fachada_url ?? asset('images/imovel-placeholder.svg');
                    @endphp
                    <a href="{{ route('imovel.show', $imovel->slug) }}"
                       target="_blank"
                       class="group bg-white rounded-[1.75rem] border border-gray-100 shadow-sm
                              hover:shadow-xl hover:shadow-blue-100/50 hover:-translate-y-1
                              transition-all duration-300 overflow-hidden flex flex-col">

                        {{-- Imagem --}}
                        <div class="relative h-44 bg-gray-100 overflow-hidden">
                            <img src="{{ $fotoUrl }}"
                                 alt="{{ $imovel->tipoImovel?->nome }} {{ $imovel->numero_original }}"
                                 loading="lazy"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                            @if($desc > 0)
                                <span class="absolute top-3 left-3 bg-[#F39200] text-white text-xs font-black
                                             px-2.5 py-1 rounded-full shadow">
                                    -{{ number_format($desc, 0, ',', '.') }}%
                                </span>
                            @endif

                            {{-- Badges financiamento --}}
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

                        {{-- Conteúdo --}}
                        <div class="p-4 flex flex-col flex-1">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                {{ $imovel->tipoImovel?->nome }} · #{{ $imovel->numero_original }}
                            </p>
                            <p class="text-sm font-bold text-gray-700 mt-1 leading-tight">
                                {{ $imovel->bairro?->nome ?? $imovel->municipio?->nome }},
                                {{ $imovel->municipio?->nome }} — {{ $imovel->estado?->uf }}
                            </p>

                            @if($imovel->quartos)
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $imovel->quartos }} quarto{{ $imovel->quartos > 1 ? 's' : '' }}
                                    @if($imovel->area_privativa)
                                        · {{ number_format($imovel->area_privativa, 0, ',', '.') }} m²
                                    @endif
                                </p>
                            @endif

                            <div class="mt-auto pt-3 border-t border-gray-50">
                                @if($preco > 0)
                                    <p class="text-lg font-black text-gray-900">
                                        R$ {{ number_format($preco, 2, ',', '.') }}
                                    </p>
                                @endif
                                @if($lucro > 0)
                                    <p class="text-xs text-emerald-600 font-bold">
                                        Lucro imediato: R$ {{ number_format($lucro, 2, ',', '.') }}
                                    </p>
                                @endif
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
    </main>
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

{{-- ── PASSO 4: JS DE REDIRECIONAMENTO ────────────────────────────────────── --}}
<script>
(function () {
    // ── Botões de quartos ────────────────────────────────────────────────────
    const quartosInput = document.getElementById('quartos-input');
    document.querySelectorAll('.quartos-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const val = this.dataset.quartos;
            const active = quartosInput.value === val;

            // Toggle: clicar novamente remove o filtro
            quartosInput.value = active ? '' : val;

            document.querySelectorAll('.quartos-btn').forEach(b => {
                b.classList.remove('bg-[#005CA9]', 'text-white', 'border-[#005CA9]');
                b.classList.add('border-gray-200', 'text-gray-500');
            });

            if (!active) {
                this.classList.add('bg-[#005CA9]', 'text-white', 'border-[#005CA9]');
                this.classList.remove('border-gray-200', 'text-gray-500');
            }
        });
    });

    // ── Intercepta submit e constrói URL amigável ────────────────────────────
    const form = document.getElementById('filtros-form');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const tipo    = form.dataset.tipo;
        const estado  = form.dataset.estado;
        const cidade  = form.dataset.cidade;
        const bairro  = form.dataset.bairro;

        // Monta o path — ignora segmentos vazios
        const segmentos = ['/imoveis', tipo, estado, cidade, bairro]
            .filter(Boolean);
        const path = segmentos.join('/');

        // Monta query string a partir dos inputs do formulário
        const params = new URLSearchParams();

        // Financiamento (checkboxes múltiplos)
        form.querySelectorAll('input[name="financiamento[]"]:checked').forEach(cb => {
            params.append('financiamento[]', cb.value);
        });

        // Quartos (hidden input)
        const quartos = quartosInput.value.trim();
        if (quartos) params.set('quartos', quartos);

        // Preço máximo
        const precoMax = form.querySelector('[name="preco_max"]').value.trim()
            .replace(/\./g, '').replace(',', '.');
        if (precoMax) params.set('preco_max', precoMax);

        // Desconto mínimo
        const descontoMin = form.querySelector('[name="desconto_min"]').value;
        if (descontoMin) params.set('desconto_min', descontoMin);

        // Ordenação (só inclui se diferente do padrão)
        const ordenar = form.querySelector('[name="ordenar"]').value;
        if (ordenar && ordenar !== 'desconto_desc') params.set('ordenar', ordenar);

        const qs = params.toString();
        window.location.href = path + (qs ? '?' + qs : '');
    });
})();
</script>

</body>
</html>

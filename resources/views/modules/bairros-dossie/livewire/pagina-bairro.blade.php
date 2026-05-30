<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10 space-y-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-400">
        <a href="{{ route('imoveis.index') }}" class="hover:text-[#005CA9] transition-colors">Início</a>
        <span>/</span>
        <span>{{ $bairro->municipio?->estado?->uf }}</span>
        <span>/</span>
        <span>{{ $bairro->municipio?->nome }}</span>
        <span>/</span>
        <span class="text-gray-700 font-medium">{{ $bairro->nome }}</span>
    </nav>

    {{-- Hero do bairro --}}
    <div class="bg-gradient-to-br from-[#005CA9] to-blue-800 rounded-3xl text-white px-8 py-10 space-y-3">
        <div class="flex items-center gap-2 text-blue-200 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            {{ $bairro->municipio?->nome }} — {{ $bairro->municipio?->estado?->uf }}
        </div>
        <h1 class="text-3xl sm:text-4xl font-black">{{ $titulo }}</h1>
        @if($imoveis->total() > 0)
            <p class="text-blue-100 text-sm">
                {{ $imoveis->total() }} {{ Str::plural('imóvel', $imoveis->total()) }} disponível{{ $imoveis->total() > 1 ? 'is' : '' }} da Caixa Econômica Federal
            </p>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Conteúdo IA --}}
        @if($texto)
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
                <h2 class="text-lg font-black text-gray-900 mb-4">Sobre o bairro {{ $bairro->nome }}</h2>
                <div class="prose prose-sm max-w-none text-gray-600 leading-relaxed space-y-4">
                    @foreach(array_filter(explode("\n\n", $texto)) as $paragrafo)
                        <p>{{ $paragrafo }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Sidebar info --}}
        <div class="space-y-4">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-3">
                <h3 class="font-bold text-gray-800 text-sm">Informações do bairro</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Município</span>
                        <span class="font-medium text-gray-700">{{ $bairro->municipio?->nome }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Estado</span>
                        <span class="font-medium text-gray-700">{{ $bairro->municipio?->estado?->uf }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Imóveis disponíveis</span>
                        <span class="font-bold text-[#005CA9]">{{ $imoveis->total() }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-[#F39200]/10 border border-[#F39200]/20 rounded-2xl p-5">
                <p class="text-xs font-bold text-[#F39200] uppercase tracking-widest mb-2">Imóveis Caixa</p>
                <p class="text-sm text-gray-700">Todos os imóveis listados são da Caixa Econômica Federal e podem ser financiados via FGTS ou crédito habitacional.</p>
            </div>
        </div>
    </div>

    {{-- Accordion FAQ do Bairro (exibido só quando o conteúdo novo da IA estiver disponível) --}}
    @if($temFaq)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8" x-data="{ openFaq: null }">
            <h2 class="text-xl font-black text-gray-900 mb-6 flex items-center gap-3">
                <span class="bg-[#F39200] w-2 h-7 rounded-full"></span>
                Conheça o Bairro {{ $bairro->nome }}
            </h2>
            <div class="space-y-3">
                @php
                $faqItens = [
                    ['vizinhanca_localizacao', 'Vizinhança e Localização'],
                    ['beneficios',             'Benefícios'],
                    ['acessos_transporte',     'Acessos e Transporte'],
                    ['comercio_conveniencia',  'Comércio e Conveniência'],
                    ['educacao',               'Educação'],
                    ['saude',                  'Saúde'],
                    ['lazer_cultura',          'Lazer e Cultura'],
                    ['dados_infraestrutura',   'Infraestrutura'],
                ];
                @endphp
                @foreach($faqItens as $i => [$campo, $label])
                    @if(!empty($conteudo[$campo]))
                        <div class="border border-gray-200 rounded-2xl overflow-hidden">
                            <button @click="openFaq = openFaq === {{ $i }} ? null : {{ $i }}"
                                    class="w-full px-6 py-4 flex justify-between items-center bg-gray-50 hover:bg-gray-100/80 text-left transition font-bold text-gray-800 text-sm">
                                <span>{{ $label }}</span>
                                <span class="text-[#005CA9] text-xs transition-transform duration-300" :class="openFaq === {{ $i }} ? 'rotate-180' : ''">▼</span>
                            </button>
                            <div x-show="openFaq === {{ $i }}" x-collapse class="px-6 py-5 bg-white border-t border-gray-100 text-sm text-gray-600 leading-relaxed">
                                <p>{{ $conteudo[$campo] }}</p>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    {{-- Grid de imóveis --}}
    @if($imoveis->isNotEmpty())
        <div>
            <h2 class="text-xl font-black text-gray-900 mb-6">
                Imóveis disponíveis em {{ $bairro->nome }}
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($imoveis as $imovel)
                    <a href="{{ route('imovel.show', $imovel) }}"
                       class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all group">
                        <div class="aspect-[4/3] bg-gray-100 relative overflow-hidden">
                            <img src="{{ $imovel->foto_fachada_url ?? asset('images/imovel-placeholder.svg') }}"
                                 alt="{{ $imovel->numero_original }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                 onerror="this.src='{{ asset('images/imovel-placeholder.svg') }}'">
                        </div>
                        <div class="p-4 space-y-1">
                            <p class="text-xs text-gray-400 font-mono"># {{ $imovel->numero_original }}</p>
                            <p class="text-sm font-bold text-gray-800 line-clamp-1">{{ $imovel->tipoImovel?->nome ?? 'Imóvel' }}</p>
                            @if($imovel->ultimoHistorico?->valor_venda)
                                <p class="text-base font-black text-[#005CA9]">
                                    R$ {{ number_format($imovel->ultimoHistorico->valor_venda, 0, ',', '.') }}
                                </p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            @if($imoveis->hasPages())
                <div class="mt-8">
                    {{ $imoveis->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="text-center py-12 text-gray-400 text-sm">
            Nenhum imóvel disponível neste bairro no momento.
        </div>
    @endif

</div>

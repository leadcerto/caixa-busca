@if($imoveis->count() > 0)
@push('preload')
<link rel="preload" as="image" href="{{ $imoveis->first()->foto_fachada_url ?? asset('images/imovel-placeholder.svg') }}">
@endpush
@endif

<!-- Vitrine de Imóveis -->
<div class="bg-slate-50 min-h-screen py-12 px-6">

    <!-- Filtros de Busca -->
    <div class="max-w-7xl mx-auto mb-12">
        <div class="bg-white p-8 md:p-12 rounded-[3rem] shadow-[0_20px_60px_-15px_rgba(0,92,169,0.15)] border border-blue-50/50 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-blue-50/20 rounded-full"></div>

            <h2 class="text-[#005CA9] text-3xl font-black mb-8 flex items-center">
                <span class="bg-[#F39200] w-2.5 h-8 mr-4 rounded-full"></span>
                Encontre sua Oportunidade
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-end">

                <!-- Número do Imóvel -->
                <div class="space-y-2">
                    <label class="block text-[#005CA9] text-[10px] font-black uppercase tracking-widest">Número do Imóvel</label>
                    <input type="text" wire:model.live.debounce.300ms="busca_numero" placeholder="Ex: 8555510834062..."
                           class="w-full bg-[#f8fafc] border border-slate-200 text-slate-900 placeholder-slate-400 rounded-2xl focus:ring-2 focus:ring-[#F39200] focus:border-[#F39200] focus:bg-white h-14 px-5 transition duration-200">
                </div>

                <!-- Estado -->
                <div class="space-y-2">
                    <label class="block text-[#005CA9] text-[10px] font-black uppercase tracking-widest">Estado (UF)</label>
                    <select wire:model.live="id_estado"
                            class="w-full bg-[#f8fafc] border border-slate-200 text-slate-900 rounded-2xl focus:ring-2 focus:ring-[#F39200] focus:border-[#F39200] focus:bg-white h-14 px-5 appearance-none cursor-pointer transition duration-200">
                        <option value="">Todos os Estados</option>
                        @foreach($estados as $e)
                            <option value="{{ $e->id }}">{{ $e->uf }} – {{ $e->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tipo de Imóvel -->
                <div class="space-y-2">
                    <label class="block text-[#005CA9] text-[10px] font-black uppercase tracking-widest">Tipo de Imóvel</label>
                    <select wire:model.live="tipo"
                            class="w-full bg-[#f8fafc] border border-slate-200 text-slate-900 rounded-2xl focus:ring-2 focus:ring-[#F39200] focus:border-[#F39200] focus:bg-white h-14 px-5 appearance-none cursor-pointer transition duration-200">
                        <option value="">Todos os Tipos</option>
                        @foreach($tipos as $t)
                            <option value="{{ $t->nome }}">{{ $t->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Cidade -->
                <div class="space-y-2">
                    <label class="block text-[#005CA9] text-[10px] font-black uppercase tracking-widest">Cidade</label>
                    <select wire:model.live="id_municipio"
                            {{ empty($municipios) ? 'disabled' : '' }}
                            class="w-full bg-[#f8fafc] border border-slate-200 text-slate-950 rounded-2xl focus:ring-2 focus:ring-[#F39200] focus:border-[#F39200] focus:bg-white h-14 px-5 appearance-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed transition duration-200">
                        <option value="">Selecione uma Cidade</option>
                        @foreach($municipios as $m)
                            <option value="{{ $m->id }}">{{ $m->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Bairro: placeholder no grid quando sem cidade -->
                @if(empty($bairros))
                <div class="space-y-2">
                    <label class="block text-[#005CA9] text-[10px] font-black uppercase tracking-widest">Bairro</label>
                    <div class="w-full bg-[#f8fafc] border border-slate-200 rounded-2xl h-14 px-5 flex items-center text-slate-400 text-sm font-semibold opacity-60 cursor-not-allowed select-none">
                        Selecione uma Cidade
                    </div>
                </div>
                @endif

                <!-- Preço Mínimo -->
                <div class="space-y-2">
                    <label class="block text-[#005CA9] text-[10px] font-black uppercase tracking-widest">Preço Mínimo (R$)</label>
                    <input type="text" wire:model.live.debounce.300ms="preco_min" placeholder="Ex: 100000"
                           class="w-full bg-[#f8fafc] border border-slate-200 text-slate-900 placeholder-slate-400 rounded-2xl focus:ring-2 focus:ring-[#F39200] focus:border-[#F39200] focus:bg-white h-14 px-5 transition duration-200">
                </div>

                <!-- Preço Máximo -->
                <div class="space-y-2">
                    <label class="block text-[#005CA9] text-[10px] font-black uppercase tracking-widest">Preço Máximo (R$)</label>
                    <input type="text" wire:model.live.debounce.300ms="preco_max" placeholder="Ex: 500000"
                           class="w-full bg-[#f8fafc] border border-slate-200 text-slate-900 placeholder-slate-400 rounded-2xl focus:ring-2 focus:ring-[#F39200] focus:border-[#F39200] focus:bg-white h-14 px-5 transition duration-200">
                </div>

                <!-- Financiamento -->
                <div class="space-y-2">
                    <label class="block text-[#005CA9] text-[10px] font-black uppercase tracking-widest">Financiamento / FGTS</label>
                    <select wire:model.live="financiamento"
                            class="w-full bg-[#f8fafc] border border-slate-200 text-slate-900 rounded-2xl focus:ring-2 focus:ring-[#F39200] focus:border-[#F39200] focus:bg-white h-14 px-5 appearance-none cursor-pointer transition duration-200">
                        <option value="todos">Mostrar Todos</option>
                        <option value="sim">Somente com Financiamento</option>
                    </select>
                </div>

                <!-- Ordenação -->
                <div class="space-y-2">
                    <label class="block text-[#005CA9] text-[10px] font-black uppercase tracking-widest">Ordenar Por</label>
                    <select wire:model.live="ordenacao"
                            class="w-full bg-[#f8fafc] border border-slate-200 text-slate-900 rounded-2xl focus:ring-2 focus:ring-[#F39200] focus:border-[#F39200] focus:bg-white h-14 px-5 appearance-none cursor-pointer transition duration-200">
                        <option value="recente">Mais Recentes</option>
                        <option value="desconto_pct_desc">Maior % de Desconto</option>
                        <option value="desconto_reais_desc">Maior Desconto (R$)</option>
                        <option value="preco_asc">Menor Preço de Venda</option>
                        <option value="preco_desc">Maior Preço de Venda</option>
                    </select>
                </div>

            </div>

            <!-- Bairros: pills visíveis (exibido quando há cidade selecionada) -->
            @if(!empty($bairros))
            <div class="mt-8 pt-6 border-t border-blue-50">
                <div class="flex items-center justify-between mb-3">
                    <label class="text-[#005CA9] text-[10px] font-black uppercase tracking-widest">
                        Bairro
                        @if(!empty($bairros_selecionados))
                            <span class="ml-2 bg-[#005CA9] text-white text-[9px] font-black px-2 py-0.5 rounded-full">{{ count($bairros_selecionados) }} selecionado(s)</span>
                        @endif
                    </label>
                    <div class="flex gap-4 text-xs">
                        <button type="button" wire:click="selecionarTodosBairros" class="text-[#005CA9] font-bold hover:underline cursor-pointer">Selecionar Todos</button>
                        <button type="button" wire:click="limparBairrosSelecionados" class="text-gray-400 font-bold hover:text-red-500 hover:underline cursor-pointer transition-colors">Limpar</button>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach($bairros as $b)
                        <label class="cursor-pointer">
                            <input type="checkbox"
                                   wire:model.live="bairros_selecionados"
                                   value="{{ $b->id }}"
                                   class="sr-only peer">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-[11px] font-bold border transition-all duration-150 select-none cursor-pointer
                                         border-slate-200 bg-[#f8fafc] text-slate-600
                                         hover:border-[#F39200] hover:text-[#F39200]
                                         peer-checked:bg-[#005CA9] peer-checked:text-white peer-checked:border-[#005CA9]">
                                {{ $b->nome }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Botoes inferiores dos filtros -->
            <div class="mt-10 flex justify-end gap-4">
                <button type="button" wire:click="limparFiltros"
                        class="h-14 bg-slate-50 hover:bg-slate-100 text-[#005CA9] font-bold rounded-2xl transition-all border border-slate-200 px-8 text-sm cursor-pointer">
                    Limpar Filtros
                </button>
                <button type="button" wire:click="buscar"
                        class="h-14 bg-[#F39200] hover:bg-[#d67e00] text-white flex items-center justify-center rounded-2xl shadow-lg shadow-orange-500/20 font-black uppercase text-xs tracking-wider px-10 transition-all duration-300 cursor-pointer">
                    BUSCAR
                </button>
            </div>

        </div>
    </div>

    <!-- Resultados -->
    @if($show_results)

        {{-- ── Gerador de URL para anúncios ──────────────────────────────────── --}}
        @if($id_estado && $tipo)
        @php
            $urlEstadoObj    = collect($estados)->firstWhere('id', $id_estado);
            $urlMunicipioObj = $id_municipio ? $municipios->firstWhere('id', $id_municipio) : null;
            $urlGerada       = null;

            if ($urlEstadoObj) {
                $segs = [
                    rtrim(url('/'), '/'),
                    'imoveis',
                    \Illuminate\Support\Str::slug($tipo),
                    strtolower($urlEstadoObj->uf),
                ];
                if ($urlMunicipioObj) {
                    $segs[] = \Illuminate\Support\Str::slug($urlMunicipioObj->nome);
                }
                $urlGerada = implode('/', $segs);

                $qs = [];
                if ($financiamento === 'sim') $qs[] = 'financiamento[]=fgts';
                if ($preco_min)               $qs[] = 'preco_min=' . rawurlencode($preco_min);
                if ($preco_max)               $qs[] = 'preco_max=' . rawurlencode($preco_max);
                if ($qs) $urlGerada .= '?' . implode('&', $qs);
            }
        @endphp
        @if($urlGerada)
        <div class="max-w-7xl mx-auto px-6 mb-6"
             x-data="{ copiado: false, urlAnuncio: {{ \Illuminate\Support\Js::from($urlGerada) }} }">
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 flex flex-col sm:flex-row items-start sm:items-center gap-3">
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] font-black uppercase tracking-widest text-[#005CA9] mb-1.5">
                        🔗 Link pronto para anúncio
                    </p>
                    <p class="text-xs text-gray-700 font-mono break-all bg-white border border-blue-100 rounded-xl px-3 py-2 select-all"
                       x-text="urlAnuncio"></p>
                </div>
                <button type="button"
                        x-on:click="navigator.clipboard.writeText(urlAnuncio).then(() => { copiado = true; setTimeout(() => copiado = false, 2500) })"
                        class="shrink-0 font-black text-xs px-5 py-3 rounded-xl transition-all cursor-pointer"
                        :class="copiado ? 'bg-emerald-500 text-white' : 'bg-[#005CA9] text-white hover:bg-[#004a8a]'">
                    <span x-text="copiado ? '✓ Copiado!' : 'Copiar link'"></span>
                </button>
            </div>
        </div>
        @endif
        @endif

        <div class="max-w-7xl mx-auto" x-data x-init="$el.scrollIntoView({ behavior: 'smooth' })">

            @if($imoveis->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($imoveis as $imovel)
                        @php
                            $historico = $imovel->ultimoHistorico;
                            $valorAvaliacao = $historico?->valor_avaliacao ?? 0;
                            $valorVenda = $historico?->valor_venda ?? 0;
                            $descontoPct = $historico?->desconto_percentual ?? 0;
                            $valorLucro = $historico?->desconto_valor ?? ($valorAvaliacao - $valorVenda);
                            if ($valorLucro < 0) {
                                $valorLucro = 0;
                            }
                            
                            $aceitaFgts = ($imovel->aceita_fgts === 'sim');
                            $aceitaFinanciamento = ($imovel->aceita_fgts === 'sim');
                            
                            $tipoNome = $imovel->tipoImovel?->nome ?? 'Imóvel';
                            $bairroNome = $imovel->bairro?->nome ?? 'Bairro não inf.';
                            $cidadeNome = $imovel->municipio?->nome ?? '';
                            $uf = $imovel->estado?->uf ?? '';
                            $endereco = $imovel->endereco;
                            $codigo = $imovel->numero_original;
                            
                            $isExtraordinario = ($descontoPct >= 40);
                        @endphp
                        
                        <a href="{{ route('imovel.show', $imovel->slug) }}"
                           target="_blank" rel="noopener noreferrer"
                           class="bg-white rounded-[2rem] overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 group border border-gray-100 flex flex-col justify-between h-full">

                            <div>
                                <!-- Imagem do Imovel -->
                                <div class="relative aspect-[4/3] overflow-hidden rounded-t-[2rem]">
                                    <img src="{{ $imovel->foto_fachada_url ?? asset('images/imovel-placeholder.svg') }}"
                                         alt="{{ $tipoNome }} em {{ $cidadeNome }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
                                         loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                         fetchpriority="{{ $loop->first ? 'high' : 'auto' }}"
                                         onerror="this.onerror=null;this.src='{{ asset('images/imovel-placeholder.svg') }}';">
                                </div>

                                <!-- Grid de Status Badges (Grade Simétrica 2x2) -->
                                <div class="grid grid-cols-2 gap-2 px-5 mt-4">
                                    <!-- Badge 1: FGTS -->
                                    @if($aceitaFgts)
                                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 font-extrabold text-xs px-3 py-2.5 rounded-xl flex items-center justify-between shadow-sm h-11"
                                             style="background-color: #ECFDF5; border-color: #A7F3D0; color: #047857;">
                                            <span>FGTS</span>
                                            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="3.5" viewBox="0 0 24 24" style="color: #10B981;">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    @else
                                        <div class="bg-rose-50 border border-rose-100 text-rose-600 font-bold text-xs px-3 py-2.5 rounded-xl flex items-center justify-between h-11"
                                             style="background-color: #FFF1F2; border-color: #FECDD3; color: #E11D48;">
                                            <span>FGTS</span>
                                            <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" stroke-width="3.5" viewBox="0 0 24 24" style="color: #F43F5E;">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                    @endif

                                    <!-- Badge 2: Financiamento -->
                                    @if($aceitaFinanciamento)
                                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 font-extrabold text-xs px-3 py-2.5 rounded-xl flex items-center justify-between shadow-sm h-11"
                                             style="background-color: #ECFDF5; border-color: #A7F3D0; color: #047857;">
                                            <span>Financiamento</span>
                                            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="3.5" viewBox="0 0 24 24" style="color: #10B981;">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    @else
                                        <div class="bg-rose-50 border border-rose-100 text-rose-600 font-bold text-xs px-3 py-2.5 rounded-xl flex items-center justify-between h-11"
                                             style="background-color: #FFF1F2; border-color: #FECDD3; color: #E11D48;">
                                            <span>Financiamento</span>
                                            <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" stroke-width="3.5" viewBox="0 0 24 24" style="color: #F43F5E;">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                    @endif

                                    <!-- Badge 3: Tipo de Imóvel -->
                                    <div class="text-white font-black text-xs px-3 py-2.5 rounded-xl flex items-center justify-center shadow-sm h-11" style="background-color: #005CA9; color: #ffffff;">
                                        <span class="truncate">{{ $tipoNome }}</span>
                                    </div>

                                    <!-- Badge 4: Oportunidade (Gradientes Metálicos) -->
                                    @php
                                        if ($descontoPct >= 40) {
                                            $badgeStyle = 'background: linear-gradient(135deg, #FEF08A 0%, #FBBF24 50%, #CA8A04 100%) !important; border: 1px solid #F59E0B !important; color: #78350F !important; font-weight: 900 !important; text-shadow: none !important;';
                                            $badgeText = 'Ouro';
                                            $badgeIcon = '⭐';
                                        } elseif ($descontoPct >= 30) {
                                            $badgeStyle = 'background: linear-gradient(135deg, #CBD5E1 0%, #64748B 100%) !important; border: 1px solid #94A3B8 !important; color: #ffffff !important;';
                                            $badgeText = 'Prata';
                                            $badgeIcon = '🥈';
                                        } elseif ($descontoPct >= 20) {
                                            $badgeStyle = 'background: linear-gradient(135deg, #FCA5A5 0%, #EF4444 50%, #991B1B 100%) !important; border: 1px solid #EF4444 !important; color: #ffffff !important; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.4) !important;';
                                            $badgeText = 'Bronze';
                                            $badgeIcon = '🥉';
                                        } else {
                                            $badgeStyle = 'background: linear-gradient(135deg, #10B981 0%, #047857 100%) !important; border: 1px solid #10B981 !important; color: #ffffff !important;';
                                            $badgeText = 'Selecionado';
                                            $badgeIcon = '🏷️';
                                        }
                                    @endphp
                                    <div class="font-black text-[11px] px-2 py-2.5 rounded-xl flex items-center justify-center gap-1 shadow-sm h-11 w-full"
                                         style="{{ $badgeStyle }}">
                                        <span class="text-base shrink-0 leading-none">{{ $badgeIcon }}</span>
                                        <span class="whitespace-nowrap leading-none">{{ $badgeText }}</span>
                                    </div>
                                </div>

                                <!-- Textos Detalhes -->
                                <div class="text-center mt-6 px-4">
                                    <h3 class="text-[#005CA9] font-black text-2xl tracking-tight text-center leading-tight">
                                        {{ $cidadeNome }} - {{ $uf }}
                                    </h3>
                                    <p class="text-[#005CA9] font-black text-lg text-center mt-1">
                                        {{ $bairroNome }}
                                    </p>
                                </div>

                                <p class="text-slate-800 font-extrabold text-xs uppercase tracking-normal text-center mt-4 px-5 line-clamp-2 min-h-[2rem]">
                                    {{ $endereco }}
                                </p>

                                <div class="text-center mt-3 px-5">
                                    <div class="text-gray-400 text-xs font-semibold">Imóvel: {{ $codigo }}</div>
                                    
                                    <div class="text-[#005CA9] text-base font-semibold mt-2">
                                        De: R$ {{ number_format($valorAvaliacao, 2, ',', '.') }}
                                    </div>
                                    
                                    <div class="mt-3">
                                        <div class="text-[#005CA9] text-xs font-extrabold uppercase tracking-wider block w-full">Por:</div>
                                        <div class="text-[#005CA9] text-2xl font-black mt-0.5 block w-full whitespace-nowrap">
                                            R$ {{ number_format($valorVenda, 2, ',', '.') }}
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <div class="text-[#E50000] text-xs font-extrabold uppercase tracking-wider block w-full">Lucro:</div>
                                        <div class="text-[#E50000] text-2xl font-black mt-0.5 block w-full whitespace-nowrap">
                                            R$ {{ number_format($valorLucro, 2, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botao Saiba Mais -->
                            <div class="px-5 pb-6 mt-6">
                                <div class="w-full bg-[#F39200] hover:bg-[#d67e00] text-white font-black text-lg py-4 rounded-2xl text-center shadow-md transition-all duration-300 group-hover:scale-[1.02] uppercase tracking-wide">
                                    SAIBA MAIS
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-16">
                    {{ $imoveis->links() }}
                </div>

            @else
                <div class="bg-white rounded-[3rem] p-24 text-center shadow-xl shadow-gray-200/50 border border-gray-100 max-w-3xl mx-auto">
                    <div class="w-32 h-32 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-8 text-[#005CA9]/30">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-3xl font-black text-gray-800">Ops! Nada por aqui.</h3>
                    <p class="text-gray-500 mt-4 text-lg">Não encontramos imóveis com esses filtros. Tente uma busca mais ampla.</p>

                    <button type="button" wire:click="limparFiltros"
                            class="mt-10 bg-[#005CA9] hover:bg-[#F39200] text-white font-black py-5 px-10 rounded-[2rem] shadow-xl shadow-blue-200 transition-all duration-500 uppercase text-xs tracking-widest">
                        Limpar Filtros e Ver Tudo
                    </button>
                </div>
            @endif
        </div>
    @endif
</div>

@push('schema')
@php
$listItems = [];
foreach ($imoveis as $i => $item) {
    $listItems[] = [
        '@type'    => 'ListItem',
        'position' => $imoveis->firstItem() + $i,
        'url'      => url('/' . $item->slug),
        'name'     => trim(($item->tipoImovel->nome ?? 'Imóvel') . ' em ' . ($item->municipio->nome ?? '') . ', ' . ($item->estado->uf ?? '')),
    ];
}
$schemaPage = [
    '@context'   => 'https://schema.org',
    '@type'      => 'SearchResultsPage',
    'name'       => 'Imóveis da Caixa Econômica Federal',
    'url'        => url('/'),
    'mainEntity' => [
        '@type'           => 'ItemList',
        'name'            => 'Resultados da busca de imóveis',
        'numberOfItems'   => $imoveis->total(),
        'itemListElement' => $listItems,
    ],
];
@endphp
<script type="application/ld+json">{!! json_encode($schemaPage, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
@endpush

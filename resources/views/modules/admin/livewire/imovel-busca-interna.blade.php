<div class="py-10 px-6 lg:px-10 space-y-10">
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="mb-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight flex items-center">
                    <span class="w-3.5 h-8 bg-gradient-to-b from-[#005CA9] to-blue-500 mr-4 rounded-full shadow-md"></span>
                    Busca Interna de Imóveis
                </h1>
                <p class="text-slate-500 mt-2 text-sm">Pesquise, filtre e classifique toda a base de dados de imóveis da Caixa de forma avançada.</p>
            </div>
            <div>
                <span class="bg-[#005CA9]/10 text-[#005CA9] font-black text-xs px-4 py-2.5 rounded-2xl border border-[#005CA9]/15 shadow-sm uppercase tracking-wider">
                    Total: {{ $imoveis->total() }} imóveis ativos
                </span>
            </div>
        </div>

        <!-- Painel de Filtros -->
        <div class="bg-white p-8 md:p-12 rounded-[3rem] shadow-[0_20px_60px_-15px_rgba(0,92,169,0.15)] border border-blue-50/50 relative overflow-hidden mb-8">
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-blue-50/20 rounded-full"></div>

            <h2 class="text-[#005CA9] text-3xl font-black mb-8 flex items-center relative">
                <span class="bg-[#F39200] w-2.5 h-8 mr-4 rounded-full"></span>
                Filtros de Pesquisa Avançados
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative">

                <!-- Número do Imóvel -->
                <div class="space-y-2">
                    <label class="block text-[#005CA9] text-[10px] font-black uppercase tracking-widest">Número do Imóvel</label>
                    <input type="text"
                           wire:model.live.debounce.300ms="busca_numero"
                           placeholder="Ex: 8555510834062…"
                           class="w-full bg-[#f8fafc] border border-slate-200 text-slate-900 placeholder-slate-400 rounded-2xl focus:ring-2 focus:ring-[#F39200] focus:border-[#F39200] focus:bg-white h-14 px-5 transition duration-200">
                </div>

                <!-- Estado -->
                <div class="space-y-2">
                    <label class="block text-[#005CA9] text-[10px] font-black uppercase tracking-widest">Estado (UF)</label>
                    <select wire:model.live="id_estado"
                            class="w-full bg-[#f8fafc] border border-slate-200 text-slate-900 rounded-2xl focus:ring-2 focus:ring-[#F39200] focus:border-[#F39200] focus:bg-white h-14 px-5 appearance-none cursor-pointer transition duration-200">
                        <option value="">Selecione um Estado</option>
                        @foreach($estados as $e)
                            <option value="{{ $e->id }}">{{ $e->nome }} ({{ $e->uf }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Cidade -->
                <div class="space-y-2">
                    <label class="block text-[#005CA9] text-[10px] font-black uppercase tracking-widest">Cidade</label>
                    <select wire:model.live="id_municipio"
                            {{ empty($municipios) ? 'disabled' : '' }}
                            class="w-full bg-[#f8fafc] border border-slate-200 text-slate-900 rounded-2xl focus:ring-2 focus:ring-[#F39200] focus:border-[#F39200] focus:bg-white h-14 px-5 appearance-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed transition duration-200">
                        <option value="">Selecione uma Cidade</option>
                        @foreach($municipios as $m)
                            <option value="{{ $m->id }}">{{ $m->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Financiamento -->
                <div class="space-y-2">
                    <label class="block text-[#005CA9] text-[10px] font-black uppercase tracking-widest">Financiamento / FGTS</label>
                    <select wire:model.live="financiamento"
                            class="w-full bg-[#f8fafc] border border-slate-200 text-slate-900 rounded-2xl focus:ring-2 focus:ring-[#F39200] focus:border-[#F39200] focus:bg-white h-14 px-5 appearance-none cursor-pointer transition duration-200">
                        <option value="todos">Exibir Todos</option>
                        <option value="sim">Somente que aceitam Financiamento / FGTS</option>
                    </select>
                </div>

                <!-- Preço Mínimo -->
                <div class="space-y-2">
                    <label class="block text-[#005CA9] text-[10px] font-black uppercase tracking-widest">Preço Mínimo (R$)</label>
                    <input type="text"
                           wire:model.live.debounce.300ms="preco_min"
                           placeholder="Ex: 100000"
                           class="w-full bg-[#f8fafc] border border-slate-200 text-slate-900 placeholder-slate-400 rounded-2xl focus:ring-2 focus:ring-[#F39200] focus:border-[#F39200] focus:bg-white h-14 px-5 transition duration-200">
                </div>

                <!-- Preço Máximo -->
                <div class="space-y-2">
                    <label class="block text-[#005CA9] text-[10px] font-black uppercase tracking-widest">Preço Máximo (R$)</label>
                    <input type="text"
                           wire:model.live.debounce.300ms="preco_max"
                           placeholder="Ex: 500000"
                           class="w-full bg-[#f8fafc] border border-slate-200 text-slate-900 placeholder-slate-400 rounded-2xl focus:ring-2 focus:ring-[#F39200] focus:border-[#F39200] focus:bg-white h-14 px-5 transition duration-200">
                </div>

                <!-- Ordenar Por -->
                <div class="space-y-2">
                    <label class="block text-[#005CA9] text-[10px] font-black uppercase tracking-widest">Ordenar Resultados Por</label>
                    <select wire:model.live="ordenacao"
                            class="w-full bg-[#f8fafc] border border-slate-200 text-slate-900 rounded-2xl focus:ring-2 focus:ring-[#F39200] focus:border-[#F39200] focus:bg-white h-14 px-5 appearance-none cursor-pointer transition duration-200">
                        <option value="recente">Mais Recentes</option>
                        <option value="desconto_pct_desc">Maior % de Desconto</option>
                        <option value="desconto_reais_desc">Maior Valor R$ Economizado</option>
                        <option value="preco_asc">Menor Preço de Venda</option>
                        <option value="preco_desc">Maior Preço de Venda</option>
                    </select>
                </div>

            </div>

            <!-- Bairros em colunas (exibido quando há cidade selecionada) -->
            @if(!empty($bairros))
            <div class="mt-8 pt-6 border-t border-blue-50 relative">
                <div class="flex items-center justify-between mb-4">
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
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
                    @foreach($bairros as $b)
                        <label class="cursor-pointer">
                            <input type="checkbox"
                                   wire:model.live="bairros_selecionados"
                                   value="{{ $b->id }}"
                                   class="sr-only peer">
                            <span class="block text-center px-2 py-2 rounded-xl text-[11px] font-bold border transition-all duration-150 select-none cursor-pointer truncate
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

            <!-- Gerador de URL para anúncios -->
            @if($id_estado)
            @php
                $urlEstadoObj    = collect($estados)->firstWhere('id', $id_estado);
                $urlMunicipioObj = $id_municipio ? collect($municipios)->firstWhere('id', $id_municipio) : null;
                $urlGerada       = null;

                if ($urlEstadoObj) {
                    $segs = [rtrim(url('/'), '/'), 'imoveis', strtolower($urlEstadoObj->uf)];
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
            <div class="mt-8 pt-6 border-t border-blue-50 relative"
                 x-data="{ copiado: false, urlAnuncio: {{ \Illuminate\Support\Js::from($urlGerada) }} }">
                <p class="text-[#005CA9] text-[10px] font-black uppercase tracking-widest mb-3">
                    Link para anúncio
                </p>
                <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                    <p class="flex-1 text-xs text-slate-700 font-mono break-all bg-[#f8fafc] border border-slate-200 rounded-2xl px-5 py-3.5 select-all"
                       x-text="urlAnuncio"></p>
                    <button type="button"
                            x-on:click="navigator.clipboard.writeText(urlAnuncio).then(() => { copiado = true; setTimeout(() => copiado = false, 2500) })"
                            class="shrink-0 font-black text-xs px-6 h-14 rounded-2xl transition-all cursor-pointer"
                            :class="copiado ? 'bg-emerald-500 text-white' : 'bg-[#F39200] text-white hover:bg-[#d67e00]'">
                        <span x-text="copiado ? '✓ Copiado!' : 'Copiar Link'"></span>
                    </button>
                </div>
            </div>
            @endif
            @endif

            <!-- Botão Limpar -->
            <div class="mt-10 flex justify-end relative">
                <button type="button" wire:click="limparFiltros"
                        class="h-14 bg-slate-50 hover:bg-slate-100 text-[#005CA9] font-bold rounded-2xl transition-all border border-slate-200 px-8 text-sm cursor-pointer">
                    Limpar Filtros
                </button>
            </div>
        </div>

        <!-- Listagem de Resultados Inferior -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <span class="text-slate-800 font-black text-sm uppercase tracking-wider">Imóveis Encontrados</span>
                <span class="text-slate-400 font-bold text-xs">Exibindo página {{ $imoveis->currentPage() }} de {{ $imoveis->lastPage() }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/30">
                            <th class="py-5 px-8 text-[10px] font-black uppercase tracking-widest text-slate-400">Imóvel / Código</th>
                            <th class="py-5 px-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Localização</th>
                            <th class="py-5 px-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Características</th>
                            <th class="py-5 px-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Valores / Economia</th>
                            <th class="py-5 px-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Financiamento</th>
                            <th class="py-5 px-8 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($imoveis as $imovel)
                            @php
                                $ultimo = $imovel->ultimoHistorico;
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition">
                                <!-- Código / Tipo -->
                                <td class="py-5 px-8">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center shrink-0 shadow-inner">
                                            <svg class="w-5 h-5 text-[#005CA9]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="text-xs font-black text-[#005CA9] block tracking-tighter">#{{ $imovel->numero_original }}</span>
                                            <span class="text-sm font-semibold text-slate-800 block mt-0.5">{{ $imovel->tipoImovel?->nome ?? 'Imóvel' }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Localização -->
                                <td class="py-5 px-6">
                                    <div>
                                        <span class="text-sm font-medium text-slate-700 block">{{ $imovel->municipio?->nome ?? '—' }} / {{ $imovel->estado?->uf ?? '—' }}</span>
                                        <span class="text-xs text-slate-400 block mt-0.5 truncate max-w-xs">{{ $imovel->bairro?->nome ?? 'Bairro não especificado' }}</span>
                                    </div>
                                </td>

                                <!-- Características -->
                                <td class="py-5 px-6">
                                    <div class="space-y-1">
                                        <div class="flex items-center space-x-1.5 text-xs text-slate-600">
                                            <span class="font-bold text-slate-800">{{ $imovel->area_total ? number_format($imovel->area_total, 0, ',', '.') . ' m²' : '—' }}</span>
                                            <span class="text-slate-400">•</span>
                                            <span>{{ $imovel->quartos ?? 0 }} quarto(s)</span>
                                        </div>
                                        <span class="text-[10px] bg-slate-100 text-slate-500 font-bold px-2 py-0.5 rounded-md uppercase tracking-wider block w-max">
                                            {{ $ultimo?->modalidade?->nome ?? 'Caixa' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Valores / Economia -->
                                <td class="py-5 px-6">
                                    <div>
                                        <span class="text-xs text-slate-400 block line-through">Avaliação: R$ {{ number_format($ultimo?->valor_avaliacao ?? 0, 2, ',', '.') }}</span>
                                        <span class="text-sm font-black text-emerald-600 block mt-0.5">Venda: R$ {{ number_format($ultimo?->valor_venda ?? 0, 2, ',', '.') }}</span>
                                        
                                        @if($ultimo && $ultimo->desconto_percentual > 0)
                                            <div class="flex items-center space-x-2 mt-1">
                                                <span class="text-[10px] bg-emerald-100 text-emerald-700 font-black px-2 py-0.5 rounded-full uppercase">
                                                    {{ number_format($ultimo->desconto_percentual, 0) }}% OFF
                                                </span>
                                                <span class="text-[10px] text-slate-500 font-semibold">
                                                    Salva R$ {{ number_format($ultimo->desconto_valor, 2, ',', '.') }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Financiamento -->
                                <td class="py-5 px-6">
                                    @if($imovel->aceita_fgts === 'sim')
                                        <span class="text-[10px] bg-green-50 text-green-700 font-black px-2.5 py-1 rounded-full uppercase tracking-wider border border-green-200/50 flex items-center w-max space-x-1">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                            <span>Aceita Financiamento</span>
                                        </span>
                                    @else
                                        <span class="text-[10px] bg-slate-100 text-slate-400 font-black px-2.5 py-1 rounded-full uppercase tracking-wider flex items-center w-max">
                                            <span>Não Informado</span>
                                        </span>
                                    @endif
                                </td>

                                <!-- Ações -->
                                <td class="py-5 px-8 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <!-- PDF da Matrícula (RGI) -->
                                        <a href="{{ $imovel->link_matricula }}"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="bg-emerald-50 hover:bg-emerald-100 text-emerald-600 p-2 rounded-xl transition-all duration-200 border border-emerald-100 hover:scale-105"
                                           title="Visualizar Matrícula (RGI) PDF">
                                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </a>

                                        <!-- Ver no Site da Caixa -->
                                        @if($imovel->link_edital)
                                            <a href="{{ $imovel->link_edital }}"
                                               target="_blank"
                                               rel="noopener noreferrer"
                                               class="bg-blue-50 hover:bg-blue-100 text-[#005CA9] p-2 rounded-xl transition-all duration-200 border border-blue-100 hover:scale-105"
                                               title="Ver Imóvel no Site da Caixa">
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                            </a>
                                        @endif

                                        <!-- Visualizar Detalhes Público -->
                                        <a href="{{ route('imovel.show', ['imovel' => $imovel->slug]) }}"
                                           target="_blank"
                                           class="bg-slate-100 hover:bg-[#005CA9] hover:text-white text-slate-700 font-bold px-4 py-2 rounded-xl transition-all duration-200 text-xs flex items-center space-x-1.5">
                                            <span>Ficha</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-16 px-8 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-3">
                                        <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 shadow-inner">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <p class="text-slate-500 font-semibold text-sm">Nenhum imóvel corresponde aos filtros selecionados.</p>
                                        <button wire:click="limparFiltros" class="text-xs font-bold text-[#005CA9] hover:underline">Limpar Filtros e Tentar Novamente</button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginação Premium -->
            @if($imoveis->hasPages())
                <div class="px-8 py-6 border-t border-slate-100 bg-slate-50/30">
                    {{ $imoveis->links() }}
                </div>
            @endif
        </div>

    </div>
</div>

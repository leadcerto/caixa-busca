<div class="bg-slate-50 min-h-screen py-12 px-6">
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

        <!-- Painel de Filtros Superiores (Premium Glassmorphism Style) -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 md:p-10 mb-8 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-48 h-48 bg-blue-50/50 rounded-full -mt-24 -mr-24 transition-all duration-700 group-hover:scale-125"></div>
            
            <div class="flex items-center space-x-3 mb-6 relative">
                <svg class="w-5 h-5 text-[#005CA9]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                </svg>
                <h2 class="text-xs font-black uppercase tracking-widest text-slate-400">Filtros de Pesquisa Avançados</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 relative">
                <!-- Busca por número -->
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Número do Imóvel</label>
                    <input type="text"
                           wire:model.live.debounce.300ms="busca_numero"
                           placeholder="Ex: 8555510834062…"
                           class="w-full border border-slate-200 rounded-2xl h-12 px-4 text-slate-800 text-sm focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition">
                </div>

                <!-- Dropdown Estado -->
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Estado (UF)</label>
                    <select wire:model.live="id_estado"
                            class="w-full border border-slate-200 rounded-2xl h-12 px-4 text-slate-800 text-sm focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition">
                        <option value="">Selecione um Estado</option>
                        @foreach($estados as $e)
                            <option value="{{ $e->id }}">{{ $e->nome }} ({{ $e->uf }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown Cidade -->
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Cidade</label>
                    <select wire:model.live="id_municipio"
                            {{ empty($municipios) ? 'disabled' : '' }}
                            class="w-full border border-slate-200 rounded-2xl h-12 px-4 text-slate-800 text-sm focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition disabled:bg-slate-50 disabled:text-slate-400">
                        <option value="">Selecione uma Cidade</option>
                        @foreach($municipios as $m)
                            <option value="{{ $m->id }}">{{ $m->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown Bairro Multi-Select -->
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Bairro</label>
                    
                    <!-- Dropdown Trigger Button -->
                    <button type="button" 
                            @click="if ({{ empty($bairros) ? 'false' : 'true' }}) open = !open"
                            {{ empty($bairros) ? 'disabled' : '' }}
                            class="w-full border border-slate-200 bg-white rounded-2xl h-12 px-4 flex items-center justify-between text-slate-800 text-sm focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition disabled:bg-slate-50 disabled:text-slate-400 text-left">
                        
                        <span class="truncate pr-2">
                            @if(empty($bairros))
                                Selecione uma Cidade
                            @elseif(empty($bairros_selecionados))
                                Todos os Bairros
                            @else
                                {{ count($bairros_selecionados) }} bairro(s) selecionado(s)
                            @endif
                        </span>
                        
                        <!-- Down Arrow Icon -->
                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Content -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 left-0 z-50 mt-2 bg-white rounded-2xl shadow-xl border border-slate-100 max-h-64 overflow-y-auto p-4 space-y-3"
                         style="display: none;">
                        
                        @if(!empty($bairros))
                            <!-- Utility Actions: Select All / Clear -->
                            <div class="flex items-center justify-between pb-2 border-b border-slate-100 text-xs">
                                <button type="button" wire:click="selecionarTodosBairros" class="text-[#005CA9] font-bold hover:underline">
                                    Selecionar Todos
                                </button>
                                <button type="button" wire:click="limparBairrosSelecionados" class="text-gray-500 font-bold hover:underline">
                                    Limpar
                                </button>
                            </div>

                            <!-- List of Checkboxes -->
                            <div class="space-y-2 max-h-40 overflow-y-auto">
                                @foreach($bairros as $b)
                                    <label class="flex items-center space-x-3 cursor-pointer p-1.5 rounded-lg hover:bg-slate-50 transition">
                                        <input type="checkbox" 
                                               wire:model.live="bairros_selecionados" 
                                               value="{{ $b->id }}"
                                               class="rounded border-slate-300 text-[#005CA9] focus:ring-[#005CA9] w-4.5 h-4.5">
                                        <span class="text-slate-700 text-xs font-semibold select-none">{{ $b->nome }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Preço Mínimo -->
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Preço Mínimo (R$)</label>
                    <input type="text"
                           wire:model.live.debounce.300ms="preco_min"
                           placeholder="Ex: 100000"
                           class="w-full border border-slate-200 rounded-2xl h-12 px-4 text-slate-800 text-sm focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition">
                </div>

                <!-- Preço Máximo -->
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Preço Máximo (R$)</label>
                    <input type="text"
                           wire:model.live.debounce.300ms="preco_max"
                           placeholder="Ex: 500000"
                           class="w-full border border-slate-200 rounded-2xl h-12 px-4 text-slate-800 text-sm focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition">
                </div>

                <!-- Financiamento -->
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Opção de Financiamento</label>
                    <select wire:model.live="financiamento"
                            class="w-full border border-slate-200 rounded-2xl h-12 px-4 text-slate-800 text-sm focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition">
                        <option value="todos">Exibir Todos</option>
                        <option value="sim">Somente que aceitam Financiamento / FGTS</option>
                    </select>
                </div>

                <!-- Ordenação Dinâmica -->
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Ordenar Resultados por</label>
                    <select wire:model.live="ordenacao"
                            class="w-full border border-slate-200 rounded-2xl h-12 px-4 text-slate-800 text-sm focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition font-semibold text-[#005CA9]">
                        <option value="recente">Mais Recentes</option>
                        <option value="desconto_pct_desc">Maior % de Desconto (Descontões)</option>
                        <option value="desconto_reais_desc">Maior Valor R$ Economizado</option>
                        <option value="preco_asc">Menor Preço de Venda</option>
                        <option value="preco_desc">Maior Preço de Venda</option>
                    </select>
                </div>
            </div>

            <!-- Botões de Ação do Painel -->
            <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between relative">
                <div class="text-xs text-slate-400">
                    Dica: selecione o Estado para liberar as Cidades e bairros correspondentes.
                </div>
                <button wire:click="limparFiltros"
                        class="bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 font-bold px-6 py-3 rounded-2xl transition-all duration-200 text-xs flex items-center space-x-2">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Limpar Filtros</span>
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

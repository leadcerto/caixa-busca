<!-- Pgina de Detalhes do Imvel - VLPHP -->
<!-- Regra: Interface de Alta Converso, Sanfonas (Accordions) e LGPD Compliance -->

<div class="bg-gray-50 min-h-screen">
    
    <!-- Hero Section / Header de Destaque -->
    <div class="max-w-7xl mx-auto py-12 px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
            
            <!-- Coluna da Esquerda: Visualizao Crítica -->
            <div class="space-y-8 sticky top-12">
                <div class="rounded-[3.5rem] overflow-hidden shadow-2xl bg-white p-6 border border-gray-100">
                    <div class="relative group">
                        <!-- REGRA 67: Sem lazy loading para a fachada (Carregamento Imediato) -->
                        <img src="{{ $imovel->foto_caixa }}" 
                             alt="{{ $imovel->tipo_imovel }} em {{ $imovel->cidade }}" 
                             class="w-full h-auto rounded-[2.5rem] object-cover shadow-inner"
                             loading="eager">
                        
                        <!-- Selo de Verificao -->
                        <div class="absolute -bottom-4 -right-4 bg-[#005CA9] text-white p-6 rounded-full shadow-2xl border-4 border-white">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coluna da Direita: Inteligncia e Converso -->
            <div class="flex flex-col">
                <!-- Migalhas / Tags -->
                <div class="flex items-center space-x-3 mb-8">
                    <span class="bg-blue-100 text-[#005CA9] text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-full">
                        {{ $imovel->tipo_imovel }}
                    </span>
                    <span class="bg-orange-100 text-[#F39200] text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-full">
                         {{ $imovel->uf }}
                    </span>
                </div>
                
                <h1 class="text-5xl md:text-6xl font-black text-gray-900 leading-[1.1] mb-6 tracking-tighter">
                    {{ $imovel->tipo_imovel }} <br>
                    <span class="text-[#005CA9]">no {{ $imovel->bairro }}</span>
                </h1>
                
                <p class="text-2xl text-gray-400 font-medium mb-10 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                    {{ $imovel->cidade }} - {{ $imovel->uf }}
                </p>

                <!-- Card de Preo em Destaque -->
                <div class="bg-white p-10 rounded-[3rem] shadow-xl shadow-blue-900/5 border border-gray-50 mb-10 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50/50 rounded-full -mt-16 -mr-16 transition-all group-hover:scale-150"></div>
                    
                    <span class="text-gray-400 text-xs uppercase font-black tracking-widest block mb-2">Valor de Investimento</span>
                    <div class="flex items-baseline space-x-2">
                        <span class="text-2xl font-bold text-gray-400">R$</span>
                        <span class="text-6xl font-black text-[#005CA9] tracking-tighter">
                            {{ number_format($imovel->preco, 2, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- Boto de Ao de Alta Converso (Regra 42) -->
                <button wire:click="converterLead" 
                        wire:loading.attr="disabled"
                        class="w-full bg-[#F39200] hover:bg-[#E08600] active:scale-95 text-white font-black py-7 rounded-[2.5rem] shadow-2xl shadow-orange-300/40 transition-all duration-300 flex items-center justify-center space-x-6 text-2xl group relative overflow-hidden">
                    
                    <div wire:loading.remove wire:target="converterLead" class="flex items-center space-x-6">
                        <span>Falar com Corretor</span>
                        <div class="bg-white/20 p-2 rounded-full group-hover:rotate-12 transition-transform">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.417-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.305 1.652zm6.599-3.835c1.522.902 3.222 1.387 5.021 1.388 5.487 0 9.954-4.467 9.956-9.956.002-2.659-1.032-5.159-2.908-7.038-1.876-1.878-4.378-2.913-7.046-2.913-5.483 0-9.95 4.467-9.953 9.956-.001 1.93.566 3.811 1.641 5.393l-.401 1.464 1.69-.443z"/></svg>
                        </div>
                    </div>

                    <span wire:loading wire:target="converterLead" class="flex items-center space-x-4">
                        <svg class="animate-spin h-8 w-8 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span>Conectando...</span>
                    </span>
                </button>
                
                <!-- Nota de Conformidade LGPD (Regra 57) -->
                <div class="mt-8 flex items-center justify-center space-x-2 text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <p class="text-[10px] font-medium uppercase tracking-tighter">Seus dados so processados de acordo com a LGPD para envio ao CRM.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Seções Retrteis / Accordions (Regra 34) -->
    <div class="max-w-7xl mx-auto pb-32 px-6" x-data="{ active: 1 }">
        <div class="space-y-6">
            
            <!-- Sanfona 1: Ficha Tcnica do Imvel -->
            <div class="bg-white rounded-[2.5rem] shadow-sm overflow-hidden border border-gray-100 transition-all" :class="active === 1 ? 'ring-2 ring-blue-50 shadow-xl' : ''">
                <button @click="active = active === 1 ? null : 1" 
                        class="w-full px-10 py-8 flex justify-between items-center text-left hover:bg-gray-50 transition-colors">
                    <span class="text-2xl font-black text-gray-800 tracking-tight flex items-center">
                        <span class="w-2 h-6 bg-[#005CA9] mr-4 rounded-full"></span>
                        Ficha Tcnica e Descrio
                    </span>
                    <div class="bg-gray-100 p-3 rounded-2xl transition-transform duration-500" :class="active === 1 ? 'rotate-180 bg-blue-50 text-blue-600' : ''">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </button>
                <div x-show="active === 1" x-collapse>
                    <div class="px-10 pb-10">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">
                            <div class="bg-gray-50 p-6 rounded-3xl border border-gray-100">
                                <span class="text-[9px] uppercase font-black text-gray-400 block mb-1">rea Total</span>
                                <span class="text-xl font-black text-gray-800">{{ $imovel->area }} m</span>
                            </div>
                            <div class="bg-gray-50 p-6 rounded-3xl border border-gray-100">
                                <span class="text-[9px] uppercase font-black text-gray-400 block mb-1">Dormitrios</span>
                                <span class="text-xl font-black text-gray-800">{{ $imovel->quartos }}</span>
                            </div>
                            <div class="bg-gray-50 p-6 rounded-3xl border border-gray-100">
                                <span class="text-[9px] uppercase font-black text-gray-400 block mb-1">Garagem</span>
                                <span class="text-xl font-black text-gray-800">{{ $imovel->vagas }} vagas</span>
                            </div>
                            <div class="bg-gray-50 p-6 rounded-3xl border border-gray-100">
                                <span class="text-[9px] uppercase font-black text-gray-400 block mb-1">Cdigo Caixa</span>
                                <span class="text-xl font-black text-gray-800">#{{ $imovel->numero_original }}</span>
                            </div>
                        </div>
                        <div class="prose prose-blue max-w-none text-gray-600 leading-relaxed italic">
                            {!! nl2br(e($imovel->descricao)) !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sanfona 2: Dossi do Bairro (Regra 26 - JSON Casting) -->
            <div class="bg-white rounded-[2.5rem] shadow-sm overflow-hidden border border-gray-100 transition-all" :class="active === 2 ? 'ring-2 ring-blue-50 shadow-xl' : ''">
                <button @click="active = active === 2 ? null : 2" 
                        class="w-full px-10 py-8 flex justify-between items-center text-left hover:bg-gray-50 transition-colors">
                    <span class="text-2xl font-black text-gray-800 tracking-tight flex items-center">
                        <span class="w-2 h-6 bg-[#F39200] mr-4 rounded-full"></span>
                        Vizinhana: {{ $imovel->bairro }}
                    </span>
                    <div class="bg-gray-100 p-3 rounded-2xl transition-transform duration-500" :class="active === 2 ? 'rotate-180 bg-orange-50 text-orange-600' : ''">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </button>
                <div x-show="active === 2" x-collapse>
                    <div class="px-10 pb-10">
                        @if($imovel->dados_bairro)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                                <!-- Dados Extraídos da Coluna JSON -->
                                <div class="space-y-4">
                                    <h4 class="text-sm font-black text-gray-900 uppercase">Educao e Lazer</h4>
                                    <p class="text-gray-500 text-sm leading-relaxed">{{ $imovel->dados_bairro['lazer'] ?? 'Dados em processamento.' }}</p>
                                </div>
                                <div class="space-y-4">
                                    <h4 class="text-sm font-black text-gray-900 uppercase">Segurana</h4>
                                    <p class="text-gray-500 text-sm leading-relaxed">{{ $imovel->dados_bairro['seguranca'] ?? 'Monitoramento local ativo.' }}</p>
                                </div>
                                <div class="space-y-4 border-l border-gray-100 md:pl-10">
                                    <h4 class="text-sm font-black text-gray-900 uppercase">Potencial de Valorizao</h4>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-3xl font-black text-[#005CA9]">8.5</span>
                                        <span class="text-[9px] text-gray-400 font-bold uppercase leading-tight">Score <br> de Bairro</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-10">
                                <p class="text-gray-400 italic">O dossi de infraestrutura para este bairro est sendo atualizado por nossa equipe.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Vitrine de Imóveis -->
<div class="bg-gray-50 min-h-screen py-12 px-6">

    <!-- Filtros de Busca -->
    <div class="max-w-7xl mx-auto mb-12">
        <div class="bg-[#005CA9] p-8 md:p-10 rounded-[2.5rem] shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white/5 rounded-full"></div>

            <h2 class="text-white text-3xl font-black mb-8 flex items-center">
                <span class="bg-[#F39200] w-2 h-8 mr-4 rounded-full"></span>
                Encontre sua Oportunidade
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">

                <!-- Estado -->
                <div class="space-y-2">
                    <label class="block text-blue-100 text-[10px] font-black uppercase tracking-widest">Estado (UF)</label>
                    <select wire:model.live="estado"
                            class="w-full bg-white/10 border-white/20 text-white rounded-2xl focus:ring-[#F39200] focus:border-[#F39200] h-14 px-5 appearance-none cursor-pointer">
                        <option value="" class="text-gray-900">Todos os Estados</option>
                        @foreach($estados as $est)
                            <option value="{{ $est->uf }}" class="text-gray-900">{{ $est->uf }} – {{ $est->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Cidade -->
                <div class="space-y-2">
                    <label class="block text-blue-100 text-[10px] font-black uppercase tracking-widest">Cidade / Município</label>
                    <input type="text" wire:model.live.debounce.400ms="municipio" placeholder="Ex: Campinas"
                           class="w-full bg-white/10 border-white/20 text-white placeholder-blue-300 rounded-2xl focus:ring-[#F39200] focus:border-[#F39200] h-14 px-5">
                </div>

                <!-- Tipo -->
                <div class="space-y-2">
                    <label class="block text-blue-100 text-[10px] font-black uppercase tracking-widest">Tipo de Imóvel</label>
                    <select wire:model.live="tipo"
                            class="w-full bg-white/10 border-white/20 text-white rounded-2xl focus:ring-[#F39200] focus:border-[#F39200] h-14 px-5 appearance-none cursor-pointer">
                        <option value="" class="text-gray-900">Qualquer Tipo</option>
                        @foreach($tipos as $t)
                            <option value="{{ $t->nome }}" class="text-gray-900">{{ $t->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Limpar -->
                <div class="flex space-x-2">
                    <button wire:click="$set('estado', ''); $set('municipio', ''); $set('tipo', ''); $set('min_preco', ''); $set('max_preco', '');"
                            class="flex-1 h-14 bg-white/5 hover:bg-white/10 text-white font-bold rounded-2xl transition-all border border-white/10 text-sm">
                        Limpar
                    </button>
                    <div class="flex-1 h-14 bg-[#F39200] text-white flex items-center justify-center rounded-2xl shadow-lg shadow-orange-900/20 font-black uppercase text-xs tracking-tighter">
                        Filtrando…
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resultados -->
    <div class="max-w-7xl mx-auto">

        @if($imoveis->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($imoveis as $imovel)
                    <a href="{{ route('imovel.show', $imovel->slug) }}"
                       class="bg-white rounded-[2rem] overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 group border border-gray-100">

                        <div class="relative aspect-[4/3] overflow-hidden">
                            <img src="{{ $imovel->foto_fachada_url ?? asset('images/imovel-placeholder.jpg') }}"
                                 alt="{{ $imovel->tipoImovel?->nome }} em {{ $imovel->municipio?->nome }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">

                            <div class="absolute bottom-4 left-4 right-4 bg-white/90 backdrop-blur-md p-3 rounded-2xl flex justify-between items-center shadow-lg">
                                <span class="text-[#005CA9] font-black text-lg">
                                    R$ {{ number_format($imovel->ultimoHistorico?->valor_venda ?? 0, 2, ',', '.') }}
                                </span>
                                <span class="bg-blue-600 text-white p-2 rounded-xl">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="flex items-center space-x-2 mb-2">
                                <span class="bg-blue-50 text-[#005CA9] text-[9px] font-black uppercase px-2 py-1 rounded-md">
                                    {{ $imovel->estado?->uf }}
                                </span>
                                <span class="text-gray-300">|</span>
                                <span class="text-gray-400 text-[10px] font-bold uppercase truncate">
                                    {{ $imovel->municipio?->nome }}
                                </span>
                            </div>

                            <h3 class="text-gray-800 font-bold text-lg leading-tight group-hover:text-[#005CA9] transition-colors line-clamp-2">
                                {{ $imovel->tipoImovel?->nome ?? 'Imóvel' }} em {{ $imovel->bairro?->nome ?? $imovel->municipio?->nome }}
                            </h3>

                            <div class="mt-4 flex items-center text-gray-400 text-xs space-x-4 border-t border-gray-50 pt-4">
                                @if($imovel->area_total)
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                        </svg>
                                        {{ number_format($imovel->area_total, 0, ',', '.') }} m²
                                    </span>
                                @endif
                                @if($imovel->quartos)
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        {{ $imovel->quartos }} qtos
                                    </span>
                                @endif
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

                <button wire:click="$set('estado', ''); $set('municipio', ''); $set('tipo', ''); $set('min_preco', ''); $set('max_preco', '');"
                        class="mt-10 bg-[#005CA9] hover:bg-[#F39200] text-white font-black py-5 px-10 rounded-[2rem] shadow-xl shadow-blue-200 transition-all duration-500 uppercase text-xs tracking-widest">
                    Limpar Filtros e Ver Tudo
                </button>
            </div>
        @endif
    </div>
</div>

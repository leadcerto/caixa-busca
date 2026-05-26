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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">

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

                <!-- Financiamento -->
                <div class="space-y-2">
                    <label class="block text-[#005CA9] text-[10px] font-black uppercase tracking-widest">Financiamento / FGTS</label>
                    <select wire:model.live="financiamento"
                            class="w-full bg-[#f8fafc] border border-slate-200 text-slate-900 rounded-2xl focus:ring-2 focus:ring-[#F39200] focus:border-[#F39200] focus:bg-white h-14 px-5 appearance-none cursor-pointer transition duration-200">
                        <option value="todos">Mostrar Todos</option>
                        <option value="sim">Somente com Financiamento</option>
                    </select>
                </div>

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

            </div>

            <!-- Bairros em colunas (exibido quando há cidade selecionada) -->
            @if(!empty($bairros))
            <div class="mt-8 pt-6 border-t border-blue-50">
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

            <!-- Botões -->
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

</div>

@push('schema')
@php
$listItems = [];
foreach ($imoveis as $i => $item) {
    $listItems[] = [
        '@type'    => 'ListItem',
        'position' => $imoveis->firstItem() + $i,
        'url'      => url('/' . $item->slug),
        'name'     => trim(($item->tipoImovel?->nome ?? 'Imóvel') . ' em ' . ($item->municipio?->nome ?? '') . ', ' . ($item->estado?->uf ?? '')),
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

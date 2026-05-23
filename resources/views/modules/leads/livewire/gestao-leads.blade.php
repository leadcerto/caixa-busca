<div class="max-w-7xl mx-auto py-10 px-6 space-y-8">

@if($modo === 'lista')
{{-- ============================================================ --}}
{{-- MODO LISTA                                                   --}}
{{-- ============================================================ --}}

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Gestão de Leads</h1>
            <p class="text-gray-400 text-sm mt-1">Todos os leads cadastrados na plataforma.</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="exportarCsv"
                    wire:loading.attr="disabled"
                    class="flex items-center space-x-2 bg-[#005CA9] hover:bg-[#004a8a] text-white text-sm font-black px-5 py-3 rounded-2xl transition-all shadow-lg shadow-blue-200/50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span wire:loading.remove wire:target="exportarCsv">Exportar CSV</span>
                <span wire:loading wire:target="exportarCsv">Gerando…</span>
            </button>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <!-- Busca -->
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Buscar</label>
                <input type="text" wire:model.live.debounce.400ms="busca"
                       placeholder="Nome, e-mail ou telefone…"
                       class="w-full border border-gray-200 rounded-2xl h-12 px-5 text-sm text-gray-800 focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition">
            </div>

            <!-- Imobiliária -->
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Imobiliária</label>
                <select wire:model.live="imobiliariaId"
                        class="w-full border border-gray-200 rounded-2xl h-12 px-5 text-sm text-gray-800 focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition appearance-none">
                    <option value="">Todas as imobiliárias</option>
                    @foreach($imobiliarias as $imob)
                        <option value="{{ $imob->id }}">{{ $imob->nome }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Estado -->
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Estado</label>
                <select wire:model.live="estadoId"
                        class="w-full border border-gray-200 rounded-2xl h-12 px-5 text-sm text-gray-800 focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition appearance-none">
                    <option value="">Todos os estados</option>
                    @foreach($estados as $est)
                        <option value="{{ $est->id }}">{{ $est->uf }} — {{ $est->nome }}</option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

            <!-- Período -->
            <div class="flex space-x-2">
                <div class="flex-1">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">De</label>
                    <input type="date" wire:model.live="dataInicio"
                           class="w-full border border-gray-200 rounded-2xl h-12 px-4 text-sm text-gray-800 focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition">
                </div>
                <div class="flex-1">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Até</label>
                    <input type="date" wire:model.live="dataFim"
                           class="w-full border border-gray-200 rounded-2xl h-12 px-4 text-sm text-gray-800 focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition">
                </div>
            </div>

            <!-- Toggle duplicados -->
            <div class="flex items-center space-x-3 h-12">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model.live="somenteDuplicados" class="sr-only peer">
                    <div class="w-10 h-5 bg-gray-200 peer-focus:ring-2 peer-focus:ring-[#005CA9] rounded-full peer peer-checked:bg-[#F39200] transition-colors"></div>
                    <div class="absolute left-0.5 top-0.5 bg-white w-4 h-4 rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                </label>
                <span class="text-sm font-bold text-gray-600">Somente duplicados</span>
            </div>

            <!-- Limpar -->
            @if($busca || $imobiliariaId || $estadoId || $dataInicio || $dataFim || $somenteDuplicados)
                <div class="flex items-center h-12">
                    <button wire:click="$set('busca',''); $set('imobiliariaId',''); $set('estadoId',''); $set('dataInicio',''); $set('dataFim',''); $set('somenteDuplicados', false)"
                            class="text-xs text-gray-400 hover:text-gray-600 font-bold flex items-center space-x-1 transition">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span>Limpar filtros</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Tabela -->
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">

        @if($leads->isEmpty())
            <div class="text-center py-20 px-6">
                <div class="w-14 h-14 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-[#005CA9]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-black text-gray-800 mb-2">Nenhum lead encontrado</h3>
                <p class="text-gray-400 text-sm">Tente ajustar os filtros ou aguarde novos cadastros.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-5 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Lead</th>
                            <th class="px-5 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Contato</th>
                            <th class="px-5 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Cadastro</th>
                            <th class="px-5 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Atendimentos</th>
                            <th class="px-5 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Flags</th>
                            <th class="px-5 py-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($leads as $lead)
                            @php
                                $isDuplicado = in_array($lead->email, $emailsDup)
                                    || (!empty($lead->telefone) && in_array($lead->telefone, $telefonesDup));
                            @endphp
                            <tr class="hover:bg-gray-50/70 transition-colors">

                                <!-- Lead -->
                                <td class="px-5 py-4">
                                    <div class="font-bold text-gray-900 text-sm flex items-center gap-2">
                                        {{ $lead->nome }}
                                        @if($isDuplicado)
                                            <span class="text-[9px] bg-red-100 text-red-600 font-black px-2 py-0.5 rounded-full uppercase tracking-wider">Duplicado</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-400 mt-0.5">{{ $lead->email }}</div>
                                </td>

                                <!-- Contato -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    @if($lead->telefone)
                                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $lead->telefone) }}"
                                           target="_blank"
                                           class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 hover:bg-green-100 text-xs font-bold px-3 py-1.5 rounded-full transition-colors">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                            </svg>
                                            {{ $lead->telefone }}
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-300">—</span>
                                    @endif
                                </td>

                                <!-- Cadastro -->
                                <td class="px-5 py-4 whitespace-nowrap text-xs text-gray-400">
                                    {{ $lead->created_at->format('d/m/Y') }}<br>
                                    <span class="text-gray-300">{{ $lead->created_at->format('H:i') }}</span>
                                </td>

                                <!-- Nº Atendimentos -->
                                <td class="px-5 py-4 text-center">
                                    <span class="text-lg font-black {{ $lead->atendimentos_count > 0 ? 'text-[#005CA9]' : 'text-gray-300' }}">
                                        {{ $lead->atendimentos_count }}
                                    </span>
                                </td>

                                <!-- Flags -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        @if($lead->ativo)
                                            <span class="text-[9px] bg-green-100 text-green-700 font-black px-2 py-0.5 rounded-full uppercase">Ativo</span>
                                        @else
                                            <span class="text-[9px] bg-gray-100 text-gray-400 font-black px-2 py-0.5 rounded-full uppercase">Inativo</span>
                                        @endif
                                        @if(!empty($lead->imoveis_interesse))
                                            <span class="text-[9px] bg-blue-100 text-blue-700 font-black px-2 py-0.5 rounded-full uppercase">
                                                {{ count($lead->imoveis_interesse) }} interesse(s)
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Ação -->
                                <td class="px-5 py-4 text-right">
                                    <button wire:click="verDetalhe({{ $lead->id }})"
                                            class="text-xs font-black text-[#005CA9] hover:text-[#F39200] transition-colors uppercase tracking-wider">
                                        Ver →
                                    </button>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <p class="text-xs text-gray-400">{{ number_format($leads->total()) }} leads encontrados</p>
                {{ $leads->links() }}
            </div>
        @endif
    </div>

@else
{{-- ============================================================ --}}
{{-- MODO DETALHE DO LEAD                                        --}}
{{-- ============================================================ --}}

    <!-- Voltar -->
    <div>
        <button wire:click="voltarLista"
                class="flex items-center space-x-2 text-sm font-bold text-gray-400 hover:text-[#005CA9] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
            <span>Voltar para lista</span>
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Coluna esquerda: dados do lead -->
        <div class="space-y-5">

            <!-- Card do lead -->
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-7">
                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-4">Dados do Lead</span>

                <h2 class="text-2xl font-black text-gray-900">{{ $lead->nome }}</h2>

                <div class="mt-5 space-y-3">
                    <div class="flex items-center space-x-3">
                        <svg class="w-4 h-4 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm text-gray-700">{{ $lead->email }}</span>
                    </div>

                    @if($lead->telefone)
                        <div class="flex items-center space-x-3">
                            <svg class="w-4 h-4 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $lead->telefone) }}"
                               target="_blank"
                               class="text-sm text-green-600 font-bold hover:underline">
                                {{ $lead->telefone }}
                            </a>
                        </div>
                    @endif
                </div>

                <div class="mt-5 pt-5 border-t border-gray-50 flex items-center justify-between">
                    <span class="text-xs text-gray-400">Cadastrado em {{ $lead->created_at->format('d/m/Y \à\s H:i') }}</span>
                    @if($lead->ativo)
                        <span class="text-[9px] bg-green-100 text-green-700 font-black px-2 py-0.5 rounded-full uppercase">Ativo</span>
                    @else
                        <span class="text-[9px] bg-gray-100 text-gray-400 font-black px-2 py-0.5 rounded-full uppercase">Inativo</span>
                    @endif
                </div>
            </div>

            <!-- Imóveis de interesse -->
            @if($imoveisInteresse->isNotEmpty())
                <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-7">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-4">
                        Imóveis de Interesse ({{ $imoveisInteresse->count() }})
                    </span>
                    <div class="space-y-3">
                        @foreach($imoveisInteresse as $imovel)
                            <div class="flex items-start justify-between py-2 border-b border-gray-50 last:border-0">
                                <div>
                                    <a href="{{ route('imovel.show', $imovel->slug) }}"
                                       target="_blank"
                                       class="text-sm font-bold text-[#005CA9] hover:underline">
                                        #{{ $imovel->numero_original }}
                                    </a>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        {{ $imovel->tipoImovel?->nome }}
                                        — {{ $imovel->municipio?->nome }}/{{ $imovel->estado?->uf }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Coluna direita: histórico de atendimentos -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-7">
                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-6">
                    Histórico de Atendimentos ({{ $lead->atendimentos->count() }})
                </span>

                @if($lead->atendimentos->isEmpty())
                    <div class="text-center py-10 text-gray-400 text-sm">Nenhum atendimento registrado.</div>
                @else
                    <div class="space-y-4">
                        @foreach($lead->atendimentos->sortByDesc('created_at') as $at)
                            <div class="border border-gray-100 rounded-2xl p-5">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <span class="text-xs font-bold text-gray-500">
                                            {{ $at->imovel?->tipoImovel?->nome ?? 'Imóvel' }}
                                            @if($at->imovel?->slug)
                                                — <a href="{{ route('imovel.show', $at->imovel->slug) }}" target="_blank" class="text-[#005CA9] hover:underline">#{{ $at->imovel->numero_original }}</a>
                                            @endif
                                        </span>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            {{ $at->imovel?->municipio?->nome }}/{{ $at->imovel?->estado?->uf }}
                                        </p>
                                    </div>
                                    <span class="text-[10px] text-gray-400 whitespace-nowrap ml-4">
                                        {{ $at->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        @if($at->imobiliaria)
                                            <span class="text-[10px] bg-gray-100 text-gray-500 font-bold px-2 py-0.5 rounded-full">
                                                {{ $at->imobiliaria->nome }}
                                            </span>
                                        @endif
                                        @if($at->origem)
                                            <span class="text-[10px] bg-blue-50 text-blue-600 font-bold px-2 py-0.5 rounded-full">
                                                {{ $at->origem->nome }}
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-[10px] font-black px-2 py-0.5 rounded-full uppercase
                                        {{ \App\Models\Atendimento::STATUS_CORES[$at->status_parceiro] ?? 'bg-gray-100 text-gray-400' }}">
                                        {{ \App\Models\Atendimento::STATUS_LABELS[$at->status_parceiro] ?? $at->status_parceiro }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>

@endif
</div>

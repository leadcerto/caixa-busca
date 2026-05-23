<div class="max-w-7xl mx-auto py-10 px-6 space-y-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Painel do Parceiro</h1>
            <p class="text-gray-400 text-sm mt-1">
                Bem-vindo, <span class="font-bold text-[#005CA9]">{{ $imobiliaria->nome }}</span>.
                @if($imobiliaria->estados->isNotEmpty())
                    Região: <span class="font-bold">{{ $imobiliaria->estados->pluck('uf')->join(', ') }}</span>
                @endif
            </p>
        </div>

        <button wire:click="exportarCsv"
                wire:loading.attr="disabled"
                class="flex items-center space-x-2 bg-[#005CA9] hover:bg-[#004a8a] text-white text-sm font-black px-6 py-3 rounded-2xl transition-all shadow-lg shadow-blue-200/50">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            <span wire:loading.remove wire:target="exportarCsv">Exportar CSV</span>
            <span wire:loading wire:target="exportarCsv">Gerando…</span>
        </button>
    </div>

    <!-- Cards de métricas -->
    <div class="grid grid-cols-3 gap-5">
        <div class="bg-white rounded-[1.75rem] border border-gray-100 shadow-sm p-6 text-center">
            <p class="text-3xl font-black text-gray-900">{{ number_format($metricas['total']) }}</p>
            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mt-1">Total de Leads</p>
        </div>
        <div class="bg-white rounded-[1.75rem] border border-gray-100 shadow-sm p-6 text-center">
            <p class="text-3xl font-black text-[#005CA9]">{{ number_format($metricas['ultimos7d']) }}</p>
            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mt-1">Últimos 7 dias</p>
        </div>
        <div class="bg-white rounded-[1.75rem] border border-gray-100 shadow-sm p-6 text-center">
            <p class="text-3xl font-black text-[#F39200]">{{ number_format($metricas['pendentes']) }}</p>
            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mt-1">Aguardando Contato</p>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

            <div class="md:col-span-2">
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Buscar</label>
                <input type="text"
                       wire:model.live.debounce.400ms="busca"
                       placeholder="Nome, email, telefone ou nº do imóvel…"
                       class="w-full border border-gray-200 rounded-2xl h-12 px-5 text-sm text-gray-800 focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition">
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Status</label>
                <select wire:model.live="statusFiltro"
                        class="w-full border border-gray-200 rounded-2xl h-12 px-5 text-sm text-gray-800 focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition appearance-none">
                    <option value="">Todos os status</option>
                    @foreach($statusOpcoes as $valor => $label)
                        <option value="{{ $valor }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

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

        </div>

        @if($busca || $dataInicio || $dataFim || $statusFiltro)
            <div class="mt-4 flex items-center">
                <button wire:click="$set('busca',''); $set('dataInicio',''); $set('dataFim',''); $set('statusFiltro','')"
                        class="text-xs text-gray-400 hover:text-gray-600 font-bold flex items-center space-x-1 transition">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span>Limpar filtros</span>
                </button>
            </div>
        @endif
    </div>

    <!-- Tabela -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">

        @if($atendimentos->isEmpty())
            <div class="text-center py-20 px-6">
                <div class="w-14 h-14 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-[#005CA9]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-black text-gray-800 mb-2">Nenhum lead encontrado</h3>
                <p class="text-gray-400 text-sm max-w-xs mx-auto">
                    {{ $busca || $dataInicio || $dataFim || $statusFiltro ? 'Tente ajustar os filtros.' : 'Os leads aparecerão aqui quando visitantes demonstrarem interesse.' }}
                </p>
            </div>

        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-5 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Data</th>
                            <th class="px-5 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Lead</th>
                            <th class="px-5 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Contato</th>
                            <th class="px-5 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Imóvel</th>
                            <th class="px-5 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                            <th class="px-5 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Notif.</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($atendimentos as $at)
                            <tr class="hover:bg-gray-50/70 transition-colors">

                                <!-- Data -->
                                <td class="px-5 py-4 whitespace-nowrap text-xs text-gray-400">
                                    {{ $at->created_at->format('d/m/Y') }}<br>
                                    <span class="text-gray-300">{{ $at->created_at->format('H:i') }}</span>
                                </td>

                                <!-- Lead -->
                                <td class="px-5 py-4">
                                    <div class="font-bold text-gray-900 text-sm">{{ $at->lead?->nome ?? '—' }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5">{{ $at->lead?->email }}</div>
                                </td>

                                <!-- Contato WhatsApp -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    @if($at->lead?->telefone)
                                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $at->lead->telefone) }}"
                                           target="_blank"
                                           class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 hover:bg-green-100 text-xs font-bold px-3 py-1.5 rounded-full transition-colors">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                            </svg>
                                            {{ $at->lead->telefone }}
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-300">—</span>
                                    @endif
                                </td>

                                <!-- Imóvel -->
                                <td class="px-5 py-4">
                                    <div class="text-sm font-bold text-[#005CA9]">
                                        @if($at->imovel?->slug)
                                            <a href="{{ route('imovel.show', $at->imovel->slug) }}" target="_blank" class="hover:underline">
                                                #{{ $at->imovel->numero_original }}
                                            </a>
                                        @else
                                            #{{ $at->imovel?->numero_original ?? '—' }}
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-400 mt-0.5">
                                        {{ $at->imovel?->tipoImovel?->nome }}
                                        @if($at->imovel?->municipio)
                                            — {{ $at->imovel->municipio->nome }}/{{ $at->imovel->estado?->uf }}
                                        @endif
                                    </div>
                                </td>

                                <!-- Status parceiro (dropdown inline) -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <select wire:change="atualizarStatus({{ $at->id }}, $event.target.value)"
                                            class="text-xs font-bold rounded-full px-3 py-1.5 border-0 cursor-pointer focus:ring-2 focus:ring-[#005CA9] transition
                                                   {{ $statusCores[$at->status_parceiro] ?? 'bg-gray-100 text-gray-500' }}">
                                        @foreach($statusOpcoes as $valor => $label)
                                            <option value="{{ $valor }}" {{ $at->status_parceiro === $valor ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                <!-- Notificações enviadas -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span title="WhatsApp {{ $at->whatsapp_enviado ? 'enviado' : 'não enviado' }}"
                                              class="w-2 h-2 rounded-full {{ $at->whatsapp_enviado ? 'bg-green-400' : 'bg-gray-200' }}"></span>
                                        <span title="E-mail {{ $at->email_enviado ? 'enviado' : 'não enviado' }}"
                                              class="w-2 h-2 rounded-full {{ $at->email_enviado ? 'bg-blue-400' : 'bg-gray-200' }}"></span>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <p class="text-xs text-gray-400">
                    {{ $atendimentos->total() }} {{ Str::plural('atendimento', $atendimentos->total()) }} encontrado{{ $atendimentos->total() !== 1 ? 's' : '' }}
                </p>
                {{ $atendimentos->links() }}
            </div>
        @endif
    </div>

</div>

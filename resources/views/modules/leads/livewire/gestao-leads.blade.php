<div class="max-w-7xl mx-auto py-10 px-6 space-y-8"
     x-data="{
        notasAberto: false,
        previewImovel: null,
        previewPos: { x: 0, y: 0 },
     }"
     @abrir-notas.window="notasAberto = true"
     @fechar-notas.window="notasAberto = false"
     @nota-salva.window="$wire.$refresh()"
     @status-atualizado.window="$wire.$refresh()"
     @massa-atualizada.window="$wire.$refresh()"
>

@if($modo === 'lista')
{{-- ============================================================ --}}
{{-- MODO LISTA                                                   --}}
{{-- ============================================================ --}}

    {{-- ============================================================ --}}
    {{-- KPIs (13.15)                                                 --}}
    {{-- ============================================================ --}}
    @if(!empty($kpis))
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

        {{-- Total --}}
        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-6 relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="absolute -top-6 -right-6 w-20 h-20 bg-blue-50 rounded-full opacity-60 group-hover:scale-110 transition-transform"></div>
            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1">Total Leads</span>
            <span class="text-3xl font-black text-gray-900">{{ number_format($kpis['total']) }}</span>
        </div>

        {{-- Novos 7d --}}
        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-6 relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="absolute -top-6 -right-6 w-20 h-20 bg-green-50 rounded-full opacity-60 group-hover:scale-110 transition-transform"></div>
            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1">Novos (7 dias)</span>
            <span class="text-3xl font-black text-green-600">{{ number_format($kpis['novos7d']) }}</span>
        </div>

        {{-- Taxa de Conversão --}}
        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-6 relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="absolute -top-6 -right-6 w-20 h-20 bg-orange-50 rounded-full opacity-60 group-hover:scale-110 transition-transform"></div>
            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1">Taxa Conversão</span>
            <span class="text-3xl font-black text-[#F39200]">{{ $kpis['taxaConv'] }}%</span>
        </div>

        {{-- Em Atendimento --}}
        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-6 relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="absolute -top-6 -right-6 w-20 h-20 bg-yellow-50 rounded-full opacity-60 group-hover:scale-110 transition-transform"></div>
            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1">Em Atendimento</span>
            <span class="text-3xl font-black text-yellow-600">{{ number_format($kpis['emAtend']) }}</span>
        </div>

    </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Gestão de Leads</h1>
            <p class="text-gray-400 text-sm mt-1">Mini-CRM — Todos os leads cadastrados na plataforma.</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="exportarCsv"
                    wire:loading.attr="disabled"
                    class="flex items-center space-x-2 bg-[#005CA9] hover:bg-[#004a8a] text-white text-sm font-black px-5 py-3 rounded-2xl transition-all shadow-lg shadow-blue-200/50 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span wire:loading.remove wire:target="exportarCsv">Exportar CSV</span>
                <span wire:loading wire:target="exportarCsv">Gerando…</span>
            </button>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- FILTROS (incluindo 13.14 — status + responsável)             --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">

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
                        class="w-full border border-gray-200 rounded-2xl h-12 px-5 text-sm text-gray-800 focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition appearance-none cursor-pointer">
                    <option value="">Todas</option>
                    @foreach($imobiliarias as $imob)
                        <option value="{{ $imob->id }}">{{ $imob->nome }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Estado -->
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Estado</label>
                <select wire:model.live="estadoId"
                        class="w-full border border-gray-200 rounded-2xl h-12 px-5 text-sm text-gray-800 focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition appearance-none cursor-pointer">
                    <option value="">Todos</option>
                    @foreach($estados as $est)
                        <option value="{{ $est->id }}">{{ $est->uf }} — {{ $est->nome }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 13.14 — Status -->
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Status</label>
                <select wire:model.live="filtroStatus"
                        class="w-full border border-gray-200 rounded-2xl h-12 px-5 text-sm text-gray-800 focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition appearance-none cursor-pointer">
                    <option value="">Todos os status</option>
                    @foreach(\App\Models\Lead::STATUS_LABELS as $val => $lbl)
                        <option value="{{ $val }}">{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 13.14 — Responsável -->
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Responsável</label>
                <select wire:model.live="filtroResponsavel"
                        class="w-full border border-gray-200 rounded-2xl h-12 px-5 text-sm text-gray-800 focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition appearance-none cursor-pointer">
                    <option value="">Todos</option>
                    <option value="sem">Sem responsável</option>
                    @foreach($usuarios as $usr)
                        <option value="{{ $usr->id }}">{{ $usr->name }}</option>
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
            @if($busca || $imobiliariaId || $estadoId || $dataInicio || $dataFim || $somenteDuplicados || $filtroStatus || $filtroResponsavel)
                <div class="flex items-center h-12">
                    <button wire:click="$set('busca',''); $set('imobiliariaId',''); $set('estadoId',''); $set('dataInicio',''); $set('dataFim',''); $set('somenteDuplicados', false); $set('filtroStatus',''); $set('filtroResponsavel','')"
                            class="text-xs text-gray-400 hover:text-gray-600 font-bold flex items-center space-x-1 transition cursor-pointer">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span>Limpar filtros</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- BARRA DE AÇÕES EM MASSA (13.11)                              --}}
    {{-- ============================================================ --}}
    @if(count($leadsSelecionados) > 0)
    <div class="bg-[#005CA9] text-white rounded-2xl p-4 flex flex-col sm:flex-row items-center justify-between gap-3 shadow-lg shadow-blue-300/30 animate-slide-down">
        <span class="text-sm font-bold">
            <span class="bg-white/20 px-2 py-0.5 rounded-full text-xs font-black mr-2">{{ count($leadsSelecionados) }}</span>
            lead(s) selecionado(s)
        </span>
        <div class="flex items-center gap-3">
            {{-- Alterar status em massa --}}
            <div class="flex items-center gap-2">
                <select wire:model="acaoMassaStatusValor"
                        class="bg-white/10 border border-white/20 text-white text-xs font-bold rounded-xl h-9 px-3 appearance-none cursor-pointer focus:ring-2 focus:ring-white/50 [&>option]:text-gray-800">
                    <option value="">Alterar status para…</option>
                    @foreach(\App\Models\Lead::STATUS_LABELS as $val => $lbl)
                        <option value="{{ $val }}">{{ $lbl }}</option>
                    @endforeach
                </select>
                <button wire:click="acaoMassaStatus"
                        {{ empty($acaoMassaStatusValor) ? 'disabled' : '' }}
                        class="bg-white text-[#005CA9] text-xs font-black px-4 py-2 rounded-xl hover:bg-blue-50 transition disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                    Aplicar
                </button>
            </div>

            {{-- Exportar selecionados --}}
            <button wire:click="exportarSelecionados"
                    class="bg-white/10 border border-white/20 text-white text-xs font-bold px-4 py-2 rounded-xl hover:bg-white/20 transition cursor-pointer">
                Exportar Selecionados
            </button>

            {{-- Limpar seleção --}}
            <button wire:click="$set('leadsSelecionados', []); $set('selecionarTodos', false)"
                    class="text-white/70 hover:text-white text-xs font-bold transition cursor-pointer">
                ✕ Limpar
            </button>
        </div>
    </div>
    @endif

    {{-- ============================================================ --}}
    {{-- TABELA DE LEADS                                              --}}
    {{-- ============================================================ --}}
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
                            {{-- 13.10 — Checkbox selecionar todos --}}
                            <th class="px-4 py-4 text-center w-10">
                                <input type="checkbox" wire:model.live="selecionarTodos"
                                       class="w-4 h-4 rounded border-gray-300 text-[#005CA9] focus:ring-[#005CA9] cursor-pointer">
                            </th>
                            <th class="px-4 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Lead</th>
                            <th class="px-4 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Contato</th>
                            <th class="px-4 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                            <th class="px-4 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Responsável</th>
                            <th class="px-4 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Score</th>
                            <th class="px-4 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Origem</th>
                            <th class="px-4 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Cadastro</th>
                            <th class="px-4 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Atend.</th>
                            <th class="px-4 py-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($leads as $lead)
                            @php
                                $isDuplicado = in_array($lead->email, $emailsDup)
                                    || (!empty($lead->telefone) && in_array($lead->telefone, $telefonesDup));
                                $score = \App\Modules\Leads\Livewire\GestaoLeads::leadScore($lead);
                            @endphp
                            <tr class="hover:bg-gray-50/70 transition-colors">

                                {{-- 13.10 — Checkbox individual --}}
                                <td class="px-4 py-4 text-center">
                                    <input type="checkbox"
                                           wire:model.live="leadsSelecionados"
                                           value="{{ $lead->id }}"
                                           class="w-4 h-4 rounded border-gray-300 text-[#005CA9] focus:ring-[#005CA9] cursor-pointer">
                                </td>

                                {{-- Lead --}}
                                <td class="px-4 py-4">
                                    <div class="font-bold text-gray-900 text-sm flex items-center gap-2">
                                        {{ $lead->nome }}
                                        @if($isDuplicado)
                                            <span class="text-[9px] bg-red-100 text-red-600 font-black px-2 py-0.5 rounded-full uppercase tracking-wider">Dup</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-400 mt-0.5">{{ $lead->email }}</div>
                                </td>

                                {{-- Contato --}}
                                <td class="px-4 py-4 whitespace-nowrap">
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

                                {{-- 13.6 — Status inline --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <select wire:change="atualizarStatus({{ $lead->id }}, $event.target.value)"
                                            class="text-[11px] font-black px-3 py-1.5 rounded-full border-0 cursor-pointer transition-all focus:ring-2 focus:ring-[#005CA9]
                                                {{ \App\Models\Lead::STATUS_CORES[$lead->status ?? 'novo'] ?? 'bg-gray-100 text-gray-400' }}">
                                        @foreach(\App\Models\Lead::STATUS_LABELS as $val => $lbl)
                                            <option value="{{ $val }}" {{ ($lead->status ?? 'novo') === $val ? 'selected' : '' }}>
                                                {{ $lbl }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                {{-- 13.7 — Responsável inline --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <select wire:change="atribuirResponsavel({{ $lead->id }}, $event.target.value)"
                                            class="text-[11px] font-bold bg-gray-50 border border-gray-200 text-gray-700 rounded-xl px-3 py-1.5 cursor-pointer appearance-none focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition">
                                        <option value="">Sem responsável</option>
                                        @foreach($usuarios as $usr)
                                            <option value="{{ $usr->id }}" {{ $lead->user_id === $usr->id ? 'selected' : '' }}>
                                                {{ $usr->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                {{-- 13.12 — Lead Score --}}
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center gap-1 text-[10px] font-black px-2.5 py-1 rounded-full {{ $score['class'] }}"
                                          title="{{ $score['label'] }}">
                                        {{ $score['icon'] }} {{ $score['label'] }}
                                    </span>
                                </td>

                                {{-- 13.13 — Origem (UTMs) --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex flex-wrap gap-1">
                                        @if($lead->utm_source)
                                            <span class="text-[9px] bg-purple-50 text-purple-600 font-bold px-2 py-0.5 rounded-full">{{ $lead->utm_source }}</span>
                                        @endif
                                        @if($lead->utm_campaign)
                                            <span class="text-[9px] bg-indigo-50 text-indigo-600 font-bold px-2 py-0.5 rounded-full">{{ $lead->utm_campaign }}</span>
                                        @endif
                                        @if(!$lead->utm_source && !$lead->utm_campaign)
                                            <span class="text-[9px] text-gray-300">Direto</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Cadastro --}}
                                <td class="px-4 py-4 whitespace-nowrap text-xs text-gray-400">
                                    {{ $lead->created_at->format('d/m/Y') }}<br>
                                    <span class="text-gray-300">{{ $lead->created_at->format('H:i') }}</span>
                                </td>

                                {{-- Nº Atendimentos --}}
                                <td class="px-4 py-4 text-center">
                                    <span class="text-lg font-black {{ $lead->atendimentos_count > 0 ? 'text-[#005CA9]' : 'text-gray-300' }}">
                                        {{ $lead->atendimentos_count }}
                                    </span>
                                </td>

                                {{-- Ações --}}
                                <td class="px-4 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- 13.9 — Botão Notas --}}
                                        <button wire:click="abrirNotas({{ $lead->id }})"
                                                class="text-gray-400 hover:text-[#F39200] transition-colors cursor-pointer"
                                                title="Notas">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                            </svg>
                                        </button>
                                        {{-- Ver detalhe --}}
                                        <button wire:click="verDetalhe({{ $lead->id }})"
                                                class="text-xs font-black text-[#005CA9] hover:text-[#F39200] transition-colors uppercase tracking-wider cursor-pointer">
                                            Ver →
                                        </button>
                                    </div>
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

{{-- ============================================================ --}}
{{-- OFFCANVAS — NOTAS RÁPIDAS (13.9)                             --}}
{{-- ============================================================ --}}
<div x-show="notasAberto"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="translate-x-full"
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="translate-x-full"
     class="fixed inset-y-0 right-0 w-full sm:w-[28rem] bg-white shadow-2xl z-50 flex flex-col border-l border-gray-100"
     style="display: none;"
     @keydown.escape.window="$wire.fecharNotas()">

    {{-- Header --}}
    <div class="flex items-center justify-between p-6 border-b border-gray-100">
        <div>
            <h3 class="text-lg font-black text-gray-900">Notas & Interações</h3>
            @if($notasLeadId)
                <p class="text-xs text-gray-400 mt-0.5">Lead #{{ $notasLeadId }}</p>
            @endif
        </div>
        <button wire:click="fecharNotas" class="text-gray-400 hover:text-gray-600 transition cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Formulário de nova nota --}}
    <div class="p-6 border-b border-gray-100 space-y-3">
        <div class="flex gap-2">
            <select wire:model="notaTipo"
                    class="text-xs font-bold bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 appearance-none cursor-pointer focus:ring-2 focus:ring-[#005CA9]">
                @foreach(\App\Models\LeadNote::TIPOS as $val => $lbl)
                    <option value="{{ $val }}">{{ \App\Models\LeadNote::TIPO_ICONS[$val] }} {{ $lbl }}</option>
                @endforeach
            </select>
        </div>
        <textarea wire:model="notaConteudo"
                  rows="3"
                  placeholder="Escreva uma nota, registro de ligação, interação…"
                  class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition resize-none"></textarea>
        @error('notaConteudo')
            <p class="text-xs text-red-500 font-bold">{{ $message }}</p>
        @enderror
        <button wire:click="salvarNota"
                wire:loading.attr="disabled"
                {{ empty($notaConteudo) ? 'disabled' : '' }}
                class="w-full bg-[#005CA9] hover:bg-[#004a8a] text-white text-sm font-black py-3 rounded-2xl transition-all disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
            <span wire:loading.remove wire:target="salvarNota">Salvar Nota</span>
            <span wire:loading wire:target="salvarNota">Salvando…</span>
        </button>
    </div>

    {{-- Lista de notas --}}
    <div class="flex-1 overflow-y-auto p-6 space-y-4">
        @if($notasLead->isEmpty())
            <div class="text-center py-10">
                <div class="text-3xl mb-3">📝</div>
                <p class="text-sm text-gray-400 font-bold">Nenhuma nota registrada ainda.</p>
            </div>
        @else
            @foreach($notasLead as $nota)
                <div class="bg-gray-50 rounded-2xl p-4 space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-base">{{ \App\Models\LeadNote::TIPO_ICONS[$nota->tipo] ?? '📝' }}</span>
                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                {{ \App\Models\LeadNote::TIPOS[$nota->tipo] ?? $nota->tipo }}
                            </span>
                        </div>
                        <span class="text-[10px] text-gray-400">{{ $nota->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $nota->conteudo }}</p>
                    @if($nota->autor)
                        <p class="text-[10px] text-gray-400 font-bold">por {{ $nota->autor->name }}</p>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
</div>

{{-- Backdrop do offcanvas --}}
<div x-show="notasAberto"
     x-transition:enter="transition-opacity ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="$wire.fecharNotas()"
     class="fixed inset-0 bg-black/20 z-40"
     style="display: none;">
</div>


@else
{{-- ============================================================ --}}
{{-- MODO DETALHE DO LEAD                                        --}}
{{-- ============================================================ --}}

    <!-- Voltar -->
    <div>
        <button wire:click="voltarLista"
                class="flex items-center space-x-2 text-sm font-bold text-gray-400 hover:text-[#005CA9] transition-colors cursor-pointer">
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

                {{-- Status e Responsável --}}
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <span class="text-[11px] font-black px-3 py-1 rounded-full {{ \App\Models\Lead::STATUS_CORES[$lead->status ?? 'novo'] ?? 'bg-gray-100 text-gray-400' }}">
                        {{ \App\Models\Lead::STATUS_LABELS[$lead->status ?? 'novo'] ?? 'Novo' }}
                    </span>
                    @if($lead->responsavel)
                        <span class="text-[10px] font-bold bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">
                            👤 {{ $lead->responsavel->name }}
                        </span>
                    @endif
                    @php $detScore = \App\Modules\Leads\Livewire\GestaoLeads::leadScore($lead); @endphp
                    <span class="text-[10px] font-black px-2 py-0.5 rounded-full {{ $detScore['class'] }}">
                        {{ $detScore['icon'] }} {{ $detScore['label'] }}
                    </span>
                </div>

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

                {{-- UTMs --}}
                @if($lead->utm_source || $lead->utm_medium || $lead->utm_campaign)
                    <div class="mt-5 pt-5 border-t border-gray-50">
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Origem / UTMs</span>
                        <div class="flex flex-wrap gap-1.5">
                            @if($lead->utm_source)
                                <span class="text-[9px] bg-purple-50 text-purple-600 font-bold px-2 py-0.5 rounded-full">source: {{ $lead->utm_source }}</span>
                            @endif
                            @if($lead->utm_medium)
                                <span class="text-[9px] bg-teal-50 text-teal-600 font-bold px-2 py-0.5 rounded-full">medium: {{ $lead->utm_medium }}</span>
                            @endif
                            @if($lead->utm_campaign)
                                <span class="text-[9px] bg-indigo-50 text-indigo-600 font-bold px-2 py-0.5 rounded-full">campaign: {{ $lead->utm_campaign }}</span>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="mt-5 pt-5 border-t border-gray-50 flex items-center justify-between">
                    <span class="text-xs text-gray-400">Cadastrado em {{ $lead->created_at->format('d/m/Y \à\s H:i') }}</span>
                    @if($lead->ativo)
                        <span class="text-[9px] bg-green-100 text-green-700 font-black px-2 py-0.5 rounded-full uppercase">Ativo</span>
                    @else
                        <span class="text-[9px] bg-gray-100 text-gray-400 font-black px-2 py-0.5 rounded-full uppercase">Inativo</span>
                    @endif
                </div>
            </div>

            <!-- Imóveis de interesse com preview (13.8) -->
            @if($imoveisInteresse->isNotEmpty())
                <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-7">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-4">
                        Imóveis de Interesse ({{ $imoveisInteresse->count() }})
                    </span>
                    <div class="space-y-3">
                        @foreach($imoveisInteresse as $imovel)
                            <div class="group flex items-start gap-4 py-3 border-b border-gray-50 last:border-0 relative"
                                 x-data="{ showPreview: false }">

                                {{-- 13.8 — Mini preview com foto --}}
                                <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                                    <img src="{{ $imovel->foto_fachada_url ?? asset('images/imovel-placeholder.svg') }}"
                                         alt="{{ $imovel->tipoImovel?->nome }}"
                                         class="w-full h-full object-cover"
                                         loading="lazy">
                                </div>

                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('imovel.show', $imovel->slug) }}"
                                       target="_blank"
                                       class="text-sm font-bold text-[#005CA9] hover:underline">
                                        #{{ $imovel->numero_original }}
                                    </a>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        {{ $imovel->tipoImovel?->nome }}
                                        — {{ $imovel->municipio?->nome }}/{{ $imovel->estado?->uf }}
                                    </p>
                                    @if($imovel->preco_venda_minimo)
                                        <p class="text-sm font-black text-[#005CA9] mt-1">
                                            R$ {{ number_format($imovel->preco_venda_minimo, 2, ',', '.') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Notas do Lead no detalhe --}}
            @if($lead->notes->isNotEmpty())
                <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-7">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-4">
                        Notas ({{ $lead->notes->count() }})
                    </span>
                    <div class="space-y-3">
                        @foreach($lead->notes as $nota)
                            <div class="bg-gray-50 rounded-2xl p-4 space-y-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="text-base">{{ \App\Models\LeadNote::TIPO_ICONS[$nota->tipo] ?? '📝' }}</span>
                                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                            {{ \App\Models\LeadNote::TIPOS[$nota->tipo] ?? $nota->tipo }}
                                        </span>
                                    </div>
                                    <span class="text-[10px] text-gray-400">{{ $nota->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <p class="text-sm text-gray-700 leading-relaxed">{{ $nota->conteudo }}</p>
                                @if($nota->autor)
                                    <p class="text-[10px] text-gray-400 font-bold">por {{ $nota->autor->name }}</p>
                                @endif
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

<style>
    @keyframes slide-down {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-down {
        animation: slide-down 0.3s ease-out;
    }
</style>

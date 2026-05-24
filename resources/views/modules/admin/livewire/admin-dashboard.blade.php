<div class="max-w-[1600px] mx-auto py-10 px-6 lg:px-10 space-y-10" wire:poll.3s>

    <!-- HEADER / HERO SECTION -->
    <div class="relative bg-gradient-to-r from-[#005CA9] via-blue-800 to-[#004A87] rounded-[2.5rem] p-8 md:p-10 text-white shadow-xl overflow-hidden group">
        <!-- Abstract decorative glowing shapes -->
        <div class="absolute -right-10 -top-10 w-48 h-48 rounded-full bg-blue-400/20 blur-3xl pointer-events-none group-hover:scale-125 transition-transform duration-700"></div>
        <div class="absolute right-[20%] -bottom-10 w-64 h-64 rounded-full bg-amber-400/10 blur-3xl pointer-events-none"></div>

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 relative z-10">
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-blue-200/80 bg-blue-900/40 px-3.5 py-1.5 rounded-full border border-blue-400/20">Área de Controle</span>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-black tracking-tight mt-4 mb-2">
                    Dashboard Geral
                </h1>
                <p class="text-blue-100/90 text-sm md:text-base font-medium max-w-xl">
                    Visão operacional completa e métricas de desempenho da plataforma sincronizadas em tempo real.
                </p>
            </div>
            
            <!-- Live Status / Date Badge -->
            <div class="flex items-center space-x-3 self-start md:self-auto bg-white/10 backdrop-blur-md border border-white/20 px-5 py-3 rounded-2xl shadow-lg">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-400"></span>
                </span>
                <span class="text-xs font-bold uppercase tracking-wider text-blue-100">Live Status</span>
                <span class="text-white/30">|</span>
                <span class="text-xs font-bold text-white">{{ now()->translatedFormat('d \d\e F, Y') }}</span>
            </div>
        </div>
    </div>

    <!-- IMPORT PROGRESS BANNER (Live) -->
    @if($this->importProgress)
        @php $progress = $this->importProgress; @endphp

        @if($progress['status'] === 'processing')
            <div class="relative bg-gradient-to-r from-[#005CA9] via-blue-700 to-indigo-800 rounded-[2rem] p-6 md:p-8 text-white shadow-xl overflow-hidden border border-blue-400/20">
                <!-- Animated background pulse -->
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-pulse"></div>
                <div class="absolute -right-8 -top-8 w-32 h-32 rounded-full bg-amber-400/15 blur-2xl animate-pulse"></div>

                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <span class="relative flex h-3.5 w-3.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#F39200] opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-[#F39200]"></span>
                            </span>
                            <h3 class="text-lg font-black uppercase tracking-wider">Importação em Andamento</h3>
                        </div>
                        <span class="text-xs font-bold text-blue-200/80 bg-blue-900/40 px-3 py-1.5 rounded-full border border-blue-400/20">
                            {{ $progress['file'] ?? 'CSV' }}
                        </span>
                    </div>

                    <!-- Progress Bar -->
                    @php
                        $total = max($progress['total'] ?? 1, 1);
                        $processed = $progress['processed'] ?? 0;
                        $pct = min(round(($processed / $total) * 100), 100);
                    @endphp
                    <div class="w-full bg-blue-950/50 rounded-full h-4 mb-4 overflow-hidden border border-blue-400/10">
                        <div class="h-full rounded-full bg-gradient-to-r from-[#F39200] via-amber-400 to-[#F39200] transition-all duration-700 ease-out relative"
                             style="width: {{ $pct }}%">
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-[shimmer_2s_infinite]"></div>
                        </div>
                    </div>

                    <!-- Counters -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/10 text-center">
                            <p class="text-2xl font-black text-white tabular-nums">{{ $pct }}%</p>
                            <p class="text-[9px] font-extrabold uppercase tracking-widest text-blue-200/80 mt-1">Progresso</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/10 text-center">
                            <p class="text-2xl font-black text-white tabular-nums">{{ number_format($processed) }}</p>
                            <p class="text-[9px] font-extrabold uppercase tracking-widest text-blue-200/80 mt-1">Linhas Lidas</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/10 text-center">
                            <p class="text-2xl font-black text-emerald-400 tabular-nums">{{ number_format($progress['inserted'] ?? 0) }}</p>
                            <p class="text-[9px] font-extrabold uppercase tracking-widest text-blue-200/80 mt-1">Inseridos</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/10 text-center">
                            <p class="text-2xl font-black text-amber-300 tabular-nums">{{ number_format($progress['skipped'] ?? 0) }}</p>
                            <p class="text-[9px] font-extrabold uppercase tracking-widest text-blue-200/80 mt-1">Ignorados</p>
                        </div>
                    </div>
                </div>
            </div>

        @elseif($progress['status'] === 'completed')
            <div class="relative bg-gradient-to-r from-emerald-600 via-emerald-700 to-teal-700 rounded-[2rem] p-6 md:p-8 text-white shadow-xl overflow-hidden border border-emerald-400/20">
                <div class="absolute -right-6 -top-6 w-28 h-28 rounded-full bg-emerald-300/15 blur-2xl"></div>

                <div class="flex items-center justify-between relative z-10">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white/15 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black">Importação Concluída!</h3>
                            <p class="text-emerald-100/80 text-sm font-medium mt-0.5">
                                <span class="font-black text-white">{{ number_format($progress['inserted'] ?? 0) }}</span> imóveis inseridos
                                @if(($progress['skipped'] ?? 0) > 0)
                                    · <span class="text-amber-200 font-bold">{{ number_format($progress['skipped']) }}</span> ignorados
                                @endif
                                · Arquivo: <span class="font-bold">{{ $progress['file'] ?? 'CSV' }}</span>
                            </p>
                        </div>
                    </div>
                    <button wire:click="dismissImportProgress"
                            class="w-10 h-10 bg-white/10 hover:bg-white/20 rounded-xl flex items-center justify-center border border-white/20 transition-all hover:scale-105 active:scale-95 cursor-pointer">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

        @elseif($progress['status'] === 'error')
            <div class="relative bg-gradient-to-r from-rose-600 via-red-700 to-rose-800 rounded-[2rem] p-6 md:p-8 text-white shadow-xl overflow-hidden border border-rose-400/20">
                <div class="flex items-center justify-between relative z-10">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white/15 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black">Erro na Importação</h3>
                            <p class="text-rose-100/80 text-sm font-medium mt-0.5">
                                {{ $progress['error'] ?? 'Erro desconhecido' }}
                                · <span class="font-bold">{{ number_format($progress['inserted'] ?? 0) }}</span> inseridos antes do erro
                            </p>
                        </div>
                    </div>
                    <button wire:click="dismissImportProgress"
                            class="w-10 h-10 bg-white/10 hover:bg-white/20 rounded-xl flex items-center justify-center border border-white/20 transition-all hover:scale-105 active:scale-95 cursor-pointer">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    @endif

    <!-- METRIC CARDS DECK (4 COLUMNS) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Imóveis Ativos -->
        <div class="group bg-gradient-to-br from-white to-blue-50/30 rounded-[2rem] border border-slate-100 hover:border-blue-200 shadow-sm hover:shadow-xl hover:shadow-blue-500/5 hover:-translate-y-1 transition-all duration-300 p-6 relative overflow-hidden">
            <!-- Background Glow -->
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-blue-500/10 to-transparent rounded-bl-full group-hover:scale-125 transition-transform duration-500"></div>
            
            <div class="flex items-center justify-between mb-5 relative z-10">
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Imóveis Ativos</span>
                <div class="w-11 h-11 bg-blue-50 text-[#005CA9] rounded-2xl flex items-center justify-center shadow-inner group-hover:bg-[#005CA9] group-hover:text-white transition-all duration-300">
                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
            <div class="relative z-10">
                <p class="text-4xl font-black text-slate-900 tracking-tight group-hover:text-[#005CA9] transition-colors">
                    {{ number_format($this->metricas['imoveis_ativos']) }}
                </p>
                <div class="flex items-center space-x-1.5 mt-2">
                    <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg flex items-center shrink-0">
                        Sincronizado
                    </span>
                    <span class="text-[10px] text-slate-400 flex items-center gap-1 truncate font-medium">
                        <span class="relative flex h-1.5 w-1.5 shrink-0">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-450 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                        </span>
                        Atualizando ao vivo
                    </span>
                </div>
            </div>
        </div>

        <!-- Total de Leads -->
        <div class="group bg-gradient-to-br from-white to-amber-50/30 rounded-[2rem] border border-slate-100 hover:border-amber-200 shadow-sm hover:shadow-xl hover:shadow-amber-500/5 hover:-translate-y-1 transition-all duration-300 p-6 relative overflow-hidden">
            <!-- Background Glow -->
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-[#F39200]/10 to-transparent rounded-bl-full group-hover:scale-125 transition-transform duration-500"></div>
            
            <div class="flex items-center justify-between mb-5 relative z-10">
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Total de Leads</span>
                <div class="w-11 h-11 bg-amber-50 text-[#F39200] rounded-2xl flex items-center justify-center shadow-inner group-hover:bg-[#F39200] group-hover:text-white transition-all duration-300">
                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
            <div class="relative z-10">
                <p class="text-4xl font-black text-slate-900 tracking-tight group-hover:text-[#F39200] transition-colors">
                    {{ number_format($this->metricas['total_leads']) }}
                </p>
                <div class="flex items-center space-x-1.5 mt-2">
                    <span class="text-[11px] font-bold text-slate-550 bg-slate-100 px-2 py-0.5 rounded-lg shrink-0">
                        Ativos
                    </span>
                    <span class="text-[10px] text-slate-400 truncate">Contatos interessados</span>
                </div>
            </div>
        </div>

        <!-- Total de Atendimentos -->
        <div class="group bg-gradient-to-br from-white to-emerald-50/30 rounded-[2rem] border border-slate-100 hover:border-emerald-200 shadow-sm hover:shadow-xl hover:shadow-emerald-500/5 hover:-translate-y-1 transition-all duration-300 p-6 relative overflow-hidden">
            <!-- Background Glow -->
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-emerald-500/10 to-transparent rounded-bl-full group-hover:scale-125 transition-transform duration-500"></div>
            
            <div class="flex items-center justify-between mb-5 relative z-10">
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Atendimentos</span>
                <div class="w-11 h-11 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shadow-inner group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300">
                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
            </div>
            <div class="relative z-10">
                <p class="text-4xl font-black text-slate-900 tracking-tight group-hover:text-emerald-600 transition-colors">
                    {{ number_format($this->metricas['total_atendimentos']) }}
                </p>
                <div class="flex items-center space-x-1.5 mt-2">
                    <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg shrink-0">
                        Disparos
                    </span>
                    <span class="text-[10px] text-slate-400 truncate">Integrações enviadas</span>
                </div>
            </div>
        </div>

        <!-- Imobiliárias -->
        <div class="group bg-gradient-to-br from-white to-purple-50/30 rounded-[2rem] border border-slate-100 hover:border-purple-200 shadow-sm hover:shadow-xl hover:shadow-purple-500/5 hover:-translate-y-1 transition-all duration-300 p-6 relative overflow-hidden">
            <!-- Background Glow -->
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-purple-500/10 to-transparent rounded-bl-full group-hover:scale-125 transition-transform duration-500"></div>
            
            <div class="flex items-center justify-between mb-5 relative z-10">
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Imobiliárias</span>
                <div class="w-11 h-11 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center shadow-inner group-hover:bg-purple-600 group-hover:text-white transition-all duration-300">
                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
            <div class="relative z-10">
                <p class="text-4xl font-black text-slate-900 tracking-tight group-hover:text-purple-600 transition-colors">
                    {{ number_format($this->metricas['imobiliarias_ativas']) }}
                </p>
                <div class="flex items-center space-x-1.5 mt-2">
                    <span class="text-[11px] font-bold text-[#005CA9] bg-blue-50 px-2 py-0.5 rounded-lg shrink-0">
                        Credenciadas
                    </span>
                    <span class="text-[10px] text-slate-400 truncate">Parceiras ativas</span>
                </div>
            </div>
        </div>
    </div>

    <!-- CARDS DE PERÍODO (LEADS E ATENDIMENTOS) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- Leads por período -->
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6 lg:p-8 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-bold text-slate-800 flex items-center space-x-2.5">
                    <span class="w-2.5 h-2.5 bg-[#F39200] rounded-full shadow-lg shadow-amber-500/30"></span>
                    <span>Leads Captados por Período</span>
                </h3>
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 bg-slate-50 px-2.5 py-1 rounded-lg">Performance</span>
            </div>
            
            <div class="grid grid-cols-3 gap-4 lg:gap-6">
                <div class="bg-gradient-to-b from-[#F39200]/[0.04] to-transparent border border-[#F39200]/10 rounded-2xl p-5 text-center transition-all hover:bg-[#F39200]/[0.06]">
                    <p class="text-3xl font-black text-[#F39200]">{{ $this->metricas['leads_hoje'] }}</p>
                    <p class="text-[10px] font-extrabold uppercase text-[#F39200]/85 mt-2.5 tracking-wider">Hoje</p>
                </div>
                <div class="bg-slate-50/50 border border-slate-100 rounded-2xl p-5 text-center transition-all hover:bg-slate-50">
                    <p class="text-3xl font-black text-slate-700">{{ $this->metricas['leads_7d'] }}</p>
                    <p class="text-[10px] font-bold uppercase text-slate-400 mt-2.5 tracking-wider">Últimos 7 dias</p>
                </div>
                <div class="bg-slate-50/50 border border-slate-100 rounded-2xl p-5 text-center transition-all hover:bg-slate-50">
                    <p class="text-3xl font-black text-slate-700">{{ $this->metricas['leads_30d'] }}</p>
                    <p class="text-[10px] font-bold uppercase text-slate-400 mt-2.5 tracking-wider">Últimos 30 dias</p>
                </div>
            </div>
        </div>

        <!-- Atendimentos por período -->
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6 lg:p-8 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-bold text-slate-800 flex items-center space-x-2.5">
                    <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full shadow-lg shadow-emerald-500/30"></span>
                    <span>Atendimentos Concluídos</span>
                </h3>
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 bg-slate-50 px-2.5 py-1 rounded-lg">Conversão</span>
            </div>
            
            <div class="grid grid-cols-3 gap-4 lg:gap-6">
                <div class="bg-gradient-to-b from-emerald-500/[0.04] to-transparent border border-emerald-500/10 rounded-2xl p-5 text-center transition-all hover:bg-emerald-500/[0.06]">
                    <p class="text-3xl font-black text-emerald-600">{{ $this->metricas['atendimentos_hoje'] }}</p>
                    <p class="text-[10px] font-extrabold uppercase text-[#005CA9]/85 mt-2.5 tracking-wider">Hoje</p>
                </div>
                <div class="bg-slate-50/50 border border-slate-100 rounded-2xl p-5 text-center transition-all hover:bg-slate-50">
                    <p class="text-3xl font-black text-slate-700">{{ $this->metricas['atendimentos_7d'] }}</p>
                    <p class="text-[10px] font-bold uppercase text-slate-400 mt-2.5 tracking-wider">Últimos 7 dias</p>
                </div>
                <div class="bg-slate-50/50 border border-slate-100 rounded-2xl p-5 text-center transition-all hover:bg-slate-50">
                    <p class="text-3xl font-black text-slate-700">{{ $this->metricas['atendimentos_30d'] }}</p>
                    <p class="text-[10px] font-bold uppercase text-slate-400 mt-2.5 tracking-wider">Últimos 30 dias</p>
                </div>
            </div>
        </div>
    </div>

    <!-- LISTAS DE DADOS PRINCIPAIS -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">

        <!-- Últimos 10 Atendimentos -->
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6 lg:p-8 flex flex-col min-w-0 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-6 border-b border-slate-50 pb-4">
                <h3 class="text-base font-bold text-slate-800 flex items-center space-x-2.5">
                    <span class="w-2.5 h-2.5 bg-[#005CA9] rounded-full shadow-lg shadow-blue-500/30"></span>
                    <span>Últimos Contatos / Atendimentos</span>
                </h3>
                <span class="text-xs text-slate-400 font-semibold">Atualização em tempo real</span>
            </div>

            @if($this->ultimosAtendimentos->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center mb-4 text-slate-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h4 class="text-sm font-bold text-slate-700">Nenhum atendimento registrado</h4>
                    <p class="text-xs text-slate-400 mt-1 max-w-[260px]">Os novos contatos de leads captados no site aparecerão listados aqui.</p>
                </div>
            @else
                <div class="divide-y divide-slate-50 flex-1">
                    @foreach($this->ultimosAtendimentos as $at)
                        <div class="flex items-start justify-between py-4 first:pt-0 last:pb-0 hover:bg-slate-50/40 rounded-xl px-2 -mx-2 transition-colors">
                            <div class="min-w-0 flex-1 pr-4">
                                <p class="text-sm font-bold text-slate-800 truncate leading-snug">{{ $at->lead?->nome ?? 'Lead Sem Nome' }}</p>
                                <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                    <span class="text-[10px] font-bold text-[#005CA9] bg-blue-50 px-2 py-0.5 rounded">
                                        {{ $at->imovel?->tipoImovel?->nome ?? 'Imóvel' }}
                                    </span>
                                    <span class="text-[10px] font-medium text-slate-500">
                                        · {{ $at->imovel?->municipio?->nome }}/{{ $at->imovel?->estado?->uf }}
                                    </span>
                                    @if($at->imobiliaria)
                                        <span class="text-[10px] font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded">
                                            {{ $at->imobiliaria->nome }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <span class="text-[10px] text-slate-400 font-bold bg-slate-100/80 px-2.5 py-1 rounded-lg shrink-0 self-center">
                                {{ $at->created_at->diffForHumans() }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Top 10 Imóveis Mais Procurados -->
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6 lg:p-8 flex flex-col min-w-0 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-6 border-b border-slate-50 pb-4">
                <h3 class="text-base font-bold text-slate-800 flex items-center space-x-2.5">
                    <span class="w-2.5 h-2.5 bg-[#F39200] rounded-full shadow-lg shadow-amber-500/30"></span>
                    <span>Imóveis Mais Procurados (Top 10)</span>
                </h3>
                <span class="text-xs text-slate-400 font-semibold">Por quantidade de contatos</span>
            </div>

            @if($this->topImoveis->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center mb-4 text-slate-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                    </div>
                    <h4 class="text-sm font-bold text-slate-700">Nenhum dado de procura ainda</h4>
                    <p class="text-xs text-slate-400 mt-1 max-w-[260px]">A lista dos imóveis mais acessados e procurados será exibida aqui.</p>
                </div>
            @else
                <div class="divide-y divide-slate-50 flex-1">
                    @foreach($this->topImoveis as $i => $imovel)
                        <div class="flex items-center space-x-4 py-3.5 first:pt-0 last:pb-0 hover:bg-slate-50/40 rounded-xl px-2 -mx-2 transition-colors">
                            <span class="w-7 h-7 flex-shrink-0 rounded-full bg-slate-100/80 border border-slate-200 flex items-center justify-center text-[11px] font-extrabold text-slate-500 shadow-sm">
                                {{ $i + 1 }}
                            </span>
                            <div class="min-w-0 flex-1 pr-4">
                                <p class="text-sm font-bold text-slate-800 truncate leading-snug">
                                    {{ $imovel->tipoImovel?->nome ?? 'Lote/Imóvel' }} #{{ $imovel->numero_original }}
                                </p>
                                <p class="text-xs text-slate-400 mt-0.5 font-medium truncate">
                                    {{ $imovel->municipio?->nome }}/{{ $imovel->estado?->uf }} · Bairro: {{ $imovel->bairro ?? 'Não informado' }}
                                </p>
                            </div>
                            <div class="flex-shrink-0 text-right">
                                <span class="text-base font-extrabold text-[#005CA9] bg-blue-50/60 px-3 py-1 rounded-xl shadow-sm">{{ $imovel->atendimentos_count }}</span>
                                <p class="text-[9px] text-slate-400 font-extrabold uppercase mt-1 tracking-wider">contatos</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>

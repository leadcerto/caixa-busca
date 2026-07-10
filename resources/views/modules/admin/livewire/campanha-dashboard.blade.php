<div class="p-6 space-y-6">

    {{-- Cabeçalho --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">📊 Campanhas</h1>
            <p class="text-sm text-slate-500 mt-0.5">Rastreamento de acessos às páginas de bairros via UTM</p>
        </div>

        {{-- Filtros --}}
        <div class="flex flex-wrap gap-2 items-center">

            {{-- Período --}}
            <select wire:model.live="period"
                    id="filtro-periodo"
                    class="text-sm border border-slate-200 rounded-lg px-3 py-2 bg-white text-slate-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="hoje">Hoje</option>
                <option value="7d">Últimos 7 dias</option>
                <option value="30d">Últimos 30 dias</option>
                <option value="todos">Todo o período</option>
            </select>

            {{-- Fonte (UTM Source) --}}
            <select wire:model.live="utmSource"
                    id="filtro-fonte"
                    class="text-sm border border-slate-200 rounded-lg px-3 py-2 bg-white text-slate-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Todas as fontes</option>
                @foreach ($fontesDisponiveis as $fonte)
                    <option value="{{ $fonte }}">{{ $fonte }}</option>
                @endforeach
            </select>

        </div>
    </div>

    {{-- Cards de Métricas --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total no Período</p>
            <p class="text-3xl font-black text-slate-800 mt-1">{{ number_format($totalAcessos) }}</p>
            <p class="text-xs text-slate-400 mt-1">page views registrados</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Acessos Hoje</p>
            <p class="text-3xl font-black text-[#005CA9] mt-1">{{ number_format($acessosHoje) }}</p>
            <p class="text-xs text-slate-400 mt-1">independente do filtro</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Fontes Únicas</p>
            <p class="text-3xl font-black text-emerald-600 mt-1">{{ $fontesUnicas }}</p>
            <p class="text-xs text-slate-400 mt-1">utm_source distintos</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">% Mobile</p>
            <p class="text-3xl font-black text-violet-600 mt-1">{{ $pctMobile }}%</p>
            <p class="text-xs text-slate-400 mt-1">dos acessos no período</p>
        </div>

    </div>

    {{-- Linha inferior: Fontes + Bairros --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Acessos por Fonte (UTM Source) --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <h2 class="text-sm font-bold text-slate-700">Acessos por Fonte</h2>
                <p class="text-xs text-slate-400">utm_source capturado no período</p>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse ($porFonte as $row)
                    @php
                        $pct = $totalAcessos > 0 ? round(($row->total / $totalAcessos) * 100) : 0;
                        $iconeFonte = match(strtolower($row->fonte)) {
                            'facebook'  => '📘',
                            'instagram' => '📸',
                            'google'    => '🔍',
                            'youtube'   => '▶️',
                            'email'     => '📧',
                            'direto'    => '🔗',
                            default     => '📡',
                        };
                    @endphp
                    <div class="flex items-center gap-3 px-5 py-3">
                        <span class="text-lg">{{ $iconeFonte }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-700 truncate">{{ $row->fonte }}</p>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-1">
                                <div class="bg-[#005CA9] h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-sm font-black text-slate-800">{{ number_format($row->total) }}</p>
                            <p class="text-xs text-slate-400">{{ $pct }}%</p>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-slate-400 text-sm">
                        Nenhum acesso registrado no período.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Top Bairros --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <h2 class="text-sm font-bold text-slate-700">Top Bairros</h2>
                <p class="text-xs text-slate-400">páginas mais acessadas no período</p>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse ($topBairros as $row)
                    @php
                        $bairro = $row->bairro;
                        $nome   = $bairro?->nome ?? '—';
                        $cidade = $bairro?->municipio?->nome ?? '';
                        $uf     = strtoupper($bairro?->municipio?->estado?->uf ?? '');
                        $pctB   = $totalAcessos > 0 ? round(($row->total / $totalAcessos) * 100) : 0;
                    @endphp
                    <div class="flex items-center gap-3 px-5 py-3">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-700 truncate">{{ $nome }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ $cidade }}{{ $uf ? " — $uf" : '' }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-sm font-black text-slate-800">{{ number_format($row->total) }}</p>
                            <p class="text-xs text-slate-400">acessos</p>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-slate-400 text-sm">
                        Nenhum acesso registrado no período.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Campanhas Ativas --}}
    @if ($campanhasAtivas->isNotEmpty())
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100">
            <h2 class="text-sm font-bold text-slate-700">Campanhas Ativas</h2>
            <p class="text-xs text-slate-400">agrupado por utm_campaign no período selecionado</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">Campanha</th>
                        <th class="px-5 py-3 text-left font-semibold">Fonte</th>
                        <th class="px-5 py-3 text-right font-semibold">Acessos</th>
                        <th class="px-5 py-3 text-right font-semibold hidden md:table-cell">Primeiro acesso</th>
                        <th class="px-5 py-3 text-right font-semibold hidden md:table-cell">Último acesso</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach ($campanhasAtivas as $campanha)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3 font-semibold text-slate-800 max-w-[200px] truncate">
                                {{ $campanha->utm_campaign }}
                            </td>
                            <td class="px-5 py-3">
                                <span class="inline-block bg-blue-50 text-blue-700 text-xs font-semibold px-2 py-0.5 rounded-full">
                                    {{ $campanha->utm_source ?? 'direto' }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right font-black text-slate-800">
                                {{ number_format($campanha->total) }}
                            </td>
                            <td class="px-5 py-3 text-right text-slate-500 text-xs hidden md:table-cell">
                                {{ \Carbon\Carbon::parse($campanha->primeiro_acesso)->format('d/m/y H:i') }}
                            </td>
                            <td class="px-5 py-3 text-right text-slate-500 text-xs hidden md:table-cell">
                                {{ \Carbon\Carbon::parse($campanha->ultimo_acesso)->format('d/m/y H:i') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Acessos Recentes --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100">
            <h2 class="text-sm font-bold text-slate-700">Acessos Recentes</h2>
            <p class="text-xs text-slate-400">últimos 50 page views registrados</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">Data/Hora</th>
                        <th class="px-5 py-3 text-left font-semibold">Bairro</th>
                        <th class="px-5 py-3 text-left font-semibold">Fonte</th>
                        <th class="px-5 py-3 text-left font-semibold hidden lg:table-cell">Campanha</th>
                        <th class="px-5 py-3 text-center font-semibold">Device</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($acessosRecentes as $acesso)
                        @php
                            $bairro = $acesso->bairro;
                            $nomeBairro = $bairro?->nome ?? '—';
                            $cidadeUf   = trim(($bairro?->municipio?->nome ?? '') . ' ' . strtoupper($bairro?->municipio?->estado?->uf ?? ''));
                            $deviceIcon = match($acesso->device_type) {
                                'mobile'  => '📱',
                                'tablet'  => '📟',
                                default   => '💻',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-2.5 text-xs text-slate-500 whitespace-nowrap">
                                {{ $acesso->created_at->format('d/m/y H:i') }}
                            </td>
                            <td class="px-5 py-2.5">
                                <p class="font-semibold text-slate-700 truncate max-w-[130px]">{{ $nomeBairro }}</p>
                                <p class="text-xs text-slate-400">{{ $cidadeUf }}</p>
                            </td>
                            <td class="px-5 py-2.5">
                                @if ($acesso->utm_source)
                                    <span class="inline-block bg-blue-50 text-blue-700 text-xs font-semibold px-2 py-0.5 rounded-full">
                                        {{ $acesso->utm_source }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400">direto</span>
                                @endif
                            </td>
                            <td class="px-5 py-2.5 text-xs text-slate-500 hidden lg:table-cell truncate max-w-[160px]">
                                {{ $acesso->utm_campaign ?? '—' }}
                            </td>
                            <td class="px-5 py-2.5 text-center text-base">
                                {{ $deviceIcon }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-slate-400">
                                Nenhum acesso registrado ainda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

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

    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- SEÇÃO: CRIAR VITRINE                                                 --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    <div class="bg-gradient-to-br from-[#005CA9] to-blue-700 rounded-2xl shadow-lg shadow-blue-500/20 p-6 text-white">
        <div class="flex items-center gap-3 mb-4">
            <span class="text-2xl">🚀</span>
            <div>
                <h2 class="text-lg font-black">Criar Vitrine para Anúncios</h2>
                <p class="text-blue-200 text-xs">Cole a URL de uma busca do site e gere uma landing page limpa para seus anúncios</p>
            </div>
        </div>

        {{-- Mensagem de feedback --}}
        @if ($mensagem)
            <div class="mb-4 px-4 py-3 rounded-xl text-sm font-semibold
                {{ $mensagemTipo === 'sucesso' ? 'bg-emerald-500/20 text-emerald-100 border border-emerald-400/30' : 'bg-red-500/20 text-red-100 border border-red-400/30' }}">
                {{ $mensagem }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="text-xs font-bold text-blue-200 uppercase tracking-wider mb-1 block">Nome da Campanha</label>
                <input wire:model="novaVitrineNome"
                       type="text"
                       placeholder="Ex: Taquara SBPE Julho"
                       class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-blue-200/50 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-white/40 focus:bg-white/15 transition">
            </div>
            <div>
                <label class="text-xs font-bold text-blue-200 uppercase tracking-wider mb-1 block">URL da Busca</label>
                <input wire:model="novaVitrineUrl"
                       type="text"
                       placeholder="Cole aqui a URL da busca (ex: /imoveis/rj/rio-de-janeiro?...)"
                       class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-blue-200/50 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-white/40 focus:bg-white/15 transition">
            </div>
        </div>
        <div class="mt-4 flex items-center gap-3">
            <button wire:click="criarVitrine"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 bg-white text-[#005CA9] font-black text-sm px-6 py-3 rounded-xl hover:bg-blue-50 transition shadow-lg disabled:opacity-50">
                <span wire:loading.remove wire:target="criarVitrine">✨ Criar Vitrine</span>
                <span wire:loading wire:target="criarVitrine">⏳ Criando...</span>
            </button>
            <p class="text-blue-200/60 text-xs">A URL será gerada automaticamente a partir do nome</p>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- SEÇÃO: VITRINES CADASTRADAS                                          --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    @if ($vitrines->isNotEmpty())
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-700">🎯 Vitrines Cadastradas</h2>
                <p class="text-xs text-slate-400">Copie os links abaixo para usar nos seus anúncios</p>
            </div>
            <span class="bg-[#005CA9] text-white text-xs font-black px-3 py-1 rounded-full">{{ $vitrines->count() }}</span>
        </div>

        <div class="divide-y divide-slate-50">
            @foreach ($vitrines as $vit)
                <div class="px-5 py-4 {{ !$vit->ativa ? 'opacity-50' : '' }}">
                    {{-- Linha 1: Nome + Status + Ações --}}
                    <div class="flex items-center justify-between gap-3 mb-3">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="text-lg">{{ $vit->ativa ? '🟢' : '🔴' }}</span>
                            <h3 class="font-black text-slate-800 truncate">{{ $vit->nome }}</h3>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <button wire:click="toggleVitrine({{ $vit->id }})"
                                    class="text-xs font-bold px-3 py-1.5 rounded-lg transition
                                    {{ $vit->ativa ? 'bg-amber-50 text-amber-700 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                                {{ $vit->ativa ? '⏸ Pausar' : '▶ Ativar' }}
                            </button>
                            <button wire:click="excluirVitrine({{ $vit->id }})"
                                    wire:confirm="Tem certeza que deseja excluir esta vitrine?"
                                    class="text-xs font-bold px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition">
                                🗑 Excluir
                            </button>
                        </div>
                    </div>

                    {{-- Linha 2: URL da vitrine --}}
                    <div class="bg-slate-50 rounded-xl px-4 py-3 mb-3">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Link da Vitrine</p>
                        <div class="flex items-center gap-2">
                            <code class="text-xs text-[#005CA9] font-bold break-all flex-1" id="url-vitrine-{{ $vit->id }}">{{ $vit->url() }}</code>
                            <button onclick="navigator.clipboard.writeText(document.getElementById('url-vitrine-{{ $vit->id }}').textContent);this.textContent='✅';setTimeout(()=>this.textContent='📋',1500)"
                                    class="text-base shrink-0 hover:scale-110 transition-transform cursor-pointer" title="Copiar">📋</button>
                        </div>
                    </div>

                    {{-- Linha 3: Links com UTM por plataforma --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                        {{-- Facebook --}}
                        <div class="bg-blue-50 rounded-xl px-3 py-2.5">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-black text-blue-800">📘 Facebook Ads</span>
                                <button onclick="navigator.clipboard.writeText(document.getElementById('url-fb-{{ $vit->id }}').textContent);this.textContent='✅';setTimeout(()=>this.textContent='📋',1500)"
                                        class="text-sm hover:scale-110 transition-transform cursor-pointer" title="Copiar">📋</button>
                            </div>
                            <code class="text-[10px] text-blue-600 break-all leading-tight block" id="url-fb-{{ $vit->id }}">{{ $vit->urlComUtm('facebook', 'cpc') }}</code>
                        </div>

                        {{-- Instagram --}}
                        <div class="bg-pink-50 rounded-xl px-3 py-2.5">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-black text-pink-800">📸 Instagram</span>
                                <button onclick="navigator.clipboard.writeText(document.getElementById('url-ig-{{ $vit->id }}').textContent);this.textContent='✅';setTimeout(()=>this.textContent='📋',1500)"
                                        class="text-sm hover:scale-110 transition-transform cursor-pointer" title="Copiar">📋</button>
                            </div>
                            <code class="text-[10px] text-pink-600 break-all leading-tight block" id="url-ig-{{ $vit->id }}">{{ $vit->urlComUtm('instagram', 'social') }}</code>
                        </div>

                        {{-- Google --}}
                        <div class="bg-emerald-50 rounded-xl px-3 py-2.5">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-black text-emerald-800">🔍 Google Ads</span>
                                <button onclick="navigator.clipboard.writeText(document.getElementById('url-gg-{{ $vit->id }}').textContent);this.textContent='✅';setTimeout(()=>this.textContent='📋',1500)"
                                        class="text-sm hover:scale-110 transition-transform cursor-pointer" title="Copiar">📋</button>
                            </div>
                            <code class="text-[10px] text-emerald-600 break-all leading-tight block" id="url-gg-{{ $vit->id }}">{{ $vit->urlComUtm('google', 'cpc') }}</code>
                        </div>
                    </div>

                    {{-- Linha 4: Info --}}
                    <div class="flex items-center gap-4 mt-3 text-[10px] text-slate-400">
                        <span>Criada em {{ $vit->created_at->format('d/m/Y H:i') }}</span>
                        <span>•</span>
                        <span>Filtros: {{ collect($vit->filtros)->map(fn($v, $k) => $k . '=' . (is_array($v) ? implode(',', $v) : $v))->implode(' | ') }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

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

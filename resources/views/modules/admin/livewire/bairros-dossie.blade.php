<div class="max-w-7xl mx-auto px-6 py-10 space-y-8">

    {{-- Cabeçalho --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-900">Bairros Dossiê</h1>
            <p class="text-sm text-gray-500 mt-1">Geração de conteúdo IA para páginas de bairros (SEO).</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="resetarParaFaq"
                    wire:loading.attr="disabled"
                    wire:confirm="Resetar bairros com conteúdo antigo (sem FAQ) para regeração? Isso apagará o conteúdo desatualizado."
                    class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 disabled:opacity-50 text-white text-sm font-bold px-4 py-2.5 rounded-xl transition-colors cursor-pointer">
                <svg wire:loading wire:target="resetarParaFaq" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <svg wire:loading.remove wire:target="resetarParaFaq" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Resetar conteúdo antigo
            </button>
            <button wire:click="gerarLote"
                    wire:loading.attr="disabled"
                    wire:confirm="Enfileirar geração de conteúdo para todos os bairros filtrados?"
                    class="flex items-center gap-2 bg-purple-600 hover:bg-purple-700 disabled:opacity-50 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-colors cursor-pointer">
                <svg wire:loading wire:target="gerarLote" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <svg wire:loading.remove wire:target="gerarLote" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Gerar em lote
            </button>
        </div>
    </div>

    {{-- Feedback --}}
    @if($mensagem)
        <div @class([
            'flex items-center gap-2 text-sm rounded-xl px-4 py-3 border',
            'bg-green-50 border-green-200 text-green-800' => $sucesso,
            'bg-yellow-50 border-yellow-200 text-yellow-800' => !$sucesso,
        ])>
            {{ $mensagem }}
        </div>
    @endif

    {{-- Cards de status --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @foreach([
            ['label' => 'Total', 'valor' => $totais['total'], 'cor' => 'gray'],
            ['label' => 'Gerados', 'valor' => $totais['gerado'], 'cor' => 'green'],
            ['label' => 'Pendentes', 'valor' => $totais['pendente'], 'cor' => 'yellow'],
            ['label' => 'Com Erro', 'valor' => $totais['erro'], 'cor' => 'red'],
        ] as $card)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $card['label'] }}</p>
                <p @class([
                    'text-2xl font-black mt-1',
                    'text-gray-800' => $card['cor'] === 'gray',
                    'text-green-600' => $card['cor'] === 'green',
                    'text-yellow-600' => $card['cor'] === 'yellow',
                    'text-red-600' => $card['cor'] === 'red',
                ])>{{ number_format($card['valor']) }}</p>
            </div>
        @endforeach
    </div>

    {{-- Filtros --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <select wire:model.live="estadoId"
                    class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Todos os estados</option>
                @foreach($estados as $estado)
                    <option value="{{ $estado->id }}">{{ $estado->uf }} — {{ $estado->nome }}</option>
                @endforeach
            </select>

            <select wire:model.live="municipioId"
                    class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    @disabled(!$estadoId)>
                <option value="">Todos os municípios</option>
                @foreach($municipios as $municipio)
                    <option value="{{ $municipio->id }}">{{ $municipio->nome }}</option>
                @endforeach
            </select>

            <select wire:model.live="statusFiltro"
                    class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Todos os status</option>
                <option value="pendente">Pendente</option>
                <option value="gerado">Gerado</option>
                <option value="erro">Com Erro</option>
            </select>
        </div>
    </div>

    {{-- Tabela --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 text-xs text-gray-400 uppercase tracking-widest">
                        <th class="px-6 py-3 text-left font-bold">Bairro</th>
                        <th class="px-6 py-3 text-left font-bold">Município / UF</th>
                        <th class="px-6 py-3 text-left font-bold">Imóveis ↓</th>
                        <th class="px-6 py-3 text-left font-bold">Status IA</th>
                        <th class="px-6 py-3 text-left font-bold">Gerado em</th>
                        <th class="px-6 py-3 text-right font-bold">Ação</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($bairros as $bairro)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 font-medium text-gray-800">{{ $bairro->nome }}</td>
                            <td class="px-6 py-3 text-gray-500">
                                {{ $bairro->municipio?->nome }} / {{ $bairro->municipio?->estado?->uf }}
                            </td>
                            <td class="px-6 py-3 text-gray-500">{{ $bairro->imoveis_count }}</td>
                            <td class="px-6 py-3">
                                @php
                                    $cores = [
                                        'gerado'  => 'bg-green-100 text-green-700',
                                        'pendente' => 'bg-yellow-100 text-yellow-700',
                                        'erro'    => 'bg-red-100 text-red-700',
                                    ];
                                    $cor = $cores[$bairro->ia_status] ?? 'bg-gray-100 text-gray-600';
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $cor }}">
                                    {{ ucfirst($bairro->ia_status ?? 'pendente') }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-xs text-gray-400 font-mono">
                                {{ $bairro->ia_gerado_em?->format('d/m/Y H:i') ?? '—' }}
                            </td>
                            <td class="px-6 py-3 text-right">
                                <button wire:click="gerarUm({{ $bairro->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="gerarUm({{ $bairro->id }})"
                                        class="text-xs font-bold text-purple-600 hover:text-purple-800 px-3 py-1.5 border border-purple-200 hover:border-purple-300 rounded-xl transition-colors disabled:opacity-40">
                                    <span wire:loading wire:target="gerarUm({{ $bairro->id }})">...</span>
                                    <span wire:loading.remove wire:target="gerarUm({{ $bairro->id }})">
                                        {{ $bairro->ia_status === 'gerado' ? 'Re-gerar' : 'Gerar' }}
                                    </span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-400">
                                Nenhum bairro encontrado com os filtros selecionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bairros->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $bairros->links() }}
            </div>
        @endif
    </div>

    {{-- Aviso configuração --}}
    <div class="bg-blue-50 border border-blue-100 rounded-2xl px-5 py-4 text-sm text-blue-700">
        <strong>Pré-requisito:</strong> Configure <code class="bg-blue-100 px-1 rounded">OPENROUTER_API_KEY</code> e <code class="bg-blue-100 px-1 rounded">OPENROUTER_MODEL</code> no <code class="bg-blue-100 px-1 rounded">.env</code> e certifique-se de que a fila de jobs está rodando (<code class="bg-blue-100 px-1 rounded">php artisan queue:work</code>).
    </div>

</div>

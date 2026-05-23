<div class="max-w-7xl mx-auto px-6 py-10 space-y-8">

    {{-- Cabeçalho --}}
    <div>
        <h1 class="text-2xl font-black text-gray-900">Integração CRM</h1>
        <p class="text-sm text-gray-500 mt-1">Configure o webhook de envio de leads para o CRM externo.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- Formulário de configuração --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
            <h2 class="text-base font-bold text-gray-800">Configuração do Webhook</h2>

            @if($mensagemSucesso)
                <div class="flex items-center gap-2 text-sm text-green-700 bg-green-50 border border-green-200 rounded-xl px-4 py-3">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ $mensagemSucesso }}
                </div>
            @endif

            @if($mensagemErro)
                <div class="flex items-center gap-2 text-sm text-red-700 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
                    {{ $mensagemErro }}
                </div>
            @endif

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">URL do Webhook</label>
                    <input wire:model="webhookUrl"
                           type="url"
                           placeholder="https://seu-crm.com/api/webhook"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('webhookUrl') border-red-400 @enderror">
                    @error('webhookUrl')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Token de Autenticação</label>
                    <input wire:model="webhookToken"
                           type="text"
                           placeholder="token_secreto"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-400 mt-1">Enviado no header <code class="bg-gray-100 px-1 rounded">X-Webhook-Token</code>.</p>
                </div>

                <label class="flex items-center gap-3 cursor-pointer select-none">
                    <div class="relative">
                        <input wire:model="ativo" type="checkbox" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#005CA9] transition-colors"></div>
                        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Webhook ativo</span>
                </label>
            </div>

            <div class="flex gap-3 pt-2">
                <button wire:click="salvar"
                        class="flex-1 bg-[#005CA9] hover:bg-blue-800 text-white text-sm font-bold py-2.5 rounded-xl transition-colors">
                    Salvar
                </button>
                <button wire:click="testarConexao"
                        wire:loading.attr="disabled"
                        class="flex items-center gap-2 px-4 py-2.5 border border-gray-200 hover:border-gray-300 rounded-xl text-sm font-bold text-gray-600 transition-colors disabled:opacity-50">
                    <svg wire:loading wire:target="testarConexao" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <svg wire:loading.remove wire:target="testarConexao" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Testar
                </button>
            </div>

            {{-- Resultado do teste --}}
            @if($resultadoTeste)
                <div @class([
                    'text-sm rounded-xl px-4 py-3 border',
                    'bg-green-50 border-green-200 text-green-800' => $corResultado === 'green',
                    'bg-yellow-50 border-yellow-200 text-yellow-800' => $corResultado === 'yellow',
                    'bg-red-50 border-red-200 text-red-800' => $corResultado === 'red',
                ])>
                    {{ $resultadoTeste }}
                </div>
            @endif

            {{-- Status atual --}}
            @if($config)
                <div class="bg-gray-50 rounded-xl px-4 py-3 text-xs text-gray-500 space-y-1">
                    <div class="flex justify-between">
                        <span>Status:</span>
                        <span @class(['font-bold', 'text-green-600' => $config->ativo, 'text-gray-400' => !$config->ativo])>
                            {{ $config->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </div>
                    @if($config->ultimo_envio_em)
                        <div class="flex justify-between">
                            <span>Último envio:</span>
                            <span class="font-medium">{{ $config->ultimo_envio_em->format('d/m/Y H:i:s') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Último status:</span>
                            <span @class([
                                'font-bold capitalize',
                                'text-green-600' => $config->ultimo_status === 'sucesso',
                                'text-yellow-600' => $config->ultimo_status === 'falha',
                                'text-red-600' => $config->ultimo_status === 'erro',
                            ])>{{ $config->ultimo_status }}</span>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        {{-- Painel info --}}
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 space-y-4">
            <h2 class="text-base font-bold text-blue-800">Como funciona</h2>
            <ul class="space-y-3 text-sm text-blue-700">
                <li class="flex gap-3">
                    <span class="shrink-0 mt-0.5 w-5 h-5 rounded-full bg-blue-200 text-blue-800 text-xs flex items-center justify-center font-bold">1</span>
                    <span>Quando um lead preenche o formulário, um <strong>Job assíncrono</strong> é disparado com os dados do atendimento.</span>
                </li>
                <li class="flex gap-3">
                    <span class="shrink-0 mt-0.5 w-5 h-5 rounded-full bg-blue-200 text-blue-800 text-xs flex items-center justify-center font-bold">2</span>
                    <span>O job faz um <strong>POST JSON</strong> para a URL configurada, incluindo dados do lead, imóvel, localidade e UTMs.</span>
                </li>
                <li class="flex gap-3">
                    <span class="shrink-0 mt-0.5 w-5 h-5 rounded-full bg-blue-200 text-blue-800 text-xs flex items-center justify-center font-bold">3</span>
                    <span>Em caso de falha, o sistema <strong>tenta novamente automaticamente</strong> (5x com backoff: 30s → 2min → 10min).</span>
                </li>
                <li class="flex gap-3">
                    <span class="shrink-0 mt-0.5 w-5 h-5 rounded-full bg-blue-200 text-blue-800 text-xs flex items-center justify-center font-bold">4</span>
                    <span>Se o banco não tiver configuração ativa, usa as variáveis <code class="bg-blue-100 px-1 rounded">CRM_WEBHOOK_URL</code> / <code class="bg-blue-100 px-1 rounded">CRM_WEBHOOK_TOKEN</code> do <code class="bg-blue-100 px-1 rounded">.env</code>.</span>
                </li>
            </ul>

            <div class="mt-4 bg-white rounded-xl border border-blue-100 p-4">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Exemplo de payload enviado</p>
                <pre class="text-xs text-gray-700 overflow-x-auto whitespace-pre-wrap">{
  "imovel_id": 12345,
  "tipo_imovel": "Apartamento",
  "valor": 350000,
  "localidade": "São Paulo, SP",
  "lead": {
    "nome": "João Silva",
    "email": "joao@email.com",
    "telefone": "11999999999"
  },
  "timestamp": "2026-05-19T10:30:00-03:00",
  "marketing": { "utm_source": "google" }
}</pre>
            </div>
        </div>
    </div>

    {{-- Histórico de envios --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-base font-bold text-gray-800">Histórico de Envios</h2>
            <span class="text-xs text-gray-400">Últimos 20 registros</span>
        </div>

        @if($logs->isEmpty())
            <div class="px-6 py-12 text-center text-sm text-gray-400">
                Nenhum envio registrado ainda.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 text-xs text-gray-400 uppercase tracking-widest">
                            <th class="px-6 py-3 text-left font-bold">Data/Hora</th>
                            <th class="px-6 py-3 text-left font-bold">Status</th>
                            <th class="px-6 py-3 text-left font-bold">HTTP</th>
                            <th class="px-6 py-3 text-left font-bold">Tipo</th>
                            <th class="px-6 py-3 text-left font-bold">Resposta</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($logs as $log)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-3 text-gray-500 whitespace-nowrap font-mono text-xs">
                                    {{ $log->created_at->format('d/m/Y H:i:s') }}
                                </td>
                                <td class="px-6 py-3">
                                    @php
                                        $cor = match($log->status) {
                                            'sucesso' => 'bg-green-100 text-green-700',
                                            'falha'   => 'bg-yellow-100 text-yellow-700',
                                            default   => 'bg-red-100 text-red-700',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $cor }}">
                                        {{ ucfirst($log->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 font-mono text-xs text-gray-500">
                                    {{ $log->status_code ?? '—' }}
                                </td>
                                <td class="px-6 py-3">
                                    @if($log->is_teste)
                                        <span class="text-xs font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded-full">Teste</span>
                                    @else
                                        <span class="text-xs text-gray-400">Lead</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 max-w-xs">
                                    <span class="text-xs text-gray-500 truncate block max-w-xs" title="{{ $log->resposta }}">
                                        {{ $log->resposta ? mb_substr($log->resposta, 0, 80) . (mb_strlen($log->resposta) > 80 ? '…' : '') : '—' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>

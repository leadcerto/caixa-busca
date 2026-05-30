<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Console de Diagnóstico — Imóveis da Caixa</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-950 text-gray-100 min-h-screen p-8 font-sans">

<div class="max-w-7xl mx-auto space-y-8">

    {{-- Header --}}
    <div class="text-center space-y-1">
        <h1 class="text-3xl font-black tracking-tight bg-gradient-to-r from-sky-400 to-violet-500 bg-clip-text text-transparent">
            Console de Diagnóstico Premium
        </h1>
        <p class="text-gray-500 text-sm">Imóveis da Caixa — Production Diagnostics Panel</p>
    </div>

    {{-- Grid principal --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Coluna Esquerda --}}
        <div class="space-y-6">

            {{-- Infraestrutura --}}
            <div class="bg-gray-900 border border-white/5 rounded-2xl p-6 shadow-xl">
                <h3 class="text-base font-black text-sky-400 mb-5">Informações de Infraestrutura</h3>
                <dl class="grid grid-cols-2 gap-y-3 gap-x-4">
                    <dt class="text-xs font-semibold text-gray-400 self-center">Versão PHP</dt>
                    <dd class="font-mono text-sm text-gray-100">{{ phpversion() }}</dd>

                    <dt class="text-xs font-semibold text-gray-400 self-center">Ambiente Laravel</dt>
                    <dd class="font-mono text-sm text-gray-100">{{ app()->environment() }}</dd>

                    <dt class="text-xs font-semibold text-gray-400 self-center">Debug Mode</dt>
                    <dd class="text-sm">
                        @if(config('app.debug'))
                            <span class="inline-flex items-center gap-1 bg-red-500/15 text-red-400 border border-red-500/25 rounded-full px-3 py-1 text-xs font-semibold">Ativado</span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-emerald-500/15 text-emerald-400 border border-emerald-500/25 rounded-full px-3 py-1 text-xs font-semibold">Desativado</span>
                        @endif
                    </dd>

                    <dt class="text-xs font-semibold text-gray-400 self-center">Banco de Dados</dt>
                    <dd class="text-sm">
                        @if($dbError)
                            <span class="inline-flex items-center gap-1 bg-red-500/15 text-red-400 border border-red-500/25 rounded-full px-3 py-1 text-xs font-semibold">Erro de Conexão</span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-emerald-500/15 text-emerald-400 border border-emerald-500/25 rounded-full px-3 py-1 text-xs font-semibold">Conectado</span>
                        @endif
                    </dd>
                </dl>

                @if($dbError)
                    <div class="mt-4 bg-red-500/10 border border-red-500/20 rounded-xl p-4">
                        <p class="text-xs font-semibold text-red-400 mb-1">Detalhe do erro:</p>
                        <code class="text-xs text-red-300 break-all">{{ $dbError }}</code>
                    </div>
                @endif
            </div>

            {{-- Cron --}}
            <div class="bg-gray-900 border border-white/5 rounded-2xl p-6 shadow-xl">
                <h3 class="text-base font-black text-violet-400 mb-3">Status do Cron (Schedule)</h3>
                <div class="bg-violet-500/8 border border-violet-500/20 rounded-xl p-4 font-mono text-sm text-violet-300">
                    {{ $scheduleStatus }}
                    <p class="text-gray-600 text-xs mt-2">Atualiza a cada minuto quando <code>schedule:run</code> está ativo no Cron Job</p>
                </div>
            </div>

            {{-- Fila --}}
            <div class="bg-gray-900 border border-white/5 rounded-2xl p-6 shadow-xl">
                <h3 class="text-base font-black text-sky-400 mb-4">Status da Fila</h3>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-yellow-500/10 border border-yellow-500/25 rounded-xl p-4 text-center">
                        <p class="text-2xl font-black text-yellow-400">{{ $jobsCount }}</p>
                        <p class="text-xs text-gray-500 mt-1">Jobs aguardando</p>
                    </div>
                    <div class="bg-red-500/10 border border-red-500/25 rounded-xl p-4 text-center">
                        <p class="text-2xl font-black text-red-400">{{ $failedJobsCount }}</p>
                        <p class="text-xs text-gray-500 mt-1">Jobs falhados</p>
                    </div>
                </div>

                <h4 class="text-sm font-black text-yellow-400 mb-3">Ações de Manutenção</h4>
                <div class="flex flex-wrap gap-2">
                    <a href="?action=clear"        class="btn-diag bg-sky-500/15 text-sky-300 border-sky-500/30 hover:bg-sky-500/25">optimize:clear</a>
                    <a href="?action=migrate"       class="btn-diag bg-yellow-500/15 text-yellow-300 border-yellow-500/30 hover:bg-yellow-500/25">migrate --force</a>
                    <a href="?action=seed"          class="btn-diag bg-emerald-500/15 text-emerald-300 border-emerald-500/30 hover:bg-emerald-500/25">db:seed --force</a>
                    <a href="?action=storage_link"  class="btn-diag bg-teal-500/15 text-teal-300 border-teal-500/30 hover:bg-teal-500/25">storage:link --force</a>
                </div>
                <div class="flex flex-wrap gap-2 mt-2">
                    <a href="?action=queue" class="btn-diag bg-violet-500/20 text-violet-300 border-violet-500/40 hover:bg-violet-500/30">
                        Processar Fila (queue:work)
                    </a>
                    @if($failedJobsCount > 0)
                        <a href="?action=queue_retry" class="btn-diag bg-orange-500/15 text-orange-300 border-orange-500/30 hover:bg-orange-500/25">Retentar Jobs Falhados</a>
                        <a href="?action=queue_flush" class="btn-diag bg-red-500/15 text-red-300 border-red-500/30 hover:bg-red-500/25">Limpar Falhados</a>
                    @endif
                </div>
                <div class="flex flex-wrap gap-2 mt-2">
                    <a href="?action=bairros_preview"  class="btn-diag bg-sky-500/15 text-sky-300 border-sky-500/30 hover:bg-sky-500/25">Prévia Limpeza Bairros</a>
                    <a href="?action=bairros_executar"
                       onclick="return confirm('Confirma a limpeza de bairros? Esta ação é irreversível.')"
                       class="btn-diag bg-red-500/15 text-red-300 border-red-500/30 hover:bg-red-500/25">Executar Limpeza Bairros</a>
                </div>
                <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-white/5">
                    <span class="text-xs font-semibold text-gray-500 self-center w-full mb-1">SEO — Google</span>
                    <a href="?action=ping_google" class="btn-diag bg-blue-500/20 text-blue-300 border-blue-500/40 hover:bg-blue-500/30">Pingar Google (Sitemap)</a>
                </div>
                <div class="flex flex-wrap gap-2 mt-2 pt-2 border-t border-white/5">
                    <span class="text-xs font-semibold text-gray-500 self-center w-full mb-1">Diagnóstico OpenRouter</span>
                    <a href="?action=check_openrouter" class="btn-diag bg-green-500/20 text-green-300 border-green-500/40 hover:bg-green-500/30">Verificar Chave</a>
                    <a href="?action=show_env" class="btn-diag bg-sky-500/15 text-sky-300 border-sky-500/30 hover:bg-sky-500/25">Ler .env (OPENROUTER)</a>
                </div>
                {{-- Formulário para gravar chave diretamente --}}
                <form method="POST" action="?action=write_openrouter_key" class="mt-3 space-y-2 p-4 bg-orange-500/5 border border-orange-500/20 rounded-xl">
                    @csrf
                    <p class="text-xs font-semibold text-orange-300">Gravar Chave OpenRouter no .env</p>
                    <input type="text" name="openrouter_key" placeholder="Cole a chave sk-or-v1-..." required
                           class="w-full bg-gray-950 border border-white/10 rounded-lg px-3 py-2 text-xs text-gray-200 font-mono focus:outline-none focus:border-orange-400">
                    <input type="text" name="openrouter_model" value="google/gemma-4-31b-it:free"
                           class="w-full bg-gray-950 border border-white/10 rounded-lg px-3 py-2 text-xs text-gray-200 font-mono focus:outline-none focus:border-orange-400">
                    <button type="submit" class="btn-diag bg-orange-500/20 text-orange-300 border-orange-500/40 hover:bg-orange-500/30 w-full text-center">
                        Gravar no .env + optimize:clear
                    </button>
                </form>
            </div>

        </div>

        {{-- Coluna Direita --}}
        <div class="space-y-6">

            {{-- Console Output --}}
            <div class="bg-gray-900 border border-white/5 rounded-2xl p-6 shadow-xl flex flex-col h-full min-h-[320px]">
                <h3 class="text-base font-black text-violet-400 mb-4">Console Output</h3>
                @if($actionOutput)
                    <div class="flex-1 bg-gray-950 border border-white/8 rounded-xl p-5 font-mono text-xs text-emerald-300 overflow-y-auto whitespace-pre-wrap leading-relaxed" style="max-height:540px">{{ $actionOutput }}</div>
                @else
                    <div class="flex-1 bg-gray-950 border border-white/8 rounded-xl p-5 font-mono text-xs text-gray-600 flex items-center justify-center">
                        Pronto. Selecione uma ação para ver a saída do console do Laravel…
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Erros Filtrados --}}
    <div class="bg-gray-900 border border-red-500/20 rounded-2xl p-6 shadow-xl">
        <h3 class="text-base font-black text-red-400 mb-4">⚠️ Últimos Erros (filtrado)</h3>
        <div class="bg-gray-950 border border-white/8 rounded-xl p-5 font-mono text-xs text-red-300 overflow-y-auto whitespace-pre-wrap leading-relaxed" style="max-height:300px">{{ $errorLines }}</div>
    </div>

    {{-- Logs --}}
    <div class="bg-gray-900 border border-white/5 rounded-2xl p-6 shadow-xl">
        <h3 class="text-base font-black text-red-400 mb-4">Histórico de Logs do Laravel (últimas 300 linhas)</h3>
        <div class="bg-gray-950 border border-white/8 rounded-xl p-5 font-mono text-xs text-slate-400 overflow-y-auto whitespace-pre-wrap leading-relaxed" style="max-height:450px">{{ $logContent }}</div>
    </div>

</div>

<style>
.btn-diag {
    display: inline-block;
    padding: 8px 14px;
    border-radius: 10px;
    border-width: 1px;
    border-style: solid;
    font-size: 0.75rem;
    font-weight: 600;
    transition: background 0.2s, transform 0.15s;
    text-decoration: none;
}
.btn-diag:hover { transform: translateY(-1px); }
</style>

</body>
</html>

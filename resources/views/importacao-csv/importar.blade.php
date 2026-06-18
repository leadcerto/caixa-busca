@component('layouts.admin', ['title' => 'Importar CSV'])

<div class="max-w-4xl mx-auto py-12 px-6"
     x-data="importacaoPanel()"
     x-init="init()">

    <div class="mb-10 text-center md:text-left border-b border-gray-100 pb-6">
        <h1 class="text-3xl font-extrabold text-[#005CA9] tracking-tight">Central de Importação</h1>
        <p class="text-gray-500 mt-2 text-lg">Atualize a base de imóveis utilizando o arquivo CSV oficial fornecido pela CAIXA.</p>
    </div>

    @if (session('importMessage'))
        @php [$type, $text] = explode('|', session('importMessage'), 2); @endphp
        <div class="mb-8 p-5 rounded-xl border flex items-start space-x-4 {{ $type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800' }}">
            <div class="flex-shrink-0 mt-0.5">
                @if ($type === 'success')
                    <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                @else
                    <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                @endif
            </div>
            <div class="flex-1">
                <p class="font-bold">{{ $type === 'success' ? 'Sucesso!' : 'Ocorreu um Problema' }}</p>
                <p class="text-sm opacity-90">{{ $text }}</p>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-8 p-5 rounded-xl border bg-red-50 border-red-200 text-red-800">
            <p class="font-bold mb-2">Erro no arquivo:</p>
            @foreach ($errors->all() as $error)
                <p class="text-sm">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- Painel de Progresso --}}
    <div x-show="status !== 'idle'" x-cloak class="mb-6 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="p-6">

            {{-- Processando --}}
            <template x-if="status === 'processing'">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-2">
                            <svg class="animate-spin h-5 w-5 text-[#005CA9]" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="font-bold text-[#005CA9]">Importando...</span>
                        </div>
                        <span class="text-2xl font-extrabold text-[#005CA9]" x-text="porcentagem + '%'"></span>
                    </div>

                    <div class="w-full bg-gray-100 rounded-full h-4 overflow-hidden">
                        <div class="h-4 rounded-full transition-all duration-500 bg-gradient-to-r from-[#005CA9] to-blue-400"
                             :style="'width: ' + porcentagem + '%'"></div>
                    </div>

                    <div class="mt-4 grid grid-cols-3 gap-4 text-center text-sm">
                        <div class="bg-blue-50 rounded-xl p-3">
                            <p class="text-gray-500 text-xs mb-1">Linhas processadas</p>
                            <p class="font-bold text-[#005CA9]" x-text="progresso.processed ?? 0"></p>
                        </div>
                        <div class="bg-green-50 rounded-xl p-3">
                            <p class="text-gray-500 text-xs mb-1">Imóveis atualizados</p>
                            <p class="font-bold text-green-700" x-text="progresso.inserted ?? 0"></p>
                        </div>
                        <div class="bg-orange-50 rounded-xl p-3">
                            <p class="text-gray-500 text-xs mb-1">Erros / Ignorados</p>
                            <p class="font-bold text-orange-600" x-text="progresso.skipped ?? 0"></p>
                        </div>
                    </div>

                    <p class="mt-3 text-xs text-gray-400 text-center" x-text="'Arquivo: ' + (progresso.file ?? '')"></p>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.importar.reset') }}" class="text-xs text-red-400 underline hover:text-red-600">Cancelar / Resetar status</a>
                    </div>
                </div>
            </template>

            {{-- Concluído --}}
            <template x-if="status === 'completed'">
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <p class="font-extrabold text-green-700 text-lg">Importação Concluída!</p>
                    <p class="text-gray-500 text-sm mt-1" x-text="'Arquivo: ' + (progresso.file ?? '')"></p>

                    <div class="mt-4 grid grid-cols-3 gap-4 text-center text-sm">
                        <div class="bg-blue-50 rounded-xl p-3">
                            <p class="text-gray-500 text-xs mb-1">Total de linhas</p>
                            <p class="font-bold text-[#005CA9]" x-text="progresso.total ?? 0"></p>
                        </div>
                        <div class="bg-green-50 rounded-xl p-3">
                            <p class="text-gray-500 text-xs mb-1">Imóveis atualizados</p>
                            <p class="font-bold text-green-700" x-text="progresso.inserted ?? 0"></p>
                        </div>
                        <div class="bg-orange-50 rounded-xl p-3">
                            <p class="text-gray-500 text-xs mb-1">Erros / Ignorados</p>
                            <p class="font-bold text-orange-600" x-text="progresso.skipped ?? 0"></p>
                        </div>
                    </div>

                    <button @click="status = 'idle'" class="mt-5 text-sm text-gray-400 underline hover:text-gray-600">
                        Fechar
                    </button>
                </div>
            </template>

            {{-- Erro --}}
            <template x-if="status === 'error'">
                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <p class="font-extrabold text-red-700 text-lg">Erro na Importação</p>
                    <p class="text-red-500 text-sm mt-2" x-text="progresso.error ?? 'Erro desconhecido'"></p>
                    <button @click="status = 'idle'" class="mt-5 text-sm text-gray-400 underline hover:text-gray-600">
                        Fechar
                    </button>
                </div>
            </template>

        </div>
    </div>

    {{-- Formulário de Upload --}}
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

        <form action="{{ route('admin.importar.store') }}"
              method="POST"
              enctype="multipart/form-data"
              x-data="{ fileName: null, submitting: false }"
              @submit="submitting = true"
              class="p-10">
            @csrf

            <div class="relative group" :class="isImporting ? 'opacity-50 pointer-events-none' : ''">
                <div class="border-2 border-dashed border-gray-200 rounded-2xl p-12 text-center transition-all duration-300 group-hover:border-[#005CA9] group-hover:bg-blue-50/30">

                    <input type="file"
                           name="csvFile"
                           id="csv_file"
                           accept=".csv,.txt"
                           @change="fileName = $event.target.files[0]?.name ?? null"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                    <div class="flex flex-col items-center">
                        <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mb-6 text-[#005CA9] group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>

                        <template x-if="fileName">
                            <div>
                                <span class="text-green-600 font-bold text-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                    <span x-text="fileName"></span>
                                </span>
                                <p class="text-gray-400 text-sm mt-1">Pronto para ser processado</p>
                            </div>
                        </template>
                        <template x-if="!fileName">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-700" x-text="isImporting ? 'Aguarde — importação em andamento...' : 'Arraste seu arquivo CSV aqui'"></h3>
                                <p class="text-gray-400 mt-2" x-text="isImporting ? 'Você poderá enviar o próximo arquivo após o término.' : 'ou clique para procurar no seu computador'"></p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="mt-10">
                <button type="submit"
                        :disabled="submitting || !fileName || isImporting"
                        :class="(submitting || !fileName || isImporting) ? 'opacity-50 cursor-not-allowed' : 'hover:bg-[#E08600] active:scale-95'"
                        class="w-full bg-[#F39200] text-white text-lg font-bold py-5 rounded-2xl shadow-xl shadow-orange-200 transition-all duration-300 flex items-center justify-center space-x-3">
                    <template x-if="submitting">
                        <svg class="animate-spin h-6 w-6 text-white mr-3" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </template>
                    <span x-text="isImporting ? 'Aguardando conclusão...' : (submitting ? 'Enviando arquivo...' : 'Iniciar Importação')"></span>
                </button>
            </div>
        </form>

        <div class="bg-gray-50 p-8 border-t border-gray-100">
            <h4 class="text-[#005CA9] font-bold text-sm uppercase tracking-widest flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Instruções de Segurança
            </h4>
            <div class="grid md:grid-cols-2 gap-6 mt-4">
                <div class="text-sm text-gray-500 leading-relaxed">
                    <p><strong>1. Formato:</strong> O sistema aceita apenas arquivos .csv codificados em UTF-8 ou ISO-8859-1 (Padrão Caixa).</p>
                    <p class="mt-2"><strong>2. Pular Linhas:</strong> O motor ignora automaticamente as 3 primeiras linhas (metadados e separadores).</p>
                </div>
                <div class="text-sm text-gray-500 leading-relaxed">
                    <p><strong>3. Background:</strong> A importação rodará em segundo plano. O progresso aparece ao vivo acima.</p>
                    <p class="mt-2"><strong>4. Deduplicação:</strong> Imóveis já existentes terão seus dados e preços atualizados automaticamente.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function importacaoPanel() {
    return {
        status: 'idle',
        progresso: {},
        porcentagem: 0,
        pollingInterval: null,

        get isImporting() {
            return this.status === 'processing';
        },

        init() {
            this.poll();
            this.pollingInterval = setInterval(() => this.poll(), 2000);
        },

        async poll() {
            try {
                const res = await fetch('{{ route('admin.importar.status') }}');
                const data = await res.json();
                this.progresso = data;
                this.status = data.status ?? 'idle';

                if (this.status === 'processing' && data.total > 0) {
                    this.porcentagem = Math.min(99, Math.round((data.processed / data.total) * 100));
                } else if (this.status === 'completed') {
                    this.porcentagem = 100;
                    clearInterval(this.pollingInterval);
                    setTimeout(() => this.restartPolling(), 8000);
                } else if (this.status === 'error') {
                    clearInterval(this.pollingInterval);
                    setTimeout(() => this.restartPolling(), 10000);
                }
            } catch (e) {
                // silencia erros de rede temporários
            }
        },

        restartPolling() {
            this.pollingInterval = setInterval(() => this.poll(), 2000);
        }
    }
}
</script>

@endcomponent

<!-- Componente de Importação de CSV - VLPHP -->
<!-- Regra: Design focado em Epicentro (Upload) e Três Estados (Vazio, Loading, Erro) -->

<div class="max-w-4xl mx-auto py-12 px-6">
    
    <!-- Cabeçalho da Página -->
    <div class="mb-10 text-center md:text-left border-b border-gray-100 pb-6">
        <h1 class="text-3xl font-extrabold text-[#005CA9] tracking-tight">
            Central de Importação
        </h1>
        <p class="text-gray-500 mt-2 text-lg">Atualize a base de imóveis utilizando o arquivo CSV oficial fornecido pela CAIXA.</p>
    </div>

    <!-- Alertas de Feedback (Estado de Sucesso ou Erro Crítico) -->
    @if ($message)
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 10000)" 
             class="mb-8 p-5 rounded-xl border flex items-start space-x-4 transition-all duration-500 {{ $messageType === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800' }}">
            
            <div class="flex-shrink-0 mt-0.5">
                @if ($messageType === 'success')
                    <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                @else
                    <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                @endif
            </div>
            
            <div class="flex-1">
                <p class="font-bold">{{ $messageType === 'success' ? 'Sucesso!' : 'Ocorreu um Problema' }}</p>
                <p class="text-sm opacity-90">{{ $message }}</p>
            </div>

            <button @click="show = false" class="flex-shrink-0 text-current hover:opacity-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif

    <!-- Container Principal do Formulário -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        
        <form wire:submit.prevent="save" class="p-10">
            
            <!-- ESTADO 1: Vazio/Regular (Área de Dropzone) -->
            <div class="relative group">
                <div class="border-2 border-dashed border-gray-200 rounded-2xl p-12 text-center transition-all duration-300 group-hover:border-[#005CA9] group-hover:bg-blue-50/30">
                    
                    <!-- Input de Arquivo Escondido (Overlay) -->
                    <input type="file" wire:model="csvFile" id="csv_file" accept=".csv,.txt" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                    <!-- Conteúdo Visual do Dropzone -->
                    <div class="flex flex-col items-center">
                        <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mb-6 text-[#005CA9] group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>

                        @if ($csvFile)
                            <!-- Estado com Arquivo Selecionado -->
                            <div class="animate-bounce-in">
                                <span class="text-green-600 font-bold text-lg flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                    {{ $csvFile->getClientOriginalName() }}
                                </span>
                                <p class="text-gray-400 text-sm mt-1">Pronto para ser processado</p>
                            </div>
                        @else
                            <h3 class="text-xl font-semibold text-gray-700">Arraste seu arquivo CSV aqui</h3>
                            <p class="text-gray-400 mt-2">ou clique para procurar no seu computador</p>
                        @endif
                    </div>
                </div>

                <!-- ESTADO 3: Erro de Validação -->
                @error('csvFile')
                    <div class="mt-4 flex items-center text-red-600 text-sm font-semibold animate-shake">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Botão de Submissão (Laranja Caixa) -->
            <!-- ESTADO 2: Carregamento (Loading) -->
            <div class="mt-10">
                <button type="submit" 
                        wire:loading.attr="disabled"
                        wire:target="save"
                        class="w-full bg-[#F39200] hover:bg-[#E08600] active:transform active:scale-95 text-white text-lg font-bold py-5 rounded-2xl shadow-xl shadow-orange-200 transition-all duration-300 flex items-center justify-center space-x-3 disabled:opacity-50 disabled:cursor-wait">
                    
                    <!-- Spinner de Loading -->
                    <svg wire:loading wire:target="save" class="animate-spin h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>

                    <span wire:loading.remove wire:target="save">Iniciar Importação</span>
                    <span wire:loading wire:target="save">Preparando Dados...</span>
                </button>
            </div>
        </form>

        <!-- Rodapé com Instruções -->
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
                    <p><strong>3. Background:</strong> A importação rodará em segundo plano. Você receberá uma notificação ao finalizar.</p>
                    <p class="mt-2"><strong>4. Deduplicação:</strong> Imóveis já existentes terão seus dados e preços atualizados automaticamente.</p>
                </div>
            </div>
        </div>
    </div>
</div>

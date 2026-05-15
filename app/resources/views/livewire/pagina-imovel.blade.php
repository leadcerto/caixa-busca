<div>
    <!-- Seção de Destaque do Imóvel -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        @if($state === 'loading')
            <!-- Estado Vazio (The Blank Slate / Loading) -->
            <div class="flex flex-col items-center justify-center py-20 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                <svg class="animate-spin h-12 w-12 text-blue-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <h2 class="text-xl font-semibold text-gray-700">Carregando informações do imóvel...</h2>
                <p class="text-gray-500">Estamos preparando os melhores dados para você.</p>
            </div>
        @elseif($state === 'error')
            <!-- Estado de Erro -->
            <div class="flex flex-col items-center justify-center py-20 bg-red-50 rounded-xl border-2 border-red-100">
                <div class="bg-red-100 p-4 rounded-full mb-4">
                    <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-red-800">Ops! Algo deu errado</h2>
                <p class="text-red-600 mb-6">Não conseguimos localizar este imóvel em nossa base.</p>
                <button wire:click="retry" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">Tentar Novamente</button>
            </div>
        @else
            <!-- Estado Regular (Dados do Imóvel) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Coluna da Esquerda: Imagem e Destaques -->
                <div class="space-y-6">
                    <div class="relative aspect-video bg-gray-200 rounded-2xl overflow-hidden shadow-lg">
                        <!-- Placeholder Real da Caixa -->
                        <img src="https://venda-imoveis.caixa.gov.br/fotos/F8544490001002_1.jpg" alt="Fachada do Imóvel" class="w-full h-full object-cover">
                        <div class="absolute top-4 left-4 bg-blue-600 text-white px-4 py-1 rounded-full text-sm font-bold shadow-md">
                            {{ $imovel['tipo'] ?? 'Casa' }}
                        </div>
                    </div>
                    
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <h1 class="text-3xl font-extrabold text-gray-900 mb-2">{{ $imovel['bairro'] }}, {{ $imovel['cidade'] }}</h1>
                        <p class="text-lg text-gray-600 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $imovel['endereco'] }}
                        </p>
                        
                        <div class="grid grid-cols-3 gap-4 mt-8">
                            <div class="text-center p-4 bg-gray-50 rounded-xl">
                                <span class="block text-2xl font-bold text-gray-800">{{ $imovel['quartos'] }}</span>
                                <span class="text-xs uppercase tracking-wider text-gray-500">Quartos</span>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-xl">
                                <span class="block text-2xl font-bold text-gray-800">{{ $imovel['vagas'] }}</span>
                                <span class="text-xs uppercase tracking-wider text-gray-500">Vagas</span>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-xl">
                                <span class="block text-2xl font-bold text-gray-800">{{ $imovel['area'] }}m²</span>
                                <span class="text-xs uppercase tracking-wider text-gray-500">Área Útil</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Coluna da Direita: Preço, Lead e Sanfonas -->
                <div class="space-y-6">
                    <div class="bg-blue-600 p-8 rounded-2xl text-white shadow-xl">
                        <span class="text-blue-100 text-sm uppercase tracking-widest font-semibold">Valor de Venda</span>
                        <div class="flex items-baseline mt-2">
                            <span class="text-2xl font-light mr-2">R$</span>
                            <span class="text-5xl font-black">{{ number_format($imovel['preco'], 2, ',', '.') }}</span>
                        </div>
                        <p class="mt-4 text-blue-100 text-sm italic">Oportunidade de leilão com até 40% de desconto.</p>
                        
                        <button class="w-full mt-8 bg-white text-blue-600 font-bold py-4 rounded-xl shadow-lg hover:bg-blue-50 transition transform hover:-translate-y-1">
                            Falar com um Consultor Especialista
                        </button>
                    </div>

                    <!-- Início da Sanfona de Detalhes -->
                    <div x-data="{ open: true }" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <button @click="open = !open" class="w-full flex items-center justify-between p-6 text-left hover:bg-gray-50 transition">
                            <span class="text-lg font-bold text-gray-900">Descrição Detalhada</span>
                            <svg :class="{'rotate-180': open}" class="h-6 w-6 text-gray-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="p-6 pt-0 border-t border-gray-50">
                            <p class="text-gray-600 leading-relaxed">
                                {{ $imovel['descricao_caixa'] }}
                            </p>
                        </div>
                    </div>

                    <!-- Início da Sanfona de Dossiê do Bairro -->
                    <div x-data="{ open: false }" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <button @click="open = !open" class="w-full flex items-center justify-between p-6 text-left hover:bg-gray-50 transition">
                            <div class="flex items-center">
                                <span class="text-lg font-bold text-gray-900">Dossiê do Bairro: {{ $imovel['bairro'] }}</span>
                                <span class="ml-3 px-2 py-0.5 bg-green-100 text-green-700 text-xs font-bold rounded">IA Insights</span>
                            </div>
                            <svg :class="{'rotate-180': open}" class="h-6 w-6 text-gray-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="p-6 pt-0 border-t border-gray-50">
                            <div class="space-y-4 py-4">
                                <div class="flex items-start">
                                    <div class="p-2 bg-blue-50 rounded-lg mr-4">
                                        <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04c0 4.833 1.89 9.215 5 12.422A11.954 11.954 0 0112 21.235a11.954 11.954 0 013.618-2.829c3.11-3.207 5-7.589 5-12.422z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900">Segurança</h4>
                                        <p class="text-sm text-gray-600">Região monitorada 24h com baixo índice de ocorrências. Ideal para famílias.</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="p-2 bg-yellow-50 rounded-lg mr-4">
                                        <svg class="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900">Comodidades</h4>
                                        <p class="text-sm text-gray-600">A menos de 500m de supermercados, farmácias e escolas de alto padrão.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endif

    </div>
</div>

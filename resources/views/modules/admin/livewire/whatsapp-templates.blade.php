<div class="max-w-7xl mx-auto px-6 py-10 space-y-8">

    {{-- Cabeçalho --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-900">Templates WhatsApp</h1>
            <p class="text-sm text-gray-500 mt-1">Configure a mensagem enviada ao lead no momento da conversão.</p>
        </div>
        @if($modo === 'lista')
            <button wire:click="novo"
                    class="flex items-center gap-2 bg-[#005CA9] hover:bg-blue-800 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Novo template
            </button>
        @endif
    </div>

    {{-- ===== FORMULÁRIO ===== --}}
    @if($modo === 'form')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            {{-- Form --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
                <h2 class="text-base font-bold text-gray-800">
                    {{ $templateId ? 'Editar template' : 'Novo template' }}
                </h2>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Nome do template</label>
                    <input wire:model.live="nome"
                           type="text"
                           placeholder="Ex: Padrão — Caixa Econômica"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nome') border-red-400 @enderror">
                    @error('nome') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Mensagem</label>
                    <textarea wire:model.live="mensagem"
                              rows="6"
                              placeholder="Olá! Meu nome é {nome}. Tenho interesse no {tipo_imovel} (Cód: {codigo}) em {localidade}. Pode me ajudar?"
                              class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none @error('mensagem') border-red-400 @enderror"></textarea>
                    @error('mensagem') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror

                    {{-- Variáveis disponíveis --}}
                    <div class="mt-2 flex flex-wrap gap-1.5">
                        @foreach(['{nome}', '{tipo_imovel}', '{codigo}', '{localidade}', '{municipio}', '{uf}'] as $var)
                            <button type="button"
                                    wire:click="$set('mensagem', $wire.mensagem + '{{ $var }}')"
                                    class="text-xs bg-gray-100 hover:bg-blue-50 hover:text-blue-700 text-gray-500 font-mono px-2 py-0.5 rounded transition-colors">
                                {{ $var }}
                            </button>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Clique nas variáveis para inserir na mensagem.</p>
                </div>

                <label class="flex items-center gap-3 cursor-pointer select-none">
                    <div class="relative">
                        <input wire:model.live="ativo" type="checkbox" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-green-500 transition-colors"></div>
                        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Usar como template ativo</span>
                </label>

                <div class="flex gap-3 pt-2">
                    <button wire:click="salvar"
                            class="flex-1 bg-[#005CA9] hover:bg-blue-800 text-white text-sm font-bold py-2.5 rounded-xl transition-colors">
                        Salvar
                    </button>
                    <button wire:click="cancelar"
                            class="px-5 py-2.5 border border-gray-200 hover:border-gray-300 rounded-xl text-sm font-bold text-gray-600 transition-colors">
                        Cancelar
                    </button>
                </div>
            </div>

            {{-- Preview --}}
            <div class="space-y-5">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                    <h2 class="text-base font-bold text-gray-800">Preview da mensagem</h2>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Nome do lead</label>
                            <input wire:model.live="previewNome" type="text" class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-xs">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Tipo de imóvel</label>
                            <input wire:model.live="previewTipo" type="text" class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-xs">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Código</label>
                            <input wire:model.live="previewCodigo" type="text" class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-xs">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Localidade</label>
                            <input wire:model.live="previewLocalidade" type="text" class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-xs">
                        </div>
                    </div>

                    {{-- Balão WhatsApp --}}
                    <div class="bg-[#ECE5DD] rounded-2xl p-4 min-h-[80px]">
                        <div class="bg-white rounded-2xl rounded-tl-none px-4 py-3 shadow-sm max-w-xs">
                            <p class="text-sm text-gray-800 leading-relaxed whitespace-pre-wrap">{{ $this->preview ?: 'Digite a mensagem para ver o preview...' }}</p>
                            <p class="text-right text-xs text-gray-400 mt-1">{{ now()->format('H:i') }}</p>
                        </div>
                    </div>

                    <p class="text-xs text-gray-400 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-[#25D366] shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        Simulação — a mensagem real usa os dados do imóvel e do lead.
                    </p>
                </div>
            </div>
        </div>

    {{-- ===== LISTA ===== --}}
    @else
        @if($templates->isEmpty())
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-16 text-center">
                <svg class="w-12 h-12 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <p class="text-gray-400 text-sm">Nenhum template criado ainda.</p>
                <button wire:click="novo"
                        class="mt-4 text-sm text-[#005CA9] font-bold hover:underline">
                    Criar primeiro template
                </button>
            </div>
        @else
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="divide-y divide-gray-50">
                    @foreach($templates as $template)
                        <div class="px-6 py-5 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="font-bold text-gray-800">{{ $template->nome }}</span>
                                        @if($template->ativo)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                                Ativo
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-500 font-mono line-clamp-2">{{ $template->mensagem }}</p>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    @if(!$template->ativo)
                                        <button wire:click="ativar({{ $template->id }})"
                                                class="text-xs font-bold text-green-600 hover:text-green-800 px-3 py-1.5 border border-green-200 hover:border-green-300 rounded-xl transition-colors">
                                            Ativar
                                        </button>
                                    @endif
                                    <button wire:click="editar({{ $template->id }})"
                                            class="text-xs font-bold text-gray-500 hover:text-gray-800 px-3 py-1.5 border border-gray-200 hover:border-gray-300 rounded-xl transition-colors">
                                        Editar
                                    </button>
                                    <button wire:click="excluir({{ $template->id }})"
                                            wire:confirm="Excluir o template '{{ addslashes($template->nome) }}'?"
                                            class="text-xs font-bold text-red-400 hover:text-red-600 px-3 py-1.5 border border-red-100 hover:border-red-200 rounded-xl transition-colors">
                                        Excluir
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Aviso: nenhum ativo --}}
        @if($templates->isNotEmpty() && $templates->where('ativo', true)->isEmpty())
            <div class="flex items-center gap-3 bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-3 text-sm text-yellow-800">
                <svg class="w-5 h-5 shrink-0 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                Nenhum template está ativo. O sistema usará a mensagem padrão do código.
            </div>
        @endif
    @endif

</div>

<div class="p-6 md:p-10 space-y-8">
    
    <!-- Top Header Bar -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900">Gestão de Imobiliárias</h1>
            <p class="text-sm font-medium text-slate-500 mt-1">Gerencie os parceiros credenciados, credenciais de login e os botões flutuantes de WhatsApp de cada um.</p>
        </div>
        <div>
            <button wire:click="abrirModalCriar" 
                    class="bg-[#F39200] hover:bg-[#E08600] text-slate-950 font-black px-6 py-3 rounded-2xl shadow-xl shadow-orange-500/10 flex items-center gap-2.5 transition-all duration-300 active:scale-95 text-sm uppercase tracking-wider shrink-0">
                <svg class="w-5 h-5 text-slate-950" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                <span>Cadastrar Imobiliária</span>
            </button>
        </div>
    </div>

    <!-- Main Content Card Container -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-6 md:p-8 space-y-6">
        
        <!-- Search & Quick Filters Bar -->
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Buscar por nome, CNPJ, e-mail, WhatsApp, creci..."
                       class="w-full bg-slate-50 border border-slate-200 rounded-2xl h-12 pl-12 pr-4 text-sm text-slate-800 focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition-all placeholder:text-slate-400">
            </div>
        </div>

        <!-- Table Listing -->
        <div class="overflow-x-auto rounded-3xl border border-slate-100 bg-white">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[10px] font-black uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <th class="px-6 py-4.5">Parceiro</th>
                        <th class="px-6 py-4.5">Contato & CRECI</th>
                        <th class="px-6 py-4.5">Estados Atendidos</th>
                        <th class="px-6 py-4.5 text-center">Botão de WhatsApp</th>
                        <th class="px-6 py-4.5 text-center">Status</th>
                        <th class="px-6 py-4.5 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($imobiliarias as $imob)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4.5">
                                <div class="flex flex-col">
                                    <span class="font-extrabold text-slate-900 text-base leading-snug">{{ $imob->nome }}</span>
                                    <span class="text-xs text-slate-400 mt-0.5">CNPJ: {{ $imob->cnpj ?: 'Não informado' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4.5">
                                <div class="flex flex-col text-slate-650">
                                    <span class="font-medium text-slate-800">{{ $imob->email }}</span>
                                    <span class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                        🟢 {{ $imob->whatsapp }}
                                    </span>
                                    @if($imob->creci)
                                        <span class="text-[10px] font-bold text-slate-400 uppercase mt-0.5">{{ $imob->creci }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4.5">
                                <div class="flex flex-wrap gap-1.5 max-w-xs">
                                    @forelse($imob->estados as $est)
                                        <span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded-md border border-blue-100 uppercase">
                                            {{ $est->uf }}
                                        </span>
                                    @empty
                                        <span class="text-slate-400 italic text-xs">Nenhum estado</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4.5 text-center">
                                @if($imob->imagem_botao)
                                    <div class="inline-block relative p-1 bg-slate-50 rounded-xl border border-slate-100 shadow-sm max-w-[120px] max-h-[50px] overflow-hidden">
                                        <img src="{{ asset('storage/' . $imob->imagem_botao) }}" 
                                             alt="Botão" 
                                             class="max-w-full max-h-[40px] object-contain">
                                    </div>
                                @else
                                    <span class="text-slate-400 italic text-xs">Sem imagem (Usa Central)</span>
                                @endif
                            </td>
                            <td class="px-6 py-4.5 text-center">
                                <button wire:click="toggleAtivo({{ $imob->id }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase transition-all duration-300 {{ $imob->ativo ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $imob->ativo ? 'bg-emerald-600' : 'bg-slate-400' }}"></span>
                                    <span>{{ $imob->ativo ? 'Ativo' : 'Inativo' }}</span>
                                </button>
                            </td>
                            <td class="px-6 py-4.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="abrirModalEditar({{ $imob->id }})"
                                            class="p-2 rounded-xl text-blue-650 hover:bg-blue-50 hover:text-blue-700 transition-colors"
                                            title="Editar">
                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                        </svg>
                                    </button>
                                    <button onclick="confirm('Tem certeza que deseja remover esta imobiliária e todos os seus vínculos?') || event.stopImmediatePropagation()"
                                            wire:click="deletar({{ $imob->id }})"
                                            class="p-2 rounded-xl text-red-650 hover:bg-red-50 hover:text-red-700 transition-colors"
                                            title="Excluir">
                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">
                                Nenhuma imobiliária parceira encontrada. Cadastre uma para começar a gerenciar os leads!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pt-4 border-t border-slate-100">
            {{ $imobiliarias->links() }}
        </div>
    </div>

    <!-- popup modal dialog panel for add/edit partners -->
    @if($modalAberto)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm animate-fade-in">
            <div class="bg-white rounded-[2.5rem] w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl border border-slate-100 flex flex-col scale-100 transition-transform duration-300">
                
                <!-- Modal Header -->
                <div class="p-6 md:p-8 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-2xl font-black text-slate-900">
                        {{ $isEditMode ? 'Editar Imobiliária Parceira' : 'Cadastrar Imobiliária Parceira' }}
                    </h3>
                    <button wire:click="fecharModal" class="p-2 rounded-xl text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <form wire:submit.prevent="salvar" class="p-6 md:p-8 space-y-6 flex-1">
                    
                    <!-- Alert Warning about Image Measurements -->
                    <div class="bg-blue-50 border border-blue-100 p-5 rounded-2xl space-y-2 text-xs text-blue-800 leading-relaxed shadow-sm">
                        <p class="font-extrabold text-sm flex items-center gap-1.5">
                            📐 DIRETRIZES DE IMAGEM PARA O BOTÃO FLUTUANTE:
                        </p>
                        <p>Para garantir a perfeita exibição em computadores, celulares e outros dispositivos, utilizamos um único formato padronizado:</p>
                        <ul class="list-disc pl-5 mt-1 space-y-1 font-medium">
                            <li><strong>Formato Retangular/Pílula:</strong> Largura de <strong>180px</strong> por Altura de <strong>60px</strong> (Proporção 3:1). Ideal para conter a logo + texto explicativo de WhatsApp.</li>
                            <li><strong>Formato do Arquivo:</strong> Deve ser exclusivamente em formato <strong>PNG com fundo transparente</strong>.</li>
                        </ul>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        
                        <!-- Nome -->
                        <div>
                            <label class="block text-slate-700 font-black text-[10px] uppercase tracking-wider mb-1.5 pl-1">Nome da Imobiliária *</label>
                            <input type="text" wire:model="nome" placeholder="Ex: Imobiliária Certo RJ"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-2xl h-12 px-4 text-sm text-slate-800 focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition-all placeholder:text-slate-400">
                            @error('nome')
                                <span class="text-red-650 text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- CNPJ -->
                        <div>
                            <label class="block text-slate-700 font-black text-[10px] uppercase tracking-wider mb-1.5 pl-1">CNPJ da Imobiliária</label>
                            <input type="text" wire:model="cnpj" placeholder="Ex: 50.563.863/0001-45"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-2xl h-12 px-4 text-sm text-slate-800 focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition-all placeholder:text-slate-400">
                            @error('cnpj')
                                <span class="text-red-650 text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- E-mail -->
                        <div>
                            <label class="block text-slate-700 font-black text-[10px] uppercase tracking-wider mb-1.5 pl-1">E-mail de Acesso (Login) *</label>
                            <input type="email" wire:model="email" placeholder="Ex: parceiro@imoveis.com"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-2xl h-12 px-4 text-sm text-slate-800 focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition-all placeholder:text-slate-400">
                            @error('email')
                                <span class="text-red-650 text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- WhatsApp -->
                        <div>
                            <label class="block text-slate-700 font-black text-[10px] uppercase tracking-wider mb-1.5 pl-1">WhatsApp com DDD (Somente Números) *</label>
                            <input type="text" wire:model="whatsapp" placeholder="Ex: 5521997882950"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-2xl h-12 px-4 text-sm text-slate-800 focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition-all placeholder:text-slate-400">
                            @error('whatsapp')
                                <span class="text-red-650 text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- CRECI -->
                        <div>
                            <label class="block text-slate-700 font-black text-[10px] uppercase tracking-wider mb-1.5 pl-1">CRECI da Imobiliária</label>
                            <input type="text" wire:model="creci" placeholder="Ex: CRECI-10.234/RJ"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-2xl h-12 px-4 text-sm text-slate-800 focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition-all placeholder:text-slate-400">
                            @error('creci')
                                <span class="text-red-650 text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Senha -->
                        <div>
                            <label class="block text-slate-700 font-black text-[10px] uppercase tracking-wider mb-1.5 pl-1">
                                {{ $isEditMode ? 'Definir Nova Senha (Opcional)' : 'Senha de Acesso *' }}
                            </label>
                            <input type="password" wire:model="senha" placeholder="Mínimo 6 caracteres..."
                                   class="w-full bg-slate-50 border border-slate-200 rounded-2xl h-12 px-4 text-sm text-slate-800 focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition-all placeholder:text-slate-400">
                            @error('senha')
                                <span class="text-red-650 text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Vincular Estados -->
                    <div class="space-y-2">
                        <label class="block text-slate-700 font-black text-[10px] uppercase tracking-wider pl-1">Estados Atendidos (Vincular para Distribuição de Leads)</label>
                        <div class="bg-slate-50 border border-slate-200 rounded-[2rem] p-5 max-h-40 overflow-y-auto grid grid-cols-3 sm:grid-cols-4 gap-3">
                            @foreach($todosEstados as $est)
                                <label class="flex items-center gap-2 text-xs font-bold text-slate-700 cursor-pointer hover:text-[#005CA9] transition-colors">
                                    <input type="checkbox" 
                                           wire:model="selectedEstados" 
                                           value="{{ $est->id }}"
                                           class="w-4.5 h-4.5 text-[#005CA9] border-slate-350 focus:ring-[#005CA9] rounded-md">
                                    <span>{{ $est->uf }} ({{ $est->nome }})</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Upload da Imagem do Botão -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 items-center">
                        <div>
                            <label class="block text-slate-700 font-black text-[10px] uppercase tracking-wider mb-1.5 pl-1">Upload da Imagem do Botão (PNG Transparente)</label>
                            <div class="relative">
                                <input type="file" wire:model="imagem" accept="image/png"
                                       class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-extrabold file:bg-blue-50 file:text-[#005CA9] hover:file:bg-[#005CA9] hover:file:text-white transition-all cursor-pointer">
                                
                                <!-- Loading Spinner for Upload -->
                                <div wire:loading wire:target="imagem" class="absolute right-3 top-2.5">
                                    <svg class="animate-spin h-5 w-5 text-[#005CA9]" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('imagem')
                                <span class="text-red-650 text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- Image Preview -->
                        <div class="flex items-center justify-center p-4 bg-slate-50 border border-dashed border-slate-200 rounded-2xl min-h-[90px]">
                            @if($imagem)
                                <div class="text-center">
                                    <p class="text-[9px] text-emerald-600 font-bold uppercase mb-1">Pré-visualização do Upload:</p>
                                    @try
                                        <img src="{{ $imagem->temporaryUrl() }}" class="max-h-[60px] object-contain shadow-sm border border-white/50 bg-white p-1 rounded-lg">
                                    @catch(\Exception $e)
                                        <div class="text-xs font-bold text-slate-500">
                                            <span>{{ $imagem->getClientOriginalName() }}</span>
                                            <span class="block text-[10px] text-slate-400 font-medium">({{ round($imagem->getSize() / 1024) }} KB)</span>
                                        </div>
                                    @endtry
                                </div>
                            @elseif($imagemExistente)
                                <div class="text-center">
                                    <p class="text-[9px] text-slate-400 font-bold uppercase mb-1">Imagem Atual no Sistema:</p>
                                    <img src="{{ asset('storage/' . $imagemExistente) }}" class="max-h-[60px] object-contain shadow-sm border border-white/50 bg-white p-1 rounded-lg">
                                </div>
                            @else
                                <span class="text-slate-400 italic text-xs text-center leading-none">Sem imagem de botão configurada. Irá usar o WhatsApp central do sistema.</span>
                            @endif
                        </div>
                    </div>

                    <!-- Toggle Ativo -->
                    <div class="flex items-center gap-3 pl-1">
                        <input type="checkbox" wire:model="ativo" id="ativo"
                               class="w-5 h-5 text-[#005CA9] border-slate-350 focus:ring-[#005CA9] rounded-md cursor-pointer">
                        <label for="ativo" class="text-xs font-black uppercase text-slate-700 cursor-pointer">
                            Esta Imobiliária está ativa no sistema
                        </label>
                    </div>

                    <!-- Modal Actions Footer -->
                    <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3.5">
                        <button type="button" wire:click="fecharModal"
                                class="px-5 py-3 border border-slate-200 text-slate-500 font-black rounded-2xl hover:bg-slate-50 transition-colors active:scale-95 text-xs uppercase tracking-wider">
                            Cancelar
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="imagem"
                                class="bg-[#005CA9] hover:bg-[#004B87] text-white font-black px-6 py-3 rounded-2xl shadow-xl shadow-blue-500/10 transition-colors active:scale-95 text-xs uppercase tracking-wider disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="imagem">Salvar Alterações</span>
                            <span wire:loading wire:target="imagem">Carregando imagem...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>

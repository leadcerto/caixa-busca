<div class="bg-gradient-to-br from-[#005CA9]/5 to-blue-50 border border-[#005CA9]/20 rounded-2xl p-6 space-y-5">

    {{-- Header --}}
    <div class="text-center space-y-1.5">
        <span class="text-3xl block">🔓</span>
        <p class="font-black text-gray-900 text-base leading-tight">Desbloqueie a Análise do Comprador</p>
        <p class="text-xs text-gray-500 leading-relaxed">
            Acesso <strong class="text-emerald-600">100% gratuito</strong> — preencha seus dados para liberar os cálculos completos de lucro, investimento e financiamento.
        </p>
    </div>

    {{-- Form --}}
    <div class="space-y-3">

        {{-- Nome --}}
        <div>
            <label class="block text-[9px] font-black uppercase tracking-wider text-gray-500 mb-1.5 pl-1">
                Seu Nome Completo
            </label>
            <input type="text"
                   wire:model="nome"
                   placeholder="Ex: João da Silva"
                   autocomplete="name"
                   class="w-full bg-white border border-gray-200 rounded-xl h-11 px-4 text-sm text-gray-800 focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition-all placeholder:text-gray-400">
            @error('nome')
                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        {{-- WhatsApp com máscara Alpine.js --}}
        <div>
            <label class="block text-[9px] font-black uppercase tracking-wider text-gray-500 mb-1.5 pl-1">
                WhatsApp com DDD
            </label>
            <input type="tel"
                   placeholder="(21) 99999-9999"
                   maxlength="15"
                   autocomplete="tel"
                   x-on:input="
                       const d = $event.target.value.replace(/\D/g, '').slice(0, 11);
                       const r = d.length === 0  ? '' :
                                 d.length <= 2   ? '(' + d :
                                 d.length <= 6   ? '(' + d.slice(0,2) + ') ' + d.slice(2) :
                                 d.length <= 10  ? '(' + d.slice(0,2) + ') ' + d.slice(2,6) + '-' + d.slice(6) :
                                                   '(' + d.slice(0,2) + ') ' + d.slice(2,7) + '-' + d.slice(7);
                       $event.target.value = r;
                       $wire.set('whatsapp', r);
                   "
                   class="w-full bg-white border border-gray-200 rounded-xl h-11 px-4 text-sm text-gray-800 focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition-all placeholder:text-gray-400">
            @error('whatsapp')
                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        {{-- E-mail --}}
        <div>
            <label class="block text-[9px] font-black uppercase tracking-wider text-gray-500 mb-1.5 pl-1">
                Seu E-mail
            </label>
            <input type="email"
                   wire:model="email"
                   placeholder="joao@email.com"
                   autocomplete="email"
                   class="w-full bg-white border border-gray-200 rounded-xl h-11 px-4 text-sm text-gray-800 focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition-all placeholder:text-gray-400">
            @error('email')
                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        {{-- Submit --}}
        <button wire:click="submit"
                wire:loading.attr="disabled"
                class="w-full bg-[#005CA9] hover:bg-[#004d8f] active:scale-95 disabled:opacity-70 text-white font-black py-3 rounded-xl shadow-lg shadow-blue-500/20 transition-all duration-300 flex items-center justify-center gap-2 text-sm tracking-wider mt-1">
            <span wire:loading.remove wire:target="submit">
                🔓 DESBLOQUEAR ANÁLISE GRATUITA
            </span>
            <span wire:loading wire:target="submit" class="flex items-center gap-2">
                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
                Verificando…
            </span>
        </button>
    </div>

    <p class="text-[9px] text-gray-400 text-center uppercase tracking-wider">
        🔐 Seus dados estão 100% protegidos conforme a LGPD.
    </p>
</div>

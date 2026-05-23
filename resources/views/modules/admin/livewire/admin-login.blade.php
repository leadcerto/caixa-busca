<div class="min-h-screen bg-gradient-to-br from-[#001E36] via-[#005CA9] to-[#001424] flex items-center justify-center px-6 relative overflow-hidden">
    <!-- Glowing background elements -->
    <div class="absolute top-[-10%] left-[-10%] w-[800px] h-[800px] rounded-full bg-blue-400/20 blur-[150px] pointer-events-none animate-pulse"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[800px] h-[800px] rounded-full bg-[#F39200]/10 blur-[150px] pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">

        <div class="text-center mb-10">
            <!-- Brand icon -->
            <div class="w-20 h-20 rounded-3xl bg-gradient-to-tr from-white to-blue-50 flex items-center justify-center shadow-2xl mx-auto mb-6 border border-white/20 hover:scale-105 transition-transform duration-300">
                <svg class="w-11 h-11 text-[#005CA9]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            
            <h1 class="text-4xl font-black text-white tracking-tight leading-none drop-shadow-md">Imóveis da Caixa</h1>
            <p class="text-blue-200 text-xs font-extrabold uppercase tracking-widest mt-4 bg-blue-950/40 inline-block px-4 py-1.5 rounded-full border border-blue-800/40">Área Administrativa</p>
        </div>

        <div class="bg-slate-950/50 backdrop-blur-2xl rounded-[2.5rem] border border-white/10 shadow-2xl p-10 space-y-8">

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-350 mb-2.5">E-mail Corporativo</label>
                <input type="email" wire:model="email" placeholder="nome@exemplo.com"
                       class="w-full bg-white/5 border border-white/10 rounded-2xl h-14 px-5 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#F39200] focus:border-transparent transition-all">
                @error('email')
                    <span class="text-rose-450 text-xs mt-2 font-semibold block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-350 mb-2.5">Senha de Acesso</label>
                <input type="password" wire:model="password" placeholder="••••••••"
                       class="w-full bg-white/5 border border-white/10 rounded-2xl h-14 px-5 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#F39200] focus:border-transparent transition-all">
                @error('password')
                    <span class="text-rose-450 text-xs mt-2 font-semibold block">{{ $message }}</span>
                @enderror
            </div>

            <button wire:click="login"
                    wire:loading.attr="disabled"
                    class="w-full bg-gradient-to-r from-[#F39200] to-amber-500 hover:from-[#d58000] hover:to-amber-600 text-slate-950 font-black h-14 rounded-2xl transition-all shadow-lg shadow-amber-500/20 active:scale-[0.98] uppercase tracking-wider text-xs">
                <span wire:loading.remove wire:target="login">Acessar Painel</span>
                <span wire:loading wire:target="login" class="flex items-center justify-center space-x-2">
                    <svg class="animate-spin h-5 w-5 text-slate-950" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Autenticando…</span>
                </span>
            </button>
        </div>
    </div>
</div>



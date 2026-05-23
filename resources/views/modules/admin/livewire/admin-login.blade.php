<div class="min-h-screen bg-slate-900 flex items-center justify-center px-6 relative overflow-hidden">
    <!-- Glowing background elements -->
    <div class="absolute top-[-20%] left-[-10%] w-[600px] h-[600px] rounded-full bg-blue-500/10 blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-[-20%] right-[-10%] w-[600px] h-[600px] rounded-full bg-amber-500/5 blur-[120px] pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">

        <div class="text-center mb-10">
            <!-- Brand icon -->
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-[#005CA9] to-blue-400 flex items-center justify-center shadow-2xl mx-auto mb-6">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            
            <h1 class="text-3xl font-extrabold text-white tracking-tight leading-none">Imóveis da Caixa</h1>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-3">Área Administrativa</p>
        </div>

        <div class="bg-slate-900/60 backdrop-blur-xl rounded-[2rem] border border-slate-800 shadow-2xl p-10 space-y-6">

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2.5">E-mail Corporativo</label>
                <input type="email" wire:model="email" placeholder="nome@exemplo.com"
                       class="w-full bg-slate-950/60 border border-slate-800 rounded-2xl h-14 px-5 text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition-all">
                @error('email')
                    <span class="text-rose-500 text-xs mt-2 font-medium block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2.5">Senha de Acesso</label>
                <input type="password" wire:model="password" placeholder="••••••••"
                       class="w-full bg-slate-950/60 border border-slate-800 rounded-2xl h-14 px-5 text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition-all">
                @error('password')
                    <span class="text-rose-500 text-xs mt-2 font-medium block">{{ $message }}</span>
                @enderror
            </div>

            <button wire:click="login"
                    wire:loading.attr="disabled"
                    class="w-full bg-gradient-to-r from-[#005CA9] to-blue-600 hover:from-[#004a8c] hover:to-blue-700 text-white font-extrabold h-14 rounded-2xl transition-all shadow-lg shadow-blue-500/20 active:scale-[0.98]">
                <span wire:loading.remove wire:target="login">Acessar Painel</span>
                <span wire:loading wire:target="login" class="flex items-center justify-center space-x-2">
                    <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Autenticando…</span>
                </span>
            </button>
        </div>
    </div>
</div>


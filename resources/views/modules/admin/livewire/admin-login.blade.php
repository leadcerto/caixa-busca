<div class="min-h-screen bg-gray-50 flex items-center justify-center px-6">
    <div class="w-full max-w-md">

        <div class="text-center mb-10">
            <span class="text-4xl font-black text-[#005CA9]">Antigravity</span>
            <p class="text-gray-400 text-sm mt-2 uppercase tracking-widest font-bold">Área Administrativa</p>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 p-10 space-y-6">

            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">E-mail</label>
                <input type="email" wire:model="email"
                       class="w-full border border-gray-200 rounded-2xl h-14 px-5 text-gray-800 focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition">
                @error('email')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Senha</label>
                <input type="password" wire:model="password"
                       class="w-full border border-gray-200 rounded-2xl h-14 px-5 text-gray-800 focus:ring-2 focus:ring-[#005CA9] focus:border-transparent transition">
                @error('password')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <button wire:click="login"
                    wire:loading.attr="disabled"
                    class="w-full bg-[#005CA9] hover:bg-[#004a8c] text-white font-black h-14 rounded-2xl transition-all shadow-lg shadow-blue-200">
                <span wire:loading.remove wire:target="login">Entrar</span>
                <span wire:loading wire:target="login">Autenticando…</span>
            </button>
        </div>
    </div>
</div>

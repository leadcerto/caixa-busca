<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Painel' }} — Antigravity Parceiro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Nav bar parceiro -->
    <nav class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">

            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <span class="text-xl font-black text-[#005CA9] tracking-tight">Antigravity</span>
                <span class="hidden sm:inline text-[10px] font-black uppercase tracking-widest text-gray-300 border-l border-gray-200 pl-3">Parceiro</span>
            </div>

            <!-- Imobiliária logada + logout -->
            <div class="flex items-center space-x-4">
                <span class="hidden sm:block text-xs text-gray-400 font-medium truncate max-w-[200px]">
                    {{ auth('imobiliaria')->user()?->nome }}
                </span>
                <form action="{{ route('imobiliaria.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="text-xs font-black uppercase tracking-wider text-gray-400 hover:text-red-500 transition-colors px-3 py-2 rounded-xl hover:bg-red-50">
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Conteúdo principal -->
    {{ $slot }}

    @livewireScripts
</body>
</html>

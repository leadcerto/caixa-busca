<!DOCTYPE html>
<html lang="pt-BR" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin' }} — Imóveis da Caixa</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <!-- Alpine.js is automatically loaded by Livewire v3+ -->
</head>
<body class="h-full bg-gradient-to-br from-[#F4F7FC] via-white to-[#EDF2FA] text-slate-800 antialiased font-sans flex flex-col md:flex-row min-h-screen" x-data="{ mobileMenuOpen: false }">

    <!-- SIDEBAR FOR DESKTOP -->
    <aside class="hidden md:flex flex-col w-72 bg-gradient-to-b from-[#003C6F] to-[#001D38] text-slate-100 border-r border-blue-950/40 h-screen sticky top-0 shrink-0 shadow-2xl z-40">
        <!-- Brand/Header -->
        <div class="h-20 px-6 flex items-center justify-between border-b border-blue-900/20 bg-slate-950/20">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 group">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-white to-blue-100 flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform border border-white/20">
                    <svg class="w-5 h-5 text-[#005CA9]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-base font-black tracking-tight text-white leading-none">Imóveis da Caixa</span>
                    <span class="text-[9px] font-extrabold uppercase tracking-widest text-[#F39200] mt-1 bg-blue-950/30 px-2 py-0.5 rounded border border-blue-800/20">Administrativo</span>
                </div>
            </a>
        </div>

        <!-- Navigation Links Stack -->
        <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1.5 scrollbar-thin scrollbar-thumb-slate-800">
            
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center space-x-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-[#005CA9] to-blue-600 text-white shadow-lg shadow-blue-500/30 border border-blue-400/20 font-bold' : 'text-blue-100/70 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-blue-200/50 group-hover:text-blue-200' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <div class="flex flex-col min-w-0">
                    <span class="truncate">Dashboard</span>
                    <span class="text-[9px] opacity-70 font-normal truncate">Visão geral em tempo real</span>
                </div>
            </a>

            <a href="{{ route('admin.leads') }}"
               class="flex items-center space-x-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('admin.leads') ? 'bg-gradient-to-r from-[#005CA9] to-blue-600 text-white shadow-lg shadow-blue-500/30 border border-blue-400/20 font-bold' : 'text-blue-100/70 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.leads') ? 'text-white' : 'text-blue-200/50 group-hover:text-blue-200' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <div class="flex flex-col min-w-0">
                    <span class="truncate">Gestão de Leads</span>
                    <span class="text-[9px] opacity-70 font-normal truncate">Gerenciar contatos recebidos</span>
                </div>
            </a>

            <a href="{{ route('admin.imobiliarias') }}"
               class="flex items-center space-x-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('admin.imobiliarias') ? 'bg-gradient-to-r from-[#005CA9] to-blue-600 text-white shadow-lg shadow-blue-500/30 border border-blue-400/20 font-bold' : 'text-blue-100/70 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.imobiliarias') ? 'text-white' : 'text-blue-200/50 group-hover:text-blue-200' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <div class="flex flex-col min-w-0">
                    <span class="truncate">Gestão de Parceiros</span>
                    <span class="text-[9px] opacity-70 font-normal truncate">Gerenciar imobiliárias credenciadas</span>
                </div>
            </a>

            <!-- BOTÃO CRÍTICO: CARREGAR LISTA DE IMÓVEIS (IMPORTAR CSV) -->
            <a href="{{ route('admin.importar') }}"
               class="flex items-center space-x-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('admin.importar') ? 'bg-[#F39200] text-slate-950 shadow-lg shadow-amber-500/20' : 'text-blue-100/70 hover:text-white hover:bg-white/5 border border-dashed border-blue-400/20' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.importar') ? 'text-slate-950' : 'text-[#F39200] group-hover:scale-105 transition-transform' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                <div class="flex flex-col min-w-0">
                    <span class="{{ request()->routeIs('admin.importar') ? 'text-slate-950 font-black' : 'text-white' }} truncate">Carregar Imóveis</span>
                    <span class="text-[9px] {{ request()->routeIs('admin.importar') ? 'text-slate-800' : 'text-[#F39200]' }} font-semibold truncate">Importar planilha CSV</span>
                </div>
            </a>

            <a href="{{ route('admin.imoveis.busca-interna') }}"
               class="flex items-center space-x-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('admin.imoveis.busca-interna') ? 'bg-gradient-to-r from-[#005CA9] to-blue-600 text-white shadow-lg shadow-blue-500/30 border border-blue-400/20 font-bold' : 'text-blue-100/70 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.imoveis.busca-interna') ? 'text-white' : 'text-blue-200/50 group-hover:text-blue-200' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <div class="flex flex-col min-w-0">
                    <span class="truncate">Busca Interna</span>
                    <span class="text-[9px] opacity-70 font-normal truncate">Filtros avançados de imóveis</span>
                </div>
            </a>

            <a href="{{ route('admin.crm') }}"
               class="flex items-center space-x-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('admin.crm') ? 'bg-gradient-to-r from-[#005CA9] to-blue-600 text-white shadow-lg shadow-blue-500/30 border border-blue-400/20 font-bold' : 'text-blue-100/70 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.crm') ? 'text-white' : 'text-blue-200/50 group-hover:text-blue-200' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <div class="flex flex-col min-w-0">
                    <span class="truncate">Integração CRM</span>
                    <span class="text-[9px] opacity-70 font-normal truncate">Configurar webhooks e disparos</span>
                </div>
            </a>

            <a href="{{ route('admin.bairros') }}"
               class="flex items-center space-x-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('admin.bairros') ? 'bg-gradient-to-r from-[#005CA9] to-blue-600 text-white shadow-lg shadow-blue-500/30 border border-blue-400/20 font-bold' : 'text-blue-100/70 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.bairros') ? 'text-white' : 'text-blue-200/50 group-hover:text-blue-200' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
                <div class="flex flex-col min-w-0">
                    <span class="truncate">Bairros IA (Dossiê)</span>
                    <span class="text-[9px] opacity-70 font-normal truncate">Descrições enriquecidas</span>
                </div>
            </a>

            <a href="{{ route('admin.whatsapp') }}"
               class="flex items-center space-x-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('admin.whatsapp') ? 'bg-gradient-to-r from-[#005CA9] to-blue-600 text-white shadow-lg shadow-blue-500/30 border border-blue-400/20 font-bold' : 'text-blue-100/70 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.whatsapp') ? 'text-white' : 'text-blue-200/50 group-hover:text-blue-200' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <div class="flex flex-col min-w-0">
                    <span class="truncate">WhatsApp API</span>
                    <span class="text-[9px] opacity-70 font-normal truncate">Modelos e configurações</span>
                </div>
            </a>

            <a href="{{ route('admin.diagnostico') }}"
               class="flex items-center space-x-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('admin.diagnostico') ? 'bg-gradient-to-r from-[#005CA9] to-blue-600 text-white shadow-lg shadow-blue-500/30 border border-blue-400/20 font-bold' : 'text-blue-100/70 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.diagnostico') ? 'text-white' : 'text-blue-200/50 group-hover:text-blue-200' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                </svg>
                <div class="flex flex-col min-w-0">
                    <span class="truncate">Diagnóstico</span>
                    <span class="text-[9px] opacity-70 font-normal truncate">Integridade e métricas</span>
                </div>
            </a>
        </nav>

        <!-- Footer / Logged User & Logout -->
        <div class="p-4 border-t border-blue-900/20 bg-slate-950/20 flex flex-col space-y-3">
            <div class="flex items-center space-x-3 px-2">
                <div class="w-9 h-9 rounded-full bg-blue-950/40 border border-blue-800/40 flex items-center justify-center font-bold text-xs text-[#F39200]">
                    {{ substr(auth()->user()?->email ?? 'A', 0, 2) }}
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-xs font-semibold text-slate-200 truncate leading-none">{{ auth()->user()?->email }}</span>
                    <span class="text-[9px] font-medium text-blue-200 mt-1 uppercase tracking-wider">Online</span>
                </div>
            </div>
            
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center space-x-2 px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider text-blue-200/60 hover:text-red-400 hover:bg-red-500/10 border border-transparent hover:border-red-500/20 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Sair do Painel</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MOBILE STICKY NAVBAR -->
    <header class="md:hidden bg-gradient-to-r from-[#003C6F] to-[#00284B] border-b border-blue-900/30 h-16 px-4 flex items-center justify-between sticky top-0 z-40 shadow-md">
        <!-- Logo -->
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2.5">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-white to-blue-50 flex items-center justify-center shadow">
                <svg class="w-4.5 h-4.5 text-[#005CA9]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-black text-white leading-none tracking-tight">Imóveis da Caixa</span>
                <span class="text-[8px] font-bold uppercase tracking-wider text-[#F39200] mt-0.5">Admin</span>
            </div>
        </a>

        <!-- Hamburger Icon Button -->
        <button type="button" 
                @click="mobileMenuOpen = !mobileMenuOpen"
                class="w-10 h-10 flex items-center justify-center rounded-xl bg-blue-950/20 border border-blue-800/20 hover:bg-blue-950/40 text-blue-200 focus:outline-none transition-all active:scale-95"
                aria-label="Menu principal">
            <svg class="w-6 h-6" x-show="!mobileMenuOpen" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg class="w-6 h-6" x-show="mobileMenuOpen" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display: none;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </header>

    <!-- MOBILE MENU DRAWER OVERLAY -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-950/85 backdrop-blur-sm z-40 md:hidden"
         @click="mobileMenuOpen = false"
         style="display: none;">
    </div>

    <!-- MOBILE MENU DRAWER -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 max-w-xs w-full bg-gradient-to-b from-[#003C6F] to-[#001D38] shadow-2xl z-50 md:hidden flex flex-col border-r border-blue-950/40 text-slate-100"
         style="display: none;">
        
        <!-- Drawer Header -->
        <div class="h-16 px-6 flex items-center justify-between border-b border-blue-900/20 bg-slate-950/20 shrink-0">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-white to-blue-50 flex items-center justify-center shadow border border-white/10">
                    <svg class="w-4.5 h-4.5 text-[#005CA9]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-black text-white leading-none tracking-tight">Imóveis da Caixa</span>
                    <span class="text-[8px] font-bold uppercase tracking-wider text-[#F39200] mt-0.5">Administrativo</span>
                </div>
            </div>
            <button type="button" @click="mobileMenuOpen = false" class="text-blue-200 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Drawer Navigation List -->
        <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-2">
            
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center space-x-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-[#005CA9] to-blue-600 text-white shadow-lg border border-blue-400/20 font-bold' : 'text-blue-100/70 hover:text-white hover:bg-white/5' }}"
               @click="mobileMenuOpen = false">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.leads') }}"
               class="flex items-center space-x-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.leads') ? 'bg-gradient-to-r from-[#005CA9] to-blue-600 text-white shadow-lg border border-blue-400/20 font-bold' : 'text-blue-100/70 hover:text-white hover:bg-white/5' }}"
               @click="mobileMenuOpen = false">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Gestão de Leads</span>
            </a>

            <a href="{{ route('admin.imobiliarias') }}"
               class="flex items-center space-x-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.imobiliarias') ? 'bg-gradient-to-r from-[#005CA9] to-blue-600 text-white shadow-lg border border-blue-400/20 font-bold' : 'text-blue-100/70 hover:text-white hover:bg-white/5' }}"
               @click="mobileMenuOpen = false">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span>Gestão de Parceiros</span>
            </a>

            <!-- BOTÃO CRÍTICO: CARREGAR LISTA DE IMÓVEIS (IMPORTAR CSV) -->
            <a href="{{ route('admin.importar') }}"
               class="flex items-center space-x-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.importar') ? 'bg-[#F39200] text-slate-950 shadow-lg shadow-amber-500/20 font-bold' : 'text-blue-100/70 hover:text-white hover:bg-white/5 border border-dashed border-blue-400/20' }}"
               @click="mobileMenuOpen = false">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.importar') ? 'text-slate-950' : 'text-[#F39200]' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                <span class="{{ request()->routeIs('admin.importar') ? 'text-slate-950 font-black' : 'text-white' }}">Carregar Imóveis (CSV)</span>
            </a>

            <a href="{{ route('admin.imoveis.busca-interna') }}"
               class="flex items-center space-x-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.imoveis.busca-interna') ? 'bg-gradient-to-r from-[#005CA9] to-blue-600 text-white shadow-lg border border-blue-400/20 font-bold' : 'text-blue-100/70 hover:text-white hover:bg-white/5' }}"
               @click="mobileMenuOpen = false">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span>Busca Interna</span>
            </a>

            <a href="{{ route('admin.crm') }}"
               class="flex items-center space-x-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.crm') ? 'bg-gradient-to-r from-[#005CA9] to-blue-600 text-white shadow-lg border border-blue-400/20 font-bold' : 'text-blue-100/70 hover:text-white hover:bg-white/5' }}"
               @click="mobileMenuOpen = false">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <span>Integração CRM</span>
            </a>

            <a href="{{ route('admin.bairros') }}"
               class="flex items-center space-x-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.bairros') ? 'bg-gradient-to-r from-[#005CA9] to-blue-600 text-white shadow-lg border border-blue-400/20 font-bold' : 'text-blue-100/70 hover:text-white hover:bg-white/5' }}"
               @click="mobileMenuOpen = false">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
                <span>Bairros IA</span>
            </a>

            <a href="{{ route('admin.whatsapp') }}"
               class="flex items-center space-x-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.whatsapp') ? 'bg-gradient-to-r from-[#005CA9] to-blue-600 text-white shadow-lg border border-blue-400/20 font-bold' : 'text-blue-100/70 hover:text-white hover:bg-white/5' }}"
               @click="mobileMenuOpen = false">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span>WhatsApp API</span>
            </a>

            <a href="{{ route('admin.diagnostico') }}"
               class="flex items-center space-x-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.diagnostico') ? 'bg-gradient-to-r from-[#005CA9] to-blue-600 text-white shadow-lg border border-blue-400/20 font-bold' : 'text-blue-100/70 hover:text-white hover:bg-white/5' }}"
               @click="mobileMenuOpen = false">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                </svg>
                <span>Diagnóstico</span>
            </a>
        </nav>

        <!-- Drawer Footer -->
        <div class="p-4 border-t border-blue-900/20 bg-slate-950/20 shrink-0">
            <div class="flex items-center space-x-3 px-2 mb-3">
                <div class="w-9 h-9 rounded-full bg-blue-950/40 border border-blue-800/40 flex items-center justify-center font-bold text-xs text-[#F39200]">
                    {{ substr(auth()->user()?->email ?? 'A', 0, 2) }}
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-xs font-semibold text-slate-200 truncate leading-none">{{ auth()->user()?->email }}</span>
                    <span class="text-[9px] font-medium text-blue-200 mt-1 uppercase tracking-wider">Online</span>
                </div>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST" @submit="mobileMenuOpen = false">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center space-x-2 px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider text-slate-400 hover:text-red-400 hover:bg-red-500/10 border border-transparent hover:border-red-500/20 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Sair</span>
                </button>
            </form>
        </div>
    </div>

    <!-- MAIN CONTENT VIEWPORT -->
    <main class="flex-1 min-w-0 overflow-y-auto">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>

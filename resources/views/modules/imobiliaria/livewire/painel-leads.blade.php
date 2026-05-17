<div class="min-h-screen bg-gray-50 py-12 px-6">
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900">Painel do Parceiro</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Bem-vindo, <span class="font-bold text-[#005CA9]">{{ $imobiliaria->nome }}</span>.
                    Leads recebidos na sua região.
                </p>
            </div>

            <div class="flex items-center gap-4">
                @if($imobiliaria->estados->isNotEmpty())
                    <div class="bg-blue-50 px-4 py-2 rounded-2xl border border-blue-100 text-sm">
                        <span class="text-blue-500 font-bold uppercase text-[10px] tracking-widest block">Estado(s)</span>
                        <span class="text-blue-900 font-black">
                            {{ $imobiliaria->estados->pluck('uf')->join(', ') }}
                        </span>
                    </div>
                @endif

                <form method="POST" action="{{ route('imobiliaria.logout') }}">
                    @csrf
                    <button type="submit"
                            class="bg-white border border-gray-200 text-gray-500 hover:text-red-500 hover:border-red-200 font-bold text-sm px-5 py-2 rounded-2xl transition-all">
                        Sair
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabela -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">

            @if($atendimentos->isEmpty())
                <div class="text-center py-24 px-6">
                    <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-[#005CA9]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-800 mb-2">Nenhum lead ainda</h3>
                    <p class="text-gray-400 max-w-sm mx-auto text-sm">
                        Quando visitantes demonstrarem interesse em imóveis da sua região, os leads aparecerão aqui.
                    </p>
                </div>

            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Data</th>
                                <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Lead</th>
                                <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Contato</th>
                                <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Imóvel</th>
                                <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Origem</th>
                                <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($atendimentos as $atendimento)
                                <tr class="hover:bg-gray-50 transition-colors">

                                    <!-- Data -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                        {{ $atendimento->created_at->format('d/m/Y') }}<br>
                                        <span class="text-xs">{{ $atendimento->created_at->format('H:i') }}</span>
                                    </td>

                                    <!-- Lead -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-gray-900 text-sm">{{ $atendimento->lead?->nome }}</div>
                                        <div class="text-xs text-gray-400">{{ $atendimento->lead?->email }}</div>
                                    </td>

                                    <!-- Contato -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($atendimento->lead?->telefone)
                                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $atendimento->lead->telefone) }}"
                                               target="_blank"
                                               class="inline-flex items-center gap-1 bg-green-50 text-green-700 hover:bg-green-100 text-xs font-bold px-3 py-1.5 rounded-full transition-colors">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                                </svg>
                                                {{ $atendimento->lead->telefone }}
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-300">—</span>
                                        @endif
                                    </td>

                                    <!-- Imóvel -->
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-[#005CA9]">
                                            @if($atendimento->imovel?->slug)
                                                <a href="{{ route('imovel.show', $atendimento->imovel->slug) }}"
                                                   target="_blank"
                                                   class="hover:underline">
                                                    #{{ $atendimento->imovel->numero_original }}
                                                </a>
                                            @else
                                                #{{ $atendimento->imovel?->numero_original }}
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-400 mt-0.5">
                                            {{ $atendimento->imovel?->tipoImovel?->nome }}
                                            @if($atendimento->imovel?->municipio)
                                                — {{ $atendimento->imovel->municipio->nome }}/{{ $atendimento->imovel->estado?->uf }}
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Origem -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-xs font-bold text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                            {{ $atendimento->origem?->nome ?? '—' }}
                                        </span>
                                    </td>

                                    <!-- Status de notificação -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <span title="WhatsApp" class="w-2 h-2 rounded-full {{ $atendimento->whatsapp_enviado ? 'bg-green-400' : 'bg-gray-200' }}"></span>
                                            <span title="E-mail" class="w-2 h-2 rounded-full {{ $atendimento->email_enviado ? 'bg-blue-400' : 'bg-gray-200' }}"></span>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $atendimentos->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

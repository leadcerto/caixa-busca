@php
    $historico = $imovel->ultimoHistorico;
    $valorAvaliacao = $historico?->valor_avaliacao ?? 0;
    $valorVenda = $historico?->valor_venda ?? 0;
    $descontoPct = $historico?->desconto_percentual ?? 0;
    $valorLucro = $historico?->desconto_valor ?? ($valorAvaliacao - $valorVenda);
    if ($valorLucro < 0) {
        $valorLucro = 0;
    }

    $cidadeNome = $imovel->municipio?->nome ?? '';
    $bairroNome = $imovel->bairro?->nome ?? '';
    $uf = $imovel->estado?->uf ?? '';
    $endereco = $imovel->endereco;
    $codigo = $imovel->numero_original;
    $tipoNome = $imovel->tipoImovel?->nome ?? 'Imóvel';
    $cepFormatado = $imovel->cep ? preg_replace('/(\d{5})(\d{3})/', '$1-$2', $imovel->cep) : 'Não informado';

    $aceitaFgts              = ($imovel->aceita_fgts === 'sim');
    // aceita_financ_sbpe pode ser NULL em registros importados antes da coluna existir no banco.
    // Nesses casos, usamos aceita_fgts como substituto (mesma fonte CSV historicamente).
    $aceitaFinanciamentoSbpe = $imovel->aceita_financ_sbpe !== null
        ? (bool) $imovel->aceita_financ_sbpe
        : $aceitaFgts;
    $aceitaFinanciamentoMcmv = (bool) $imovel->aceita_financ_mcmv;
    // Financiamento = SBPE + MCMV. FGTS é modalidade de pagamento distinta.
    $aceitaFinanciamento = ($aceitaFinanciamentoSbpe || $aceitaFinanciamentoMcmv);

    // Morar calculations
    $morarRegistro = $valorAvaliacao * 0.05; 
    $morarCondominioMax = $valorAvaliacao * 0.10; 
    $morarDesocupacao = $valorAvaliacao * 0.015; 
    $morarDespachante = $valorAvaliacao * 0.005; 
    $entradaNormal = $valorAvaliacao * 0.20;
    $entradaCaixa = $valorVenda * 0.05;
    $reducaoEntrada = $entradaNormal - $entradaCaixa;
    $prestacaoTradicional = $valorAvaliacao * 0.008;
    $prestacaoCaixa = $valorVenda * 0.008;

    // Revenda calculations
    $reforma = $valorAvaliacao * 0.05; 
    $mesesVenda = 6;
    $condominioMes = $valorAvaliacao * 0.001 * $mesesVenda;
    $iptuMes = $valorAvaliacao * 0.0005 * $mesesVenda;
    $aguaLuzMes = 150 * $mesesVenda;
    $fundoReserva = 50 * $mesesVenda;
    $comissaoVenda = $valorAvaliacao * 0.05;
    $descontoAceleracao = $valorAvaliacao * 0.10;
    $vendaSugerida = $valorAvaliacao - $descontoAceleracao;
    $despesasTotais = $reforma + $condominioMes + $iptuMes + $aguaLuzMes + $fundoReserva + $comissaoVenda;
    $lucroPrevisto = $vendaSugerida - ($valorVenda + $despesasTotais);

    // Locação calculations
    $aluguelEstimado = $valorAvaliacao * 0.0047;
    $rentabilidadeReal = $valorVenda > 0 ? ($aluguelEstimado / $valorVenda) * 100 : 0;

    // Portal comparison valuations
    $valFipe = $valorAvaliacao * 0.98;
    $valLoft = $valorAvaliacao * 1.02;
    $valOlx = $valorAvaliacao * 0.95;
    $valQuinto = $valorAvaliacao * 1.05;

    // Imobiliária responsável pelo estado
    $resolvedImob = $imovel->resolved_imobiliaria;
    if ($resolvedImob) {
        $imobFone = preg_replace('/\D/', '', $resolvedImob->whatsapp);
        if (strlen($imobFone) > 0 && !str_starts_with($imobFone, '55')) {
            if (strlen($imobFone) === 10 || strlen($imobFone) === 11) {
                $imobFone = '55' . $imobFone;
            }
        }
    } else {
        $imobFone = preg_replace('/\D/', '', config('services.whatsapp.central', env('WHATSAPP_CENTRAL', '5521997882950')));
    }

    // Opportunity tier definitions
    if ($descontoPct >= 40) {
        $badgeStyle = 'background: linear-gradient(135deg, #FEF08A 0%, #FBBF24 50%, #CA8A04 100%) !important; border: 1px solid #F59E0B !important; color: #78350F !important; font-weight: 900 !important; text-shadow: none !important;';
        $badgeText = 'Ouro';
        $badgeIcon = '⭐';
    } elseif ($descontoPct >= 30) {
        $badgeStyle = 'background: linear-gradient(135deg, #CBD5E1 0%, #64748B 100%) !important; border: 1px solid #94A3B8 !important; color: #ffffff !important;';
        $badgeText = 'Prata';
        $badgeIcon = '🥈';
    } elseif ($descontoPct >= 20) {
        $badgeStyle = 'background: linear-gradient(135deg, #FCA5A5 0%, #EF4444 50%, #991B1B 100%) !important; border: 1px solid #EF4444 !important; color: #ffffff !important; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.4) !important;';
        $badgeText = 'Bronze';
        $badgeIcon = '🥉';
    } else {
        $badgeStyle = 'background: linear-gradient(135deg, #10B981 0%, #047857 100%) !important; border: 1px solid #10B981 !important; color: #ffffff !important;';
        $badgeText = 'Selecionado';
        $badgeIcon = '🏷️';
    }
@endphp

@if($imovel->foto_fachada_url)
@push('preload')
<link rel="preload" as="image" href="{{ $imovel->foto_fachada_url }}">
@endpush
@endif

@push('schema')
@php
$schemaListing = [
    '@context'   => 'https://schema.org',
    '@type'      => 'RealEstateListing',
    'name'       => "{$tipoNome} em {$bairroNome}, {$cidadeNome} – {$uf}",
    'description'=> mb_substr(strip_tags($imovel->descricao_original ?? ''), 0, 200),
    'url'        => url('/' . $imovel->slug),
    'datePosted' => $imovel->created_at?->toAtomString(),
    'image'      => $imovel->foto_fachada_url ?? asset('images/imovel-placeholder.svg'),
    'offers'     => [
        '@type'        => 'Offer',
        'price'        => number_format($valorVenda, 2, '.', ''),
        'priceCurrency'=> 'BRL',
        'availability' => 'https://schema.org/InStock',
    ],
    'address' => [
        '@type'           => 'PostalAddress',
        'streetAddress'   => $endereco,
        'addressLocality' => $cidadeNome,
        'addressRegion'   => $uf,
        'postalCode'      => $imovel->cep ?? '',
        'addressCountry'  => 'BR',
    ],
];
$schemaBreadcrumb = [
    '@context'        => 'https://schema.org',
    '@type'           => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Início',                          'item' => url('/')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => "{$uf} – Imóveis da Caixa",        'item' => url('/') . '?estado=' . urlencode($uf)],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $cidadeNome,                        'item' => url('/') . '?estado=' . urlencode($uf) . '&cidade=' . urlencode($cidadeNome)],
        ['@type' => 'ListItem', 'position' => 4, 'name' => "{$tipoNome} em {$bairroNome}",    'item' => url('/' . $imovel->slug)],
    ],
];
$schemaFaq = [
    '@context'   => 'https://schema.org',
    '@type'      => 'FAQPage',
    'mainEntity' => [
        [
            '@type' => 'Question',
            'name'  => "Este {$tipoNome} aceita FGTS?",
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => $aceitaFgts
                    ? "Sim, este {$tipoNome} em {$cidadeNome}/{$uf} aceita FGTS como forma de pagamento. Você pode usar o saldo do FGTS para abater parte do valor, com pagamento mínimo de 5% do preço de venda por boleto bancário."
                    : "Não, este {$tipoNome} em {$cidadeNome}/{$uf} não aceita FGTS como forma de pagamento. A compra deve ser realizada com recursos próprios ou financiamento.",
            ],
        ],
        [
            '@type' => 'Question',
            'name'  => "Este {$tipoNome} aceita financiamento bancário (SBPE)?",
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => $aceitaFinanciamentoSbpe
                    ? "Sim, este {$tipoNome} aceita financiamento SBPE. Com entrada mínima de 5% por boleto, o saldo pode ser financiado via crédito imobiliário. A Caixa recomenda ter pré-aprovação de crédito antes de fazer a proposta."
                    : "Não, este {$tipoNome} não aceita financiamento bancário. A compra deve ser feita à vista, com recursos próprios ou FGTS.",
            ],
        ],
        [
            '@type' => 'Question',
            'name'  => "Qual é o desconto e o lucro potencial deste imóvel da Caixa?",
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => $descontoPct > 0
                    ? "Este {$tipoNome} tem " . number_format($descontoPct, 0, ',', '.') . "% de desconto sobre o valor de avaliação. O preço de venda é R$ " . number_format($valorVenda, 2, ',', '.') . " e o lucro potencial imediato é de R$ " . number_format($valorLucro, 2, ',', '.') . "."
                    : "Consulte o valor atualizado deste {$tipoNome} na ficha completa do imóvel.",
            ],
        ],
        [
            '@type' => 'Question',
            'name'  => "Como fazer uma proposta para comprar este imóvel da Caixa?",
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => "A proposta deve ser feita no portal oficial da Caixa Econômica Federal. Após a aceitação, o pagamento mínimo de 5% do valor de venda é feito por boleto bancário em até 3 dias úteis. Se for financiado, há até 7 dias para apresentar a liberação do crédito. Recomendamos ter análise prévia de crédito aprovada antes de fazer a proposta.",
            ],
        ],
        [
            '@type' => 'Question',
            'name'  => "Quem é responsável por IPTU, condomínio e custas de cartório?",
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => "A Caixa cobre apenas as dívidas de IPTU e condomínio informadas na Proposta de Compra. Débitos posteriores, custas de cartório, despachante e eventuais despesas de desocupação são de responsabilidade do comprador.",
            ],
        ],
    ],
];
@endphp
<script type="application/ld+json">{!! json_encode($schemaListing,    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
<script type="application/ld+json">{!! json_encode($schemaBreadcrumb, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
<script type="application/ld+json">{!! json_encode($schemaFaq,        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
@endpush

<!-- Página de Apresentação de Imóvel Premium -->
<div class="bg-gray-50 min-h-screen text-gray-800 font-sans pb-24 selection:bg-[#F39200] selection:text-white"
     x-data="{ activeTab: null }">

    <!-- Hero Header Banner Section -->
    <div class="py-8 px-6 text-center text-white relative overflow-hidden border-b border-gray-100 shadow-md" style="background-color: #005CA9;">
        <div class="max-w-3xl mx-auto space-y-3 relative z-10">
            <h1 class="text-base md:text-lg font-bold tracking-tight leading-snug text-white">
                {{ $tipoNome }} à venda em {{ $bairroNome }}, {{ $cidadeNome }} - {{ $uf }}
            </h1>
            <p class="text-lg md:text-2xl text-white font-black max-w-2xl mx-auto">
                Lucro imediato de <span class="text-yellow-300 underline decoration-wavy decoration-orange-400">R$ {{ number_format($valorLucro, 2, ',', '.') }}</span>
            </p>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 space-y-8">

                <!-- Galeria / Imagem Fachada do Imóvel -->
                <div class="overflow-hidden relative group">
                    <div class="relative aspect-video rounded-2xl overflow-hidden bg-gray-50 border border-gray-200/60 shadow-sm">
                        <img src="{{ $imovel->foto_fachada_url ?? asset('images/imovel-placeholder.svg') }}"
                             alt="{{ $tipoNome }} em {{ $cidadeNome }}"
                             class="w-full h-full object-cover group-hover:scale-[1.01] transition-transform duration-700 ease-out"
                             loading="eager"
                             fetchpriority="high"
                             onerror="this.onerror=null;this.src='{{ asset('images/imovel-placeholder.svg') }}';">
                        
                        <!-- Floating badges row — stacks vertically on mobile, spreads apart on sm+ -->
                        <div class="absolute top-4 left-4 right-4 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                            <div class="bg-[#E50000] text-white font-black text-xs sm:text-sm px-3 sm:px-4 py-2 rounded-2xl shadow-xl border border-red-500 tracking-wider self-start">
                                {{ number_format($descontoPct, 0) }}% DE DESCONTO
                            </div>
                            @if($aceitaFinanciamento || $imovel->aceita_fgts === 'sim')
                            <div class="bg-[#005CA9] text-white font-black text-xs sm:text-sm px-3 sm:px-4 py-2 rounded-2xl shadow-xl border border-blue-400 tracking-wider self-start sm:self-auto">
                                ✅ ACEITA FINANCIAMENTO
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Bloco LUCRO IMEDIATO -->
                <div class="p-6 rounded-2xl text-center space-y-2" style="background-color: #F3F4F6; border: 1px solid #E5E7EB;">
                    <span class="font-black uppercase tracking-widest px-4 py-1.5 rounded-full inline-block text-xs" style="color: #111827; border: 1px solid #D1D5DB; background-color: #E5E7EB;">
                        LUCRO IMEDIATO
                    </span>
                    <span class="font-black block tracking-tight leading-none text-3xl md:text-4xl" style="color: #E50000;">
                        R$ {{ number_format($valorLucro, 2, ',', '.') }}
                    </span>
                    <p class="font-bold text-sm" style="color: #4B5563;">
                        <strong>De:</strong> R$ {{ number_format($valorAvaliacao, 2, ',', '.') }}
                    </p>
                    <p class="font-bold text-sm" style="color: #111827;">
                        <strong>Por Apenas:</strong> R$ {{ number_format($valorVenda, 2, ',', '.') }}
                    </p>
                    <p class="font-bold text-sm pt-1" style="color: #6B7280;">
                        ({{ number_format($descontoPct, 0) }}% OFF) por tempo limitado!
                    </p>
                </div>

                <!-- Endereço, Descrição e Dados Técnicos Físicos -->
                <div class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-lg border border-gray-100 space-y-6">
                    <div class="space-y-2">
                        <h2 class="text-xs font-black uppercase tracking-wider text-gray-500">📍 Endereço:</h2>
                        <p class="text-xl font-bold text-gray-900 flex items-start gap-2.5 leading-relaxed">
                            {{ $endereco }}, {{ $bairroNome }}, {{ $cidadeNome }} – {{ $uf }}
                        </p>
                    </div>

                    <hr class="border-gray-100">

                    <div class="space-y-3">
                        <h2 class="text-xs font-black uppercase tracking-wider text-gray-500">📝 Descrição:</h2>
                        <div class="prose prose-blue max-w-none text-gray-700 leading-relaxed text-justify text-sm">
                            {!! nl2br(e($imovel->descricao_original)) !!}
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <!-- Ficha Técnica e Checklist de Cômodos/Amenities -->
                    <div class="space-y-4">
                        <h2 class="text-xs font-black uppercase tracking-wider text-gray-500">⚙️ Informações Técnicas e Distribuição:</h2>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200/60 text-center">
                                <span class="text-[9px] uppercase font-black text-gray-500 block mb-1">Número do imóvel</span>
                                <span class="text-base font-black text-gray-900">{{ $codigo }}</span>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200/60 text-center">
                                <span class="text-[9px] uppercase font-black text-gray-500 block mb-1">Tipo de imóvel</span>
                                <span class="text-base font-black text-gray-900">{{ $tipoNome }}</span>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200/60 text-center">
                                <span class="text-[9px] uppercase font-black text-gray-500 block mb-1">CEP Formatado</span>
                                <span class="text-base font-black text-gray-900">{{ $cepFormatado }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-2">
                            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200/60 text-center">
                                <span class="text-[9px] uppercase font-black text-gray-500 block mb-1">Matrícula</span>
                                <span class="text-sm font-black text-gray-900 truncate block" title="{{ $imovel->matricula }}">{{ $imovel->matricula }}</span>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200/60 text-center">
                                <span class="text-[9px] uppercase font-black text-gray-500 block mb-1">Comarca</span>
                                <span class="text-sm font-black text-gray-900 truncate block" title="{{ $imovel->comarca }}">{{ $imovel->comarca }}</span>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200/60 text-center">
                                <span class="text-[9px] uppercase font-black text-gray-500 block mb-1">Ofício</span>
                                <span class="text-sm font-black text-gray-900 truncate block" title="{{ $imovel->oficio }}">{{ $imovel->oficio }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-2">
                            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200/60 text-center">
                                <span class="text-[9px] uppercase font-black text-gray-500 block mb-1">Inscrição Imobiliária</span>
                                <span class="text-sm font-black text-gray-900 truncate block" title="{{ $imovel->inscricao_imobiliaria }}">{{ $imovel->inscricao_imobiliaria }}</span>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200/60 text-center col-span-2">
                                <span class="text-[9px] uppercase font-black text-gray-500 block mb-1">Averbação dos leilões negativos</span>
                                <span class="text-sm font-black text-emerald-700 font-extrabold">Sim (Confirmada e Averbada no RGI)</span>
                            </div>
                        </div>

                        <hr class="border-gray-100 my-4">

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200/60 text-center">
                                <span class="text-[9px] uppercase font-black text-gray-500 block mb-1">Área do Terreno</span>
                                <span class="text-base font-black text-gray-900">
                                    {{ $imovel->area_terreno ? number_format($imovel->area_terreno, 2, ',', '.') . ' m²' : 'Não informado' }}
                                </span>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200/60 text-center">
                                <span class="text-[9px] uppercase font-black text-gray-500 block mb-1">Área Privativa</span>
                                <span class="text-base font-black text-gray-900">
                                    {{ $imovel->area_privativa ? number_format($imovel->area_privativa, 2, ',', '.') . ' m²' : 'Não informado' }}
                                </span>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200/60 text-center">
                                <span class="text-[9px] uppercase font-black text-gray-500 block mb-1">Área total</span>
                                <span class="text-base font-black text-gray-900">
                                    {{ $imovel->area_total ? number_format($imovel->area_total, 2, ',', '.') . ' m²' : 'Não informado' }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-4">
                            <!-- 1. Cozinha -->
                            <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200/60 text-center flex flex-col justify-center items-center">
                                <span class="text-[10px] sm:text-xs uppercase font-black text-gray-505 text-gray-500 block mb-1">🍳 Cozinha</span>
                                <span class="text-base sm:text-lg font-black text-gray-900">{{ $imovel->cozinha ? 'Sim' : 'Não' }}</span>
                            </div>
                            <!-- 2. Garagem -->
                            <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200/60 text-center flex flex-col justify-center items-center">
                                <span class="text-[10px] sm:text-xs uppercase font-black text-gray-505 text-gray-500 block mb-1">🚗 Garagem</span>
                                <span class="text-base sm:text-lg font-black text-gray-900">{{ $imovel->garagens ?? '0' }}</span>
                            </div>
                            <!-- 3. Quartos -->
                            <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200/60 text-center flex flex-col justify-center items-center">
                                <span class="text-[10px] sm:text-xs uppercase font-black text-gray-505 text-gray-500 block mb-1">🛏️ Quartos</span>
                                <span class="text-base sm:text-lg font-black text-gray-900">{{ $imovel->quartos ?? '0' }}</span>
                            </div>
                            <!-- 4. Sala -->
                            <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200/60 text-center flex flex-col justify-center items-center">
                                <span class="text-[10px] sm:text-xs uppercase font-black text-gray-550 text-gray-500 block mb-1">🛋️ Sala</span>
                                <span class="text-base sm:text-lg font-black text-gray-900">{{ $imovel->salas ?? '0' }}</span>
                            </div>
                            <!-- 5. Banheiro -->
                            <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200/60 text-center flex flex-col justify-center items-center">
                                <span class="text-[10px] sm:text-xs uppercase font-black text-gray-550 text-gray-500 block mb-1">🚽 Banheiro</span>
                                <span class="text-base sm:text-lg font-black text-gray-900">{{ $imovel->banheiros ?? '0' }}</span>
                            </div>
                            <!-- 6. Área de Serviço -->
                            <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200/60 text-center flex flex-col justify-center items-center">
                                <span class="text-[10px] sm:text-xs uppercase font-black text-gray-550 text-gray-500 block mb-1">🧺 Área de Serviço</span>
                                <span class="text-base sm:text-lg font-black text-gray-900">{{ $imovel->area_servico ? 'Sim' : 'Não' }}</span>
                            </div>
                            <!-- 7. Varanda -->
                            <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200/60 text-center flex flex-col justify-center items-center">
                                <span class="text-[10px] sm:text-xs uppercase font-black text-gray-550 text-gray-500 block mb-1">🌅 Varanda</span>
                                <span class="text-base sm:text-lg font-black text-gray-900">{{ $imovel->varanda ? 'Sim' : 'Não' }}</span>
                            </div>
                            <!-- 8. Terraço -->
                            <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200/60 text-center flex flex-col justify-center items-center">
                                <span class="text-[10px] sm:text-xs uppercase font-black text-gray-550 text-gray-500 block mb-1">🏢 Terraço</span>
                                <span class="text-base sm:text-lg font-black text-gray-900">{{ $imovel->terraco ? 'Sim' : 'Não' }}</span>
                            </div>
                            <!-- 9. Churrasqueira -->
                            <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200/60 text-center flex flex-col justify-center items-center">
                                <span class="text-[10px] sm:text-xs uppercase font-black text-gray-550 text-gray-500 block mb-1">🥩 Churrasqueira</span>
                                <span class="text-base sm:text-lg font-black text-gray-900">{{ $imovel->churrasqueira ? 'Sim' : 'Não' }}</span>
                            </div>
                            <!-- 10. Piscina -->
                            <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200/60 text-center flex flex-col justify-center items-center">
                                <span class="text-[10px] sm:text-xs uppercase font-black text-gray-550 text-gray-500 block mb-1">🏊 Piscina</span>
                                <span class="text-base sm:text-lg font-black text-gray-900">{{ $imovel->piscina ? 'Sim' : 'Não' }}</span>
                            </div>
                            <!-- 11. Sauna -->
                            <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200/60 text-center flex flex-col justify-center items-center">
                                <span class="text-[10px] sm:text-xs uppercase font-black text-gray-550 text-gray-500 block mb-1">🧖 Sauna</span>
                                <span class="text-base sm:text-lg font-black text-gray-900">Não</span>
                            </div>
                            <!-- 12. Playground -->
                            <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200/60 text-center flex flex-col justify-center items-center">
                                <span class="text-[10px] sm:text-xs uppercase font-black text-gray-550 text-gray-500 block mb-1">🛝 Playground</span>
                                <span class="text-base sm:text-lg font-black text-gray-900">Não</span>
                            </div>
                            <!-- 13. Estacionamento -->
                            <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200/60 text-center flex flex-col justify-center items-center">
                                <span class="text-[10px] sm:text-xs uppercase font-black text-gray-550 text-gray-500 block mb-1">🚗 Estacionamento</span>
                                <span class="text-base sm:text-lg font-black text-gray-900">{{ $imovel->garagens ? 'Sim' : 'Não' }}</span>
                            </div>
                            <!-- 14. Salão de Festa -->
                            <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200/60 text-center flex flex-col justify-center items-center">
                                <span class="text-[10px] sm:text-xs uppercase font-black text-gray-550 text-gray-500 block mb-1">🥳 Salão de Festa</span>
                                <span class="text-base sm:text-lg font-black text-gray-900">Não</span>
                            </div>
                            <!-- 15. Quadra esportiva -->
                            <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200/60 text-center flex flex-col justify-center items-center">
                                <span class="text-[10px] sm:text-xs uppercase font-black text-gray-550 text-gray-500 block mb-1">⚽ Quadra esportiva</span>
                                <span class="text-base sm:text-lg font-black text-gray-900">Não</span>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3 pt-2">
                            @if($imovel->link_edital)
                            <a href="{{ $imovel->link_edital }}" target="_blank" rel="noopener noreferrer"
                               class="flex-1 flex items-center justify-center gap-2 text-[#005CA9] border border-[#005CA9]/30 hover:bg-[#005CA9]/5 bg-white rounded-2xl py-3 transition-all text-sm font-extrabold shadow-sm">
                                🌐 <span>Ver no Site Oficial da Caixa</span>
                            </a>
                            @endif
                            @if($imovel->link_matricula)
                            <a href="{{ $imovel->link_matricula }}" target="_blank" rel="noopener noreferrer"
                               class="flex-1 flex items-center justify-center gap-2 text-emerald-700 border border-emerald-300/50 hover:bg-emerald-50 bg-white rounded-2xl py-3 transition-all text-sm font-extrabold shadow-sm">
                                📋 <span>Visualizar Matrícula (RGI)</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Formas de Pagamento e Regras de Despesas -->
                <div class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-lg border border-gray-100 space-y-6">
                    <h2 class="text-2xl font-black text-gray-900 tracking-tight flex items-center">
                        <span class="bg-[#005CA9] w-2.5 h-6 mr-3 rounded-full"></span>
                        💳 Formas de Pagamento Permitidas:
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-5 rounded-2xl border flex items-center justify-between shadow-sm bg-emerald-50 border-emerald-200">
                            <div>
                                <span class="font-extrabold text-sm text-emerald-700 block">Recursos Próprios: Sim</span>
                                <span class="text-xs text-emerald-700">Permitido para todos os imóveis (À vista)</span>
                            </div>
                            <span class="text-xl">✅</span>
                        </div>

                        <div class="p-5 rounded-2xl border flex items-center justify-between shadow-sm {{ $aceitaFgts ? 'bg-emerald-50 border-emerald-200' : 'bg-rose-50 border-rose-100 opacity-80' }}">
                            <div>
                                <span class="font-extrabold text-sm {{ $aceitaFgts ? 'text-emerald-700' : 'text-rose-700' }} block">Aceita FGTS: {{ $aceitaFgts ? 'Sim' : 'Não' }}</span>
                                <span class="text-xs {{ $aceitaFgts ? 'text-emerald-700' : 'text-rose-700' }}">{{ $aceitaFgts ? 'Utilize o saldo acumulado' : 'Não permite FGTS' }}</span>
                            </div>
                            <span class="text-xl">{{ $aceitaFgts ? '✅' : '❌' }}</span>
                        </div>

                        <div class="p-5 rounded-2xl border flex items-center justify-between shadow-sm {{ $aceitaFinanciamentoMcmv ? 'bg-emerald-50 border-emerald-200' : 'bg-rose-50 border-rose-100 opacity-80' }}">
                            <div>
                                <span class="font-extrabold text-sm {{ $aceitaFinanciamentoMcmv ? 'text-emerald-700' : 'text-rose-700' }} block">Aceita Financiamento MCMV: {{ $aceitaFinanciamentoMcmv ? 'Sim' : 'Não' }}</span>
                                <span class="text-xs {{ $aceitaFinanciamentoMcmv ? 'text-emerald-700' : 'text-rose-700' }}">{{ $aceitaFinanciamentoMcmv ? 'Permite taxas Minha Casa Minha Vida' : 'Não permite MCMV' }}</span>
                            </div>
                            <span class="text-xl">{{ $aceitaFinanciamentoMcmv ? '✅' : '❌' }}</span>
                        </div>

                        <div class="p-5 rounded-2xl border flex items-center justify-between shadow-sm {{ $aceitaFinanciamentoSbpe ? 'bg-emerald-50 border-emerald-200' : 'bg-rose-50 border-rose-100 opacity-80' }}">
                            <div>
                                <span class="font-extrabold text-sm {{ $aceitaFinanciamentoSbpe ? 'text-emerald-700' : 'text-rose-700' }} block">Aceita Financiamento SBPE: {{ $aceitaFinanciamentoSbpe ? 'Sim' : 'Não' }}</span>
                                <span class="text-xs {{ $aceitaFinanciamentoSbpe ? 'text-emerald-700' : 'text-rose-700' }}">{{ $aceitaFinanciamentoSbpe ? 'Permite carta de crédito SBPE' : 'Não permite SBPE' }}</span>
                            </div>
                            <span class="text-xl">{{ $aceitaFinanciamentoSbpe ? '✅' : '❌' }}</span>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200/60 text-xs text-gray-600 space-y-2 mt-4 leading-relaxed">
                        <p><strong>FORMAS DE PAGAMENTO ACEITAS:</strong> Exclusivamente de acordo com os critérios informados acima.</p>
                        <p><strong>REGRAS PARA PAGAMENTO DAS DESPESAS (caso existam):</strong></p>
                        <ul class="list-disc pl-4 space-y-1">
                            <li><strong>Condomínio:</strong> Sob responsabilidade do comprador, até o limite de 10% em relação ao valor de avaliação do imóvel. A CAIXA realizará o pagamento apenas do valor que exceder o limite de 10% do valor de avaliação.</li>
                            <li><strong>Tributos (IPTU/Taxas):</strong> Sob responsabilidade do comprador a partir da data de assinatura.</li>
                        </ul>
                        <p class="font-semibold text-orange-600">Existe área não averbada (caso informada na descrição original).</p>
                    </div>
                </div>

                <!-- Análise da Oportunidade (Todos começam 100% FECHADOS para atender o pedido do usuário!) -->
                <div class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-lg border border-gray-100 space-y-6"
                     x-data="{ openOportunidade: null }">
                    <h2 class="text-2xl font-black text-gray-900 tracking-tight flex items-center">
                        <span class="bg-[#F39200] w-2.5 h-6 mr-3 rounded-full"></span>
                        📈 Análise da Oportunidade
                    </h2>
                    
                    <p class="text-xs font-black text-orange-600 tracking-widest uppercase mb-2">
                        {{ $tipoNome }} em {{ $bairroNome }}, {{ $cidadeNome }} - {{ $uf }}
                    </p>

                    <p class="text-gray-650 leading-relaxed text-justify text-sm">
                        Hoje este Imóvel da Caixa está avaliado pela equipe técnica da CAIXA pelo preço de mercado de <strong class="text-gray-900">R$ {{ number_format($valorAvaliacao, 2, ',', '.') }}</strong> e está sendo vendido hoje pelo valor de <strong class="text-gray-900">R$ {{ number_format($valorVenda, 2, ',', '.') }}</strong> o que é uma <strong class="text-emerald-600 font-extrabold">grande oportunidade</strong> para o comprador que vai economizar o valor de <strong class="text-emerald-650 font-black">R$ {{ number_format($valorLucro, 2, ',', '.') }}</strong>. Este percentual de <strong class="text-red-600 font-black">{{ number_format($descontoPct, 0) }}%</strong> de desconto na compra deste imóvel só é possível, porque <strong class="text-orange-600">ESTE IMÓVEL NÃO ESTÁ EM LEILÃO</strong>, este imóvel está sendo vendido hoje nas modalidades de venda da CAIXA e por isso o banco consegue oferecer descontos que não seriam possíveis caso este imóvel ainda estivesse “preso” à Lei de Alienação Fiduciária.
                    </p>
                    
                    <p class="text-xs text-orange-600 font-black animate-pulse uppercase tracking-wider text-center pt-2">
                        👇 CLIQUE NAS OPÇÕES ABAIXO PARA VISUALIZAR MAIS INFORMAÇÕES
                    </p>

                    <div class="space-y-3">
                        <!-- Aba 1: Modalidades -->
                        <div class="border border-gray-200 rounded-2xl overflow-hidden">
                            <button @click="openOportunidade = openOportunidade === 1 ? null : 1"
                                    class="w-full px-6 py-4.5 flex justify-between items-center bg-gray-50 hover:bg-gray-100/80 text-left transition font-bold text-gray-800 text-sm">
                                <span class="flex items-center gap-2">📌 MODALIDADE X BENEFÍCIOS</span>
                                <span class="text-[#005CA9] text-xs transition-transform duration-300" :class="openOportunidade === 1 ? 'rotate-180' : ''">▼</span>
                            </button>
                            <div x-show="openOportunidade === 1" x-collapse class="px-6 py-5 bg-white border-t border-gray-100 text-xs text-gray-600 space-y-3 leading-relaxed">
                                <p>Os Imóveis Caixa são comercializados na sua grande maioria em diferentes modalidades de venda, porém as mais conhecidas são as modalidades de Leilão. O ouro está nas modalidades que a Caixa vende após o leilão onde ela consegue oferecer os maiores descontos e o comprador ainda está livre do pagamento da comissão do leiloeiro e a Caixa ainda pode pagar grande parte das dívidas de condomínio caso existam.</p>
                                <p>As Vantagens de comprar um Imóvel Caixa nas modalidades de Venda Direta ou Venda Direta Online são muitas e começamos com a economia de não ter que pagar a comissão do leiloeiro que neste caso seria de <strong class="text-emerald-600">R$ {{ number_format($valorVenda * 0.05, 2, ',', '.') }}</strong> e o não pagamento de comissão de corretagem, esses honorários seriam de <strong class="text-emerald-600">R$ {{ number_format($valorVenda * 0.05, 2, ',', '.') }}</strong> mas hoje, nestas modalidades de venda, o comprador não vai pagar nada.</p>
                            </div>
                        </div>

                        <!-- Aba 2: Valor de Mercado -->
                        <div class="border border-gray-200 rounded-2xl overflow-hidden">
                            <button @click="openOportunidade = openOportunidade === 2 ? null : 2"
                                    class="w-full px-6 py-4.5 flex justify-between items-center bg-gray-50 hover:bg-gray-100/80 text-left transition font-bold text-gray-800 text-sm">
                                <span class="flex items-center gap-2">📌 VALOR DE MERCADO</span>
                                <span class="text-[#005CA9] text-xs transition-transform duration-300" :class="openOportunidade === 2 ? 'rotate-180' : ''">▼</span>
                            </button>
                            <div x-show="openOportunidade === 2" x-collapse class="px-6 py-5 bg-white border-t border-gray-100 text-xs text-gray-600 space-y-3 leading-relaxed">
                                <p>Para validarmos esta informação nós fomos fazer uma pesquisa atualizada no mercado imobiliário utilizando as informações existentes em outros grandes portais de venda de imóveis usados. Usamos como base outros imóveis semelhantes a este imóvel que está sendo anunciado para que possamos garantir esta previsão de lucros. Então segue abaixo o resultado destas pesquisas:</p>
                                <p>Pelo portal <strong>Fipe zap</strong> as avaliações estão em torno de <strong class="text-gray-900">R$ {{ number_format($valFipe, 2, ',', '.') }}</strong>. Acessando o portal do <strong>Loft</strong> as avaliações estão com um preço aproximado de <strong class="text-gray-900">R$ {{ number_format($valLoft, 2, ',', '.') }}</strong>. No portal <strong>OLX</strong> encontramos avaliações pelo preço de <strong class="text-gray-900">R$ {{ number_format($valOlx, 2, ',', '.') }}</strong>. Já no portal do <strong>Quinto Andar</strong> as avaliações estão em torno de <strong class="text-gray-900">R$ {{ number_format($valQuinto, 2, ',', '.') }}</strong>. Com essa busca chegamos a um <strong>valor médio de mercado de R$ {{ number_format($valorAvaliacao, 2, ',', '.') }}</strong> e com este resultado fica mais fácil garantirmos nossa margem de lucro na compra deste imóvel.</p>
                            </div>
                        </div>

                        <!-- Aba 3: Comissão de Venda -->
                        <div class="border border-gray-200 rounded-2xl overflow-hidden">
                            <button @click="openOportunidade = openOportunidade === 3 ? null : 3"
                                    class="w-full px-6 py-4.5 flex justify-between items-center bg-gray-50 hover:bg-gray-100/80 text-left transition font-bold text-gray-800 text-sm">
                                <span class="flex items-center gap-2">📌 COMISSÃO DE VENDA</span>
                                <span class="text-[#005CA9] text-xs transition-transform duration-300" :class="openOportunidade === 3 ? 'rotate-180' : ''">▼</span>
                            </button>
                            <div x-show="openOportunidade === 3" x-collapse class="px-6 py-5 bg-white border-t border-gray-100 text-xs text-gray-600 space-y-2 leading-relaxed">
                                <p>Faça sua proposta de compra com a nossa imobiliária – <strong>Imóveis da Caixa LTDA CNPJ 50.563.863/0001-45 – CRECI-PJ 10.234/RJ</strong>, nós vamos te enviar um documento assegurando esta gratuidade do serviço prestado.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Análise do Comprador — Gated Content -->
                {{--
                    Equivalência arquitetural:
                    · x-data { isUnlocked }          → useLeadAccess hook
                    · <livewire:buyer-analysis-gate>  → LeadCaptureForm component
                    · @buyer-analysis-unlocked.window → unlockAccess() callback
                    · Lock overlay + accordions       → BuyerAnalysisSection parent
                --}}
                <div class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-lg border border-gray-100 space-y-6"
                     x-data="{
                         openPerfil: null,
                         isUnlocked: !!localStorage.getItem('hasUnlockedBuyerAnalysis')
                     }"
                     @buyer-analysis-unlocked.window="
                         localStorage.setItem('hasUnlockedBuyerAnalysis', 'true');
                         isUnlocked = true;
                     ">
                    <h2 class="text-2xl font-black text-gray-900 tracking-tight flex items-center">
                        <span class="bg-[#005CA9] w-2.5 h-6 mr-3 rounded-full"></span>
                        👤 Análise do Comprador
                    </h2>

                    <p class="text-xs font-black text-[#005CA9] tracking-widest uppercase mb-2">
                        {{ $tipoNome }} em {{ $bairroNome }}, {{ $cidadeNome }} - {{ $uf }}
                    </p>

                    <p class="text-gray-650 leading-relaxed text-justify text-sm">
                        Neste conteúdo estamos trazendo a maneira de se pensar de acordo com cada finalidade de compra do seu imóvel e para te ajudar nesta reflexão também vamos te dar uma visão geral das despesas, do lucro, e como nós fazemos estas formas distintas de processo de compra. Você terá acesso aos cálculos já prontos para que você tenha clareza do retorno financeiro que esta compra pode te trazer. São valores criados com base em experiências anteriores que servem para nos ajudar na tomada de decisão de compra.
                    </p>

                    {{-- Gate: form quando bloqueado / hint quando desbloqueado --}}
                    <div x-show="!isUnlocked" x-cloak>
                        <livewire:buyer-analysis-gate :imovel-id="$imovel->numero_original" />
                    </div>

                    <div x-show="isUnlocked" x-cloak
                         class="bg-emerald-50 border border-emerald-200 rounded-2xl px-5 py-3 flex items-center gap-3">
                        <span class="text-xl">✅</span>
                        <p class="text-xs font-black text-emerald-700 uppercase tracking-wide">Conteúdo desbloqueado! Clique nas opções abaixo.</p>
                    </div>

                    {{-- Accordions: bloqueados visualmente até unlock --}}
                    <div class="space-y-3 relative">

                        {{-- Overlay de bloqueio --}}
                        <div x-show="!isUnlocked" x-cloak
                             class="absolute inset-0 z-10 rounded-2xl flex flex-col items-center justify-center gap-3 py-8"
                             style="background: linear-gradient(to bottom, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.97) 40%);">
                            <span class="text-4xl mt-16">🔒</span>
                            <p class="text-sm font-black text-gray-500 text-center px-4">
                                Preencha o formulário acima para desbloquear os cálculos
                            </p>
                        </div>
                        <!-- Perfil 1: Comprar para Morar -->
                        <div class="border border-gray-200 rounded-2xl overflow-hidden">
                            <button @click="openPerfil = openPerfil === 1 ? null : 1"
                                    class="w-full px-6 py-5 flex justify-between items-center bg-gray-50 hover:bg-gray-100/80 text-left transition font-extrabold text-gray-800 text-base">
                                <span class="flex items-center gap-2.5">🏠 👤 COMPRAR PARA MORAR</span>
                                <span class="text-[#005CA9] text-xs transition-transform duration-300" :class="openPerfil === 1 ? 'rotate-180' : ''">▼</span>
                            </button>
                            <div x-show="openPerfil === 1" x-collapse class="px-6 py-6 bg-white border-t border-gray-100 text-xs text-gray-600 space-y-5 leading-relaxed">
                                <p><strong>Perfil do Comprador:</strong> O “Comprador Morador” é o cliente que descobriu no leilão de imóveis a oportunidade de ouro para elevar seu padrão de vida. Ele busca comprar um imóvel significativamente abaixo do valor de mercado, não para revender, mas para realizar o sonho da casa própria, fazer um upgrade de moradia (ir para um bairro melhor ou um imóvel maior) ou sair do aluguel.</p>
                                
                                <div class="space-y-2">
                                    <h4 class="font-extrabold text-gray-900 uppercase tracking-wider text-[10px]">🏠 01 – Comprar para Morar pagando barato</h4>
                                    <h5 class="font-extrabold text-gray-900 text-[10px] uppercase">📌 DESPESAS DE COMPRA:</h5>
                                    <ul class="list-disc pl-5 space-y-1.5">
                                        <li><strong>Despesas obrigatórias:</strong> quando o comprador faz a proposta de compra do Imóvel da Caixa ele se compromete a enviar uma cópia do Registro do Imóvel para dar baixa na compra do imóvel, esta despesa, deste imóvel, terá um custo aproximado de <strong class="text-gray-900">R$ {{ number_format($morarRegistro, 2, ',', '.') }}</strong> (R$ {{ number_format($valorAvaliacao, 2, ',', '.') }} x 5% do registro).</li>
                                        <li><strong>Despesas eventuais:</strong> Débitos de Condomínio: limite de <strong class="text-gray-900">R$ {{ number_format($morarCondominioMax, 2, ',', '.') }}</strong> (R$ {{ number_format($valorAvaliacao, 2, ',', '.') }} x 10%), o que passar deste valor a CAIXA faz a quitação das despesas que ainda existam. Para saber este valor, o comprador deverá entrar em contato com o condomínio e solicitar o extrato de débitos.</li>
                                        <li><strong>Débitos de Tributos:</strong> Para saber o débito de IPTU o comprador precisa pesquisar no site da prefeitura, através do número da inscrição do imóvel, para ver todas as despesas de tributos que ficam por conta do comprador.</li>
                                    </ul>
                                </div>

                                <div class="space-y-2">
                                    <h5 class="font-extrabold text-gray-900 text-[10px] uppercase">📌 DESPESAS QUE PODEM NÃO EXISTIR:</h5>
                                    <p>A grande maioria dos imóveis da Caixa estão ocupados, mas o processo de desocupação vem sendo simplificado, na maioria das vezes a desocupação é amigável. Em último caso pode ser que você tenha uma Custas de Desocupação deste imóvel que deve ficar em torno de <strong class="text-gray-900">R$ {{ number_format($morarDesocupacao, 2, ',', '.') }}</strong> (R$ {{ number_format($valorAvaliacao, 2, ',', '.') }} x 1.5% de desocupação) para iniciar um processo de reintegração de posse, por liminar que tem o prazo máximo de 60 dias, de acordo com a lei de alienação fiduciária.</p>
                                    <p>Uma das Despesas opcionais que ajudam muito as pessoas mais ocupadas é o investimento na contratação de um Despachante Imobiliário em torno de <strong class="text-gray-900">R$ {{ number_format($morarDespachante, 2, ',', '.') }}</strong> (R$ {{ number_format($valorAvaliacao, 2, ',', '.') }} x 0.5% despachante), ele vai agilizar todo o processo de registro e de quitação de dívidas se for o caso. Mas com nossa orientação o próprio comprador poderá fazer todo o processo, porém vai ter que investir tempo nestas tarefas.</p>
                                </div>

                                @if($aceitaFinanciamento)
                                <div class="bg-blue-50 border border-blue-200 p-5 rounded-2xl space-y-3">
                                    <h5 class="font-extrabold text-[#005CA9] text-[10px] uppercase">📌 COMPRA FINANCIADA (caso este imóvel aceite financiamento):</h5>
                                    <p class="text-gray-700"><strong>FINANCIAMENTO:</strong> Se este imóvel estivesse sendo vendido no mercado comum você teria que dar uma entrada de <strong class="text-gray-900">R$ {{ number_format($entradaNormal, 2, ',', '.') }}</strong> (R$ {{ number_format($valorAvaliacao, 2, ',', '.') }} x 20% entrada tradicional) mas como é um imóvel da Caixa, o valor de entrada fica em apenas <strong class="text-emerald-700 font-bold">R$ {{ number_format($entradaCaixa, 2, ',', '.') }}</strong> (R$ {{ number_format($valorVenda, 2, ',', '.') }} x 5% entrada Caixa). É uma redução de entrada de <strong class="text-emerald-700 font-black">R$ {{ number_format($reducaoEntrada, 2, ',', '.') }}</strong>!</p>
                                    <p class="text-gray-700">No Financiamento tradicional você pagaria uma prestação de <strong class="text-gray-400 line-through">R$ {{ number_format($prestacaoTradicional, 2, ',', '.') }}</strong> (R$ {{ number_format($valorAvaliacao, 2, ',', '.') }} x 0.8%); Mas por ser um Imóvel da Caixa você vai pagar uma prestação no valor de <strong class="text-emerald-700 font-bold">R$ {{ number_format($prestacaoCaixa, 2, ',', '.') }} /mês</strong> (R$ {{ number_format($valorVenda, 2, ',', '.') }} x 0.8% da prestação).</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Perfil 2: Comprar para Revender -->
                        <div class="border border-gray-200 rounded-2xl overflow-hidden">
                            <button @click="openPerfil = openPerfil === 2 ? null : 2"
                                    class="w-full px-6 py-5 flex justify-between items-center bg-gray-50 hover:bg-gray-100/80 text-left transition font-extrabold text-gray-800 text-base">
                                <span class="flex items-center gap-2.5">💲 👤 COMPRAR PARA REVENDER</span>
                                <span class="text-[#005CA9] text-xs transition-transform duration-300" :class="openPerfil === 2 ? 'rotate-180' : ''">▼</span>
                            </button>
                            <div x-show="openPerfil === 2" x-collapse class="px-6 py-6 bg-white border-t border-gray-100 text-xs text-gray-600 space-y-4 leading-relaxed">
                                <p><strong>Perfil Investidor de Giro Rápido (Volume e Liquidez):</strong> O “Investidor de Giro Rápido” ganha dinheiro na velocidade do capital. Ele compra no leilão com um bom deságio e, em vez de tentar vender pelo preço máximo de mercado (o que pode demorar meses), ele vende de 10% a 15% abaixo do mercado tradicional. O objetivo dele é que o imóvel dele seja a melhor oferta da rua no portal imobiliário, vendendo em tempo recorde para pegar o dinheiro e já arrematar o próximo. Ele busca volume de negócios.</p>
                                
                                <div class="bg-gray-50 p-4.5 rounded-xl border border-gray-200 space-y-1">
                                    <p class="text-gray-700">Este imóvel faz parte do Grupo <strong class="text-gray-900">{{ $imovel->grupo?->nome ?? 'Especial de Giro Rápido' }}</strong>, os imóveis deste grupo têm o seu valor de venda entre o valor mínimo de <strong class="text-gray-900">R$ {{ number_format($imovel->grupo?->valor_minimo ?? $valorVenda * 0.9, 2, ',', '.') }}</strong> e o valor máximo de <strong class="text-gray-900">R$ {{ number_format($imovel->grupo?->valor_maximo ?? $valorAvaliacao * 1.1, 2, ',', '.') }}</strong>.</p>
                                </div>

                                <div class="space-y-2">
                                    <h4 class="font-extrabold text-gray-900 uppercase tracking-wider text-[10px]">💲 Comprar para Vender com Lucro Rápido</h4>
                                    <h5 class="font-extrabold text-gray-900 text-[10px] uppercase">🚀 CÁLCULOS DE REVENDA (INVESTIDOR):</h5>
                                    <p>Não existe um valor exato a ser investido nesta operação, mas com base em casos anteriores, nós criamos uma tabela de valores que nos ajuda a ter uma estimativa bem precisa dos gastos e dos lucros que serão alcançados neste processo de compra e venda.</p>
                                    <ul class="list-disc pl-5 space-y-1.5 mt-2">
                                        <li><strong>Reforma de Manutenção:</strong> não devemos fazer grandes reformas, o objetivo é fazer uma pintura, consertar torneiras, disjuntores, lâmpadas, caixa de descarga, fechaduras, pisos que possam estar danificados, janelas quebradas. A meta é deixar o imóvel atraente, limpo e tudo funcionando. <strong class="text-gray-900">R$ {{ number_format($reforma, 2, ',', '.') }}</strong> (R$ {{ number_format($valorAvaliacao, 2, ',', '.') }} x 5% de reforma).</li>
                                        <li><strong>Prazo estimado para venda:</strong> {{ $mesesVenda }} meses.</li>
                                        <li><strong>Despesas durante o prazo de venda:</strong> Condomínio: <strong class="text-gray-900">R$ {{ number_format($condominioMes, 2, ',', '.') }}</strong> (R$ {{ number_format($valorAvaliacao, 2, ',', '.') }} x 0.1% condominio por 6 meses) | IPTU: <strong class="text-gray-900">R$ {{ number_format($iptuMes, 2, ',', '.') }}</strong> (R$ {{ number_format($valorAvaliacao, 2, ',', '.') }} x 0.05% impostos por 6 meses) | Água e Luz: <strong class="text-gray-900">R$ {{ number_format($aguaLuzMes, 2, ',', '.') }}</strong> (R$ {{ number_format($valorAvaliacao, 2, ',', '.') }} x agua/luz) | Fundo de Reserva: <strong class="text-gray-900">R$ {{ number_format($fundoReserva, 2, ',', '.') }}</strong> (R$ {{ number_format($valorAvaliacao, 2, ',', '.') }} x fundo reserva).</li>
                                        <li><strong>Despesas de Venda (Comissões e Anúncios):</strong> <strong class="text-gray-900">R$ {{ number_format($comissaoVenda, 2, ',', '.') }}</strong> (R$ {{ number_format($valorAvaliacao, 2, ',', '.') }} x 5% despesas).</li>
                                        <li><strong>Desconto de aceleração de venda:</strong> <strong class="text-gray-900">R$ {{ number_format($descontoAceleracao, 2, ',', '.') }}</strong> (R$ {{ number_format($valorAvaliacao, 2, ',', '.') }} x 10% aceleracao) (Ajuda a manter a venda rápida dentro do prazo sugerido).</li>
                                        <li><strong>Valor de Venda Sugerido:</strong> <strong class="text-gray-900">R$ {{ number_format($vendaSugerida, 2, ',', '.') }}</strong> (R$ {{ number_format($valorAvaliacao, 2, ',', '.') }} – (R$ {{ number_format($valorAvaliacao, 2, ',', '.') }} x 10% aceleracao)).</li>
                                    </ul>
                                </div>

                                <div class="p-5 bg-emerald-50 border border-emerald-200 rounded-2xl">
                                    <span class="text-[10px] font-black text-emerald-700 uppercase tracking-widest block mb-1">🚀 LUCRO PREVISTO NA OPERAÇÃO:</span>
                                    <span class="text-3xl font-black text-emerald-700 block">R$ {{ number_format($lucroPrevisto, 2, ',', '.') }}</span>
                                    <span class="text-[10px] text-gray-500 block pt-1">Fórmula: Valor Sugerido - (Valor de Venda Caixa + Reforma + Despesas Venda + Condomínio + IPTU no período).</span>
                                </div>
                            </div>
                        </div>

                        <!-- Perfil 3: Comprar para Alugar -->
                        <div class="border border-gray-200 rounded-2xl overflow-hidden">
                            <button @click="openPerfil = openPerfil === 3 ? null : 3"
                                    class="w-full px-6 py-5 flex justify-between items-center bg-gray-50 hover:bg-gray-100/80 text-left transition font-extrabold text-gray-800 text-base">
                                <span class="flex items-center gap-2.5">💰 👤 COMPRAR PARA ALUGAR</span>
                                <span class="text-[#005CA9] text-xs transition-transform duration-300" :class="openPerfil === 3 ? 'rotate-180' : ''">▼</span>
                            </button>
                            <div x-show="openPerfil === 3" x-collapse class="px-6 py-6 bg-white border-t border-gray-100 text-xs text-gray-600 space-y-4 leading-relaxed">
                                <p><strong>Perfil O Investidor de Renda (Focado em Aluguel / Yield):</strong> O “Investidor de Renda” vê o imóvel como uma “máquina de imprimir dinheiro” mensal. O objetivo principal dele não é revender para ter um lucro alto de uma vez só (como o perfil de Giro), mas sim construir um patrimônio sólido que pague dividendos constantes todos os meses. Ele usa o leilão como a ferramenta perfeita para “turbinar” essa rentabilidade, comprando barato para alugar pelo preço cheio de mercado.</p>
                                
                                <div class="space-y-3">
                                    <h4 class="font-extrabold text-gray-900 uppercase tracking-wider text-[10px]">💰 Comprar para Alugar com maior Lucro</h4>
                                    <h5 class="font-extrabold text-gray-900 text-[10px] uppercase">📌 CÁLCULOS DE LOCAÇÃO:</h5>
                                    <p>Se você comprasse este imóvel hoje no mercado tradicional, você investiria <strong class="text-gray-900">R$ {{ number_format($valorAvaliacao, 2, ',', '.') }}</strong> para ter um aluguel de <strong class="text-gray-900">R$ {{ number_format($aluguelEstimado, 2, ',', '.') }}</strong> (R$ {{ number_format($valorAvaliacao, 2, ',', '.') }} x 0.0047 de yield) (margem de 0,47% ao mês).</p>
                                    <p>But you only invested <strong class="text-emerald-750 font-bold">R$ {{ number_format($valorVenda, 2, ',', '.') }}</strong> to get the exact same market performance!</p>
                                    <p>Sendo assim, sua rentabilidade real saltou para mais de <strong class="text-emerald-700 font-black">{{ number_format($rentabilidadeReal, 2, ',', '.') }}% ao mês</strong> (([R$ {{ number_format($aluguelEstimado, 2, ',', '.') }}] / R$ {{ number_format($valorVenda, 2, ',', '.') }}) x 100)% ao mês.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nossos Serviços (Todos começam 100% FECHADOS!) -->
                <div class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-lg border border-gray-100 space-y-6"
                     x-data="{ openServico: null }">
                    <h2 class="text-2xl font-black text-gray-900 tracking-tight flex items-center">
                        <span class="bg-[#F39200] w-2.5 h-6 mr-3 rounded-full"></span>
                        💼 Nossos Serviços
                    </h2>
                    
                    <p class="text-xs font-black text-orange-600 tracking-widest uppercase mb-2">
                        {{ $tipoNome }} em {{ $bairroNome }}, {{ $cidadeNome }} - {{ $uf }}
                    </p>

                    <p class="text-xs text-orange-600 font-black animate-pulse uppercase tracking-wider text-center pt-2">
                        👇 CLIQUE NAS OPÇÕES ABAIXO PARA VISUALIZAR MAIS INFORMAÇÕES
                    </p>

                    <div class="space-y-3">
                        <!-- Sanfona 1: Assessoria 360 -->
                        <div class="border border-gray-200 rounded-2xl overflow-hidden">
                            <button @click="openServico = openServico === 1 ? null : 1"
                                    class="w-full px-6 py-4.5 flex justify-between items-center bg-gray-50 hover:bg-gray-100/80 text-left transition font-bold text-gray-800 text-sm">
                                <span class="flex items-center gap-2">🚀 ASSESSORIA 360º DE PONTA A PONTA</span>
                                <span class="text-[#005CA9] text-xs transition-transform duration-300" :class="openServico === 1 ? 'rotate-180' : ''">▼</span>
                            </button>
                            <div x-show="openServico === 1" x-collapse class="px-6 py-5 bg-white border-t border-gray-100 text-xs text-gray-600 space-y-4 leading-relaxed">
                                <h4 class="text-sm font-black text-gray-900">🚀 Assessoria 360º de Ponta a Ponta (Parceria de Resultados)</h4>
                                <p>Seu capital financeiro aliado à nossa inteligência operacional.</p>
                                <p><strong>O que é o serviço?</strong> A Assessoria 360º é um modelo exclusivo de parceria onde nós assumimos <strong>100% do trabalho operacional</strong> do investimento em leilão, enquanto você entra exclusivamente com o capital (ou crédito aprovado para financiamento).</p>
                                <p>Nosso objetivo é transformar o leilão de imóveis em um investimento totalmente passivo e livre de dores de cabeça para você. Nós cuidamos de absolutamente tudo, desde a busca da oportunidade até a venda final do imóvel, e só somos remunerados no final, dividindo o lucro da operação.</p>
                                
                                <h5 class="font-extrabold text-gray-900 pt-2">🛡️ Segurança e Transparência em Primeiro Lugar:</h5>
                                <ul class="list-disc pl-5 space-y-1.5">
                                    <li><strong>O Imóvel é Seu:</strong> Toda a documentação, o registro e a posse do imóvel ficam <strong>100% no seu nome</strong> desde o primeiro dia. Você tem controle e segurança patrimonial absoluta.</li>
                                    <li><strong>Alinhamento de Interesses:</strong> Como nossa remuneração é baseada na divisão dos lucros da venda, nosso maior interesse é comprar pelo menor preço, resolver tudo no menor tempo possível e vender com a maior margem de lucro. Nós só ganhamos se você ganhar.</li>
                                </ul>

                                <h5 class="font-extrabold text-gray-900 pt-2">⚙️ Como funciona a nossa Operação na Prática?</h5>
                                <ul class="list-decimal pl-5 space-y-1.5">
                                    <li><strong>Garimpo e Viabilidade Jurídica:</strong> Analisamos centenas de oportunidades para encontrar os imóveis mais lucrativos e fazemos toda a análise de risco e verificação de dívidas antes da compra.</li>
                                    <li><strong>Estratégia de Arrematação:</strong> Definimos o teto máximo de lance baseados em planilhas rigorosas de rentabilidade e representamos você no dia do leilão.</li>
                                    <li><strong>Burocracia e Documentação:</strong> Cuidamos de pagamentos de guias, ITBI, baixas de penhoras e o registro do imóvel no seu nome no cartório.</li>
                                    <li><strong>Desocupação Humanizada ou Jurídica:</strong> Lidamos diretamente com o ocupante. Negociamos acordos amigáveis para uma saída rápida e pacífica ou, se necessário, conduzimos a desocupação judicial com nossos advogados parceiros.</li>
                                    <li><strong>Reforma Estratégica (Home Staging):</strong> Assumimos a gestão da obra. Fazemos as reformas necessárias (pintura, reparos, iluminação) focadas exclusivamente em valorizar o imóvel gastando o mínimo possível, preparando-o para uma venda rápida.</li>
                                    <li><strong>Venda Acelerada:</strong> Colocamos o imóvel no mercado, gerando fotos profissionais, anúncios e conduzindo as visitas e negociações com os compradores finais.</li>
                                    <li><strong>Apuração e Divisão de Lucros:</strong> Com o imóvel vendido e o dinheiro na sua conta, apuramos todos os custos da operação. O capital investido retorna para você e, somente sobre o Lucro Líquido, fazemos a nossa divisão combinada.</li>
                                </ul>

                                <h5 class="font-extrabold text-gray-900 pt-2">🎯 Para quem é este serviço?</h5>
                                <ul class="list-disc pl-5 space-y-1.5">
                                    <li>Investidores que buscam a alta rentabilidade dos leilões, mas não têm tempo para procurar imóveis, lidar com obras ou negociar desocupações.</li>
                                    <li>Pessoas que têm capital disponível ou crédito imobiliário pré-aprovado, mas não têm o conhecimento técnico/jurídico para atuar sozinhas no mercado de leilões.</li>
                                    <li>Investidores que buscam diversificar seu portfólio de forma segura, mantendo o patrimônio no seu próprio nome.</li>
                                </ul>

                                <div class="bg-blue-50 p-4 rounded-xl border border-blue-200 italic text-gray-750 mt-3">
                                    “no mercado financeiro, quando você investe em um fundo, o gestor te cobra taxa de administração mesmo se o fundo der prejuízo. Na nossa Parceria 360º, o imóvel é garantido no seu nome, você não tem nenhum trabalho braçal e nós só colocamos a mão no dinheiro se entregarmos lucro. O seu único trabalho é aprovar o lance inicial e assinar a venda no final. O resto é com a minha equipe.”
                                </div>
                            </div>
                        </div>

                        <!-- Sanfona 2: Formacao de Consultores -->
                        <div class="border border-gray-200 rounded-2xl overflow-hidden">
                            <button @click="openServico = openServico === 2 ? null : 2"
                                    class="w-full px-6 py-4.5 flex justify-between items-center bg-gray-50 hover:bg-gray-100/80 text-left transition font-bold text-gray-800 text-sm">
                                <span class="flex items-center gap-2">🎓 FORMAÇÃO DE CONSULTORES EM LEILÕES</span>
                                <span class="text-[#005CA9] text-xs transition-transform duration-300" :class="openServico === 2 ? 'rotate-180' : ''">▼</span>
                            </button>
                            <div x-show="openServico === 2" x-collapse class="px-6 py-5 bg-white border-t border-gray-100 text-xs text-gray-600 space-y-4 leading-relaxed">
                                <h4 class="text-sm font-black text-gray-900">🎓 Formação de Consultores em Leilões de Imóveis (O Método 360º)</h4>
                                <p>Construa um negócio altamente lucrativo prestando assessoria para investidores.</p>
                                <p><strong>Sobre o Treinamento:</strong> Existe um oceano de pessoas com capital disponível — ou crédito imobiliário aprovado — que sonham com as altas margens do mercado de leilões, mas esbarram no medo, na burocracia e na falta de conhecimento.</p>
                                <p>Este treinamento pago é a sua formação completa para se tornar a “ponte” entre o dinheiro desses investidores e as melhores oportunidades do mercado. Nós vamos abrir a “caixa-preta” da nossa empresa e te ensinar o exato passo a passo que utilizamos todos os dias para arrematar e lucrar com imóveis.</p>
                                
                                <h5 class="font-extrabold text-gray-900 pt-2">🚀 O que você vai dominar:</h5>
                                <ul class="list-disc pl-5 space-y-1.5">
                                    <li><strong>Captar e Fechar com Investidores:</strong> Como encontrar pessoas com capital, apresentar o modelo de negócio (onde eles entram com o dinheiro e você com o trabalho) e fechar contratos de parceria com divisão de lucros.</li>
                                    <li><strong>Garimpo e Análise de Risco:</strong> Como ler editais, encontrar as verdadeiras “minas de ouro” ocultas e analisar a documentação para garantir que a compra seja 100% segura para o seu cliente.</li>
                                    <li><strong>A Estratégia de Arrematação:</strong> Como calcular o lance máximo, avaliar a rentabilidade (para giro ou aluguel) e vencer a disputa no leilão.</li>
                                    <li><strong>Operação e Desburocratização:</strong> O passo a passo jurídico e cartorário para passar o imóvel para o nome do investidor sem dores de cabeça.</li>
                                    <li><strong>Desocupação e Venda:</strong> Técnicas para desocupar o imóvel (amigável ou judicialmente), estratégias de reforma de baixo custo para valorização e o caminho mais rápido para vender o imóvel e colocar a sua parte do lucro no bolso.</li>
                                </ul>

                                <h5 class="font-extrabold text-gray-900 pt-2">🎯 Por que fazer este treinamento?</h5>
                                <p>Você não precisa ter centenas de milhares de reais para lucrar com leilões. O seu maior ativo será o <strong>conhecimento técnico</strong>. Você aprenderá a rentabilizar o capital de terceiros, construindo a sua própria carteira de clientes, estruturando o seu próprio negócio de assessoria e ganhando honorários ou participação expressiva nos lucros de cada operação validada por você.</p>
                                
                                <p class="font-bold text-orange-600">“Consultores em Leilões de Imóveis – uma nova profissão altamente rentável.”</p>

                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 italic text-gray-700">
                                    “Eu não estou te vendendo apenas um cursinho sobre leilões. Eu estou te entregando o modelo de negócios da minha empresa pronto para você replicar. Você vai sair daqui sabendo como captar o investidor, como achar o imóvel e como colocar dinheiro no bolso fazendo o trabalho que nós fazemos hoje, com total independência.”
                                </div>

                                <h5 class="font-black text-gray-900 pt-3 border-t border-gray-200 flex items-center gap-1.5">♾️ Os 7 segredos desta profissão:</h5>
                                <p class="italic text-gray-500 mt-1">“Você quer continuar preso no trânsito 2 horas por dia trabalhando para enriquecer o seu chefe, ou quer usar apenas um notebook para construir um networking com grandes investidores, sendo o dono do seu próprio tempo?”</p>
                                
                                <div class="space-y-3 pt-2">
                                    <p><strong>🌍 1. Liberdade Geográfica e de Tempo (Trabalhe de onde quiser):</strong> Hoje, mais de 90% dos leilões ocorrem de forma 100% online. Tudo o que ele precisa é de um notebook, internet e um celular. Ele pode analisar editais, dar lances e negociar com clientes da sala de casa, de um café ou até mesmo viajando.</p>
                                    <p><strong>🤝 2. Construção de um Networking de Alto Nível (Capital Social):</strong> Como consultor, o aluno vai lidar diariamente com pessoas que têm capital: empresários, médicos, diretores de empresas, investidores e advogados.</p>
                                    <p><strong>🧠 3. Autoridade e Prestígio Profissional:</strong> O consultor que domina esse mercado é visto como um especialista raro, um estrategista. Ele não é apenas um “vendedor de casas”, ele é um solucionador de problemas e um multiplicador de patrimônio.</p>
                                    <p><strong>📦 4. Negócio “Zero Estoque” (Sem dor de cabeça operacional):</strong> Diferente de quem abre uma loja física, o consultor não precisa comprar mercadoria ou lidar com fornecedores. É um modelo de negócio Asset-Light.</p>
                                    <p><strong>🧩 5. Estimulação Intelectual Constante (Adeus ao Tédio):</strong> Cada leilão é um “quebra-cabeça” único, que envolve direito, economia, mercado imobiliário e relações humanas.</p>
                                    <p><strong>🛡️ 6. Independência de “Chefes” e do Mercado de Trabalho Tradicional:</strong> O consultor não depende de currículos, ele cria a própria demanda. Em momentos de crise econômica, o número de leilões aumenta, trazendo resiliência.</p>
                                    <p><strong>♾️ 7. Mercado Inesgotável, “Oceano Azul” e de Rápida Formação:</strong> A fonte nunca seca; enquanto existir financiamento bancário existirá leilão. E você tem uma vantagem competitiva "injusta" comprando mais barato e vendendo rápido.</p>
                                </div>

                                <div class="bg-blue-50 p-4 rounded-xl border border-blue-200 italic text-gray-700 mt-3">
                                    “Hoje, você tem a chance de surfar uma onda que pouca gente conhece. Você não precisa passar 5 anos numa faculdade para ter uma profissão altamente lucrativa. Em poucas semanas aplicando o meu método, você já tem o conhecimento necessário para comprar imóveis mais baratos que qualquer um, vender rápido e ganhar dinheiro num mercado que nunca, jamais, vai deixar de existir.”
                                </div>

                                <h5 class="font-extrabold text-gray-900 pt-3 border-t border-gray-200 flex items-center gap-1.5">🤝 O Fim do Medo: Acompanhamento “Lado a Lado” na Primeira Operação:</h5>
                                <p>A maioria dos cursos te entrega um monte de vídeo-aulas teóricas e te joga aos leões. Aqui é diferente. Nós sabemos que a primeira arrematação é a que gera mais frio na barriga. Por isso, além de você receber todo o acesso às aulas em vídeo detalhando como tudo funciona, <strong>eu vou pegar na sua mão na sua primeira operação real</strong>.</p>
                                <ul class="list-disc pl-5 space-y-1.5">
                                    <li><strong>Como funciona:</strong> Eu não vou fazer o trabalho por você – afinal, você precisa aprender na prática para ser independente –, mas eu vou ser o seu co-piloto. Desde a escolha do primeiro imóvel, passando pela análise documental, até a estratégia de lance e, finalmente, a venda para colocar o lucro no bolso.</li>
                                    <li><strong>Você executa, eu oriento:</strong> Vou te explicar exatamente o que fazer em cada etapa. Você terá a tranquilidade de tomar as decisões sabendo que tem um especialista olhando por cima do seu ombro.</li>
                                    <li><strong>Aceleração de Confiança:</strong> Depois que você passar por essa primeira operação com o meu acompanhamento e ver o dinheiro entrando, o medo desaparece. Você estará pronto para repetir o processo sozinho.</li>
                                </ul>
                                
                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 italic text-gray-700">
                                    “Eu confio tanto no meu método que eu não vou te dar apenas as aulas gravadas e sumir. Na sua primeira arrematação, quando você captar o seu primeiro cliente, eu vou estar com você. Eu vou validar a sua análise, te orientar no lance e te guiar até o imóvel ser vendido. Qual a chance de dar errado quando você tem o mapa e o criador do mapa do seu lado?”
                                </div>
                            </div>
                        </div>

                        <!-- Sanfona 3: Treinamento Gratuito -->
                        <div class="border border-gray-200 rounded-2xl overflow-hidden">
                            <button @click="openServico = openServico === 3 ? null : 3"
                                    class="w-full px-6 py-4.5 flex justify-between items-center bg-gray-50 hover:bg-gray-100/80 text-left transition font-bold text-gray-800 text-sm">
                                <span class="flex items-center gap-2">🎁 TREINAMENTO GRATUITO</span>
                                <span class="text-[#005CA9] text-xs transition-transform duration-300" :class="openServico === 3 ? 'rotate-180' : ''">▼</span>
                            </button>
                            <div x-show="openServico === 3" x-collapse class="px-6 py-5 bg-white border-t border-gray-100 text-xs text-gray-600 space-y-3 leading-relaxed">
                                <h4 class="text-sm font-black text-gray-900">🏠 A Jornada da Casa Própria: Como Comprar Seu Imóvel em Leilão (Treinamento 100% Gratuito)</h4>
                                <p>Descubra o caminho seguro para comprar a casa da sua família pagando muito menos que o valor de mercado tradicional.</p>
                                <p><strong>Sobre o Treinamento:</strong> Sair do aluguel e conquistar a casa própria é o maior sonho da maioria dos brasileiros. Mas sabemos que, muitas vezes, os preços do mercado tradicional e as taxas de financiamento tornam esse sonho distante.</p>
                                <p>O que poucas pessoas sabem é que os leilões de imóveis não são apenas para “grandes investidores ricos”. Eles são, na verdade, a <strong>oportunidade perfeita para famílias</strong> que têm um orçamento mais apertado e querem fazer o seu dinheiro render muito mais na hora de comprar o lugar onde vão morar.</p>
                                
                                <h5 class="font-extrabold text-gray-900 pt-2">🎒 O que você vai aprender de forma simples e direta:</h5>
                                <p>Criamos um passo a passo pensado exatamente para quem quer comprar apenas um imóvel popular para morar com a família, com segurança e tranquilidade:</p>
                                <ul class="list-disc pl-5 space-y-1.5">
                                    <li><strong>Onde encontrar as oportunidades:</strong> Como pesquisar e achar casas e apartamentos no bairro que você deseja, por valores que cabem no seu bolso.</li>
                                    <li><strong>Entendendo as regras (O Bê-á-bá do Leilão):</strong> Como ler as informações do imóvel e entender exatamente o que você está comprando, sem pegadinhas.</li>
                                    <li><strong>Compra Segura:</strong> O que você precisa olhar para ter certeza de que está fazendo um negócio seguro para a sua família.</li>
                                    <li><strong>O Passo a Passo até a Chave na Mão:</strong> O que acontece depois que você ganha o leilão e como organizar a papelada para entrar na sua casa nova.</li>
                                </ul>

                                <h5 class="font-extrabold text-gray-900 pt-2">💙 Por que este treinamento é gratuito?</h5>
                                <p>Acreditamos que o conhecimento sobre leilões pode mudar a vida de muitas famílias. Nosso objetivo com este treinamento básico é democratizar o acesso a essas informações. Queremos tirar os seus medos e te mostrar que é possível, sim, comprar a sua casa própria pagando muito mais barato.</p>
                                <p>Você não precisa de milhares de reais para começar a aprender. O conhecimento está aqui, à sua disposição, sem custo nenhum.</p>
                                <p class="text-orange-600 font-extrabold">Nos chame no WhatsApp para fazer a sua inscrição gratuita!</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ 1: Perguntas Frequentes sobre os Imóveis Caixa (Todos começam 100% FECHADOS!) -->
                <div class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-lg border border-gray-100 space-y-6"
                     x-data="{ openFaq1: null }">
                    <h2 class="text-2xl font-black text-gray-900 tracking-tight flex items-center">
                        <span class="bg-[#005CA9] w-2.5 h-6 mr-3 rounded-full"></span>
                        ❓ FAQ 1 = Perguntas frequentes sobre os Imóveis Caixa
                    </h2>
                    
                    <p class="text-xs font-black text-[#005CA9] tracking-widest uppercase mb-2">
                        {{ $tipoNome }} em {{ $bairroNome }}, {{ $cidadeNome }} - {{ $uf }}
                    </p>

                    <p class="text-xs text-orange-600 font-black animate-pulse uppercase tracking-wider text-center pt-2">
                        👇 CLIQUE NAS OPÇÕES ABAIXO PARA VISUALIZAR MAIS INFORMAÇÕES
                    </p>

                    <div class="space-y-3">
                        <!-- Aba 1 -->
                        <div class="border border-gray-200 rounded-2xl overflow-hidden">
                            <button @click="openFaq1 = openFaq1 === 1 ? null : 1"
                                    class="w-full px-6 py-4.5 flex justify-between items-center bg-gray-50 hover:bg-gray-100/80 text-left transition font-bold text-gray-800 text-sm">
                                <span>📌 Imóveis da CAIXA e Alienação Fiduciária</span>
                                <span class="text-[#005CA9] text-xs transition-transform duration-300" :class="openFaq1 === 1 ? 'rotate-180' : ''">▼</span>
                            </button>
                            <div x-show="openFaq1 === 1" x-collapse class="px-6 py-5 bg-white border-t border-gray-100 text-xs text-gray-600 space-y-3 leading-relaxed">
                                <p>Os imóveis que não foram vendidos no leilão são chamados de imóveis adjudicados, ou seja, é decidido judicialmente que o imóvel pertence à Caixa e por isso estes imóveis são vendidos pelas modalidades de venda que a CAIXA preferir, cada modalidade tem suas características e por isso devemos prestar atenção em cada detalhe da modalidade escolhida para a venda do imóvel que você deseja comprar.</p>
                                <p>A origem dos imóveis da caixa está na cobrança da dívida de um financiamento imobiliário que não foi pago por quem realizou o financiamento bancário. Este procedimento é regulado pela lei de alienação fiduciária o que facilita todo o processo de venda e de tomada de posse.</p>
                                <p>Os imóveis da caixa já estiveram na posse de quem fez a compra financiada, mas quem sempre teve a propriedade do imóvel foi a Caixa. O imóvel só é do comprador depois que o financiamento é quitado. Como alguém já pagou parte do preço deste imóvel e o banco não tem o interesse de ficar com este patrimônio, o banco vende este imóvel com preços e condições muito atraentes.</p>
                                <p>Estes imóveis um dia já foram vendidos para pessoas que já pagaram uma parte do valor desse imóvel, pagaram uma entrada e algumas prestações, só não pagaram todas as prestações até o final; Por algum motivo deixaram de pagar o financiamento, por isso a CAIXA precisou retomar este imóvel para recuperar o valor da dívida deixada pelo comprador. Esse procedimento é realizado de acordo com a lei de alienação fiduciária e não está vinculado a nenhum processo judicial, aqui estamos falando em leilão extrajudicial e por isso é muito mais simples, prático e direto que os leilões judiciais.</p>
                                <p>Outro ponto forte é o grande número de ofertas que são lançadas diariamente na plataforma. Todos os dias a Caixa Econômica realiza novos financiamentos ao mesmo tempo que todos os dias novos imóveis vão sendo retomados e disponibilizados para venda, por isso todo dia o estoque de imóveis é atualizado. O segredo para garantir uma boa oportunidade é se preparar com antecedência para que quando a oferta apareça, você esteja pronto para fazer a sua proposta de compra.</p>
                            </div>
                        </div>

                        <!-- Aba 2 -->
                        <div class="border border-gray-200 rounded-2xl overflow-hidden">
                            <button @click="openFaq1 = openFaq1 === 2 ? null : 2"
                                    class="w-full px-6 py-4.5 flex justify-between items-center bg-gray-50 hover:bg-gray-100/80 text-left transition font-bold text-gray-800 text-sm">
                                <span>📌 Detalhes das Formas de Pagamento</span>
                                <span class="text-[#005CA9] text-xs transition-transform duration-300" :class="openFaq1 === 2 ? 'rotate-180' : ''">▼</span>
                            </button>
                            <div x-show="openFaq1 === 2" x-collapse class="px-6 py-5 bg-white border-t border-gray-100 text-xs text-gray-600 space-y-4 leading-relaxed">
                                <p><strong>Compra Exclusivamente à vista (recursos próprios):</strong> Tanto nos casos de pagamento à vista como nos pagamentos com FGTS e Financiamento, o Comprador vai fazer o pagamento total através de boleto bancário da Caixa no valor total do Preço de Venda, em até 3 dias. O seu boleto bancário será gerado automaticamente; Este boleto deverá ser pago em até 3 dias. Esta data de pagamento não pode ser alterada; O Comprador pode fazer o download do boleto no sistema da CAIXA, mas nós temos o hábito de enviar direto no WhatsApp do comprador que nós estamos atendendo.</p>
                                <p><strong>Compra com Recursos próprios e utilização de FGTS:</strong> O Comprador na hora de fazer sua Proposta de Compra vai informar o valor que tem disponível para saque do seu FGTS e vai fazer o pagamento do boleto bancário da Caixa no valor informado na Proposta de Compra em até 3 dias. O Comprador terá um prazo para quitação da parte do pagamento que será feita com recursos do FGTS. O comprador deverá fazer uma análise prévia dos valores que estão disponíveis para saque e deverá informar o valor que será sacado do FGTS na hora que estiver fazendo sua proposta de compra. É preciso que o comprador já tenha consultado suas condições e seu enquadramento em relação ao seu FGTS e saber qual o valor ele tem liberado para compra de imóvel e informar na proposta a parte que ele vai utilizar o FGTS. Mesmo que você tenha um valor de FGTS maior que o preço de venda, você sempre terá que pagar um mínimo de 5% em dinheiro pago por boleto e os 95% do valor você usa o seu FGTS.</p>
                                <p><strong>Compra com Financiamento:</strong> Se na ficha do imóvel do seu interesse *APARECE* esta opção é porque este imóvel aceita financiamento, *mas só faça sua proposta se souber que não tem pendências de crédito*; O comprador já deverá ter feito uma análise prévia de aprovação de crédito antes de fazer uma proposta de compra, este procedimento é orientado pela CAIXA; No envio da proposta o comprador vai informar o valor de entrada que será pago em boleto, com o valor mínimo de 5% de entrada mais o saldo do valor a ser pago em financiamento imobiliário da CAIXA. O Comprador vai fazer o pagamento no valor mínimo de 5% do preço de venda através de boleto bancário da Caixa, em até 3 dias. Esta data de pagamento não pode ser alterada e tem um prazo de até 7 dias para apresentar a liberação do financiamento. Como o prazo é curto, a Caixa indica que esteja pré-aprovado antes da realização da Proposta de Compra; Caso o valor aprovado de financiamento somado ao valor mínimo de entrada de 5% não atinja o valor da compra, o comprador deverá aumentar o valor de entrada até que a soma do valor financiado e o valor de entrada seja igual ao valor de venda. A CAIXA orienta que antes de fazer sua proposta, você já tenha uma análise prévia de aprovação de crédito, para saber até que valor você poderá financiar; Se na ficha do imóvel do seu interesse NÃO APARECE esta opção é porque este imóvel não permite financiamento. Se você só pode comprar imóveis que possam ser financiados, será preciso que continue buscando até encontrar outro imóvel que seja do seu agrado e que venha informando que aceita financiamento.</p>
                            </div>
                        </div>

                        <!-- Aba 3 -->
                        <div class="border border-gray-200 rounded-2xl overflow-hidden">
                            <button @click="openFaq1 = openFaq1 === 3 ? null : 3"
                                    class="w-full px-6 py-4.5 flex justify-between items-center bg-gray-50 hover:bg-gray-100/80 text-left transition font-bold text-gray-800 text-sm">
                                <span>📌 Gratuidade da Comissão de Venda e Serviços Adicionais</span>
                                <span class="text-[#005CA9] text-xs transition-transform duration-300" :class="openFaq1 === 3 ? 'rotate-180' : ''">▼</span>
                            </button>
                            <div x-show="openFaq1 === 3" x-collapse class="px-6 py-5 bg-white border-t border-gray-100 text-xs text-gray-600 space-y-3 leading-relaxed">
                                <p>Na comercialização dos Imóveis da Caixa você só paga comissão de venda para o leiloeiro, nas modalidades que existem o leiloeiro, o cliente não paga nenhuma comissão de venda para a imobiliária. A Caixa nos contratou para fornecer um serviço de assessoramento aos interessados em comprar seus imóveis, e por isso nós vamos te orientar no passo a passo da sua compra de forma <strong>inteiramente gratuita</strong>.</p>
                                <p><strong>Leia os Serviços Adicionais que a CAIXA não cobre (Todos opcionais):</strong> Todos os serviços abaixo são opcionais, o comprador pode contratar através da nossa imobiliária ou pode realizar por conta própria.</p>
                                <ul class="list-disc pl-5 space-y-1.5">
                                    <li><strong>Despachante Imobiliário:</strong> é o serviço realizado por profissionais parceiros que tem o objetivo de facilitar a vida dos compradores que possuem recursos financeiros para terceirizar toda a parte de registro do imóvel, viabilizando e agilizando todo o processo de compra do imóvel.</li>
                                    <li><strong>Desocupação Amigável:</strong> é o serviço de desocupação extrajudicial que segue um método próprio criado por nós para agilizar a desocupação e evitar a necessidade de um processo judicial. Este valor não envolve custas judiciais, honorários advocatícios nem custas de negociação ou outras despesas que se façam necessárias.</li>
                                    <li><strong>Desocupação Judicial:</strong> Este serviço é realizado por advogado especialista neste tipo de processo, são pouquíssimos os casos que precisam de uma ação judicial, e já foi comprovado ao longo do tempo que é preciso realizar uma análise mais profunda em cada caso para poder ser feito um processo de imissão de posse que atenda todos os detalhes de uma desocupação de sucesso, aqui não é só saber da lei, mas ser também muito atento aos detalhes.</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Aba 4 -->
                        <div class="border border-gray-200 rounded-2xl overflow-hidden">
                            <button @click="openFaq1 = openFaq1 === 4 ? null : 4"
                                    class="w-full px-6 py-4.5 flex justify-between items-center bg-gray-50 hover:bg-gray-100/80 text-left transition font-bold text-gray-800 text-sm">
                                <span>📌 Regras de Pagamento de Condomínio e IPTU</span>
                                <span class="text-[#005CA9] text-xs transition-transform duration-300" :class="openFaq1 === 4 ? 'rotate-180' : ''">▼</span>
                            </button>
                            <div x-show="openFaq1 === 4" x-collapse class="px-6 py-5 bg-white border-t border-gray-100 text-xs text-gray-600 space-y-1.5 leading-relaxed">
                                <p>A CAIXA só realiza o pagamento das dívidas de Condomínio e ou IPTU, depois que o imóvel foi comprado, respeitando as regras que estão na proposta de compra.</p>
                                <p>A CAIXA também não se responsabiliza por pagamentos de tributos e outras dívidas que surgirem, bem como as despesas de desocupação caso sejam necessárias.</p>
                                <p>A CAIXA só se responsabiliza pelos pagamentos que são informados dentro da Proposta de Compra.</p>
                                <p>Todas as custas de cartório, despachantes, diligências, e outras que se façam necessárias são de responsabilidade do comprador.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mapa do Google -->
                <div class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-lg border border-gray-100 space-y-4">
                    <h2 class="text-2xl font-black text-gray-900 tracking-tight flex items-center">
                        <span class="bg-[#005CA9] w-2.5 h-6 mr-3 rounded-full"></span>
                        🗺️ Localização: {{ $bairroNome }}, {{ $cidadeNome }} – {{ $uf }}
                    </h2>
                    <div class="rounded-2xl overflow-hidden border border-gray-200 shadow-sm">
                        <iframe
                            title="Mapa de localização: {{ $bairroNome }}, {{ $cidadeNome }} – {{ $uf }}"
                            width="100%"
                            height="350"
                            style="border:0;"
                            loading="lazy"
                            allowfullscreen
                            referrerpolicy="no-referrer-when-downgrade"
                            src="https://maps.google.com/maps?q={{ urlencode($bairroNome . ', ' . $cidadeNome . ', ' . $uf . ', Brasil') }}&output=embed&z=14">
                        </iframe>
                    </div>
                </div>

                <!-- FAQ 2: Perguntas Frequentes sobre o Bairro (Todos começam 100% FECHADOS!) -->
                <div class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-lg border border-gray-100 space-y-6"
                     x-data="{ openFaq2: null }">
                    <h2 class="text-2xl font-black text-gray-900 tracking-tight flex items-center">
                        <span class="bg-[#F39200] w-2.5 h-6 mr-3 rounded-full"></span>
                        ❓ FAQ 2 = Perguntas Frequentes sobre o Bairro
                    </h2>
                    
                    <p class="text-xs font-black text-orange-600 tracking-widest uppercase mb-2">
                        {{ $tipoNome }} em {{ $bairroNome }}, {{ $cidadeNome }} - {{ $uf }}
                    </p>

                    <p class="text-xs text-orange-600 font-black animate-pulse uppercase tracking-wider text-center pt-2">
                        👇 CLIQUE NAS OPÇÕES ABAIXO PARA VISUALIZAR MAIS INFORMAÇÕES
                    </p>

                    <div class="space-y-3">
                        @php
                        $faqItens = [
                            ['vizinhanca_localizacao', 'Vizinhança e Localização',  'Vizinhança tranquila e residencial, excelente localização.'],
                            ['beneficios',             'Benefícios',                'Proximidade com estabelecimentos comerciais, áreas verdes e praças.'],
                            ['acessos_transporte',     'Acessos e Transporte',      'Bairro bem conectado com linhas de ônibus e acesso a vias arteriais.'],
                            ['comercio_conveniencia',  'Comércio e Conveniência',   'Supermercados, farmácias e comércio local nas proximidades.'],
                            ['educacao',               'Educação',                  'Escolas municipais, estaduais e particulares nas proximidades.'],
                            ['saude',                  'Saúde',                     'Postos de saúde, UPAs e hospitais na região.'],
                            ['lazer_cultura',          'Lazer e Cultura',           'Praças, parques e opções de lazer ao ar livre.'],
                            ['dados_infraestrutura',   'Infraestrutura',            'Bairro com saneamento básico e boa iluminação pública.'],
                        ];
                        @endphp
                        @foreach($faqItens as $i => [$campo, $label, $fallback])
                            <div class="border border-gray-200 rounded-2xl overflow-hidden">
                                <button @click="openFaq2 = openFaq2 === {{ $i }} ? null : {{ $i }}"
                                        class="w-full px-6 py-4 flex justify-between items-center bg-gray-50 hover:bg-gray-100/80 text-left transition font-bold text-gray-800 text-sm">
                                    <span>🔖 {{ $label }}</span>
                                    <span class="text-[#005CA9] text-xs transition-transform duration-300" :class="openFaq2 === {{ $i }} ? 'rotate-180' : ''">▼</span>
                                </button>
                                <div x-show="openFaq2 === {{ $i }}" x-collapse class="px-6 py-5 bg-white border-t border-gray-100 text-sm text-gray-600 leading-relaxed">
                                    <p>{{ $conteudoIaBairro[$campo] ?? $fallback }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Histórico de Atualizações de Valores (Todos começam 100% FECHADOS!) -->
                <div class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-lg border border-gray-100 space-y-6"
                     x-data="{ openHistorico: false }">
                    <h2 class="text-2xl font-black text-gray-900 tracking-tight flex items-center">
                        <span class="bg-[#005CA9] w-2.5 h-6 mr-3 rounded-full"></span>
                        📅 Histórico de Atualizações
                    </h2>
                    
                    <p class="text-xs font-black text-[#005CA9] tracking-widest uppercase mb-2">
                        {{ $tipoNome }} em {{ $bairroNome }}, {{ $cidadeNome }} - {{ $uf }}
                    </p>

                    <button @click="openHistorico = !openHistorico"
                            class="w-full px-6 py-4 flex justify-between items-center bg-gray-50 hover:bg-gray-100/80 text-left transition font-bold text-gray-850 text-sm rounded-2xl border border-gray-200">
                        <span>Visualizar Lista Completa de Atualizações</span>
                        <span class="text-[#005CA9] text-xs transition-transform duration-300" :class="openHistorico ? 'rotate-180' : ''">▼</span>
                    </button>

                    <div x-show="openHistorico" x-collapse class="space-y-4 pt-2">
                        @if($imovel->historico->isNotEmpty())
                            @foreach($imovel->historico as $idx => $hist)
                                <div class="p-5 rounded-2xl border border-gray-200 bg-gray-50 space-y-3 text-xs">
                                    <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                                        <span class="font-extrabold text-gray-900">Atualização #{{ $idx + 1 }}</span>
                                        <span class="text-gray-500">Data: {{ $hist->created_at->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-gray-500">Modalidade:</p>
                                            <p class="font-bold text-gray-900">{{ $hist->modalidade?->nome ?? 'Venda Direta Especial' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Avaliação CAIXA:</p>
                                            <p class="font-bold text-gray-900">R$ {{ number_format($hist->valor_avaliacao, 2, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 pt-1">
                                        <div>
                                            <p class="text-gray-500">Preço de Venda:</p>
                                            <p class="font-bold text-[#005CA9]">R$ {{ number_format($hist->valor_venda, 2, ',', '.') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Economia:</p>
                                            <p class="font-bold text-emerald-605 font-extrabold text-emerald-650">R$ {{ number_format($hist->desconto_valor, 2, ',', '.') }} ({{ number_format($hist->desconto_percentual, 0) }}% OFF)</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="p-5 rounded-2xl border border-gray-200 bg-gray-50 text-center text-gray-500 text-xs">
                                Nenhuma alteração anterior de preço registrada. Valor atualizado e estável.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Imobiliária Credenciada Responsável pelo Atendimento -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-lg border border-gray-100 space-y-5">
                    <h2 class="text-xl font-black text-gray-900 tracking-tight flex items-center">
                        <span class="bg-[#005CA9] w-2.5 h-6 mr-3 rounded-full"></span>
                        🏢 Imobiliária Credenciada
                    </h2>
                    @if($resolvedImob)
                        <div class="flex gap-6 items-start">
                            {{-- Coluna esquerda: logo --}}
                            <div class="shrink-0">
                                @if($resolvedImob->logo_url)
                                    <img src="/storage/{{ $resolvedImob->logo_url }}" alt="Logo {{ $resolvedImob->nome }}"
                                         class="w-24 h-24 object-cover rounded-2xl border border-gray-200 bg-gray-50 shadow-sm">
                                @else
                                    <div class="w-24 h-24 rounded-2xl bg-[#005CA9]/10 flex items-center justify-center text-4xl">🏢</div>
                                @endif
                            </div>
                            {{-- Coluna direita: informações --}}
                            <div class="flex flex-col gap-1">
                                <p class="font-black text-gray-900 text-base leading-tight">{{ $resolvedImob->nome }}</p>
                                @if($resolvedImob->cnpj)
                                    <p class="text-sm text-gray-500">CNPJ: {{ $resolvedImob->cnpj }}</p>
                                @endif
                                @if($resolvedImob->creci)
                                    <p class="text-sm text-gray-500">CRECI: {{ $resolvedImob->creci }}</p>
                                @endif
                                <div class="mt-3">
                                    <span class="font-black text-gray-900 block text-xs uppercase tracking-wider">🕒 Horário de Atendimento</span>
                                    <p class="text-sm text-gray-600 leading-relaxed mt-0.5">
                                        {{ $resolvedImob->horario_atendimento ?? 'Segunda a Sexta-feira: 10:00 às 16:00' }}<br>
                                        Telefone / WhatsApp: {{ $resolvedImob->whatsapp ?? '(21) 99788-2950' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex gap-6 items-start">
                            {{-- Coluna esquerda: logo --}}
                            <div class="shrink-0">
                                <div class="w-24 h-24 rounded-2xl bg-[#005CA9]/10 flex items-center justify-center text-4xl">🏢</div>
                            </div>
                            {{-- Coluna direita: informações --}}
                            <div class="flex flex-col gap-1">
                                <p class="font-black text-gray-900 text-base leading-tight">Imóveis da Caixa LTDA</p>
                                <p class="text-sm text-gray-500">CNPJ: 50.563.863/0001-45</p>
                                <p class="text-sm text-gray-500">CRECI-PJ: 10.234/RJ</p>
                                <div class="mt-3">
                                    <span class="font-black text-gray-900 block text-xs uppercase tracking-wider">🕒 Horário de Atendimento</span>
                                    <p class="text-sm text-gray-600 leading-relaxed mt-0.5">
                                        Segunda a Sexta-feira: 10:00 às 16:00<br>
                                        Telefone / WhatsApp: (21) 99788-2950
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Acesso Restrito / Regras de SEO (Fim do arquivo) -->
                @auth
                <div class="bg-gray-100/60 p-6 rounded-2xl border border-gray-200 text-sm text-gray-600 space-y-3 mt-4 leading-relaxed">
                    <p class="font-black text-[#E50000] uppercase tracking-wider text-xs">🔒 ACESSO RESTRITO — Visível apenas para Gestores</p>

                    {{-- ── BLOCO 1: SEO ── --}}
                    <div class="space-y-4">
                        <p class="font-black text-gray-900 text-xs uppercase tracking-wider">🔍 Dados de SEO</p>

                        @php
                            $altDestaque   = 'venda ' . strtolower($tipoNome) . ' ' . strtolower($cidadeNome) . ' ' . strtolower($uf) . ' ' . $codigo;
                            $imgDestaque   = asset("images/imoveis/{$imovel->slug}.jpg");
                            $imgCaixa      = $imovel->foto_fachada_url ?? '— sem imagem —';
                        @endphp

                        {{-- Campos de texto --}}
                        @foreach([
                            'Slug'            => $imovel->slug,
                            'Palavra-chave'   => $tipoNome . ' em ' . $bairroNome . ', ' . $cidadeNome . ' - ' . $uf,
                            'Meta Descrição'  => $imovel->meta_description ?? 'Oportunidade de investimento Caixa Adjudicados.',
                            'Meta Título'     => $tipoNome . ' à venda em ' . $bairroNome . ', ' . $cidadeNome . ' - ' . $uf,
                        ] as $label => $valor)
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">{{ $label }}:</p>
                            <p class="text-xs text-gray-700 break-all mt-0.5">{{ $valor }}</p>
                        </div>
                        @endforeach

                        {{-- Imagem do Imóvel Caixa --}}
                        <div class="bg-white rounded-xl p-3 border border-gray-200 space-y-1">
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Imagem do Imóvel Caixa:</p>
                            <p class="text-xs text-[#005CA9] break-all">{{ $imgCaixa }}</p>
                            <p class="text-[10px] text-gray-500">Tag ALT: Fachada do {{ $tipoNome }} em {{ $bairroNome }}</p>
                            <p class="text-[10px] text-gray-500">Tag TITLE: {{ $imovel->slug }}</p>
                        </div>

                        {{-- Imagem Destaque (nossa) --}}
                        <div class="bg-white rounded-xl p-3 border border-gray-200 space-y-1">
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Imagem Destaque:</p>
                            <p class="text-xs text-[#005CA9] break-all">{{ $imgDestaque }}</p>
                            <p class="text-[10px] text-gray-500">Tag ALT da imagem destaque: {{ $altDestaque }}</p>
                        </div>
                    </div>

                    <hr class="border-gray-200">

                    {{-- ── BLOCO 2: RELATÓRIO DE DESEMPENHO ── --}}
                    <div>
                        <p class="font-black text-gray-900 text-xs uppercase tracking-wider mb-3">📊 Relatório de Desempenho</p>

                        {{-- Grade de métricas --}}
                        <div class="grid grid-cols-2 gap-3 text-xs">

                            {{-- Visibilidade --}}
                            <div class="col-span-2">
                                <p class="text-[10px] font-black uppercase tracking-widest text-[#005CA9] mb-1.5">👁️ Visibilidade</p>
                            </div>

                            <div class="bg-blue-50 rounded-xl p-3 border border-blue-100">
                                <p class="text-2xl font-black text-[#005CA9]">{{ number_format($stats['visitas']) }}</p>
                                <p class="font-bold text-gray-700 mt-0.5">Visitas Totais</p>
                                <p class="text-gray-500 mt-1 leading-relaxed">Número total de acessos a esta página desde o início do monitoramento.</p>
                            </div>

                            <div class="bg-gray-50 rounded-xl p-3 border border-gray-200">
                                <p class="text-2xl font-black text-gray-700">{{ $stats['diasNaPlataforma'] }}</p>
                                <p class="font-bold text-gray-700 mt-0.5">Dias na Plataforma</p>
                                <p class="text-gray-500 mt-1 leading-relaxed">Há quantos dias este imóvel está publicado. Listado em {{ $imovel->created_at->format('d/m/Y') }}.</p>
                            </div>

                            {{-- Conversões --}}
                            <div class="col-span-2 mt-1">
                                <p class="text-[10px] font-black uppercase tracking-widest text-[#005CA9] mb-1.5">🎯 Conversões</p>
                            </div>

                            <div class="bg-green-50 rounded-xl p-3 border border-green-100">
                                <p class="text-2xl font-black text-green-700">{{ number_format($stats['totalFormularios']) }}</p>
                                <p class="font-bold text-gray-700 mt-0.5">Formulários Enviados</p>
                                <p class="text-gray-500 mt-1 leading-relaxed">Total de leads que preencheram o formulário de interesse neste imóvel.</p>
                            </div>

                            <div class="bg-emerald-50 rounded-xl p-3 border border-emerald-100">
                                <p class="text-2xl font-black text-emerald-700">{{ number_format($stats['whatsappClicks']) }}</p>
                                <p class="font-bold text-gray-700 mt-0.5">Cliques no WhatsApp</p>
                                <p class="text-gray-500 mt-1 leading-relaxed">Quantas vezes o botão flutuante de WhatsApp foi acionado nesta página.</p>
                            </div>

                            <div class="bg-orange-50 rounded-xl p-3 border border-orange-100">
                                <p class="text-2xl font-black text-orange-600">{{ $stats['formUltimos7Dias'] }}</p>
                                <p class="font-bold text-gray-700 mt-0.5">Formulários (7 dias)</p>
                                <p class="text-gray-500 mt-1 leading-relaxed">Leads captados nos últimos 7 dias via formulário.</p>
                            </div>

                            <div class="bg-orange-50 rounded-xl p-3 border border-orange-100">
                                <p class="text-2xl font-black text-orange-600">{{ $stats['formUltimos30Dias'] }}</p>
                                <p class="font-bold text-gray-700 mt-0.5">Formulários (30 dias)</p>
                                <p class="text-gray-500 mt-1 leading-relaxed">Leads captados nos últimos 30 dias via formulário.</p>
                            </div>

                            <div class="bg-purple-50 rounded-xl p-3 border border-purple-100">
                                <p class="text-2xl font-black text-purple-700">{{ $stats['taxaConversao'] }}%</p>
                                <p class="font-bold text-gray-700 mt-0.5">Taxa de Conversão</p>
                                <p class="text-gray-500 mt-1 leading-relaxed">Percentual de visitantes que preencheram o formulário. (Formulários ÷ Visitas × 100).</p>
                            </div>

                            <div class="bg-teal-50 rounded-xl p-3 border border-teal-100">
                                <p class="text-2xl font-black text-teal-700">{{ number_format($stats['whatsappEnviados']) }}</p>
                                <p class="font-bold text-gray-700 mt-0.5">WhatsApp Enviados</p>
                                <p class="text-gray-500 mt-1 leading-relaxed">Formulários nos quais a notificação de WhatsApp foi disparada ao consultor.</p>
                            </div>

                            {{-- Histórico de Preço --}}
                            <div class="col-span-2 mt-1">
                                <p class="text-[10px] font-black uppercase tracking-widest text-[#005CA9] mb-1.5">💰 Histórico de Preço</p>
                            </div>

                            <div class="bg-gray-50 rounded-xl p-3 border border-gray-200">
                                <p class="text-2xl font-black text-gray-700">{{ $stats['totalAtualizacoes'] }}</p>
                                <p class="font-bold text-gray-700 mt-0.5">Atualizações de Preço</p>
                                <p class="text-gray-500 mt-1 leading-relaxed">Quantas vezes o valor de venda foi atualizado pela CAIXA desde o primeiro registro.</p>
                            </div>

                            <div class="bg-gray-50 rounded-xl p-3 border border-gray-200">
                                @php
                                    $varIcon  = $stats['variacaoPreco'] < 0 ? '📉' : ($stats['variacaoPreco'] > 0 ? '📈' : '—');
                                    $varColor = $stats['variacaoPreco'] < 0 ? 'text-green-600' : ($stats['variacaoPreco'] > 0 ? 'text-red-600' : 'text-gray-500');
                                @endphp
                                <p class="text-2xl font-black {{ $varColor }}">{{ $varIcon }} {{ $stats['variacaoPreco'] }}%</p>
                                <p class="font-bold text-gray-700 mt-0.5">Variação de Preço</p>
                                <p class="text-gray-500 mt-1 leading-relaxed">
                                    Inicial: R$ {{ number_format($stats['precoInicial'], 2, ',', '.') }}<br>
                                    Atual: R$ {{ number_format($stats['precoAtual'], 2, ',', '.') }}
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
                @endauth

            </div>

            {{-- SIDEBAR REMOVIDO: conteúdo migrado para coluna única --}}
            @if(false)
                <!-- Bloco de Lucro Imediato -->
                <div class="p-8 rounded-[2.5rem] shadow-lg text-center relative overflow-hidden" style="background-color: #F3F4F6; border: 1px solid #E5E7EB;">
                    <div class="space-y-4">
                        <!-- Line 1: LUCRO IMEDIATO -->
                        <span class="font-black uppercase tracking-widest px-4 py-1.5 rounded-full inline-block" style="color: #111827; border: 1px solid #D1D5DB; background-color: #E5E7EB; font-size: 13px;">
                            LUCRO IMEDIATO
                        </span>
                        
                        <!-- Line 2: SUA MARGEM ESTIMADA -->
                        <span class="block font-black uppercase tracking-wider text-sm" style="color: #374151;">
                            Sua Margem Estimada
                        </span>
                        
                        <!-- Line 3: R$ 565.227,20 (grandão em vermelho) -->
                        <span class="font-black block tracking-tight leading-none text-4xl" style="color: #E50000;">
                            R$ {{ number_format($valorLucro, 2, ',', '.') }}
                        </span>
                        
                        <!-- Line 4: De: R$ ... -->
                        <p class="font-bold text-sm" style="color: #4B5563;">
                            <strong>De:</strong> R$ {{ number_format($valorAvaliacao, 2, ',', '.') }}
                        </p>
                        
                        <!-- Line 5: Por Apenas: R$ ... -->
                        <p class="font-bold text-sm" style="color: #111827;">
                            <strong>Por Apenas:</strong> R$ {{ number_format($valorVenda, 2, ',', '.') }}
                        </p>
                    </div>
                </div>

                <!-- CTAs Rápidos e Úteis Extras -->
                <div class="space-y-3">
                    @if($imovel->link_edital)
                    <a href="{{ $imovel->link_edital }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="w-full flex items-center justify-center space-x-3 text-blue-650 border border-gray-200 hover:border-blue-500/30 hover:bg-gray-50 bg-white rounded-2xl py-4 transition-all duration-300 text-sm font-extrabold shadow-sm">
                        <span class="text-base leading-none">🌐</span>
                        <span>Ver no Site Oficial da Caixa</span>
                    </a>
                    @endif

                    <a href="{{ $imovel->link_matricula }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="w-full flex items-center justify-center space-x-3 text-emerald-650 border border-gray-200 hover:border-emerald-500/30 hover:bg-gray-50 bg-white rounded-2xl py-4 transition-all duration-300 text-sm font-extrabold shadow-sm">
                        <span class="text-base leading-none">📋</span>
                        <span>Visualizar Matrícula (RGI)</span>
                    </a>
                </div>

                <!-- Horário de Atendimento e CNPJ da Imobiliária -->
                <div class="p-6 bg-white border border-gray-100 rounded-2xl text-center space-y-4 text-xs text-gray-500 shadow-sm">
                    <div class="space-y-1">
                        <span class="font-black text-gray-900 block text-[9px] uppercase tracking-wider">🕒 HORÁRIO DE ATENDIMENTO</span>
                        <p class="leading-relaxed">
                            Segunda a Sexta-feira: 10:00 às 16:00<br>
                            Telefone / WhatsApp: (21) 99788-2950
                        </p>
                    </div>
                    <hr class="border-gray-100">
                    <p class="leading-relaxed italic">
                        Imóveis da Caixa LTDA<br>
                        CNPJ: 50.563.863/0001-45<br>
                        CRECI-PJ: 10.234/RJ
                    </p>
                </div>

            @endif

    <!-- Botão Flutuante Dinâmico (Parceiros por Estado ou Central) -->
    @php
        // $resolvedImob já computado no @php do topo
        $whatsappFone = $imobFone;
        $whatsappNome = $resolvedImob?->nome ?? 'Imóveis da Caixa';
        $whatsappImg  = $resolvedImob?->imagem_botao ?? null;
        $msgWhatsapp  = "🎯 Olá! Entrei no site *Imóveis da Caixa* e quero mais informações sobre o imóvel nº *{$imovel->numero_original}*.";
    @endphp
    <div class="fixed bottom-6 left-0 right-0 flex justify-center" style="position: fixed !important; bottom: 24px !important; left: 0 !important; right: 0 !important; z-index: 9999999 !important; pointer-events: none !important; display: flex !important; justify-content: center !important;">
        <div class="w-[240px] h-[80px] md:w-[360px] md:h-[120px]" style="pointer-events: auto !important; display: block !important;">
            <a href="{{ route('imovel.whatsapp-redirect', $imovel->id) }}"
               target="_blank"
               rel="noopener noreferrer"
               class="block group {{ $whatsappImg ? '' : 'rounded-3xl overflow-hidden' }} shadow-[0_15px_40px_rgba(0,0,0,0.2)] hover:shadow-[0_20px_45px_rgba(37,211,102,0.4)] hover:scale-105 active:scale-95 transition-all duration-300 ease-out w-full h-full"
               style="display: block !important; width: 100% !important; height: 100% !important;">
                @if($whatsappImg)
                    <!-- Botão de Imagem Personalizado da Imobiliária -->
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($whatsappImg) }}"
                         alt="Falar com {{ $whatsappNome }}"
                         class="transition-transform duration-300"
                         style="display: block !important; width: 100% !important; height: 100% !important; object-fit: fill !important; background: transparent !important; border: none !important;"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <!-- Fallback se imagem não carregar -->
                    <div class="w-full h-full bg-[#25D366] hover:bg-[#20BA5A] text-white items-center justify-center gap-3.5 px-6 transition-all duration-300"
                         style="display: none; width: 100% !important; height: 100% !important; border-radius: 1.5rem !important;">
                        <svg class="w-9 h-9 md:w-12 md:h-12 text-white shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.5-5.739-1.453L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.42 9.864-9.864.002-2.637-1.019-5.117-2.875-6.973-1.857-1.857-4.339-2.875-6.979-2.875-5.437 0-9.863 4.42-9.867 9.865-.001 1.623.424 3.21 1.233 4.613L1.95 22.05l4.697-1.896zm12.39-11.537c-.27-.135-1.602-.79-1.85-.88-.247-.09-.427-.135-.606.135-.18.27-.697.88-.854 1.06-.157.18-.314.202-.584.067-.27-.136-1.14-.42-2.172-1.34-1.03-.92-1.724-2.057-1.926-2.396-.202-.34-.022-.523.148-.692.153-.153.337-.393.506-.59.168-.196.224-.336.336-.56.113-.225.056-.42-.028-.584-.084-.165-.606-1.46-.83-2.004-.219-.526-.479-.452-.606-.459-.126-.007-.27-.008-.415-.008-.146 0-.382.055-.584.277-.202.22-.772.755-.772 1.84s.79 2.13 1.002 2.413c.213.283 1.547 2.363 3.75 3.315 2.203.952 2.203.635 2.6.598.397-.037 1.282-.525 1.462-1.03.18-.506.18-.94.126-1.03-.056-.09-.202-.135-.472-.27z"/>
                        </svg>
                        <div class="text-left leading-tight">
                            <span class="block text-xs font-medium opacity-90 uppercase tracking-wider md:text-sm">Falar com</span>
                            <span class="block text-lg font-black uppercase tracking-tight md:text-2xl">Corretor</span>
                        </div>
                    </div>
                @else
                    <!-- Botão Padrão de Alta Definição (Ampliado e Centrado) -->
                    <div class="w-full h-full bg-[#25D366] hover:bg-[#20BA5A] text-white flex items-center justify-center gap-3.5 px-6 transition-all duration-300"
                         style="display: flex !important; width: 100% !important; height: 100% !important; border-radius: 1.5rem !important;">
                        <svg class="w-9 h-9 md:w-12 md:h-12 text-white shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.5-5.739-1.453L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.42 9.864-9.864.002-2.637-1.019-5.117-2.875-6.973-1.857-1.857-4.339-2.875-6.979-2.875-5.437 0-9.863 4.42-9.867 9.865-.001 1.623.424 3.21 1.233 4.613L1.95 22.05l4.697-1.896zm12.39-11.537c-.27-.135-1.602-.79-1.85-.88-.247-.09-.427-.135-.606.135-.18.27-.697.88-.854 1.06-.157.18-.314.202-.584.067-.27-.136-1.14-.42-2.172-1.34-1.03-.92-1.724-2.057-1.926-2.396-.202-.34-.022-.523.148-.692.153-.153.337-.393.506-.59.168-.196.224-.336.336-.56.113-.225.056-.42-.028-.584-.084-.165-.606-1.46-.83-2.004-.219-.526-.479-.452-.606-.459-.126-.007-.27-.008-.415-.008-.146 0-.382.055-.584.277-.202.22-.772.755-.772 1.84s.79 2.13 1.002 2.413c.213.283 1.547 2.363 3.75 3.315 2.203.952 2.203.635 2.6.598.397-.037 1.282-.525 1.462-1.03.18-.506.18-.94.126-1.03-.056-.09-.202-.135-.472-.27z"/>
                        </svg>
                        <div class="text-left leading-tight">
                            <span class="block text-xs font-medium opacity-90 uppercase tracking-wider md:text-sm">Falar com</span>
                            <span class="block text-lg font-black uppercase tracking-tight md:text-2xl">Corretor</span>
                        </div>
                    </div>
                @endif
            </a>
        </div>
    </div>
</div>

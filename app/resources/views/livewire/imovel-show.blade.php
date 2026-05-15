<div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8 bg-white text-gray-800">
    <!-- Header: Título -->
    <div class="mb-6 text-center">
        <h1 class="text-3xl font-bold italic text-gray-900">
            {{ $imovel['post_titulo'] ?? '[post_titulo]' }}
        </h1>
    </div>

    <!-- Imagem Única -->
    <div class="mb-8 rounded overflow-hidden shadow-lg border border-gray-200">
        <img src="https://via.placeholder.com/800x400?text=Imagem+do+Imovel" alt="Imagem do Imóvel" class="w-full h-auto object-cover">
    </div>

    <!-- Box de Preços (Price Table) -->
    <div class="mb-8 border border-gray-200 rounded-lg shadow-sm bg-white overflow-hidden text-center relative">
        <div class="bg-gray-100 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">{{ $imovel['post_palavra_chave'] ?? '[post_palavra_chave]' }}</h2>
        </div>
        <div class="absolute top-4 right-0 bg-[#046bd2] text-white font-bold py-1 px-4 rounded-l text-sm">
            {{ $imovel['selo_oportunidade'] ?? '[selo_oportunidade]' }}
        </div>
        
        <div class="py-6 bg-white">
            <div class="flex items-center justify-center text-4xl font-extrabold text-gray-900 mb-4">
                <span class="text-xl font-semibold mr-1 mt-1">R$</span>
                <span>{{ $imovel['atualizacao_desconto_valor'] ?? '[atualizacao_desconto_valor]' }}</span>
            </div>
            
            <ul class="text-gray-600 text-sm space-y-4 max-w-xs mx-auto text-left mb-6">
                <li class="flex items-center border-t border-gray-100 pt-3">
                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="font-bold text-gray-800">Lucro Imediato</span>
                </li>
                <li class="flex items-center border-t border-gray-100 pt-3">
                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Valor de Avaliação: {{ $imovel['atualizacao_avaliacao'] ?? '[atualizacao_avaliacao]' }}</span>
                </li>
                <li class="flex items-center border-t border-gray-100 pt-3">
                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Valor de Venda: {{ $imovel['atualizacao_venda'] ?? '[atualizacao_venda]' }}</span>
                </li>
                <li class="flex items-center border-t border-gray-100 pt-3 border-b pb-3">
                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Desconto: {{ $imovel['atualizacao_desconto_percentual'] ?? '[atualizacao_desconto_percentual]' }}%</span>
                </li>
            </ul>
            
            <a href="https://api.whatsapp.com/send/?phone=5521997882950&text=Olá" target="_blank" class="inline-block px-8 py-3 text-sm font-bold bg-[#32373c] text-white rounded-full hover:bg-[#045cb4] transition-colors">
                Iniciar Conversa
            </a>
        </div>
    </div>

    <!-- Informações de Texto (Exatamente como na referência) -->
    <div class="mb-8 prose max-w-none text-gray-800">
        <h2 class="text-2xl mb-4 font-bold"><span class="text-[#ff6600]">{{ $imovel['post_palavra_chave'] ?? '[post_palavra_chave]' }}</span></h2>
        
        <h3 class="text-xl font-bold mt-6 mb-2">Endereço:</h3>
        <p class="mb-4">
            {{ $imovel['imovel_caixa_endereco_original'] ?? '[imovel_caixa_endereco_original]' }}<br>
            {{ $imovel['imovel_caixa_endereco_bairro'] ?? '[imovel_caixa_endereco_bairro]' }}, {{ $imovel['imovel_caixa_endereco_cidade'] ?? '[imovel_caixa_endereco_cidade]' }} – {{ $imovel['imovel_caixa_endereco_uf'] ?? '[imovel_caixa_endereco_uf ]' }}
        </p>
        
        <h3 class="text-xl font-bold mt-6 mb-2">Descrição:</h3>
        <p class="mb-4">{{ $imovel['imovel_caixa_descricao_original'] ?? '[imovel_caixa_descricao_original]' }}</p>
        
        <h3 class="text-xl font-bold mt-6 mb-2">Formas de Pagamento:</h3>
        <ul class="list-disc pl-5 mb-4 space-y-1">
            <li><em><strong>Recursos Próprios:</strong></em> {{ $imovel['atualizacao_recurso_proprio'] ?? '[atualizacao_recurso_proprio]' }}</li>
            <li><strong><em>Aceita FGTS:</em></strong> {{ $imovel['atualizacao_fgts'] ?? '[atualizacao_fgts]' }}</li>
            <li><strong>Aceita Financiamento MCMV:</strong> {{ $imovel['atualizacao_financiamento_mcmv'] ?? '[atualizacao_financiamento_mcmv]' }}</li>
            <li><strong>Aceita Financiamento SBPE:</strong> {{ $imovel['atualizacao_financiamento_sbpe'] ?? '[atualizacao_financiamento_sbpe]' }}</li>
        </ul>
        
        <h3 class="text-xl font-bold mt-6 mb-2">Regras para pagamento das despesas:</h3>
        <ul class="list-disc pl-5 mb-4 space-y-1">
            <li><strong><em>Débitos de Condomínio:</em></strong> A CAIXA quita as dívidas de condomínio se existirem, é de responsabilidade do comprador pagar até o limite de 10% do valor de avaliação, o que exceder a este valor é pago pela CAIXA.</li>
            <li><em><strong>Débitos de Tributos (IPTU/Taxa do lixo):</strong></em> Esta dívida fica por conta do comprador e o valor se existir poderá ser verificado no site da prefeitura.</li>
            <li><em><strong>Existe área não averbada.</strong></em></li>
        </ul>
        
        <h3 class="text-xl font-bold mt-6 mb-2">Dados de Cartório e Matrícula:</h3>
        <ul class="list-disc pl-5 mb-4 space-y-1">
            <li><span style="color: #ff6600;"><strong>Número do Imóvel:</strong> {{ $imovel['imovel_caixa_numero'] ?? '[imovel_caixa_numero]' }}</span></li>
            <li><strong>Comarca:</strong> {{ $imovel['cartorio_comarca'] ?? '[cartorio_comarca]' }}</li>
            <li><strong>Ofício:</strong> {{ $imovel['cartorio_oficio'] ?? '[cartorio_oficio]' }}</li>
            <li><strong>Matrícula:</strong> {{ $imovel['cartorio_matricula'] ?? '[cartorio_matricula]' }}</li>
            <li><strong>Inscrição Imobiliária (IPTU):</strong> {{ $imovel['cartorio_inscricao_imobiliaria'] ?? '[cartorio_inscricao_imobiliaria]' }}</li>
            <li><strong>Averbação dos leilões negativos:</strong> {{ $imovel['cartorio_averbacao'] ?? '[cartorio_averbacao]' }}</li>
        </ul>
    </div>

    <!-- SANFONAS (Accordions) -->
    <div class="mb-8 space-y-2">
        <h2 class="text-2xl font-bold mb-4">Análise do Comprador</h2>
        
        <!-- Accordion 1 -->
        <div x-data="{ open: false }" class="border border-gray-200 rounded">
            <button @click="open = !open" class="w-full px-4 py-3 text-left font-bold text-gray-800 bg-gray-50 hover:bg-gray-100 flex justify-between items-center focus:outline-none">
                <span class="flex items-center"><span class="mr-2 text-xl">👤</span> COMPRAR PARA MORAR 🏠</span>
                <span x-show="!open" class="text-gray-400">+</span>
                <span x-show="open" class="text-gray-400">-</span>
            </button>
            <div x-show="open" x-collapse x-cloak class="px-4 py-3 text-gray-600 text-sm bg-white border-t border-gray-200">
                <p>O “Comprador Morador” é o cliente que descobriu no leilão de imóveis a oportunidade de ouro para elevar seu padrão de vida. Ele busca comprar um imóvel significativamente abaixo do valor de mercado, não para revender, mas para realizar o sonho da casa própria...</p>
            </div>
        </div>

        <!-- Accordion 2 -->
        <div x-data="{ open: false }" class="border border-gray-200 rounded">
            <button @click="open = !open" class="w-full px-4 py-3 text-left font-bold text-gray-800 bg-gray-50 hover:bg-gray-100 flex justify-between items-center focus:outline-none">
                <span class="flex items-center"><span class="mr-2 text-xl">📌</span> DESPESAS DE COMPRA:</span>
                <span x-show="!open" class="text-gray-400">+</span>
                <span x-show="open" class="text-gray-400">-</span>
            </button>
            <div x-show="open" x-collapse x-cloak class="px-4 py-3 text-gray-600 text-sm bg-white border-t border-gray-200">
                <ul class="list-disc pl-5 space-y-1">
                    <li><strong>Despesas obrigatórias:</strong> Registro do Imóvel: R$ ( [imovel_caixa_valor_avaliacao] x [compra_registro] )</li>
                    <li><strong>Despesas eventuais:</strong> Débitos de Condomínio: R$ ( [imovel_caixa_valor_avaliacao] x [10%] ) - O excedente a CAIXA quita.</li>
                    <li><strong>Débitos de Tributos:</strong> Verificável no site da prefeitura. Fica por conta do comprador.</li>
                </ul>
            </div>
        </div>

        <!-- Accordion 3 -->
        <div x-data="{ open: false }" class="border border-gray-200 rounded">
            <button @click="open = !open" class="w-full px-4 py-3 text-left font-bold text-gray-800 bg-gray-50 hover:bg-gray-100 flex justify-between items-center focus:outline-none">
                <span class="flex items-center"><span class="mr-2 text-xl">📌</span> COMPRA FINANCIADA</span>
                <span x-show="!open" class="text-gray-400">+</span>
                <span x-show="open" class="text-gray-400">-</span>
            </button>
            <div x-show="open" x-collapse x-cloak class="px-4 py-3 text-gray-600 text-sm bg-white border-t border-gray-200">
                <p class="text-red-600 font-bold mb-2">(caso este imóvel aceite financiamento)</p>
                <p>Entrada Mercado Comum: R$([imovel_caixa_valor_avaliacao] x [compra_financiamento_entrada_normal])</p>
                <p class="font-bold text-green-700">Entrada Imóvel da Caixa: R$([imovel_caixa_valor_venda] x [compra_financiamento_entrada_caixa])</p>
                <p>Prestação Tradicional: R$([imovel_caixa_valor_avaliacao] x [compra_financiamento_prestacao])</p>
                <p class="font-bold text-green-700">Prestação Caixa: R$([imovel_caixa_valor_venda] x [compra_financiamento_prestacao])</p>
            </div>
        </div>
        
        <!-- Accordion 4 -->
        <div x-data="{ open: false }" class="border border-gray-200 rounded">
            <button @click="open = !open" class="w-full px-4 py-3 text-left font-bold text-gray-800 bg-gray-50 hover:bg-gray-100 flex justify-between items-center focus:outline-none">
                <span class="flex items-center"><span class="mr-2 text-xl">👤</span> COMPRAR PARA REVENDER 💲</span>
                <span x-show="!open" class="text-gray-400">+</span>
                <span x-show="open" class="text-gray-400">-</span>
            </button>
            <div x-show="open" x-collapse x-cloak class="px-4 py-3 text-gray-600 text-sm bg-white border-t border-gray-200">
                <p>O “Investidor de Giro Rápido” ganha dinheiro na velocidade do capital. Ele compra no leilão com um bom deságio e vende rápido abaixo do mercado.</p>
            </div>
        </div>

        <!-- Accordion 5 -->
        <div x-data="{ open: false }" class="border border-gray-200 rounded">
            <button @click="open = !open" class="w-full px-4 py-3 text-left font-bold text-gray-800 bg-gray-50 hover:bg-gray-100 flex justify-between items-center focus:outline-none">
                <span class="flex items-center"><span class="mr-2 text-xl">📌</span> CÁLCULOS DE REVENDA 🚀</span>
                <span x-show="!open" class="text-gray-400">+</span>
                <span x-show="open" class="text-gray-400">-</span>
            </button>
            <div x-show="open" x-collapse x-cloak class="px-4 py-3 text-gray-600 text-sm bg-white border-t border-gray-200">
                <p class="mb-2"><strong>Grupo:</strong> {{ $imovel['grupo_nome'] ?? '[grupo_nome]' }} (Valor mín: {{ $imovel['grupo_valor_minimo'] ?? '[grupo_valor_minimo]' }}, máx: {{ $imovel['grupo_valor_maximo'] ?? '[grupo_valor_maximo]' }})</p>
                <ul class="list-disc pl-5 space-y-1 mb-2">
                    <li><strong>Reforma Manutenção:</strong> R$([imovel_caixa_valor_avaliacao] x [revenda_reforma])</li>
                    <li><strong>Prazo estimado:</strong> {{ $imovel['revenda_tempo_meses'] ?? '[revenda_tempo_meses]' }} meses</li>
                    <li><strong>Despesas de Venda (Comissões):</strong> R$([imovel_caixa_valor_avaliacao] x [revenda_despesas])</li>
                    <li><strong>Desconto de Aceleração:</strong> R$([imovel_caixa_valor_avaliacao] x [revenda_aceleracao])</li>
                </ul>
                <p class="font-bold">Valor Sugerido de Venda: R$([imovel_caixa_valor_avaliacao] - ([imovel_caixa_valor_avaliacao] x [revenda_aceleracao]))</p>
            </div>
        </div>

        <!-- Accordion 6 -->
        <div x-data="{ open: false }" class="border border-gray-200 rounded">
            <button @click="open = !open" class="w-full px-4 py-3 text-left font-bold text-gray-800 bg-gray-50 hover:bg-gray-100 flex justify-between items-center focus:outline-none">
                <span class="flex items-center"><span class="mr-2 text-xl">👤</span> COMPRAR PARA ALUGAR 💰 (LOCAÇÃO)</span>
                <span x-show="!open" class="text-gray-400">+</span>
                <span x-show="open" class="text-gray-400">-</span>
            </button>
            <div x-show="open" x-collapse x-cloak class="px-4 py-3 text-gray-600 text-sm bg-white border-t border-gray-200">
                <p>O “Investidor de Renda” vê o imóvel como uma “máquina de imprimir dinheiro” mensal.</p>
                <p class="font-bold text-green-700 mt-2">Rentabilidade de Locação Calculada: Mais de ([([imovel_caixa_valor_avaliacao] x 0.0047) / [imovel_caixa_valor_venda]] x 100)% ao mês.</p>
            </div>
        </div>
    </div>
</div>

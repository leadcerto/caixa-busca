<?php

namespace App\Http\Controllers;

use App\Models\Bairro;
use App\Models\Estado;
use App\Models\Imovel;
use App\Models\Municipio;
use App\Models\TipoImovel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BuscaImovelController extends Controller
{
    public function semTipo(
        Request $request,
        string  $estado,
        ?string $cidade = null,
        ?string $bairro = null,
    ) {
        return $this->buildResults($request, null, $estado, $cidade, $bairro);
    }

    public function index(
        Request $request,
        string  $tipo,
        string  $estado,
        ?string $cidade = null,
        ?string $bairro = null,
    ) {
        return $this->buildResults($request, $tipo, $estado, $cidade, $bairro);
    }

    public function comFinanciamento(
        Request $request,
        string  $estado,
        string  $cidade,
        ?string $bairro = null,
    ) {
        return $this->buildResults($request, null, $estado, $cidade, $bairro, 'financiamento');
    }

    public function comDesconto70(
        Request $request,
        string  $estado,
        string  $cidade,
        ?string $bairro = null,
    ) {
        return $this->buildResults($request, null, $estado, $cidade, $bairro, 'desconto70');
    }

    private function buildResults(
        Request $request,
        ?string $tipo,
        string  $estado,
        ?string $cidade,
        ?string $bairro,
        ?string $filtroEspecial = null,
    ) {
        // ── 1. Resolve entidades a partir dos slugs da URL (com cache 1h) ────
        $tipoObj = null;
        if ($tipo) {
            $tipos = collect(Cache::remember('dropdown_tipos_imoveis', 86400, fn () =>
                TipoImovel::where('ativo', true)->get(['id', 'nome'])->toArray()
            ))->map(fn ($t) => (object) $t);
            $tipoObj = $tipos->first(fn ($t) => Str::slug($t->nome) === $tipo);
            if (! $tipoObj) abort(404);
        }

        $estadoObj = (object) (Cache::remember("estado_uf_{$estado}", 86400, fn () =>
            Estado::where('uf', strtoupper($estado))->first()?->toArray()
        ) ?? abort(404));

        $municipioObj = null;
        if ($cidade) {
            $municipios = collect(Cache::remember("municipios_estado_{$estadoObj->id}", 3600, fn () =>
                Municipio::where('id_estado', $estadoObj->id)->get(['id', 'nome'])->toArray()
            ))->map(fn ($m) => (object) $m);
            $municipioObj = $municipios->first(fn ($m) => Str::slug($m->nome) === $cidade);
            if (! $municipioObj) abort(404);
        }

        $bairroObj = null;
        if ($bairro && $municipioObj) {
            $bairros = collect(Cache::remember("bairros_municipio_{$municipioObj->id}", 3600, fn () =>
                Bairro::where('id_municipio', $municipioObj->id)->get(['id', 'nome'])->toArray()
            ))->map(fn ($b) => (object) $b);
            $bairroObj = $bairros->first(fn ($b) => Str::slug($b->nome) === $bairro);
            if (! $bairroObj) abort(404);
        }

        // ── 2. Monta query base com JOIN no último histórico ──────────────────
        $query = Imovel::query()
            ->select('imoveis.*')
            ->joinSub(
                DB::table('imoveis_historico')
                    ->select('id_imovel', DB::raw('MAX(id) as latest_id'))
                    ->groupBy('id_imovel'),
                'latest_ids',
                'latest_ids.id_imovel', '=', 'imoveis.id'
            )
            ->join('imoveis_historico as h', 'h.id', '=', 'latest_ids.latest_id')
            ->where('imoveis.status', 'ativo')
            ->where('imoveis.id_estado', $estadoObj->id)
            ->with(['municipio', 'bairro', 'ultimoHistorico', 'tipoImovel']);

        if ($tipoObj) {
            $query->where('imoveis.id_tipo_imovel', $tipoObj->id);
        }
        if ($municipioObj) {
            $query->where('imoveis.id_municipio', $municipioObj->id);
        }
        if ($bairroObj) {
            $query->where('imoveis.id_bairro', $bairroObj->id);
        } elseif ($request->filled('bairros_ids')) {
            $query->whereIn('imoveis.id_bairro', (array) $request->input('bairros_ids'));
        }

        // ── 3. Filtros ────────────────────────────────────────────────────────
        $financiamentos = [];

        if ($filtroEspecial === 'financiamento') {
            $query->where(function ($q) {
                $q->where('imoveis.aceita_financ_sbpe', true)
                  ->orWhere('imoveis.aceita_financ_mcmv', true);
            });
            $financiamentos = ['sbpe'];
        } elseif ($filtroEspecial === 'desconto70') {
            $query->where('h.desconto_percentual', '>=', 70);
        } else {
            $financiamentos = (array) $request->input('financiamento', []);

            if (in_array('fgts', $financiamentos)) {
                $query->where('imoveis.aceita_fgts', 'sim');
            }
            if (in_array('sbpe', $financiamentos)) {
                $query->where('imoveis.aceita_financ_sbpe', true);
            }
            if (in_array('mcmv', $financiamentos)) {
                $query->where('imoveis.aceita_financ_mcmv', true);
            }

            if ($request->filled('quartos')) {
                $query->where('imoveis.quartos', '>=', (int) $request->input('quartos'));
            }

            if ($request->filled('preco_min')) {
                $query->where('h.valor_venda', '>=',
                    (float) str_replace(['.', ','], ['', '.'], $request->input('preco_min'))
                );
            }

            if ($request->filled('preco_max')) {
                $query->where('h.valor_venda', '<=',
                    (float) str_replace(['.', ','], ['', '.'], $request->input('preco_max'))
                );
            }

            if ($request->filled('desconto_min')) {
                $query->where('h.desconto_percentual', '>=', (float) $request->input('desconto_min'));
            }
        }

        // ── 4. Ordenação ──────────────────────────────────────────────────────
        $defaultOrdenar = $filtroEspecial === 'desconto70' ? 'desconto_desc' : 'preco_asc';
        $ordenar = $request->input('ordenar', $defaultOrdenar);

        match ($ordenar) {
            'preco_desc'    => $query->orderBy('h.valor_venda', 'desc'),
            'desconto_desc' => $query->orderBy('h.desconto_percentual', 'desc'),
            'desconto_asc'  => $query->orderBy('h.desconto_percentual', 'asc'),
            default         => $query->orderBy('h.valor_venda', 'asc'),
        };

        // ── 5. Paginação — preserva query strings nos links ───────────────────
        $imoveis = $query->paginate(9)->appends($request->query());

        // ── 6. Meta tags dinâmicas ────────────────────────────────────────────
        $localidade = implode(', ', array_filter([
            $bairroObj?->nome,
            $municipioObj?->nome,
            $estadoObj->nome,
        ]));

        $tipoLabel = $tipoObj?->nome ?? 'Imóvel';

        $descontoMedio = $imoveis->isNotEmpty()
            ? round($imoveis->avg(fn ($i) => $i->ultimoHistorico?->desconto_percentual ?? 0))
            : 0;

        if ($filtroEspecial === 'financiamento') {
            $metaTitle = 'Imóveis com Financiamento em ' . $localidade . ' | Imóveis da Caixa';
            $metaDesc  = "Compre imóvel da Caixa com FGTS e financiamento em {$localidade}. Parcelas acessíveis e até 35 anos para pagar.";
        } elseif ($filtroEspecial === 'desconto70') {
            $metaTitle = 'Imóveis com 70%+ de Desconto em ' . $localidade . ' | Imóveis da Caixa';
            $metaDesc  = "Imóveis da Caixa com mais de 70% de desconto em {$localidade}. As maiores oportunidades de lucro do mercado.";
        } else {
            $metaTitle = ucfirst($tipoLabel) . ' à venda em ' . $localidade . ' | Imóveis da Caixa';
            $metaDesc  = "Encontre {$tipoLabel} à venda em {$localidade}"
                . ($descontoMedio > 0 ? " com até {$descontoMedio}% de desconto" : '')
                . '. Financiamento FGTS e SBPE disponível.';
        }

        return view('imoveis.resultados', compact(
            'imoveis',
            'tipoObj', 'estadoObj', 'municipioObj', 'bairroObj',
            'tipo', 'estado', 'cidade', 'bairro',
            'localidade', 'financiamentos', 'ordenar',
            'metaTitle', 'metaDesc',
            'filtroEspecial',
        ));
    }
}

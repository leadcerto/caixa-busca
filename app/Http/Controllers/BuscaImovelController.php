<?php

namespace App\Http\Controllers;

use App\Models\Bairro;
use App\Models\Estado;
use App\Models\Imovel;
use App\Models\Municipio;
use App\Models\TipoImovel;
use Illuminate\Http\Request;
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

    private function buildResults(
        Request $request,
        ?string $tipo,
        string  $estado,
        ?string $cidade,
        ?string $bairro,
    ) {
        // ── 1. Resolve entidades a partir dos slugs da URL ────────────────────
        $tipoObj = null;
        if ($tipo) {
            $tipoObj = TipoImovel::where('ativo', true)
                ->get(['id', 'nome'])
                ->first(fn ($t) => Str::slug($t->nome) === $tipo);

            if (! $tipoObj) abort(404);
        }

        $estadoObj = Estado::where('uf', strtoupper($estado))->first();
        if (! $estadoObj) abort(404);

        $municipioObj = null;
        if ($cidade) {
            $municipioObj = Municipio::where('id_estado', $estadoObj->id)
                ->get(['id', 'nome'])
                ->first(fn ($m) => Str::slug($m->nome) === $cidade);

            if (! $municipioObj) abort(404);
        }

        $bairroObj = null;
        if ($bairro && $municipioObj) {
            $bairroObj = Bairro::where('id_municipio', $municipioObj->id)
                ->get(['id', 'nome'])
                ->first(fn ($b) => Str::slug($b->nome) === $bairro);

            if (! $bairroObj) abort(404);
        }

        // ── 2. Monta query base com JOIN no último histórico ──────────────────
        $query = Imovel::query()
            ->select('imoveis.*')
            ->join('imoveis_historico as h', function ($join) {
                $join->on('h.id_imovel', '=', 'imoveis.id')
                    ->whereRaw('h.id = (
                        SELECT id FROM imoveis_historico
                        WHERE id_imovel = imoveis.id
                        ORDER BY created_at DESC, id DESC
                        LIMIT 1
                    )');
            })
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

        // ── 3. Filtros de query string ────────────────────────────────────────
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

        // ── 4. Ordenação ──────────────────────────────────────────────────────
        $ordenar = $request->input('ordenar', 'preco_asc');

        match ($ordenar) {
            'preco_desc'   => $query->orderBy('h.valor_venda', 'desc'),
            'desconto_desc' => $query->orderBy('h.desconto_percentual', 'desc'),
            'desconto_asc' => $query->orderBy('h.desconto_percentual', 'asc'),
            default        => $query->orderBy('h.valor_venda', 'asc'),
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

        $metaTitle = ucfirst($tipoLabel) . ' à venda em ' . $localidade . ' | Imóveis da Caixa';
        $metaDesc  = "Encontre {$tipoLabel} à venda em {$localidade}"
            . ($descontoMedio > 0 ? " com até {$descontoMedio}% de desconto" : '')
            . '. Financiamento FGTS e SBPE disponível.';

        return view('imoveis.resultados', compact(
            'imoveis',
            'tipoObj', 'estadoObj', 'municipioObj', 'bairroObj',
            'tipo', 'estado', 'cidade', 'bairro',
            'localidade', 'financiamentos', 'ordenar',
            'metaTitle', 'metaDesc',
        ));
    }
}

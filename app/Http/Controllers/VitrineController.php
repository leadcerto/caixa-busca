<?php

namespace App\Http\Controllers;

use App\Models\CampaignPageView;
use App\Models\Estado;
use App\Models\Imovel;
use App\Models\Municipio;
use App\Models\Vitrine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VitrineController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $vitrine = Vitrine::where('slug', $slug)->where('ativa', true)->firstOrFail();
        $filtros = $vitrine->filtros;

        // ── 1. Resolve entidades a partir dos filtros ─────────────────────
        $estadoObj = null;
        if (!empty($filtros['estado'])) {
            $estadoObj = (object) (Cache::remember("estado_uf_{$filtros['estado']}", 86400, fn () =>
                Estado::where('uf', strtoupper($filtros['estado']))->first()?->toArray()
            ));
        }

        if (!$estadoObj || !isset($estadoObj->id)) {
            abort(404);
        }

        $municipioObj = null;
        if (!empty($filtros['cidade'])) {
            $municipios = collect(Cache::remember("municipios_estado_{$estadoObj->id}", 3600, fn () =>
                Municipio::where('id_estado', $estadoObj->id)->get(['id', 'nome'])->toArray()
            ))->map(fn ($m) => (object) $m);
            $municipioObj = $municipios->first(fn ($m) => Str::slug($m->nome) === $filtros['cidade']);
        }

        // ── 2. Monta query base ──────────────────────────────────────────
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
            ->with(['municipio', 'bairro', 'ultimoHistorico', 'tipoImovel', 'estado']);

        if ($municipioObj) {
            $query->where('imoveis.id_municipio', $municipioObj->id);
        }

        // Bairros específicos
        if (!empty($filtros['bairros_ids'])) {
            $query->whereIn('imoveis.id_bairro', (array) $filtros['bairros_ids']);
        }

        // Financiamento
        $financiamentos = (array) ($filtros['financiamento'] ?? []);
        if (in_array('fgts', $financiamentos)) {
            $query->where('imoveis.aceita_fgts', 'sim');
        }
        if (in_array('sbpe', $financiamentos)) {
            $query->where('imoveis.aceita_financ_sbpe', true);
        }
        if (in_array('mcmv', $financiamentos)) {
            $query->where('imoveis.aceita_financ_mcmv', true);
        }

        // Filtros extras
        if (!empty($filtros['quartos'])) {
            $query->where('imoveis.quartos', '>=', (int) $filtros['quartos']);
        }
        if (!empty($filtros['preco_min'])) {
            $query->where('h.valor_venda', '>=', (float) str_replace(['.', ','], ['', '.'], $filtros['preco_min']));
        }
        if (!empty($filtros['preco_max'])) {
            $query->where('h.valor_venda', '<=', (float) str_replace(['.', ','], ['', '.'], $filtros['preco_max']));
        }
        if (!empty($filtros['desconto_min'])) {
            $query->where('h.desconto_percentual', '>=', (float) $filtros['desconto_min']);
        }

        // ── 3. Ordenação ─────────────────────────────────────────────────
        $ordenar = $filtros['ordenar'] ?? 'preco_asc';
        match ($ordenar) {
            'preco_desc'    => $query->orderBy('h.valor_venda', 'desc'),
            'desconto_desc' => $query->orderBy('h.desconto_percentual', 'desc'),
            'desconto_asc'  => $query->orderBy('h.desconto_percentual', 'asc'),
            default         => $query->orderBy('h.valor_venda', 'asc'),
        };

        // ── 4. Paginação — 3 cards por página ────────────────────────────
        $imoveis = $query->paginate(3)->appends($request->query());

        // ── 5. Tracking — registrar acesso com UTMs ──────────────────────
        try {
            $ua = $request->userAgent() ?? '';
            $deviceType = 'desktop';
            $uaLower = strtolower($ua);
            if (str_contains($uaLower, 'tablet') || str_contains($uaLower, 'ipad')) {
                $deviceType = 'tablet';
            } elseif (str_contains($uaLower, 'mobile') || str_contains($uaLower, 'android') || str_contains($uaLower, 'iphone')) {
                $deviceType = 'mobile';
            }

            CampaignPageView::create([
                'bairro_id'    => null,
                'session_id'   => hash('sha256', $request->ip() . '|' . $ua . '|' . today()->toDateString() . config('app.key')),
                'ip_hash'      => hash('sha256', $request->ip() . config('app.key')),
                'utm_source'   => $this->clean($request->query('utm_source')),
                'utm_medium'   => $this->clean($request->query('utm_medium')),
                'utm_campaign' => $this->clean($request->query('utm_campaign')) ?? $vitrine->slug,
                'utm_content'  => $this->clean($request->query('utm_content')),
                'utm_term'     => $this->clean($request->query('utm_term')),
                'referrer'     => mb_substr($request->headers->get('referer') ?? '', 0, 500) ?: null,
                'user_agent'   => mb_substr($ua, 0, 500) ?: null,
                'device_type'  => $deviceType,
            ]);
        } catch (\Throwable) {
            // Falha silenciosa — tracking nunca interrompe o usuário
        }

        // ── 6. Meta tags ─────────────────────────────────────────────────
        $localidade = implode(', ', array_filter([
            $municipioObj?->nome,
            $estadoObj->nome ?? strtoupper($filtros['estado']),
        ]));

        $metaTitle = $vitrine->nome . ' | Imóveis da Caixa';
        $metaDesc  = "Confira imóveis da Caixa em {$localidade} com condições especiais de financiamento.";

        return view('vitrine.show', compact(
            'vitrine', 'imoveis', 'metaTitle', 'metaDesc', 'localidade',
        ));
    }

    private function clean(?string $value): ?string
    {
        if ($value === null) return null;
        $clean = strip_tags(trim($value));
        return $clean !== '' ? mb_substr($clean, 0, 200) : null;
    }
}

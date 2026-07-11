<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Vitrine extends Model
{
    protected $table = 'vitrines';

    protected $fillable = [
        'nome',
        'slug',
        'filtros',
        'url_original',
        'ativa',
    ];

    protected $casts = [
        'filtros' => 'array',
        'ativa'   => 'boolean',
    ];

    // ── Boot: gerar slug automaticamente ─────────────────────────────────

    protected static function booted(): void
    {
        static::creating(function (Vitrine $vitrine) {
            if (empty($vitrine->slug)) {
                $vitrine->slug = static::gerarSlugUnico($vitrine->nome);
            }
        });
    }

    /**
     * Gera um slug único, adicionando sufixo numérico se necessário.
     */
    public static function gerarSlugUnico(string $nome): string
    {
        $base = Str::slug($nome);
        $slug = $base;
        $i = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    // ── Helpers ──────────────────────────────────────────────────────────

    /**
     * URL pública da vitrine.
     */
    public function url(): string
    {
        return route('vitrine.show', $this->slug);
    }

    /**
     * URL com UTMs pré-configurados para uma plataforma.
     */
    public function urlComUtm(string $source, string $medium = 'cpc'): string
    {
        return $this->url() . '?' . http_build_query([
            'utm_source'   => $source,
            'utm_medium'   => $medium,
            'utm_campaign' => $this->slug,
        ]);
    }

    /**
     * Extrai filtros de uma URL de busca do site.
     * Ex: /imoveis/rj/rio-de-janeiro?ordenar=preco_asc&financiamento[0]=sbpe&bairros_ids[0]=594
     */
    public static function extrairFiltrosDaUrl(string $url): array
    {
        $parsed = parse_url($url);
        $path   = trim($parsed['path'] ?? '', '/');
        $query  = [];

        if (!empty($parsed['query'])) {
            parse_str($parsed['query'], $query);
        }

        // Extrair estado/cidade/bairro do path: imoveis/{estado}/{cidade?}/{bairro?}
        $segments = explode('/', $path);
        $filtros = [];

        // Detectar segmento "imoveis" e pegar estado/cidade/bairro
        $idx = array_search('imoveis', $segments);
        if ($idx !== false) {
            $filtros['estado'] = $segments[$idx + 1] ?? null;
            $filtros['cidade'] = $segments[$idx + 2] ?? null;
            $filtros['bairro'] = $segments[$idx + 3] ?? null;
        }

        // Filtros da query string
        if (!empty($query['bairros_ids'])) {
            $filtros['bairros_ids'] = array_map('intval', (array) $query['bairros_ids']);
        }
        if (!empty($query['financiamento'])) {
            $filtros['financiamento'] = (array) $query['financiamento'];
        }
        if (!empty($query['ordenar'])) {
            $filtros['ordenar'] = $query['ordenar'];
        }
        if (!empty($query['quartos'])) {
            $filtros['quartos'] = (int) $query['quartos'];
        }
        if (!empty($query['preco_min'])) {
            $filtros['preco_min'] = $query['preco_min'];
        }
        if (!empty($query['preco_max'])) {
            $filtros['preco_max'] = $query['preco_max'];
        }
        if (!empty($query['desconto_min'])) {
            $filtros['desconto_min'] = $query['desconto_min'];
        }

        // Remover nulls
        return array_filter($filtros, fn ($v) => $v !== null);
    }
}

<?php

namespace App\Modules\Imoveis\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Estado;
use App\Models\Imovel;
use App\Models\Municipio;
use App\Modules\Imoveis\Resources\Api\EstadoResource;
use App\Modules\Imoveis\Resources\Api\ImovelCollection;
use App\Modules\Imoveis\Resources\Api\ImovelResource;
use App\Modules\Imoveis\Resources\Api\MunicipioResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ImovelApiController extends Controller
{
    /**
     * Display a listing of properties.
     */
    public function index(Request $request): ImovelCollection
    {
        $query = Imovel::query()->where('status', 'ativo');

        // Filter by state UF (e.g. SP, RJ)
        if ($request->filled('estado')) {
            $query->whereHas('estado', fn($q) => $q->where('uf', strtoupper($request->input('estado'))));
        }

        // Filter by municipality slug or name
        if ($request->filled('municipio')) {
            $query->whereHas('municipio', fn($q) => $q
                ->where('slug', $request->input('municipio'))
                ->orWhere('nome', 'like', '%' . $request->input('municipio') . '%')
            );
        }

        // Filter by property type (name or ID)
        if ($request->filled('tipo')) {
            $tipo = $request->input('tipo');
            $query->whereHas('tipoImovel', fn($q) => is_numeric($tipo) 
                ? $q->where('id', $tipo)
                : $q->where('nome', 'like', "%{$tipo}%")
            );
        }

        // Filter by min/max price (valor_venda)
        if ($request->filled('preco_min') || $request->filled('preco_max')) {
            $query->whereHas('ultimoHistorico', function ($q) use ($request) {
                if ($request->filled('preco_min')) {
                    $q->where('valor_venda', '>=', (float) $request->input('preco_min'));
                }
                if ($request->filled('preco_max')) {
                    $q->where('valor_venda', '<=', (float) $request->input('preco_max'));
                }
            });
        }

        // Filter by features
        if ($request->filled('quartos')) {
            $query->where('quartos', '>=', (int) $request->input('quartos'));
        }
        if ($request->filled('banheiros')) {
            $query->where('banheiros', '>=', (int) $request->input('banheiros'));
        }
        if ($request->filled('garagens')) {
            $query->where('garagens', '>=', (int) $request->input('garagens'));
        }

        // Filter by areas
        if ($request->filled('area_min')) {
            $query->where('area_total', '>=', (float) $request->input('area_min'));
        }
        if ($request->filled('area_max')) {
            $query->where('area_total', '<=', (float) $request->input('area_max'));
        }

        // Load relations and paginate
        $imoveis = $query
            ->with(['estado', 'municipio', 'tipoImovel', 'bairro', 'ultimoHistorico.modalidade'])
            ->orderBy('updated_at', 'desc')
            ->paginate($request->integer('per_page', 15));

        return new ImovelCollection($imoveis);
    }

    /**
     * Display the detailed single property.
     */
    public function show(string $slug): ImovelResource
    {
        $imovel = Imovel::where('slug', $slug)
            ->where('status', 'ativo')
            ->with([
                'estado',
                'municipio',
                'tipoImovel',
                'bairro',
                'ultimoHistorico.modalidade',
                'historico' => fn($q) => $q->with('modalidade')->orderBy('created_at', 'desc')
            ])
            ->firstOrFail();

        return new ImovelResource($imovel);
    }

    /**
     * List all states with property counts.
     */
    public function estados(): AnonymousResourceCollection
    {
        $estados = Estado::withCount(['imoveis' => fn($q) => $q->where('status', 'ativo')])
            ->orderBy('nome')
            ->get();

        return EstadoResource::collection($estados);
    }

    /**
     * List municipalities, with optional state filter.
     */
    public function municipios(Request $request): AnonymousResourceCollection
    {
        $query = Municipio::query()
            ->with(['estado'])
            ->withCount(['imoveis' => fn($q) => $q->where('status', 'ativo')]);

        if ($request->filled('estado')) {
            $query->whereHas('estado', fn($q) => $q->where('uf', strtoupper($request->input('estado'))));
        }

        $municipios = $query->orderBy('nome')->get();

        return MunicipioResource::collection($municipios);
    }
}

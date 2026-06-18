<?php

namespace App\Modules\ImportacaoCSV\Http;

use App\Modules\ImportacaoCSV\Jobs\ProcessCaixaCsvJob;
use App\Modules\ImportacaoCSV\Services\CaixaCsvParserService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ImportacaoCsvController extends Controller
{
    public function show()
    {
        return view('importacao-csv.importar');
    }

    public function status()
    {
        $progresso = Cache::get(CaixaCsvParserService::PROGRESS_CACHE_KEY);
        return response()->json($progresso ?? ['status' => 'idle']);
    }

    public function reset()
    {
        Cache::forget(CaixaCsvParserService::PROGRESS_CACHE_KEY);
        return redirect()->route('admin.importar')->with('importMessage', 'success|Status resetado. Pode enviar um novo arquivo.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'csvFile' => 'required|file|extensions:csv,txt|max:102400',
        ], [
            'csvFile.required' => 'Por favor, selecione um arquivo CSV.',
            'csvFile.file'     => 'O arquivo enviado é inválido.',
            'csvFile.extensions' => 'O arquivo deve ser um .csv ou .txt.',
            'csvFile.max'      => 'O arquivo é muito grande. Limite: 100MB.',
        ]);

        try {
            $fileName  = 'caixa_import_' . now()->format('Ymd_His') . '.csv';
            $importsDir = storage_path('app/imports');

            if (!is_dir($importsDir)) {
                mkdir($importsDir, 0775, true);
            }

            $filePath = $importsDir . '/' . $fileName;
            $request->file('csvFile')->move($importsDir, $fileName);

            if (!file_exists($filePath)) {
                throw new \RuntimeException("Arquivo não foi salvo em {$filePath}. Verifique permissões.");
            }

            Log::info("IMPORTACAO: Arquivo salvo em {$filePath} (" . filesize($filePath) . " bytes)");

            // Marca imediatamente como aguardando para o painel mostrar o status
            // antes mesmo do worker pegar o job da fila
            Cache::put(CaixaCsvParserService::PROGRESS_CACHE_KEY, [
                'status'     => 'processing',
                'file'       => $request->file('csvFile')->getClientOriginalName(),
                'total'      => 0,
                'processed'  => 0,
                'inserted'   => 0,
                'skipped'    => 0,
                'started_at' => now()->toDateTimeString(),
            ], 1800);

            ProcessCaixaCsvJob::dispatch($filePath);

            Log::info("IMPORTACAO: Upload realizado — {$filePath}");

            return back()->with('importMessage', 'success|Arquivo enviado! A importação está rodando em background.');

        } catch (\Exception $e) {
            Log::error("IMPORTACAO: Falha — " . $e->getMessage());
            return back()->with('importMessage', 'error|Erro ao iniciar importação: ' . $e->getMessage());
        }
    }
}

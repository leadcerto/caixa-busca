<?php

use App\Modules\Admin\Livewire\AdminDashboard;
use App\Modules\Admin\Livewire\AdminLogin;
use App\Modules\Admin\Livewire\BairrosDossie;
use App\Modules\Admin\Livewire\IntegracaoCrm;
use App\Modules\Admin\Livewire\ImovelBuscaInterna;
use App\Modules\Admin\Livewire\WhatsappTemplates;
use App\Modules\BairrosDossie\Livewire\PaginaBairro;
use App\Modules\Leads\Livewire\GestaoLeads;
use App\Modules\Imobiliaria\Livewire\ImobiliariaLogin;
use App\Modules\ImportacaoCSV\Livewire\ImportacaoCsv;
use App\Modules\Imoveis\Livewire\ImovelSearch;
use App\Modules\Imoveis\Livewire\ImovelShow as ModularImovelShow;
use App\Modules\Imobiliaria\Livewire\PainelLeads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Público
Route::get('/', ImovelSearch::class)->name('imoveis.index');
Route::get('/buscar', fn() => redirect()->route('imoveis.index'));
Route::get('/bairros/{uf}/{municipio_slug}/{bairro_slug}', PaginaBairro::class)->name('bairro.show');
Route::get('/images/imoveis/{slug}.jpg', [App\Http\Controllers\ImovelImageController::class, 'serve'])->name('imovel.imagem');

// Rota de diagnóstico temporária segura para capturar o Erro 500 em produção
Route::get('/verificar-erro-sistema', function () {
    if (request('token') !== 'lcps1974') {
        abort(403);
    }
    $logPath = storage_path('logs/laravel.log');
    if (!file_exists($logPath)) {
        return "Arquivo de log não existe em: " . $logPath;
    }
    $lines = file($logPath);
    $lastLines = array_slice($lines, -150);
    return response(implode("", $lastLines), 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
});

// Retrocompatibilidade: URLs antigas do diagnóstico redirecionam para o painel admin protegido
Route::get('/test-log', fn() => redirect()->route('admin.diagnostico'));
Route::get('/test-log.php', fn() => redirect()->route('admin.diagnostico'));

// Admin — login/logout
Route::get('/admin/login', AdminLogin::class)->name('login')->middleware('guest');
Route::post('/admin/logout', function () {
    Auth::guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('admin.logout');

// Admin — área protegida
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/leads', GestaoLeads::class)->name('leads');
    Route::get('/imobiliarias', \App\Modules\Admin\Livewire\GestaoImobiliarias::class)->name('imobiliarias');
    Route::get('/importar', ImportacaoCsv::class)->name('importar');
    Route::get('/imoveis/busca-interna', ImovelBuscaInterna::class)->name('imoveis.busca-interna');
    Route::get('/integracao-crm', IntegracaoCrm::class)->name('crm');
    Route::get('/whatsapp-templates', WhatsappTemplates::class)->name('whatsapp');
    Route::get('/bairros-dossie', BairrosDossie::class)->name('bairros');
    Route::get('/diagnostico', function () {
        $dbStatus = 'Desconhecido';
        $dbError = null;
        try {
            \Illuminate\Support\Facades\DB::connection()->getPdo();
            $dbStatus = '✅ Conectado com Sucesso!';
        } catch (\Throwable $e) {
            $dbStatus = '❌ Erro de Conexão';
            $dbError = $e->getMessage();
        }

        $actionOutput = '';
        $action = request('action');
        if ($action) {
            try {
                if ($action === 'migrate') {
                    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                    $actionOutput = "=== php artisan migrate --force ===\n" . \Illuminate\Support\Facades\Artisan::output();
                } elseif ($action === 'clear') {
                    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
                    $actionOutput = "=== php artisan optimize:clear ===\n" . \Illuminate\Support\Facades\Artisan::output();
                } elseif ($action === 'seed') {
                    \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
                    $actionOutput = "=== php artisan db:seed --force ===\n" . \Illuminate\Support\Facades\Artisan::output();
                }
            } catch (\Throwable $e) {
                $actionOutput = "Erro ao executar ação '$action':\n" . $e->getMessage() . "\n" . $e->getTraceAsString();
            }
        }

        $logContent = '';
        $logPath = storage_path('logs/laravel.log');
        if (file_exists($logPath)) {
            $lines = file($logPath);
            $lastLines = array_slice($lines, -150);
            $logContent = implode("", $lastLines);
        } else {
            $logContent = "Arquivo laravel.log não existe em: " . $logPath;
        }

        return response('
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Console de Diagnóstico - Imóveis da Caixa</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
        <style>
            body {
                background: linear-gradient(135deg, #0b0f19 0%, #111827 100%);
                color: #f3f4f6;
                font-family: "Outfit", sans-serif;
                min-height: 100vh;
                padding: 40px 20px;
            }
            .card-premium {
                background: rgba(17, 24, 39, 0.7);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.05);
                border-radius: 20px;
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
                padding: 30px;
                margin-bottom: 30px;
            }
            h1, h2, h3, h4 { font-weight: 700; letter-spacing: -0.02em; }
            .gradient-text {
                background: linear-gradient(90deg, #0ea5e9 0%, #8b5cf6 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
            .badge-status {
                padding: 6px 14px;
                border-radius: 9999px;
                font-weight: 600;
                font-size: 0.85rem;
                display: inline-block;
            }
            .badge-success-custom {
                background: rgba(16, 185, 129, 0.15);
                color: #10b981;
                border: 1px solid rgba(16, 185, 129, 0.25);
            }
            .badge-danger-custom {
                background: rgba(239, 68, 68, 0.15);
                color: #ef4444;
                border: 1px solid rgba(239, 68, 68, 0.25);
            }
            .console-panel {
                background: #030712;
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 14px;
                padding: 20px;
                font-family: "JetBrains Mono", monospace;
                font-size: 0.82rem;
                color: #34d399;
                max-height: 400px;
                overflow-y: auto;
                white-space: pre-wrap;
                box-shadow: inset 0 2px 8px rgba(0,0,0,0.8);
            }
            .btn-action {
                border-radius: 12px;
                font-weight: 600;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                padding: 10px 18px;
                border: 1px solid rgba(255,255,255,0.1);
            }
            .btn-action:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(139, 92, 246, 0.3);
            }
            .info-label { font-weight: 600; color: #9ca3af; }
            .info-val { color: #f3f4f6; font-family: "JetBrains Mono", monospace; font-size: 0.9rem; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="gradient-text display-4">🔍 Console de Diagnóstico Premium</h1>
                <p class="text-muted">Imóveis da Caixa Production Diagnostics Panel</p>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="card-premium">
                        <h3 class="mb-4 text-info">⚙️ Informações de Infraestrutura</h3>
                        <div class="row g-3">
                            <div class="col-6"><span class="info-label">Versão PHP:</span></div>
                            <div class="col-6"><span class="info-val">' . phpversion() . '</span></div>
                            <div class="col-6"><span class="info-label">Ambiente Laravel:</span></div>
                            <div class="col-6"><span class="info-val">' . app()->environment() . '</span></div>
                            <div class="col-6"><span class="info-label">Debug Mode:</span></div>
                            <div class="col-6"><span class="info-val">' . (config('app.debug') ? '✅ Ativado' : '❌ Desativado') . '</span></div>
                            <div class="col-6"><span class="info-label">Banco de Dados:</span></div>
                            <div class="col-6">
                                <span class="badge-status ' . ($dbError ? 'badge-danger-custom' : 'badge-success-custom') . '">
                                    ' . $dbStatus . '
                                </span>
                            </div>
                        </div>
                        ' . ($dbError ? '
                        <div class="alert alert-danger mt-4" role="alert" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 12px;">
                            <strong class="text-danger">Erro de Conexão com Banco de Dados:</strong><br>
                            <code class="text-danger" style="font-size:0.8rem; word-break: break-all;">' . htmlspecialchars($dbError) . '</code>
                        </div>' : '') . '
                        <h4 class="mt-5 mb-3 text-warning">⚡ Ações de Manutenção</h4>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="?action=clear" class="btn btn-outline-info btn-action">optimize:clear</a>
                            <a href="?action=migrate" class="btn btn-outline-warning btn-action">migrate --force</a>
                            <a href="?action=seed" class="btn btn-outline-success btn-action">db:seed --force</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card-premium h-100 d-flex flex-column">
                        <h3 class="mb-4 text-purple">💻 Console Output</h3>
                        <div class="console-panel flex-grow-1" style="color: #6ee7b7;">' . ($actionOutput ?: "Pronto. Selecione uma ação para ver a saída técnica do console do Laravel...") . '</div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card-premium">
                        <h3 class="mb-4 text-danger">📋 Histórico de Logs do Laravel</h3>
                        <div class="console-panel" style="color: #cbd5e1; max-height: 450px;">' . htmlspecialchars($logContent) . '</div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
    ');
    })->name('diagnostico');
});

// Parceiro — login/logout
Route::get('/parceiro/login', ImobiliariaLogin::class)->name('imobiliaria.login')->middleware('guest:imobiliaria');
Route::post('/parceiro/logout', function () {
    Auth::guard('imobiliaria')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('imobiliaria.login');
})->name('imobiliaria.logout');

// Parceiro — área protegida
Route::middleware(['auth:imobiliaria', 'imobiliaria'])->prefix('parceiro')->name('imobiliaria.')->group(function () {
    Route::get('/painel', PainelLeads::class)->name('painel');
});

// Rota local de redirecionamento do WhatsApp para evitar bloqueio por Ad-Blockers (como uBlock, Brave Shield)
Route::get('/contato-imovel/{imovel:id}', function (\App\Models\Imovel $imovel) {
    $resolvedImob = $imovel->resolved_imobiliaria;
    if ($resolvedImob) {
        $whatsappFone = preg_replace('/\D/', '', $resolvedImob->whatsapp);
    } else {
        $whatsappFone = preg_replace('/\D/', '', config('services.whatsapp.central', env('WHATSAPP_CENTRAL', '5521997882950')));
    }
    
    // Forçar DDI do Brasil (55)
    if (strlen($whatsappFone) > 0 && !str_starts_with($whatsappFone, '55')) {
        if (strlen($whatsappFone) === 10 || strlen($whatsappFone) === 11) {
            $whatsappFone = '55' . $whatsappFone;
        }
    }
    
    $msgWhatsapp = "🎯 Olá! Entrei no site *Imóveis da Caixa* e quero mais informações sobre o imóvel nº *{$imovel->numero_original}*.";
    $whatsappUrl = 'https://api.whatsapp.com/send/?phone=' . $whatsappFone . '&text=' . urlencode($msgWhatsapp) . '&type=phone_number&app_absent=0';
    
    return redirect()->away($whatsappUrl);
})->name('imovel.whatsapp-redirect');

// Wildcard de imóvel amigável (deve ficar por último para não interceptar outras rotas)
Route::get('/{imovel:slug}', ModularImovelShow::class)->name('imovel.show');

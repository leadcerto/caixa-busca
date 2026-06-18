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
use App\Modules\ImportacaoCSV\Http\ImportacaoCsvController;
use App\Modules\Imoveis\Livewire\ImovelSearch;
use App\Modules\Imoveis\Livewire\ImovelShow as ModularImovelShow;
use App\Modules\Imobiliaria\Livewire\PainelLeads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Público
Route::get('/', ImovelSearch::class)->name('imoveis.index');
Route::get('/buscar', fn() => redirect()->route('imoveis.index'));
Route::get('/bairros/{uf}/{municipio_slug}/{bairro_slug}', PaginaBairro::class)->name('bairro.show');

// Busca com URL amigável — SEO + tráfego pago
// Landing pages filtradas para campanhas (devem vir ANTES das rotas genéricas)
Route::get('/imoveis/{estado}/{cidade}/{bairro}/financiamento',
    [\App\Http\Controllers\BuscaImovelController::class, 'comFinanciamento'])
    ->name('imoveis.busca.bairro.financiamento')
    ->where(['estado' => '[a-z]{2}', 'cidade' => '[a-z0-9-]+', 'bairro' => '[a-z0-9-]+']);

Route::get('/imoveis/{estado}/{cidade}/financiamento',
    [\App\Http\Controllers\BuscaImovelController::class, 'comFinanciamento'])
    ->name('imoveis.busca.financiamento')
    ->where(['estado' => '[a-z]{2}', 'cidade' => '[a-z0-9-]+']);

Route::get('/imoveis/{estado}/{cidade}/{bairro}/desconto-70',
    [\App\Http\Controllers\BuscaImovelController::class, 'comDesconto70'])
    ->name('imoveis.busca.bairro.desconto70')
    ->where(['estado' => '[a-z]{2}', 'cidade' => '[a-z0-9-]+', 'bairro' => '[a-z0-9-]+']);

Route::get('/imoveis/{estado}/{cidade}/desconto-70',
    [\App\Http\Controllers\BuscaImovelController::class, 'comDesconto70'])
    ->name('imoveis.busca.desconto70')
    ->where(['estado' => '[a-z]{2}', 'cidade' => '[a-z0-9-]+']);

// Sem tipo: /imoveis/{estado}/{cidade?}/{bairro?}  (estado = UF 2 letras)
Route::get('/imoveis/{estado}/{cidade?}/{bairro?}',
    [\App\Http\Controllers\BuscaImovelController::class, 'semTipo'])
    ->name('imoveis.busca.sem-tipo')
    ->where([
        'estado' => '[a-z]{2}',
        'cidade' => '[a-z0-9-]+',
        'bairro' => '[a-z0-9-]+',
    ]);

// Com tipo: /imoveis/{tipo}/{estado}/{cidade?}/{bairro?}
Route::get('/imoveis/{tipo}/{estado}/{cidade?}/{bairro?}',
    [\App\Http\Controllers\BuscaImovelController::class, 'index'])
    ->name('imoveis.busca')
    ->where([
        'tipo'   => '[a-z0-9-]+',
        'estado' => '[a-z]{2}',
        'cidade' => '[a-z0-9-]+',
        'bairro' => '[a-z0-9-]+',
    ]);
Route::get('/images/imoveis/{slug}.jpg', [App\Http\Controllers\ImovelImageController::class, 'serve'])->name('imovel.imagem');

// Rota de diagnóstico temporária segura para capturar o Erro 500 em produção e configurar banco
Route::match(['GET', 'POST'], '/verificar-erro-sistema', function () {
    $token = env('DIAGNOSTICO_TOKEN');
    if (!$token || request('token') !== $token) {
        abort(403);
    }
    
    $envPath = base_path('.env');
    $envExists = file_exists($envPath);
    
    // Se for um envio de POST, atualiza as credenciais
    if (request()->isMethod('POST')) {
        $host = request('db_host', '127.0.0.1');
        $port = request('db_port', '3306');
        $database = request('db_database', '');
        $username = request('db_username', '');
        $password = request('db_password', '');
        
        if ($envExists) {
            $lines = file($envPath, FILE_IGNORE_NEW_LINES);
            $newLines = [];
            $dbKeys = [
                'DB_HOST' => $host,
                'DB_PORT' => $port,
                'DB_DATABASE' => $database,
                'DB_USERNAME' => $username,
                'DB_PASSWORD' => $password,
            ];
            $replaced = [];
            foreach ($lines as $line) {
                $matched = false;
                foreach ($dbKeys as $key => $val) {
                    if (str_starts_with(trim($line), $key . '=')) {
                        $newLines[] = "{$key}={$val}";
                        $replaced[$key] = true;
                        $matched = true;
                        break;
                    }
                }
                if (!$matched) {
                    $newLines[] = $line;
                }
            }
            foreach ($dbKeys as $key => $val) {
                if (!isset($replaced[$key])) {
                    $newLines[] = "{$key}={$val}";
                }
            }
            file_put_contents($envPath, implode("\n", $newLines) . "\n");
            
            // Limpa o cache de configurações na Hostinger
            try {
                \Illuminate\Support\Facades\Artisan::call('optimize:clear');
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                $message = "Configurações gravadas com sucesso! Caches limpos e banco de dados migrado para a versão mais recente.";
                $success = true;
            } catch (\Throwable $e) {
                $message = "Configurações gravadas, mas ocorreu um erro no artisan: " . $e->getMessage();
                $success = false;
            }
        } else {
            $message = "Erro: Arquivo .env não encontrado no servidor.";
            $success = false;
        }
        
        return "
        <!DOCTYPE html>
        <html lang='pt-BR'>
        <head>
            <meta charset='UTF-8'>
            <title>Resultado da Configuração</title>
            <link href='https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&display=swap' rel='stylesheet'>
            <style>
                body { font-family: 'Outfit', sans-serif; background: #0f172a; color: #f8fafc; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; }
                .card { background: #1e293b; padding: 40px; border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); text-align: center; max-width: 500px; border: 1px solid " . ($success ? '#10b981' : '#ef4444') . "; }
                h1 { color: " . ($success ? '#10b981' : '#ef4444') . "; margin-bottom: 20px; }
                p { line-height: 1.6; color: #cbd5e1; }
                .btn { display: inline-block; margin-top: 30px; padding: 12px 24px; background: #005CA9; color: #fff; text-decoration: none; border-radius: 12px; font-weight: bold; }
            </style>
        </head>
        <body>
            <div class='card'>
                <h1>" . ($success ? '✅ Sucesso!' : '⚠️ Atenção!') . "</h1>
                <p>{$message}</p>
                <a href='https://venda.imoveisdacaixa.com.br' class='btn'>Acessar o Site</a>
            </div>
        </body>
        </html>
        ";
    }
    // Exibe o painel HTML de preenchimento seguro das credenciais com painel de diagnósticos integrado
    $envWritable = is_writable($envPath) ? "<span style='color: #10b981;'>SIM</span>" : "<span style='color: #ef4444;'>NÃO</span>";
    $envContentSnippet = '';
    if ($envExists) {
        $lines = file($envPath);
        foreach ($lines as $line) {
            if (str_starts_with(trim($line), 'DB_')) {
                if (str_contains($line, 'PASSWORD')) {
                    $envContentSnippet .= "DB_PASSWORD=***\n";
                } else {
                    $envContentSnippet .= $line;
                }
            }
        }
    } else {
        $envContentSnippet = "Arquivo .env não encontrado!";
    }
    
    $dbTestResult = '';
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $dbTestResult = "<span style='color: #10b981; font-weight: bold;'>✅ Conexão OK! Banco de dados conectado com sucesso!</span>";
        
        // Se solicitado via URL, executa a migração
        if (request('migrate') === 'true') {
            try {
                \Illuminate\Support\Facades\Artisan::call('optimize:clear');
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                $dbTestResult .= "<br><span style='color: #38bdf8; font-weight: bold;'>⚡ php artisan migrate executado com sucesso!</span>";
            } catch (\Throwable $e) {
                $dbTestResult .= "<br><span style='color: #ef4444; font-weight: bold;'>⚠️ Erro ao rodar migrate: " . htmlspecialchars($e->getMessage()) . "</span>";
            }
        }

        // Se solicitado via URL, executa o seed
        if (request('seed') === 'true') {
            try {
                \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
                $dbTestResult .= "<br><span style='color: #10b981; font-weight: bold;'>🌱 php artisan db:seed executado com sucesso!</span>";
            } catch (\Throwable $e) {
                $dbTestResult .= "<br><span style='color: #ef4444; font-weight: bold;'>⚠️ Erro ao rodar seed: " . htmlspecialchars($e->getMessage()) . "</span>";
            }
        }

        // Se solicitado via URL, executa a fila uma vez
        if (request('work_queue') === 'true') {
            try {
                \Illuminate\Support\Facades\Artisan::call('queue:work', ['--once' => true, '--stop-when-empty' => true]);
                $dbTestResult .= "<br><span style='color: #10b981; font-weight: bold;'>⚙️ Fila processada (queue:work --once) executado com sucesso!</span>";
            } catch (\Throwable $e) {
                $dbTestResult .= "<br><span style='color: #ef4444; font-weight: bold;'>⚠️ Erro ao processar fila: " . htmlspecialchars($e->getMessage()) . "</span>";
            }
        }
    } catch (\Throwable $e) {
        $dbTestResult = "<span style='color: #ef4444; font-weight: bold;'>❌ Erro de Conexão: " . htmlspecialchars($e->getMessage()) . "</span>";
    }

    $estadosCount = 0;
    $municipiosCount = 0;
    $bairrosCount = 0;
    $imoveisCount = 0;
    $jobsCount = 0;
    $failedJobsCount = 0;
    $queueConnection = env('QUEUE_CONNECTION', 'database');
    try {
        $estadosCount = \App\Models\Estado::count();
        $municipiosCount = \App\Models\Municipio::count();
        $bairrosCount = \App\Models\Bairro::count();
        $imoveisCount = \App\Models\Imovel::count();
        $jobsCount = \Illuminate\Support\Facades\DB::table('jobs')->count();
        $failedJobsCount = \Illuminate\Support\Facades\DB::table('failed_jobs')->count();
    } catch (\Throwable $e) {
        // Ignora caso tabelas estejam vazias/ausentes
    }
    
    $logPath = storage_path('logs/laravel.log');
    $recentLogs = 'Nenhum log encontrado no servidor.';
    if (file_exists($logPath)) {
        $logLines = file($logPath);
        $recentLogs = implode("", array_slice($logLines, -150));
    }
    
    return "
    <!DOCTYPE html>
    <html lang='pt-BR'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Diagnóstico & Configurador Seguro</title>
        <link href='https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&family=JetBrains+Mono:wght@400;700&display=swap' rel='stylesheet'>
        <style>
            body { font-family: 'Outfit', sans-serif; background: linear-gradient(135deg, #0b0f19 0%, #020617 100%); color: #f8fafc; min-height: 100vh; margin: 0; padding: 40px 20px; box-sizing: border-box; }
            .grid { display: grid; grid-template-columns: 1fr; gap: 30px; max-width: 1200px; margin: 0 auto; }
            @media (min-width: 992px) { .grid { grid-template-columns: 450px 1fr; } }
            .card { background: rgba(30, 41, 59, 0.45); backdrop-filter: blur(16px); border: 1px solid rgba(255,255,255,0.05); padding: 35px; border-radius: 24px; box-shadow: 0 20px 50px rgba(0,0,0,0.5); }
            h1 { font-size: 22px; font-weight: 700; color: #38bdf8; margin-top: 0; margin-bottom: 5px; }
            h2 { font-size: 18px; font-weight: 700; color: #f43f5e; margin-top: 0; margin-bottom: 20px; }
            p.subtitle { color: #94a3b8; font-size: 13px; margin-top: 0; margin-bottom: 25px; }
            .form-group { margin-bottom: 18px; }
            label { display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; margin-bottom: 6px; }
            input { width: 100%; box-sizing: border-box; background: #0f172a; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px 16px; color: #fff; font-size: 14px; transition: all 0.3s; }
            input:focus { border-color: #38bdf8; outline: none; box-shadow: 0 0 10px rgba(56, 189, 248, 0.2); }
            .btn { width: 100%; background: #005CA9; color: #fff; border: none; border-radius: 12px; padding: 14px; font-size: 15px; font-weight: bold; cursor: pointer; transition: all 0.3s; margin-top: 10px; }
            .btn:hover { background: #004b87; transform: translateY(-1px); }
            .btn:active { transform: translateY(0); }
            .mono { font-family: 'JetBrains Mono', monospace; font-size: 12px; background: #030712; border: 1px solid rgba(255,255,255,0.08); padding: 15px; border-radius: 12px; overflow-x: auto; white-space: pre-wrap; color: #34d399; }
            .logs-panel { color: #cbd5e1; max-height: 250px; overflow-y: auto; }
            .info-item { display: flex; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.05); padding: 10px 0; font-size: 14px; }
            .info-item:last-child { border-bottom: none; }
            .label-info { color: #94a3b8; font-weight: 500; }
            .val-info { font-family: 'JetBrains Mono', monospace; color: #e2e8f0; }
        </style>
    </head>
    <body>
        <div class='grid'>
            <!-- Coluna Esquerda: Formulário -->
            <div class='card'>
                <h1>⚙️ Banco de Dados</h1>
                <p class='subtitle'>Configure os dados do MySQL Hostinger</p>
                <form method='POST'>
                    <input type='hidden' name='_token' value='" . csrf_token() . "'>
                    <div class='form-group'>
                        <label>MySQL Host</label>
                        <input type='text' name='db_host' value='127.0.0.1' required>
                    </div>
                    <div class='form-group'>
                        <label>MySQL Port</label>
                        <input type='text' name='db_port' value='3306' required>
                    </div>
                    <div class='form-group'>
                        <label>Nome do Banco de Dados (DB_DATABASE)</label>
                        <input type='text' name='db_database' placeholder='Ex: u541302702_venda' required>
                    </div>
                    <div class='form-group'>
                        <label>Usuário do Banco (DB_USERNAME)</label>
                        <input type='text' name='db_username' placeholder='Ex: u541302702_user' required>
                    </div>
                    <div class='form-group'>
                        <label>Senha do Banco (DB_PASSWORD)</label>
                        <input type='password' name='db_password' placeholder='Sua senha do MySQL' required>
                    </div>
                    <button type='submit' class='btn'>Salvar Configuração</button>
                </form>
            </div>
 
            <!-- Coluna Direita: Diagnóstico e Logs -->
            <div class='card' style='display: flex; flex-direction: column; gap: 20px;'>
                <div>
                    <h2>🔍 Diagnóstico da Aplicação</h2>
                    <div class='info-item'>
                        <span class='label-info'>Status de Conexão com o Banco:</span>
                        <span class='val-info'>{$dbTestResult}</span>
                    </div>
                    <div class='info-item'>
                        <span class='label-info'>Arquivo .env Existe?</span>
                        <span class='val-info'>" . ($envExists ? "<span style='color: #10b981;'>SIM</span>" : "<span style='color: #ef4444;'>NÃO</span>") . "</span>
                    </div>
                    <div class='info-item'>
                        <span class='label-info'>Arquivo .env é Gravável?</span>
                        <span class='val-info'>{$envWritable}</span>
                    </div>
                    <div class='info-item'>
                        <span class='label-info'>Conexão de Fila (QUEUE_CONNECTION):</span>
                        <span class='val-info' style='color: #eab308; font-weight: bold;'>{$queueConnection}</span>
                    </div>
                    <div class='info-item'>
                        <span class='label-info'>Estados no Banco:</span>
                        <span class='val-info'>{$estadosCount}</span>
                    </div>
                    <div class='info-item'>
                        <span class='label-info'>Cidades no Banco:</span>
                        <span class='val-info'>{$municipiosCount}</span>
                    </div>
                    <div class='info-item'>
                        <span class='label-info'>Bairros no Banco:</span>
                        <span class='val-info'>{$bairrosCount}</span>
                    </div>
                    <div class='info-item'>
                        <span class='label-info'>Imóveis no Banco:</span>
                        <span class='val-info' style='color: #38bdf8; font-weight: bold;'>{$imoveisCount}</span>
                    </div>
                    <div class='info-item'>
                        <span class='label-info'>Trabalhos na Fila (jobs):</span>
                        <span class='val-info' style='color: #eab308; font-weight: bold;'>{$jobsCount}</span>
                    </div>
                    <div class='info-item'>
                        <span class='label-info'>Trabalhos Falhados (failed_jobs):</span>
                        <span class='val-info' style='color: #ef4444;'>{$failedJobsCount}</span>
                    </div>
                    <div class='info-item' style='margin-top: 15px; border: none; padding: 0; display: flex; flex-wrap: wrap; gap: 10px;'>
                        <a href='?token=" . request('token') . "&migrate=true' class='btn' style='margin-top: 0; padding: 10px; font-size: 13px; background: #eab308; color: #000; text-align: center; text-decoration: none; flex: 1; min-width: 120px;'>⚡ Migrar Banco & Limpar Cache</a>
                        <a href='?token=" . request('token') . "&seed=true' class='btn' style='margin-top: 0; padding: 10px; font-size: 13px; background: #10b981; color: #fff; text-align: center; text-decoration: none; flex: 1; min-width: 120px;'>🌱 Popular Dados (Seed)</a>
                        <a href='?token=" . request('token') . "&work_queue=true' class='btn' style='margin-top: 0; padding: 10px; font-size: 13px; background: #3b82f6; color: #fff; text-align: center; text-decoration: none; flex: 1; min-width: 120px;'>⚙️ Processar Fila</a>
                    </div>
                </div>

                <div>
                    <label>Variáveis de Conexão no .env Ativo</label>
                    <div class='mono'>" . htmlspecialchars($envContentSnippet) . "</div>
                </div>

                <div style='flex-grow: 1; display: flex; flex-direction: column;'>
                    <label>Últimos Logs do Servidor (laravel.log)</label>
                    <div class='mono logs-panel flex-grow-1'>" . htmlspecialchars($recentLogs) . "</div>
                </div>
            </div>
        </div>
    </body>
    </html>
    ";
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
    Route::get('/importar', [ImportacaoCsvController::class, 'show'])->name('importar');
    Route::post('/importar', [ImportacaoCsvController::class, 'store'])->name('importar.store');
    Route::get('/imoveis/busca-interna', ImovelBuscaInterna::class)->name('imoveis.busca-interna');
    Route::get('/integracao-crm', IntegracaoCrm::class)->name('crm');
    Route::get('/whatsapp-templates', WhatsappTemplates::class)->name('whatsapp');
    Route::get('/bairros-conteudo', BairrosDossie::class)->name('bairros');
    Route::redirect('/bairros-dossie', '/admin/bairros-conteudo');
    Route::match(['GET', 'POST'], '/diagnostico', function () {
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
                } elseif ($action === 'storage_link') {
                    \Illuminate\Support\Facades\Artisan::call('storage:link', ['--force' => true]);
                    $actionOutput = "=== php artisan storage:link --force ===\n" . \Illuminate\Support\Facades\Artisan::output();

                    // No Hostinger, public_html/venda/public/ é o web root real, diferente de laravel-app/public/.
                    // Precisa criar o symlink storage/ também no web root para imagens carregarem.
                    $storageTarget = storage_path('app/public');
                    $scriptFile = $_SERVER['SCRIPT_FILENAME'] ?? '';
                    $webRoot = $scriptFile ? (dirname(realpath($scriptFile) ?: $scriptFile)) : '';
                    $webStorageLink = rtrim($webRoot, '/') . '/storage';
                    $actionOutput .= "\n\n=== Symlink no web root ===";
                    $actionOutput .= "\nWeb root detectado: $webRoot";
                    $actionOutput .= "\nAlvo: $storageTarget";
                    if ($webRoot) {
                        if (is_link($webStorageLink)) {
                            @unlink($webStorageLink);
                        } elseif (is_dir($webStorageLink)) {
                            @shell_exec('rm -rf ' . escapeshellarg($webStorageLink));
                            if (is_dir($webStorageLink)) {
                                @rmdir($webStorageLink);
                            }
                        }
                        $ok = @symlink($storageTarget, $webStorageLink);
                        $actionOutput .= "\nResultado: " . ($ok ? '✅ Symlink criado' : '❌ Falha — tente via SSH: ln -sfn ' . $storageTarget . ' ' . $webStorageLink);
                    } else {
                        $actionOutput .= "\n⚠️ Não foi possível detectar o web root via SCRIPT_FILENAME.";
                    }

                    $storagePath = public_path('storage');
                    $actionOutput .= "\n\n=== Status ===";
                    $actionOutput .= "\nlaravel-app/public/storage: " . (is_link($storagePath) ? '✅ → ' . readlink($storagePath) : '❌ NÃO existe');
                    $actionOutput .= "\nweb root/storage: " . (is_link($webStorageLink) ? '✅ → ' . readlink($webStorageLink) : (file_exists($webStorageLink) ? '⚠️ diretório (não symlink)' : '❌ NÃO existe'));
                    $actionOutput .= "\nstorage/app/public: " . (file_exists($storageTarget) ? '✅ existe' : '❌ ausente');
                } elseif ($action === 'seed') {
                    \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
                    $actionOutput = "=== php artisan db:seed --force ===\n" . \Illuminate\Support\Facades\Artisan::output();
                } elseif ($action === 'queue') {
                    \Illuminate\Support\Facades\Artisan::call('queue:work', [
                        '--once'            => true,
                        '--stop-when-empty' => true,
                        '--tries'           => 3,
                    ]);
                    $actionOutput = "=== php artisan queue:work --once --stop-when-empty ===\n" . \Illuminate\Support\Facades\Artisan::output();
                    $actionOutput .= "\nJobs restantes na fila: " . \Illuminate\Support\Facades\DB::table('jobs')->count();
                } elseif ($action === 'queue_retry') {
                    \Illuminate\Support\Facades\Artisan::call('queue:retry', ['id' => ['all']]);
                    $actionOutput = "=== php artisan queue:retry all ===\n" . \Illuminate\Support\Facades\Artisan::output();
                } elseif ($action === 'queue_flush') {
                    \Illuminate\Support\Facades\Artisan::call('queue:flush');
                    $actionOutput = "=== php artisan queue:flush (limpa failed_jobs) ===\n" . \Illuminate\Support\Facades\Artisan::output();
                } elseif ($action === 'delete_imovel') {
                    $numero = request('numero');
                    if ($numero) {
                        $imovel = \App\Models\Imovel::where('numero_original', $numero)->first();
                        if ($imovel) {
                            $id    = $imovel->id;
                            $slug  = $imovel->slug;
                            $imovel->delete();
                            $actionOutput = "=== Imóvel excluído (soft delete) ===\nNúmero original : {$numero}\nID no banco     : {$id}\nSlug            : {$slug}\nResultado       : ✅ Excluído com sucesso";
                        } else {
                            $actionOutput = "=== Imóvel não encontrado ===\nNúmero original buscado: {$numero}\nNenhum registro encontrado.";
                        }
                    } else {
                        $actionOutput = "Parâmetro 'numero' não informado na URL.";
                    }
                } elseif ($action === 'bairros_preview') {
                    \Illuminate\Support\Facades\Artisan::call('bairros:limpar', ['--preview' => true]);
                    $actionOutput = "=== PRÉVIA — nenhuma alteração feita ===\n" . \Illuminate\Support\Facades\Artisan::output();
                } elseif ($action === 'bairros_executar') {
                    \Illuminate\Support\Facades\Artisan::call('bairros:limpar');
                    $actionOutput = "=== Limpeza de Bairros Executada ===\n" . \Illuminate\Support\Facades\Artisan::output();
                    \Illuminate\Support\Facades\Cache::forget('dropdown_estados_com_imoveis');
                    \Illuminate\Support\Facades\Cache::forget('dropdown_tipos_imoveis');
                } elseif ($action === 'check_openrouter') {
                    $key = config('services.openrouter.key');
                    $model = config('services.openrouter.model');
                    $envKey = env('OPENROUTER_API_KEY');
                    $actionOutput  = "=== OpenRouter Config ===\n";
                    $actionOutput .= "config('services.openrouter.key'): " . (empty($key) ? '❌ VAZIO' : '✅ ' . substr($key, 0, 20) . '...') . "\n";
                    $actionOutput .= "config('services.openrouter.model'): " . ($model ?: '❌ VAZIO') . "\n";
                    $actionOutput .= "env('OPENROUTER_API_KEY'): " . (empty($envKey) ? '❌ VAZIO' : '✅ ' . substr($envKey, 0, 20) . '...') . "\n";
                } elseif ($action === 'ping_google') {
                    $result = \App\Services\GooglePingService::pingSitemap();
                    $actionOutput  = "=== Ping Google Sitemap ===\n";
                    $actionOutput .= $result
                        ? "✅ Google notificado com sucesso!\nURL: https://www.google.com/ping?sitemap=https://venda.imoveisdacaixa.com.br/sitemap.xml"
                        : "⚠️ Ping enviado mas resposta não foi 200. Verifique os logs.";
                } elseif ($action === 'show_env') {
                    $envPath = base_path('.env');
                    $actionOutput  = "=== Arquivo .env ===\n";
                    $actionOutput .= "Caminho: {$envPath}\n";
                    $actionOutput .= "Existe: " . (file_exists($envPath) ? '✅ SIM' : '❌ NÃO') . "\n";
                    $actionOutput .= "Gravável: " . (is_writable($envPath) ? '✅ SIM' : '❌ NÃO') . "\n";
                    $actionOutput .= "Tamanho: " . (file_exists($envPath) ? filesize($envPath) . ' bytes' : 'N/A') . "\n";
                    $actionOutput .= "Modificado em: " . (file_exists($envPath) ? date('d/m/Y H:i:s', filemtime($envPath)) : 'N/A') . "\n\n";
                    if (file_exists($envPath)) {
                        $lines = file($envPath, FILE_IGNORE_NEW_LINES);
                        $actionOutput .= "=== Linhas com OPENROUTER ===\n";
                        $found = false;
                        foreach ($lines as $num => $line) {
                            if (stripos($line, 'openrouter') !== false) {
                                $safeVal = $line;
                                if (preg_match('/^OPENROUTER_API_KEY=(.+)$/', $line, $m)) {
                                    $v = trim($m[1]);
                                    $safeVal = 'OPENROUTER_API_KEY=' . (strlen($v) > 8 ? substr($v, 0, 12) . '...' . substr($v, -4) : ($v ?: '(vazio)'));
                                }
                                $actionOutput .= "Linha " . ($num + 1) . ": {$safeVal}\n";
                                $found = true;
                            }
                        }
                        if (!$found) {
                            $actionOutput .= "⚠️ NENHUMA linha com OPENROUTER encontrada no arquivo!\n";
                            $actionOutput .= "Últimas 5 linhas do arquivo:\n";
                            $tail = array_slice($lines, -5);
                            foreach ($tail as $i => $l) {
                                $actionOutput .= "  " . (count($lines) - 5 + $i + 1) . ": {$l}\n";
                            }
                        }
                    }
                } elseif ($action === 'write_openrouter_key') {
                    $newKey   = trim(request('openrouter_key', ''));
                    $newModel = trim(request('openrouter_model', 'google/gemma-4-31b-it:free'));
                    if (empty($newKey)) {
                        $actionOutput = "❌ Chave não informada. Use o formulário abaixo.";
                    } else {
                        $envPath = base_path('.env');
                        $content = file_exists($envPath) ? file_get_contents($envPath) : '';
                        $content = preg_replace('/^OPENROUTER_API_KEY=.*$/m', '', $content);
                        $content = preg_replace('/^OPENROUTER_MODEL=.*$/m', '', $content);
                        $content = rtrim($content) . "\n\nOPENROUTER_API_KEY={$newKey}\nOPENROUTER_MODEL={$newModel}\n";
                        file_put_contents($envPath, $content);
                        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
                        $actionOutput  = "=== Chave escrita no .env ===\n";
                        $actionOutput .= "OPENROUTER_API_KEY: ✅ gravada (" . substr($newKey, 0, 12) . "...)\n";
                        $actionOutput .= "OPENROUTER_MODEL: {$newModel}\n\n";
                        $actionOutput .= \Illuminate\Support\Facades\Artisan::output();
                        $check = file_get_contents($envPath);
                        $actionOutput .= "\nVerificação: " . (str_contains($check, $newKey) ? '✅ Chave encontrada no arquivo' : '❌ Chave NÃO encontrada — verifique permissões');
                    }
                }
            } catch (\Throwable $e) {
                $actionOutput = "Erro ao executar ação '$action':\n" . $e->getMessage() . "\n" . $e->getTraceAsString();
            }
        }

        $jobsCount = 0;
        $failedJobsCount = 0;
        try {
            $jobsCount = \Illuminate\Support\Facades\DB::table('jobs')->count();
            $failedJobsCount = \Illuminate\Support\Facades\DB::table('failed_jobs')->count();
        } catch (\Throwable) {}

        $scheduleLastRun = \Illuminate\Support\Facades\Cache::get('schedule_last_run');
        $scheduleStatus = $scheduleLastRun
            ? '✅ ' . $scheduleLastRun
            : '❌ Nunca rodou (cron não configurado ou aguardando 1º minuto)';

        $logContent = '';
        $errorLines = '';
        $logPath = storage_path('logs/laravel.log');
        if (file_exists($logPath)) {
            $lines = file($logPath);
            $lastLines = array_slice($lines, -300);
            $logContent = implode("", $lastLines);
            // Filtra apenas linhas de erro e as 3 linhas seguintes (contexto)
            $errorBuffer = [];
            foreach ($lastLines as $i => $line) {
                if (str_contains($line, '.ERROR') || str_contains($line, 'BairrosDossie') || str_contains($line, 'OpenRouter')) {
                    $errorBuffer[] = rtrim($line);
                    if (isset($lastLines[$i + 1])) $errorBuffer[] = rtrim($lastLines[$i + 1]);
                    if (isset($lastLines[$i + 2])) $errorBuffer[] = rtrim($lastLines[$i + 2]);
                    $errorBuffer[] = '---';
                }
            }
            $errorLines = implode("\n", array_unique($errorBuffer)) ?: 'Nenhum erro encontrado nas últimas 300 linhas.';
        } else {
            $logContent = "Arquivo laravel.log não existe em: " . $logPath;
            $errorLines = $logContent;
        }

        return view('admin.diagnostico', compact(
            'dbStatus', 'dbError', 'actionOutput',
            'jobsCount', 'failedJobsCount', 'scheduleStatus', 'logContent', 'errorLines'
        ));
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
    \Illuminate\Support\Facades\DB::table('imoveis')->where('id', $imovel->id)->increment('whatsapp_clicks');

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

// Sitemap dinâmico
Route::get('/sitemap.xml', function () {
    $imoveis = \App\Models\Imovel::where('status', 'ativo')
        ->select('slug', 'updated_at')
        ->orderBy('updated_at', 'desc')
        ->get();

    $bairros = \App\Models\Bairro::where('ia_status', 'gerado')
        ->whereNotNull('slug')
        ->with(['municipio.estado'])
        ->select('id', 'slug', 'id_municipio', 'ia_gerado_em', 'updated_at')
        ->get();

    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    // Homepage
    $xml .= '<url>';
    $xml .= '<loc>https://venda.imoveisdacaixa.com.br/</loc>';
    $xml .= '<changefreq>daily</changefreq>';
    $xml .= '<priority>1.0</priority>';
    $xml .= '</url>';

    // Páginas de estado (/imoveis/rj, /imoveis/sp, ...)
    $ufsComImoveis = \Illuminate\Support\Facades\DB::table('imoveis')
        ->join('estados', 'estados.id', '=', 'imoveis.id_estado')
        ->where('imoveis.status', 'ativo')
        ->whereNull('imoveis.deleted_at')
        ->select('estados.uf')
        ->distinct()
        ->pluck('uf');

    foreach ($ufsComImoveis as $uf) {
        $xml .= '<url>';
        $xml .= '<loc>https://venda.imoveisdacaixa.com.br/imoveis/' . e(strtolower($uf)) . '</loc>';
        $xml .= '<changefreq>daily</changefreq>';
        $xml .= '<priority>0.9</priority>';
        $xml .= '</url>';
    }

    // Páginas de cidade (/imoveis/rj/niteroi, ...)
    $municipiosComImoveis = \Illuminate\Support\Facades\DB::table('imoveis')
        ->join('municipios', 'municipios.id', '=', 'imoveis.id_municipio')
        ->join('estados', 'estados.id', '=', 'imoveis.id_estado')
        ->where('imoveis.status', 'ativo')
        ->whereNull('imoveis.deleted_at')
        ->select('estados.uf', 'municipios.nome')
        ->distinct()
        ->get();

    foreach ($municipiosComImoveis as $row) {
        $uf = strtolower($row->uf);
        $cidadeSlug = \Illuminate\Support\Str::slug($row->nome);
        if (!$uf || !$cidadeSlug) continue;
        $xml .= '<url>';
        $xml .= '<loc>https://venda.imoveisdacaixa.com.br/imoveis/' . e($uf) . '/' . e($cidadeSlug) . '</loc>';
        $xml .= '<changefreq>daily</changefreq>';
        $xml .= '<priority>0.85</priority>';
        $xml .= '</url>';
    }

    // Bairros com conteúdo IA gerado
    foreach ($bairros as $bairro) {
        $uf = strtolower($bairro->municipio?->estado?->uf ?? '');
        $municipioSlug = $bairro->municipio?->slug ?? '';
        if (!$uf || !$municipioSlug || !$bairro->slug) continue;
        $lastmod = ($bairro->ia_gerado_em ?? $bairro->updated_at)?->toAtomString();
        $xml .= '<url>';
        $xml .= '<loc>https://venda.imoveisdacaixa.com.br/bairros/' . e($uf) . '/' . e($municipioSlug) . '/' . e($bairro->slug) . '</loc>';
        if ($lastmod) $xml .= '<lastmod>' . $lastmod . '</lastmod>';
        $xml .= '<changefreq>monthly</changefreq>';
        $xml .= '<priority>0.7</priority>';
        $xml .= '</url>';
    }

    // Imóveis ativos
    foreach ($imoveis as $imovel) {
        $xml .= '<url>';
        $xml .= '<loc>https://venda.imoveisdacaixa.com.br/' . e($imovel->slug) . '</loc>';
        $xml .= '<lastmod>' . $imovel->updated_at->toAtomString() . '</lastmod>';
        $xml .= '<changefreq>weekly</changefreq>';
        $xml .= '<priority>0.8</priority>';
        $xml .= '</url>';
    }

    $xml .= '</urlset>';

    return response($xml, 200)->header('Content-Type', 'application/xml');
})->name('sitemap');

// Redireciona URLs de dois segmentos /{slug}/{extra} → /{slug} com 301
// Ex: /{slug}/{hdnimovel} vindo de links externos ou do Caixa. Sem isso, o Hostinger retorna 500.
Route::get('/{slug}/{extra}', function (string $slug) {
    $imovel = \App\Models\Imovel::where('slug', $slug)->first();
    abort_unless($imovel, 404);
    return redirect('/' . $imovel->slug, 301);
})->where('extra', '[^/]+');

// Wildcard de imóvel amigável (deve ficar por último para não interceptar outras rotas)
Route::get('/{imovel:slug}', ModularImovelShow::class)->name('imovel.show');
